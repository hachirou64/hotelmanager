<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomViewController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->get();
        return view('rooms', compact('rooms'));
    }

    public function show(Room $room)
    {
        $room->load('roomType');
        return view('room', compact('room'));
    }

    /**
     * Return a partial view suitable for loading in a modal (AJAX).
     */
    public function modal(Room $room)
    {
        $room->load('roomType');
        return view('partials.room-modal-content', compact('room'));
    }

    public function publicIndex(Request $request)
    {
        $date_debut = $request->query('date_debut');
        $date_fin = $request->query('date_fin');

        $query = Room::with('roomType');

        if ($date_debut && $date_fin) {
            $query->whereDoesntHave('reservations', function($q) use ($date_debut, $date_fin) {
                $q->where('statut', '!=', 'annulée')
                  ->where(function ($query) use ($date_debut, $date_fin) {
                      $query->where('date_debut', '<', $date_fin)
                            ->where('date_fin', '>', $date_debut);
                  });
            });
        }

        $rooms = $query->get();
        return view('public-rooms', compact('rooms', 'date_debut', 'date_fin'));
    }
}
