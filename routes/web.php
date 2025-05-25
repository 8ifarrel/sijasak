<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\guest\BerandaGuestController;
use App\Http\Controllers\auth\LoginAuthController;

Route::get('/', [BerandaGuestController::class, 'index'])
    ->name('guest.beranda.index');

Route::get('/api/jalan-rusak', [BerandaGuestController::class, 'jalanRusakApi']);

// Login Admin
Route::get('/login', [LoginAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginAuthController::class, 'logout'])->name('logout');


