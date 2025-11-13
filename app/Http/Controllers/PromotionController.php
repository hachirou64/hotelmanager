<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return Promotion::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'code_promotion' => 'required|string|max:255|unique:promotions',
            'pourcentage_reduction' => 'required|numeric|min:0|max:100',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        return Promotion::create($request->all());
    }

    public function show(Promotion $promotion)
    {
        return $promotion;
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'code_promotion' => 'required|string|max:255|unique:promotions,code_promotion,' . $promotion->id_promotion . ',id_promotion',
            'pourcentage_reduction' => 'required|numeric|min:0|max:100',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $promotion->update($request->all());
        return $promotion;
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return response()->noContent();
    }
}
