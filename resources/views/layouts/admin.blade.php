<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hospital Queue System</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Hospital Color Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',  // Main blue
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        medical: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',  // Medical cyan
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        health: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',  // Health green
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        warning: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',  // Warning amber
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        emergency: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',  // Emergency red
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome for medical icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active {
            background-color: #1e40af;
            color: white;
        }
        .sidebar-link:hover {
            background-color: #1e3a8a;
            color: white;
        }
        .queue-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .urgent {
            border-left: 4px solid #ef4444;
        }
        .priority {
            border-left: 4px solid #f59e0b;
        }
        .normal {
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: false, dropdownOpen: false, notifOpen: false }">
    <!-- Main Container -->
    <div class="flex h-screen">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
            x-transition.opacity 
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
            x-cloak>
        </div>

        <!-- Sidebar Component -->
        @include('layouts.admin-sidebar')

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Header Component -->
            @include('layouts.admin-header')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gradient-to-br from-blue-50 to-cyan-50">
                <!-- Page Title with Hospital Theme -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-primary-500 to-medical-400 rounded-xl mr-4">
                            <i class="fas fa-hospital text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800" id="pageTitle">Patient Queue Dashboard</h1>
                            <p class="text-gray-600 mt-2" id="pageDescription">Live monitoring of patient queue and appointments</p>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Content Area -->
                <div id="contentArea">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-600 text-sm mb-2 md:mb-0">
                        <i class="fas fa-heartbeat text-primary-600 mr-2"></i>
                        &copy; 2024 City General Hospital Queue System
                    </div>
                    <div class="text-gray-500 text-sm">
                        <span class="inline-flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-primary-500"></i>
                            HIPAA Compliant • Version 2.1.4
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Simple script for hospital queue system
        document.addEventListener('DOMContentLoaded', function() {
            // Get current page from URL hash
            const currentPage = window.location.hash.substring(1) || 'dashboard';
            
            // Hospital-specific page titles
            const pageTitles = {
                'dashboard': 'Queue Dashboard',
                'patients': 'Patient Management',
                'queue': 'Live Queue View',
                'doctors': 'Doctors Schedule',
                'appointments': 'Appointments',
                'reports': 'Medical Reports'
            };
            
            const pageDescriptions = {
                'dashboard': 'Live monitoring of patient queue and vital stats',
                'patients': 'Manage patient records and medical history',
                'queue': 'Real-time view of current patient queue',
                'doctors': 'View and manage doctor schedules',
                'appointments': 'Schedule and manage patient appointments',
                'reports': 'Generate and view medical reports'
            };
            
            // Update page info
            document.getElementById('pageTitle').textContent = pageTitles[currentPage] || 'Queue Dashboard';
            document.getElementById('pageDescription').textContent = pageDescriptions[currentPage] || 'Live monitoring of patient queue';
            
            // Set active link in sidebar
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-page') === currentPage) {
                    link.classList.add('active');
                }
            });
            
            // Handle sidebar link clicks
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!this.getAttribute('href')) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        window.location.hash = page;
                        
                        // Update UI
                        document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                        
                        document.getElementById('pageTitle').textContent = pageTitles[page] || 'Queue Dashboard';
                        document.getElementById('pageDescription').textContent = pageDescriptions[page] || 'Live monitoring of patient queue';
                        
                        // Close mobile sidebar
                        if (window.innerWidth < 1024) {
                            sidebarOpen = false;
                        }
                    }
                });
            });

            // Simulate queue updates (for design demo)
            setInterval(() => {
                const waitingBadge = document.querySelector('[data-badge="waiting"]');
                const queueBadge = document.querySelector('[data-badge="queue"]');
                if (waitingBadge && Math.random() > 0.7) {
                    const current = parseInt(waitingBadge.textContent);
                    waitingBadge.textContent = Math.max(0, current + (Math.random() > 0.5 ? 1 : -1));
                }
                if (queueBadge && Math.random() > 0.8) {
                    queueBadge.classList.add('queue-badge');
                    setTimeout(() => queueBadge.classList.remove('queue-badge'), 1000);
                }
            }, 5000);
        });
    </script>
</body>
</html>