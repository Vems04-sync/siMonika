<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AplikasiController;

Route::get('/', [AplikasiController::class, 'index']);
Route::resource('aplikasi', AplikasiController::class);

