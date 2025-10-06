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
        
        /* Navigation styles */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link.active {
            color: #3b82f6;
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #3b82f6;
            border-radius: 3px;
        }
        
        .nav-link:hover {
            color: #3b82f6;
        }
    </style>
    
    @stack('styles')
    @livewireStyles()
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-blue-600 text-white p-2 rounded-lg">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h1 class="font-heading text-xl font-bold text-gray-800">Gizi Lombok</h1>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <button id="mobileMenuButton" class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Navigation -->
                <nav id="mobileMenu" class="hidden md:flex flex-col md:flex-row md:space-x-8 mt-4 md:mt-0">
                    <a href="{{ route('frontend.dashboard') }}" class="nav-link py-2 md:py-0 {{ request()->routeIs('frontend.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('home') }}" class="nav-link py-2 md:py-0 {{ request()->routeIs('home') ? 'active' : '' }}">Grafik</a>
                    <a href="{{ route('map.desa') }}" class="nav-link py-2 md:py-0 {{ request()->routeIs('map.desa') ? 'active' : '' }}">Peta Sebaran</a>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Page Title Section -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-6">
            <h1 class="font-heading text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600 mt-1">@yield('page-description', 'Sistem monitoring status gizi anak Lombok Utara')</p>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        {{ $slot }}
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600 text-sm">
            <p>© {{ date('Y') }} Dinas Kesehatan Lombok Utara. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    @stack('scripts')
    @livewireScripts()
    
    <!-- Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>