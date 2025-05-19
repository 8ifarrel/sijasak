<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\guest\BerandaGuestController;

Route::get('/', [BerandaGuestController::class, 'index'])
    ->name('guest.beranda.index');


