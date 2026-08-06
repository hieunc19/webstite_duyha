<div x-data="{
    map: null,
    boundaryLayer: null,
    initMap() {
        const lat = {{ $getRecord()?->latitude ?? 20.54 }};
        const lng = {{ $getRecord()?->longitude ?? 105.93 }};
        this.map = L.map($refs.map).setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
        
        setTimeout(() => {
            this.map.invalidateSize();
            this.updateBoundary();
        }, 100);

        this.$watch('data.boundary_data', () => {
            this.updateBoundary();
        });
    },
    updateBoundary() {
        if (this.boundaryLayer) {
            this.map.removeLayer(this.boundaryLayer);
        }

        let geojson = null;
        try {
            const rawData = this.$wire.get('data.boundary_data');
            geojson = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;
        } catch (e) {}

        if (geojson && (geojson.type === 'Feature' || geojson.type === 'FeatureCollection' || geojson.type === 'Polygon' || geojson.type === 'MultiPolygon')) {
            this.boundaryLayer = L.geoJSON(geojson, {
                style: {
                    color: '#ef4444',
                    weight: 3,
                    fillColor: '#ef4444',
                    fillOpacity: 0.1
                }
            }).addTo(this.map);
            this.map.fitBounds(this.boundaryLayer.getBounds());
        }
    }
}"
x-init="initMap()"
wire:ignore
class="w-full h-96 rounded-xl border border-slate-200 overflow-hidden"
>
    <div x-ref="map" class="w-full h-full"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</div>
