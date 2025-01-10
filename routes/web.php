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
    Route::get('/aplikasi/export', [AplikasiController::class, 'export'])->name('aplikasi.export');
    // Route::resource('aplikasi', AplikasiController::class);  // Comment atau hapus ini untuk sementara
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Definisikan route secara manual
    Route::get('/aplikasi', [AplikasiController::class, 'index'])->name('aplikasi.index');
    Route::get('/aplikasi/create', [AplikasiController::class, 'create'])->name('aplikasi.create');
    Route::post('/aplikasi', [AplikasiController::class, 'store'])->name('aplikasi.store');
    Route::get('/aplikasi/edit/{nama}', [AplikasiController::class, 'editByNama'])->name('aplikasi.editByNama');
    Route::put('/aplikasi/{nama}', [AplikasiController::class, 'updateByNama'])->name('aplikasi.updateByNama');
    Route::delete('/aplikasi/delete/{nama}', [AplikasiController::class, 'destroyByNama'])->name('aplikasi.destroyByNama');

    Route::get('/chart-data', [AplikasiController::class, 'getChartData']);
});
