<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::with('roomType')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_chambre' => 'required|string|max:255|unique:rooms',
            'type_chambre' => 'required|exists:room_types,id_type',
            'statut' => 'required|in:libre,occupée,nettoyage,maintenance',
            'capacite_max' => 'required|integer|min:1',
        ]);

        return Room::create($request->all());
    }

    public function show(Room $room)
    {
        return $room->load('roomType');
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'numero_chambre' => 'required|string|max:255|unique:rooms,numero_chambre,' . $room->id_chambre . ',id_chambre',
            'type_chambre' => 'required|exists:room_types,id_type',
            'statut' => 'required|in:libre,occupée,nettoyage,maintenance',
            'capacite_max' => 'required|integer|min:1',
        ]);

        $room->update($request->all());
        return $room;
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->noContent();
    }
}
