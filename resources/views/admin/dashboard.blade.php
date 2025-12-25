@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Current Queue -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-primary-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Current Queue</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">16</h3>
                    <p class="text-primary-600 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        4 new in last hour
                    </p>
                </div>
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-primary-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Avg Wait Time -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-medical-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Average Wait Time</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">22 min</h3>
                    <p class="text-health-600 text-sm mt-1">
                        <i class="fas fa-arrow-down mr-1"></i>
                        5 min less than yesterday
                    </p>
                </div>
                <div class="w-14 h-14 bg-medical-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-medical-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Doctors Available -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-health-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Doctors Available</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">8/12</h3>
                    <p class="text-health-600 text-sm mt-1">
                        <i class="fas fa-check-circle mr-1"></i>
                        4 in consultation
                    </p>
                </div>
                <div class="w-14 h-14 bg-health-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-md text-health-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Emergency Cases -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emergency-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Emergency Cases</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">3</h3>
                    <p class="text-emergency-600 text-sm mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        1 critical in ER
                    </p>
                </div>
                <div class="w-14 h-14 bg-emergency-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ambulance text-emergency-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Active Queue -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-list-ol text-primary-600 mr-2"></i>
                            Active Patient Queue
                        </h3>
                        <button class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>Add Patient
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Queue #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Doctor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Wait Time
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Patient 1 - Emergency -->
                            <tr class="bg-red-50 urgent">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 bg-emergency-600 text-white text-sm font-bold rounded">E01</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe&background=ef4444&color=fff" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">John Doe</div>
                                            <div class="text-sm text-gray-500">MRN: P-2301</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Dr. Smith</div>
                                    <div class="text-sm text-gray-500">Emergency</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emergency-100 text-emergency-800">
                                        <i class="fas fa-ambulance mr-1"></i> Critical
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    5 min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-emergency-600 hover:text-emergency-900">
                                        <i class="fas fa-play-circle text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Patient 2 - Priority -->
                            <tr class="bg-yellow-50 priority">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 bg-warning-500 text-white text-sm font-bold rounded">P02</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Jane+Smith&background=f59e0b&color=fff" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Jane Smith</div>
                                            <div class="text-sm text-gray-500">MRN: P-2302</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Dr. Johnson</div>
                                    <div class="text-sm text-gray-500">General</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning-100 text-warning-800">
                                        <i class="fas fa-clock mr-1"></i> Waiting
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    35 min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-primary-600 hover:text-primary-900">
                                        <i class="fas fa-user-md text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Patient 3 - Normal -->
                            <tr class="hover:bg-blue-50 normal">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 bg-primary-500 text-white text-sm font-bold rounded">N03</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Robert+Brown&background=3b82f6&color=fff" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Robert Brown</div>
                                            <div class="text-sm text-gray-500">MRN: P-2303</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Dr. Williams</div>
                                    <div class="text-sm text-gray-500">Pediatrics</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                        <i class="fas fa-user-clock mr-1"></i> In Queue
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    18 min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-primary-600 hover:text-primary-900">
                                        <i class="fas fa-user-md text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Stats -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-bolt text-primary-600 mr-2"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <button class="p-4 bg-primary-50 hover:bg-primary-100 rounded-xl text-center transition-colors">
                            <i class="fas fa-user-plus text-primary-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-800">New Patient</p>
                        </button>
                        <button class="p-4 bg-medical-50 hover:bg-medical-100 rounded-xl text-center transition-colors">
                            <i class="fas fa-calendar-plus text-medical-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-800">Schedule</p>
                        </button>
                        <button class="p-4 bg-health-50 hover:bg-health-100 rounded-xl text-center transition-colors">
                            <i class="fas fa-file-prescription text-health-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-800">Prescription</p>
                        </button>
                        <button class="p-4 bg-warning-50 hover:bg-warning-100 rounded-xl text-center transition-colors">
                            <i class="fas fa-print text-warning-600 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-800">Reports</p>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Department Stats
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-hospital-user text-primary-600 mr-2"></i>
                        Department Load
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Emergency Room</span>
                            <span class="text-sm font-medium text-gray-700">85%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-emergency-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">General OPD</span>
                            <span class="text-sm font-medium text-gray-700">65%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary-500 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Pediatrics</span>
                            <span class="text-sm font-medium text-gray-700">45%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-medical-500 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Cardiology</span>
                            <span class="text-sm font-medium text-gray-700">30%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-health-500 h-2 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection