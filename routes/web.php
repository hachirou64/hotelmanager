<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomViewController;
use App\Http\Controllers\ClientViewController;
use App\Http\Controllers\BillingViewController;
use App\Http\Controllers\PersonnelViewController;
use App\Http\Controllers\SettingsViewController;
use App\Http\Controllers\UserSettingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:Admin')->name('dashboard');

Route::get('/reservations', function () {
    return view('reservations');
})->name('reservations');

Route::get('/rooms', [RoomViewController::class, 'index'])->name('rooms');

Route::get('/clients', [ClientViewController::class, 'index'])->name('clients');

Route::get('/billing', [BillingViewController::class, 'index'])->name('billing');

Route::get('/personnel', [PersonnelViewController::class, 'index'])->name('personnel');

Route::get('/settings', [SettingsViewController::class, 'index'])->name('settings');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/user-settings', function () {
    return view('user-settings');
})->name('user-settings');

Route::post('/user-settings', [UserSettingsController::class, 'update'])->name('user-settings.update');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
