@extends('layouts.client')

@section('title', 'Mes Réservations')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Mes réservations</h1>

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
                            @if(in_array(strtolower($reservation->status ?? $reservation->statut ?? ''), ['en cours', 'confirmée']))
                                <div class="mt-2">
                                    <a href="{{ route('reservations.pay.form', $reservation) }}" class="inline-block px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Payer avec MOMO</a>
                                </div>
                            @endif
                            @if($reservation->invoice && $reservation->invoice->statut_paiement === 'payée')
                                <div class="mt-2">
                                    <a href="{{ route('invoices.download', $reservation->invoice) }}" class="inline-block px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Télécharger Facture</a>
                                </div>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
@endif
        </div>
    </div>
@endsection
