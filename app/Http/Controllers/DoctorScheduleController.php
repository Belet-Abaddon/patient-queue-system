<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DoctorScheduleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = DoctorSchedule::with('doctor');

            // Filter by doctor_id
            if ($request->has('doctor_id') && $request->doctor_id) {
                $query->where('doctor_id', $request->doctor_id);
            }

            // Filter by day
            if ($request->has('day') && $request->day) {
                $query->where('day', $request->day);
            }

            // Filter by shift type
            if ($request->has('shift_type') && $request->shift_type) {
                $query->where('shift_type', $request->shift_type);
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', '!=', 'cancelled');
            }

            $schedules = $query->orderByRaw(
                "FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')"
            )
                ->orderBy('start_time', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching schedules: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch schedules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'shift_type' => 'required|in:morning,afternoon,evening,night,on_call,full_day',
                'notes' => 'nullable|string',
                'status' => 'required|in:scheduled,confirmed,cancelled'
            ]);

            // Check for schedule conflicts by day
            $conflict = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
                ->where('day', $validated['day'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule conflict detected. Doctor already has a schedule for this day and time slot.'
                ], 409);
            }

            $schedule = DoctorSchedule::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully',
                'data' => $schedule
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Schedule creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $schedule = DoctorSchedule::with('doctor')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $schedule = DoctorSchedule::findOrFail($id);

            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'shift_type' => 'required|in:morning,afternoon,evening,night,on_call,full_day',
                'notes' => 'nullable|string',
                'status' => 'required|in:scheduled,confirmed,cancelled'
            ]);

            // Check for schedule conflicts (excluding current schedule)
            $conflict = DoctorSchedule::where('doctor_id', $validated['doctor_id'])
                ->where('day', $validated['day'])
                ->where('id', '!=', $id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule conflict detected. Doctor already has another schedule for this day and time slot.'
                ], 409);
            }

            $schedule->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'data' => $schedule
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Schedule update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = DoctorSchedule::findOrFail($id);

            // Instead of deleting, mark as cancelled
            $schedule->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Schedule deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByDoctor($doctorId, Request $request)
    {
        try {
            $query = DoctorSchedule::where('doctor_id', $doctorId);

            // Filter by day if provided
            if ($request->has('day') && $request->day) {
                $query->where('day', $request->day);
            }

            $schedules = $query->where('status', '!=', 'cancelled')
                ->orderByRaw(
                    "FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')"
                )
                ->orderBy('start_time', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching doctor schedules: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch schedules',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
