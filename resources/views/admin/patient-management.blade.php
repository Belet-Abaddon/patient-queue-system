@extends('layouts.admin')

@section('title', 'Patient Management')
@section('page-title', 'Patients Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Statistics -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Patient Management</h2>
                <p class="text-gray-600 mt-1">Manage hospital patients and their medical records</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="openAddPatientModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-md hover:shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i> Register New Patient
                </button>
            </div>
        </div>
        
        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Patients</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="totalPatients">0</h3>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Active Appointments</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="activeAppointments">0</h3>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-user-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">New This Month</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="newThisMonth">0</h3>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-exclamation text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Follow-up Required</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="followUpRequired">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Patient List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-list text-blue-600 mr-2"></i>
                            Patient Records
                        </h3>
                        <div class="mt-2 md:mt-0 flex space-x-2">
                            <div class="relative">
                                <input type="text" id="searchPatient" placeholder="Search patients..." 
                                       class="border border-gray-300 rounded-lg px-4 py-2 pl-10 w-full md:w-64">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2">
                                <option value="all">All Patients</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="follow_up">Follow-up</option>
                                <option value="new">New Patients</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Patient Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Patient Info
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Appointments
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="patientTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Patient data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div id="noPatients" class="text-center py-12 hidden">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-user-injured text-gray-400 text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">No Patients Found</h4>
                        <p class="text-gray-600 mb-6">Start by registering your first patient</p>
                        <button onclick="openAddPatientModal()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-md">
                            <i class="fas fa-plus mr-2"></i> Register Patient
                        </button>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loadingPatients" class="text-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Loading patients...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Stats -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    <button onclick="sendAppointmentReminders()" class="w-full flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-bell text-blue-600"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-medium text-gray-900">Send Reminders</div>
                                <div class="text-sm text-gray-600">Appointment notifications</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                    
                    <button onclick="generateMedicalReport()" class="w-full flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-medical text-green-600"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-medium text-gray-900">Generate Report</div>
                                <div class="text-sm text-gray-600">Monthly patient report</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                    
                    <button onclick="scheduleBulkAppointments()" class="w-full flex items-center justify-between p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-plus text-purple-600"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-medium text-gray-900">Bulk Schedule</div>
                                <div class="text-sm text-gray-600">Schedule multiple appointments</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                    
                    <button onclick="exportPatientData()" class="w-full flex items-center justify-between p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-download text-red-600"></i>
                            </div>
                            <div class="text-left">
                                <div class="font-medium text-gray-900">Export Data</div>
                                <div class="text-sm text-gray-600">Export to CSV/Excel</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-2"></i>
                    Recent Activity
                </h3>
                <div class="space-y-4" id="recentActivity">
                    <!-- Activity items will be loaded here -->
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                    Today's Appointments
                </h3>
                <div class="space-y-3" id="todaysAppointments">
                    <!-- Today's appointments will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->

<!-- Add/Edit Patient Modal -->
<div id="patientModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('patientModal')"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-xl leading-6 font-semibold text-gray-900 mb-4" id="patientModalTitle">
                            Register New Patient
                        </h3>
                        <form id="patientForm" class="space-y-6">
                            <!-- Personal Information Section -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                                    Personal Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input type="text" name="first_name" required 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                        <input type="text" name="last_name" required 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                                        <input type="date" name="date_of_birth" required 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                                        <select name="gender" required 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                                        <textarea name="address" rows="2" required 
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-phone text-green-600 mr-2"></i>
                                    Contact Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                        <input type="tel" name="phone" required 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" name="email" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Phone</label>
                                        <input type="tel" name="emergency_phone" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information Section -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-heartbeat text-red-600 mr-2"></i>
                                    Medical Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                                        <select name="blood_type" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select Blood Type</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                                        <input type="text" name="allergies" placeholder="e.g., Penicillin, Pollen" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Medical History</label>
                                        <textarea name="medical_history" rows="3" placeholder="Any previous medical conditions..." 
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Medications</label>
                                        <textarea name="current_medications" rows="2" placeholder="List any current medications..." 
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance & Additional Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-file-invoice-dollar text-yellow-600 mr-2"></i>
                                    Insurance Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Provider</label>
                                        <input type="text" name="insurance_provider" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Policy Number</label>
                                        <input type="text" name="insurance_policy_number" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                                        <input type="text" name="occupation" 
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                                        <select name="marital_status" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select Status</option>
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="divorced">Divorced</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="patient_id" id="patient_id">
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="savePatient()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Patient
                </button>
                <button type="button" onclick="closeModal('patientModal')" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Patient Details Modal -->
<div id="viewPatientModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('viewPatientModal')"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900" id="viewPatientModalTitle">
                                Patient Details
                            </h3>
                            <div class="flex space-x-2">
                                <button onclick="editPatientFromView()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button onclick="scheduleAppointmentForPatient()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-calendar-plus mr-1"></i> Schedule
                                </button>
                            </div>
                        </div>
                        <div id="patientDetailsContent">
                            <!-- Patient details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <button type="button" onclick="closeModal('viewPatientModal')" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Appointment History Modal -->
<div id="appointmentHistoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('appointmentHistoryModal')"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-xl leading-6 font-semibold text-gray-900 mb-6" id="appointmentHistoryTitle">
                            Appointment History
                        </h3>
                        <div id="appointmentHistoryContent">
                            <!-- Appointment history will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <button type="button" onclick="closeModal('appointmentHistoryModal')" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Data storage
    let patients = [];
    let appointments = [];
    let selectedPatientId = null;

    // Initialize data
    document.addEventListener('DOMContentLoaded', function() {
        loadData();
        setupEventListeners();
    });

    // Setup event listeners
    function setupEventListeners() {
        // Search functionality
        document.getElementById('searchPatient').addEventListener('input', debounce(loadPatients, 300));
        
        // Filter functionality
        document.getElementById('filterStatus').addEventListener('change', loadPatients);
        
        // Form validation
        const patientForm = document.getElementById('patientForm');
        if (patientForm) {
            patientForm.addEventListener('submit', function(e) {
                e.preventDefault();
                savePatient();
            });
        }
    }

    // Load initial data
    function loadData() {
        // Load from localStorage or use sample data
        const savedPatients = localStorage.getItem('hospital_patients');
        const savedAppointments = localStorage.getItem('hospital_appointments');
        
        patients = savedPatients ? JSON.parse(savedPatients) : getSamplePatients();
        appointments = savedAppointments ? JSON.parse(savedAppointments) : getSampleAppointments();
        
        loadPatients();
        updateStatistics();
        loadRecentActivity();
        loadTodaysAppointments();
    }

    // Sample data for demo
    function getSamplePatients() {
        return [
            {
                id: 1,
                patient_id: "P-2024-001",
                first_name: "John",
                last_name: "Doe",
                full_name: "John Doe",
                date_of_birth: "1985-05-15",
                age: 39,
                gender: "male",
                phone: "+1 (555) 123-4567",
                email: "john.doe@email.com",
                address: "123 Main St, Cityville, State 12345",
                emergency_contact: "Jane Doe",
                emergency_phone: "+1 (555) 987-6543",
                blood_type: "O+",
                allergies: "Penicillin, Pollen",
                medical_history: "Hypertension (2018), Appendectomy (2015)",
                current_medications: "Lisinopril 10mg daily",
                insurance_provider: "HealthFirst Insurance",
                insurance_policy_number: "HFI-78901234",
                occupation: "Software Engineer",
                marital_status: "married",
                status: "active",
                registration_date: "2024-01-15",
                last_visit: "2024-10-20",
                total_appointments: 5,
                avatar: "https://ui-avatars.com/api/?name=John+Doe&background=3b82f6&color=fff&size=128"
            },
            {
                id: 2,
                patient_id: "P-2024-002",
                first_name: "Jane",
                last_name: "Smith",
                full_name: "Jane Smith",
                date_of_birth: "1990-08-22",
                age: 34,
                gender: "female",
                phone: "+1 (555) 234-5678",
                email: "jane.smith@email.com",
                address: "456 Oak Ave, Townsville, State 23456",
                emergency_contact: "John Smith",
                emergency_phone: "+1 (555) 876-5432",
                blood_type: "A+",
                allergies: "None",
                medical_history: "Asthma (Childhood), Normal Delivery (2020)",
                current_medications: "Albuterol inhaler as needed",
                insurance_provider: "MediCare Plus",
                insurance_policy_number: "MCP-56789012",
                occupation: "Teacher",
                marital_status: "married",
                status: "active",
                registration_date: "2024-02-10",
                last_visit: "2024-10-18",
                total_appointments: 3,
                avatar: "https://ui-avatars.com/api/?name=Jane+Smith&background=10b981&color=fff&size=128"
            },
            {
                id: 3,
                patient_id: "P-2024-003",
                first_name: "Robert",
                last_name: "Johnson",
                full_name: "Robert Johnson",
                date_of_birth: "1975-12-03",
                age: 48,
                gender: "male",
                phone: "+1 (555) 345-6789",
                email: "robert.johnson@email.com",
                address: "789 Pine Rd, Villagetown, State 34567",
                emergency_contact: "Mary Johnson",
                emergency_phone: "+1 (555) 765-4321",
                blood_type: "B+",
                allergies: "Shellfish",
                medical_history: "Diabetes Type 2 (2015), High Cholesterol",
                current_medications: "Metformin 500mg, Atorvastatin 20mg",
                insurance_provider: "WellCare",
                insurance_policy_number: "WC-90123456",
                occupation: "Accountant",
                marital_status: "married",
                status: "follow_up",
                registration_date: "2024-03-05",
                last_visit: "2024-10-15",
                total_appointments: 8,
                avatar: "https://ui-avatars.com/api/?name=Robert+Johnson&background=8b5cf6&color=fff&size=128"
            },
            {
                id: 4,
                patient_id: "P-2024-004",
                first_name: "Sarah",
                last_name: "Williams",
                full_name: "Sarah Williams",
                date_of_birth: "1988-03-18",
                age: 36,
                gender: "female",
                phone: "+1 (555) 456-7890",
                email: "sarah.williams@email.com",
                address: "321 Maple Dr, Hilltown, State 45678",
                emergency_contact: "David Williams",
                emergency_phone: "+1 (555) 654-3210",
                blood_type: "AB+",
                allergies: "Peanuts, Latex",
                medical_history: "Migraine, Thyroid Disorder",
                current_medications: "Levothyroxine 50mcg, Sumatriptan as needed",
                insurance_provider: "BlueCross BlueShield",
                insurance_policy_number: "BCBS-34567890",
                occupation: "Marketing Manager",
                marital_status: "married",
                status: "active",
                registration_date: "2024-04-20",
                last_visit: "2024-10-12",
                total_appointments: 4,
                avatar: "https://ui-avatars.com/api/?name=Sarah+Williams&background=f59e0b&color=fff&size=128"
            },
            {
                id: 5,
                patient_id: "P-2024-005",
                first_name: "Michael",
                last_name: "Brown",
                full_name: "Michael Brown",
                date_of_birth: "1995-07-30",
                age: 29,
                gender: "male",
                phone: "+1 (555) 567-8901",
                email: "michael.brown@email.com",
                address: "654 Cedar Ln, Rivertown, State 56789",
                emergency_contact: "Lisa Brown",
                emergency_phone: "+1 (555) 543-2109",
                blood_type: "O-",
                allergies: "Iodine, Bee Stings",
                medical_history: "Appendectomy (2018), Broken Arm (2021)",
                current_medications: "None",
                insurance_provider: "Aetna",
                insurance_policy_number: "AET-12345678",
                occupation: "Construction Worker",
                marital_status: "single",
                status: "inactive",
                registration_date: "2024-05-15",
                last_visit: "2024-09-28",
                total_appointments: 2,
                avatar: "https://ui-avatars.com/api/?name=Michael+Brown&background=ef4444&color=fff&size=128"
            }
        ];
    }

    function getSampleAppointments() {
        const today = new Date().toISOString().split('T')[0];
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayStr = yesterday.toISOString().split('T')[0];
        
        return [
            {
                id: 1,
                patient_id: 1,
                patient_name: "John Doe",
                appointment_date: today,
                appointment_time: "09:00",
                doctor: "Dr. Michael Smith",
                department: "General Medicine",
                status: "scheduled",
                reason: "Follow-up for hypertension",
                notes: "Check blood pressure, review medication"
            },
            {
                id: 2,
                patient_id: 2,
                patient_name: "Jane Smith",
                appointment_date: today,
                appointment_time: "10:30",
                doctor: "Dr. Sarah Johnson",
                department: "Pediatrics",
                status: "scheduled",
                reason: "Child vaccination",
                notes: "Regular immunization schedule"
            },
            {
                id: 3,
                patient_id: 3,
                patient_name: "Robert Johnson",
                appointment_date: today,
                appointment_time: "14:00",
                doctor: "Dr. Robert Williams",
                department: "Cardiology",
                status: "scheduled",
                reason: "Diabetes management",
                notes: "Review blood sugar levels"
            }
        ];
    }

    // Load patients table
    function loadPatients() {
        const searchTerm = document.getElementById('searchPatient').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        
        // Hide loading state
        document.getElementById('loadingPatients').style.display = 'none';
        
        // Filter patients
        let filteredPatients = patients.filter(patient => {
            const matchesSearch = searchTerm === '' || 
                patient.full_name.toLowerCase().includes(searchTerm) ||
                patient.patient_id.toLowerCase().includes(searchTerm) ||
                patient.phone.toLowerCase().includes(searchTerm) ||
                patient.email.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === 'all' || 
                patient.status === statusFilter ||
                (statusFilter === 'new' && isNewPatient(patient.registration_date));
            
            return matchesSearch && matchesStatus;
        });
        
        // Update table
        const tableBody = document.getElementById('patientTableBody');
        const noPatients = document.getElementById('noPatients');
        
        if (filteredPatients.length === 0) {
            tableBody.innerHTML = '';
            noPatients.style.display = 'block';
            return;
        }
        
        noPatients.style.display = 'none';
        
        // Generate table rows
        let html = '';
        filteredPatients.forEach(patient => {
            const statusBadge = getStatusBadge(patient.status);
            const lastVisit = patient.last_visit ? formatDate(patient.last_visit) : 'Never';
            
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full border border-gray-300" src="${patient.avatar}" alt="${patient.full_name}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${patient.full_name}</div>
                                <div class="text-sm text-gray-500">${patient.patient_id}</div>
                                <div class="text-xs text-gray-400">Age: ${patient.age}, ${patient.gender}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${patient.phone}</div>
                        <div class="text-sm text-gray-500">${patient.email}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${patient.total_appointments}</div>
                        <div class="text-sm text-gray-500">Last: ${lastVisit}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${statusBadge}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="viewPatient(${patient.id})" class="text-blue-600 hover:text-blue-900" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="viewAppointmentHistory(${patient.id})" class="text-green-600 hover:text-green-900" title="Appointments">
                                <i class="fas fa-calendar"></i>
                            </button>
                            <button onclick="editPatient(${patient.id})" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deletePatient(${patient.id})" class="text-red-600 hover:text-red-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }

    // Open add patient modal
    function openAddPatientModal() {
        document.getElementById('patientModalTitle').textContent = 'Register New Patient';
        document.getElementById('patientForm').reset();
        document.getElementById('patient_id').value = '';
        
        // Generate new patient ID
        const newId = patients.length > 0 ? Math.max(...patients.map(p => p.id)) + 1 : 1;
        const year = new Date().getFullYear();
        const paddedId = newId.toString().padStart(3, '0');
        
        // Set default date of birth to 30 years ago
        const defaultDOB = new Date();
        defaultDOB.setFullYear(defaultDOB.getFullYear() - 30);
        document.querySelector('input[name="date_of_birth"]').value = defaultDOB.toISOString().split('T')[0];
        
        openModal('patientModal');
    }

    // Edit patient
    function editPatient(patientId) {
        const patient = patients.find(p => p.id == patientId);
        if (!patient) {
            showNotification('Patient not found!', 'error');
            return;
        }
        
        document.getElementById('patientModalTitle').textContent = 'Edit Patient';
        const form = document.getElementById('patientForm');
        
        // Fill form with patient data
        form.first_name.value = patient.first_name;
        form.last_name.value = patient.last_name;
        form.date_of_birth.value = patient.date_of_birth;
        form.gender.value = patient.gender;
        form.address.value = patient.address;
        form.phone.value = patient.phone;
        form.email.value = patient.email || '';
        form.emergency_contact.value = patient.emergency_contact || '';
        form.emergency_phone.value = patient.emergency_phone || '';
        form.blood_type.value = patient.blood_type || '';
        form.allergies.value = patient.allergies || '';
        form.medical_history.value = patient.medical_history || '';
        form.current_medications.value = patient.current_medications || '';
        form.insurance_provider.value = patient.insurance_provider || '';
        form.insurance_policy_number.value = patient.insurance_policy_number || '';
        form.occupation.value = patient.occupation || '';
        form.marital_status.value = patient.marital_status || '';
        
        form.patient_id.value = patient.id;
        
        openModal('patientModal');
    }

    // Save patient
    function savePatient() {
        const form = document.getElementById('patientForm');
        const patientId = form.patient_id.value;
        
        // Validate required fields
        if (!form.first_name.value || !form.last_name.value || !form.date_of_birth.value || 
            !form.gender.value || !form.phone.value || !form.address.value) {
            showNotification('Please fill in all required fields!', 'error');
            return;
        }
        
        // Calculate age
        const dob = new Date(form.date_of_birth.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        
        // Prepare patient data
        const patientData = {
            id: patientId ? parseInt(patientId) : patients.length + 1,
            patient_id: patientId ? patients.find(p => p.id == patientId).patient_id : `P-${new Date().getFullYear()}-${(patients.length + 1).toString().padStart(3, '0')}`,
            first_name: form.first_name.value.trim(),
            last_name: form.last_name.value.trim(),
            full_name: `${form.first_name.value.trim()} ${form.last_name.value.trim()}`,
            date_of_birth: form.date_of_birth.value,
            age: age,
            gender: form.gender.value,
            phone: form.phone.value.trim(),
            email: form.email.value.trim() || '',
            address: form.address.value.trim(),
            emergency_contact: form.emergency_contact.value.trim() || '',
            emergency_phone: form.emergency_phone.value.trim() || '',
            blood_type: form.blood_type.value || '',
            allergies: form.allergies.value.trim() || '',
            medical_history: form.medical_history.value.trim() || '',
            current_medications: form.current_medications.value.trim() || '',
            insurance_provider: form.insurance_provider.value.trim() || '',
            insurance_policy_number: form.insurance_policy_number.value.trim() || '',
            occupation: form.occupation.value.trim() || '',
            marital_status: form.marital_status.value || '',
            status: patientId ? patients.find(p => p.id == patientId).status : 'active',
            registration_date: patientId ? patients.find(p => p.id == patientId).registration_date : new Date().toISOString().split('T')[0],
            last_visit: patientId ? patients.find(p => p.id == patientId).last_visit : new Date().toISOString().split('T')[0],
            total_appointments: patientId ? patients.find(p => p.id == patientId).total_appointments : 0,
            avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(form.first_name.value.trim() + ' ' + form.last_name.value.trim())}&background=3b82f6&color=fff&size=128`
        };
        
        if (patientId) {
            // Update existing patient
            const index = patients.findIndex(p => p.id == patientId);
            if (index !== -1) {
                patients[index] = patientData;
                showNotification('Patient updated successfully!', 'success');
            }
        } else {
            // Add new patient
            patients.push(patientData);
            showNotification('Patient registered successfully!', 'success');
        }
        
        // Save to localStorage
        localStorage.setItem('hospital_patients', JSON.stringify(patients));
        
        closeModal('patientModal');
        loadPatients();
        updateStatistics();
        loadRecentActivity();
    }

    // View patient details
    function viewPatient(patientId) {
        const patient = patients.find(p => p.id == patientId);
        if (!patient) {
            showNotification('Patient not found!', 'error');
            return;
        }
        
        selectedPatientId = patientId;
        document.getElementById('viewPatientModalTitle').textContent = `Patient: ${patient.full_name}`;
        
        const content = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column: Basic Info -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-center mb-4">
                            <img class="h-32 w-32 rounded-full border-4 border-white shadow-md mx-auto" src="${patient.avatar}" alt="${patient.full_name}">
                            <h4 class="text-xl font-bold mt-4">${patient.full_name}</h4>
                            <p class="text-gray-600">${patient.patient_id}</p>
                            <div class="mt-2">
                                ${getStatusBadge(patient.status)}
                            </div>
                        </div>
                        
                        <div class="space-y-3 mt-6">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-6"></i>
                                <span class="ml-3">${patient.phone}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-6"></i>
                                <span class="ml-3">${patient.email || 'No email'}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 w-6"></i>
                                <span class="ml-3">${patient.address}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Middle Column: Personal & Medical Info -->
                <div class="md:col-span-2">
                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                                Personal Information
                            </h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                                    <p class="mt-1">${formatDate(patient.date_of_birth)} (${patient.age} years)</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Gender</label>
                                    <p class="mt-1 capitalize">${patient.gender}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Marital Status</label>
                                    <p class="mt-1 capitalize">${patient.marital_status || 'Not specified'}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Occupation</label>
                                    <p class="mt-1">${patient.occupation || 'Not specified'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Medical Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-heartbeat text-red-600 mr-2"></i>
                                Medical Information
                            </h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Blood Type</label>
                                    <p class="mt-1 font-bold ${patient.blood_type ? 'text-red-600' : ''}">${patient.blood_type || 'Unknown'}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Allergies</label>
                                    <p class="mt-1 ${patient.allergies ? 'text-red-600' : ''}">${patient.allergies || 'None'}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm font-medium text-gray-500">Medical History</label>
                                    <p class="mt-1">${patient.medical_history || 'None recorded'}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm font-medium text-gray-500">Current Medications</label>
                                    <p class="mt-1">${patient.current_medications || 'None'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Emergency & Insurance -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                                Additional Information
                            </h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Emergency Contact</label>
                                    <p class="mt-1">${patient.emergency_contact || 'Not specified'}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Emergency Phone</label>
                                    <p class="mt-1">${patient.emergency_phone || 'Not specified'}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Insurance Provider</label>
                                    <p class="mt-1">${patient.insurance_provider || 'None'}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Policy Number</label>
                                    <p class="mt-1">${patient.insurance_policy_number || 'None'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visit Statistics -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                                Visit Statistics
                            </h5>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">${patient.total_appointments}</div>
                                    <div class="text-sm text-gray-600">Total Visits</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900">${formatDate(patient.registration_date)}</div>
                                    <div class="text-sm text-gray-600">Registration Date</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900">${formatDate(patient.last_visit)}</div>
                                    <div class="text-sm text-gray-600">Last Visit</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('patientDetailsContent').innerHTML = content;
        openModal('viewPatientModal');
    }

    // View appointment history
    function viewAppointmentHistory(patientId) {
        const patient = patients.find(p => p.id == patientId);
        if (!patient) {
            showNotification('Patient not found!', 'error');
            return;
        }
        
        const patientAppointments = appointments.filter(a => a.patient_id == patientId);
        
        document.getElementById('appointmentHistoryTitle').textContent = `Appointment History: ${patient.full_name}`;
        
        let content = '';
        
        if (patientAppointments.length === 0) {
            content = `
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No Appointments Found</h4>
                    <p class="text-gray-600">This patient has no appointment history.</p>
                </div>
            `;
        } else {
            content = `
                <div class="space-y-4">
                    ${patientAppointments.map(appointment => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="font-medium text-gray-900">${appointment.reason}</h5>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-calendar-day mr-1"></i> ${formatDate(appointment.appointment_date)}
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i> ${appointment.appointment_time}
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            <i class="fas fa-user-md mr-1"></i> ${appointment.doctor}
                                        </span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full ${getAppointmentStatusClass(appointment.status)}">
                                    ${appointment.status}
                                </span>
                            </div>
                            ${appointment.notes ? `
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">${appointment.notes}</p>
                                </div>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        document.getElementById('appointmentHistoryContent').innerHTML = content;
        openModal('appointmentHistoryModal');
    }

    // Edit patient from view
    function editPatientFromView() {
        closeModal('viewPatientModal');
        setTimeout(() => {
            editPatient(selectedPatientId);
        }, 300);
    }

    // Schedule appointment for patient
    function scheduleAppointmentForPatient() {
        closeModal('viewPatientModal');
        showNotification('Opening appointment scheduler...', 'info');
        // In real application, redirect to appointment scheduler with patient pre-selected
    }

    // Delete patient
    function deletePatient(patientId) {
        if (!confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
            return;
        }
        
        const index = patients.findIndex(p => p.id == patientId);
        if (index === -1) {
            showNotification('Patient not found!', 'error');
            return;
        }
        
        const patientName = patients[index].full_name;
        patients.splice(index, 1);
        
        // Remove patient's appointments
        appointments = appointments.filter(a => a.patient_id != patientId);
        
        // Save to localStorage
        localStorage.setItem('hospital_patients', JSON.stringify(patients));
        localStorage.setItem('hospital_appointments', JSON.stringify(appointments));
        
        showNotification(`Patient "${patientName}" deleted successfully!`, 'success');
        loadPatients();
        updateStatistics();
        loadRecentActivity();
    }

    // Update statistics
    function updateStatistics() {
        const today = new Date();
        const thisMonth = today.getMonth();
        const thisYear = today.getFullYear();
        
        // Total patients
        document.getElementById('totalPatients').textContent = patients.length;
        
        // Active appointments (from appointments data)
        const activeAppointments = appointments.filter(a => a.status === 'scheduled').length;
        document.getElementById('activeAppointments').textContent = activeAppointments;
        
        // New this month
        const newThisMonth = patients.filter(p => {
            const regDate = new Date(p.registration_date);
            return regDate.getMonth() === thisMonth && regDate.getFullYear() === thisYear;
        }).length;
        document.getElementById('newThisMonth').textContent = newThisMonth;
        
        // Follow-up required
        const followUpRequired = patients.filter(p => p.status === 'follow_up').length;
        document.getElementById('followUpRequired').textContent = followUpRequired;
    }

    // Load recent activity
    function loadRecentActivity() {
        const container = document.getElementById('recentActivity');
        const recentPatients = [...patients]
            .sort((a, b) => new Date(b.registration_date) - new Date(a.registration_date))
            .slice(0, 5);
        
        let html = '';
        
        recentPatients.forEach(patient => {
            const timeAgo = getTimeAgo(patient.registration_date);
            html += `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8 rounded-full" src="${patient.avatar}" alt="${patient.full_name}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">${patient.full_name}</span> registered as new patient
                        </p>
                        <p class="text-xs text-gray-500">${timeAgo}</p>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html || '<p class="text-gray-500 text-sm">No recent activity</p>';
    }

    // Load today's appointments
    function loadTodaysAppointments() {
        const container = document.getElementById('todaysAppointments');
        const today = new Date().toISOString().split('T')[0];
        const todaysApps = appointments.filter(a => a.appointment_date === today);
        
        let html = '';
        
        if (todaysApps.length === 0) {
            html = `
                <div class="text-center py-4">
                    <p class="text-gray-500 text-sm">No appointments today</p>
                </div>
            `;
        } else {
            todaysApps.forEach(appointment => {
                html += `
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="font-medium text-gray-900">${appointment.patient_name}</h5>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i> ${appointment.appointment_time}
                                </div>
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-user-md mr-1"></i> ${appointment.doctor}
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full ${getAppointmentStatusClass(appointment.status)}">
                                ${appointment.status}
                            </span>
                        </div>
                    </div>
                `;
            });
        }
        
        container.innerHTML = html;
    }

    // Quick action functions
    function sendAppointmentReminders() {
        showNotification('Appointment reminders sent successfully!', 'success');
    }

    function generateMedicalReport() {
        showNotification('Medical report generated and downloaded!', 'success');
    }

    function scheduleBulkAppointments() {
        showNotification('Bulk appointment scheduler opened!', 'info');
    }

    function exportPatientData() {
        // Create CSV content
        const headers = ['Patient ID', 'Name', 'Age', 'Gender', 'Phone', 'Email', 'Status', 'Last Visit', 'Total Appointments'];
        const csvContent = [
            headers.join(','),
            ...patients.map(p => [
                p.patient_id,
                `"${p.full_name}"`,
                p.age,
                p.gender,
                p.phone,
                p.email,
                p.status,
                p.last_visit,
                p.total_appointments
            ].join(','))
        ].join('\n');
        
        // Create download link
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `patients_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showNotification('Patient data exported successfully!', 'success');
    }

    // Helper functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Yesterday';
        if (diffDays < 7) return `${diffDays} days ago`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
        if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
        return `${Math.floor(diffDays / 365)} years ago`;
    }

    function getStatusBadge(status) {
        const badges = {
            active: '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>',
            inactive: '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>',
            follow_up: '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Follow-up</span>',
            new: '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">New</span>'
        };
        return badges[status] || '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }

    function getAppointmentStatusClass(status) {
        const classes = {
            scheduled: 'bg-blue-100 text-blue-800',
            completed: 'bg-green-100 text-green-800',
            cancelled: 'bg-red-100 text-red-800',
            no_show: 'bg-gray-100 text-gray-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    function isNewPatient(registrationDate) {
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
        return new Date(registrationDate) >= oneWeekAgo;
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

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.notification');
        existing.forEach(n => n.remove());
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' :
            type === 'error' ? 'bg-red-600' :
            type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('opacity-0', 'translate-x-full');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>

<style>
    /* Modal animations */
    .modal-enter {
        opacity: 0;
        transform: scale(0.95);
    }
    
    .modal-enter-active {
        opacity: 1;
        transform: scale(1);
        transition: opacity 300ms, transform 300ms;
    }
    
    .modal-exit {
        opacity: 1;
        transform: scale(1);
    }
    
    .modal-exit-active {
        opacity: 0;
        transform: scale(0.95);
        transition: opacity 300ms, transform 300ms;
    }
    
    /* Table styles */
    table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    th {
        position: sticky;
        top: 0;
        background-color: #f9fafb;
        z-index: 10;
    }
    
    /* Hover effects */
    tr:hover {
        background-color: #f3f4f6;
    }
    
    /* Scrollbar styling */
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
    
    /* Loading spinner */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Notification animation */
    .notification {
        opacity: 0;
        transform: translateX(100%);
    }
    
    .notification.show {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* Gradient backgrounds */
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    /* Card shadows */
    .shadow-lg {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Input focus styles */
    input:focus, select:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Transition effects */
    .transition-colors {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endsection