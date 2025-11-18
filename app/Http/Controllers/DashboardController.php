<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques principales
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('statut', 'occupée')->count();
        $todayReservations = Reservation::whereDate('date_debut', today()->toDateString())->count();
        $todayRevenue = Payment::whereDate('date_paiement', today()->toDateString())->sum('montant_paye') ?? 0;

        // Réservations récentes avec vérification des relations
        $recentReservations = Reservation::with(['client', 'room'])
            ->whereHas('client')
            ->whereHas('room')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'totalRooms',
            'occupiedRooms',
            'todayReservations',
            'todayRevenue',
            'recentReservations'
        ));
    }
}
