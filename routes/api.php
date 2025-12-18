<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\QueueHistoryController;

Route::prefix('doctors')->group(function () {
    Route::post('/store', [DoctorController::class, 'store']);
    Route::post('/update', [DoctorController::class, 'update']);
    Route::post('/delete/{id}', [DoctorController::class, 'destroy']);

});
Route::prefix('doctor-schedules')->group(function () {
    Route::post('/store', [DoctorScheduleController::class, 'store']);
    Route::post('/update', [DoctorScheduleController::class, 'update']);
    Route::post('/delete/{id}', [DoctorScheduleController::class, 'destroy']);
    Route::get('/index', [DoctorScheduleController::class, 'index']);
    Route::get('/doctor/{doctorId}', [DoctorScheduleController::class, 'getByDoctor']);
});
Route::prefix('appointments')->group(function () {
    Route::post('/store', [AppointmentController::class, 'store']);
    Route::post('/update', [AppointmentController::class, 'update']);
    Route::post('/delete', [AppointmentController::class, 'destroy']);
    Route::post('/change-status', [AppointmentController::class, 'changeStatus']);
    Route::get('/user/{userId}', [AppointmentController::class, 'getByUser']);
    Route::get('/doctor/{doctorId}', [AppointmentController::class, 'getByDoctor']);
    Route::get('/today/{doctorId}', [AppointmentController::class, 'getTodayAppointments']);
    Route::get('/next-queue/{doctorId}', [AppointmentController::class, 'getNextQueue']);
});
// Patient Routes
Route::prefix('patients')->group(function () {
    Route::post('/store', [PatientController::class, 'store']);
    Route::post('/update', [PatientController::class, 'update']);
    Route::post('/delete', [PatientController::class, 'destroy']);
    Route::post('/change-status', [PatientController::class, 'changeStatus']);
    Route::post('/call-next', [PatientController::class, 'callNextPatient']);
    Route::post('/complete', [PatientController::class, 'completePatient']);
    Route::get('/doctor/{doctorId}', [PatientController::class, 'getByDoctor']);
    Route::get('/waiting/{doctorId}', [PatientController::class, 'getWaitingPatients']);
    Route::get('/serving/{doctorId}', [PatientController::class, 'getServingPatient']);
    Route::get('/queue-position/{patientId}', [PatientController::class, 'getQueuePosition']);
});
// Queue History Routes
Route::prefix('queue-histories')->group(function () {
    Route::post('/store', [QueueHistoryController::class, 'store']);
    Route::post('/update', [QueueHistoryController::class, 'update']);
    Route::post('/delete', [QueueHistoryController::class, 'destroy']);
    Route::get('/patient/{patientId}', [QueueHistoryController::class, 'getByPatient']);
    Route::get('/doctor/{doctorId}', [QueueHistoryController::class, 'getByDoctor']);
    Route::get('/today/{doctorId}', [QueueHistoryController::class, 'getTodayHistory']);
    Route::get('/summary', [QueueHistoryController::class, 'getSummary']);
});