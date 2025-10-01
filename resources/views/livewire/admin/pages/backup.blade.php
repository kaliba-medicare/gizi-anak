<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex py-0 bg-secondary text-white">
                    <h4 class="card-title my-2 flex-grow-1">GRAFIK STATUS GIZI ANAK LOMBOK UTARA</h4>
                    <div class="my-2">
                        <button type="button" class="btn btn-light btn-sm">Tahun 2025</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-xl-5 audiences-border">
                            <div id="bar-perbulan-chart" class="apex-charts"></div>
                        </div>
                        <div class="col-xl-7">
                            <div id="line-perbulan-chart" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Controls -->
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <!-- Boundary Type Selector moved to top -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Pilih Batas Wilayah</strong></label>
                        <select class="form-select" id="boundary-selector">
                            <option value="kecamatan">Data Gizi Anak - Kecamatan</option>
                            <option value="desa">Data Gizi Anak - Desa</option>
                        </select>
                    </div>
                    
                    <!-- Area Selection Controls -->
                    <div class="border-top pt-3">
                        <h5 class="card-title mb-3">Pilih Wilayah</h5>
                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <select class="form-select" id="kecamatan-select">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Desa</label>
                            <select class="form-select" id="desa-select" disabled>
                                <option value="">Semua Desa</option>
                            </select>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="show-posyandu" checked>
                            <label class="form-check-label" for="show-posyandu">Tampilkan Posyandu</label>
                        </div>
                        <button class="btn btn-primary btn-sm w-100" id="reset-map">Reset Peta</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body p-0">
                    <div id="map" style="height: 600px; width: 100%; border-radius: 10px; box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 600px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }

        .info,
        .legend {
            padding: 10px;
            font: 14px 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .info h4 {
            margin-bottom: 8px;
            color: #333;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.8;
            border-radius: 3px;
        }

        .leaflet-control-custom {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .leaflet-control-custom label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .leaflet-control-custom select {
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f9f9f9;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%23666' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 1rem;
        }

        .leaflet-control-custom select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .desa-label,
        .kecamatan-label,
        .kabupaten-label {
            font-weight: bold;
            text-shadow: 1px 1px 1px white;
            background: rgba(255, 255, 255, 0.7);
            padding: 2px 5px;
            border-radius: 3px;
        }

        .desa-label {
            color: green;
            font-size: 10px;
        }

        .kecamatan-label {
            color: blue;
            font-size: 12px;
        }

        .kabupaten-label {
            color: red;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.featuregroup.subgroup@1.0.2/dist/leaflet.featuregroup.subgroup.js"></script>
    
    <script>
        // Nutrition Charts
        const dataGizi = @json($dataGizi);

        const bulanLabels = {
            1: 'Januari',
            2: 'Februari',
            3: 'Maret',
            4: 'April',
            5: 'Mei',
            6: 'Juni',
            7: 'Juli',
            8: 'Agustus',
            9: 'September',
            10: 'Oktober',
            11: 'November',
            12: 'Desember'
        };

        // Get all unique months from all data
        const allMonths = [...new Set(
            dataGizi.flatMap(item => Object.keys(item.dataPerMonth))
        )].map(Number).sort((a, b) => a - b);

        const monthNames = allMonths.map(m => bulanLabels[m] ?? `Bulan ${m}`);

        // Bar chart: Per bulan, each series is a nutrition type
        const barSeries = dataGizi.map(item => ({
            name: item.type,
            data: allMonths.map(m => item.dataPerMonth[m] || 0)
        }));

        const barOptions = {
            chart: {
                type: 'bar',
                height: 350,
                stacked: true
            },
            series: barSeries,
            xaxis: {
                categories: monthNames
            },
            title: {
                text: 'Data Status Gizi Anak',
                align: 'center'
            }
        };
        new ApexCharts(document.querySelector("#bar-perbulan-chart"), barOptions).render();

        // Line chart: same as bar but with "line" type
        const lineSeries = dataGizi.map(item => ({
            name: item.type,
            data: allMonths.map(m => item.dataPerMonth[m] || 0)
        }));

        const lineOptions = {
            chart: {
                type: 'line',
                height: 350
            },
            stroke: {
                curve: 'smooth'
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: (val) => `${val} anak`
                }
            },
            grid: {
                borderColor: '#f1f1f1'
            },
            fill: {
                type: 'solid',
                opacity: 0.5
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                floating: true,
                fontSize: '14px',
                fontWeight: 600,
                labels: {
                    colors: '#333'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => `${val} anak`
            },
            series: lineSeries,
            xaxis: {
                categories: monthNames
            },
            title: {
                text: 'Perkembangan Status Gizi Anak',
                align: 'center'
            },
        };
        new ApexCharts(document.querySelector("#line-perbulan-chart"), lineOptions).render();

        // Map Visualization
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize map with stunting visualization
            const stuntingMap = L.map('map').setView([-8.404715960686927, 116.35618150844543], 11);

            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(stuntingMap);

            const countDataCache = {};
            let currentBoundaryType = 'kecamatan';

            const info = L.control();

            info.onAdd = function(map) {
                this._div = L.DomUtil.create('div', 'info');
                this.update();
                return this._div;
            };

            info.update = function(props) {
                const label = currentBoundaryType === 'kecamatan' ? 'Kecamatan' : 'Desa';
                if (props) {
                    const count = countDataCache[props.Name] || 0;
                    this._div.innerHTML = `<h4>Data Stunting ${label}</h4><strong>${props.Name}</strong><br>Jumlah Kasus: <strong>${count}</strong> anak`;
                } else {
                    this._div.innerHTML = `<h4>Data Stunting ${label}</h4>Arahkan kursor ke ${label}`;
                }
            };

            info.addTo(stuntingMap);

            // Remove the separate boundary selector control since we moved it to the panel
            document.getElementById('boundary-selector').addEventListener('change', function() {
                updateBoundary(this.value);
            });

            function getColor(d) {
                return d > 1000 ? '#800026' :
                    d > 500 ? '#BD0026' :
                    d > 200 ? '#E31A1C' :
                    d > 100 ? '#FC4E2A' :
                    d > 50 ? '#FD8D3C' :
                    d > 20 ? '#FEB24C' :
                    d > 10 ? '#FED976' : '#FFEDA0';
            }

            async function fetchAllCountData(features, boundaryType) {
                const names = features.map(f => f.properties.Name);
                const endpoint = boundaryType === 'kecamatan'
                    ? `/getAllCountStunting?params=${encodeURIComponent(JSON.stringify(names))}`
                    : `/getAllCountGiziForDesa?params=${encodeURIComponent(JSON.stringify(names))}`;

                try {
                    const response = await fetch(endpoint);
                    const data = await response.json();
                    Object.keys(data).forEach(key => {
                        countDataCache[key] = data[key] || 0;
                    });
                } catch (error) {
                    console.error('Error fetching count data:', error);
                    names.forEach(name => {
                        countDataCache[name] = countDataCache[name] || 0;
                    });
                }
            }

            function highlightFeature(e) {
                const layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: '#555',
                    dashArray: '',
                    fillOpacity: 0.7
                });
                layer.bringToFront();
                info.update(layer.feature.properties);
            }

            function resetHighlight(e) {
                geojson.resetStyle(e.target);
                info.update();
            }

            function zoomToFeature(e) {
                stuntingMap.fitBounds(e.target.getBounds());
            }

            function onEachFeature(feature, layer) {
                layer.on({
                    mouseover: highlightFeature,
                    mouseout: resetHighlight,
                    click: zoomToFeature
                });
            }

            let geojson;
            let legend;

            async function loadGeoJson(boundaryType) {
                const geoJsonFile = boundaryType === 'kecamatan'
                    ? '/geojson/Batas_Kecamatan.geojson'
                    : '/geojson/Batas_Desa_di_KLU.geojson';

                try {
                    const response = await fetch(geoJsonFile);
                    const data = await response.json();

                    await fetchAllCountData(data.features, boundaryType);

                    geojson = L.geoJson(data, {
                        style: function(feature) {
                            const count = countDataCache[feature.properties.Name] || 0;
                            return {
                                weight: 1.5,
                                color: '#fff',
                                dashArray: '3',
                                fillOpacity: 0.7,
                                fillColor: getColor(count)
                            };
                        },
                        onEachFeature: onEachFeature
                    }).addTo(stuntingMap);

                    addLegend();
                } catch (error) {
                    console.error('Error loading GeoJSON:', error);
                }
            }

            function updateBoundary(selectedBoundary) {
                currentBoundaryType = selectedBoundary;
                stuntingMap.eachLayer(function(layer) {
                    if (layer instanceof L.GeoJSON) {
                        stuntingMap.removeLayer(layer);
                    }
                });
                if (legend) stuntingMap.removeControl(legend);
                loadGeoJson(selectedBoundary);
            }

            function addLegend() {
                legend = L.control({ position: 'bottomright' });

                legend.onAdd = function(map) {
                    const div = L.DomUtil.create('div', 'info legend');
                    const grades = [0, 10, 20, 50, 100, 200, 500, 1000];
                    for (let i = 0; i < grades.length; i++) {
                        div.innerHTML +=
                            `<i style="background:${getColor(grades[i] + 1)}"></i> ${grades[i]}&ndash;${grades[i + 1] || '+'}<br>`;
                    }
                    return div;
                };

                legend.addTo(stuntingMap);
            }

            loadGeoJson(currentBoundaryType);

            // Original map controls for kecamatan/desa selection
            const posyanduData = @json($posyandus);
            const posyanduIcon = L.icon({
                iconUrl: '/images/pin.png',
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });

            // Create layer groups for posyandu
            const posyanduLayer = L.layerGroup();
            const kecamatanGroups = {};
            const desaGroups = {};

            // Organize posyandu by kecamatan and desa
            posyanduData.forEach(pos => {
                const [lat, lng] = pos.latlong.split(',').map(parseFloat);
                const marker = L.marker([lat, lng], {
                        icon: posyanduIcon
                    })
                    .bindPopup(`
                        <strong>${pos.nama_posyandu}</strong><br>
                        Dusun: ${pos.nama_dusun}<br>
                        Desa: ${pos.nama_desa}<br>
                        Kecamatan: ${pos.kecamatan}
                    `);

                // Add to main posyandu layer
                marker.addTo(posyanduLayer);

                // Create kecamatan group if not exists
                if (!kecamatanGroups[pos.kecamatan]) {
                    kecamatanGroups[pos.kecamatan] = L.layerGroup();
                }

                // Create desa group if not exists
                if (!desaGroups[pos.nama_desa]) {
                    desaGroups[pos.nama_desa] = L.layerGroup();
                }

                // Add to kecamatan and desa groups
                marker.addTo(kecamatanGroups[pos.kecamatan]);
                marker.addTo(desaGroups[pos.nama_desa]);
            });

            // Add posyandu layer to map by default
            if ($('#show-posyandu').is(':checked')) {
                posyanduLayer.addTo(stuntingMap);
            }

            // Event handlers for UI controls
            $('#kecamatan-select').on('change', function() {
                const selectedKecamatan = $(this).val();

                // Reset desa select
                $('#desa-select').val('').prop('disabled', !selectedKecamatan);

                // Update desa options
                $('#desa-select').empty().append('<option value="">Semua Desa</option>');

                if (selectedKecamatan) {
                    const desasInKecamatan = posyanduData
                        .filter(pos => pos.kecamatan === selectedKecamatan)
                        .map(pos => pos.nama_desa)
                        .filter((value, index, self) => self.indexOf(value) === index);

                    desasInKecamatan.forEach(desa => {
                        $('#desa-select').append(`<option value="${desa}">${desa}</option>`);
                    });

                    // Zoom to kecamatan
                    const kecamatanLayer = Object.values(geojson._layers).find(layer =>
                        layer.feature?.properties.Name === selectedKecamatan
                    );

                    if (kecamatanLayer) {
                        stuntingMap.fitBounds(kecamatanLayer.getBounds());
                    }

                    // Show posyandu in kecamatan
                    if ($('#show-posyandu').is(':checked')) {
                        posyanduLayer.clearLayers();
                        if (kecamatanGroups[selectedKecamatan]) {
                            kecamatanGroups[selectedKecamatan].addTo(posyanduLayer);
                        }
                    }
                } else {
                    // Show all posyandu if checkbox is checked
                    if ($('#show-posyandu').is(':checked')) {
                        posyanduLayer.clearLayers();
                        Object.values(desaGroups).forEach(group => {
                            group.addTo(posyanduLayer);
                        });
                    }
                }
            });

            $('#desa-select').on('change', function() {
                const selectedDesa = $(this).val();

                if (selectedDesa) {
                    // Zoom to desa
                    const desaLayer = Object.values(geojson._layers).find(layer =>
                        layer.feature?.properties.Name === selectedDesa
                    );

                    if (desaLayer) {
                        stuntingMap.fitBounds(desaLayer.getBounds());
                    }

                    // Show posyandu in desa
                    if ($('#show-posyandu').is(':checked')) {
                        posyanduLayer.clearLayers();
                        if (desaGroups[selectedDesa]) {
                            desaGroups[selectedDesa].addTo(posyanduLayer);
                        }
                    }
                } else {
                    // Show posyandu in selected kecamatan or all
                    const selectedKecamatan = $('#kecamatan-select').val();

                    if ($('#show-posyandu').is(':checked')) {
                        posyanduLayer.clearLayers();

                        if (selectedKecamatan) {
                            if (kecamatanGroups[selectedKecamatan]) {
                                kecamatanGroups[selectedKecamatan].addTo(posyanduLayer);
                            }
                        } else {
                            Object.values(desaGroups).forEach(group => {
                                group.addTo(posyanduLayer);
                            });
                        }
                    }
                }
            });

            $('#show-posyandu').on('change', function() {
                if ($(this).is(':checked')) {
                    const selectedDesa = $('#desa-select').val();
                    const selectedKecamatan = $('#kecamatan-select').val();

                    posyanduLayer.clearLayers();

                    if (selectedDesa) {
                        if (desaGroups[selectedDesa]) {
                            desaGroups[selectedDesa].addTo(posyanduLayer);
                        }
                    } else if (selectedKecamatan) {
                        if (kecamatanGroups[selectedKecamatan]) {
                            kecamatanGroups[selectedKecamatan].addTo(posyanduLayer);
                        }
                    } else {
                        Object.values(desaGroups).forEach(group => {
                            group.addTo(posyanduLayer);
                        });
                    }
                } else {
                    posyanduLayer.clearLayers();
                }
            });

            $('#reset-map').on('click', function() {
                $('#kecamatan-select').val('').trigger('change');
                $('#desa-select').val('').prop('disabled', true);
                stuntingMap.setView([-8.404715960686927, 116.35618150844543], 11);

                if ($('#show-posyandu').is(':checked')) {
                    posyanduLayer.clearLayers();
                    Object.values(desaGroups).forEach(group => {
                        group.addTo(posyanduLayer);
                    });
                }
            });
        });
    </script>
@endpush