@extends('layouts.marketing')

@section('title', 'Gestion des réservations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- En-tête avec titre et actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10">
        <div>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Réservations</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Consultez et gérez l’ensemble des séjours de votre établissement.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-primary text-white font-semibold shadow-md hover:bg-primary-dark transition-all duration-200 transform hover:scale-[1.02]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle réservation
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques (exemple) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border-l-8 border-primary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total réservations</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $reservations->count() ?? 0 }}</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-full">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border-l-8 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide">Taux d’occupation</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">78%</p>
                </div>
                <div class="bg-amber-500/10 p-3 rounded-full">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border-l-8 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide">Revenus (mois)</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">12 450 €</p>
                </div>
                <div class="bg-emerald-500/10 p-3 rounded-full">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM21 12c0 4-4 8-9 8s-9-4-9-8 4-8 9-8 9 4 9 8z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des réservations (cartes responsives) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-serif text-2xl font-semibold text-gray-900 dark:text-white">Réservations en cours</h2>
        </div>

        @if(isset($reservations) && $reservations->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="mt-4 text-gray-600 dark:text-gray-300">Aucune réservation trouvée.</p>
                <a href="{{ route('reservations.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-full hover:bg-primary-dark transition">Créer une réservation</a>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($reservations ?? collect() as $reservation)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Informations principales -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-mono text-sm font-semibold text-gray-500 dark:text-gray-400">#{{ $reservation->id_reservation ?? $reservation->id ?? '—' }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if(strtolower($reservation->statut ?? $reservation->status ?? '') === 'payée' || strtolower($reservation->status ?? '') === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif(strtolower($reservation->statut ?? $reservation->status ?? '') === 'en attente' || strtolower($reservation->status ?? '') === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif(strtolower($reservation->statut ?? $reservation->status ?? '') === 'annulée') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($reservation->statut ?? $reservation->status ?? '—') }}
                                </span>
                            </div>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="text-gray-700 dark:text-gray-300"><strong>Client :</strong> {{ optional($reservation->client)->prenom ?? '' }} {{ optional($reservation->client)->nom ?? '—' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"/></svg>
                                    <span class="text-gray-700 dark:text-gray-300"><strong>Chambre :</strong> {{ optional($reservation->room)->numero_chambre ?? optional($reservation->room)->numero ?? '—' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-gray-700 dark:text-gray-300"><strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($reservation->date_debut ?? $reservation->checkin_date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-gray-700 dark:text-gray-300"><strong>Départ :</strong> {{ \Carbon\Carbon::parse($reservation->date_fin ?? $reservation->checkout_date)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('rooms.modal', [$reservation->id_chambre ?? ($reservation->room->id_chambre ?? ($reservation->room->id ?? 0))]) }}" class="open-room-modal inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600 text-sm font-medium transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Voir chambre
                            </a>
                            <a href="{{ route('reservations.edit', $reservation->id_reservation ?? $reservation->id) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-primary text-white hover:bg-primary-dark text-sm font-medium transition shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Modifier
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Modal pour afficher les détails de la chambre -->
@include('partials.room-modal')
@endsection

@push('scripts')
<script>
    // Gestion de la modal (identique à votre code mais améliorée)
    document.querySelectorAll('.open-room-modal').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            const modal = document.getElementById('room-modal');
            const content = document.getElementById('room-modal-content');

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                })
                .catch(() => {
                    content.innerHTML = '<p class="text-red-600 dark:text-red-400">Impossible de charger les détails de la chambre.</p>';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
        });
    });

    // Fermeture de la modal
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('room-modal');
        if (e.target === modal || e.target.closest('.close-modal')) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    });
</script>
@endpush