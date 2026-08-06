const fs = require('fs');
const https = require('https');

function httpsGet(url) {
    return new Promise((resolve) => {
        const req = https.get(url, (res) => {
            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                try { resolve(JSON.parse(data)); }
                catch { resolve(null); }
            });
        });
        req.on('error', () => resolve(null));
        req.setTimeout(30000, () => { req.destroy(); resolve(null); });
    });
}

// Search by name in Vietnam bounding box, admin level 7 or 8
async function fetchByName(name) {
    // Vietnam bbox: 102,8,110,24
    // Ha Nam bbox approx: 105.7,20.3,106.3,20.8
    const query = `[out:json][timeout:30];
(
  relation["name"="${name}"]["admin_level"="8"](20.3,105.7,20.8,106.3);
  relation["name"="${name}"]["admin_level"="7"](20.3,105.7,20.8,106.3);
  relation["name"="${name}"]["admin_level"="6"](20.3,105.7,20.8,106.3);
);
out geom;`;
    const url = `https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`;
    return httpsGet(url);
}

function ptEq(a, b) {
    return Math.abs(a[0]-b[0]) < 1e-6 && Math.abs(a[1]-b[1]) < 1e-6;
}

function mergeWayRings(ways) {
    if (!ways.length) return [];
    const segs = ways.map(w => [...w]);
    const used = new Set();
    const rings = [];
    for (let s = 0; s < segs.length; s++) {
        if (used.has(s)) continue;
        let ring = [...segs[s]];
        used.add(s);
        let ext = true;
        while (ext && !ptEq(ring[0], ring[ring.length-1])) {
            ext = false;
            for (let i = 0; i < segs.length; i++) {
                if (used.has(i)) continue;
                const seg = segs[i];
                if (ptEq(ring[ring.length-1], seg[0])) {
                    ring = ring.concat(seg.slice(1));
                    used.add(i); ext = true; break;
                } else if (ptEq(ring[ring.length-1], seg[seg.length-1])) {
                    ring = ring.concat([...seg].reverse().slice(1));
                    used.add(i); ext = true; break;
                }
            }
        }
        if (!ptEq(ring[0], ring[ring.length-1])) ring.push(ring[0]);
        if (ring.length >= 4) rings.push(ring);
    }
    return rings;
}

function buildGeoJSON(el) {
    if (!el?.members) return null;
    const outers = el.members
        .filter(m => m.type === 'way' && m.role === 'outer' && m.geometry?.length >= 2)
        .map(m => m.geometry.map(p => [p.lon, p.lat]));
    if (!outers.length) return null;
    const rings = mergeWayRings(outers);
    if (!rings.length) return null;
    const geometry = rings.length === 1
        ? { type: 'Polygon', coordinates: rings }
        : { type: 'MultiPolygon', coordinates: rings.map(r => [r]) };
    return { type: 'Feature', properties: { name: el.tags?.name || '' }, geometry };
}

async function run() {
    const boundaries = JSON.parse(fs.readFileSync('public/boundaries.json', 'utf-8'));
    const existing = new Set(Object.keys(boundaries));
    console.log(`Existing: ${existing.size} boundaries`);

    // Read all unit names from CSV
    const csv = fs.readFileSync('dist/administrative_units.csv', 'utf-8');
    const lines = csv.split('\n');
    const allNames = [];
    for (let i = 1; i < lines.length; i++) {
        const line = lines[i].trim();
        if (!line) continue;
        const cols = line.split(',');
        if (cols[2]) allNames.push(cols[2]);
    }

    const missing = allNames.filter(n => !existing.has(n));
    console.log(`Missing: ${missing.length} boundaries — fetching by name in Ha Nam bbox...\n`);

    for (let i = 0; i < missing.length; i++) {
        const name = missing[i];
        process.stdout.write(`[${i+1}/${missing.length}] ${name}... `);
        
        const data = await fetchByName(name);
        
        if (data?.elements?.length > 0) {
            const feature = buildGeoJSON(data.elements[0]);
            if (feature) {
                boundaries[name] = feature;
                console.log(`✓ (${feature.geometry.type})`);
            } else {
                const el = data.elements[0];
                const outerCount = (el.members || []).filter(m => m.role === 'outer').length;
                console.log(`✗ Parse failed (${outerCount} outer ways, admin_level=${el.tags?.admin_level})`);
            }
        } else {
            console.log(`✗ Not found in bbox`);
        }

        await new Promise(r => setTimeout(r, 1000));
    }

    const total = Object.keys(boundaries).length;
    fs.writeFileSync('public/boundaries.json', JSON.stringify(boundaries));
    console.log(`\n✅ Final: ${total}/33 boundaries saved.`);
}

run();
