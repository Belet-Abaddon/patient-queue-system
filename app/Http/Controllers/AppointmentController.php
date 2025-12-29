<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments for admin.
     */
    public function index()
    {
        // Get all appointments with relationships
        $appointments = Appointment::with(['user', 'doctor', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('queue_number', 'asc')
            ->paginate(10);

        // Get all doctors for filter dropdown
        $doctors = Doctor::all();

        // Get today's statistics
        $today = now()->format('Y-m-d');
        $stats = [
            'total_today' => Appointment::whereDate('appointment_date', $today)->count(),
            'confirmed_count' => Appointment::whereDate('appointment_date', $today)
                ->where('appstatus', 'confirmed')->count(),
            'pending_count' => Appointment::whereDate('appointment_date', $today)
                ->where('appstatus', 'pending')->count(),
            'completed_count' => Appointment::whereDate('appointment_date', $today)
                ->where('appstatus', 'completed')->count(),
        ];

        // Get today's appointments for schedule sidebar
        $todayAppointments = Appointment::with(['user', 'doctor', 'schedule'])
            ->whereDate('appointment_date', $today)
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('admin.appointment', compact('appointments', 'doctors', 'stats', 'todayAppointments'));
    }

    /**
     * Get appointments for AJAX requests.
     */
    public function getAppointments(Request $request)
    {
        $query = Appointment::with(['user', 'doctor', 'schedule']);

        // Apply filters
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('appstatus', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('queue_number', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Get today's appointments.
     */
    public function getTodaysAppointments()
    {
        $today = now()->format('Y-m-d');
        $appointments = Appointment::with(['user', 'doctor', 'schedule'])
            ->whereDate('appointment_date', $today)
            ->orderBy('queue_number', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Get today's statistics.
     */
    public function getTodayStatistics()
    {
        $today = now()->format('Y-m-d');
        
        $stats = [
            'total_today' => Appointment::whereDate('appointment_date', $today)->count(),
            'confirmed_count' => Appointment::whereDate('appointment_date', $today)
                ->where('appstatus', 'confirmed')->count(),
            'pending_count' => Appointment::whereDate('appointment_date', $today)
                ->where('appstatus', 'pending')->count(),
            'completed_count' => Appointment::whereDate('appointment_date', $today)
                ->where('appstatus', 'completed')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Change appointment status.
     */
    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:appointments,id',
            'appstatus' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        try {
            $appointment = Appointment::findOrFail($request->id);
            $appointment->appstatus = $request->appstatus;
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an appointment.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:appointments,id'
        ]);

        try {
            $appointment = Appointment::findOrFail($request->id);
            $appointment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date',
        ]);

        try {
            // Get the next queue number for this doctor on this date
            $lastQueue = Appointment::where('doctor_id', $request->doctor_id)
                ->whereDate('appointment_date', $request->appointment_date)
                ->max('queue_number');

            $queueNumber = ($lastQueue ?? 0) + 1;

            $appointment = Appointment::create([
                'user_id' => $request->user_id,
                'doctor_id' => $request->doctor_id,
                'schedule_id' => $request->schedule_id,
                'appointment_date' => $request->appointment_date,
                'queue_number' => $queueNumber,
                'appstatus' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}