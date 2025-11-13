<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    public function index()
    {
        return Personnel::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_utilisateur' => 'required|exists:users,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'service' => 'required|string|max:255',
        ]);

        return Personnel::create($request->all());
    }

    public function show(Personnel $personnel)
    {
        return $personnel->load('user');
    }

    public function update(Request $request, Personnel $personnel)
    {
        $request->validate([
            'id_utilisateur' => 'required|exists:users,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'service' => 'required|string|max:255',
        ]);

        $personnel->update($request->all());
        return $personnel;
    }

    public function destroy(Personnel $personnel)
    {
        $personnel->delete();
        return response()->noContent();
    }
}
