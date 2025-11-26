
+<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomViewController;
use App\Http\Controllers\ClientViewController;
use App\Http\Controllers\BillingViewController;
use App\Http\Controllers\ReservationAdminController;
use App\Http\Controllers\ClientReservationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PersonnelViewController;
use App\Http\Controllers\SettingsViewController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Admin routes for contact messages (protected by auth and admin/manager role)
Route::middleware(['auth', 'role:Admin,Manager'])->prefix('admin/contact-messages')->name('admin.contact_messages.')->group(function () {
    Route::get('/', [App\Http\Controllers\ContactMessageController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\ContactMessageController::class, 'show'])->name('show');
    Route::post('/{id}/reply', [App\Http\Controllers\ContactMessageController::class, 'reply'])->name('reply');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth', 'role:Admin,Manager,Staff')->name('dashboard');

Route::get('/reservations', [ReservationAdminController::class, 'index'])->middleware('auth', 'role:Admin,Manager,Staff')->name('reservations');

Route::get('/client-reservations', [ClientReservationController::class, 'index'])->middleware('auth')->name('client.reservations');

Route::get('/rooms', [RoomViewController::class, 'index'])->middleware('auth', 'role:Admin,Manager,Staff')->name('rooms');

Route::get('/rooms/{room}', [RoomViewController::class, 'show'])->name('rooms.show');
// Modal partial for room quick-view in admin reservation list
Route::get('/rooms/{room}/modal', [RoomViewController::class, 'modal'])->name('rooms.modal');

Route::get('/public-rooms', [RoomViewController::class, 'publicIndex'])->name('public.rooms');

Route::get('/book-room/{roomId}', [ReservationController::class, 'create'])->name('book.room');
Route::post('/book-room', [ReservationController::class, 'store'])->name('book-room.store');

// Payment routes (MOMO)
Route::post('/reservations/{reservation}/pay', [PaymentController::class, 'initiate'])->name('reservations.pay');
Route::get('/reservations/{reservation}/pay', [PaymentController::class, 'showPaymentForm'])->name('reservations.pay.form');
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');

Route::get('/clients', [ClientViewController::class, 'index'])->middleware('auth', 'role:Admin,Manager,Staff')->name('clients');

Route::get('/billing', [BillingViewController::class, 'index'])->name('billing');

Route::get('/personnel', [PersonnelViewController::class, 'index'])->middleware('auth', 'role:Admin,Manager,Staff')->name('personnel');

Route::get('/settings', [SettingsViewController::class, 'index'])->middleware('auth', 'role:Admin,Manager,Staff')->name('settings');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::get('/user-settings', function () {
    return view('user-settings');
})->middleware('auth')->name('user-settings');

Route::post('/user-settings', [UserSettingsController::class, 'update'])->middleware('auth')->name('user-settings.update');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
