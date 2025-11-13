<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return Reservation::with(['client', 'room.roomType'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_client' => 'required|exists:clients,id_client',
            'id_chambre' => 'required|exists:rooms,id_chambre',
            'date_debut' => 'required|date|after:today',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:confirmée,en cours,annulée',
            'demandes_speciales' => 'nullable|string',
        ]);

        return Reservation::create($request->all());
    }

    public function show(Reservation $reservation)
    {
        return $reservation->load(['client', 'room.roomType']);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'id_client' => 'required|exists:clients,id_client',
            'id_chambre' => 'required|exists:rooms,id_chambre',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:confirmée,en cours,annulée',
            'demandes_speciales' => 'nullable|string',
        ]);

        $reservation->update($request->all());
        return $reservation;
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->noContent();
    }
}
