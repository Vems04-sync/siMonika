<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AtributController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;

// Route untuk guest (belum login)
Route::middleware(['guest', 'throttle:6,1'])->group(function () {
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/aplikasi/export', [AplikasiController::class, 'export'])->name('aplikasi.export');
    // Route::resource('aplikasi', AplikasiController::class);  // Comment atau hapus ini untuk sementara
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Definisikan route secara manual
    Route::get('/aplikasi', [AplikasiController::class, 'index'])->name('aplikasi.index');
    Route::get('/aplikasi/create', [AplikasiController::class, 'create'])->name('aplikasi.create');
    Route::post('/aplikasi', [AplikasiController::class, 'store'])->name('aplikasi.store');
    Route::get('/aplikasi/edit/{nama}', [AplikasiController::class, 'editByNama'])->name('aplikasi.editByNama');
    Route::put('/aplikasi/{nama}', [AplikasiController::class, 'update'])->name('aplikasi.update');
    Route::put('/aplikasi/{nama}', [AplikasiController::class, 'update'])->name('aplikasi.update');
    Route::delete('/aplikasi/delete/{nama}', [AplikasiController::class, 'destroyByNama'])->name('aplikasi.destroyByNama');

    Route::get('/chart-data', [AplikasiController::class, 'getChartData']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // route untuk detail aplikasi
    Route::get('/aplikasi/detail/{nama}', [AplikasiController::class, 'detail'])->name('aplikasi.detail');

    Route::get('/last-update', [DashboardController::class, 'getLastUpdate'])->name('last.update');

    // Route untuk Super Admin (tanpa middleware di route)
    Route::get('/super-admin/dashboard', [SuperAdminDashboard::class, 'index'])
         ->name('super-admin.dashboard');
});

// Route untuk admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// Route untuk login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::resource('atribut', AtributController::class);

Route::get('/super-admin/log/export', [App\Http\Controllers\SuperAdmin\LogAktivitasController::class, 'export'])
    ->name('super-admin.log.export')
    ->middleware('auth')
    ->middleware(\App\Http\Middleware\CheckRole::class . ':super_admin');

// Tambahkan route forgot password dari branch baru
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

    