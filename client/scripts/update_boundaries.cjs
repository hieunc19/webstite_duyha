const fs = require('fs');
const https = require('https');

// Function to make HTTPS GET request
function httpsGet(url) {
    return new Promise((resolve) => {
        const req = https.get(url, (res) => {
            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                try {
                    resolve(JSON.parse(data));
                } catch (e) {
                    resolve(null);
                }
            });
        });
        req.on('error', () => resolve(null));
        req.setTimeout(30000, () => {
            req.destroy();
            resolve(null);
        });
    });
}

// Function to fetch GeoJSON from OSM Polygons API
async function fetchOSMGeometry(osmId, osmType = 'relation') {
    // Using the official OSM Polygons API
    const url = `https://polygons.openstreetmap.fr/get_geometry.php?osm_id=${osmId}&osm_type=${osmType}`;
    console.log(`Fetching: ${url}`);
    return httpsGet(url);
}

// Function to convert OSM geometry to GeoJSON Feature
function convertToGeoJSONFeature(osmGeom, name) {
    if (!osmGeom || !osmGeom.geometry) {
        console.log(`  No geometry found for ${name}`);
        return null;
    }

    // Directly use geometry from OSM
    return {
        type: 'Feature',
        properties: {
            name: name
        },
        geometry: osmGeom.geometry
    };
}

// Main function
async function updateBoundaries() {
    try {
        // Read OSM IDs
        const osmIdsData = fs.readFileSync('scratch/osm_ids.json', 'utf-8');
        const osmIds = JSON.parse(osmIdsData);

        // Read existing boundaries or create new object
        let boundaries = {};
        const boundariesPath = 'public/boundaries.json';
        if (fs.existsSync(boundariesPath)) {
            const existingData = fs.readFileSync(boundariesPath, 'utf-8');
            boundaries = JSON.parse(existingData);
        }

        let successCount = 0;
        let failCount = 0;

        // Process each OSM ID
        for (const osmEntry of osmIds) {
            const { name, osm_id, osm_type } = osmEntry;
            const wardName = name.split(',')[0].trim(); // Extract ward name (before comma)

            console.log(`\nProcessing: ${wardName} (ID: ${osm_id}, Type: ${osm_type})`);

            // Fetch geometry
            const osmGeom = await fetchOSMGeometry(osm_id, osm_type);

            if (osmGeom && osmGeom.geometry) {
                // Convert to GeoJSON Feature
                const feature = convertToGeoJSONFeature(osmGeom, wardName);
                if (feature) {
                    boundaries[wardName] = feature;
                    console.log(`  ✓ Updated ${wardName}`);
                    successCount++;
                } else {
                    console.log(`  ✗ Failed to convert geometry for ${wardName}`);
                    failCount++;
                }
            } else {
                console.log(`  ✗ No geometry data for ${wardName}`);
                failCount++;
            }

            // Add delay to avoid rate limiting
            await new Promise(resolve => setTimeout(resolve, 500));
        }

        // Write updated boundaries
        fs.writeFileSync(boundariesPath, JSON.stringify(boundaries, null, 2));
        console.log(`\n✓ Successfully updated boundaries.json`);
        console.log(`Total: ${osmIds.length} | Success: ${successCount} | Failed: ${failCount}`);

    } catch (error) {
        console.error('Error:', error);
    }
}

// Run the update
updateBoundaries();
