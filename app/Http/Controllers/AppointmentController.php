<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'doctor', 'schedule'])
            ->where('status', 1);
        
        // Apply filters
        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->has('status')) {
            $query->where('appstatus', $request->status);
        }
        
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        // Sort by created date
        $query->orderBy('created_at', 'desc');
        
        // Paginate results
        $appointments = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
    
    public function show($id)
    {
        $appointment = Appointment::with(['user', 'doctor', 'schedule'])
            ->where('id', $id)
            ->where('status', 1)
            ->firstOrFail();
            
        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'queue_number' => 'nullable|integer',
            'alert_before' => 'nullable|integer|min:1|max:60',
            'appstatus' => 'nullable|in:pending,approved,cancelled,completed',
            'appointment_date' => 'required|date',
        ]);

        // Check if schedule is available
        $schedule = DoctorSchedule::findOrFail($validated['schedule_id']);
        if ($schedule->status != 'scheduled' && $schedule->status != 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'This schedule is not available'
            ], 400);
        }

        // Check if doctor exists and is active
        $doctor = Doctor::findOrFail($validated['doctor_id']);
        if ($doctor->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor is not available'
            ], 400);
        }

        // Get day of week from appointment date
        $appointmentDate = Carbon::parse($validated['appointment_date']);
        $dayOfWeek = $appointmentDate->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        
        // Check if schedule matches the day
        if ($schedule->day != $dayOfWeek) {
            return response()->json([
                'success' => false,
                'message' => 'Selected schedule is not available on this day'
            ], 400);
        }

        // Check if appointment already exists for this user, doctor, and date
        $existingAppointment = Appointment::where('user_id', $validated['user_id'])
            ->where('doctor_id', $validated['doctor_id'])
            ->whereDate('created_at', $validated['appointment_date'])
            ->where('status', 1)
            ->first();
            
        if ($existingAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an appointment with this doctor on this date'
            ], 400);
        }

        // Generate queue number if not provided
        if (!isset($validated['queue_number'])) {
            $lastQueue = Appointment::where('doctor_id', $validated['doctor_id'])
                ->whereDate('created_at', $validated['appointment_date'])
                ->max('queue_number');
                
            $validated['queue_number'] = $lastQueue ? $lastQueue + 1 : 1;
        }

        // Set default values
        $validated['alert_sent'] = 0;
        $validated['status'] = 1;
        
        if (!isset($validated['appstatus'])) {
            $validated['appstatus'] = 'pending';
        }

        // Create appointment
        $appointment = Appointment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Appointment created successfully',
            'data' => $appointment
        ], 201);
    }
    
    public function update(Request $request)
    {
        // Get appointment ID from request
        $id = $request->input('id');
        $appointment = Appointment::findOrFail($id);

        // Validate data
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'schedule_id' => 'sometimes|exists:doctor_schedules,id',
            'queue_number' => 'nullable|integer',
            'alert_before' => 'nullable|integer|min:1|max:60',
            'alert_sent' => 'nullable|in:0,1',
            'appstatus' => 'nullable|in:pending,approved,cancelled,completed',
            'status' => 'nullable|in:0,1',
            'appointment_date' => 'nullable|date',
        ]);

        // Update appointment
        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully',
            'data' => $appointment
        ]);
    }
    
    // ... [Other methods remain the same, but update date filtering] ...
    
    public function getTodayAppointments()
    {
        $today = Carbon::today()->toDateString();
        
        $appointments = Appointment::with(['user', 'doctor', 'schedule'])
            ->where('status', 1)
            ->whereDate('created_at', $today)
            ->orderBy('queue_number')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
    
    public function getTodayByDoctor($doctorId)
    {
        $today = Carbon::today()->toDateString();
        
        $appointments = Appointment::with(['user', 'doctor', 'schedule'])
            ->where('doctor_id', $doctorId)
            ->where('status', 1)
            ->whereDate('created_at', $today)
            ->orderBy('queue_number')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
    
    public function getTodayStatistics()
    {
        $today = Carbon::today()->toDateString();
        
        $totalToday = Appointment::where('status', 1)
            ->whereDate('created_at', $today)
            ->count();
            
        $approvedCount = Appointment::where('status', 1)
            ->where('appstatus', 'approved')
            ->whereDate('created_at', $today)
            ->count();
            
        $pendingCount = Appointment::where('status', 1)
            ->where('appstatus', 'pending')
            ->whereDate('created_at', $today)
            ->count();
            
        $completedCount = Appointment::where('status', 1)
            ->where('appstatus', 'completed')
            ->whereDate('created_at', $today)
            ->count();
            
        return response()->json([
            'success' => true,
            'data' => [
                'total_today' => $totalToday,
                'approved_count' => $approvedCount,
                'pending_count' => $pendingCount,
                'completed_count' => $completedCount
            ]
        ]);
    }
}
