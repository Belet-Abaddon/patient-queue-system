<aside class="fixed inset-y-0 left-0 z-50 w-64 transform lg:translate-x-0 lg:static lg:inset-0 transition duration-200 ease-in-out"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
    x-cloak>

    <div class="flex flex-col h-full bg-gray-900">
        <!-- Logo -->
        <div class="flex items-center h-16 px-4 bg-gray-900 border-b border-gray-800">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">A</span>
                </div>
                <span class="text-xl font-bold text-white">AdminPanel</span>
            </div>
        </div>

        <!-- User Profile -->
        <div class="px-4 py-6 border-b border-gray-800">
            <div class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full"
                    src="https://ui-avatars.com/api/?name=&background=4F46E5&color=fff"
                    alt="Profile">
                <div>
                    <p class="text-sm font-medium text-white">hla hla</p>
                    <p class="text-xs text-gray-400">hlahla@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 text-sm text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <!-- Users -->
            <a href=""
                class="flex items-center px-4 py-3 text-sm text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.623a10.953 10.953 0 01-1.67-.623m-12 2.623h.01" />
                </svg>
                Users
            </a>

            <!-- Products -->
            <a href=""
                class="flex items-center px-4 py-3 text-sm text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Products
            </a>

            <!-- Orders -->
            <a href=""
                class="flex items-center px-4 py-3 text-sm text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Orders
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-4 py-3 text-sm text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>