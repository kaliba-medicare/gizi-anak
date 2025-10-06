<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard Gizi Anak Lombok Utara' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar.expanded {
            transform: translateX(0);
        }
        
        .main-content {
            transition: margin-left 0.3s ease;
        }
        
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1000;
        }
        
        @media (min-width: 768px) {
            .sidebar.collapsed {
                transform: translateX(0);
                width: 70px;
            }
            
            .sidebar.collapsed .nav-text {
                display: none;
            }
            
            .sidebar.collapsed .logo-text {
                display: none;
            }
            
            .sidebar.collapsed .logo-icon {
                display: block;
            }
            
            .sidebar:not(.collapsed) {
                width: 250px;
            }
        }
    </style>
    
    @stack('styles')
    @livewireStyles()
</head>
<body class="bg-gray-50">
    <!-- Sidebar Toggle Button (Mobile) -->
    <button id="sidebarToggle" class="sidebar-toggle md:hidden bg-blue-600 text-white p-3 rounded-full shadow-lg z-50">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-white shadow-lg h-screen sticky top-0 z-40 expanded">
            <div class="p-5 border-b">
                <div class="flex items-center">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="ml-3 logo-text">
                        <h1 class="font-heading text-lg font-bold text-gray-800">Gizi Lombok</h1>
                        <p class="text-xs text-gray-500">Sistem Monitoring</p>
                    </div>
                    <div class="ml-3 logo-icon hidden">
                        <i class="fas fa-chart-line text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <nav class="mt-5 px-3">
                <a href="{{ route('frontend.dashboard') }}" class="flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('frontend.dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-th-large text-lg"></i>
                    <span class="ml-3 nav-text">Dashboard</span>
                </a>
                
                <a href="{{ route('home') }}" class="flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-chart-bar text-lg"></i>
                    <span class="ml-3 nav-text">Grafik</span>
                </a>
                
                <a href="{{ route('map.desa') }}" class="flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('map.desa') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-map-marked-alt text-lg"></i>
                    <span class="ml-3 nav-text">Peta Sebaran</span>
                </a>
                
                
                
                <div class="mt-8 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider nav-text">
                    Data Management
                </div>
                
                <a href="#" class="flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-database text-lg"></i>
                    <span class="ml-3 nav-text">Data Gizi</span>
                </a>
                
                <a href="#" class="flex items-center p-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600">
                    <i class="fas fa-clinic-medical text-lg"></i>
                    <span class="ml-3 nav-text">Posyandu</span>
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-full p-4 border-t">
                <div class="flex items-center">
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10"></div>
                    <div class="ml-3 nav-text">
                        <p class="text-sm font-medium text-gray-800">Admin User</p>
                        <p class="text-xs text-gray-500">admin@example.com</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content flex-1 md:ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h1 class="font-heading text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-600 text-sm">@yield('page-description', 'Sistem monitoring status gizi anak Lombok Utara')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell text-gray-600"></i>
                        </button>
                        
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-cog text-gray-600"></i>
                        </button>
                        
                        <div class="relative">
                            <button class="flex items-center space-x-2">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-8"></div>
                                <span class="hidden md:inline text-gray-700 font-medium">Admin User</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="p-4 md:p-6">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <footer class="bg-white border-t mt-8">
                <div class="p-4 text-center text-gray-600 text-sm">
                    <p>© {{ date('Y') }} Dinas Kesehatan Lombok Utara. All rights reserved.</p>
                </div>
            </footer>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    @stack('scripts')
    @livewireScripts()
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.querySelector('.main-content');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('expanded');
                sidebar.classList.toggle('collapsed');
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 768) {
                    sidebar.classList.remove('expanded');
                    sidebar.classList.add('collapsed');
                }
            });
        });
    </script>
</body>
</html>