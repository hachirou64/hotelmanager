
+<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ClientDashboardController;

Route::get('/', function () {
    // Données pour la page d'accueil de l'hôtel
    $services = [
        ['icon' => 'reception', 'title' => 'Réception 24/7', 'description' => 'Notre équipe est disponible à tout moment pour vous accueillir et répondre à vos besoins.'],
        ['icon' => 'restaurant', 'title' => 'Restaurant & Bar', 'description' => 'Découvrez notre cuisine locale et internationale dans un cadre chaleureux.'],
        ['icon' => 'spa', 'title' => 'Spa & Bien-être', 'description' => 'Détendez-vous avec nos soins spa, sauna et hammam.'],
        ['icon' => 'wifi', 'title' => 'WiFi Gratuit', 'description' => 'Connexion internet haute vitesse disponible dans tout l\'établissement.'],
        ['icon' => 'parking', 'title' => 'Parking Sécurisé', 'description' => 'Parking privé gratuit pour tous nos hôtes.'],
        ['icon' => 'roomervice', 'title' => 'Room Service', 'description' => 'Commandez depuis votre chambre, 24h/24.'],
    ];

    $testimonials = [
        ['name' => 'Marie Dupont', 'location' => 'Paris', 'text' => 'Séjour magnifique ! Chambre spacieuse, personnel très aimable et restaurant excellent. Je recommande !', 'rating' => 5],
        ['name' => 'Jean-Pierre Martin', 'location' => 'Lyon', 'text' => 'Excellent accueil, propreté irréprochable. Le spa est un vrai plus après une journée de travail.', 'rating' => 5],
        ['name' => 'Sophie Bernard', 'location' => 'Bordeaux', 'text' => 'Parfait pour un weekend en couple. L\'emplacement est idéal et le petit-déjeuner est délicieux.', 'rating' => 4],
    ];

    // Chambres disponibles (limité à 6 pour la page d'accueil)
    $featuredRooms = \App\Models\Room::with('roomType')
        ->where('statut', 'libre')
        ->limit(6)
        ->get();

    return view('welcome', compact('services', 'testimonials', 'featuredRooms'));
});

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');


// Contact form (GET) - uses existing POST /contact for submission
Route::get('/contact', function () {
    return view('static.contact');
})->name('contact');


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

Route::get('/client-dashboard', [ClientDashboardController::class, 'index'])->middleware('auth')->name('client.dashboard');

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

// Invoice download
Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->middleware('auth')->name('invoices.download');

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

Route::get('/reports', [ReportsController::class, 'index'])->middleware('auth', 'role:Admin,Manager')->name('reports');
