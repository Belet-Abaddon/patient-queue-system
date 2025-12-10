<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

Route::prefix('doctors')->group(function () {
    Route::post('/store', [DoctorController::class, 'store']);
    Route::post('/update', [DoctorController::class, 'update']);
    Route::post('/delete/{id}', [DoctorController::class, 'destroy']);
});
