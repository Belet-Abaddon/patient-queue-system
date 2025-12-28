<!-- Sidebar - Hospital Theme -->
<aside class="fixed inset-y-0 left-0 z-30 w-64 transform bg-gradient-to-b from-primary-800 to-primary-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
    x-cloak>

    <!-- Hospital Logo -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-primary-700">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-medical-400 to-health-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-hospital text-2xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold">City General</h1>
                <p class="text-xs text-primary-300">Queue System</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-primary-300 hover:text-white">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    <!-- Current Status -->
    <!-- <div class="p-4 border-b border-primary-700 bg-primary-750">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-primary-300">Queue Status</span>
            <span class="px-2 py-1 bg-health-500 text-white text-xs font-bold rounded-full">
                <i class="fas fa-play-circle mr-1"></i> LIVE
            </span>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-primary-700 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-white">12</div>
                <div class="text-xs text-primary-300 mt-1">Waiting</div>
            </div>
            <div class="bg-primary-700 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-white">4</div>
                <div class="text-xs text-primary-300 mt-1">In Progress</div>
            </div>
        </div>
    </div> -->

    <!-- Navigation Menu - Hospital Focus -->
    <nav class="flex-1 p-4 overflow-y-auto">
        <div class="mb-3">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" data-page="dashboard" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-clipboard-list w-5 mr-3 text-medical-300"></i>
                        <span>Queue Dashboard</span>
                        <span class="ml-auto bg-medical-500 text-xs px-2 py-1 rounded-full" data-badge="queue">16</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.appointment') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check w-5 mr-3 text-medical-300"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#" data-page="queue" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-users w-5 mr-3 text-medical-300"></i>
                        <span>Live Queue View</span>
                        <span class="ml-auto bg-warning-500 text-xs px-2 py-1 rounded-full animate-pulse">!</span>
                    </a>
                </li> -->
                <li>
                    <a href="{{ route('admin.patients') }}" data-page="patients" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-user-injured w-5 mr-3 text-medical-300"></i>
                        <span>Patient Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctors.management') }}" data-page="doctors" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-stethoscope w-5 mr-3 text-medical-300"></i>
                        <span>Doctors Schedule</span>
                        <span class="ml-auto bg-primary-600 text-xs px-2 py-1 rounded-full">8</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.queue-history') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-file-medical-alt w-5 mr-3 text-medical-300"></i>
                        <span>Queue History</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#" data-page="reports" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-chart-line w-5 mr-3 text-medical-300"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-prescription-bottle-alt w-5 mr-3 text-medical-300"></i>
                        <span>Prescriptions</span>
                    </a>
                </li> -->
            </ul>
        </div>
        <!-- Emergency Section -->
        <!-- <div class="mt-8 p-4 bg-gradient-to-r from-emergency-600 to-emergency-700 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-ambulance text-xl mr-3"></i>
                <h4 class="font-bold">Emergency Mode</h4>
            </div>
            <p class="text-sm text-emergency-100 mb-3">Activate for emergency situations</p>
            <button class="w-full py-2 bg-white text-emergency-700 font-bold rounded-lg hover:bg-emergency-100 transition-colors">
                <i class="fas fa-exclamation-triangle mr-2"></i>Activate
            </button>
        </div> -->
        <!-- Logout Button -->
        <div class="">
            <a href="#" class="flex items-center justify-center px-4 py-3 bg-primary-700 hover:bg-primary-600 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>