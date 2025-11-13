<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        return RoomType::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_base' => 'required|numeric|min:0',
        ]);

        return RoomType::create($request->all());
    }

    public function show(RoomType $roomType)
    {
        return $roomType;
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'nom_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_base' => 'required|numeric|min:0',
        ]);

        $roomType->update($request->all());
        return $roomType;
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return response()->noContent();
    }
}
