<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;

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