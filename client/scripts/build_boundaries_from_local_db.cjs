const fs = require('fs');
const path = require('path');

// Đường dẫn các tệp liên quan
const csvPath = path.join(__dirname, '../public/administrative_units.csv');
const distCsvPath = path.join(__dirname, '../dist/administrative_units.csv');
const jsonPath = path.join(__dirname, '../scratch/vietnamese-provinces-database/json/simplified_json_generated_data_vn_units.json');
const geojsonDir = path.join(__dirname, '../scratch/vietnamese-provinces-database/json/geojson/37_ninh_binh/wards/');
const osmIdsPath = path.join(__dirname, '../scratch/osm_ids.json');
const boundariesPath = path.join(__dirname, '../public/boundaries.json');

// Helper to remove tones
function removeVietnameseTones(str) {
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
    str = str.replace(/Ù|Ú|Ụ|Ủ|U|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
    str = str.replace(/Đ/g, "D");
    str = str.replace(/\u0300|\u0301|\u0309|\u0303|\u0309/g, "");
    str = str.replace(/\u02C6|\u0306|\u031B/g, "");
    return str;
}

function toSnakeCase(str) {
    return removeVietnameseTones(str)
        .toLowerCase()
        .replace(/[^a-z0-9\s]/g, "")
        .trim()
        .replace(/\s+/g, "_");
}

// Tính centroid của Polygon/MultiPolygon
function getCentroid(geometry) {
    let sumLng = 0;
    let sumLat = 0;
    let totalPoints = 0;

    if (geometry.type === 'Polygon') {
        const ring = geometry.coordinates[0];
        for (let i = 0; i < ring.length - 1; i++) {
            sumLng += ring[i][0];
            sumLat += ring[i][1];
            totalPoints++;
        }
    } else if (geometry.type === 'MultiPolygon') {
        geometry.coordinates.forEach(polygon => {
            const ring = polygon[0];
            for (let i = 0; i < ring.length - 1; i++) {
                sumLng += ring[i][0];
                sumLat += ring[i][1];
                totalPoints++;
            }
        });
    }

    if (totalPoints > 0) {
        return {
            lat: sumLat / totalPoints,
            lng: sumLng / totalPoints
        };
    }
    return null;
}

// Đọc danh sách OSM IDs nếu có
let osmIdsMap = {};
if (fs.existsSync(osmIdsPath)) {
    const osmIdsData = JSON.parse(fs.readFileSync(osmIdsPath, 'utf8'));
    osmIdsData.forEach(item => {
        if (item.osm_id) {
            // Lấy tên xã từ name dạng "Xã Bình Lục, Huyện Bình Lục, Hà Nam, Việt Nam"
            // Hoặc dạng "Phường Duy Tiên..."
            const parts = item.name.split(',');
            if (parts.length > 0) {
                const cleanName = parts[0].replace(/^(Xã|Phường)\s+/, '').trim();
                osmIdsMap[cleanName] = item.osm_id;
            }
        }
    });
}

// Đọc simplified_json_generated_data_vn_units.json để so khớp xã với file GeoJSON
const repoData = JSON.parse(fs.readFileSync(jsonPath, 'utf8'));
const ninhBinh = repoData.find(p => p.Code === '37');
if (!ninhBinh) {
    console.error("Không tìm thấy tỉnh Ninh Bình trong repo!");
    process.exit(1);
}
const repoWards = ninhBinh.Wards;

// Đọc CSV hiện tại
let csvContent = fs.readFileSync(csvPath, 'utf8');
let csvLines = csvContent.split('\n').map(line => line.trim()).filter(line => line.length > 0);
let headerLine = csvLines[0];
if (headerLine.startsWith('\uFEFF') || headerLine.startsWith('ï»¿')) {
    headerLine = headerLine.replace(/^\uFEFF|ï»¿/, '');
}

const csvWards = [];
for (let i = 1; i < csvLines.length; i++) {
    const cols = csvLines[i].split(',');
    if (cols.length >= 8) {
        csvWards.push({
            stt: cols[0],
            id: cols[1],
            name: cols[2],
            type: cols[3],
            district: cols[4],
            lat: cols[5],
            lng: cols[6],
            link: cols[7]
        });
    }
}

console.log(`Đang xử lý ${csvWards.length} xã/phường...`);

const boundaries = {};
const updatedCsvWards = [];

// Quét thư mục wards của repo
const geojsonFiles = fs.readdirSync(geojsonDir);

csvWards.forEach(c => {
    const cSnake = toSnakeCase(c.name);
    
    // Tìm ward tương ứng trong repo JSON
    const repoMatch = repoWards.find(r => r.CodeName === cSnake || toSnakeCase(r.Name) === cSnake);
    if (!repoMatch) {
        console.error(`Lỗi: Không tìm thấy xã ${c.name} trong repo JSON!`);
        return;
    }

    // Tìm file geojson tương ứng trong thư mục (ví dụ kết thúc bằng _binh_luc.geojson)
    const geojsonFile = geojsonFiles.find(f => f.endsWith(`_${cSnake}.geojson`));
    if (!geojsonFile) {
        console.error(`Lỗi: Không tìm thấy file GeoJSON cho xã ${c.name} (snake_case: ${cSnake})`);
        return;
    }

    // Đọc file GeoJSON
    const geojsonFilePath = path.join(geojsonDir, geojsonFile);
    const geojson = JSON.parse(fs.readFileSync(geojsonFilePath, 'utf8'));
    const feature = geojson.features[0];

    // Tính centroid từ geometry
    const centroid = getCentroid(feature.geometry);
    const finalLat = centroid ? centroid.lat.toFixed(7) : c.lat;
    const finalLng = centroid ? centroid.lng.toFixed(7) : c.lng;

    // Xác định loại hình từ FullName của repo
    let finalType = c.type;
    if (repoMatch.FullName.startsWith("Phường")) {
        finalType = "Phường";
    } else if (repoMatch.FullName.startsWith("Xã")) {
        finalType = "Xã";
    } else if (repoMatch.FullName.startsWith("Thị trấn")) {
        finalType = "Thị trấn";
    }

    // Tạo GeoJSON Feature với thuộc tính properties và geometry
    boundaries[repoMatch.Name] = {
        type: "Feature",
        properties: {
            name: repoMatch.Name,
            gso_id: repoMatch.Code,
            osm_id: osmIdsMap[repoMatch.Name] || null
        },
        geometry: feature.geometry
    };

    // Tạo link Google Maps mới dựa trên tọa độ centroid hoặc giữ nguyên link cũ
    const finalLink = `https://www.google.com/maps/place/${encodeURIComponent(repoMatch.FullName + ', Tỉnh Ninh Bình, Việt Nam')}/@${finalLat},${finalLng},13z`;

    updatedCsvWards.push({
        stt: c.stt,
        id: c.id,
        name: repoMatch.Name,
        type: finalType,
        district: c.district,
        lat: finalLat,
        lng: finalLng,
        link: finalLink
    });

    console.log(`+ Đã map xã: ${repoMatch.Name} (${finalType}) | Code: ${repoMatch.Code} | Centroid: ${finalLat}, ${finalLng}`);
});

// Lưu file boundaries.json
fs.writeFileSync(boundariesPath, JSON.stringify(boundaries, null, 2), 'utf8');
console.log(`\nĐã ghi đè thành công tệp public/boundaries.json với ${Object.keys(boundaries).length} xã/phường.`);

// Cập nhật lại CSV
const csvHeaderStr = "ï»¿STT,ID,Tên đơn vị,Loại hình,Quận/Huyện,Latitude,Longitude,Link\n";
const csvRowsStr = updatedCsvWards.map(w => `${w.stt},${w.id},${w.name},${w.type},${w.district},${w.lat},${w.lng},${w.link}`).join('\n');
const finalCsvContent = csvHeaderStr + csvRowsStr;

fs.writeFileSync(csvPath, finalCsvContent, 'utf8');
fs.writeFileSync(distCsvPath, finalCsvContent, 'utf8');
console.log(`Đã cập nhật lại public/administrative_units.csv và dist/administrative_units.csv.`);
