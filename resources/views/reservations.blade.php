@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Gestion des Réservations</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Gérez les réservations de votre hôtel.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 shadow overflow-hidden sm:rounded-md">
        @if(isset($reservations) && $reservations->isEmpty())
            <div class="p-8">
                <p class="text-gray-600 dark:text-gray-300">Aucune réservation trouvée.</p>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Chambre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Arrivée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Départ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reservations ?? collect() as $reservation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $reservation->id_reservation ?? $reservation->id ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ optional($reservation->client)->nom ?? '—' }} {{ optional($reservation->client)->prenom ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ optional($reservation->room)->numero_chambre ?? optional($reservation->room)->numero ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reservation->date_debut ?? $reservation->checkin_date ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reservation->date_fin ?? $reservation->checkout_date ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ strtolower($reservation->statut ?? $reservation->status ?? '') === 'payée' || strtolower($reservation->status ?? '') === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ ucfirst($reservation->statut ?? $reservation->status ?? '—') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <a href="{{ route('rooms.modal', [$reservation->id_chambre ?? ($reservation->room->id_chambre ?? ($reservation->room->id ?? 0))]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 open-room-modal">Voir la chambre</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reservations ?? collect() as $reservation)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-slate-700">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Réservation #{{ $reservation->id_reservation ?? $reservation->id ?? '—' }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ strtolower($reservation->statut ?? $reservation->status ?? '') === 'payée' || strtolower($reservation->status ?? '') === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ ucfirst($reservation->statut ?? $reservation->status ?? '—') }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                                    <p><strong>Client:</strong> {{ optional($reservation->client)->nom ?? '—' }} {{ optional($reservation->client)->prenom ?? '' }}</p>
                                    <p><strong>Chambre:</strong> {{ optional($reservation->room)->numero_chambre ?? optional($reservation->room)->numero ?? '—' }}</p>
                                    <p><strong>Arrivée:</strong> {{ $reservation->date_debut ?? $reservation->checkin_date ?? '—' }}</p>
                                    <p><strong>Départ:</strong> {{ $reservation->date_fin ?? $reservation->checkout_date ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('rooms.modal', [$reservation->id_chambre ?? ($reservation->room->id_chambre ?? ($reservation->room->id ?? 0))]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium open-room-modal">Voir la chambre</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Room quick-view modal -->
<div id="room-modal" class="fixed inset-0 hidden items-center justify-center z-50">
    <div id="room-modal-overlay" class="absolute inset-0 bg-black opacity-50"></div>
    <div id="room-modal-container" class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-auto" style="max-height:90vh;">
        <div class="p-4">
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Détails de la chambre</h3>
                <button id="room-modal-close" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
            </div>
            <div id="room-modal-content" class="mt-4"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Open room modal and load partial
    document.querySelectorAll('.open-room-modal').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            var overlay = document.getElementById('room-modal-overlay');
            var modal = document.getElementById('room-modal');
            var content = document.getElementById('room-modal-content');

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(res) { return res.text(); })
                .then(function(html) {
                    content.innerHTML = html;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                })
                .catch(function(err) {
                    content.innerHTML = '<p class="text-red-600 dark:text-red-400">Impossible de charger les détails de la chambre.</p>';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
        });
    });

    // Close modal
    document.getElementById('room-modal-close').addEventListener('click', function() {
        var modal = document.getElementById('room-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    });

    document.getElementById('room-modal-overlay').addEventListener('click', function() {
        var modal = document.getElementById('room-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    });
</script>
@endpush
