<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ClientReservationController extends Controller
{
    /**
     * Display a listing of reservations for the authenticated client.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Try to find related client record; otherwise return empty collection
        $client = $user->client ?? null;

        if ($client) {
            $reservations = Reservation::where('id_client', $client->id_client)->with('room')->orderBy('date_debut', 'desc')->get();
        } else {
            $reservations = collect();
        }

        return view('client-reservations', compact('reservations'));
    }
}
