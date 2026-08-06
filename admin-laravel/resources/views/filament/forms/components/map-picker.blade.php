<div x-data="{
    coordinates: '',
    coordinatesPath: null,
    latPath: null,
    lngPath: null,
    map: null,
    marker: null,
    selectedCoordinates: null,
    async init() {
        this.resolveStatePaths();
        this.syncFromWire();
        await this.loadLeaflet();
        this.watchCoordinates();

        // Delay initialization to ensure the container is visible (especially in modals)
        setTimeout(() => {
            this.initMap();
        }, 100);
    },
    resolveStatePaths() {
        const mountedActions = this.$wire.get('mountedActions') ?? [];
        const actionIndex = Math.max(mountedActions.length - 1, 0);
        const basePath = `mountedActions.${actionIndex}.data`;

        this.coordinatesPath = `${basePath}.coordinates`;
        this.latPath = `${basePath}.lat`;
        this.lngPath = `${basePath}.lng`;
    },
    syncFromWire() {
        if (!this.coordinatesPath) {
            return;
        }

        this.coordinates = this.$wire.get(this.coordinatesPath) ?? '';
    },
    watchCoordinates() {
        if (!this.coordinatesPath) {
            return;
        }

        this.$wire.watch(this.coordinatesPath, (value) => {
            this.coordinates = value ?? '';

            const parsed = this.parseCoordinates(this.coordinates);

            if (parsed) {
                this.selectedCoordinates = this.formatCoordinates(parsed.lat, parsed.lng);
                this.syncMarker(parsed.lat, parsed.lng);

                if (this.map) {
                    this.map.flyTo([parsed.lat, parsed.lng], Math.max(this.map.getZoom(), 15));
                }
            }
        });
    },
    async loadLeaflet() {
        if (!document.getElementById('leaflet-css')) {
            const link = document.createElement('link');
            link.id = 'leaflet-css';
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);
        }

        if (window.L) {
            return;
        }

        if (!window.__leafletLoader) {
            window.__leafletLoader = new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        await window.__leafletLoader;
    },
    initMap() {
        if (this.map || !$refs.map || !window.L) {
            return;
        }

        const initialPosition = this.parseCoordinates(this.coordinates) ?? { lat: 20.5409, lng: 105.9169 };

        // Initialize map
        this.map = window.L.map($refs.map).setView([initialPosition.lat, initialPosition.lng], 13);
        
        window.L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            attribution: '© Google Maps'
        }).addTo(this.map);

        this.selectedCoordinates = this.formatCoordinates(initialPosition.lat, initialPosition.lng);

        // Add initial marker
        this.marker = window.L.marker([initialPosition.lat, initialPosition.lng], { draggable: true }).addTo(this.map);

        // Handle map clicks
        this.map.on('click', (e) => {
            this.selectCoordinates(e.latlng.lat, e.latlng.lng, { pan: false });
        });

        // Handle marker drag
        this.marker.on('dragend', (e) => {
            const position = e.target.getLatLng();
            this.selectCoordinates(position.lat, position.lng, { pan: false });
        });
        // Force resize recalculation
        setTimeout(() => {
            this.map.invalidateSize();
        }, 300);
    },
    parseCoordinates(value) {
        if (!value || !value.includes(',')) {
            return null;
        }

        const parts = value.split(',');

        if (parts.length < 2) {
            return null;
        }

        const lat = parseFloat(parts[0].trim());
        const lng = parseFloat(parts[1].trim());

        if (Number.isNaN(lat) || Number.isNaN(lng)) {
            return null;
        }

        return { lat, lng };
    },
    formatCoordinate(value) {
        return Number(value).toFixed(6);
    },
    formatCoordinates(lat, lng) {
        return `${this.formatCoordinate(lat)},${this.formatCoordinate(lng)}`;
    },
    selectCoordinates(lat, lng, options = {}) {
        this.selectedCoordinates = this.formatCoordinates(lat, lng);
        this.syncMarker(lat, lng);

        if (this.map && options.pan !== false) {
            this.map.flyTo([lat, lng], options.zoom ?? Math.max(this.map.getZoom(), 16));
        }
    },
    commitCoordinates(lat, lng, options = {}) {
        const nextValue = this.formatCoordinates(lat, lng);

        this.coordinates = nextValue;
        this.selectedCoordinates = nextValue;
        this.syncMarker(lat, lng);
        this.$wire.set(this.coordinatesPath, nextValue);
        this.$wire.set(this.latPath, this.formatCoordinate(lat));
        this.$wire.set(this.lngPath, this.formatCoordinate(lng));

        if (this.map && options.pan !== false) {
            this.map.flyTo([lat, lng], options.zoom ?? Math.max(this.map.getZoom(), 16));
        }
    },
    confirmSelectedLocation() {
        const parsed = this.parseCoordinates(this.selectedCoordinates);

        if (!parsed) {
            alert('Vui lòng chọn một vị trí trên bản đồ trước.');

            return;
        }

        this.commitCoordinates(parsed.lat, parsed.lng, { pan: false });
    },
    syncMarker(lat, lng) {
        if (!this.map) {
            return;
        }

        const position = window.L.latLng(lat, lng);

        if (!this.marker) {
            this.marker = window.L.marker(position, { draggable: true }).addTo(this.map);

            this.marker.on('dragend', (e) => {
                const draggedPosition = e.target.getLatLng();
                this.selectCoordinates(draggedPosition.lat, draggedPosition.lng, { pan: false });
            });

            return;
        }

        this.marker.setLatLng(position);
    },
    useCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Trình duyệt không hỗ trợ lấy vị trí hiện tại.');

            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                this.commitCoordinates(position.coords.latitude, position.coords.longitude, { zoom: 16 });
            },
            () => {
                alert('Không thể lấy vị trí hiện tại. Vui lòng kiểm tra quyền truy cập vị trí.');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            },
        );
    }
}"
wire:ignore
x-on:use-current-location.window="useCurrentLocation()"
class="w-full">
    <div x-ref="map" style="height: 400px; border-radius: 0.75rem; z-index: 1;" class="shadow-sm border border-gray-300 bg-gray-50"></div>
    <div style="margin-top: 16px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; padding: 14px 16px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);">
        <div style="min-width: 0; flex: 1 1 auto;">
            <p style="margin: 0; font-size: 12px; line-height: 16px; font-weight: 600; color: #64748b; text-transform: uppercase;">Tọa độ đang chọn</p>
            <p style="margin: 6px 0 0; font-size: 15px; line-height: 22px; font-weight: 600; color: #0f172a; word-break: break-all;" x-text="selectedCoordinates ?? 'Chua chon vi tri'"></p>
        </div>
        <button
            type="button"
            x-on:click="confirmSelectedLocation()"
            title="Xac nhan toa do"
            aria-label="Xac nhan toa do"
            style="display: inline-flex; flex: 0 0 auto; align-items: center; justify-content: center; width: 44px; height: 44px; margin-top: 2px; border: none; border-radius: 9999px; background: #0284c7; color: #ffffff; cursor: pointer; box-shadow: 0 6px 18px rgba(2, 132, 199, 0.28);"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 22px; height: 22px;">
                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.25 7.313a1 1 0 0 1-1.42 0L3.29 9.267a1 1 0 1 1 1.414-1.414l4.046 4.045 6.54-6.601a1 1 0 0 1 1.414-.006Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
