<header class="sticky top-0 z-10 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left: Menu button and breadcrumb -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button x-on:click="sidebarOpen = !sidebarOpen"
                class="p-2 text-gray-500 rounded-lg lg:hidden hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Breadcrumb -->
            <nav class="hidden md:flex items-center space-x-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    Dashboard
                </a>
                @hasSection('breadcrumb')
                <span class="text-gray-400">/</span>
                @yield('breadcrumb')
                @endif
            </nav>
        </div>

        <!-- Right: Search and notifications -->
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="hidden md:block relative">
                <input type="search"
                    placeholder="Search..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- User dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button x-on:click="open = !open"
                    class="flex items-center space-x-3 focus:outline-none">
                    <img class="w-8 h-8 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin User') }}&background=4F46E5&color=fff"
                        alt="Profile">
                    <span class="hidden md:inline text-sm font-medium text-gray-700">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</header>