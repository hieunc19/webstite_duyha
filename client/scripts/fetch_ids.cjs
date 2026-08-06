const fs = require('fs');
const http = require('https');

async function getOSMID(unit) {
    const { name, type, district } = unit;
    // Truy vấn dạng: [Tên đơn vị], Ninh Bình, Việt Nam
    const searchQuery = `${name}, Ninh Bình, Việt Nam`;
    
    return new Promise((resolve) => {
        const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(searchQuery)}&format=json&limit=10`;
        const options = {
            headers: {
                'User-Agent': 'TripMapBoundaryFetcher/2.0'
            }
        };
        http.get(url, options, (res) => {
            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                try {
                    const json = JSON.parse(data);
                    if (Array.isArray(json) && json.length > 0) {
                        // Lọc các relation hành chính cấp địa giới (boundary administrative)
                        const administrativeRelations = json.filter(item => 
                            item.osm_type === 'relation' && 
                            item.class === 'boundary' && 
                            (item.type === 'administrative' || item.type === 'historic')
                        );
                        
                        if (administrativeRelations.length > 0) {
                            // 1. Ưu tiên khớp cả loại hình (ví dụ: "Xã Bình Lục" hoặc "Phường Duy Tiên")
                            let bestMatch = administrativeRelations.find(item => 
                                item.display_name.toLowerCase().includes(`${type.toLowerCase()} ${name.toLowerCase()}`)
                            );
                            
                            // 2. Nếu không khớp loại hình, ưu tiên khớp tên huyện (để tránh lấy nhầm huyện khác hoặc tỉnh khác)
                            if (!bestMatch) {
                                bestMatch = administrativeRelations.find(item => 
                                    item.display_name.toLowerCase().includes(district.toLowerCase())
                                );
                            }
                            
                            // 3. Nếu vẫn không khớp, lấy phần tử relation hành chính đầu tiên
                            if (!bestMatch) {
                                bestMatch = administrativeRelations[0];
                            }
                            
                            resolve({
                                name: `${type} ${name}, ${district}, Hà Nam, Việt Nam`,
                                osm_id: bestMatch.osm_id,
                                osm_type: bestMatch.osm_type,
                                display_name: bestMatch.display_name
                            });
                            return;
                        }
                    }
                    resolve({ name: `${type} ${name}, ${district}, Hà Nam, Việt Nam`, error: 'Not found' });
                } catch (e) {
                    resolve({ name: `${type} ${name}, ${district}, Hà Nam, Việt Nam`, error: 'JSON Parse Error: ' + e.message });
                }
            });
        }).on('error', (e) => {
            resolve({ name: `${type} ${name}, ${district}, Hà Nam, Việt Nam`, error: e.message });
        });
    });
}

function extractUnits() {
    const data = fs.readFileSync('dist/administrative_units.csv', 'utf-8');
    const lines = data.split('\n');
    const units = [];

    for (let i = 1; i < lines.length; i++) {
        const line = lines[i].trim();
        if (!line) continue;
        const cols = line.split(',');
        if (cols.length < 5) continue;
        
        units.push({
            name: cols[2].trim(),
            type: cols[3].trim(),
            district: cols[4].trim()
        });
    }
    return units;
}

async function run() {
    const units = extractUnits();
    const results = [];
    console.log(`Starting lookup for ${units.length} units...`);
    
    // Thực hiện tuần tự để tránh bị rate limit từ Nominatim API
    for (let i = 0; i < units.length; i++) {
        const unit = units[i];
        console.log(`[${i+1}/${units.length}] Searching ${unit.type} ${unit.name} (${unit.district})...`);
        const res = await getOSMID(unit);
        results.push(res);
        // Chờ 1.5 giây giữa các request để đảm bảo an toàn
        await new Promise(r => setTimeout(r, 1500));
    }
    
    fs.writeFileSync('scratch/osm_ids.json', JSON.stringify(results, null, 2));
    console.log('Done! Results saved to scratch/osm_ids.json');
}

run();
