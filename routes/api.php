<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Api\ReservationController as ApiReservationController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\HotelParameterController;
use App\Http\Controllers\ClientReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes for Hotel Management System
Route::get('/public/rooms/{id}', [RoomController::class, 'show']);
Route::post('/client/reservations', [ClientReservationController::class, 'store'])->middleware('auth');

Route::resource('roles', RoleController::class);
Route::resource('room-types', RoomTypeController::class);
Route::resource('rooms', RoomController::class);
Route::resource('clients', ClientController::class);
Route::resource('reservations', ApiReservationController::class);
Route::resource('personnel', PersonnelController::class);

Route::resource('invoices', InvoiceController::class);
Route::resource('payments', PaymentController::class);
Route::resource('promotions', PromotionController::class);
Route::resource('hotel-parameters', HotelParameterController::class);
