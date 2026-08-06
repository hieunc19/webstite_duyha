const fs = require('fs');

function extractUnits() {
    const data = fs.readFileSync('dist/administrative_units.csv', 'utf-8');
    const lines = data.split('\n');
    const headers = lines[0].split(',');
    const units = [];

    for (let i = 1; i < lines.length; i++) {
        if (!lines[i].trim()) continue;
        const cols = lines[i].split(',');
        units.push({
            name: cols[2],
            type: cols[3],
            district: cols[4]
        });
    }
    return units;
}

const units = extractUnits();
units.forEach(u => {
    console.log(`Searching for: ${u.name}, ${u.district}, Hà Nam, Việt Nam`);
});
