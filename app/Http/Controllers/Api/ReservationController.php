<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Requests\StoreReservationRequest;

class ReservationController extends Controller
{
    // POST /api/reservations
    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();

        // Basic availability check (using DB column names mapped by StoreReservationRequest)
        $overlap = Reservation::where('id_chambre', $data['id_chambre'] ?? $data['room_id'] ?? null)
            ->where(function ($q) use ($data) {
                $q->where('date_debut', '<', $data['date_fin'] ?? $data['checkout_date'])
                  ->where('date_fin', '>', $data['date_debut'] ?? $data['checkin_date']);
            })
            ->exists();

        if ($overlap) {
            return response()->json(['message' => 'La chambre n\'est pas disponible pour ces dates.'], 409);
        }

        $reservation = Reservation::create([
            'id_client' => $data['id_client'] ?? $data['client_id'] ?? null,
            'id_chambre' => $data['id_chambre'] ?? $data['room_id'] ?? null,
            'date_debut' => $data['date_debut'] ?? $data['checkin_date'] ?? null,
            'date_fin' => $data['date_fin'] ?? $data['checkout_date'] ?? null,
            'demandes_speciales' => $data['notes'] ?? $data['notes'] ?? null,
            'statut' => 'en cours',
        ]);

        return response()->json($reservation->load('room','client'), 201);
    }
}
