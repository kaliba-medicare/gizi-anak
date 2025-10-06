<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Command Center - Gizi Lombok Utara' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #0ea5e9;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            transition: background-color 0.3s, color 0.3s;
        }
        
        body.dark {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        
        .font-heading {
            font-family: 'Orbitron', sans-serif;
        }
        
        .font-mono {
            font-family: 'Roboto Mono', sans-serif;
        }
        
        /* Command Center Styles */
        .command-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .dark .command-header {
            background: linear-gradient(135deg, #0c4a6e 0%, #0ea5e9 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        
        .command-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .dark .command-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        
        .command-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .dark .command-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 4px;
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }
        
        .dark .nav-link.active {
            background: rgba(30, 41, 59, 0.5);
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .dark .nav-link:hover {
            background: rgba(30, 41, 59, 0.3);
        }
        
        .mode-toggle {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .mode-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .dark .mode-toggle {
            background: rgba(30, 41, 59, 0.5);
        }
        
        .dark .mode-toggle:hover {
            background: rgba(30, 41, 59, 0.7);
        }
        
        /* Status Indicators */
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-online {
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
        }
        
        .status-warning {
            background-color: #f59e0b;
            box-shadow: 0 0 8px #f59e0b;
        }
        
        .status-critical {
            background-color: #ef4444;
            box-shadow: 0 0 8px #ef4444;
        }
        
        /* Data Display */
        .data-display {
            font-family: 'Roboto Mono', monospace;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Footer */
        .command-footer {
            background: rgba(30, 41, 59, 0.9);
            color: #cbd5e1;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .nav-link {
                margin: 2px 0;
            }
        }
    </style>
    
    @stack('styles')
    @livewireStyles()
</head>
<body class="bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
    <!-- Command Center Header -->
    <header class="command-header text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-xl mr-4">
                            <i class="fas fa-satellite-dish text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="font-heading text-2xl font-bold">COMMAND CENTER</h1>
                            <p class="text-blue-100 text-sm">Gizi Lombok Utara Monitoring System</p>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <button id="mobileMenuButton" class="md:hidden text-white hover:text-blue-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Navigation -->
                <nav id="mobileMenu" class="hidden md:flex flex-col md:flex-row md:items-center mt-4 md:mt-0 space-y-2 md:space-y-0 md:space-x-2">
                    <a href="{{ route('home') }}" class="nav-link px-4 py-2 flex items-center {{ request()->routeIs('home') || request()->routeIs('frontend.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large mr-2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('grafik') }}" class="nav-link px-4 py-2 flex items-center {{ request()->routeIs('grafik') ? 'active' : '' }}">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>Grafik</span>
                    </a>
                    <a href="{{ route('map.desa') }}" class="nav-link px-4 py-2 flex items-center {{ request()->routeIs('map.desa') ? 'active' : '' }}">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        <span>Peta</span>
                    </a>
                </nav>
                
                <!-- Dark Mode Toggle -->
                <div class="flex items-center mt-4 md:mt-0">
                    <div class="mode-toggle" id="modeToggle">
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- System Status Bar -->
    <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="container mx-auto px-4 py-2">
            <div class="flex flex-wrap items-center justify-between text-sm">
                <div class="flex items-center mb-2 md:mb-0">
                    <span class="status-indicator status-online"></span>
                    <span class="font-medium">SISTEM AKTIF</span>
                    <span class="mx-2">•</span>
                    <span>Data Terkini: {{ now()->format('d M Y H:i') }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <i class="fas fa-database mr-1 text-blue-500"></i>
                        <span>Database: <span class="font-mono">ONLINE</span></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-server mr-1 text-green-500"></i>
                        <span>Server: <span class="font-mono">STABLE</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Title Section -->
    <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="font-heading text-2xl font-bold text-slate-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-slate-600 dark:text-slate-300 mt-1">@yield('page-description', 'Sistem monitoring status gizi anak Lombok Utara')</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                            <i class="fas fa-sync-alt mr-1"></i> Real-time
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        {{ $slot }}
    </main>
    
    <!-- Command Center Footer -->
    <footer class="command-footer mt-8">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-blue-400"></i>
                        <span class="font-medium">SISTEM MONITORING GIZI LOMBOK UTARA</span>
                    </div>
                    <p class="text-slate-400 text-sm mt-1">© {{ date('Y') }} Dinas Kesehatan Lombok Utara. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <div class="flex items-center">
                        <i class="fas fa-circle text-green-500 text-xs mr-1"></i>
                        <span class="text-sm">Operasional</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-circle text-blue-500 text-xs mr-1"></i>
                        <span class="text-sm">Terhubung</span>
                    </div>
                </div>
            </div>
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
    
    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeToggle = document.getElementById('modeToggle');
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            const body = document.body;
            const moonIcon = modeToggle.querySelector('i');
            
            // Check for saved theme preference or respect OS preference
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Apply saved theme or default to light mode
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                body.classList.add('dark');
                moonIcon.classList.remove('fa-moon');
                moonIcon.classList.add('fa-sun');
            } else {
                body.classList.remove('dark');
                moonIcon.classList.remove('fa-sun');
                moonIcon.classList.add('fa-moon');
            }
            
            // Toggle dark mode
            modeToggle.addEventListener('click', function() {
                body.classList.toggle('dark');
                
                if (body.classList.contains('dark')) {
                    moonIcon.classList.remove('fa-moon');
                    moonIcon.classList.add('fa-sun');
                    localStorage.setItem('theme', 'dark');
                } else {
                    moonIcon.classList.remove('fa-sun');
                    moonIcon.classList.add('fa-moon');
                    localStorage.setItem('theme', 'light');
                }
            });
            
            // Mobile menu toggle
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>