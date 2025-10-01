@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        #map {
            height: 500px;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            text-align: left;
            line-height: 18px;
            color: #555;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div id='map'></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        // Initialize the map
        const map = L.map('map').setView([-8.367487387414142, 116.24772867731335], 11);

        // Add base tiles
        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Create a cache for count data to avoid repeated API calls
        const countDataCache = {};

        // Control for displaying information
        const info = L.control();

        info.onAdd = function(map) {
            this._div = L.DomUtil.create('div', 'info');
            this.update();
            return this._div;
        };

        info.update = async function(props) {
            if (props) {
                const count = await getCountData(props.Name);
                this._div.innerHTML = `<h4>Data Stunting Kecamatan</h4><b>${props.Name}</b><br />Jumlah Kasus: ${count}`;
            } else {
                this._div.innerHTML = '<h4>Data Stunting Kecamatan</h4>Gerakkan mouse ke kecamatan';
            }
        };

        info.addTo(map);

        // Color scale based on stunting count
        function getColor(d) {
            return d > 1000 ? '#800026' :
                d > 500 ? '#BD0026' :
                d > 200 ? '#E31A1C' :
                d > 100 ? '#FC4E2A' :
                d > 50 ? '#FD8D3C' :
                d > 20 ? '#FEB24C' :
                d > 10 ? '#FED976' : '#FFEDA0';
        }

        // Function to fetch count data with caching
        async function getCountData(kecamatanName) {
            // Check cache first
            if (countDataCache[kecamatanName]) {
                return countDataCache[kecamatanName];
            }

            try {
                const response = await fetch(`/getCountStunting?param=${encodeURIComponent(kecamatanName)}`);
                const data = await response.json();
                
                // Store in cache
                countDataCache[kecamatanName] = data.count || 0;
                return countDataCache[kecamatanName];
            } catch (error) {
                console.error('Error fetching count data:', error);
                return 0;
            }
        }

        // Style function for GeoJSON features
        async function getStyle(feature) {
            const count = await getCountData(feature.properties.Name);
            return {
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7,
                fillColor: getColor(count)
            };
        }

        // Highlight feature on hover
        function highlightFeature(e) {
            const layer = e.target;

            layer.setStyle({
                weight: 5,
                color: '#666',
                dashArray: '',
                fillOpacity: 0.7
            });

            layer.bringToFront();
            info.update(layer.feature.properties);
        }

        // Reset highlight when mouse leaves
        function resetHighlight(e) {
            geojson.resetStyle(e.target);
            info.update();
        }

        // Zoom to feature on click
        function zoomToFeature(e) {
            map.fitBounds(e.target.getBounds());
        }

        // Add event listeners to each feature
        function onEachFeature(feature, layer) {
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: zoomToFeature
            });
        }

        // Variable to hold our GeoJSON layer
        let geojson;

        // Load and display GeoJSON data
        fetch('/geojson/Batas_Kecamatan.geojson')
            .then(response => response.json())
            .then(async data => {
                // Create a property to store the original style
                data.features.forEach(feature => {
                    feature.properties.originalStyle = {
                        weight: 2,
                        opacity: 1,
                        color: 'white',
                        dashArray: '3',
                        fillOpacity: 0.7
                    };
                });

                geojson = L.geoJson(data, {
                    style: async function(feature) {
                        const count = await getCountData(feature.properties.Name);
                        return {
                            ...feature.properties.originalStyle,
                            fillColor: getColor(count)
                        };
                    },
                    onEachFeature: onEachFeature
                }).addTo(map);

                // Add legend after data is loaded
                addLegend();
            })
            .catch(error => console.error('Error loading GeoJSON:', error));

        // Function to add legend
        function addLegend() {
            const legend = L.control({ position: 'bottomright' });

            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'info legend');
                const grades = [0, 10, 20, 50, 100, 200, 500, 1000];
                const labels = ['<strong>Jumlah Kasus Stunting</strong>'];
                
                // Loop through our intervals and generate a label with a colored square for each interval
                for (let i = 0; i < grades.length; i++) {
                    div.innerHTML +=
                        '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                        grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
                }

                return div;
            };

            legend.addTo(map);
        }
    </script>
@endpush