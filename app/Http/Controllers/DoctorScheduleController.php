<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = DoctorSchedule::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully',
            'data' => $schedule
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
        $schedule = DoctorSchedule::findOrFail($id);

        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule->update($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'data' => $schedule
            ]);
        }

        return redirect()->back()->with('success', 'Schedule updated successfully');
    }
    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->status = 0;
        $schedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully'
        ]);
    }
    public function getByDoctor($doctorId)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('status', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }
    public function index()
    {
        $schedules = DoctorSchedule::with('doctor')
            ->where('status', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
        // $schedules = DoctorSchedule::all();
        // return response()->json([
        //     'data' => $schedules
        // ]);
    }
}
