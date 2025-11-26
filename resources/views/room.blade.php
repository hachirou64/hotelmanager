<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chambre {{ $room->numero_chambre }} - Hôtel Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <a href="{{ route('rooms') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md mb-4">&larr; Retour à la liste</a>

        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Chambre {{ $room->numero_chambre }}</h1>
            <p class="text-sm text-gray-600 mb-4">Détails de la chambre</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Type</h3>
                    <p class="text-lg text-gray-900">{{ $room->roomType->nom_type ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Capacité</h3>
                    <p class="text-lg text-gray-900">{{ $room->capacite_max }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Statut</h3>
                    <p class="text-lg text-gray-900">{{ ucfirst($room->statut) }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Prix (base)</h3>
                    <p class="text-lg text-gray-900">{{ isset($room->roomType->prix_base) ? number_format((float) $room->roomType->prix_base, 2, ',', ' ') . ' €' : 'N/A' }}</p>
                </div>
            </div>

            @if(!empty($room->description) || !empty($room->roomType->description))
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500">Description</h3>
                <p class="text-gray-700 mt-2">{{ $room->description ?? $room->roomType->description ?? 'Pas de description disponible.' }}</p>
            </div>
            @endif

            <div class="mt-6 flex space-x-2">
                <a href="{{ route('rooms') }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Retour</a>
                <a href="#" class="px-4 py-2 rounded-md bg-primary text-white">Éditer</a>
            </div>
        </div>
    </div>
</body>
</html>
