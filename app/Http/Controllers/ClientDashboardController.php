<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Invoice;
use App\Models\Payment;

class ClientDashboardController extends Controller
{
    /**
     * Display the client dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get the client profile associated with the user
        $client = $user->client ?? null;
        
        if (!$client) {
            return view('client-dashboard', [
                'client' => null,
                'totalStays' => 0,
                'upcomingStays' => 0,
                'totalSpent' => 0,
                'recentReservations' => collect(),
                'upcomingReservations' => collect(),
            ]);
        }
        
        // Total number of stays (past reservations)
        $totalStays = Reservation::where('id_client', $client->id_client)
            ->where('statut', 'confirmée')
            ->count();
        
        // Upcoming stays (reservations with date_debut >= today)
        $upcomingStays = Reservation::where('id_client', $client->id_client)
            ->where('date_debut', '>=', now()->toDateString())
            ->whereIn('statut', ['en attente', 'confirmée'])
            ->count();
        
        // Total amount spent (sum of paid invoices)
        $totalSpent = Invoice::where('id_client', $client->id_client)
            ->where('statut_paiement', 'payée')
            ->sum('montant_total') ?? 0;
        
        // Recent reservations (last 3)
        $recentReservations = Reservation::where('id_client', $client->id_client)
            ->with(['room'])
            ->latest()
            ->take(3)
            ->get();
        
        // Upcoming reservations (next 3)
        $upcomingReservations = Reservation::where('id_client', $client->id_client)
            ->where('date_debut', '>=', now()->toDateString())
            ->whereIn('statut', ['en attente', 'confirmée'])
            ->with(['room'])
            ->orderBy('date_debut')
            ->take(3)
            ->get();
        
        return view('client-dashboard', compact(
            'client',
            'totalStays',
            'upcomingStays',
            'totalSpent',
            'recentReservations',
            'upcomingReservations'
        ));
    }
}
