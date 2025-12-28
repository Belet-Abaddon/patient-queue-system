@extends('layouts.admin')

@section('title', 'Appointment Management')
@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">
    <!-- Header with Controls -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Appointment Management</h2>
                <p class="text-gray-600 mt-1">Manage patient appointments and doctor queues</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button onclick="openAddAppointmentModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-calendar-plus mr-2"></i> New Appointment
                </button>
                <button onclick="refreshQueues()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh Queues
                </button>
            </div>
        </div>
    </div>

    <!-- Doctor Queues -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="doctorQueues">
        <!-- Doctor queue cards will be loaded here -->
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Appointments List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                            All Appointments
                        </h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="refreshAppointments()" class="p-2 hover:bg-gray-100 rounded-lg" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                            <select id="doctorFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Doctors</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" id="dateFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                            <button onclick="loadAppointments()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <!-- Appointments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Queue #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTable" class="bg-white divide-y divide-gray-200">
                                <!-- Appointments will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div id="pagination" class="mt-4 flex justify-between items-center hidden">
                        <div class="text-sm text-gray-700">
                            Showing <span id="startRow">0</span> to <span id="endRow">0</span> of <span id="totalRows">0</span> results
                        </div>
                        <div class="flex space-x-2">
                            <button id="prevPage" onclick="changePage(currentPage - 1)" 
                                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50" disabled>
                                Previous
                            </button>
                            <button id="nextPage" onclick="changePage(currentPage + 1)" 
                                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50" disabled>
                                Next
                            </button>
                        </div>
                    </div>
                    
                    <!-- Empty State -->
                    <div id="noAppointments" class="text-center py-8 hidden">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Appointments Found</h4>
                        <p class="text-gray-600 mb-4">No appointments match your search criteria.</p>
                        <button onclick="openAddAppointmentModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i> Add First Appointment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Today's Schedule -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar-day text-green-600 mr-2"></i>
                            Today's Schedule
                        </h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full" id="todayCount">0</span>
                    </div>
                </div>
                <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto" id="todaysSchedule">
                    <!-- Today's appointments will be loaded here -->
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                        Queue Statistics
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Today</span>
                            <span class="font-bold text-gray-800" id="totalToday">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Approved</span>
                            <span class="font-bold text-green-600" id="approvedCount">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-bold text-yellow-600" id="pendingCount">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Completed</span>
                            <span class="font-bold text-blue-600" id="completedCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS SECTION -->

<!-- Add/Edit Appointment Modal -->
<div id="appointmentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="appointmentModalTitle">New Appointment</h3>
                        <div class="mt-4">
                            <form id="appointmentForm">
                                @csrf
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient *</label>
                                        <select name="user_id" required 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Patient</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor *</label>
                                        <select name="doctor_id" id="doctorSelect" required 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Doctor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date *</label>
                                        <input type="date" name="appointment_date" id="appointmentDate" required 
                                               min="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div id="availableSchedulesContainer" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Schedule *</label>
                                        <select name="schedule_id" id="scheduleSelect" required 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Schedule</option>
                                        </select>
                                        <div id="scheduleInfo" class="mt-2 text-sm text-gray-600"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Queue Number</label>
                                            <input type="number" name="queue_number" min="1" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                                   placeholder="Auto-generate if empty">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alert Before (min)</label>
                                            <input type="number" name="alert_before" min="1" max="60" 
                                                   value="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="appstatus" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="appointment_id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveAppointment()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Appointment
                </button>
                <button type="button" onclick="closeModal('appointmentModal')" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Queue Management Modal -->
<div id="queueModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="queueModalTitle">Queue Management</h3>
                        <div class="mt-4">
                            <div id="queueDetails">
                                <!-- Queue details will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal('queueModal')" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Appointment</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this appointment? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="confirmDelete()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Delete
                </button>
                <button type="button" onclick="closeModal('deleteModal')" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Global variables
    let currentPage = 1;
    let totalPages = 1;
    let deleteAppointmentId = null;
    
    // CSRF token setup for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', function() {
        loadDoctorQueues();
        loadAppointments();
        loadTodaysSchedule();
        loadDoctors();
        loadPatients();
        updateQueueStatistics();
        
        // Setup doctor change event for schedules
        document.getElementById('doctorSelect').addEventListener('change', function() {
            document.getElementById('availableSchedulesContainer').classList.add('hidden');
        });
        
        // Setup date change event for schedules
        document.getElementById('appointmentDate').addEventListener('change', function() {
            const doctorId = document.getElementById('doctorSelect').value;
            const appointmentDate = this.value;
            
            if (doctorId && appointmentDate) {
                loadAvailableSchedules(doctorId, appointmentDate);
            }
        });
    });

    // MODAL FUNCTIONS
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // DATA LOADING FUNCTIONS
    async function loadDoctors() {
        try {
            const response = await fetch('/api/doctors/active');
            const data = await response.json();
            
            if (data.success) {
                // Update filter dropdown
                const filterSelect = document.getElementById('doctorFilter');
                filterSelect.innerHTML = '<option value="">All Doctors</option>';
                data.data.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = `${doctor.name} (${doctor.specialization})`;
                    filterSelect.appendChild(option);
                });
                
                // Update appointment form dropdown
                const formSelect = document.getElementById('doctorSelect');
                formSelect.innerHTML = '<option value="">Select Doctor</option>';
                data.data.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = `${doctor.name} - ${doctor.specialization}`;
                    formSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading doctors:', error);
            showNotification('Error loading doctors', 'error');
        }
    }

    async function loadPatients() {
        try {
            const response = await fetch('/api/patients');
            const data = await response.json();
            
            if (data.success) {
                const select = document.querySelector('#appointmentForm select[name="user_id"]');
                select.innerHTML = '<option value="">Select Patient</option>';
                data.data.forEach(patient => {
                    const option = document.createElement('option');
                    option.value = patient.id;
                    option.textContent = `${patient.name} (${patient.email})`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading patients:', error);
        }
    }

    async function loadAvailableSchedules(doctorId, appointmentDate) {
        try {
            const response = await fetch(`/api/doctors/${doctorId}/schedules/available?date=${appointmentDate}`);
            const data = await response.json();
            
            const select = document.getElementById('scheduleSelect');
            const info = document.getElementById('scheduleInfo');
            const container = document.getElementById('availableSchedulesContainer');
            
            select.innerHTML = '<option value="">Select Schedule</option>';
            info.innerHTML = '';
            container.classList.remove('hidden');
            
            if (data.success && data.data.length > 0) {
                data.data.forEach(schedule => {
                    const option = document.createElement('option');
                    option.value = schedule.id;
                    const dayName = getDayName(schedule.day);
                    option.textContent = `${dayName}: ${schedule.start_time} - ${schedule.end_time} (${schedule.shift_type})`;
                    option.dataset.schedule = JSON.stringify(schedule);
                    select.appendChild(option);
                });
                
                // Add change event to show schedule info
                select.addEventListener('change', function() {
                    if (this.value) {
                        const schedule = JSON.parse(this.selectedOptions[0].dataset.schedule);
                        info.innerHTML = `
                            <div class="bg-gray-50 p-2 rounded">
                                <div class="grid grid-cols-2 gap-2">
                                    <div><strong>Day:</strong> ${getFullDayName(schedule.day)}</div>
                                    <div><strong>Time:</strong> ${schedule.start_time} - ${schedule.end_time}</div>
                                    <div><strong>Shift:</strong> ${schedule.shift_type.replace('_', ' ')}</div>
                                    <div><strong>Status:</strong> ${schedule.status}</div>
                                </div>
                            </div>
                        `;
                    } else {
                        info.innerHTML = '';
                    }
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No available schedules for this date';
                option.disabled = true;
                select.appendChild(option);
                showNotification('No available schedules for this date', 'warning');
            }
        } catch (error) {
            console.error('Error loading schedules:', error);
        }
    }

    // APPOINTMENT MANAGEMENT
    function openAddAppointmentModal() {
        document.getElementById('appointmentModalTitle').textContent = 'New Appointment';
        document.getElementById('appointmentForm').reset();
        document.getElementById('appointment_id').value = '';
        document.getElementById('availableSchedulesContainer').classList.add('hidden');
        document.getElementById('scheduleInfo').innerHTML = '';
        
        // Set default date to today
        document.getElementById('appointmentDate').valueAsDate = new Date();
        
        // Reset schedule dropdown
        const scheduleSelect = document.getElementById('scheduleSelect');
        scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';
        
        openModal('appointmentModal');
    }

    async function openEditAppointmentModal(appointmentId) {
        try {
            const response = await fetch(`/api/appointments/${appointmentId}`);
            const data = await response.json();
            
            if (data.success) {
                const appointment = data.data;
                document.getElementById('appointmentModalTitle').textContent = 'Edit Appointment';
                document.getElementById('appointment_id').value = appointment.id;
                
                // Fill form
                const form = document.getElementById('appointmentForm');
                form.user_id.value = appointment.user_id;
                form.doctor_id.value = appointment.doctor_id;
                
                // Format date from created_at or use today
                const appointmentDate = appointment.created_at ? appointment.created_at.split('T')[0] : new Date().toISOString().split('T')[0];
                form.appointment_date.value = appointmentDate;
                
                // Load available schedules and select current one
                await loadAvailableSchedules(appointment.doctor_id, appointmentDate);
                
                // Wait a bit for schedules to load, then select the current one
                setTimeout(() => {
                    form.schedule_id.value = appointment.schedule_id;
                    // Trigger change to show schedule info
                    if (form.schedule_id.value) {
                        form.schedule_id.dispatchEvent(new Event('change'));
                    }
                }, 300);
                
                form.queue_number.value = appointment.queue_number;
                form.alert_before.value = appointment.alert_before;
                form.appstatus.value = appointment.appstatus;
                
                openModal('appointmentModal');
            }
        } catch (error) {
            console.error('Error loading appointment:', error);
            showNotification('Error loading appointment', 'error');
        }
    }

    async function saveAppointment() {
        const form = document.getElementById('appointmentForm');
        const appointmentId = document.getElementById('appointment_id').value;
        
        // Validation
        if (!form.user_id.value || !form.doctor_id.value || !form.appointment_date.value || !form.schedule_id.value) {
            showNotification('Please fill in all required fields', 'error');
            return;
        }

        const formData = new FormData(form);
        
        try {
            let response;
            if (appointmentId) {
                // Update existing appointment
                response = await fetch('/api/appointments/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
            } else {
                // Create new appointment
                response = await fetch('/api/appointments', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
            }

            const data = await response.json();
            
            if (data.success) {
                closeModal('appointmentModal');
                showNotification(data.message, 'success');
                loadDoctorQueues();
                loadAppointments();
                loadTodaysSchedule();
                updateQueueStatistics();
            } else {
                showNotification(data.message || 'Error saving appointment', 'error');
            }
        } catch (error) {
            console.error('Error saving appointment:', error);
            showNotification('Error saving appointment', 'error');
        }
    }

    async function loadAppointments(page = 1) {
        const doctorFilter = document.getElementById('doctorFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        try {
            let url = `/api/appointments?page=${page}`;
            if (doctorFilter) url += `&doctor_id=${doctorFilter}`;
            if (statusFilter) url += `&status=${statusFilter}`;
            if (dateFilter) url += `&date=${dateFilter}`;
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                renderAppointmentsTable(data.data.data);
                updatePagination(data.data);
            }
        } catch (error) {
            console.error('Error loading appointments:', error);
            showNotification('Error loading appointments', 'error');
        }
    }

    function renderAppointmentsTable(appointments) {
        const container = document.getElementById('appointmentsTable');
        const noAppointments = document.getElementById('noAppointments');
        
        if (appointments.length === 0) {
            container.innerHTML = '';
            noAppointments.classList.remove('hidden');
            document.getElementById('pagination').classList.add('hidden');
        } else {
            noAppointments.classList.add('hidden');
            container.innerHTML = appointments.map(appointment => {
                // Get day from schedule
                const day = appointment.schedule?.day ? getDayName(appointment.schedule.day) : '';
                const time = appointment.schedule ? `${appointment.schedule.start_time} - ${appointment.schedule.end_time}` : '';
                
                return `
                    <tr class="hover:bg-blue-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(appointment.user?.name || 'Patient')}&background=3b82f6&color=fff" 
                                     class="w-8 h-8 rounded-full mr-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">${appointment.user?.name || 'Unknown Patient'}</div>
                                    <div class="text-xs text-gray-500">${appointment.user?.email || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">${appointment.doctor?.name || 'Unknown Doctor'}</div>
                            <div class="text-xs text-gray-500">${appointment.doctor?.specialization || ''}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">${formatDate(appointment.created_at)}</div>
                            <div class="text-xs text-gray-500">${day}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">${time}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="w-8 h-8 ${getQueueNumberColor(appointment.appstatus)} rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold ${getQueueNumberTextColor(appointment.appstatus)}">${appointment.queue_number}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 ${getStatusColor(appointment.appstatus)} text-xs rounded-full">
                                ${appointment.appstatus}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <button onclick="openEditAppointmentModal(${appointment.id})" 
                                        class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${appointment.appstatus === 'pending' ? `
                                    <button onclick="changeAppointmentStatus(${appointment.id}, 'approved')" 
                                            class="text-green-600 hover:text-green-900" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                ` : ''}
                                ${appointment.appstatus !== 'cancelled' && appointment.appstatus !== 'completed' ? `
                                    <button onclick="changeAppointmentStatus(${appointment.id}, 'cancelled')" 
                                            class="text-red-600 hover:text-red-900" title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                                ${appointment.appstatus === 'approved' ? `
                                    <button onclick="changeAppointmentStatus(${appointment.id}, 'completed')" 
                                            class="text-indigo-600 hover:text-indigo-900" title="Complete">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                ` : ''}
                                <button onclick="openDeleteModal(${appointment.id})" 
                                        class="text-gray-600 hover:text-gray-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            document.getElementById('pagination').classList.remove('hidden');
        }
    }

    async function loadDoctorQueues() {
        try {
            const response = await fetch('/api/doctors/queues/today');
            const data = await response.json();
            
            if (data.success) {
                renderDoctorQueues(data.data);
            }
        } catch (error) {
            console.error('Error loading doctor queues:', error);
        }
    }

    function renderDoctorQueues(doctors) {
        const container = document.getElementById('doctorQueues');
        
        let html = '';
        
        doctors.forEach(doctor => {
            const todayAppointments = doctor.appointments_today || [];
            const approvedAppointments = todayAppointments.filter(a => a.appstatus === 'approved');
            const currentQueue = doctor.current_queue || 0;
            
            // Find current appointment (if any)
            const currentAppointment = approvedAppointments.find(a => a.queue_number === currentQueue);
            
            // Find waiting patients (queue number > current)
            const waitingPatients = approvedAppointments.filter(a => a.queue_number > currentQueue);
            
            html += `
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-900">${doctor.name}</h4>
                                <p class="text-sm text-gray-600">${doctor.specialization}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 ${doctor.status === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} text-xs rounded-full">
                                    ${doctor.status === 1 ? 'Available' : 'Unavailable'}
                                </span>
                                <span class="text-xs text-gray-500">Room ${doctor.room || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Current Patient -->
                        ${currentAppointment ? `
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-700 mb-2">Currently Serving</p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-bold text-blue-600">${currentAppointment.queue_number}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">${currentAppointment.user?.name || 'Unknown'}</p>
                                                <p class="text-xs text-gray-500">Queue #${currentAppointment.queue_number}</p>
                                            </div>
                                        </div>
                                        <button onclick="completeCurrentAppointment(${doctor.id})" 
                                                class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                            Complete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ` : `
                            <div class="mb-6">
                                <p class="text-sm font-medium text-gray-700 mb-2">Currently Serving</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                                    <p class="text-gray-500 text-sm">No patient currently</p>
                                </div>
                            </div>
                        `}
                        
                        <!-- Waiting Queue -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <p class="text-sm font-medium text-gray-700">Waiting Queue</p>
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                    ${waitingPatients.length} waiting
                                </span>
                            </div>
                            
                            ${waitingPatients.length === 0 ? `
                                <div class="text-center py-4">
                                    <p class="text-gray-400 text-sm">No patients waiting</p>
                                </div>
                            ` : `
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    ${waitingPatients.map(appointment => {
                                        return `
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                        <span class="text-xs font-bold text-yellow-600">${appointment.queue_number}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">${appointment.user?.name || 'Unknown'}</p>
                                                        <p class="text-xs text-gray-500">Queue #${appointment.queue_number}</p>
                                                    </div>
                                                </div>
                                                <button onclick="callNextPatient(${doctor.id}, ${appointment.id})" 
                                                        class="text-xs text-blue-600 hover:text-blue-800">
                                                    Call Now
                                                </button>
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            `}
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button onclick="openDoctorQueue(${doctor.id})" 
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                <i class="fas fa-list mr-2"></i> View Full Queue
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    async function loadTodaysSchedule() {
        try {
            const response = await fetch('/api/appointments/today');
            const data = await response.json();
            
            if (data.success) {
                renderTodaysSchedule(data.data);
            }
        } catch (error) {
            console.error('Error loading today\'s schedule:', error);
        }
    }

    function renderTodaysSchedule(appointments) {
        const container = document.getElementById('todaysSchedule');
        document.getElementById('todayCount').textContent = appointments.length;
        
        if (appointments.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-calendar-check text-gray-300 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-500">No appointments today</p>
                </div>
            `;
        } else {
            container.innerHTML = appointments.map(appointment => {
                const time = appointment.schedule ? `${appointment.schedule.start_time} - ${appointment.schedule.end_time}` : '';
                
                return `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 ${getQueueNumberColor(appointment.appstatus)} rounded-full flex items-center justify-center mr-2">
                                            <span class="text-xs font-bold ${getQueueNumberTextColor(appointment.appstatus)}">${appointment.queue_number}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-900">${time}</span>
                                            <span class="text-sm text-gray-500 ml-2">Dr. ${appointment.doctor?.name?.split(' ')[1] || ''}</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 ${getStatusColor(appointment.appstatus)} text-xs rounded-full">
                                        ${appointment.appstatus}
                                    </span>
                                </div>
                                <div class="text-sm font-medium text-gray-900">${appointment.user?.name || 'Unknown'}</div>
                                <div class="text-xs text-gray-500">${appointment.doctor?.specialization || 'No specialization'}</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    // QUEUE MANAGEMENT FUNCTIONS
    async function openDoctorQueue(doctorId) {
        try {
            const response = await fetch(`/api/appointments/doctor/${doctorId}/today`);
            const data = await response.json();
            
            if (data.success) {
                const appointments = data.data;
                const doctor = appointments[0]?.doctor;
                
                document.getElementById('queueModalTitle').textContent = `${doctor?.name || 'Doctor'}'s Queue`;
                
                const detailsHtml = `
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Doctor</p>
                                    <p class="font-medium">${doctor?.name || 'Unknown'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Specialization</p>
                                    <p class="font-medium">${doctor?.specialization || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Room</p>
                                    <p class="font-medium">${doctor?.room || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="px-2 py-1 ${doctor?.status === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} text-xs rounded-full">
                                        ${doctor?.status === 1 ? 'Available' : 'Unavailable'}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Complete Queue List -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Today's Queue (${appointments.length} appointments)</h4>
                            <div class="space-y-2">
                                ${appointments.map(appointment => {
                                    const time = appointment.schedule ? `${appointment.schedule.start_time} - ${appointment.schedule.end_time}` : '';
                                    const isCurrent = doctor?.current_queue === appointment.queue_number;
                                    
                                    return `
                                        <div class="border border-gray-200 rounded-lg p-4 ${isCurrent ? 'bg-green-50' : appointment.appstatus === 'approved' ? 'bg-yellow-50' : 'bg-white'}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 ${getQueueNumberColor(appointment.appstatus)} rounded-full flex items-center justify-center mr-3">
                                                        <span class="font-bold ${getQueueNumberTextColor(appointment.appstatus)}">${appointment.queue_number}</span>
                                                        ${isCurrent ? '<span class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>' : ''}
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">${appointment.user?.name || 'Unknown'}</p>
                                                        <div class="flex items-center space-x-3 mt-1">
                                                            <span class="text-xs ${getStatusTextColor(appointment.appstatus)}">
                                                                ${appointment.appstatus} ${isCurrent ? '(Current)' : ''}
                                                            </span>
                                                            <span class="text-xs text-gray-500">${time}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm text-gray-600">${appointment.schedule?.day ? getDayName(appointment.schedule.day) : ''}</p>
                                                    ${appointment.alert_before ? `
                                                        <p class="text-xs text-gray-500">Alert: ${appointment.alert_before} min</p>
                                                    ` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                        
                        <!-- Queue Actions -->
                        <div class="pt-4 border-t border-gray-200">
                            <button onclick="callNextPatient(${doctorId})" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-forward mr-2"></i> Call Next Patient
                            </button>
                        </div>
                    </div>
                `;
                
                document.getElementById('queueDetails').innerHTML = detailsHtml;
                openModal('queueModal');
            }
        } catch (error) {
            console.error('Error loading doctor queue:', error);
            showNotification('Error loading doctor queue', 'error');
        }
    }

    async function callNextPatient(doctorId, appointmentId = null) {
        try {
            const response = await fetch('/api/appointments/call-next', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ doctor_id: doctorId, appointment_id: appointmentId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                loadDoctorQueues();
                loadAppointments();
                loadTodaysSchedule();
                updateQueueStatistics();
                
                if (document.getElementById('queueModal').classList.contains('hidden')) {
                    closeModal('queueModal');
                }
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error calling next patient:', error);
            showNotification('Error calling next patient', 'error');
        }
    }

    async function completeCurrentAppointment(doctorId) {
        try {
            const response = await fetch('/api/appointments/complete-current', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ doctor_id: doctorId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                loadDoctorQueues();
                loadAppointments();
                loadTodaysSchedule();
                updateQueueStatistics();
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error completing appointment:', error);
            showNotification('Error completing appointment', 'error');
        }
    }

    // APPOINTMENT STATUS FUNCTIONS
    async function changeAppointmentStatus(appointmentId, status) {
        if (!confirm(`Are you sure you want to change status to ${status}?`)) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('id', appointmentId);
            formData.append('appstatus', status);
            
            const response = await fetch('/api/appointments/change-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                loadDoctorQueues();
                loadAppointments();
                loadTodaysSchedule();
                updateQueueStatistics();
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error changing status:', error);
            showNotification('Error changing status', 'error');
        }
    }

    // DELETE FUNCTIONS
    function openDeleteModal(appointmentId) {
        deleteAppointmentId = appointmentId;
        openModal('deleteModal');
    }

    async function confirmDelete() {
        if (!deleteAppointmentId) return;
        
        try {
            const formData = new FormData();
            formData.append('id', deleteAppointmentId);
            
            const response = await fetch('/api/appointments/delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                loadDoctorQueues();
                loadAppointments();
                loadTodaysSchedule();
                updateQueueStatistics();
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error deleting appointment:', error);
            showNotification('Error deleting appointment', 'error');
        } finally {
            closeModal('deleteModal');
            deleteAppointmentId = null;
        }
    }

    // STATISTICS FUNCTIONS
    async function updateQueueStatistics() {
        try {
            const response = await fetch('/api/appointments/statistics/today');
            const data = await response.json();
            
            if (data.success) {
                const stats = data.data;
                document.getElementById('totalToday').textContent = stats.total_today || 0;
                document.getElementById('approvedCount').textContent = stats.approved_count || 0;
                document.getElementById('pendingCount').textContent = stats.pending_count || 0;
                document.getElementById('completedCount').textContent = stats.completed_count || 0;
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    // UTILITY FUNCTIONS
    function updatePagination(data) {
        currentPage = data.current_page;
        totalPages = data.last_page;
        
        document.getElementById('startRow').textContent = data.from || 0;
        document.getElementById('endRow').textContent = data.to || 0;
        document.getElementById('totalRows').textContent = data.total || 0;
        
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    function changePage(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        loadAppointments(page);
    }

    function refreshQueues() {
        loadDoctorQueues();
        loadAppointments();
        loadTodaysSchedule();
        updateQueueStatistics();
        showNotification('Queues refreshed!', 'success');
    }

    function refreshAppointments() {
        loadAppointments(currentPage);
        loadTodaysSchedule();
        showNotification('Appointments refreshed!', 'success');
    }

    // HELPER FUNCTIONS
    function getDayName(dayNumber) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return days[dayNumber] || dayNumber;
    }

    function getFullDayName(dayNumber) {
        const days = [
            'Sunday', 'Monday', 'Tuesday', 'Wednesday', 
            'Thursday', 'Friday', 'Saturday'
        ];
        return days[dayNumber] || `Day ${dayNumber}`;
    }

    function getDayOfWeek(dateString) {
        const date = new Date(dateString);
        return date.getDay(); // 0 = Sunday, 1 = Monday, etc.
    }

    // COLOR UTILITIES
    function getDoctorStatusColor(status) {
        return status === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    function getStatusColor(status) {
        switch(status) {
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'approved': return 'bg-blue-100 text-blue-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            case 'completed': return 'bg-green-100 text-green-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusTextColor(status) {
        switch(status) {
            case 'pending': return 'text-yellow-600';
            case 'approved': return 'text-blue-600';
            case 'cancelled': return 'text-red-600';
            case 'completed': return 'text-green-600';
            default: return 'text-gray-600';
        }
    }

    function getQueueNumberColor(status) {
        switch(status) {
            case 'approved': return 'bg-blue-100';
            case 'completed': return 'bg-green-100';
            case 'cancelled': return 'bg-red-100';
            default: return 'bg-yellow-100';
        }
    }

    function getQueueNumberTextColor(status) {
        switch(status) {
            case 'approved': return 'text-blue-600';
            case 'completed': return 'text-green-600';
            case 'cancelled': return 'text-red-600';
            default: return 'text-yellow-600';
        }
    }

    // FORMATTING FUNCTIONS
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    function formatTime(timeString) {
        if (!timeString) return 'N/A';
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }

    function showNotification(message, type = 'success') {
        const bgColor = type === 'error' ? 'bg-red-600' : 
                       type === 'warning' ? 'bg-yellow-600' : 'bg-green-600';
        const icon = type === 'error' ? 'fa-exclamation-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' : 'fa-check-circle';
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center animate-slide-in`;
        notification.innerHTML = `
            <i class="fas ${icon} mr-3"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('animate-slide-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>

<style>
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    /* Animation for notifications */
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
    
    .animate-slide-out {
        animation: slide-out 0.3s ease-in;
    }
    
    /* Table hover effects */
    tbody tr {
        transition: background-color 0.2s ease;
    }
    
    tbody tr:hover {
        background-color: #eff6ff;
    }
    
    /* Queue number animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .bg-blue-100 {
        animation: pulse 2s infinite;
    }
    
    /* Scrollbar styling */
    .max-h-40::-webkit-scrollbar {
        width: 4px;
    }
    
    .max-h-40::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .max-h-40::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
</style>
@endsection