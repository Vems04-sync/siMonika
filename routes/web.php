<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\AuthController;

// Route untuk guest (belum login)
Route::middleware(['guest'])->group(function () {
    // Redirect root URL ke halaman login
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Route untuk login dan register
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AplikasiController::class, 'index'])->name('dashboard');
    Route::resource('aplikasi', AplikasiController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/aplikasi/{id}', [AplikasiController::class, 'show'])->name('aplikasi.show');
    Route::put('/aplikasi/{id}', [AplikasiController::class, 'update'])->name('aplikasi.update');
    Route::get('/chart-data', [AplikasiController::class, 'getChartData']);
});
