<!-- Top Header - Hospital Theme -->
<header class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-4 py-3 md:px-6">
        <!-- Left Section -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Menu Button -->
            <button @click="sidebarOpen = true" class="lg:hidden text-primary-600 hover:text-primary-800">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Current Time & Date -->
            <div class="hidden md:flex flex-col">
                <div class="text-sm font-medium text-gray-900">
                    <i class="far fa-clock text-primary-500 mr-2"></i>
                    <span id="currentTime">08:45 AM</span>
                </div>
                <div class="text-xs text-gray-500">
                    <i class="far fa-calendar text-primary-400 mr-2"></i>
                    <span id="currentDate">Monday, October 16, 2024</span>
                </div>
            </div>

            <!-- Search Patient -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       placeholder="Search patient or MRN..." 
                       class="pl-10 pr-4 py-2 w-48 md:w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <!-- Emergency Alert -->
            <div class="hidden lg:block">
                <button class="px-4 py-2 bg-gradient-to-r from-emergency-500 to-emergency-600 text-white rounded-lg hover:from-emergency-600 hover:to-emergency-700 font-medium transition-all shadow-md">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Emergency
                </button>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button @click="notifOpen = !notifOpen" 
                        class="relative p-2 text-gray-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-emergency-500 rounded-full"></span>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="notifOpen" 
                     @click.away="notifOpen = false"
                     x-transition
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                     x-cloak>
                    <div class="p-4 border-b border-gray-100 bg-primary-50">
                        <div class="flex items-center">
                            <i class="fas fa-bell text-primary-600 mr-3"></i>
                            <div>
                                <h3 class="font-semibold text-gray-800">Medical Alerts</h3>
                                <p class="text-sm text-gray-600">3 urgent notifications</p>
                            </div>
                        </div>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="flex items-start px-4 py-3 hover:bg-red-50 border-b border-gray-100 urgent">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-ambulance text-red-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Emergency Case Arrived</p>
                                <p class="text-xs text-gray-500">Trauma patient in ER - Code Blue</p>
                                <p class="text-xs text-red-500 mt-1">2 minutes ago</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start px-4 py-3 hover:bg-yellow-50 border-b border-gray-100 priority">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Long Wait Time Alert</p>
                                <p class="text-xs text-gray-500">Patient #P-1023 waiting 45+ minutes</p>
                                <p class="text-xs text-yellow-500 mt-1">15 minutes ago</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start px-4 py-3 hover:bg-blue-50 normal">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-md text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Doctor Available</p>
                                <p class="text-xs text-gray-500">Dr. Smith is now available</p>
                                <p class="text-xs text-blue-500 mt-1">30 minutes ago</p>
                            </div>
                        </a>
                    </div>
                    <div class="p-3 border-t border-gray-100">
                        <a href="#" class="block text-center text-primary-600 hover:text-primary-800 text-sm font-medium">
                            <i class="fas fa-arrow-right mr-2"></i>View all alerts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current User -->
            <div class="relative">
                <button @click="dropdownOpen = !dropdownOpen" 
                        class="flex items-center space-x-3 focus:outline-none">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=Nurse+Sarah&background=0ea5e9&color=fff&size=128" 
                             alt="Medical Staff" 
                             class="w-9 h-9 rounded-full border-2 border-primary-200">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-health-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900">Nurse Sarah</p>
                        <p class="text-xs text-gray-500">Triage Nurse</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-500 hidden md:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="dropdownOpen" 
                     @click.away="dropdownOpen = false"
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                     x-cloak>
                    <div class="p-4 border-b border-gray-100 bg-primary-50">
                        <p class="text-sm font-medium text-gray-900">Sarah Johnson, RN</p>
                        <p class="text-xs text-gray-500">sarah.j@hospital.org</p>
                        <p class="text-xs text-primary-600 mt-1">
                            <i class="fas fa-shield-alt mr-1"></i>Triage Access Level
                        </p>
                    </div>
                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-user-circle w-4 mr-3 text-primary-500"></i>
                            My Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-cog w-4 mr-3 text-primary-500"></i>
                            Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-question-circle w-4 mr-3 text-primary-500"></i>
                            Help & Support
                        </a>
                    </div>
                    <div class="border-t border-gray-100 pt-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-emergency-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Status Bar -->
    <div class="bg-gradient-to-r from-primary-600 to-medical-500 text-white px-4 py-2">
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center space-x-6">
                <span class="flex items-center">
                    <i class="fas fa-user-clock mr-2"></i>
                    <span>Waiting: <strong class="ml-1">12</strong> patients</span>
                </span>
                <span class="flex items-center">
                    <i class="fas fa-user-md mr-2"></i>
                    <span>In Consultation: <strong class="ml-1">4</strong> patients</span>
                </span>
                <span class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Completed: <strong class="ml-1">28</strong> today</span>
                </span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-heartbeat mr-2"></i>
                <span>Avg. Wait Time: <strong class="ml-1">22 min</strong></span>
            </div>
        </div>
    </div>
</header>

<script>
    // Update time and date
    function updateDateTime() {
        const now = new Date();
        const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
    }
    
    updateDateTime();
    setInterval(updateDateTime, 60000);
</script>