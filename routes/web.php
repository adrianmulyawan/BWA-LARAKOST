<?php

use App\Http\Controllers\BoardingHouseController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/check-booking', [BookingController::class, 'check'])
    ->name('check-booking');

Route::get('/find-boarding-house', [BoardingHouseController::class, 'find'])
    ->name('boarding-house.find');
Route::get('/result-boarding-house', [BoardingHouseController::class, 'findResult'])
    ->name('boarding-house.result');

Route::get('/categories/{slug:name}', [CategoryController::class, 'show'])
    ->name('category.show');
