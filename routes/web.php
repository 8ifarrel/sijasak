<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\guest\BerandaGuestController;
use App\Http\Controllers\auth\LoginAuthController;
use App\Http\Controllers\admin\DashboardAdminController;
use App\Http\Controllers\admin\JalanRusakAdminController;
use App\Http\Controllers\Api\JalanRusakAPIController;

/**
 * API Jalan Rusak
 */
Route::get('/api/jalan-rusak', [JalanRusakAPIController::class, 'index'])
	->name('api.jalan-rusak');

/**
 * Beranda
 */
Route::get('/', [BerandaGuestController::class, 'index'])
	->name('guest.beranda.index');

/**
 * Authentication
 */
Route::name('auth.')->group(function () {
	Route::get('/login', [LoginAuthController::class, 'index'])
		->name('login.index');
	Route::post('/login', [LoginAuthController::class, 'login'])
		->name('login.submit');
	Route::post('/logout', [LoginAuthController::class, 'logout'])
		->name('logout');
});

Route::middleware('auth')->group(function () {
	Route::prefix('admin')->name('admin.')->group(function () {
		/**
		 * Dashboard
		 */
		Route::get('/dashboard', [DashboardAdminController::class, 'index'])
			->name('dashboard.index');

		/**
		 * Jalan Rusak
		 */
		Route::prefix('jalan-rusak')->name('jalan-rusak.')->group(function () {
			Route::get('/', [JalanRusakAdminController::class, 'index'])
				->name('index');
			Route::get('/create', [JalanRusakAdminController::class, 'create'])
				->name('create');
			Route::post('/', [JalanRusakAdminController::class, 'store'])
				->name('store');
			Route::get('/{id}/edit', [JalanRusakAdminController::class, 'edit'])
				->name('edit');
			Route::put('/{id}', [JalanRusakAdminController::class, 'update'])
				->name('update');
		});
	});
});
