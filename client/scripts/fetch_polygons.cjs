const fs = require('fs');
const https = require('https');

// Map osm_ids.json names back to unit names from CSV
const NAME_MAP = {
  'Bình Lục': 'Bình Lục',
  'Bình Mỹ': 'Bình Mỹ',
  'Bình An': 'Bình An',
  'Bình Giang': 'Bình Giang',
  'Bình Sơn': 'Bình Sơn',
  'Liêm Hà': 'Liêm Hà',
  'Tân Thanh': 'Tân Thanh',
  'Thanh Bình': 'Thanh Bình',
  'Thanh Lâm': 'Thanh Lâm',
  'Thanh Liêm': 'Thanh Liêm',
  'Lý Nhân': 'Lý Nhân',
  'Nam Xang': 'Nam Xang',
  'Bắc Lý': 'Bắc Lý',
  'Vĩnh Trụ': 'Vĩnh Trụ',
  'Trần Thương': 'Trần Thương',
  'Nhân Hà': 'Nhân Hà',
  'Nam Lý': 'Nam Lý',
  'Duy Tiên': 'Duy Tiên',
  'Duy Tân': 'Duy Tân',
  'Đồng Văn': 'Đồng Văn',
  'Duy Hà': 'Duy Hà',
  'Tiên Sơn': 'Tiên Sơn',
  'Lê Hồ': 'Lê Hồ',
  'Nguyễn Úy': 'Nguyễn Úy',
  'Lý Thường Kiệt': 'Lý Thường Kiệt',
  'Kim Thanh': 'Kim Thanh',
  'Tam Chúc': 'Tam Chúc',
  'Kim Bảng': 'Kim Bảng',
  'Hà Nam': 'Hà Nam',
  'Phù Vân': 'Phù Vân',
  'Châu Sơn': 'Châu Sơn',
  'Phủ Lý': 'Phủ Lý',
  'Liêm Tuyền': 'Liêm Tuyền',
};

// Manual OSM IDs overrides for units that were "node" type in osm_ids.json
// These are correct relation IDs found manually from OSM
const MANUAL_OVERRIDES = {
  'Tân Thanh': 19539163,   // Xã Tân Thanh, Huyện Thanh Liêm
  'Thanh Bình': 19539161,  // Xã Thanh Bình, Huyện Thanh Liêm
  'Đồng Văn': 19539221,    // Phường Đồng Văn, Duy Tiên
};

function httpsGet(url) {
  return new Promise((resolve) => {
    const req = https.get(url, { headers: { 'User-Agent': 'JobMapBoundaryBot/1.0' } }, (res) => {
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

// Fetch GeoJSON from polygons.openstreetmap.fr
async function fetchPolygonGeoJSON(osmId) {
  const url = `https://polygons.openstreetmap.fr/get_geojson.py?id=${osmId}&params=0`;
  return httpsGet(url);
}

async function run() {
  const osmIds = JSON.parse(fs.readFileSync('scratch/osm_ids.json', 'utf-8'));
  const boundaries = {};

  console.log(`\n🗺️  Fetching GeoJSON from polygons.openstreetmap.fr for ${osmIds.length} units...\n`);

  for (let i = 0; i < osmIds.length; i++) {
    const item = osmIds[i];
    const shortName = item.name.split(',')[0].trim();

    // Use manual override ID if available, else use the ID from osm_ids.json
    let osmId = MANUAL_OVERRIDES[shortName] || item.osm_id;

    // Skip if no relation ID
    if (!osmId) {
      console.log(`[${i+1}/${osmIds.length}] ${shortName}... ✗ No ID`);
      continue;
    }

    process.stdout.write(`[${i+1}/${osmIds.length}] ${shortName} (id=${osmId})... `);

    const geojson = await fetchPolygonGeoJSON(osmId);

    if (geojson && (geojson.type === 'Polygon' || geojson.type === 'MultiPolygon')) {
      // API returns direct Geometry (most common case)
      boundaries[shortName] = {
        type: 'Feature',
        properties: { name: shortName, osm_id: osmId },
        geometry: geojson
      };
      const coordCount = geojson.type === 'Polygon'
        ? geojson.coordinates[0].length
        : geojson.coordinates.reduce((s, p) => s + p[0].length, 0);
      console.log(`✓ (${geojson.type}, ${coordCount} coords)`);
    } else if (geojson && geojson.geometries && geojson.geometries.length > 0) {
      // GeometryCollection fallback
      const geometry = geojson.geometries[0];
      if (geometry.type === 'Polygon' || geometry.type === 'MultiPolygon') {
        boundaries[shortName] = {
          type: 'Feature',
          properties: { name: shortName, osm_id: osmId },
          geometry: geometry
        };
        console.log(`✓ GeometryCollection (${geometry.type})`);
      } else {
        console.log(`✗ Unexpected type: ${geometry.type}`);
      }
    } else if (geojson && geojson.type === 'FeatureCollection' && geojson.features?.length > 0) {
      // FeatureCollection fallback
      const feature = geojson.features[0];
      if (feature.geometry?.type === 'Polygon' || feature.geometry?.type === 'MultiPolygon') {
        boundaries[shortName] = { ...feature, properties: { name: shortName, osm_id: osmId } };
        console.log(`✓ FeatureCollection (${feature.geometry.type})`);
      } else {
        console.log(`✗ No polygon in FeatureCollection`);
      }
    } else {
      console.log(`✗ No data (response: ${geojson ? JSON.stringify(geojson).substring(0, 80) : 'null'})`);
    }

    // Throttle: respect rate limit
    await new Promise(r => setTimeout(r, 1000));
  }

  const count = Object.keys(boundaries).length;
  fs.writeFileSync('public/boundaries.json', JSON.stringify(boundaries));
  console.log(`\n✅ Done! ${count}/33 boundaries saved to public/boundaries.json`);
  console.log('Missing:', osmIds.map(x => x.name.split(',')[0].trim()).filter(n => !boundaries[n]).join(', ') || 'None!');
}

run();
