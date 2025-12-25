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
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                            <select id="doctorFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Doctors</option>
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
                            <span class="text-gray-600">Total in Queue</span>
                            <span class="font-bold text-gray-800" id="totalInQueue">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Average Wait Time</span>
                            <span class="font-bold text-gray-800" id="avgWaitTime">0 min</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Currently Serving</span>
                            <span class="font-bold text-green-600" id="currentlyServing">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Waiting</span>
                            <span class="font-bold text-yellow-600" id="waitingCount">0</span>
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient *</label>
                                        <select name="patient_id" required 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Patient</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor *</label>
                                        <select name="doctor_id" required 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Doctor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                        <input type="date" name="appointment_date" required 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Time *</label>
                                        <input type="time" name="appointment_time" required 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                                        <select name="appointment_type" required 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Type</option>
                                            <option value="consultation">Consultation</option>
                                            <option value="follow_up">Follow-up</option>
                                            <option value="check_up">Check-up</option>
                                            <option value="emergency">Emergency</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                                        <select name="duration" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="15">15 minutes</option>
                                            <option value="30" selected>30 minutes</option>
                                            <option value="45">45 minutes</option>
                                            <option value="60">60 minutes</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                                        <textarea name="reason" rows="2" placeholder="Reason for appointment..."
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="appointment_id" id="appointment_id">
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

<!-- JavaScript -->
<script>
    // Sample data storage
    let patients = [
        {
            id: 1,
            name: "John Doe",
            phone: "+1 (555) 123-4567",
            age: 35,
            avatar: "https://ui-avatars.com/api/?name=John+Doe&background=3b82f6&color=fff"
        },
        {
            id: 2,
            name: "Jane Smith",
            phone: "+1 (555) 234-5678",
            age: 28,
            avatar: "https://ui-avatars.com/api/?name=Jane+Smith&background=10b981&color=fff"
        },
        {
            id: 3,
            name: "Robert Johnson",
            phone: "+1 (555) 345-6789",
            age: 42,
            avatar: "https://ui-avatars.com/api/?name=Robert+Johnson&background=8b5cf6&color=fff"
        },
        {
            id: 4,
            name: "Sarah Williams",
            phone: "+1 (555) 456-7890",
            age: 31,
            avatar: "https://ui-avatars.com/api/?name=Sarah+Williams&background=f59e0b&color=fff"
        }
    ];

    let doctors = [
        {
            id: 1,
            name: "Dr. Michael Smith",
            specialization: "Emergency Medicine",
            room: "201",
            status: "available",
            current_queue_number: 1,
            avg_consultation_time: 15
        },
        {
            id: 2,
            name: "Dr. Sarah Johnson",
            specialization: "Pediatrics",
            room: "305",
            status: "in_consultation",
            current_queue_number: 2,
            avg_consultation_time: 20
        },
        {
            id: 3,
            name: "Dr. Robert Williams",
            specialization: "Cardiology",
            room: "402",
            status: "available",
            current_queue_number: 1,
            avg_consultation_time: 25
        }
    ];

    let appointments = [
        {
            id: 1,
            patient_id: 1,
            doctor_id: 1,
            appointment_date: new Date().toISOString().split('T')[0],
            appointment_time: "09:00",
            queue_number: 1,
            duration: 30,
            appointment_type: "consultation",
            reason: "Headache and fever",
            status: "in_progress",
            checkin_time: "08:45",
            wait_time: 15,
            created_at: new Date().toISOString()
        },
        {
            id: 2,
            patient_id: 2,
            doctor_id: 1,
            appointment_date: new Date().toISOString().split('T')[0],
            appointment_time: "09:30",
            queue_number: 2,
            duration: 30,
            appointment_type: "follow_up",
            reason: "Follow-up for vaccination",
            status: "waiting",
            checkin_time: "09:15",
            wait_time: 25,
            created_at: new Date().toISOString()
        },
        {
            id: 3,
            patient_id: 3,
            doctor_id: 2,
            appointment_date: new Date().toISOString().split('T')[0],
            appointment_time: "10:00",
            queue_number: 1,
            duration: 45,
            appointment_type: "consultation",
            reason: "Child check-up",
            status: "in_progress",
            checkin_time: "09:50",
            wait_time: 10,
            created_at: new Date().toISOString()
        },
        {
            id: 4,
            patient_id: 4,
            doctor_id: 3,
            appointment_date: new Date().toISOString().split('T')[0],
            appointment_time: "11:00",
            queue_number: 1,
            duration: 60,
            appointment_type: "check_up",
            reason: "Heart check-up",
            status: "waiting",
            checkin_time: "10:45",
            wait_time: 15,
            created_at: new Date().toISOString()
        }
    ];

    document.addEventListener('DOMContentLoaded', function() {
        loadDoctorQueues();
        loadAppointments();
        loadTodaysSchedule();
        updateDoctorDropdowns();
        updatePatientDropdown();
        updateQueueStatistics();
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

    // APPOINTMENT MANAGEMENT
    function openAddAppointmentModal() {
        document.getElementById('appointmentModalTitle').textContent = 'New Appointment';
        document.getElementById('appointmentForm').reset();
        document.getElementById('appointment_id').value = '';
        
        // Set default date to today
        const today = new Date();
        document.querySelector('#appointmentForm input[name="appointment_date"]').value = 
            today.toISOString().split('T')[0];
        
        // Set default time to next hour
        const nextHour = new Date();
        nextHour.setHours(nextHour.getHours() + 1);
        nextHour.setMinutes(0, 0, 0);
        document.querySelector('#appointmentForm input[name="appointment_time"]').value = 
            nextHour.toTimeString().slice(0, 5);
        
        openModal('appointmentModal');
    }

    function saveAppointment() {
        const form = document.getElementById('appointmentForm');
        const appointmentId = document.getElementById('appointment_id').value;
        
        // Validation
        if (!form.patient_id.value || !form.doctor_id.value || !form.appointment_date.value || 
            !form.appointment_time.value || !form.appointment_type.value) {
            alert('Please fill in all required fields');
            return;
        }

        // Calculate queue number for the doctor on that day
        const doctorId = parseInt(form.doctor_id.value);
        const appointmentDate = form.appointment_date.value;
        const doctorAppointments = appointments.filter(a => 
            a.doctor_id === doctorId && 
            a.appointment_date === appointmentDate
        );
        const queueNumber = doctorAppointments.length + 1;

        const appointmentData = {
            id: appointmentId ? parseInt(appointmentId) : appointments.length + 1,
            patient_id: parseInt(form.patient_id.value),
            doctor_id: doctorId,
            appointment_date: appointmentDate,
            appointment_time: form.appointment_time.value,
            queue_number: queueNumber,
            duration: parseInt(form.duration.value),
            appointment_type: form.appointment_type.value,
            reason: form.reason.value,
            status: "scheduled",
            checkin_time: null,
            wait_time: 0,
            created_at: new Date().toISOString()
        };

        if (appointmentId) {
            // Update existing appointment
            const index = appointments.findIndex(a => a.id == appointmentId);
            if (index !== -1) {
                appointments[index] = appointmentData;
            }
        } else {
            // Add new appointment
            appointments.push(appointmentData);
        }

        loadDoctorQueues();
        loadAppointments();
        loadTodaysSchedule();
        updateQueueStatistics();
        closeModal('appointmentModal');
        showNotification(appointmentId ? 'Appointment updated successfully!' : 'Appointment created successfully!');
    }

    function updateAppointmentStatus(appointmentId, status) {
        const appointment = appointments.find(a => a.id == appointmentId);
        if (appointment) {
            appointment.status = status;
            
            // If checking in, set checkin time
            if (status === 'checked_in' && !appointment.checkin_time) {
                appointment.checkin_time = new Date().toISOString().split('T')[1].slice(0, 5);
            }
            
            // If starting consultation, update doctor's current queue
            if (status === 'in_progress') {
                const doctor = doctors.find(d => d.id === appointment.doctor_id);
                if (doctor) {
                    doctor.current_queue_number = appointment.queue_number;
                    doctor.status = 'in_consultation';
                }
            }
            
            // If completed, make doctor available for next patient
            if (status === 'completed') {
                const doctor = doctors.find(d => d.id === appointment.doctor_id);
                if (doctor) {
                    doctor.status = 'available';
                }
            }
            
            loadDoctorQueues();
            loadAppointments();
            loadTodaysSchedule();
            updateQueueStatistics();
            showNotification(`Appointment ${status.replace('_', ' ')}`);
        }
    }

    function moveToNextPatient(doctorId) {
        const doctor = doctors.find(d => d.id === doctorId);
        if (!doctor) return;

        // Find next waiting patient for this doctor today
        const today = new Date().toISOString().split('T')[0];
        const waitingAppointments = appointments.filter(a => 
            a.doctor_id === doctorId && 
            a.appointment_date === today &&
            a.status === 'waiting'
        ).sort((a, b) => a.queue_number - b.queue_number);

        if (waitingAppointments.length > 0) {
            const nextAppointment = waitingAppointments[0];
            updateAppointmentStatus(nextAppointment.id, 'in_progress');
            doctor.status = 'in_consultation';
            doctor.current_queue_number = nextAppointment.queue_number;
        } else {
            // No more waiting patients
            doctor.status = 'available';
            showNotification('No more patients waiting for this doctor');
        }
    }

    // DOCTOR QUEUES DISPLAY
    function loadDoctorQueues() {
        const container = document.getElementById('doctorQueues');
        const today = new Date().toISOString().split('T')[0];
        
        let html = '';
        
        doctors.forEach(doctor => {
            // Get today's appointments for this doctor
            const doctorAppointments = appointments.filter(a => 
                a.doctor_id === doctor.id && 
                a.appointment_date === today
            ).sort((a, b) => a.queue_number - b.queue_number);
            
            // Separate current and waiting patients
            const currentAppointment = doctorAppointments.find(a => a.status === 'in_progress');
            const waitingPatients = doctorAppointments.filter(a => a.status === 'waiting');
            const completedPatients = doctorAppointments.filter(a => a.status === 'completed');
            
            html += `
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-900">${doctor.name}</h4>
                                <p class="text-sm text-gray-600">${doctor.specialization}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 ${getDoctorStatusColor(doctor.status)} text-xs rounded-full">
                                    ${doctor.status.replace('_', ' ')}
                                </span>
                                <span class="text-xs text-gray-500">Room ${doctor.room}</span>
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
                                                <p class="font-medium text-gray-900">${patients.find(p => p.id === currentAppointment.patient_id)?.name || 'Unknown'}</p>
                                                <p class="text-xs text-gray-500">Queue #${currentAppointment.queue_number}</p>
                                            </div>
                                        </div>
                                        <button onclick="updateAppointmentStatus(${currentAppointment.id}, 'completed')" 
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
                                        const patient = patients.find(p => p.id === appointment.patient_id);
                                        return `
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                                        <span class="text-xs font-bold text-yellow-600">${appointment.queue_number}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">${patient?.name || 'Unknown'}</p>
                                                        <p class="text-xs text-gray-500">Wait: ${appointment.wait_time} min</p>
                                                    </div>
                                                </div>
                                                <button onclick="updateAppointmentStatus(${appointment.id}, 'checked_in')" 
                                                        class="text-xs text-blue-600 hover:text-blue-800">
                                                    Check-in
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

    function openDoctorQueue(doctorId) {
        const doctor = doctors.find(d => d.id === doctorId);
        const today = new Date().toISOString().split('T')[0];
        const doctorAppointments = appointments.filter(a => 
            a.doctor_id === doctorId && 
            a.appointment_date === today
        ).sort((a, b) => a.queue_number - b.queue_number);
        
        document.getElementById('queueModalTitle').textContent = `${doctor.name}'s Queue`;
        
        const detailsHtml = `
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Doctor</p>
                            <p class="font-medium">${doctor.name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Specialization</p>
                            <p class="font-medium">${doctor.specialization}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Room</p>
                            <p class="font-medium">${doctor.room}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="px-2 py-1 ${getDoctorStatusColor(doctor.status)} text-xs rounded-full">
                                ${doctor.status.replace('_', ' ')}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Complete Queue List -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Today's Queue (${doctorAppointments.length} appointments)</h4>
                    <div class="space-y-2">
                        ${doctorAppointments.map(appointment => {
                            const patient = patients.find(p => p.id === appointment.patient_id);
                            return `
                                <div class="border border-gray-200 rounded-lg p-4 ${getQueueItemBgColor(appointment.status)}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 ${getQueueNumberColor(appointment.status)} rounded-full flex items-center justify-center mr-3">
                                                <span class="font-bold ${getQueueNumberTextColor(appointment.status)}">${appointment.queue_number}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">${patient?.name || 'Unknown'}</p>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-xs ${getStatusTextColor(appointment.status)}">
                                                        ${appointment.status.replace('_', ' ')}
                                                    </span>
                                                    <span class="text-xs text-gray-500">${appointment.appointment_time}</span>
                                                    <span class="text-xs text-gray-500">${appointment.duration} min</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">${appointment.appointment_type.replace('_', ' ')}</p>
                                            ${appointment.wait_time > 0 ? `
                                                <p class="text-xs text-gray-500">Wait: ${appointment.wait_time} min</p>
                                            ` : ''}
                                        </div>
                                    </div>
                                    ${appointment.reason ? `
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-sm text-gray-600">${appointment.reason}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
                
                <!-- Queue Actions -->
                <div class="pt-4 border-t border-gray-200">
                    <button onclick="moveToNextPatient(${doctorId})" 
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-forward mr-2"></i> Call Next Patient
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('queueDetails').innerHTML = detailsHtml;
        openModal('queueModal');
    }

    // APPOINTMENTS TABLE
    function loadAppointments() {
        const doctorFilter = document.getElementById('doctorFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        // Filter appointments
        let filteredAppointments = appointments;
        
        if (doctorFilter) {
            filteredAppointments = filteredAppointments.filter(a => a.doctor_id == doctorFilter);
        }
        
        if (dateFilter) {
            filteredAppointments = filteredAppointments.filter(a => a.appointment_date === dateFilter);
        }
        
        // Sort by date, time, and queue number
        filteredAppointments.sort((a, b) => {
            if (a.appointment_date === b.appointment_date) {
                if (a.doctor_id === b.doctor_id) {
                    return a.queue_number - b.queue_number;
                }
                return a.doctor_id - b.doctor_id;
            }
            return a.appointment_date.localeCompare(b.appointment_date);
        });
        
        // Render appointments table
        const container = document.getElementById('appointmentsTable');
        const noAppointments = document.getElementById('noAppointments');
        
        if (filteredAppointments.length === 0) {
            container.innerHTML = '';
            noAppointments.classList.remove('hidden');
        } else {
            noAppointments.classList.add('hidden');
            container.innerHTML = filteredAppointments.map(appointment => {
                const patient = patients.find(p => p.id === appointment.patient_id);
                const doctor = doctors.find(d => d.id === appointment.doctor_id);
                
                return `
                    <tr class="hover:bg-blue-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <img src="${patient?.avatar || 'https://ui-avatars.com/api/?name=Patient&background=3b82f6&color=fff'}" 
                                     class="w-8 h-8 rounded-full mr-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">${patient?.name || 'Unknown Patient'}</div>
                                    <div class="text-xs text-gray-500">${patient?.phone || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">${doctor?.name || 'Unknown Doctor'}</div>
                            <div class="text-xs text-gray-500">${doctor?.specialization || ''}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900">${formatTime(appointment.appointment_time)}</div>
                            <div class="text-xs text-gray-500">${formatDate(appointment.appointment_date)}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="w-8 h-8 ${getQueueNumberColor(appointment.status)} rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold ${getQueueNumberTextColor(appointment.status)}">${appointment.queue_number}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 ${getStatusColor(appointment.status)} text-xs rounded-full">
                                ${appointment.status.replace('_', ' ')}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <button onclick="updateAppointmentStatus(${appointment.id}, 'checked_in')" 
                                        class="text-purple-600 hover:text-purple-900" title="Check-in">
                                    <i class="fas fa-user-check"></i>
                                </button>
                                <button onclick="updateAppointmentStatus(${appointment.id}, 'in_progress')" 
                                        class="text-green-600 hover:text-green-900" title="Start Consultation">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button onclick="updateAppointmentStatus(${appointment.id}, 'completed')" 
                                        class="text-blue-600 hover:text-blue-900" title="Complete">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }
    }

    function loadTodaysSchedule() {
        const today = new Date().toISOString().split('T')[0];
        const todaysAppointments = appointments.filter(a => a.appointment_date === today);
        
        // Sort by time and queue number
        todaysAppointments.sort((a, b) => {
            if (a.appointment_time === b.appointment_time) {
                return a.queue_number - b.queue_number;
            }
            return a.appointment_time.localeCompare(b.appointment_time);
        });
        
        const container = document.getElementById('todaysSchedule');
        document.getElementById('todayCount').textContent = todaysAppointments.length;
        
        if (todaysAppointments.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-calendar-check text-gray-300 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-500">No appointments today</p>
                </div>
            `;
        } else {
            container.innerHTML = todaysAppointments.map(appointment => {
                const patient = patients.find(p => p.id === appointment.patient_id);
                const doctor = doctors.find(d => d.id === appointment.doctor_id);
                
                return `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 ${getQueueNumberColor(appointment.status)} rounded-full flex items-center justify-center mr-2">
                                            <span class="text-xs font-bold ${getQueueNumberTextColor(appointment.status)}">${appointment.queue_number}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-900">${formatTime(appointment.appointment_time)}</span>
                                            <span class="text-sm text-gray-500 ml-2">${doctor?.name?.split(' ')[1] || 'Dr.'}</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 ${getStatusColor(appointment.status)} text-xs rounded-full">
                                        ${appointment.status.replace('_', ' ')}
                                    </span>
                                </div>
                                <div class="text-sm font-medium text-gray-900">${patient?.name || 'Unknown'}</div>
                                <div class="text-xs text-gray-500">${appointment.reason || 'No reason provided'}</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    // UTILITY FUNCTIONS
    function updateDoctorDropdowns() {
        const filterSelect = document.getElementById('doctorFilter');
        const appointmentSelect = document.querySelector('#appointmentForm select[name="doctor_id"]');
        
        // Clear existing options
        filterSelect.innerHTML = '<option value="">All Doctors</option>';
        if (appointmentSelect) {
            appointmentSelect.innerHTML = '<option value="">Select Doctor</option>';
        }
        
        // Add doctor options
        doctors.forEach(doctor => {
            // For filter dropdown
            const filterOption = document.createElement('option');
            filterOption.value = doctor.id;
            filterOption.textContent = `${doctor.name} (${doctor.specialization})`;
            filterSelect.appendChild(filterOption);
            
            // For appointment form dropdown
            if (appointmentSelect) {
                const appointOption = document.createElement('option');
                appointOption.value = doctor.id;
                appointOption.textContent = `${doctor.name} (${doctor.specialization}) - Room ${doctor.room}`;
                appointmentSelect.appendChild(appointOption);
            }
        });
    }

    function updatePatientDropdown() {
        const select = document.querySelector('#appointmentForm select[name="patient_id"]');
        if (select) {
            select.innerHTML = '<option value="">Select Patient</option>';
            patients.forEach(patient => {
                const option = document.createElement('option');
                option.value = patient.id;
                option.textContent = `${patient.name} (${patient.phone})`;
                select.appendChild(option);
            });
        }
    }

    function updateQueueStatistics() {
        const today = new Date().toISOString().split('T')[0];
        const todaysAppointments = appointments.filter(a => a.appointment_date === today);
        
        // Calculate statistics
        const totalInQueue = todaysAppointments.filter(a => 
            ['waiting', 'in_progress'].includes(a.status)
        ).length;
        
        const currentlyServing = todaysAppointments.filter(a => 
            a.status === 'in_progress'
        ).length;
        
        const waitingCount = todaysAppointments.filter(a => 
            a.status === 'waiting'
        ).length;
        
        // Calculate average wait time
        const waitingAppointments = todaysAppointments.filter(a => a.status === 'waiting');
        const avgWaitTime = waitingAppointments.length > 0 ? 
            Math.round(waitingAppointments.reduce((sum, a) => sum + a.wait_time, 0) / waitingAppointments.length) : 0;
        
        // Update UI
        document.getElementById('totalInQueue').textContent = totalInQueue;
        document.getElementById('currentlyServing').textContent = currentlyServing;
        document.getElementById('waitingCount').textContent = waitingCount;
        document.getElementById('avgWaitTime').textContent = `${avgWaitTime} min`;
    }

    function refreshQueues() {
        // Update wait times
        const today = new Date().toISOString().split('T')[0];
        appointments.forEach(appointment => {
            if (appointment.appointment_date === today && appointment.status === 'waiting') {
                // Increase wait time by 1 minute on refresh
                appointment.wait_time = (appointment.wait_time || 0) + 1;
            }
        });
        
        loadDoctorQueues();
        loadAppointments();
        loadTodaysSchedule();
        updateQueueStatistics();
        showNotification('Queues refreshed!');
    }

    function refreshAppointments() {
        loadAppointments();
        loadTodaysSchedule();
        showNotification('Appointments refreshed!');
    }

    // COLOR UTILITIES
    function getDoctorStatusColor(status) {
        switch(status) {
            case 'available': return 'bg-green-100 text-green-800';
            case 'in_consultation': return 'bg-blue-100 text-blue-800';
            case 'busy': return 'bg-yellow-100 text-yellow-800';
            case 'away': return 'bg-gray-100 text-gray-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusColor(status) {
        switch(status) {
            case 'scheduled': return 'bg-blue-100 text-blue-800';
            case 'checked_in': return 'bg-purple-100 text-purple-800';
            case 'waiting': return 'bg-yellow-100 text-yellow-800';
            case 'in_progress': return 'bg-green-100 text-green-800';
            case 'completed': return 'bg-indigo-100 text-indigo-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusTextColor(status) {
        switch(status) {
            case 'scheduled': return 'text-blue-600';
            case 'checked_in': return 'text-purple-600';
            case 'waiting': return 'text-yellow-600';
            case 'in_progress': return 'text-green-600';
            case 'completed': return 'text-indigo-600';
            case 'cancelled': return 'text-red-600';
            default: return 'text-gray-600';
        }
    }

    function getQueueNumberColor(status) {
        switch(status) {
            case 'in_progress': return 'bg-green-100';
            case 'waiting': return 'bg-yellow-100';
            case 'checked_in': return 'bg-purple-100';
            case 'completed': return 'bg-gray-100';
            default: return 'bg-blue-100';
        }
    }

    function getQueueNumberTextColor(status) {
        switch(status) {
            case 'in_progress': return 'text-green-600';
            case 'waiting': return 'text-yellow-600';
            case 'checked_in': return 'text-purple-600';
            case 'completed': return 'text-gray-600';
            default: return 'text-blue-600';
        }
    }

    function getQueueItemBgColor(status) {
        switch(status) {
            case 'in_progress': return 'bg-green-50';
            case 'waiting': return 'bg-yellow-50';
            default: return 'bg-white';
        }
    }

    // FORMATTING FUNCTIONS
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric'
        });
    }

    function formatTime(timeString) {
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center animate-slide-in';
        notification.innerHTML = `
            <i class="fas fa-check-circle mr-3"></i>
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
    
    .bg-green-100 {
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