<?php

use App\Http\Controllers\BoardingHouseController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/check-booking', [BookingController::class, 'check'])
    ->name('check-booking');

Route::get('/find-boarding-house', [BoardingHouseController::class, 'find'])
    ->name('find-boarding-house');
