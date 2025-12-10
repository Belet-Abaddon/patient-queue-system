<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        return response()->json([
            'data' => $doctors
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'degree' => 'required',
            'specialist' => 'required',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|unique:doctors,phone',
        ]);

        $doctor = Doctor::create([
            'name' => $request->name,
            'degree' => $request->degree,
            'specialist' => $request->specialist,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'message' => 'Doctor created successfully',
            'data' => $doctor
        ]);
    }

    public function show(Doctor $doctor) {

    }

    public function update(Request $request)
    {
        // Get doctor ID from request
        $id = $request->input('id');
        $doctor = Doctor::findOrFail($id);

        // Validate data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialist' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $id,
        ]);

        // Update doctor
        $doctor->update($validated);

        // Check if it's an API request (Postman)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Doctor updated successfully',
                'data' => $doctor
            ]);
        }

        // For web request
        return redirect()->back()->with('success', 'Doctor updated successfully');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = 0;
        $doctor->save();

        return response()->json([
            'success' => true,
            'message' => 'Doctor deleted successfully'
        ]);
    }
}
