@extends('layouts.admin')

@section('title', 'Doctors Schedule Management')
@section('page-title', 'Doctors Schedule')

@section('content')
<div class="space-y-6">
    <!-- Doctors Header with CRUD Controls -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Doctors Schedule Management</h2>
                <p class="text-gray-600 mt-1">Manage doctor weekly schedules and availability</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button onclick="openAddDoctorModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-user-plus mr-2"></i> Add Doctor
                </button>
                <button onclick="openAddScheduleModal()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-calendar-plus mr-2"></i> Add Schedule
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Doctors List with CRUD Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-user-md text-blue-600 mr-2"></i>
                            Doctors List
                        </h3>
                        <div class="flex items-center space-x-2">
                            <select id="specializationFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">All Specializations</option>
                                <option value="Cardiology">Heart</option>
                                <option value="Pulmonology">Lungs</option>
                                <option value="Hepatology">Liver</option>
                                <option value="Orthopedics">Bones</option>
                                <option value="Neurology">Brain</option>
                                <option value="Nephrology">Kidneys</option>
                                <option value="Gastroenterology">Stomach</option>
                                <option value="Urology">Urinary</option>
                                <option value="Dermatology">Skin</option>
                                <option value="Ophthalmology">Eyes</option>
                                <option value="ENT">Ear/Nose/Throat</option>
                                <option value="General Medicine">General</option>
                                <option value="Pediatrics">Children</option>
                                <option value="Emergency Medicine">Emergency</option>
                            </select>
                        </div>
                    </div>
                    <!-- Search Input -->
                    <div class="mt-4 relative">
                        <input type="text" id="doctorSearch" placeholder="Search doctors by name..."
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4 space-y-4 max-h-[600px] overflow-y-auto" id="doctorsList">
                    <!-- Doctor cards will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Right Column: Schedule Management -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                            Schedule Management
                        </h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="refreshSchedules()" class="p-2 hover:bg-gray-100 rounded-lg" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Schedule filters -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>
                            <select id="doctorSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Doctors</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Day</label>
                            <select id="dayFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Days</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                            <button onclick="loadSchedules()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Filter
                            </button>
                        </div>
                    </div>

                    <!-- Schedules Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shift</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="schedulesTable" class="bg-white divide-y divide-gray-200">
                                <!-- Schedules will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="noSchedules" class="text-center py-8 hidden">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Schedules Found</h4>
                        <p class="text-gray-600 mb-4">No schedules match your search criteria.</p>
                        <button onclick="openAddScheduleModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i> Add First Schedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Calendar View -->
    <div class="mt-6 bg-white rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-calendar-week text-green-600 mr-2"></i>
                    Weekly Calendar View
                </h3>
                <div class="flex items-center space-x-2">
                    <button onclick="prevWeek()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="weekRange" class="font-medium text-gray-800">Oct 16-22, 2024</span>
                    <button onclick="nextWeek()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button onclick="goToToday()" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                        Today
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Calendar Header -->
            <div class="grid grid-cols-7 mb-4">
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Monday</div>
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Tuesday</div>
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Wednesday</div>
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Thursday</div>
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Friday</div>
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Saturday</div>
                <div class="text-center text-sm font-semibold text-gray-600 p-2">Sunday</div>
            </div>

            <!-- Calendar Body - Column layout -->
            <div class="grid grid-cols-7 gap-4" id="weeklyCalendar">
                <!-- Calendar columns will be loaded here -->
            </div>

            <!-- Legend -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-4">Schedule Legend</h4>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                        <span class="text-xs text-gray-600">Morning Shift</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-xs text-gray-600">Afternoon Shift</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-purple-500 mr-2"></div>
                        <span class="text-xs text-gray-600">Evening Shift</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                        <span class="text-xs text-gray-600">Night Shift</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-xs text-gray-600">On Call</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS SECTION -->

<!-- Add/Edit Doctor Modal -->
<div id="doctorModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="doctorModalTitle">Add New Doctor</h3>
                        <div class="mt-4">
                            <form id="doctorForm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                        <input type="text" name="name" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Specialization *</label>
                                        <select name="specialization" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Specialization</option>
                                            <option value="Cardiology">Heart</option>
                                            <option value="Pulmonology">Lungs</option>
                                            <option value="Hepatology">Liver</option>
                                            <option value="Orthopedics">Bones</option>
                                            <option value="Neurology">Brain</option>
                                            <option value="Nephrology">Kidneys</option>
                                            <option value="Gastroenterology">Stomach</option>
                                            <option value="Urology">Urinary</option>
                                            <option value="Dermatology">Skin</option>
                                            <option value="Ophthalmology">Eyes</option>
                                            <option value="ENT">Ear/Nose/Throat</option>
                                            <option value="General Medicine">General</option>
                                            <option value="Pediatrics">Children</option>
                                            <option value="Emergency Medicine">Emergency</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                        <input type="email" name="email" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                                        <input type="tel" name="phone" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">License Number *</label>
                                        <input type="text" name="license" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Degree *</label>
                                        <input type="text" name="degree" required placeholder="e.g., MD, MBBS, PhD"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                                        <input type="text" name="room"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                                        <textarea name="bio" rows="3"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="on_leave">On Leave</option>
                                            <option value="retired">Retired</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="doctor_id" id="doctor_id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveDoctor()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Doctor
                </button>
                <button type="button" onclick="closeModal('doctorModal')"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Doctor Details Modal -->
<div id="doctorDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white px-6 pt-5 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900" id="doctorDetailTitle">Doctor Details</h3>
                            <button onclick="closeModal('doctorDetailModal')" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4" id="doctorDetailContent">
                            <!-- Details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:px-6">
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('doctorDetailModal')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Schedule Modal -->
<div id="scheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="scheduleModalTitle">Add Schedule</h3>
                        <div class="mt-4">
                            <form id="scheduleForm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor *</label>
                                        <select name="doctor_id" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Doctor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Day *</label>
                                        <select name="day" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
                                        <input type="time" name="start_time" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">End Time *</label>
                                        <input type="time" name="end_time" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Shift Type *</label>
                                        <select name="shift_type" required
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="">Select Shift</option>
                                            <option value="morning">Morning</option>
                                            <option value="afternoon">Afternoon</option>
                                            <option value="evening">Evening</option>
                                            <option value="night">Night</option>
                                            <option value="on_call">On Call</option>
                                            <option value="full_day">Full Day</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                        <textarea name="notes" rows="2" placeholder="Any special instructions or notes..."
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="scheduled">Scheduled</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="schedule_id" id="schedule_id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveSchedule()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Schedule
                </button>
                <button type="button" onclick="closeModal('scheduleModal')"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="deleteModalTitle">Confirm Deletion</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="deleteModalMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
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

<!-- View Schedule Details Modal -->
<div id="viewScheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="viewScheduleTitle">Schedule Details</h3>
                        <div class="mt-4">
                            <div id="scheduleDetails">
                                <!-- Details will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal('viewScheduleModal')"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for CRUD Operations -->
<script>
    // Global variables
    let doctors = [];
    let schedules = [];
    let currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Start from Monday
    let currentDelete = {
        type: null,
        id: null
    };

    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', function() {
        loadDoctors();
        loadSchedules();
        loadWeeklyCalendar();
        updateWeekRangeDisplay();
        setupSearch();

        // Event listeners
        document.getElementById('specializationFilter').addEventListener('change', function() {
            const searchTerm = document.getElementById('doctorSearch').value;
            filterDoctorsByName(searchTerm);
        });
        document.getElementById('doctorSelect').addEventListener('change', loadSchedules);
        document.getElementById('dayFilter').addEventListener('change', loadSchedules);
    });

    // API CALL FUNCTIONS
    async function fetchDoctors() {
        try {
            const response = await fetch('/api/doctors');
            const data = await response.json();
            return data.success ? data.data : [];
        } catch (error) {
            console.error('Error fetching doctors:', error);
            showNotification('Error loading doctors', 'error');
            return [];
        }
    }

    async function fetchSchedules(filters = {}) {
        try {
            let url = '/api/schedules';
            const params = new URLSearchParams(filters).toString();
            if (params) url += '?' + params;

            const response = await fetch(url);
            const data = await response.json();
            return data.success ? data.data : [];
        } catch (error) {
            console.error('Error fetching schedules:', error);
            showNotification('Error loading schedules', 'error');
            return [];
        }
    }

    // MODAL FUNCTIONS
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // DOCTOR MANAGEMENT
    async function openAddDoctorModal() {
        document.getElementById('doctorModalTitle').textContent = 'Add New Doctor';
        document.getElementById('doctorForm').reset();
        document.getElementById('doctor_id').value = '';
        openModal('doctorModal');
    }

    async function openEditDoctorModal(doctorId) {
        try {
            showNotification('Loading doctor details...', 'info');

            const response = await fetch(`/api/doctors/${doctorId}`);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            if (!data.success) {
                throw new Error(data.message || 'Failed to fetch doctor data');
            }

            const doctor = data.data;

            document.getElementById('doctorModalTitle').textContent = 'Edit Doctor';
            document.getElementById('doctor_id').value = doctor.id;

            const form = document.getElementById('doctorForm');
            form.name.value = doctor.name || '';
            form.specialization.value = doctor.specialization || '';
            form.degree.value = doctor.degree || '';
            form.email.value = doctor.email || '';
            form.phone.value = doctor.phone || '';
            form.license.value = doctor.license || '';
            form.room.value = doctor.room || '';
            form.status.value = doctor.status || 'active';
            form.bio.value = doctor.bio || '';

            openModal('doctorModal');
            showNotification('Doctor details loaded', 'success');

        } catch (error) {
            console.error('Error fetching doctor:', error);
            showNotification(`Error: ${error.message}`, 'error');
            console.error('Full error details:', {
                doctorId,
                error: error.message,
                stack: error.stack
            });
        }
    }

    async function saveDoctor() {
        const form = document.getElementById('doctorForm');
        const doctorId = document.getElementById('doctor_id').value;

        // Enhanced validation including degree
        const requiredFields = ['name', 'specialization', 'degree', 'email', 'phone', 'license'];
        const missingFields = requiredFields.filter(field => !form[field].value.trim());

        if (missingFields.length > 0) {
            showNotification(`Please fill in all required fields: ${missingFields.join(', ')}`, 'error');
            return;
        }

        const formData = new FormData(form);
        const doctorData = Object.fromEntries(formData.entries());

        try {
            let url = '/api/doctors';
            let method = 'POST';

            if (doctorId) {
                url = `/api/doctors/${doctorId}`;
                method = 'PUT';
            }

            console.log('Sending doctor data:', doctorData);

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(doctorData)
            });

            const data = await response.json();
            console.log('Server response:', data);

            if (!response.ok) {
                const errorMsg = data.errors ?
                    Object.values(data.errors).flat().join(', ') :
                    data.message || `HTTP error! status: ${response.status}`;
                throw new Error(errorMsg);
            }

            if (!data.success) {
                showNotification(data.message || 'Failed to save doctor', 'error');
                return;
            }

            await loadDoctors();
            updateDoctorDropdown();
            closeModal('doctorModal');
            showNotification(data.message || 'Doctor saved successfully!', 'success');

        } catch (error) {
            console.error('Error saving doctor:', error);
            showNotification(`Save failed: ${error.message}`, 'error');
        }
    }

    async function deleteDoctor(doctorId) {
        currentDelete = {
            type: 'doctor',
            id: doctorId
        };

        try {
            const response = await fetch(`/api/doctors/${doctorId}`);
            const data = await response.json();

            if (data.success) {
                const doctor = data.data;
                document.getElementById('deleteModalMessage').textContent =
                    `Are you sure you want to delete ${doctor?.name || 'this doctor'}? This will also remove all associated schedules.`;
                openModal('deleteModal');
            }
        } catch (error) {
            console.error('Error fetching doctor:', error);
            showNotification('Error loading doctor details', 'error');
        }
    }

    // SCHEDULE MANAGEMENT
    async function openAddScheduleModal() {
        document.getElementById('scheduleModalTitle').textContent = 'Add Schedule';
        document.getElementById('scheduleForm').reset();
        document.getElementById('schedule_id').value = '';

        // Populate doctor dropdown
        const doctorSelect = document.querySelector('#scheduleForm select[name="doctor_id"]');
        doctorSelect.innerHTML = '<option value="">Select Doctor</option>';

        const doctors = await fetchDoctors();
        doctors.forEach(doctor => {
            if (doctor.status === 'active') {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = `${doctor.name} (${doctor.specialization})`;
                doctorSelect.appendChild(option);
            }
        });

        // Set default times
        document.querySelector('#scheduleForm input[name="start_time"]').value = '08:00';
        document.querySelector('#scheduleForm input[name="end_time"]').value = '12:00';
        document.querySelector('#scheduleForm select[name="status"]').value = 'scheduled';

        openModal('scheduleModal');
    }

    async function openEditScheduleModal(scheduleId) {
        try {
            const response = await fetch(`/api/schedules/${scheduleId}`);
            const data = await response.json();

            if (!data.success) {
                showNotification('Schedule not found', 'error');
                return;
            }

            const schedule = data.data;

            document.getElementById('scheduleModalTitle').textContent = 'Edit Schedule';
            document.getElementById('schedule_id').value = schedule.id;

            const form = document.getElementById('scheduleForm');
            form.doctor_id.value = schedule.doctor_id;
            form.start_time.value = schedule.start_time;
            form.end_time.value = schedule.end_time;
            form.shift_type.value = schedule.shift_type;
            form.notes.value = schedule.notes || '';
            form.status.value = schedule.status;
            form.day.value = schedule.day;

            // Populate doctor dropdown
            const doctorSelect = form.doctor_id;
            doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
            const doctors = await fetchDoctors();
            doctors.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = `${doctor.name} (${doctor.specialization})`;
                if (doctor.id == schedule.doctor_id) {
                    option.selected = true;
                }
                doctorSelect.appendChild(option);
            });

            openModal('scheduleModal');
        } catch (error) {
            console.error('Error fetching schedule:', error);
            showNotification('Error loading schedule details', 'error');
        }
    }

    async function saveSchedule() {
        const form = document.getElementById('scheduleForm');
        const scheduleId = document.getElementById('schedule_id').value;

        if (!form.doctor_id.value || !form.day.value ||
            !form.start_time.value || !form.end_time.value ||
            !form.shift_type.value) {
            showNotification('Please fill in all required fields', 'error');
            return;
        }

        if (form.start_time.value >= form.end_time.value) {
            showNotification('End time must be after start time', 'error');
            return;
        }

        try {
            const formData = new FormData(form);
            const scheduleData = Object.fromEntries(formData.entries());

            console.log('Schedule data to save:', scheduleData);

            let url = '/api/schedules';
            let method = 'POST';

            if (scheduleId) {
                url = `/api/schedules/${scheduleId}`;
                method = 'PUT';
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(scheduleData)
            });

            const data = await response.json();
            console.log('Server response:', data);

            if (!response.ok) {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            if (!data.success) {
                showNotification(data.message || 'Failed to save schedule', 'error');
                return;
            }

            await loadSchedules();
            await loadWeeklyCalendar();
            closeModal('scheduleModal');
            showNotification(data.message || 'Schedule saved successfully!', 'success');

        } catch (error) {
            console.error('Error saving schedule:', error);
            showNotification(`Save failed: ${error.message}`, 'error');
        }
    }

    async function deleteSchedule(scheduleId) {
        currentDelete = {
            type: 'schedule',
            id: scheduleId
        };

        try {
            const response = await fetch(`/api/schedules/${scheduleId}`);
            const data = await response.json();

            if (data.success) {
                const schedule = data.data;
                const doctor = schedule.doctor;
                document.getElementById('deleteModalMessage').textContent =
                    `Are you sure you want to delete this schedule for ${doctor?.name || 'unknown doctor'}?`;
                openModal('deleteModal');
            }
        } catch (error) {
            console.error('Error fetching schedule:', error);
            showNotification('Error loading schedule details', 'error');
        }
    }

    // DATA LOADING FUNCTIONS
    async function loadDoctors() {
        const specializationFilter = document.getElementById('specializationFilter').value;
        const container = document.getElementById('doctorsList');

        container.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i><p class="mt-2 text-gray-600">Loading doctors...</p></div>';

        try {
            let url = '/api/doctors';
            if (specializationFilter) {
                url += `?specialization=${encodeURIComponent(specializationFilter)}`;
            }

            const response = await fetch(url);
            const data = await response.json();

            doctors = data.success ? data.data : [];

            if (doctors.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-user-md text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-600">No doctors found</p>
                        <button onclick="openAddDoctorModal()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Add First Doctor
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = doctors.map(doctor => `
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">${doctor.name}</h4>
                                <p class="text-sm text-gray-600">${doctor.specialization}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    ${doctor.degree || 'No degree specified'}
                                </p>
                                <div class="flex items-center mt-1">
                                    <span class="px-2 py-1 ${getStatusColor(doctor.status)} text-xs font-medium rounded-full">
                                        <i class="fas ${getStatusIcon(doctor.status)} text-xs mr-1"></i>
                                        ${doctor.status ? doctor.status.replace('_', ' ').toUpperCase() : 'UNKNOWN'}
                                    </span>
                                    ${doctor.room ? `<span class="ml-2 text-xs text-gray-500">Room ${doctor.room}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button onclick="viewDoctorDetails(${doctor.id})" 
                                class="text-sm text-purple-600 hover:text-purple-800">
                            <i class="fas fa-eye mr-1"></i> 
                        </button>
                        <button onclick="openEditDoctorModal(${doctor.id})" 
                                class="text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit mr-1"></i> 
                        </button>
                        <button onclick="deleteDoctor(${doctor.id})" 
                                class="text-sm text-red-600 hover:text-red-800">
                            <i class="fas fa-trash mr-1"></i> 
                        </button>
                        <button onclick="viewDoctorSchedules(${doctor.id})" 
                                class="text-sm text-green-600 hover:text-green-800">
                            <i class="fas fa-calendar mr-1"></i> 
                        </button>
                    </div>
                </div>
            `).join('');

            updateDoctorDropdown();
        } catch (error) {
            console.error('Error loading doctors:', error);
            container.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-circle mr-2"></i>Error loading doctors</div>';
        }
    }

    // View Doctor Details Function
    async function viewDoctorDetails(doctorId) {
        try {
            showNotification('Loading doctor information...', 'info');

            const response = await fetch(`/api/doctors/${doctorId}`);
            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to load doctor details');
            }

            const doctor = data.data;

            const detailsHtml = `
            <div class="space-y-6">
                <div class="flex items-start space-x-6">
                    <div class="flex-1">
                        <h4 class="text-2xl font-bold text-gray-900">${doctor.name}</h4>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                                ${doctor.specialization}
                            </span>
                            <span class="px-3 py-1 ${getStatusColor(doctor.status)} text-sm rounded-full">
                                ${doctor.status ? doctor.status.replace('_', ' ').toUpperCase() : 'ACTIVE'}
                            </span>
                        </div>
                        <p class="mt-3 text-gray-600">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            ${doctor.degree || 'Degree not specified'}
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-semibold text-gray-700 mb-3">Contact Information</h5>
                        <div class="space-y-2">
                            <p>
                                <i class="fas fa-envelope text-gray-400 mr-2 w-5"></i>
                                <span class="text-gray-600">${doctor.email}</span>
                            </p>
                            <p>
                                <i class="fas fa-phone text-gray-400 mr-2 w-5"></i>
                                <span class="text-gray-600">${doctor.phone}</span>
                            </p>
                            ${doctor.room ? `
                                <p>
                                    <i class="fas fa-door-closed text-gray-400 mr-2 w-5"></i>
                                    <span class="text-gray-600">Room: ${doctor.room}</span>
                                </p>
                            ` : ''}
                            <p>
                                <i class="fas fa-id-card text-gray-400 mr-2 w-5"></i>
                                <span class="text-gray-600">License: ${doctor.license}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-semibold text-gray-700 mb-3">Statistics</h5>
                        <div class="space-y-2">
                            <p class="text-gray-600">Full details will be shown here</p>
                        </div>
                    </div>
                </div>
                
                ${doctor.bio ? `
                    <div>
                        <h5 class="font-semibold text-gray-700 mb-3">Biography</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">${doctor.bio}</p>
                        </div>
                    </div>
                ` : ''}
                
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="font-semibold text-gray-700">Weekly Schedules</h5>
                        <button onclick="viewDoctorSchedules(${doctor.id})" 
                                class="text-sm text-blue-600 hover:text-blue-800">
                            View All Schedules <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                    <div id="doctorRecentSchedules_${doctor.id}" class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-blue-500"></i> Loading schedules...
                    </div>
                </div>
            </div>
        `;

            document.getElementById('doctorDetailContent').innerHTML = detailsHtml;
            openModal('doctorDetailModal');
            loadDoctorRecentSchedules(doctorId);

        } catch (error) {
            console.error('Error loading doctor details:', error);
            showNotification(`Error: ${error.message}`, 'error');
        }
    }

    // Load recent schedules for doctor details
    async function loadDoctorRecentSchedules(doctorId) {
        try {
            const response = await fetch(`/api/doctors/${doctorId}/schedules?limit=5`);
            const data = await response.json();

            const container = document.getElementById(`doctorRecentSchedules_${doctorId}`);

            if (data.success && data.data.length > 0) {
                const schedulesHtml = data.data.map(schedule => `
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg mb-2">
                    <div>
                        <p class="font-medium text-gray-900">${schedule.day}</p>
                        <p class="text-sm text-gray-600">${schedule.start_time} - ${schedule.end_time}</p>
                    </div>
                    <span class="px-2 py-1 ${getShiftColor(schedule.shift_type)} text-xs rounded-full">
                        ${schedule.shift_type.replace('_', ' ')}
                    </span>
                </div>
            `).join('');

                container.innerHTML = schedulesHtml;
            } else {
                container.innerHTML = '<p class="text-gray-500 text-sm">No schedules found</p>';
            }
        } catch (error) {
            console.error('Error loading recent schedules:', error);
            document.getElementById(`doctorRecentSchedules_${doctorId}`).innerHTML =
                '<p class="text-red-500 text-sm">Error loading schedules</p>';
        }
    }

    // Search Doctors Functionality
    function setupSearch() {
        const searchInput = document.getElementById('doctorSearch');
        let searchTimeout;

        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterDoctorsByName(e.target.value);
            }, 300);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                filterDoctorsByName('');
            }
        });
    }

    // Filter doctors by name
    async function filterDoctorsByName(searchTerm) {
        const container = document.getElementById('doctorsList');

        container.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i><p class="mt-2 text-gray-600">Searching...</p></div>';

        try {
            let url = '/api/doctors';
            if (searchTerm.trim()) {
                url += `?search=${encodeURIComponent(searchTerm.trim())}`;
            }

            const response = await fetch(url);
            const data = await response.json();

            let filteredDoctors = data.success ? data.data : [];

            const specializationFilter = document.getElementById('specializationFilter').value;
            if (specializationFilter && searchTerm.trim()) {
                filteredDoctors = filteredDoctors.filter(d =>
                    d.specialization.toLowerCase().includes(specializationFilter.toLowerCase())
                );
            }

            if (filteredDoctors.length === 0) {
                container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
                    <p class="text-gray-600">No doctors found matching "${searchTerm}"</p>
                    <button onclick="document.getElementById('doctorSearch').value = ''; filterDoctorsByName('');" 
                            class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-times mr-1"></i> Clear Search
                    </button>
                </div>
            `;
                return;
            }

            container.innerHTML = filteredDoctors.map(doctor => `
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">${doctor.name}</h4>
                            <p class="text-sm text-gray-600">${doctor.specialization}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                ${doctor.degree || 'No degree specified'}
                            </p>
                            <div class="flex items-center mt-1">
                                <span class="px-2 py-1 ${getStatusColor(doctor.status)} text-xs font-medium rounded-full">
                                    <i class="fas ${getStatusIcon(doctor.status)} text-xs mr-1"></i>
                                    ${doctor.status ? doctor.status.replace('_', ' ').toUpperCase() : 'UNKNOWN'}
                                </span>
                                ${doctor.room ? `<span class="ml-2 text-xs text-gray-500">Room ${doctor.room}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="viewDoctorDetails(${doctor.id})" 
                            class="text-purple-600 hover:text-purple-900">
                        <i class="fas fa-eye mr-1"></i> View
                    </button>
                    <button onclick="openEditDoctorModal(${doctor.id})" 
                            class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button onclick="deleteDoctor(${doctor.id})" 
                            class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                    <button onclick="viewDoctorSchedules(${doctor.id})" 
                            class="text-green-600 hover:text-green-900">
                        <i class="fas fa-calendar mr-1"></i> Schedules
                    </button>
                </div>
            </div>
        `).join('');

        } catch (error) {
            console.error('Error searching doctors:', error);
            container.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-circle mr-2"></i>Error searching doctors</div>';
        }
    }

    async function loadSchedules() {
        const doctorId = document.getElementById('doctorSelect').value;
        const dayFilter = document.getElementById('dayFilter').value;
        const container = document.getElementById('schedulesTable');
        const noSchedules = document.getElementById('noSchedules');

        container.innerHTML = '<tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>Loading schedules...</td></tr>';

        try {
            const filters = {};
            if (doctorId) filters.doctor_id = doctorId;
            if (dayFilter) filters.day = dayFilter;

            schedules = await fetchSchedules(filters);

            if (schedules.length === 0) {
                container.innerHTML = '';
                noSchedules.classList.remove('hidden');
            } else {
                noSchedules.classList.add('hidden');
                container.innerHTML = schedules.map(schedule => {
                    const doctor = schedule.doctor;
                    return `
                        <tr class="hover:bg-blue-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">${doctor?.name || 'Unknown'}</div>
                                        <div class="text-xs text-gray-500">${doctor?.specialization || ''}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">${schedule.day || 'N/A'}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-500">${schedule.start_time} - ${schedule.end_time}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 ${getShiftColor(schedule.shift_type)} text-xs rounded-full">
                                    ${schedule.shift_type ? schedule.shift_type.replace('_', ' ').toUpperCase() : 'N/A'}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 ${getStatusColor(schedule.status)} text-xs rounded-full">
                                    ${schedule.status ? schedule.status.toUpperCase() : 'N/A'}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <button onclick="openEditScheduleModal(${schedule.id})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteSchedule(${schedule.id})" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button onclick="viewScheduleDetails(${schedule.id})" 
                                            class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        } catch (error) {
            console.error('Error loading schedules:', error);
            container.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500"><i class="fas fa-exclamation-circle mr-2"></i>Error loading schedules</td></tr>';
        }
    }

    async function loadWeeklyCalendar() {
    const container = document.getElementById('weeklyCalendar');

    container.innerHTML = '<div class="col-span-7 text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i><p class="mt-2 text-gray-600">Loading calendar...</p></div>';

    try {
        const allSchedules = await fetchSchedules();
        
        const weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        let html = '';

        for (let i = 0; i < 7; i++) {
            const currentDate = new Date(currentWeekStart);
            currentDate.setDate(currentWeekStart.getDate() + i);
            const dayName = weekDays[i];
            
            // Filter schedules for this day of week
            const daySchedules = allSchedules.filter(s => s.day === dayName);

            // Check if today
            const today = new Date();
            const isToday = currentDate.toDateString() === today.toDateString();

            // Get date number and month
            const dateNumber = currentDate.getDate();
            const monthName = currentDate.toLocaleDateString('en-US', { month: 'short' });

            html += `
                <div class="flex flex-col">
                    <!-- Date header -->
                    <div class="text-center p-3 ${isToday ? 'bg-blue-50 border-b-2 border-blue-500' : 'bg-gray-50 border-b border-gray-200'} rounded-t-lg">
                        <div class="text-lg font-bold ${isToday ? 'text-blue-700' : 'text-gray-800'}">${dateNumber}</div>
                        <div class="text-sm ${isToday ? 'text-blue-600' : 'text-gray-600'}">${monthName}</div>
                        <div class="text-xs text-gray-500 mt-1">${dayName}</div>
                        ${isToday ? '<div class="mt-1 text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">TODAY</div>' : ''}
                    </div>
                    
                    <!-- Schedules column -->
                    <div class="flex-1 border-l border-r border-b border-gray-200 rounded-b-lg p-3 min-h-[400px] overflow-y-auto bg-white">
                        <div class="mb-3 flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-700">Schedules:</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                ${daySchedules.length}
                            </span>
                        </div>
                        
                        ${daySchedules.length === 0 ? 
                            `<div class="text-center py-8">
                                <i class="fas fa-calendar-day text-gray-300 text-2xl mb-3"></i>
                                <p class="text-sm text-gray-500">No schedules for ${dayName}</p>
                                <button onclick="openAddScheduleModalWithDay('${dayName}')" class="mt-3 text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Add Schedule for ${dayName}
                                </button>
                            </div>` : 
                            `<div class="space-y-3">` + 
                            daySchedules.map(schedule => {
                                const doctor = schedule.doctor;
                                const scheduleId = schedule.id; // Get schedule ID
                                
                                return `
                                    <div class="p-3 ${getShiftBgColor(schedule.shift_type)} rounded-lg border ${getShiftBorderColor(schedule.shift_type)} hover:shadow-md transition-shadow cursor-pointer" onclick="viewScheduleDetails(${scheduleId})">
                                        <div class="flex items-start">
                                            <div class="w-3 h-3 mt-1 rounded-full ${getShiftDotColor(schedule.shift_type)} mr-2 flex-shrink-0"></div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h5 class="text-sm font-medium text-gray-900 truncate">${doctor?.name || 'Unknown Doctor'}</h5>
                                                    <span class="text-xs ${getStatusTextColor(schedule.status)} font-medium">
                                                        ${schedule.status || 'scheduled'}
                                                    </span>
                                                </div>
                                                <div class="flex items-center text-xs text-gray-600 mb-2">
                                                    <i class="fas fa-clock mr-1 text-xs"></i>
                                                    ${schedule.start_time} - ${schedule.end_time}
                                                </div>
                                                <div class="text-xs text-gray-500 mb-2">
                                                    ${doctor?.specialization || ''}
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs px-2 py-1 ${getShiftColor(schedule.shift_type)} rounded-full">
                                                        ${schedule.shift_type ? schedule.shift_type.replace('_', ' ') : 'N/A'}
                                                    </span>
                                                    <button onclick="event.stopPropagation(); openEditScheduleModal(${scheduleId})" 
                                                            class="text-xs text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('') + 
                            `<div class="pt-3 border-t border-gray-200">
                                <button onclick="openAddScheduleModalWithDay('${dayName}')" class="w-full text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Add Another Schedule for ${dayName}
                                </button>
                            </div>
                            </div>`
                        }
                    </div>
                </div>
            `;
        }

        container.innerHTML = html;
    } catch (error) {
        console.error('Error loading weekly calendar:', error);
        container.innerHTML = '<div class="col-span-7 text-center py-8 text-red-500"><i class="fas fa-exclamation-circle mr-2"></i>Error loading calendar</div>';
    }
}

    function openAddScheduleModalWithDay(day) {
        openAddScheduleModal();
        setTimeout(() => {
            const daySelect = document.querySelector('#scheduleForm select[name="day"]');
            if (daySelect) {
                daySelect.value = day;
            }
        }, 100);
    }

    async function viewScheduleDetails(scheduleId) {
        try {
            const response = await fetch(`/api/schedules/${scheduleId}`);
            const data = await response.json();

            if (!data.success) {
                showNotification('Schedule not found', 'error');
                return;
            }

            const schedule = data.data;
            const doctor = schedule.doctor;

            document.getElementById('viewScheduleTitle').textContent = 'Schedule Details';

            const detailsHtml = `
                <div class="space-y-4">
                    <div class="flex items-center">
                        <img src="${doctor?.avatar || 'https://ui-avatars.com/api/?name=Doctor&background=6b7280&color=fff'}" 
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">${doctor?.name || 'Unknown Doctor'}</h4>
                            <p class="text-sm text-gray-600">${doctor?.specialization || ''}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Day</p>
                            <p class="font-medium">${schedule.day || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Time</p>
                            <p class="font-medium">${schedule.start_time} - ${schedule.end_time}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Shift Type</p>
                            <p class="font-medium">${schedule.shift_type ? schedule.shift_type.replace('_', ' ').toUpperCase() : 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="px-2 py-1 ${getStatusColor(schedule.status)} text-xs rounded-full">
                                ${schedule.status ? schedule.status.toUpperCase() : 'N/A'}
                            </span>
                        </div>
                    </div>
                    
                    ${schedule.notes ? `
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Notes</p>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded">${schedule.notes}</p>
                        </div>
                    ` : ''}
                    
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Schedule Type</p>
                        <p class="text-gray-700">Recurring weekly schedule (applies every ${schedule.day})</p>
                    </div>
                </div>
            `;

            document.getElementById('scheduleDetails').innerHTML = detailsHtml;
            openModal('viewScheduleModal');
        } catch (error) {
            console.error('Error fetching schedule details:', error);
            showNotification('Error loading schedule details', 'error');
        }
    }

    function viewDoctorSchedules(doctorId) {
        document.getElementById('doctorSelect').value = doctorId;
        loadSchedules();

        document.querySelector('[class*="lg:col-span-2"]').scrollIntoView({
            behavior: 'smooth'
        });
    }

    // WEEKLY CALENDAR NAVIGATION
    function prevWeek() {
        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        loadWeeklyCalendar();
        updateWeekRangeDisplay();
    }

    function nextWeek() {
        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
        loadWeeklyCalendar();
        updateWeekRangeDisplay();
    }

    function goToToday() {
        const today = new Date();
        today.setDate(today.getDate() - today.getDay() + 1);
        currentWeekStart = today;
        loadWeeklyCalendar();
        updateWeekRangeDisplay();
    }

    function updateWeekRangeDisplay() {
        const endDate = new Date(currentWeekStart);
        endDate.setDate(endDate.getDate() + 6);

        const weekRange = document.getElementById('weekRange');
        const startMonth = currentWeekStart.toLocaleDateString('en-US', {
            month: 'short'
        });
        const endMonth = endDate.toLocaleDateString('en-US', {
            month: 'short'
        });

        if (startMonth === endMonth) {
            weekRange.textContent = `${startMonth} ${currentWeekStart.getDate()} - ${endDate.getDate()}, ${currentWeekStart.getFullYear()}`;
        } else {
            weekRange.textContent = `${startMonth} ${currentWeekStart.getDate()} - ${endMonth} ${endDate.getDate()}, ${currentWeekStart.getFullYear()}`;
        }
    }

    // UTILITY FUNCTIONS
    function getStatusColor(status) {
        if (!status) return 'bg-gray-100 text-gray-800';

        switch (status.toLowerCase()) {
            case 'active':
            case 'confirmed':
                return 'bg-green-100 text-green-800';
            case 'scheduled':
                return 'bg-blue-100 text-blue-800';
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            case 'on_leave':
                return 'bg-yellow-100 text-yellow-800';
            case 'inactive':
                return 'bg-gray-100 text-gray-800';
            case 'retired':
                return 'bg-purple-100 text-purple-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    function getStatusTextColor(status) {
        if (!status) return 'text-gray-600';

        switch (status.toLowerCase()) {
            case 'confirmed':
                return 'text-green-600';
            case 'scheduled':
                return 'text-blue-600';
            case 'cancelled':
                return 'text-red-600';
            default:
                return 'text-gray-600';
        }
    }

    function getStatusIcon(status) {
        if (!status) return 'fa-question-circle';

        switch (status.toLowerCase()) {
            case 'active':
                return 'fa-circle';
            case 'on_leave':
                return 'fa-umbrella-beach';
            case 'retired':
                return 'fa-user-slash';
            case 'inactive':
                return 'fa-ban';
            case 'scheduled':
                return 'fa-clock';
            case 'confirmed':
                return 'fa-check-circle';
            case 'cancelled':
                return 'fa-times-circle';
            default:
                return 'fa-question-circle';
        }
    }

    function getShiftColor(shift) {
        if (!shift) return 'bg-gray-100 text-gray-800';

        switch (shift.toLowerCase()) {
            case 'morning':
                return 'bg-blue-100 text-blue-800';
            case 'afternoon':
                return 'bg-green-100 text-green-800';
            case 'evening':
                return 'bg-purple-100 text-purple-800';
            case 'night':
                return 'bg-yellow-100 text-yellow-800';
            case 'on_call':
                return 'bg-red-100 text-red-800';
            case 'full_day':
                return 'bg-indigo-100 text-indigo-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    function getShiftBgColor(shift) {
        if (!shift) return 'bg-gray-50';

        switch (shift.toLowerCase()) {
            case 'morning':
                return 'bg-blue-50';
            case 'afternoon':
                return 'bg-green-50';
            case 'evening':
                return 'bg-purple-50';
            case 'night':
                return 'bg-yellow-50';
            case 'on_call':
                return 'bg-red-50';
            case 'full_day':
                return 'bg-indigo-50';
            default:
                return 'bg-gray-50';
        }
    }

    function getShiftBorderColor(shift) {
        if (!shift) return 'border-gray-200';

        switch (shift.toLowerCase()) {
            case 'morning':
                return 'border-blue-200';
            case 'afternoon':
                return 'border-green-200';
            case 'evening':
                return 'border-purple-200';
            case 'night':
                return 'border-yellow-200';
            case 'on_call':
                return 'border-red-200';
            case 'full_day':
                return 'border-indigo-200';
            default:
                return 'border-gray-200';
        }
    }

    function getShiftDotColor(shift) {
        if (!shift) return 'bg-gray-500';

        switch (shift.toLowerCase()) {
            case 'morning':
                return 'bg-blue-500';
            case 'afternoon':
                return 'bg-green-500';
            case 'evening':
                return 'bg-purple-500';
            case 'night':
                return 'bg-yellow-500';
            case 'on_call':
                return 'bg-red-500';
            case 'full_day':
                return 'bg-indigo-500';
            default:
                return 'bg-gray-500';
        }
    }

    function formatDate(dateString) {
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return dateString;
            }
            return date.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        } catch (error) {
            return dateString;
        }
    }

    async function updateDoctorDropdown() {
        const select = document.getElementById('doctorSelect');

        try {
            const doctors = await fetchDoctors();

            select.innerHTML = '<option value="">All Doctors</option>';
            doctors.forEach(doctor => {
                if (doctor.status === 'active') {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = `${doctor.name} (${doctor.specialization})`;
                    select.appendChild(option);
                }
            });
        } catch (error) {
            console.error('Error updating doctor dropdown:', error);
        }
    }

    async function refreshSchedules() {
        await loadSchedules();
        await loadWeeklyCalendar();
        showNotification('Schedules refreshed!', 'success');
    }

    async function confirmDelete() {
        const {
            type,
            id
        } = currentDelete;

        try {
            let url = '';
            let method = 'DELETE';

            if (type === 'doctor') {
                url = `/api/doctors/${id}`;
            } else if (type === 'schedule') {
                url = `/api/schedules/${id}`;
            } else {
                throw new Error('Invalid delete type');
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to delete');
            }

            if (!data.success) {
                showNotification(data.message || 'Failed to delete', 'error');
                return;
            }

            if (type === 'doctor') {
                await loadDoctors();
            }
            await loadSchedules();
            await loadWeeklyCalendar();

            showNotification(data.message || 'Deleted successfully!', 'success');
            closeModal('deleteModal');

        } catch (error) {
            console.error('Error deleting:', error);
            showNotification(error.message || 'Failed to delete', 'error');
        } finally {
            currentDelete = {
                type: null,
                id: null
            };
        }
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        const bgColor = type === 'error' ? 'bg-red-600' : 'bg-green-600';
        const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';

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

    .doctor-card {
        transition: all 0.2s ease;
    }

    .doctor-card:hover {
        transform: translateY(-2px);
    }

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

    tbody tr {
        transition: background-color 0.2s ease;
    }

    tbody tr:hover {
        background-color: #eff6ff;
    }

    .min-h-\[400px\] {
        min-height: 400px;
    }

    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .transition-shadow {
        transition: box-shadow 0.2s ease-in-out;
    }

    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .grid-cols-7.gap-4>* {
        min-height: 500px;
    }

    .rounded-b-lg {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .rounded-t-lg {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    #doctorSearch:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .clear-search {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        display: none;
    }

    .clear-search:hover {
        color: #6b7280;
    }

    #doctorSearch:not(:placeholder-shown)+.clear-search {
        display: block;
    }

    #doctorDetailModal .modal-content {
        max-height: 80vh;
        overflow-y: auto;
    }

    .highlight {
        background-color: #fef3c7;
        padding: 0 2px;
        border-radius: 2px;
    }
</style>
@endsection