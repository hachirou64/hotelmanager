<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse_email' => 'required|email|unique:clients',
            'telephone' => 'required|string|max:20',
            'historique_sejours' => 'nullable|array',
            'preferences' => 'nullable|array',
        ]);

        return Client::create($request->all());
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse_email' => 'required|email|unique:clients,adresse_email,' . $client->id_client . ',id_client',
            'telephone' => 'required|string|max:20',
            'historique_sejours' => 'nullable|array',
            'preferences' => 'nullable|array',
        ]);

        $client->update($request->all());
        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }
}
