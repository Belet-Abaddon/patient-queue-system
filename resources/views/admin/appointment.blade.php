@extends('layouts.admin')

@section('title', 'Appointment Management')
@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Appointment Management</h2>
                <p class="text-gray-600 mt-1">Manage patient appointments and doctor queues</p>
            </div>
        </div>
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
                    </div>
                </div>

                <div class="p-6">
                    <!-- Filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                            <select id="doctorFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->specialization }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" id="dateFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                            <button onclick="filterAppointments()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Search
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTable" class="bg-white divide-y divide-gray-200">
                                @if($appointments->count() > 0)
                                @foreach($appointments as $appointment)
                                <tr class="hover:bg-blue-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-bold text-blue-600">
                                                    {{ substr($appointment->user->first_name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $appointment->user->first_name ?? 'Unknown' }} {{ $appointment->user->last_name ?? '' }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $appointment->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">{{ $appointment->doctor->name ?? 'Unknown Doctor' }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->doctor->specialization ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">{{ $appointment->appointment_date }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">
                                            {{ $appointment->schedule->start_time ?? '' }} - {{ $appointment->schedule->end_time ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                        $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800'
                                        ];
                                        $statusColor = $statusColors[$appointment->appstatus] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 {{ $statusColor }} text-xs rounded-full">
                                            {{ ucfirst($appointment->appstatus) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            @if($appointment->appstatus == 'pending')
                                            <button data-id="{{ $appointment->id }}" data-action="confirm"
                                                class="change-status-btn text-green-600 hover:text-green-900" title="Confirm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            @if($appointment->appstatus != 'cancelled' && $appointment->appstatus != 'completed')
                                            <button data-id="{{ $appointment->id }}" data-action="cancel"
                                                class="change-status-btn text-red-600 hover:text-red-900" title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                            @if($appointment->appstatus == 'confirmed')
                                            <button data-id="{{ $appointment->id }}" data-action="complete"
                                                class="change-status-btn text-indigo-600 hover:text-indigo-900" title="Complete">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            @endif
                                            <button data-id="{{ $appointment->id }}"
                                                class="delete-btn text-gray-600 hover:text-gray-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        No appointments found.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div id="pagination" class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Showing <span id="startRow">{{ $appointments->firstItem() ?? 0 }}</span>
                                to <span id="endRow">{{ $appointments->lastItem() ?? 0 }}</span>
                                of <span id="totalRows">{{ $appointments->total() ?? 0 }}</span> results
                            </div>
                            <div class="flex space-x-2">
                                <button id="prevPage"
                                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50"
                                    {{ $appointments->onFirstPage() ? 'disabled' : '' }}>
                                    Previous
                                </button>
                                <button id="nextPage"
                                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50"
                                    {{ !$appointments->hasMorePages() ? 'disabled' : '' }}>
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for AJAX -->
                    <div id="noAppointments" class="text-center py-8 hidden">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Appointments Found</h4>
                        <p class="text-gray-600 mb-4">No appointments match your search criteria.</p>
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
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full" id="todayCount">{{ $todayAppointments->count() }}</span>
                    </div>
                </div>
                <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto" id="todaysSchedule">
                    @if($todayAppointments->count() > 0)
                    @foreach($todayAppointments as $appointment)
                    @php
                    $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'completed' => 'bg-blue-100 text-blue-800'
                    ];
                    $statusColor = $statusColors[$appointment->appstatus] ?? 'bg-gray-100 text-gray-800';

                    $doctorName = $appointment->doctor->name ?? '';
                    $doctorLastName = $doctorName ? explode(' ', $doctorName) : [];
                    $doctorLastName = end($doctorLastName);
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="mr-2">
                                            <span class="font-medium text-gray-900">
                                                {{ $appointment->schedule->start_time ?? '' }} - {{ $appointment->schedule->end_time ?? '' }}
                                            </span>
                                            @if($doctorLastName)
                                            <span class="text-sm text-gray-500 ml-2">Dr. {{ $doctorLastName }}</span>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 {{ $statusColor }} text-xs rounded-full">
                                            {{ ucfirst($appointment->appstatus) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $appointment->user->first_name ?? 'Unknown' }} {{ $appointment->user->last_name ?? '' }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $appointment->doctor->specialization ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-check text-gray-300 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500">No appointments today</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                        Today's Statistics
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Appointments</span>
                            <span class="font-bold text-gray-800" id="totalToday">{{ $stats['total_today'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Confirmed</span>
                            <span class="font-bold text-green-600" id="confirmedCount">{{ $stats['confirmed_count'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-bold text-yellow-600" id="pendingCount">{{ $stats['pending_count'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Completed</span>
                            <span class="font-bold text-blue-600" id="completedCount">{{ $stats['completed_count'] }}</span>
                        </div>
                    </div>
                </div>
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

<script>
    /* =========================
   GLOBAL VARIABLES
========================= */
    let currentPage = 1;
    let totalPages = 1;
    let deleteAppointmentId = null;

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    /* =========================
       PAGE LOAD
    ========================= */
    document.addEventListener('DOMContentLoaded', function() {
        initPagination();
        attachGlobalClickHandler();
    });

    /* =========================
       PAGINATION INIT
    ========================= */
    function initPagination() {
        const start = document.getElementById('startRow')?.textContent || '0';
        const end = document.getElementById('endRow')?.textContent || '0';
        const total = document.getElementById('totalRows')?.textContent || '0';

        currentPage = 1;

        if (total > 0 && end > 0) {
            totalPages = Math.ceil(total / (end - start + 1));
        } else {
            totalPages = 1;
        }
    }

    /* =========================
       GLOBAL CLICK HANDLER
    ========================= */
    function attachGlobalClickHandler() {
        document.addEventListener('click', function(e) {

            // STATUS CHANGE
            const statusBtn = e.target.closest('.change-status-btn');
            if (statusBtn) {
                const id = statusBtn.dataset.id;
                const action = statusBtn.dataset.action;

                let status = 'pending';
                if (action === 'confirm') status = 'confirmed';
                if (action === 'cancel') status = 'cancelled';
                if (action === 'complete') status = 'completed';

                changeAppointmentStatus(id, status);
                return;
            }

            // DELETE
            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                openDeleteModal(deleteBtn.dataset.id);
                return;
            }

            // PAGINATION
            if (e.target.id === 'prevPage' && currentPage > 1) {
                loadAppointments(currentPage - 1);
            }

            if (e.target.id === 'nextPage' && currentPage < totalPages) {
                loadAppointments(currentPage + 1);
            }
        });
    }

    /* =========================
       FILTER
    ========================= */
    function filterAppointments() {
        loadAppointments(1);
    }

    /* =========================
       LOAD APPOINTMENTS
    ========================= */
    async function loadAppointments(page = 1) {
        const doctor = document.getElementById('doctorFilter')?.value || '';
        const status = document.getElementById('statusFilter')?.value || '';
        const date = document.getElementById('dateFilter')?.value || '';

        let url = `/ajax/appointments?page=${page}`;
        if (doctor) url += `&doctor_id=${doctor}`;
        if (status) url += `&status=${status}`;
        if (date) url += `&date=${date}`;

        try {
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) {
                showNotification('Failed to load appointments', 'error');
                return;
            }

            const data = await res.json();

            if (data.success) {
                renderAppointmentsTable(data.data.data);
                updatePagination(data.data);
            }
        } catch (err) {
            console.error(err);
            showNotification('Server error', 'error');
        }
    }

    /* =========================
       RENDER TABLE
    ========================= */
    function renderAppointmentsTable(appointments) {
        const table = document.getElementById('appointmentsTable');
        const empty = document.getElementById('noAppointments');

        if (!appointments.length) {
            table.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }

        empty.classList.add('hidden');

        table.innerHTML = appointments.map(a => `
        <tr>
            <td>${a.user?.first_name || 'Unknown'}</td>
            <td>${a.doctor?.name || 'Unknown'}</td>
            <td>${formatDate(a.appointment_date)}</td>
            <td>${a.schedule ? a.schedule.start_time + ' - ' + a.schedule.end_time : ''}</td>
            <td>
                <span class="${getStatusColor(a.appstatus)} px-2 py-1 rounded text-xs">
                    ${a.appstatus}
                </span>
            </td>
            <td>
                ${a.appstatus === 'pending' ? statusBtn(a.id,'confirm','fa-check','green') : ''}
                ${a.appstatus !== 'cancelled' && a.appstatus !== 'completed'
                    ? statusBtn(a.id,'cancel','fa-times','red') : ''}
                ${a.appstatus === 'confirmed'
                    ? statusBtn(a.id,'complete','fa-check-double','indigo') : ''}
                <button data-id="${a.id}" class="delete-btn text-gray-600">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    }

    function statusBtn(id, action, icon, color) {
        return `
        <button data-id="${id}" data-action="${action}"
            class="change-status-btn text-${color}-600">
            <i class="fas ${icon}"></i>
        </button>
    `;
    }

    /* =========================
       PAGINATION UPDATE
    ========================= */
    function updatePagination(data) {
        currentPage = data.current_page;
        totalPages = data.last_page;

        document.getElementById('startRow').textContent = data.from || 0;
        document.getElementById('endRow').textContent = data.to || 0;
        document.getElementById('totalRows').textContent = data.total || 0;

        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages;
    }

    /* =========================
       STATUS CHANGE
    ========================= */
    async function changeAppointmentStatus(id, status) {
        if (!confirm('Change status to ' + status + '?')) return;

        const form = new FormData();
        form.append('id', id);
        form.append('appstatus', status);

        const res = await fetch('/ajax/appointments/change-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: form
        });

        const data = await res.json();

        if (data.success) {
            showNotification(data.message);
            loadAppointments(currentPage);
            loadTodaysSchedule();
            updateQueueStatistics();
        } else {
            showNotification(data.message, 'error');
        }
    }

    /* =========================
       DELETE
    ========================= */
    function openDeleteModal(id) {
        deleteAppointmentId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    async function confirmDelete() {
        const form = new FormData();
        form.append('id', deleteAppointmentId);

        const res = await fetch('/ajax/appointments/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: form
        });

        const data = await res.json();

        if (data.success) {
            showNotification(data.message);
            loadAppointments(currentPage);
        } else {
            showNotification(data.message, 'error');
        }

        closeModal('deleteModal');
    }

    /* =========================
       TODAY SCHEDULE
    ========================= */
    async function loadTodaysSchedule() {
        const res = await fetch('/ajax/appointments/today', {
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.success) {
            document.getElementById('todayCount').textContent = data.data.length;
        }
    }

    /* =========================
       STATISTICS
    ========================= */
    async function updateQueueStatistics() {
        const res = await fetch('/ajax/appointments/today-statistics');
        const data = await res.json();

        if (data.success) {
            document.getElementById('totalToday').textContent = data.data.total_today;
            document.getElementById('confirmedCount').textContent = data.data.confirmed_count;
            document.getElementById('pendingCount').textContent = data.data.pending_count;
            document.getElementById('completedCount').textContent = data.data.completed_count;
        }
    }

    /* =========================
       HELPERS
    ========================= */
    function getStatusColor(status) {
        if (status === 'pending') return 'bg-yellow-100 text-yellow-800';
        if (status === 'confirmed') return 'bg-green-100 text-green-800';
        if (status === 'cancelled') return 'bg-red-100 text-red-800';
        if (status === 'completed') return 'bg-blue-100 text-blue-800';
        return 'bg-gray-100 text-gray-800';
    }

    function formatDate(d) {
        if (!d) return '';
        return new Date(d).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function showNotification(msg, type = 'success') {
        alert(msg);
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
</style>
@endsection