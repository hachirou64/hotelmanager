<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Mes réservations</h1>

        <div class="bg-white shadow rounded-lg p-4">
            @if($reservations->isEmpty())
                <p class="text-gray-600">Vous n'avez aucune réservation pour le moment.</p>
            @else
                <ul class="space-y-3">
                    @foreach($reservations as $reservation)
                    <li class="border p-3 rounded-md flex justify-between items-center">
                        <div>
                            <div class="font-medium">Réservation #{{ $reservation->id }}</div>
                            <div class="text-sm text-gray-600">Chambre : {{ optional($reservation->room)->numero ?? '—' }}</div>
                            <div class="text-sm text-gray-600">Du {{ $reservation->checkin_date ?? $reservation->date_debut ?? '—' }} au {{ $reservation->checkout_date ?? $reservation->date_fin ?? '—' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm">Statut :</div>
                            <div class="mt-1"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ strtolower($reservation->status ?? $reservation->statut ?? '') === 'confirmed' || strtolower($reservation->statut ?? '') === 'payée' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($reservation->status ?? $reservation->statut ?? '—') }}</span></div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>
