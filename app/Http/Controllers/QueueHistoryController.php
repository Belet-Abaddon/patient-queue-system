<?php

namespace App\Http\Controllers;

use App\Models\QueueHistory;
use Illuminate\Http\Request;

class QueueHistoryController extends Controller
{
        public function store(Request $request)
    {
        // This function can be called manually, but usually called automatically
        
        // Validate data
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'call_time' => 'required|date',
            'status' => 'nullable|in:0,1',
        ]);

        // Set default values
        if (!isset($validated['status'])) {
            $validated['status'] = 1;
        }

        // Create queue history
        $queueHistory = QueueHistory::create($validated);

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Queue history recorded successfully',
                'data' => $queueHistory
            ], 201);
        }

        // For web request
        return redirect()->back()->with('success', 'Queue history recorded successfully');
    }

    /**
     * Update a queue history record
     */
    public function update(Request $request)
    {
        // Get queue history ID from request
        $id = $request->input('id');
        $queueHistory = QueueHistory::findOrFail($id);

        // Validate data
        $validated = $request->validate([
            'patient_id' => 'sometimes|required|exists:patients,id',
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'call_time' => 'sometimes|required|date',
            'status' => 'nullable|in:0,1',
        ]);

        // Update queue history
        $queueHistory->update($validated);

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Queue history updated successfully',
                'data' => $queueHistory
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Queue history updated successfully');
    }

    /**
     * Delete a queue history record (soft delete - status to 0)
     */
    public function destroy(Request $request)
    {
        // Get queue history ID from request
        $id = $request->input('id');
        $queueHistory = QueueHistory::findOrFail($id);
        
        // Update status to 0
        $queueHistory->status = 0;
        $queueHistory->save();

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Queue history deleted successfully'
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Queue history deleted successfully');
    }

    /**
     * Get queue history by patient ID
     */
    public function getByPatient($patientId)
    {
        $histories = QueueHistory::with(['patient', 'doctor'])
            ->where('patient_id', $patientId)
            ->where('status', 1)
            ->orderBy('call_time', 'desc')
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        }

        // For web request
        return view('queue-history.patient', compact('histories'));
    }

    /**
     * Get queue history by doctor ID
     */
    public function getByDoctor($doctorId)
    {
        $histories = QueueHistory::with(['patient', 'doctor'])
            ->where('doctor_id', $doctorId)
            ->where('status', 1)
            ->orderBy('call_time', 'desc')
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        }

        // For web request
        return view('queue-history.doctor', compact('histories'));
    }

    /**
     * Get today's queue history for a doctor
     */
    public function getTodayHistory($doctorId)
    {
        $histories = QueueHistory::with('patient')
            ->where('doctor_id', $doctorId)
            ->where('status', 1)
            ->whereDate('call_time', today())
            ->orderBy('call_time', 'desc')
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $histories
            ]);
        }

        // For web request
        return view('queue-history.today', compact('histories'));
    }

    /**
     * Get queue history summary (for reporting)
     */
    public function getSummary(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $date = $request->input('date', today());
        
        $query = QueueHistory::query();
        
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }
        
        if ($date) {
            $query->whereDate('call_time', $date);
        }
        
        $histories = $query->with(['patient', 'doctor'])
            ->where('status', 1)
            ->orderBy('call_time', 'desc')
            ->get();
            
        $summary = [
            'total_calls' => $histories->count(),
            'by_doctor' => $histories->groupBy('doctor_id')->map(function($items) {
                return $items->count();
            }),
            'by_hour' => $histories->groupBy(function($item) {
                return $item->call_time->format('H:00');
            })->map(function($items) {
                return $items->count();
            })
        ];
            
        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'summary' => $summary,
                'data' => $histories
            ]);
        }

        // For web request
        return view('queue-history.summary', compact('histories', 'summary'));
    }
}
