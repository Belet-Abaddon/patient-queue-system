<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class AppointmentController extends Controller
{
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
        ]);

        // Check if schedule is available
        $schedule = DoctorSchedule::findOrFail($validated['schedule_id']);
        if ($schedule->status == 0) {
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

        // Generate queue number if not provided
        if (!isset($validated['queue_number'])) {
            $lastQueue = Appointment::where('doctor_id', $validated['doctor_id'])
                ->whereDate('created_at', today())
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

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => $appointment
            ], 201);
        }

        // For web request
        return redirect()->back()->with('success', 'Appointment created successfully');
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
        ]);

        // Update appointment
        $appointment->update($validated);

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => $appointment
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Appointment updated successfully');
    }
    public function destroy(Request $request)
    {
        // Get appointment ID from request
        $id = $request->input('id');
        $appointment = Appointment::findOrFail($id);
        
        // Update status to 0
        $appointment->status = 0;
        $appointment->save();

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully'
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Appointment deleted successfully');
    }
    public function getByUser($userId)
    {
        $appointments = Appointment::with(['doctor', 'schedule'])
            ->where('user_id', $userId)
            ->where('status', 1)
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        // For web request
        return view('appointments.user', compact('appointments'));
    }
    public function getByDoctor($doctorId)
    {
        $appointments = Appointment::with(['user', 'schedule'])
            ->where('doctor_id', $doctorId)
            ->where('status', 1)
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        // For web request
        return view('appointments.doctor', compact('appointments'));
    }
    public function changeStatus(Request $request)
    {
        // Get appointment ID from request
        $id = $request->input('id');
        $appointment = Appointment::findOrFail($id);

        // Validate status
        $validated = $request->validate([
            'appstatus' => 'required|in:pending,approved,cancelled,completed'
        ]);

        // Update appointment status
        $appointment->appstatus = $validated['appstatus'];
        $appointment->save();

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment status changed to ' . $validated['appstatus'],
                'data' => $appointment
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Appointment status changed');
    }
    public function getTodayAppointments($doctorId)
    {
        $appointments = Appointment::with('user')
            ->where('doctor_id', $doctorId)
            ->where('status', 1)
            ->whereDate('created_at', today())
            ->orderBy('queue_number')
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        // For web request
        return view('appointments.today', compact('appointments'));
    }
    public function getNextQueue($doctorId)
    {
        $lastQueue = Appointment::where('doctor_id', $doctorId)
            ->whereDate('created_at', today())
            ->max('queue_number');
            
        $nextQueue = $lastQueue ? $lastQueue + 1 : 1;
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'next_queue' => $nextQueue
            ]);
        }

        // For web request
        return $nextQueue;
    }
}
