<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Doctor::query();

            // Search by name if provided
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('specialization', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter by specialization if provided
            if ($request->has('specialization') && $request->specialization) {
                $query->where('specialization', 'like', '%' . $request->specialization . '%');
            }

            // Filter by status if provided
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            } else {
                // Default to active doctors
                $query->where('status', 'active');
            }

            // Limit for recent schedules (optional)
            if ($request->has('limit') && $request->limit) {
                $query->limit($request->limit);
            }

            $doctors = $query->with('schedules')->get();

            return response()->json([
                'success' => true,
                'data' => $doctors,
                'count' => $doctors->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching doctors: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch doctors',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'specialization' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors,email',
                'phone' => 'required|string|unique:doctors,phone',
                'license' => 'required|string|unique:doctors,license',
                'room' => 'nullable|string|max:10',
                'bio' => 'nullable|string',
                'status' => 'required|in:active,inactive,on_leave,retired',
                'degree' => 'nullable|string|max:255'
            ]);

            $doctor = Doctor::create([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'email' => $request->email,
                'phone' => $request->phone,
                'license' => $request->license,
                'room' => $request->room,
                'bio' => $request->bio,
                'status' => $request->status,
                'degree' => $request->degree,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Doctor created successfully',
                'data' => $doctor
            ], 201);
        } catch (\Exception $e) {
            Log::error('Doctor creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $doctor = Doctor::with('schedules')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $doctor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $doctor = Doctor::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'specialization' => 'required|string|max:255',
                'email' => 'required|email|unique:doctors,email,' . $id,
                'phone' => 'required|string|unique:doctors,phone,' . $id,
                'license' => 'required|string|unique:doctors,license,' . $id,
                'room' => 'nullable|string|max:10',
                'bio' => 'nullable|string',
                'status' => 'required|in:active,inactive,on_leave,retired',
                'degree' => 'nullable|string|max:255'
            ]);

            $doctor->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Doctor updated successfully',
                'data' => $doctor
            ]);
        } catch (\Exception $e) {
            Log::error('Doctor update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);

            // Check if doctor has active schedules
            $hasActiveSchedules = $doctor->schedules()
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($hasActiveSchedules) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete doctor with active schedules. Cancel or reassign schedules first.'
                ], 400);
            }

            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Doctor deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Doctor deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getTodayQueues()
    {
        $today = Carbon::today()->toDateString();
        
        $doctors = Doctor::with(['appointments' => function($query) use ($today) {
            $query->where('status', 1)
                ->whereDate('created_at', $today)
                ->orderBy('queue_number');
        }, 'appointments.user', 'appointments.schedule'])
            ->where('status', 1)
            ->get()
            ->map(function($doctor) use ($today) {
                // Add today's appointments separately for easier access
                $doctor->appointments_today = $doctor->appointments->where('created_at', '>=', $today);
                return $doctor;
            });
            
        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }
}
