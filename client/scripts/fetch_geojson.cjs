const fs = require('fs');
const https = require('https');

function httpsGet(url) {
    return new Promise((resolve) => {
        const req = https.get(url, (res) => {
            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                try { resolve(JSON.parse(data)); }
                catch (e) { resolve(null); }
            });
        });
        req.on('error', () => resolve(null));
        req.setTimeout(30000, () => { req.destroy(); resolve(null); });
    });
}

// Method 1: Use out geom for the specific relation
async function fetchRelationGeom(osmId) {
    const query = `[out:json][timeout:30];relation(${osmId});out geom;`;
    const url = `https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`;
    return httpsGet(url);
}

// Merge OSM way segments into closed rings
function mergeWayRings(ways) {
    if (!ways.length) return [];
    const ptEq = (a, b) => Math.abs(a[0]-b[0]) < 1e-6 && Math.abs(a[1]-b[1]) < 1e-6;
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
    if (!el) return null;

    // For relations with member geometry
    if (el.type === 'relation' && el.members) {
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
    return null;
}

async function run() {
    const ids = JSON.parse(fs.readFileSync('scratch/osm_ids.json', 'utf-8'));
    
    // Load existing boundaries.json so we keep what already works
    let boundaries = {};
    try {
        boundaries = JSON.parse(fs.readFileSync('public/boundaries.json', 'utf-8'));
        console.log(`Loaded ${Object.keys(boundaries).length} existing boundaries`);
    } catch { console.log('Starting fresh') }

    console.log(`\nFetching ${ids.length} units...\n`);

    for (let i = 0; i < ids.length; i++) {
        const item = ids[i];
        const name = item.name.split(',')[0].trim();

        if (boundaries[name]?.geometry?.type === 'Polygon' || boundaries[name]?.geometry?.type === 'MultiPolygon') {
            console.log(`[${i+1}/${ids.length}] ${name}... ✓ Already have it`);
            continue;
        }

        if (item.osm_type !== 'relation' || !item.osm_id) {
            console.log(`[${i+1}/${ids.length}] ${name}... ✗ Not a relation`);
            continue;
        }

        process.stdout.write(`[${i+1}/${ids.length}] ${name}... `);

        const data = await fetchRelationGeom(item.osm_id);
        
        if (data?.elements?.length > 0) {
            const feature = buildGeoJSON(data.elements[0]);
            if (feature) {
                boundaries[name] = feature;
                const rings = feature.geometry.type === 'Polygon' ? 1 : feature.geometry.coordinates.length;
                console.log(`✓ (${feature.geometry.type}, ${rings} ring(s))`);
            } else {
                // Log raw member types for debugging
                const el = data.elements[0];
                const roleTypes = (el.members || []).map(m => `${m.role}:${m.type}`).join(',').substring(0, 60);
                console.log(`✗ parseError [${roleTypes}]`);
            }
        } else {
            console.log(`✗ Empty response`);
        }

        await new Promise(r => setTimeout(r, 1200));
    }

    const count = Object.keys(boundaries).length;
    fs.writeFileSync('public/boundaries.json', JSON.stringify(boundaries));
    console.log(`\n✅ Done! ${count}/${ids.filter(x => x.osm_type === 'relation').length} boundaries saved to public/boundaries.json`);
}

run();
