<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen"
            class="fixed inset-0 z-40 flex lg:hidden"
            style="display: none;">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75"
                x-on:click="sidebarOpen = false"></div>
        </div>

        <!-- Sidebar -->
        @include('layouts.admin-sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1">
            <!-- Top Navigation -->
            @include('layouts.admin-header')

            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6">
                <!-- Page Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-description')
                    <p class="mt-2 text-sm text-gray-600">@yield('page-description')</p>
                    @endif
                </div>

                <!-- Page Content -->
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-4 py-3">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Your Company</p>
                    <p class="text-sm text-gray-600">v1.0.0</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>