@extends('layouts.payment')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Summary -->
    <aside class="lg:col-span-2 bg-gray-50 dark:bg-slate-800 rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Résumé de la réservation</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Commande #{{ $reservation->id_reservation }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Montant</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($amount, 2) }} €</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm">
                <h4 class="text-sm text-gray-500">Chambre</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ optional($reservation->room)->numero_chambre ?? '—' }} • {{ optional($reservation->room->roomType)->nom_type ?? '—' }}</p>
                <p class="mt-2 text-xs text-gray-500">Capacité: {{ optional($reservation->room)->capacite_max ?? '—' }} personne(s)</p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm">
                <h4 class="text-sm text-gray-500">Séjour</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">Du {{ $reservation->date_debut ?? $reservation->checkin_date ?? '—' }} au {{ $reservation->date_fin ?? $reservation->checkout_date ?? '—' }}</p>
                <p class="mt-2 text-xs text-gray-500">{{ $reservation->date_debut && $reservation->date_fin ? \Illuminate\Support\Carbon::parse($reservation->date_debut)->diffInDays(\Illuminate\Support\Carbon::parse($reservation->date_fin)) . ' nuit(s)' : '' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h4 class="text-sm text-gray-500">Détails</h4>
            <div class="mt-3 bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm text-sm text-gray-700 dark:text-gray-300">
                @if($reservation->notes)
                    <p>{{ $reservation->notes }}</p>
                @else
                    <p>Pas de notes additionnelles.</p>
                @endif
            </div>
        </div>
    </aside>

    <!-- Payment form -->
    <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Paiement</h3>
            <div class="text-xs text-gray-500">Sécurisé <span aria-hidden>•</span> SSL</div>
        </div>

        <p class="mt-2 text-sm text-gray-500">Payer via MOMO. Vos informations ne seront pas partagées.</p>

        <form id="payment-form" method="POST" action="{{ route('reservations.pay', $reservation) }}" class="mt-4">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro de téléphone (MOMO)</label>
                <input type="tel" name="telephone" value="{{ old('telephone') }}" class="mt-1 block w-full border-gray-200 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary" placeholder="Ex: +22960000000" required />
                @error('telephone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fournisseur MOMO</label>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @php($sel = old('provider', 'mtn'))
                        @foreach(['mtn' => 'MTN', 'moov' => 'Moov', 'celtis' => 'Celtis'] as $key => $label)
                        <label class="relative cursor-pointer rounded-lg border p-2 flex flex-col items-center justify-center text-center bg-white dark:bg-slate-900 shadow-sm hover:shadow-md transition focus-within:ring-2 focus-within:ring-primary">
                            <input type="radio" name="provider" value="{{ $key }}" class="sr-only provider-radio" {{ $sel == $key ? 'checked' : '' }} required>
                            <img src="{{ asset('images/providers/' . $key . '.svg') }}" alt="{{ $label }}" class="h-8 w-auto">
                            <span class="mt-2 text-xs text-gray-600 dark:text-gray-300">{{ $label }}</span>
                            <span class="absolute top-2 right-2 text-xs text-green-600 provider-check {{ $sel == $key ? '' : 'hidden' }}">✓</span>
                        </label>
                        @endforeach
                    </div>
                @error('provider') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email (optionnel)</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-200 dark:border-gray-700 rounded-md shadow-sm" placeholder="Recevoir le reçu par email" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <button id="pay-btn" type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary"> 
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white hidden" id="pay-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span id="pay-label">Payer {{ number_format($amount, 2) }} €</span>
                </button>
            </div>

            <p class="mt-4 text-xs text-gray-500">En procédant au paiement, vous acceptez nos <a href="/terms" class="underline">conditions</a>.</p>
        </form>

        <div class="mt-6 border-t pt-4 text-sm text-gray-500">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden><path d="M10 2a6 6 0 00-6 6v3.586l-1.293 1.293A1 1 0 004 15h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/></svg>
                <div>Paiement traité de manière sécurisée.</div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.getElementById('payment-form').addEventListener('submit', function(e){
        var btn = document.getElementById('pay-btn');
        var spinner = document.getElementById('pay-spinner');
        var label = document.getElementById('pay-label');
        btn.setAttribute('disabled', 'disabled');
        spinner.classList.remove('hidden');
        label.textContent = 'Traitement…';
    });

    // Provider radio visuals
    document.querySelectorAll('.provider-radio').forEach(function(radio){
        radio.addEventListener('change', function(){
            document.querySelectorAll('.provider-check').forEach(function(el){ el.classList.add('hidden'); });
            var parent = radio.closest('label');
            var check = parent.querySelector('.provider-check');
            if (check) check.classList.remove('hidden');
        });
    });
</script>
@endpush

@if(session('success'))
@push('scripts')
<script>
    // Show confirmation overlay when redirect contains success message
    document.addEventListener('DOMContentLoaded', function(){
        var overlay = document.createElement('div');
        overlay.id = 'payment-confirm-overlay';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white dark:bg-slate-900 rounded-lg p-6 max-w-md w-full text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Paiement initié</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ session('success') }}</p>
                <div class="mt-4">
                    <button id="confirm-ok" class="px-4 py-2 bg-primary text-white rounded">J'ai compris</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        document.getElementById('confirm-ok').addEventListener('click', function(){
            overlay.remove();
        });
    });
</script>
@endpush
@endif
@endsection
