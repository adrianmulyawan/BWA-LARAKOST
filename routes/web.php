<?php

use App\Http\Controllers\BoardingHouseController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/check-booking', [BookingController::class, 'check'])
    ->name('check-booking');

Route::get('boarding-house/find', [BoardingHouseController::class, 'find'])
    ->name('boarding-house.find');
Route::get('boarding-house/result', [BoardingHouseController::class, 'findResult'])
    ->name('boarding-house.result');
Route::get('/boarding-house/{slug:name}', [BoardingHouseController::class, 'show'])
    ->name('boarding-house.show');
Route::get('/boarding-house/{slug}/rooms', [BoardingHouseController::class, 'rooms'])
    ->name('boarding-house.rooms');

Route::get('/boarding-house/booking/{slug:name}', [BookingController::class, 'booking'])
    ->name('booking');
Route::get('/boarding-house/booking/{slug:name}/information', [BookingController::class, 'information'])
    ->name('booking.information');
Route::post('/boarding-house/booking/{slug:name}/information/save', [BookingController::class, 'saveInformation'])
    ->name('booking.information.save');
Route::get('/boarding-house/booking/{slug:name}/checkout', [BookingController::class, 'checkout'])
    ->name('booking.checkout');
Route::post('/boarding-house/booking/{slug:name}/payment', [BookingController::class, 'payment'])
    ->name('booking.payment');

Route::get('/booking-success', [BookingController::class, 'success'])
    ->name('booking.success');

Route::get('/categories/{slug:name}', [CategoryController::class, 'show'])
    ->name('category.show');

Route::get('/cities/{slug:name}', [CityController::class, 'show'])
    ->name('city.show');
