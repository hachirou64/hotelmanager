<?php

namespace App\Http\Controllers;

use App\Models\HotelParameter;
use Illuminate\Http\Request;

class HotelParameterController extends Controller
{
    public function index()
    {
        return HotelParameter::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'valeur' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return HotelParameter::create($request->all());
    }

    public function show(HotelParameter $hotelParameter)
    {
        return $hotelParameter;
    }

    public function update(Request $request, HotelParameter $hotelParameter)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'valeur' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $hotelParameter->update($request->all());
        return $hotelParameter;
    }

    public function destroy(HotelParameter $hotelParameter)
    {
        $hotelParameter->delete();
        return response()->noContent();
    }
}
