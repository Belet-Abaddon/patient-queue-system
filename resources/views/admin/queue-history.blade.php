@extends('layouts.admin')

@section('title', 'Queue History')
@section('page-title', 'Queue Analytics & History')

@section('content')
<div class="space-y-6">
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold">Queue History Dashboard</h2>
                <p class="text-blue-100 mt-2">Track, analyze, and optimize patient flow</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex flex-wrap gap-3">
                    <button onclick="refreshData()" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl font-medium transition-all backdrop-blur-sm">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh
                    </button>
                    <button onclick="exportData()" class="px-4 py-2 bg-white text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-all">
                        <i class="fas fa-download mr-2"></i> Export
                    </button>
                    <button onclick="showAnalytics()" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl font-medium transition-all backdrop-blur-sm">
                        <i class="fas fa-chart-line mr-2"></i> Analytics
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Queues</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="totalQueues">0</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-500 font-medium">
                        <i class="fas fa-arrow-up mr-1"></i> 12.5%
                    </span>
                    <span class="text-gray-400 ml-2">vs last week</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Avg Wait Time</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="avgWaitTime">0 min</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-red-500 font-medium">
                        <i class="fas fa-arrow-down mr-1"></i> 8.3%
                    </span>
                    <span class="text-gray-400 ml-2">improved</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user-md text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Busiest Doctor</p>
                    <h3 class="text-lg font-bold text-gray-800 mt-1 truncate" id="busiestDoctor">Loading...</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-400">
                    <span id="busiestDoctorCount">0</span> patients today
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Completion Rate</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1" id="completionRate">0%</h3>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-green-500 font-medium">
                        <i class="fas fa-check-circle mr-1"></i> 94.2%
                    </span>
                    <span class="text-gray-400 ml-2">success rate</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Filters & Calendar -->
        <div class="lg:col-span-1">
            <!-- Date Range Picker -->
            <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Date Range
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                            <input type="date" id="fromDate" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                            <input type="date" id="toDate" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="setDateRange('today')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition-colors">Today</button>
                        <button onclick="setDateRange('week')" class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm transition-colors">This Week</button>
                        <button onclick="setDateRange('month')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition-colors">This Month</button>
                        <button onclick="setDateRange('year')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm transition-colors">This Year</button>
                    </div>
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-filter text-green-600 mr-2"></i>
                    Quick Filters
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                        <select id="doctorFilter" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">All Doctors</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select id="departmentFilter" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="toggleStatus('completed')" class="status-filter px-3 py-2 bg-green-100 text-green-700 rounded-lg text-sm transition-all" data-status="completed">
                                <i class="fas fa-check-circle mr-1"></i> Completed
                            </button>
                            <button onclick="toggleStatus('pending')" class="status-filter px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm transition-all" data-status="pending">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </button>
                            <button onclick="toggleStatus('cancelled')" class="status-filter px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm transition-all" data-status="cancelled">
                                <i class="fas fa-times-circle mr-1"></i> Cancelled
                            </button>
                        </div>
                    </div>
                    <button onclick="applyFilters()" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                </div>
            </div>

            <!-- Mini Calendar -->
            <div class="bg-white rounded-2xl shadow-lg p-5">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-week text-purple-600 mr-2"></i>
                    Week Overview
                </h3>
                <div class="grid grid-cols-7 gap-1" id="miniCalendar">
                    <!-- Mini calendar days will be loaded here -->
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Selected Period:</span>
                        <span class="font-medium text-gray-800" id="selectedPeriod">This Week</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Queue List & Analytics -->
        <div class="lg:col-span-2">
            <!-- Queue List Header -->
            <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Queue History</h3>
                        <p class="text-gray-500 text-sm mt-1" id="queueSummary">Loading queue data...</p>
                    </div>
                    <div class="mt-3 md:mt-0 flex items-center space-x-3">
                        <div class="relative">
                            <input type="text" id="searchQueue" placeholder="Search queues..." 
                                   class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all w-full md:w-64">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button onclick="clearSearch()" class="px-3 py-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Queue List -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <span>Patient</span>
                                        <button onclick="sortBy('patient')" class="ml-1 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <span>Doctor</span>
                                        <button onclick="sortBy('doctor')" class="ml-1 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <span>Time</span>
                                        <button onclick="sortBy('time')" class="ml-1 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <span>Duration</span>
                                        <button onclick="sortBy('duration')" class="ml-1 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-sort"></i>
                                        </button>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="queueTableBody" class="divide-y divide-gray-100">
                            <!-- Queue data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading State -->
                <div id="loadingQueues" class="p-12 text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600"></div>
                    <p class="mt-4 text-gray-500">Loading queue data...</p>
                </div>

                <!-- Empty State -->
                <div id="noQueues" class="p-12 text-center hidden">
                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">No Queues Found</h4>
                    <p class="text-gray-500 mb-6">No queue records match your search criteria</p>
                    <button onclick="clearFilters()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Clear Filters
                    </button>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="text-sm text-gray-500 mb-4 md:mb-0">
                            Showing <span class="font-medium" id="startRecord">0</span> to 
                            <span class="font-medium" id="endRecord">0</span> of 
                            <span class="font-medium" id="totalRecords">0</span> records
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="previousPage()" id="prevPageBtn" 
                                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div class="flex items-center space-x-1" id="pageNumbers">
                                <!-- Page numbers will be generated here -->
                            </div>
                            <button onclick="nextPage()" id="nextPageBtn" 
                                    class="px-3 py-2 border border-gray-200 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Preview -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-5 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold">Peak Hours</h4>
                        <i class="fas fa-chart-bar text-blue-200"></i>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Morning (8AM-12PM)</span>
                                <span class="font-semibold" id="morningPeak">0</span>
                            </div>
                            <div class="w-full bg-blue-400/30 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" id="morningBar" style="width: 40%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Afternoon (12PM-4PM)</span>
                                <span class="font-semibold" id="afternoonPeak">0</span>
                            </div>
                            <div class="w-full bg-blue-400/30 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" id="afternoonBar" style="width: 60%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Evening (4PM-8PM)</span>
                                <span class="font-semibold" id="eveningPeak">0</span>
                            </div>
                            <div class="w-full bg-blue-400/30 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full" id="eveningBar" style="width: 20%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-5 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold">Wait Time Analysis</h4>
                        <i class="fas fa-clock text-green-200"></i>
                    </div>
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold mb-1" id="avgWaitAnalysis">0 min</div>
                            <div class="text-green-200 text-sm">Average Wait Time</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <div class="text-lg font-semibold" id="fastQueue">0</div>
                                <div class="text-green-200 text-xs">Under 15 min</div>
                            </div>
                            <div>
                                <div class="text-lg font-semibold" id="mediumQueue">0</div>
                                <div class="text-green-200 text-xs">15-30 min</div>
                            </div>
                            <div>
                                <div class="text-lg font-semibold" id="slowQueue">0</div>
                                <div class="text-green-200 text-xs">30+ min</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->

<!-- Queue Details Modal -->
<div id="queueDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('queueDetailsModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Queue Details</h3>
                    <button onclick="closeModal('queueDetailsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-8">
                <div id="queueDetailsContent">
                    <!-- Queue details will be loaded here -->
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-8 py-6 border-t border-gray-100">
                <div class="flex justify-end space-x-3">
                    <button onclick="closeModal('queueDetailsModal')" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <button onclick="printQueueDetails()" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Modal -->
<div id="analyticsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('analyticsModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-6xl mx-auto">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                        Advanced Analytics
                    </h3>
                    <button onclick="closeModal('analyticsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-8">
                        <!-- Doctor Performance -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Doctor Performance</h4>
                            <div class="space-y-4" id="doctorPerformanceChart">
                                <!-- Doctor performance bars will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Status Distribution -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Status Distribution</h4>
                            <div class="flex items-center justify-center h-48">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Daily Trends -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Daily Trends</h4>
                            <div class="h-48">
                                <canvas id="dailyTrendsChart"></canvas>
                            </div>
                        </div>
                        
                        <!-- Peak Hours -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Peak Hours Analysis</h4>
                            <div class="h-48">
                                <canvas id="peakHoursChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global variables
    let queueHistory = [];
    let doctors = [];
    let departments = [];
    let currentPage = 1;
    let pageSize = 10;
    let totalPages = 1;
    let currentSort = { field: 'date', order: 'desc' };
    let activeStatusFilters = new Set(['completed', 'pending']);
    let statusChart = null;
    let dailyTrendsChart = null;
    let peakHoursChart = null;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeDates();
        loadData();
        setupEventListeners();
        loadQueueData();
    });

    // Setup event listeners
    function setupEventListeners() {
        // Search input
        document.getElementById('searchQueue').addEventListener('input', debounce(loadQueueData, 300));
        
        // Date inputs
        document.getElementById('fromDate').addEventListener('change', loadQueueData);
        document.getElementById('toDate').addEventListener('change', loadQueueData);
        
        // Filters
        document.getElementById('doctorFilter').addEventListener('change', loadQueueData);
        document.getElementById('departmentFilter').addEventListener('change', loadQueueData);
        
        // Page size
        document.getElementById('pageSize')?.addEventListener('change', function() {
            pageSize = parseInt(this.value);
            currentPage = 1;
            loadQueueData();
        });
    }

    // Initialize dates
    function initializeDates() {
        const today = new Date();
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(today.getDate() - 7);
        
        document.getElementById('fromDate').value = oneWeekAgo.toISOString().split('T')[0];
        document.getElementById('toDate').value = today.toISOString().split('T')[0];
    }

    // Load data
    function loadData() {
        // Load from localStorage or use sample data
        const savedQueueHistory = localStorage.getItem('queue_history');
        const savedDoctors = localStorage.getItem('hospital_doctors');
        const savedDepartments = localStorage.getItem('hospital_departments');
        
        queueHistory = savedQueueHistory ? JSON.parse(savedQueueHistory) : getSampleQueueHistory();
        doctors = savedDoctors ? JSON.parse(savedDoctors) : getSampleDoctors();
        departments = savedDepartments ? JSON.parse(savedDepartments) : getSampleDepartments();
        
        populateDoctorFilter();
        populateDepartmentFilter();
        updateMiniCalendar();
        updateQuickStats();
    }

    // Get sample queue history
    function getSampleQueueHistory() {
        const sampleData = [];
        const today = new Date();
        
        // Generate data for last 30 days
        for (let i = 0; i < 30; i++) {
            const date = new Date(today);
            date.setDate(date.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            
            // Generate 5-15 queue entries per day
            const entriesPerDay = Math.floor(Math.random() * 10) + 5;
            
            for (let j = 0; j < entriesPerDay; j++) {
                const hour = Math.floor(Math.random() * 8) + 8; // 8 AM to 4 PM
                const minute = Math.floor(Math.random() * 60);
                const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                
                const waitTime = Math.floor(Math.random() * 45) + 5; // 5-50 minutes
                const serviceTime = Math.floor(Math.random() * 30) + 10; // 10-40 minutes
                
                const doctor = getRandomDoctor();
                const department = getRandomDepartment();
                const patient = getRandomPatient();
                const status = getRandomStatus();
                
                sampleData.push({
                    id: sampleData.length + 1,
                    patient_id: patient.id,
                    patient_name: patient.name,
                    patient_phone: patient.phone,
                    patient_age: patient.age,
                    patient_gender: patient.gender,
                    queue_number: Math.floor(Math.random() * 30) + 1,
                    doctor_id: doctor.id,
                    doctor_name: doctor.name,
                    doctor_specialization: doctor.specialization,
                    department_id: department.id,
                    department_name: department.name,
                    date: dateStr,
                    checkin_time: time,
                    start_time: addMinutes(time, waitTime),
                    end_time: addMinutes(time, waitTime + serviceTime),
                    wait_time: waitTime,
                    service_time: serviceTime,
                    total_time: waitTime + serviceTime,
                    status: status,
                    reason: getRandomReason(),
                    notes: getRandomNotes(),
                    room: doctor.room,
                    created_at: `${dateStr} ${time}:00`
                });
            }
        }
        
        return sampleData;
    }

    // Helper functions for sample data
    function getRandomDoctor() {
        const doctors = [
            { id: 1, name: "Dr. Michael Smith", specialization: "Cardiology", room: "201" },
            { id: 2, name: "Dr. Sarah Johnson", specialization: "Pediatrics", room: "305" },
            { id: 3, name: "Dr. Robert Williams", specialization: "General Medicine", room: "402" },
            { id: 4, name: "Dr. Emily Brown", specialization: "Dermatology", room: "109" },
            { id: 5, name: "Dr. David Wilson", specialization: "Orthopedics", room: "208" }
        ];
        return doctors[Math.floor(Math.random() * doctors.length)];
    }

    function getRandomDepartment() {
        const departments = [
            { id: 1, name: "Cardiology" },
            { id: 2, name: "Pediatrics" },
            { id: 3, name: "General Medicine" },
            { id: 4, name: "Dermatology" },
            { id: 5, name: "Orthopedics" }
        ];
        return departments[Math.floor(Math.random() * departments.length)];
    }

    function getRandomPatient() {
        const patients = [
            { id: 1, name: "John Doe", phone: "+1 (555) 123-4567", age: 45, gender: "Male" },
            { id: 2, name: "Jane Smith", phone: "+1 (555) 234-5678", age: 32, gender: "Female" },
            { id: 3, name: "Robert Johnson", phone: "+1 (555) 345-6789", age: 58, gender: "Male" },
            { id: 4, name: "Sarah Williams", phone: "+1 (555) 456-7890", age: 29, gender: "Female" },
            { id: 5, name: "Michael Brown", phone: "+1 (555) 567-8901", age: 36, gender: "Male" }
        ];
        return patients[Math.floor(Math.random() * patients.length)];
    }

    function getRandomStatus() {
        const statuses = ['completed', 'completed', 'completed', 'completed', 'pending', 'pending', 'cancelled'];
        return statuses[Math.floor(Math.random() * statuses.length)];
    }

    function getRandomReason() {
        const reasons = [
            "Routine Check-up",
            "Follow-up Visit",
            "Fever and Cough",
            "Blood Pressure Check",
            "Vaccination",
            "Injury Consultation"
        ];
        return reasons[Math.floor(Math.random() * reasons.length)];
    }

    function getRandomNotes() {
        const notes = [
            "Patient arrived on time",
            "All vitals normal",
            "Prescription given",
            "Follow-up scheduled",
            "Tests ordered"
        ];
        return notes[Math.floor(Math.random() * notes.length)];
    }

    function addMinutes(time, minutes) {
        const [hours, mins] = time.split(':').map(Number);
        const date = new Date();
        date.setHours(hours, mins + minutes);
        return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
    }

    function getSampleDoctors() {
        return [
            { id: 1, name: "Dr. Michael Smith", specialization: "Cardiology" },
            { id: 2, name: "Dr. Sarah Johnson", specialization: "Pediatrics" },
            { id: 3, name: "Dr. Robert Williams", specialization: "General Medicine" },
            { id: 4, name: "Dr. Emily Brown", specialization: "Dermatology" },
            { id: 5, name: "Dr. David Wilson", specialization: "Orthopedics" }
        ];
    }

    function getSampleDepartments() {
        return [
            { id: 1, name: "Cardiology" },
            { id: 2, name: "Pediatrics" },
            { id: 3, name: "General Medicine" },
            { id: 4, name: "Dermatology" },
            { id: 5, name: "Orthopedics" }
        ];
    }

    // Populate filters
    function populateDoctorFilter() {
        const select = document.getElementById('doctorFilter');
        select.innerHTML = '<option value="">All Doctors</option>';
        
        doctors.forEach(doctor => {
            const option = document.createElement('option');
            option.value = doctor.id;
            option.textContent = `${doctor.name} (${doctor.specialization})`;
            select.appendChild(option);
        });
    }

    function populateDepartmentFilter() {
        const select = document.getElementById('departmentFilter');
        select.innerHTML = '<option value="">All Departments</option>';
        
        departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.id;
            option.textContent = dept.name;
            select.appendChild(option);
        });
    }

    // Update mini calendar
    function updateMiniCalendar() {
        const container = document.getElementById('miniCalendar');
        const today = new Date();
        
        // Get start of current week (Sunday)
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() - today.getDay());
        
        let html = '';
        
        // Generate 7 days
        for (let i = 0; i < 7; i++) {
            const date = new Date(startOfWeek);
            date.setDate(startOfWeek.getDate() + i);
            
            const isToday = date.toDateString() === today.toDateString();
            const isSelected = i === today.getDay(); // Select current day
            
            const dayName = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][i];
            const dayNumber = date.getDate();
            
            // Count queues for this day
            const dateStr = date.toISOString().split('T')[0];
            const dayQueues = queueHistory.filter(q => q.date === dateStr).length;
            
            html += `
                <div class="text-center cursor-pointer ${isSelected ? 'bg-blue-100 rounded-lg' : ''}" 
                     onclick="selectDate('${dateStr}')">
                    <div class="text-xs font-medium ${isToday ? 'text-blue-600' : 'text-gray-500'} mb-1">
                        ${dayName}
                    </div>
                    <div class="text-lg font-semibold ${isToday ? 'text-blue-600' : 'text-gray-800'}">
                        ${dayNumber}
                    </div>
                    ${dayQueues > 0 ? `
                        <div class="mt-1 text-xs ${dayQueues > 10 ? 'text-red-500' : dayQueues > 5 ? 'text-yellow-500' : 'text-green-500'}">
                            ${dayQueues} queues
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        container.innerHTML = html;
    }

    // Load queue data with filters
    function loadQueueData() {
        // Show loading state
        document.getElementById('loadingQueues').style.display = 'block';
        document.getElementById('noQueues').style.display = 'none';
        document.getElementById('queueTableBody').innerHTML = '';
        
        // Get filter values
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        const doctorId = document.getElementById('doctorFilter').value;
        const departmentId = document.getElementById('departmentFilter').value;
        const searchTerm = document.getElementById('searchQueue').value.toLowerCase();
        
        // Filter queue history
        let filteredQueues = queueHistory.filter(queue => {
            // Date filter
            if (fromDate && queue.date < fromDate) return false;
            if (toDate && queue.date > toDate) return false;
            
            // Doctor filter
            if (doctorId && queue.doctor_id != doctorId) return false;
            
            // Department filter
            if (departmentId && queue.department_id != departmentId) return false;
            
            // Status filter
            if (!activeStatusFilters.has(queue.status)) return false;
            
            // Search filter
            if (searchTerm) {
                const searchFields = [
                    queue.patient_name,
                    queue.doctor_name,
                    queue.department_name,
                    queue.reason,
                    queue.queue_number.toString()
                ];
                if (!searchFields.some(field => field.toLowerCase().includes(searchTerm))) {
                    return false;
                }
            }
            
            return true;
        });
        
        // Apply sorting
        filteredQueues.sort((a, b) => {
            let aVal, bVal;
            
            switch(currentSort.field) {
                case 'patient':
                    aVal = a.patient_name;
                    bVal = b.patient_name;
                    break;
                case 'doctor':
                    aVal = a.doctor_name;
                    bVal = b.doctor_name;
                    break;
                case 'time':
                    aVal = new Date(`${a.date} ${a.checkin_time}`);
                    bVal = new Date(`${b.date} ${b.checkin_time}`);
                    break;
                case 'duration':
                    aVal = a.total_time;
                    bVal = b.total_time;
                    break;
                default: // date
                    aVal = new Date(a.date);
                    bVal = new Date(b.date);
            }
            
            if (currentSort.order === 'asc') {
                return aVal > bVal ? 1 : -1;
            } else {
                return aVal < bVal ? 1 : -1;
            }
        });
        
        // Update statistics
        updateQuickStats(filteredQueues);
        updateAnalyticsPreview(filteredQueues);
        
        // Update pagination
        const totalRecords = filteredQueues.length;
        totalPages = Math.ceil(totalRecords / pageSize);
        currentPage = Math.min(currentPage, totalPages);
        
        // Get current page data
        const startIndex = (currentPage - 1) * pageSize;
        const endIndex = Math.min(startIndex + pageSize, totalRecords);
        const pageData = filteredQueues.slice(startIndex, endIndex);
        
        // Update UI
        setTimeout(() => {
            document.getElementById('loadingQueues').style.display = 'none';
            
            if (totalRecords === 0) {
                document.getElementById('noQueues').style.display = 'block';
                resetPagination();
                return;
            }
            
            // Update queue summary
            document.getElementById('queueSummary').textContent = 
                `${totalRecords} queues found • Page ${currentPage} of ${totalPages}`;
            
            // Update pagination info
            document.getElementById('startRecord').textContent = startIndex + 1;
            document.getElementById('endRecord').textContent = endIndex;
            document.getElementById('totalRecords').textContent = totalRecords;
            
            // Update pagination buttons
            updatePaginationButtons();
            
            // Render table rows
            renderQueueTable(pageData);
        }, 500);
    }

    // Render queue table
    function renderQueueTable(queues) {
        const tableBody = document.getElementById('queueTableBody');
        
        let html = '';
        queues.forEach(queue => {
            const statusColor = getStatusColor(queue.status);
            const waitTimeColor = queue.wait_time > 30 ? 'text-red-600' : queue.wait_time > 15 ? 'text-yellow-600' : 'text-green-600';
            
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-sm font-bold text-blue-600">${queue.queue_number}</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${queue.patient_name}</div>
                                <div class="text-sm text-gray-500">${queue.patient_phone}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">${queue.doctor_name}</div>
                        <div class="text-sm text-gray-500">${queue.department_name}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-900">${formatDate(queue.date)}</div>
                        <div class="text-sm text-gray-500">${queue.checkin_time}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            <div class="text-sm">
                                <span class="text-gray-500">Wait:</span>
                                <span class="font-medium ml-1 ${waitTimeColor}">${queue.wait_time}m</span>
                            </div>
                            <div class="text-sm">
                                <span class="text-gray-500">Service:</span>
                                <span class="font-medium ml-1">${queue.service_time}m</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusColor}">
                            <i class="fas ${getStatusIcon(queue.status)} mr-1.5"></i>
                            ${queue.status}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <button onclick="viewQueueDetails(${queue.id})" 
                                    class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editQueue(${queue.id})" 
                                    class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }

    // Update quick stats
    function updateQuickStats(queues = queueHistory) {
        const totalQueues = queues.length;
        const completedQueues = queues.filter(q => q.status === 'completed').length;
        const completionRate = totalQueues > 0 ? Math.round((completedQueues / totalQueues) * 100) : 0;
        
        const totalWaitTime = queues.reduce((sum, q) => sum + q.wait_time, 0);
        const avgWaitTime = totalQueues > 0 ? Math.round(totalWaitTime / totalQueues) : 0;
        
        // Find busiest doctor
        const doctorCounts = {};
        queues.forEach(queue => {
            doctorCounts[queue.doctor_id] = (doctorCounts[queue.doctor_id] || 0) + 1;
        });
        
        let busiestDoctor = "No data";
        let busiestDoctorCount = 0;
        
        if (Object.keys(doctorCounts).length > 0) {
            const busiestId = Object.keys(doctorCounts).reduce((a, b) => doctorCounts[a] > doctorCounts[b] ? a : b);
            const doctor = doctors.find(d => d.id == busiestId);
            busiestDoctor = doctor ? doctor.name.split(' ')[1] : "Unknown";
            busiestDoctorCount = doctorCounts[busiestId];
        }
        
        // Update UI
        document.getElementById('totalQueues').textContent = totalQueues.toLocaleString();
        document.getElementById('avgWaitTime').textContent = `${avgWaitTime} min`;
        document.getElementById('busiestDoctor').textContent = `Dr. ${busiestDoctor}`;
        document.getElementById('busiestDoctorCount').textContent = busiestDoctorCount;
        document.getElementById('completionRate').textContent = `${completionRate}%`;
    }

    // Update analytics preview
    function updateAnalyticsPreview(queues) {
        // Peak hours analysis
        const morning = queues.filter(q => {
            const hour = parseInt(q.checkin_time.split(':')[0]);
            return hour >= 8 && hour < 12;
        }).length;
        
        const afternoon = queues.filter(q => {
            const hour = parseInt(q.checkin_time.split(':')[0]);
            return hour >= 12 && hour < 16;
        }).length;
        
        const evening = queues.filter(q => {
            const hour = parseInt(q.checkin_time.split(':')[0]);
            return hour >= 16 && hour < 20;
        }).length;
        
        const maxPeak = Math.max(morning, afternoon, evening);
        
        document.getElementById('morningPeak').textContent = morning;
        document.getElementById('afternoonPeak').textContent = afternoon;
        document.getElementById('eveningPeak').textContent = evening;
        
        document.getElementById('morningBar').style.width = maxPeak > 0 ? `${(morning / maxPeak) * 100}%` : '0%';
        document.getElementById('afternoonBar').style.width = maxPeak > 0 ? `${(afternoon / maxPeak) * 100}%` : '0%';
        document.getElementById('eveningBar').style.width = maxPeak > 0 ? `${(evening / maxPeak) * 100}%` : '0%';
        
        // Wait time analysis
        const totalWaitTime = queues.reduce((sum, q) => sum + q.wait_time, 0);
        const avgWaitTime = queues.length > 0 ? Math.round(totalWaitTime / queues.length) : 0;
        
        const fast = queues.filter(q => q.wait_time <= 15).length;
        const medium = queues.filter(q => q.wait_time > 15 && q.wait_time <= 30).length;
        const slow = queues.filter(q => q.wait_time > 30).length;
        
        document.getElementById('avgWaitAnalysis').textContent = `${avgWaitTime} min`;
        document.getElementById('fastQueue').textContent = fast;
        document.getElementById('mediumQueue').textContent = medium;
        document.getElementById('slowQueue').textContent = slow;
    }

    // Update pagination buttons
    function updatePaginationButtons() {
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');
        const pageNumbers = document.getElementById('pageNumbers');
        
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
        
        // Generate page numbers
        let html = '';
        const maxPagesToShow = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
        
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }
        
        // Previous pages indicator
        if (startPage > 1) {
            html += `<button onclick="goToPage(1)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-blue-600">1</button>`;
            if (startPage > 2) {
                html += `<span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>`;
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                html += `<button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg">${i}</button>`;
            } else {
                html += `<button onclick="goToPage(${i})" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-blue-600">${i}</button>`;
            }
        }
        
        // Next pages indicator
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>`;
            }
            html += `<button onclick="goToPage(${totalPages})" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:text-blue-600">${totalPages}</button>`;
        }
        
        pageNumbers.innerHTML = html;
    }

    function resetPagination() {
        document.getElementById('startRecord').textContent = '0';
        document.getElementById('endRecord').textContent = '0';
        document.getElementById('totalRecords').textContent = '0';
        document.getElementById('prevPageBtn').disabled = true;
        document.getElementById('nextPageBtn').disabled = true;
        document.getElementById('pageNumbers').innerHTML = '';
    }

    // Pagination functions
    function previousPage() {
        if (currentPage > 1) {
            currentPage--;
            loadQueueData();
        }
    }

    function nextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            loadQueueData();
        }
    }

    function goToPage(page) {
        currentPage = page;
        loadQueueData();
    }

    // Sorting
    function sortBy(field) {
        if (currentSort.field === field) {
            currentSort.order = currentSort.order === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.field = field;
            currentSort.order = 'desc';
        }
        loadQueueData();
    }

    // Status filter toggle
    function toggleStatus(status) {
        const button = event.currentTarget;
        
        if (activeStatusFilters.has(status)) {
            activeStatusFilters.delete(status);
            button.classList.remove('ring-2', 'ring-offset-2');
        } else {
            activeStatusFilters.add(status);
            button.classList.add('ring-2', 'ring-offset-2');
        }
        
        loadQueueData();
    }

    // Date range shortcuts
    function setDateRange(range) {
        const today = new Date();
        const fromDate = new Date(today);
        const toDate = new Date(today);
        
        switch(range) {
            case 'today':
                // Already set to today
                break;
            case 'week':
                fromDate.setDate(today.getDate() - 7);
                break;
            case 'month':
                fromDate.setMonth(today.getMonth() - 1);
                break;
            case 'year':
                fromDate.setFullYear(today.getFullYear() - 1);
                break;
        }
        
        document.getElementById('fromDate').value = fromDate.toISOString().split('T')[0];
        document.getElementById('toDate').value = toDate.toISOString().split('T')[0];
        loadQueueData();
    }

    // Apply filters
    function applyFilters() {
        loadQueueData();
        showNotification('Filters applied successfully!', 'success');
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        document.getElementById('doctorFilter').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('searchQueue').value = '';
        activeStatusFilters = new Set(['completed', 'pending']);
        
        // Reset status filter buttons
        document.querySelectorAll('.status-filter').forEach(btn => {
            const status = btn.dataset.status;
            if (status === 'completed' || status === 'pending') {
                btn.classList.add('ring-2', 'ring-offset-2');
            } else {
                btn.classList.remove('ring-2', 'ring-offset-2');
            }
        });
        
        loadQueueData();
        showNotification('All filters cleared!', 'success');
    }

    function clearSearch() {
        document.getElementById('searchQueue').value = '';
        loadQueueData();
    }

    function selectDate(dateStr) {
        document.getElementById('fromDate').value = dateStr;
        document.getElementById('toDate').value = dateStr;
        loadQueueData();
    }

    // View queue details
    function viewQueueDetails(queueId) {
        const queue = queueHistory.find(q => q.id === queueId);
        if (!queue) {
            showNotification('Queue not found!', 'error');
            return;
        }
        
        const content = `
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Queue #${queue.queue_number}</h4>
                        <p class="text-gray-500">${formatDate(queue.date)} at ${queue.checkin_time}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium ${getStatusColor(queue.status)}">
                        <i class="fas ${getStatusIcon(queue.status)} mr-1.5"></i>
                        ${queue.status}
                    </span>
                </div>
                
                <!-- Patient & Doctor Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5">
                        <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-injured text-blue-600 mr-2"></i>
                            Patient Information
                        </h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">${queue.patient_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span>${queue.patient_phone}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Age/Gender:</span>
                                <span>${queue.patient_age || 'N/A'} / ${queue.patient_gender || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-5">
                        <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-md text-green-600 mr-2"></i>
                            Doctor Information
                        </h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Doctor:</span>
                                <span class="font-medium">${queue.doctor_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Department:</span>
                                <span>${queue.department_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Room:</span>
                                <span>${queue.room || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Time Tracking -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <h5 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock text-purple-600 mr-2"></i>
                        Time Tracking
                    </h5>
                    <div class="space-y-4">
                        <!-- Timeline -->
                        <div class="relative pt-8">
                            <div class="absolute left-0 right-0 top-0 flex justify-between text-sm text-gray-600">
                                <span>Check-in</span>
                                <span>Consultation Start</span>
                                <span>Completed</span>
                            </div>
                            <div class="absolute left-0 right-0 top-4 text-xs text-gray-500">
                                <div class="flex justify-between">
                                    <span>${queue.checkin_time}</span>
                                    <span>${queue.start_time}</span>
                                    <span>${queue.end_time}</span>
                                </div>
                            </div>
                            <div class="relative h-2 bg-gray-200 rounded-full overflow-hidden mt-6">
                                <div class="absolute left-0 w-1/3 h-full bg-yellow-500"></div>
                                <div class="absolute left-1/3 w-2/3 h-full bg-green-500"></div>
                            </div>
                            <div class="absolute left-0 right-0 top-14 flex justify-between text-sm font-medium">
                                <span class="text-yellow-600">Wait: ${queue.wait_time} min</span>
                                <span class="text-green-600">Service: ${queue.service_time} min</span>
                            </div>
                        </div>
                        
                        <!-- Time Summary -->
                        <div class="grid grid-cols-3 gap-4 mt-8">
                            <div class="text-center">
                                <div class="text-2xl font-bold ${queue.wait_time > 30 ? 'text-red-600' : queue.wait_time > 15 ? 'text-yellow-600' : 'text-green-600'}">${queue.wait_time}</div>
                                <div class="text-sm text-gray-600">Wait Time</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">${queue.service_time}</div>
                                <div class="text-sm text-gray-600">Service Time</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800">${queue.total_time}</div>
                                <div class="text-sm text-gray-600">Total Time</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <h5 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-orange-600 mr-2"></i>
                        Additional Information
                    </h5>
                    <div class="space-y-3">
                        <div>
                            <div class="text-gray-600 mb-1">Reason for Visit</div>
                            <div class="font-medium">${queue.reason}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 mb-1">Notes</div>
                            <div>${queue.notes || 'No notes provided'}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('queueDetailsContent').innerHTML = content;
        openModal('queueDetailsModal');
    }

    // Show analytics modal
    function showAnalytics() {
        openModal('analyticsModal');
        
        // Initialize charts after modal opens
        setTimeout(() => {
            initializeCharts();
        }, 100);
    }

    // Initialize charts
    function initializeCharts() {
        // Status distribution chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusCounts = {
            completed: queueHistory.filter(q => q.status === 'completed').length,
            pending: queueHistory.filter(q => q.status === 'pending').length,
            cancelled: queueHistory.filter(q => q.status === 'cancelled').length
        };
        
        if (statusChart) statusChart.destroy();
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [statusCounts.completed, statusCounts.pending, statusCounts.cancelled],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
        
        // Daily trends chart
        const trendsCtx = document.getElementById('dailyTrendsChart').getContext('2d');
        const last7Days = Array.from({length: 7}, (_, i) => {
            const date = new Date();
            date.setDate(date.getDate() - i);
            return date.toISOString().split('T')[0];
        }).reverse();
        
        const dailyCounts = last7Days.map(date => 
            queueHistory.filter(q => q.date === date).length
        );
        
        if (dailyTrendsChart) dailyTrendsChart.destroy();
        dailyTrendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: last7Days.map(d => formatDateShort(d)),
                datasets: [{
                    label: 'Daily Queues',
                    data: dailyCounts,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Peak hours chart
        const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
        const hours = Array.from({length: 12}, (_, i) => `${i + 8}:00`);
        const hourCounts = hours.map(hour => {
            const hourNum = parseInt(hour);
            return queueHistory.filter(q => {
                const queueHour = parseInt(q.checkin_time.split(':')[0]);
                return queueHour === hourNum;
            }).length;
        });
        
        if (peakHoursChart) peakHoursChart.destroy();
        peakHoursChart = new Chart(peakCtx, {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [{
                    label: 'Patients per Hour',
                    data: hourCounts,
                    backgroundColor: 'rgba(139, 92, 246, 0.7)',
                    borderColor: 'rgb(139, 92, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Doctor performance
        const doctorPerformance = document.getElementById('doctorPerformanceChart');
        let html = '';
        
        doctors.forEach(doctor => {
            const doctorQueues = queueHistory.filter(q => q.doctor_id === doctor.id);
            const total = doctorQueues.length;
            const completed = doctorQueues.filter(q => q.status === 'completed').length;
            const rate = total > 0 ? Math.round((completed / total) * 100) : 0;
            
            html += `
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">${doctor.name.split(' ')[1]}</span>
                        <span>${rate}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: ${rate}%"></div>
                    </div>
                </div>
            `;
        });
        
        doctorPerformance.innerHTML = html;
    }

    // Utility functions
    function refreshData() {
        loadQueueData();
        showNotification('Data refreshed successfully!', 'success');
    }

    function exportData() {
        showNotification('Exporting data...', 'info');
        // In a real app, this would trigger a download
    }

    function editQueue(queueId) {
        showNotification('Edit feature coming soon!', 'info');
    }

    function printQueueDetails() {
        window.print();
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function formatDate(dateString) {
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    function formatDateShort(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', { weekday: 'short' });
    }

    function getStatusColor(status) {
        switch(status) {
            case 'completed': return 'bg-green-100 text-green-800';
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusIcon(status) {
        switch(status) {
            case 'completed': return 'fa-check-circle';
            case 'pending': return 'fa-clock';
            case 'cancelled': return 'fa-times-circle';
            default: return 'fa-question-circle';
        }
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg text-white transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' :
            type === 'error' ? 'bg-red-600' :
            type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-3"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('opacity-0', 'translate-y-[-100%]');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-y-[-100%]');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
</script>

<style>
    /* Custom animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Gradient borders */
    .border-l-4 {
        position: relative;
    }
    
    .border-l-4::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 2px;
    }
    
    /* Hover effects */
    tr {
        transition: all 0.2s ease;
    }
    
    tr:hover {
        transform: translateX(4px);
    }
    
    /* Glass effect */
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
    
    /* Card shadows */
    .shadow-lg {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .shadow-xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Button transitions */
    button {
        transition: all 0.2s ease;
    }
    
    button:active {
        transform: scale(0.98);
    }
    
    /* Input focus styles */
    input:focus, select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Status filter active state */
    .status-filter.ring-2 {
        box-shadow: 0 0 0 2px currentColor;
    }
    
    /* Loading spinner */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .bg-white {
            background: white !important;
            box-shadow: none !important;
        }
        
        .border {
            border-color: #e5e7eb !important;
        }
    }
</style>
@endsection