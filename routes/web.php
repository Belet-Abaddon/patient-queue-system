<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('doctors')->group(function () {
    // Route::post('/store', [DoctorController::class, 'store']);
    Route::get('/index', [DoctorController::class, 'index']);
});

Route::get('/', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
Route::get('/admin/patients', function () {
    return view('admin.patient-management');
})->name('admin.patients');

// Public doctor management page (No auth required)
Route::get('/admin/doctors', function () {
    return view('admin.doctors');
})->name('doctors.management');

// Public API routes for AJAX calls (No auth required)
Route::prefix('api')->group(function () {
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('schedules', DoctorScheduleController::class);
    Route::get('schedules/weekly', [DoctorScheduleController::class, 'getWeeklySchedules']);
    Route::get('doctors/{id}/schedules', [DoctorScheduleController::class, 'getByDoctor']);
});

Route::get('/admin/appointment', function () {
    return view('admin.appointment');
})->name('admin.appointment');
Route::get('/admin/queue-history', function () {
    return view('admin.queue-history');
})->name('admin.queue-history');

require __DIR__ . '/auth.php';
