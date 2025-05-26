<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\guest\BerandaGuestController;
use App\Http\Controllers\auth\LoginAuthController;
use App\Http\Controllers\admin\DashboardAdminController;
use App\Http\Controllers\admin\JalanRusakAdminController;
use App\Http\Controllers\Api\JalanRusakAPIController;

Route::get('/', [BerandaGuestController::class, 'index'])
    ->name('guest.beranda.index');

Route::get('/api/jalan-rusak', [JalanRusakAPIController::class, 'index'])
    ->name('api.jalan-rusak');

Route::get('/login', [LoginAuthController::class, 'index'])
    ->name('auth.login.index');
Route::post('/login', [LoginAuthController::class, 'login'])
    ->name('auth.login.submit');
Route::post('/logout', [LoginAuthController::class, 'logout'])
    ->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])
        ->name('admin.dashboard.index');

    Route::get('/admin/jalan-rusak', [JalanRusakAdminController::class, 'index'])
        ->name('admin.jalan-rusak.index');
    Route::get('/admin/jalan-rusak/create', [JalanRusakAdminController::class, 'create'])
        ->name('admin.jalan-rusak.create');
    Route::post('/admin/jalan-rusak', [JalanRusakAdminController::class, 'store'])
        ->name('admin.jalan-rusak.store');
    Route::get('/admin/jalan-rusak/{id}/edit', [JalanRusakAdminController::class, 'edit'])
        ->name('admin.jalan-rusak.edit');
    Route::put('/admin/jalan-rusak/{id}', [JalanRusakAdminController::class, 'update'])
        ->name('admin.jalan-rusak.update');
});
