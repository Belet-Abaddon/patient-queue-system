<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\QueueHistory;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        // Validate data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'doctor_id' => 'required|exists:doctors,id',
            'queue_number' => 'nullable|integer',
            'wait_status' => 'nullable|in:waiting,serving,done',
        ]);

        // Check if doctor is active
        $doctor = Doctor::findOrFail($validated['doctor_id']);
        if ($doctor->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor is not available'
            ], 400);
        }

        // Generate queue number if not provided
        if (!isset($validated['queue_number'])) {
            $lastQueue = Patient::where('doctor_id', $validated['doctor_id'])
                ->whereDate('created_at', today())
                ->max('queue_number');
                
            $validated['queue_number'] = $lastQueue ? $lastQueue + 1 : 1;
        }

        // Set default values
        if (!isset($validated['wait_status'])) {
            $validated['wait_status'] = 'waiting';
        }
        $validated['status'] = 1;

        // Create patient
        $patient = Patient::create($validated);

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Patient added to queue successfully',
                'data' => $patient
            ], 201);
        }

        // For web request
        return redirect()->back()->with('success', 'Patient added to queue successfully');
    }
    public function update(Request $request)
    {
        // Get patient ID from request
        $id = $request->input('id');
        $patient = Patient::findOrFail($id);

        // Validate data
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'queue_number' => 'nullable|integer',
            'wait_status' => 'nullable|in:waiting,serving,done',
            'status' => 'nullable|in:0,1',
        ]);

        // Update patient
        $patient->update($validated);

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Patient updated successfully',
                'data' => $patient
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Patient updated successfully');
    }
    public function destroy(Request $request)
    {
        // Get patient ID from request
        $id = $request->input('id');
        $patient = Patient::findOrFail($id);
        
        // Update status to 0
        $patient->status = 0;
        $patient->save();

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Patient removed successfully'
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Patient removed successfully');
    }
    public function changeStatus(Request $request)
    {
        // Get patient ID from request
        $id = $request->input('id');
        $patient = Patient::findOrFail($id);
        
        // Get old status before change
        $oldStatus = $patient->wait_status;

        // Validate status
        $validated = $request->validate([
            'wait_status' => 'required|in:waiting,serving,done'
        ]);

        // Update patient status
        $patient->wait_status = $validated['wait_status'];
        $patient->save();

        // ================== AUTO CREATE QUEUE HISTORY ==================
        // When status changes to "serving" or "done", create queue history
        if (($oldStatus != 'serving' && $validated['wait_status'] == 'serving') ||
            ($oldStatus != 'done' && $validated['wait_status'] == 'done')) {
            
            QueueHistory::create([
                'patient_id' => $patient->id,
                'doctor_id' => $patient->doctor_id,
                'call_time' => now(),
                'status' => 1
            ]);
        }
        // ================== END AUTO CREATE ==================

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Patient status changed to ' . $validated['wait_status'],
                'data' => $patient,
                'queue_history_created' => true
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Patient status changed');
    }

    /**
     * Call next patient - UPDATED VERSION with QueueHistory
     */
    public function callNextPatient(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        
        // Find next waiting patient
        $nextPatient = Patient::where('doctor_id', $doctorId)
            ->where('status', 1)
            ->where('wait_status', 'waiting')
            ->whereDate('created_at', today())
            ->orderBy('queue_number')
            ->first();
            
        if (!$nextPatient) {
            // Check if it's an API request (Postman)
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No more waiting patients'
                ], 404);
            }

            // For web request
            return redirect()->back()->with('error', 'No more waiting patients');
        }
        
        // Update patient status to serving
        $nextPatient->wait_status = 'serving';
        $nextPatient->save();

        // ================== CREATE QUEUE HISTORY ==================
        QueueHistory::create([
            'patient_id' => $nextPatient->id,
            'doctor_id' => $nextPatient->doctor_id,
            'call_time' => now(),
            'status' => 1
        ]);
        // ================== END CREATE ==================

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Patient #' . $nextPatient->queue_number . ' called',
                'data' => $nextPatient,
                'queue_history_created' => true
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Patient #' . $nextPatient->queue_number . ' called');
    }

    /**
     * Complete current patient - UPDATED VERSION with QueueHistory
     */
    public function completePatient(Request $request)
    {
        $patientId = $request->input('patient_id');
        $patient = Patient::findOrFail($patientId);
        
        // Update patient status to done
        $patient->wait_status = 'done';
        $patient->save();

        // ================== CREATE QUEUE HISTORY ==================
        QueueHistory::create([
            'patient_id' => $patient->id,
            'doctor_id' => $patient->doctor_id,
            'call_time' => now(),
            'status' => 1
        ]);
        // ================== END CREATE ==================

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Patient consultation completed',
                'data' => $patient,
                'queue_history_created' => true
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Patient consultation completed');
    }
    public function getByDoctor($doctorId)
    {
        $patients = Patient::where('doctor_id', $doctorId)
            ->where('status', 1)
            ->orderBy('queue_number')
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        }

        // For web request
        return view('patients.doctor', compact('patients'));
    }
    public function getWaitingPatients($doctorId)
    {
        $patients = Patient::where('doctor_id', $doctorId)
            ->where('status', 1)
            ->where('wait_status', 'waiting')
            ->whereDate('created_at', today())
            ->orderBy('queue_number')
            ->get();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        }

        // For web request
        return view('patients.waiting', compact('patients'));
    }
    public function getServingPatient($doctorId)
    {
        $patient = Patient::where('doctor_id', $doctorId)
            ->where('status', 1)
            ->where('wait_status', 'serving')
            ->whereDate('created_at', today())
            ->first();
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $patient
            ]);
        }

        // For web request
        return view('patients.serving', compact('patient'));
    }
    
    public function getQueuePosition($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        
        $position = Patient::where('doctor_id', $patient->doctor_id)
            ->where('status', 1)
            ->where('wait_status', 'waiting')
            ->whereDate('created_at', today())
            ->where('queue_number', '<', $patient->queue_number)
            ->count();
            
        $position += 1; // Add 1 because count starts from 0
            
        // Check if it's an API request (Postman)
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'patient' => $patient,
                'queue_position' => $position,
                'message' => 'You are number ' . $position . ' in the queue'
            ]);
        }

        // For web request
        return $position;
    }
}
