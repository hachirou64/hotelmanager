@extends('layouts.app')

@section('title', 'Clients - Hôtel Manager')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Clients</h1>
        <p class="text-gray-600 dark:text-gray-300">Gérez les clients de votre hôtel.</p>
    </div>

    <!-- Clients List -->
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Liste des Clients</h3>
            <div class="space-y-3">
                @forelse($clients as $client)
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->nom }} {{ $client->prenom }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Email: {{ $client->adresse_email }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Téléphone: {{ $client->telephone }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">Aucun client trouvé</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
