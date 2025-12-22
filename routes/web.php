<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;

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

Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

require __DIR__.'/auth.php';
