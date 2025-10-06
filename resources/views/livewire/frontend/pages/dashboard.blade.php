<div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 p-3 md:p-4">
    <div class="max-w-7xl mx-auto h-screen flex flex-col">
        <!-- Dashboard Header with Controls -->
        <div class="bg-gray-800 rounded-2xl shadow-lg p-2 md:p-3 mb-2 border border-gray-700 flex-shrink-0">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div>
                    <h1 class="text-sm md:text-base font-extrabold text-white">Dashboard Monitoring Gizi Anak</h1>
                    <p class="text-gray-400 text-[9px] mt-0.5 font-medium">Sistem komprehensif untuk memantau status gizi anak di Lombok Utara</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center bg-gray-700 rounded-lg px-2 py-1">
                        <label for="year-selector" class="text-gray-300 font-bold text-[9px] mr-2">Tahun:</label>
                        <select id="year-selector" class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-1 text-[9px] font-bold" onchange="applyFilters()">
                            @foreach ($listYears as $year)
                                <option value="{{ $year }}" {{ $year == $filterYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-center bg-gray-700 rounded-lg px-2 py-1">
                        <label for="month-selector" class="text-gray-300 font-bold text-[9px] mr-2">Bulan:</label>
                        <select id="month-selector" class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-1 text-[9px] font-bold" onchange="applyFilters()">
                            <option value="all" {{ $filterMonth === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            <option value="1" {{ $filterMonth == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ $filterMonth == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ $filterMonth == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ $filterMonth == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $filterMonth == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ $filterMonth == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ $filterMonth == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ $filterMonth == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ $filterMonth == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $filterMonth == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $filterMonth == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $filterMonth == 12 ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center bg-gray-700 rounded-lg px-2 py-1">
                        <label for="category-selector" class="text-gray-300 font-bold text-[9px] mr-2">Kategori:</label>
                        <select id="category-selector" class="bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-1 text-[9px] font-bold" onchange="applyFilters()">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $filterCategory ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-center bg-gray-700 rounded-lg px-2 py-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                        <span class="text-[8px] text-green-400 font-bold">Data Terkini</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid - Takes remaining space -->
        <div class="flex-grow overflow-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-full">
                <!-- Left Column: Stats and Charts -->
                <div class="flex flex-col gap-4 h-full">
                    <!-- Stats Overview -->
                    <div class="grid grid-cols-4 gap-1">
                        @php
                            $totalChildren = array_sum(array_column($chartDataGizi, 'total'));
                            $categories = count($chartDataGizi);
                            $months = count(array_unique(array_merge(...array_map(function($item) { return array_keys($item['dataPerMonth']); }, $chartDataGizi))));
                            $posyanduCount = count($posyandus);
                        @endphp
                        
                        <div class="bg-gray-800 rounded-xl shadow-lg p-1.5 border border-gray-700">
                            <div class="flex items-center">
                                <div class="p-0.5 rounded-md bg-blue-900 mr-1">
                                    <i class="fas fa-child text-blue-400 text-[9px]"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[8px]">Total Anak</p>
                                    <p class="text-[9px] font-bold text-white" id="total-children-count">{{ number_format($totalChildren) }}</p>
                                    <p class="text-[7px] text-gray-500 mt-0.5" id="total-children-detail">Semua Kategori</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl shadow-lg p-1.5 border border-gray-700">
                            <div class="flex items-center">
                                <div class="p-0.5 rounded-md bg-green-900 mr-1">
                                    <i class="fas fa-clipboard-list text-green-400 text-[9px]"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[8px]">Kategori</p>
                                    <p class="text-[9px] font-bold text-white">{{ $categories }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl shadow-lg p-1.5 border border-gray-700">
                            <div class="flex items-center">
                                <div class="p-0.5 rounded-md bg-purple-900 mr-1">
                                    <i class="fas fa-calendar-alt text-purple-400 text-[9px]"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[8px]">Bulan</p>
                                    <p class="text-[9px] font-bold text-white">{{ $months }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-xl shadow-lg p-1.5 border border-gray-700">
                            <div class="flex items-center">
                                <div class="p-0.5 rounded-md bg-amber-900 mr-1">
                                    <i class="fas fa-clinic-medical text-amber-400 text-[9px]"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[8px]">Posyandu</p>
                                    <p class="text-[9px] font-bold text-white">{{ number_format($posyanduCount) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="bg-gray-800 rounded-2xl shadow-lg p-2 border border-gray-700 flex-grow flex flex-col">
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="font-bold text-white text-[10px]">Distribusi Status Gizi</h2>
                            <div class="flex space-x-1">
                                <button id="toggle-auto-slide" class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600">
                                    <i class="fas fa-play text-gray-300 text-[9px]"></i>
                                </button>
                                <button class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600">
                                    <i class="fas fa-expand-alt text-gray-300 text-[9px]"></i>
                                </button>
                            </div>
                        </div>
                        <div id="current-category" class="text-center text-[9px] font-medium text-gray-400 mb-1 hidden"></div>
                        <div id="bar-perbulan-chart" class="apex-charts flex-grow" style="min-height: 150px;"></div>
                    </div>

                    <div class="bg-gray-800 rounded-2xl shadow-lg p-2 border border-gray-700 flex-grow flex flex-col">
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="font-bold text-white text-[10px]">Tren Perkembangan Gizi</h2>
                            <div class="flex space-x-1">
                                <button id="toggle-auto-slide-2" class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600">
                                    <i class="fas fa-play text-gray-300 text-[9px]"></i>
                                </button>
                                <button class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600">
                                    <i class="fas fa-expand-alt text-gray-300 text-[9px]"></i>
                                </button>
                            </div>
                        </div>
                        <div id="current-category-2" class="text-center text-[9px] font-medium text-gray-400 mb-1 hidden"></div>
                        <div id="line-perbulan-chart" class="apex-charts flex-grow" style="min-height: 150px;"></div>
                    </div>
                </div>

                <!-- Middle Column: Map (Larger) -->
                <div class="bg-gray-800 rounded-2xl shadow-lg p-2 border border-gray-700 flex flex-col h-full lg:col-span-2">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-bold text-white text-[10px]">Peta Sebaran Status Gizi</h2>
                        <button class="p-1 rounded-lg bg-gray-700 hover:bg-gray-600">
                            <i class="fas fa-expand-alt text-gray-300 text-[9px]"></i>
                        </button>
                    </div>
                    <div id="map" class="w-full rounded-lg flex-grow" style="min-height: 250px;"></div>
                </div>

                <!-- Right Column: Only Stats (Removed Data Posyandu and Kategori Status Gizi) -->
                <div class="flex flex-col gap-4 h-full">
                    <!-- Empty column for balance -->
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
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .info,
        .legend {
            padding: 4px;
            font: 9px 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: rgba(30, 30, 30, 0.95);
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.5);
            border-radius: 3px;
            color: #e0e0e0;
        }

        .info h4 {
            margin-bottom: 2px;
            color: #ffffff;
            font-size: 9px;
        }
        
        .info span {
            font-size: 8px;
            color: #cccccc;
        }
        
        .info strong {
            font-size: 8px;
            color: #ffffff;
        }

        .legend i {
            width: 8px;
            height: 8px;
            float: left;
            margin-right: 2px;
            opacity: 0.8;
            border-radius: 1px;
        }
        
        .legend h4 {
            margin-bottom: 2px;
            font-size: 9px;
            color: #ffffff;
        }
        
        .legend span {
            font-size: 8px;
            color: #cccccc;
        }

        .leaflet-control-custom {
            background: #2d3748;
            padding: 4px;
            border-radius: 3px;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.5);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 150px;
            font-size: 9px;
        }

        .leaflet-control-custom label {
            font-size: 9px;
            font-weight: 600;
            margin-bottom: 2px;
            display: block;
            color: #e2e8f0;
        }

        .leaflet-control-custom select {
            width: 100%;
            padding: 2px 3px;
            font-size: 9px;
            border: 1px solid #4a5568;
            border-radius: 2px;
            background: #4a5568;
            color: #e2e8f0;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%23cbd5e0' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 3px center;
            background-size: 0.5rem;
            margin-bottom: 2px;
        }

        .leaflet-control-custom select:focus {
            outline: none;
            border-color: #63b3ed;
            box-shadow: 0 0 0 1px #63b3ed;
        }

        .leaflet-control-custom .form-check {
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .leaflet-control-custom .form-check-label {
            font-size: 9px;
            color: #e2e8f0;
        }

        .leaflet-control-custom .btn {
            width: 100%;
            padding: 2px 4px;
            font-size: 9px;
            background-color: #3182ce;
            color: white;
            border: none;
            border-radius: 2px;
        }

        .desa-label,
        .kecamatan-label,
        .kabupaten-label {
            font-weight: bold;
            text-shadow: 1px 1px 1px black;
            background: rgba(30, 30, 30, 0.7);
            padding: 1px 1px;
            border-radius: 1px;
            font-size: 5px;
            color: white;
        }

        .desa-label {
            color: green;
        }

        .kecamatan-label {
            color: blue;
            font-size: 6px;
        }

        .kabupaten-label {
            color: red;
            font-size: 7px;
        }
        
        /* Custom ApexCharts styling */
        .apexcharts-tooltip {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            border-radius: 0.25rem;
            font-size: 7px;
            background: #2d3748;
            color: #ffffff;
            border: 1px solid #4a5568;
        }
        
        .apexcharts-menu {
            border-radius: 0.25rem;
            background: #2d3748;
            color: #e2e8f0;
            border: 1px solid #4a5568;
        }
        
        .apexcharts-xaxis-label,
        .apexcharts-yaxis-label {
            font-size: 7px;
            fill: #ffffff;
        }
        
        .apexcharts-legend-text {
            font-size: 6px;
            fill: #ffffff;
        }
        
        .apexcharts-title-text {
            font-size: 8px;
            fill: #ffffff;
        }
        
        /* Category indicator styling */
        #current-category, #current-category-2 {
            background-color: #2d3748;
            border-radius: 0.25rem;
            padding: 0.125rem 0.25rem;
            display: inline-block;
            font-size: 0.5rem;
            color: #e2e8f0;
            border: 1px solid #4a5568;
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.featuregroup.subgroup@1.0.2/dist/leaflet.featuregroup.subgroup.js"></script>
    <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
    
    <script>
        // Nutrition Charts
        const dataGizi = @json($chartDataGizi);
        const filterMonth = "{{ $filterMonth ?? 'all' }}";
        console.log('Data Gizi:', dataGizi);

        function updateYear() {
            const selectedYear = document.getElementById('year-selector').value;
            const selectedCategory = document.getElementById('category-selector').value;
            Livewire.dispatch('updateFilters', {year: selectedYear, category: selectedCategory});
        }
        
        function applyFilters() {
            const selectedYear = document.getElementById('year-selector').value;
            const selectedMonth = document.getElementById('month-selector').value;
            const selectedCategory = document.getElementById('category-selector').value;
            
            // Update URL with new filter parameters
            const url = new URL(window.location);
            url.searchParams.set('year', selectedYear);
            url.searchParams.set('month', selectedMonth);
            url.searchParams.set('category', selectedCategory);
            window.location.href = url;
        }
        
        // Set the selected values when the page loads based on URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const year = urlParams.get('year');
            const month = urlParams.get('month');
            const category = urlParams.get('category');
            
            if (year) {
                document.getElementById('year-selector').value = year;
            }
            
            if (month) {
                document.getElementById('month-selector').value = month;
            }
            
            if (category) {
                document.getElementById('category-selector').value = category;
            }
            
            // Reinitialize map with new filter values
            setTimeout(initializeMap, 1000);
        });
        
        function refreshDashboardData() {
            // Reinitialize charts with filtered data
            initializeCharts();
        }

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

        // Variables for auto-sliding functionality
        let barChartInstance = null;
        let lineChartInstance = null;
        let currentCategoryIndex = 0;
        let autoSlideInterval = null;
        let isAutoSliding = false;
        
        // Variables for total children sliding functionality
        let totalChildrenSlideInterval = null;
        let currentTotalChildrenIndex = 0;

        // Function to initialize charts
        function initializeCharts() {
            // Check if ApexCharts is available
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts is not loaded');
                return;
            }

            // Clear any existing charts
            if (barChartInstance) {
                barChartInstance.destroy();
                barChartInstance = null;
            }
            
            if (lineChartInstance) {
                lineChartInstance.destroy();
                lineChartInstance = null;
            }

            // Create initial charts with all data
            createFullCharts();
            
            // Start auto-sliding after a delay
            setTimeout(() => {
                startAutoSlide();
                startTotalChildrenSlide();
            }, 3000);
        }
        
        // Function to start total children sliding
        function startTotalChildrenSlide() {
            // Clean up any existing intervals
            if (totalChildrenSlideInterval) {
                clearInterval(totalChildrenSlideInterval);
            }
            
            currentTotalChildrenIndex = 0;
            
            // Start new interval
            totalChildrenSlideInterval = setInterval(() => {
                slideTotalChildren();
            }, 5000); // Change every 5 seconds
            
            // Show first category immediately
            slideTotalChildren();
        }

        // Function to create charts with all categories
        function createFullCharts() {
            // Bar chart: All categories
            const barSeries = dataGizi.map(item => ({
                name: item.type,
                data: allMonths.map(m => item.dataPerMonth[m] || 0)
            }));

            const barOptions = {
                chart: {
                    type: 'bar',
                    height: '100%',
                    stacked: true,
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                series: barSeries,
                xaxis: {
                    categories: monthNames,
                    labels: {
                        style: {
                            fontSize: '7px',
                            colors: '#ffffff'
                        },
                        rotate: -45,
                        maxHeight: 40
                    }
                },
                title: {
                    text: undefined
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['transparent']
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " anak"
                        }
                    },
                    style: {
                        fontSize: '8px',
                        color: '#ffffff'
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '7px',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                colors: ['#3B82F6', '#10B981']
            };
            
            barChartInstance = new ApexCharts(document.querySelector("#bar-perbulan-chart"), barOptions);
            barChartInstance.render();

            // Line chart: All categories
            const lineSeries = dataGizi.map(item => ({
                name: item.type,
                data: allMonths.map(m => item.dataPerMonth[m] || 0)
            }));

            const lineOptions = {
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                markers: {
                    size: 2,
                    hover: {
                        size: 4
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: (val) => `${val} anak`
                    },
                    style: {
                        fontSize: '8px',
                        color: '#ffffff'
                    }
                },
                grid: {
                    borderColor: '#4a5568'
                },
                fill: {
                    type: 'solid',
                    opacity: 0.5
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '7px',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: lineSeries,
                xaxis: {
                    categories: monthNames,
                    labels: {
                        style: {
                            fontSize: '7px',
                            colors: '#ffffff'
                        },
                        rotate: -45
                    }
                },
                title: {
                    text: undefined
                },
                colors: ['#3B82F6', '#10B981']
            };
            
            lineChartInstance = new ApexCharts(document.querySelector("#line-perbulan-chart"), lineOptions);
            lineChartInstance.render();
        }

        // Function to start auto-sliding
        function startAutoSlide() {
            if (isAutoSliding) return;
            
            // Clean up any existing intervals/timeouts
            cleanupAutoSlide();
            
            isAutoSliding = true;
            isPaused = false;
            currentCategoryIndex = 0;
            
            // Start new interval
            autoSlideInterval = setInterval(() => {
                slideToNextCategory();
            }, 8000); // Change category every 8 seconds
            
            // Show first category immediately
            slideToNextCategory();
        }

        // Function to slide to the next category
        function slideToNextCategory() {
            if (dataGizi.length === 0) return;
            
            // Update index
            currentCategoryIndex = (currentCategoryIndex + 1) % dataGizi.length;
            const category = dataGizi[currentCategoryIndex];
            
            // Update bar chart with single category
            const barSeries = [{
                name: category.type,
                data: allMonths.map(m => category.dataPerMonth[m] || 0)
            }];
            
            // Use only blue and green colors, alternating between them
            const barColors = ['#3B82F6', '#10B981'];
            const barColor = barColors[currentCategoryIndex % barColors.length];
            
            barChartInstance.updateOptions({
                series: barSeries,
                chart: {
                    type: 'bar',
                    stacked: false
                },
                colors: [barColor],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '7px',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: '#ffffff'
                        }
                    }
                }
            });
            
            // Update line chart with single category
            const lineSeries = [{
                name: category.type,
                data: allMonths.map(m => category.dataPerMonth[m] || 0)
            }];
            
            // Use only blue and green colors, alternating between them
            const lineColors = ['#3B82F6', '#10B981'];
            const lineColor = lineColors[currentCategoryIndex % lineColors.length];
            
            lineChartInstance.updateOptions({
                series: lineSeries,
                colors: [lineColor],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '7px',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: '#ffffff'
                        }
                    }
                }
            });
            
            // Show current category name with colored badge
            const categoryElements = document.querySelectorAll('#current-category, #current-category-2');
            // Use only blue and green colors, alternating between them
            const colors = ['#3B82F6', '#10B981'];
            const color = colors[currentCategoryIndex % colors.length];
            
            categoryElements.forEach(element => {
                element.textContent = category.type;
                element.classList.remove('hidden');
                // Set background color based on category
                element.style.backgroundColor = color + '20'; // 20 = 12% opacity
                element.style.color = color;
                element.style.borderColor = color;
            });
        }
        
        // Function to slide total children count
        function slideTotalChildren() {
            if (dataGizi.length === 0) return;
            
            // Update index
            currentTotalChildrenIndex = (currentTotalChildrenIndex + 1) % (dataGizi.length + 1);
            
            const totalCountElement = document.getElementById('total-children-count');
            const detailElement = document.getElementById('total-children-detail');
            
            if (currentTotalChildrenIndex === 0) {
                // Show total for all categories
                const totalChildren = array_sum(array_column(dataGizi, 'total'));
                totalCountElement.textContent = number_format(totalChildren);
                detailElement.textContent = 'Semua Kategori';
            } else {
                // Show total for specific category
                const categoryIndex = currentTotalChildrenIndex - 1;
                const category = dataGizi[categoryIndex];
                const categoryTotal = category.total;
                
                totalCountElement.textContent = number_format(categoryTotal);
                detailElement.textContent = category.type;
            }
        }
        
        // Helper functions for number formatting
        function number_format(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        function array_sum(array) {
            return array.reduce((a, b) => a + b, 0);
        }
        
        function array_column(array, column) {
            return array.map(item => item[column]);
        }

        // Function to get color for category based on index
        function getCategoryColor(categoryName) {
            // Define a set of distinct colors
            const colors = ['#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#8B5CF6', '#06B6D4', '#F97316', '#8B5CF6', '#EC4899', '#14B8A6'];
            
            // Create a hash from the category name to determine the color index
            let hash = 0;
            for (let i = 0; i < categoryName.length; i++) {
                hash = categoryName.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            // Use the hash to select a color from the array
            const index = Math.abs(hash) % colors.length;
            const color = colors[index];
            
            return color;
        }

        // Function to stop auto-sliding
        function stopAutoSlide() {
            isAutoSliding = false;
            isPaused = false;
            
            // Clean up intervals and timeouts
            cleanupAutoSlide();
            
            // Stop total children sliding
            if (totalChildrenSlideInterval) {
                clearInterval(totalChildrenSlideInterval);
                totalChildrenSlideInterval = null;
            }
            
            // Reset total children display to show all categories
            const totalChildren = array_sum(array_column(dataGizi, 'total'));
            document.getElementById('total-children-count').textContent = number_format(totalChildren);
            document.getElementById('total-children-detail').textContent = 'Semua Kategori';
            
            // Hide category indicators
            const categoryElements = document.querySelectorAll('#current-category, #current-category-2');
            categoryElements.forEach(element => {
                element.classList.add('hidden');
            });
            
            // Restore full charts
            createFullCharts();
        }

        // Initialize charts when DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Set the selected values when the page loads based on URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const year = urlParams.get('year');
            const month = urlParams.get('month');
            const category = urlParams.get('category');
            
            if (year) {
                document.getElementById('year-selector').value = year;
            }
            
            if (month) {
                document.getElementById('month-selector').value = month;
            }
            
            if (category) {
                document.getElementById('category-selector').value = category;
            }
            
            // Initialize charts
            setTimeout(initializeCharts, 1000);
            
            // Initialize map
            setTimeout(initializeMap, 1500);
            
            // Add event listeners for auto-slide toggle buttons
            document.getElementById('toggle-auto-slide').addEventListener('click', function() {
                toggleAutoSlide();
            });
            
            document.getElementById('toggle-auto-slide-2').addEventListener('click', function() {
                toggleAutoSlide();
            });
            
            // Add hover events to pause/resume auto-slide
            const barChartContainer = document.getElementById('bar-perbulan-chart');
            const lineChartContainer = document.getElementById('line-perbulan-chart');
            
            if (barChartContainer && lineChartContainer) {
                barChartContainer.addEventListener('mouseenter', function() {
                    if (isAutoSliding) {
                        pauseAutoSlide();
                    }
                });
                
                barChartContainer.addEventListener('mouseleave', function() {
                    if (isAutoSliding) {
                        resumeAutoSlide();
                    }
                });
                
                lineChartContainer.addEventListener('mouseenter', function() {
                    if (isAutoSliding) {
                        pauseAutoSlide();
                    }
                });
                
                lineChartContainer.addEventListener('mouseleave', function() {
                    if (isAutoSliding) {
                        resumeAutoSlide();
                    }
                });
            }
        });
        
        // Variables to track auto-slide pause state
        let isPaused = false;
        let pauseTimeout = null;
        
        // Function to pause auto-slide
        function pauseAutoSlide() {
            if (!isAutoSliding || isPaused) return;
            
            isPaused = true;
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
                autoSlideInterval = null;
            }
        }
        
        // Function to resume auto-slide
        function resumeAutoSlide() {
            if (!isAutoSliding || !isPaused) return;
            
            isPaused = false;
            // Resume after a short delay
            if (pauseTimeout) {
                clearTimeout(pauseTimeout);
            }
            
            pauseTimeout = setTimeout(() => {
                if (isAutoSliding && !isPaused) {
                    // Reduced from 8 seconds to 5 seconds
                    autoSlideInterval = setInterval(() => {
                        slideToNextCategory();
                    }, 5000);
                }
            }, 2000);
        }
        
        // Function to clean up timeouts and intervals
        function cleanupAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
                autoSlideInterval = null;
            }
            
            if (totalChildrenSlideInterval) {
                clearInterval(totalChildrenSlideInterval);
                totalChildrenSlideInterval = null;
            }
            
            if (pauseTimeout) {
                clearTimeout(pauseTimeout);
                pauseTimeout = null;
            }
        }
        
        // Function to toggle auto-sliding
        function toggleAutoSlide() {
            if (isAutoSliding) {
                stopAutoSlide();
                // Update button icons
                document.querySelectorAll('#toggle-auto-slide i, #toggle-auto-slide-2 i').forEach(icon => {
                    icon.classList.remove('fa-pause');
                    icon.classList.add('fa-play');
                });
            } else {
                startAutoSlide();
                // Update button icons
                document.querySelectorAll('#toggle-auto-slide i, #toggle-auto-slide-2 i').forEach(icon => {
                    icon.classList.remove('fa-play');
                    icon.classList.add('fa-pause');
                });
            }
        }

        function refreshDashboardData() {
            // Reinitialize charts with filtered data
            initializeCharts();
            
            // Refresh map with filtered data
            // Reinitialize map after Livewire update
            setTimeout(initializeMap, 500);
        }

        // Map Visualization
        let stuntingMap = null;
        let currentBoundaryType = 'kecamatan';
        let countDataCache = {};
        let mapInitialized = false;
        let legend = null; // Keep track of legend instance
        
        // Function to initialize the map
        function initializeMap() {
            // Check if map container exists
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }
            
            // Check if map already exists and remove it
            if (stuntingMap) {
                stuntingMap.remove();
                stuntingMap = null;
            }
            
            // Clear cache
            countDataCache = {};
            
            // Initialize map with stunting visualization
            stuntingMap = L.map('map').setView([-8.357307747936277, 116.2580360327694], 11);

            // Create a dark background instead of default Leaflet tiles
            const tiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(stuntingMap);

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
                    this._div.innerHTML = `<h4 class="font-bold text-white text-[10px]">Data Stunting ${label}</h4><strong class="text-blue-400 text-[10px]">${props.Name}</strong><br><span class="text-[10px] text-gray-300">Jumlah: <strong class="text-red-400">${count}</strong> anak</span>`;
                } else {
                    this._div.innerHTML = `<h4 class="font-bold text-white text-[10px]">Data Stunting ${label}</h4><span class="text-[10px] text-gray-300">Arahkan kursor ke ${label}</span>`;
                }
            };

            info.addTo(stuntingMap);

            // Create custom control for map controls
            const MapControls = L.Control.extend({
                options: {
                    position: 'topleft'
                },
                
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-control-custom');
                    
                    // Boundary selector
                    container.innerHTML = `
                        <label for="boundary-selector" class="font-bold text-gray-700">Batas</label>
                        <select class="form-select w-full mb-2" id="boundary-selector">
                            <option value="kecamatan">Kecamatan</option>
                            <option value="desa">Desa</option>
                        </select>
                        
                        <label for="kecamatan-select" class="text-xs">Kecamatan</label>
                        <select class="form-select w-full mb-1" id="kecamatan-select">
                            <option value="">Semua</option>
                            @foreach ($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                            @endforeach
                        </select>
                        
                        <label for="desa-select" class="text-xs">Desa</label>
                        <select class="form-select w-full mb-2" id="desa-select" disabled>
                            <option value="">Semua</option>
                        </select>
                        
                        <div class="flex items-center mb-2">
                            <input class="form-check-input mr-1" type="checkbox" id="show-posyandu">
                            <label class="form-check-label text-xs" for="show-posyandu">Tampilkan Posyandu</label>
                        </div>
                        
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-1 px-2 rounded text-[9px]" id="reset-map">Reset</button>
                    `;
                    
                    // Prevent map events from being triggered when interacting with controls
                    L.DomEvent.disableClickPropagation(container);
                    L.DomEvent.disableScrollPropagation(container);
                    
                    return container;
                }
            });
            
            // Add control to map
            new MapControls().addTo(stuntingMap);

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
                // Get current filter values from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const selectedYear = urlParams.get('year') || document.getElementById('year-selector').value;
                const selectedMonth = urlParams.get('month') || document.getElementById('month-selector').value;
                const selectedCategory = urlParams.get('category') || document.getElementById('category-selector').value;
                
                // Add filter parameters to the endpoint
                const endpoint = boundaryType === 'kecamatan'
                    ? `/getAllCountStunting?params=${encodeURIComponent(JSON.stringify(names))}&year=${selectedYear}&month=${selectedMonth}&category=${selectedCategory}`
                    : `/getAllCountGiziForDesa?params=${encodeURIComponent(JSON.stringify(names))}&year=${selectedYear}&month=${selectedMonth}&category=${selectedCategory}`;

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
                    weight: 2,
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

            async function loadGeoJson(boundaryType) {
                const geoJsonFile = boundaryType === 'kecamatan'
                    ? '/geojson/Batas_Kecamatan.geojson'
                    : '/geojson/Batas_Desa_di_KLU.geojson';

                try {
                    const response = await fetch(geoJsonFile);
                    const data = await response.json();

                    // Fetch the stunting data for the selected boundary with filters
                    await fetchAllCountData(data.features, boundaryType);

                    geojson = L.geoJson(data, {
                        style: function(feature) {
                            const count = countDataCache[feature.properties.Name] || 0;
                            return {
                                weight: 1,
                                color: '#fff',
                                dashArray: '2',
                                fillOpacity: 0.7,
                                fillColor: getColor(count)
                            };
                        },
                        onEachFeature: onEachFeature
                    }).addTo(stuntingMap);

                    // Clear existing labels first
                    stuntingMap.eachLayer(function(layer) {
                        if (layer instanceof L.Marker && layer.options.icon && layer.options.icon.options.className === 'stunting-label') {
                            stuntingMap.removeLayer(layer);
                        }
                    });

                    if (boundaryType === 'kecamatan') {
                        data.features.forEach(feature => {
                            const count = countDataCache[feature.properties.Name] || 0;
                            const centroid = turf.centroid(feature);
                            const coords = centroid.geometry.coordinates;
                            L.marker([coords[1], coords[0]], {
                                icon: L.divIcon({
                                    className: 'stunting-label',
                                    html: `<div class="bg-white px-1 py-0.5 rounded-full shadow text-[9px] font-bold">${count}</div>`,
                                    iconSize: [30, 15]
                                })
                            }).addTo(stuntingMap);
                        });
                    } else if (boundaryType === 'desa') {
                        data.features.forEach(feature => {
                            const count = countDataCache[feature.properties.Name] || 0;
                            const centroid = turf.centroid(feature);
                            const coords = centroid.geometry.coordinates;
                            L.marker([coords[1], coords[0]], {
                                icon: L.divIcon({
                                    className: 'stunting-label',
                                    html: `<div class="bg-white px-1 py-0.5 rounded-full shadow text-[9px] font-bold">${count}</div>`,
                                    iconSize: [30, 15]
                                })
                            }).addTo(stuntingMap);
                        });
                    }

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
                    // Remove existing count labels
                    if (layer instanceof L.Marker && layer.options.icon && layer.options.icon.options.className === 'stunting-label') {
                        stuntingMap.removeLayer(layer);
                    }
                });
                // Remove existing legend if it exists
                if (legend) {
                    stuntingMap.removeControl(legend);
                    legend = null;
                }
                loadGeoJson(selectedBoundary);
                // Update the info control to reflect the new boundary type
                info.update();
            }

            // New function to filter map to show only selected kecamatan
            function filterMapToKecamatan(kecamatanName) {
                currentBoundaryType = 'kecamatan';
                stuntingMap.eachLayer(function(layer) {
                    if (layer instanceof L.GeoJSON) {
                        stuntingMap.removeLayer(layer);
                    }
                    // Remove existing count labels
                    if (layer instanceof L.Marker && layer.options.icon && layer.options.icon.options.className === 'stunting-label') {
                        stuntingMap.removeLayer(layer);
                    }
                });
                // Remove existing legend if it exists
                if (legend) {
                    stuntingMap.removeControl(legend);
                    legend = null;
                }

                // Load kecamatan GeoJSON and filter to show only selected kecamatan
                fetch('/geojson/Batas_Kecamatan.geojson')
                    .then(response => response.json())
                    .then(async data => {
                        // Filter features to only include selected kecamatan
                        const filteredFeatures = data.features.filter(feature => 
                            feature.properties.Name === kecamatanName
                        );
                        
                        // Create new GeoJSON with only filtered features
                        const filteredData = {
                            ...data,
                            features: filteredFeatures
                        };

                        // Fetch the stunting data for the selected kecamatan with filters
                        await fetchAllCountData(filteredFeatures, 'kecamatan');

                        geojson = L.geoJson(filteredData, {
                            style: function(feature) {
                                const count = countDataCache[feature.properties.Name] || 0;
                                return {
                                    weight: 1,
                                    color: '#fff',
                                    dashArray: '2',
                                    fillOpacity: 0.7,
                                    fillColor: getColor(count)
                                };
                            },
                            onEachFeature: onEachFeature
                        }).addTo(stuntingMap);

                        // Add count label for selected kecamatan
                        filteredFeatures.forEach(feature => {
                            const count = countDataCache[feature.properties.Name] || 0;
                            const centroid = turf.centroid(feature);
                            const coords = centroid.geometry.coordinates;
                            L.marker([coords[1], coords[0]], {
                                icon: L.divIcon({
                                    className: 'stunting-label',
                                    html: `<div class="bg-white px-1 py-0.5 rounded-full shadow text-[9px] font-bold">${count}</div>`,
                                    iconSize: [30, 15]
                                })
                            }).addTo(stuntingMap);
                        });

                        // Zoom to the selected kecamatan
                        if (filteredFeatures.length > 0) {
                            const bounds = geojson.getBounds();
                            stuntingMap.fitBounds(bounds);
                        }

                        addLegend();
                        info.update();
                    })
                    .catch(error => {
                        console.error('Error loading kecamatan GeoJSON:', error);
                    });
            }

            // New function to filter map to show only selected desa
            function filterMapToDesa(desaName) {
                currentBoundaryType = 'desa';
                stuntingMap.eachLayer(function(layer) {
                    if (layer instanceof L.GeoJSON) {
                        stuntingMap.removeLayer(layer);
                    }
                    // Remove existing count labels
                    if (layer instanceof L.Marker && layer.options.icon && layer.options.icon.options.className === 'stunting-label') {
                        stuntingMap.removeLayer(layer);
                    }
                });
                // Remove existing legend if it exists
                if (legend) {
                    stuntingMap.removeControl(legend);
                    legend = null;
                }

                // Load desa GeoJSON and filter to show only selected desa
                fetch('/geojson/Batas_Desa_di_KLU.geojson')
                    .then(response => response.json())
                    .then(async data => {
                        // Filter features to only include selected desa
                        const filteredFeatures = data.features.filter(feature => 
                            feature.properties.Name === desaName
                        );
                        
                        // Create new GeoJSON with only filtered features
                        const filteredData = {
                            ...data,
                            features: filteredFeatures
                        };

                        // Fetch the stunting data for the selected desa with filters
                        await fetchAllCountData(filteredFeatures, 'desa');

                        geojson = L.geoJson(filteredData, {
                            style: function(feature) {
                                const count = countDataCache[feature.properties.Name] || 0;
                                return {
                                    weight: 1,
                                    color: '#fff',
                                    dashArray: '2',
                                    fillOpacity: 0.7,
                                    fillColor: getColor(count)
                                };
                            },
                            onEachFeature: onEachFeature
                        }).addTo(stuntingMap);

                        // Add count label for selected desa
                        filteredFeatures.forEach(feature => {
                            const count = countDataCache[feature.properties.Name] || 0;
                            const centroid = turf.centroid(feature);
                            const coords = centroid.geometry.coordinates;
                            L.marker([coords[1], coords[0]], {
                                icon: L.divIcon({
                                    className: 'stunting-label',
                                    html: `<div class="bg-white px-1 py-0.5 rounded-full shadow text-[9px] font-bold">${count}</div>`,
                                    iconSize: [30, 15]
                                })
                            }).addTo(stuntingMap);
                        });

                        // Zoom to the selected desa
                        if (filteredFeatures.length > 0) {
                            const bounds = geojson.getBounds();
                            stuntingMap.fitBounds(bounds);
                        }

                        addLegend();
                        info.update();
                    })
                    .catch(error => {
                        console.error('Error loading desa GeoJSON:', error);
                    });
            }

            function addLegend() {
                // Remove existing legend if it exists
                if (legend) {
                    stuntingMap.removeControl(legend);
                    legend = null;
                }
                
                legend = L.control({ position: 'bottomright' });

                legend.onAdd = function(map) {
                    const div = L.DomUtil.create('div', 'info legend');
                    const grades = [0, 10, 20, 50, 100, 200, 500, 1000];
                    div.innerHTML = '<h4 class="font-bold mb-1 text-[10px]">Legenda</h4>';
                    for (let i = 0; i < grades.length; i++) {
                        div.innerHTML +=
                            `<div class="flex items-center mb-0.5"><i style="background:${getColor(grades[i] + 1)}" class="w-3 h-3 mr-1"></i> <span class="text-[9px]">${grades[i]}&ndash;${grades[i + 1] || '+'}</span></div>`;
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
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -24]
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
                        <div class="font-semibold text-[10px] mb-1">${pos.nama_posyandu}</div>
                        <div class="text-[9px]">
                            <div class="flex justify-between py-0.5 border-b">
                                <span class="font-medium">Dusun:</span>
                                <span>${pos.nama_dusun}</span>
                            </div>
                            <div class="flex justify-between py-0.5 border-b">
                                <span class="font-medium">Desa:</span>
                                <span>${pos.nama_desa}</span>
                            </div>
                            <div class="flex justify-between py-0.5">
                                <span class="font-medium">Kec:</span>
                                <span>${pos.kecamatan}</span>
                            </div>
                        </div>
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

            // Don't add posyandu layer to map by default (unchecked)
            // posyanduLayer.addTo(stuntingMap);
            
            // Explicitly ensure the posyandu layer is not on the map initially
            if (stuntingMap.hasLayer(posyanduLayer)) {
                stuntingMap.removeLayer(posyanduLayer);
            }
            
            // Ensure the show-posyandu checkbox is unchecked by default
            $('#show-posyandu').prop('checked', false);

            // Event handlers for UI controls
            document.getElementById('boundary-selector').addEventListener('change', function() {
                const selectedBoundary = this.value;
                // Reset kecamatan and desa selectors when boundary type changes
                $('#kecamatan-select').val('');
                $('#desa-select').val('').prop('disabled', true);
                currentBoundaryType = selectedBoundary;
                updateBoundary(selectedBoundary);
            });

            // Initialize with kecamatan as default
            $('#boundary-selector').val('kecamatan');
            currentBoundaryType = 'kecamatan';

            $('#kecamatan-select').on('change', function() {
                const selectedKecamatan = $(this).val();

                // Reset desa select
                $('#desa-select').val('').prop('disabled', !selectedKecamatan);

                // Update desa options
                $('#desa-select').empty().append('<option value="">Semua</option>');

                if (selectedKecamatan) {
                    const desasInKecamatan = posyanduData
                        .filter(pos => pos.kecamatan === selectedKecamatan)
                        .map(pos => pos.nama_desa)
                        .filter((value, index, self) => self.indexOf(value) === index);

                    desasInKecamatan.forEach(desa => {
                        $('#desa-select').append(`<option value="${desa}">${desa}</option>`);
                    });

                    // Filter map to show only selected kecamatan
                    filterMapToKecamatan(selectedKecamatan);
                } else {
                    // Show all kecamatan
                    currentBoundaryType = 'kecamatan';
                    updateBoundary('kecamatan');
                }

                // Show posyandu in kecamatan only if checkbox is checked
                if ($('#show-posyandu').is(':checked')) {
                    posyanduLayer.clearLayers();
                    if (kecamatanGroups[selectedKecamatan]) {
                        kecamatanGroups[selectedKecamatan].addTo(posyanduLayer);
                    }
                    // Ensure posyandu layer is added to map
                    if (!stuntingMap.hasLayer(posyanduLayer)) {
                        posyanduLayer.addTo(stuntingMap);
                    }
                }
            });

            $('#desa-select').on('change', function() {
                const selectedDesa = $(this).val();

                if (selectedDesa) {
                    // Filter map to show only selected desa
                    filterMapToDesa(selectedDesa);
                    
                    // Show posyandu in desa only if checkbox is checked
                    if ($('#show-posyandu').is(':checked')) {
                        posyanduLayer.clearLayers();
                        if (desaGroups[selectedDesa]) {
                            desaGroups[selectedDesa].addTo(posyanduLayer);
                        }
                        // Ensure posyandu layer is added to map
                        if (!stuntingMap.hasLayer(posyanduLayer)) {
                            posyanduLayer.addTo(stuntingMap);
                        }
                    }
                } else {
                    // Show all desa or based on kecamatan selection
                    const selectedKecamatan = $('#kecamatan-select').val();
                    
                    if (selectedKecamatan) {
                        // Filter map to show only selected kecamatan
                        filterMapToKecamatan(selectedKecamatan);
                    } else {
                        // Show all desa
                        currentBoundaryType = 'desa';
                        updateBoundary('desa');
                    }

                    // Show posyandu in selected kecamatan or all
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
                        
                        // Ensure posyandu layer is added to map
                        if (!stuntingMap.hasLayer(posyanduLayer)) {
                            posyanduLayer.addTo(stuntingMap);
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
                    
                    // Add posyandu layer to map
                    posyanduLayer.addTo(stuntingMap);
                } else {
                    posyanduLayer.clearLayers();
                    stuntingMap.removeLayer(posyanduLayer);
                }
            });

            $('#reset-map').on('click', function() {
                $('#kecamatan-select').val('').trigger('change');
                $('#desa-select').val('').prop('disabled', true);
                stuntingMap.setView([-8.357307747936277, 116.2580360327694], 11);
                
                // Reset boundary selector to default
                $('#boundary-selector').val('kecamatan');
                currentBoundaryType = 'kecamatan';

                if ($('#show-posyandu').is(':checked')) {
                    posyanduLayer.clearLayers();
                    Object.values(desaGroups).forEach(group => {
                        group.addTo(posyanduLayer);
                    });
                    // Ensure posyandu layer is added to map only if not already added
                    if (!stuntingMap.hasLayer(posyanduLayer)) {
                        posyanduLayer.addTo(stuntingMap);
                    }
                } else {
                    // Remove posyandu layer if it exists
                    if (stuntingMap.hasLayer(posyanduLayer)) {
                        stuntingMap.removeLayer(posyanduLayer);
                    }
                }
                
                // Reload GeoJSON with default boundary type
                updateBoundary('kecamatan');
            });
            
            mapInitialized = true;
        }

        // Initialize map when DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Set the selected values when the page loads based on URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const year = urlParams.get('year');
            const month = urlParams.get('month');
            const category = urlParams.get('category');
            
            if (year) {
                document.getElementById('year-selector').value = year;
            }
            
            if (month) {
                document.getElementById('month-selector').value = month;
            }
            
            if (category) {
                document.getElementById('category-selector').value = category;
            }
            
            // Initialize charts
            setTimeout(initializeCharts, 1000);
            
            // Initialize map
            setTimeout(initializeMap, 1500);
            
            // Add event listeners for auto-slide toggle buttons
            document.getElementById('toggle-auto-slide').addEventListener('click', function() {
                toggleAutoSlide();
            });
            
            document.getElementById('toggle-auto-slide-2').addEventListener('click', function() {
                toggleAutoSlide();
            });
            
            // Add hover events to pause/resume auto-slide
            const barChartContainer = document.getElementById('bar-perbulan-chart');
            const lineChartContainer = document.getElementById('line-perbulan-chart');
            
            if (barChartContainer && lineChartContainer) {
                barChartContainer.addEventListener('mouseenter', function() {
                    if (isAutoSliding) {
                        pauseAutoSlide();
                    }
                });
                
                barChartContainer.addEventListener('mouseleave', function() {
                    if (isAutoSliding) {
                        resumeAutoSlide();
                    }
                });
                
                lineChartContainer.addEventListener('mouseenter', function() {
                    if (isAutoSliding) {
                        pauseAutoSlide();
                    }
                });
                
                lineChartContainer.addEventListener('mouseleave', function() {
                    if (isAutoSliding) {
                        resumeAutoSlide();
                    }
                });
            }
        });
        
        // Remove Livewire event listeners since we're not using Livewire for filtering anymore
        // These listeners are no longer needed
        
        // Also listen for the updated event
        document.addEventListener('livewire:updated', function () {
            // Reinitialize map after Livewire update
            setTimeout(initializeMap, 500);
        });
        
    </script>
@endpush