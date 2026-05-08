@extends('layouts.marketing')

@section('title', 'Chambres disponibles - Hôtel Plaza')

@section('content')
<!-- Section hero réduction : titre + soustitre -->
<div class="relative overflow-hidden bg-gradient-to-r from-amber-50 via-white to-amber-50 dark:from-slate-800 dark:via-slate-900 dark:to-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <span class="text-amber-700 dark:text-amber-400 text-sm font-semibold tracking-wider uppercase">Séjournez dans le confort</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mt-2">Nos chambres disponibles</h1>
            <div class="w-24 h-1 bg-amber-600 mx-auto mt-4 mb-6"></div>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Découvrez un art de vivre unique, entre élégance classique et équipements modernes.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Formulaire de recherche de dates (carte raffinée) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-amber-100 dark:border-slate-700 overflow-hidden mb-16">
        <div class="bg-gradient-to-r from-amber-50 to-white dark:from-slate-700 dark:to-slate-800 px-8 py-6">
            <div class="flex items-center gap-3">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <h3 class="font-serif text-2xl font-semibold text-gray-900 dark:text-white">Vérifier la disponibilité</h3>
            </div>
            <p class="text-gray-500 dark:text-gray-400 mt-1 ml-10">Choisissez vos dates pour voir les chambres libres</p>
        </div>

        <div class="p-8">
            <form method="GET" action="{{ route('public.rooms') }}" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📅 Date d’arrivée</label>
                    <input type="date" name="date_debut" value="{{ $date_debut ?? '' }}" min="{{ date('Y-m-d') }}"
                           class="w-full px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📅 Date de départ</label>
                    <input type="date" name="date_fin" value="{{ $date_fin ?? '' }}" min="{{ $date_debut ?? date('Y-m-d') }}"
                           class="w-full px-5 py-3 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                </div>
                <div class="md:col-span-2 flex justify-center">
                    <button type="submit" class="px-10 py-3 bg-amber-700 hover:bg-amber-800 text-white font-semibold rounded-full shadow-md transition transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Rechercher
                    </button>
                </div>
            </form>

            @if(!empty($date_debut) && !empty($date_fin))
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Chambres disponibles du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Liste des chambres avec cartes modernes -->
    @if(isset($rooms) && $rooms->count())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($rooms as $room)
                <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-slate-700">
                    <!-- Image réelle ou placeholder élégant -->
                    <div class="h-56 w-full overflow-hidden bg-amber-50 dark:bg-slate-700 relative">
                        @if($room->photo)
                            <img src="{{ asset('storage/'.$room->photo) }}" alt="Chambre {{ $room->numero_chambre }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-20 h-20 text-amber-300 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h18v18H3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 9h6v6H9z"/></svg>
                            </div>
                        @endif
                        <!-- Badge statut -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if(!empty($date_debut) && !empty($date_fin)) bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                @elseif($room->statut === 'libre') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                @elseif($room->statut === 'occupée') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                                @else bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300
                                @endif">
                                @if(!empty($date_debut) && !empty($date_fin)) Disponible @else {{ ucfirst($room->statut) }} @endif
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-serif text-2xl font-bold text-gray-900 dark:text-white">Chambre {{ $room->numero_chambre }}</h3>
                                <p class="text-amber-700 dark:text-amber-400 text-sm mt-1">{{ $room->roomType->nom_type ?? 'Chambre standard' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-amber-700 dark:text-amber-400">{{ number_format($room->roomType->prix_base ?? 0, 2, ',', ' ') }} €</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">/nuit</span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $room->capacite_max }} personne{{ $room->capacite_max > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>20 m²</span>
                            </div>
                        </div>

                        @if(!empty($room->description))
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm line-clamp-2">{{ $room->description }}</p>
                        @endif

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="button"
                                class="view-room-btn flex-1 inline-flex justify-center items-center gap-2 px-4 py-2.5 border border-amber-600 text-amber-700 dark:text-amber-400 font-medium rounded-full hover:bg-amber-50 dark:hover:bg-amber-900/20 transition"
                                data-room-id="{{ $room->id_chambre ?? $room->id }}"
                                data-room-number="{{ $room->numero_chambre }}"
                                data-room-type="{{ $room->roomType->nom_type ?? '' }}"
                                data-room-status="{{ (!empty($date_debut) && !empty($date_fin)) ? 'disponible' : $room->statut }}"
                                data-room-capacity="{{ $room->capacite_max }}"
                                data-room-price="{{ $room->roomType->prix_base ?? '' }}"
                                data-room-description="{{ $room->description ?? $room->roomType->description ?? '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Détails
                            </button>
                            <a href="/book-room/{{ $room->id_chambre ?? $room->id }}{{ !empty($date_debut) && !empty($date_fin) ? '?date_debut=' . urlencode($date_debut) . '&date_fin=' . urlencode($date_fin) : '' }}"
                               class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-amber-700 hover:bg-amber-800 text-white font-medium rounded-full shadow transition transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination si nécessaire -->
        @if(method_exists($rooms, 'links'))
            <div class="mt-12">
                {{ $rooms->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md p-12 text-center border border-amber-100 dark:border-slate-700">
            <svg class="w-20 h-20 text-amber-300 dark:text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h18v18H3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 9h6v6H9z"/></svg>
            <h3 class="font-serif text-2xl font-semibold text-gray-900 dark:text-white mb-2">Aucune chambre trouvée</h3>
            <p class="text-gray-500 dark:text-gray-400">Modifiez vos dates pour voir les disponibilités.</p>
        </div>
    @endif
</div>

<!-- MODAL pour les détails de chambre (améliorée) -->
<div id="room-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden transform transition-all">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-amber-50 dark:bg-slate-700/50">
            <div>
                <h2 id="modal-room-number" class="font-serif text-2xl font-bold text-gray-900 dark:text-white">Chambre 101</h2>
                <p id="modal-room-type" class="text-amber-700 dark:text-amber-400 text-sm">Type de chambre</p>
            </div>
            <div class="flex items-center gap-3">
                <span id="modal-room-status" class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Libre</span>
                <button id="modal-close" class="text-gray-500 hover:text-amber-700 transition text-2xl leading-none">&times;</button>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center justify-center bg-gray-100 dark:bg-slate-700 rounded-xl p-4">
                <svg class="w-32 h-32 text-amber-300 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h18v18H3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 9h6v6H9z"/></svg>
            </div>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">Capacité</h4>
                    <p id="modal-room-capacity" class="text-lg text-gray-900 dark:text-white">2 personnes</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">Prix par nuit</h4>
                    <p id="modal-room-price" class="text-2xl font-bold text-amber-700 dark:text-amber-400">0 €</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">Description</h4>
                    <p id="modal-room-description" class="text-gray-600 dark:text-gray-300 text-sm">...</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/30 flex justify-end">
            <a href="#" id="modal-book-room-link" class="px-6 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded-full font-semibold transition shadow">Réserver cette chambre</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la modale
        const modal = document.getElementById('room-modal');
        const modalClose = document.getElementById('modal-close');
        const bookLink = document.getElementById('modal-book-room-link');

        function openModal(roomData) {
            document.getElementById('modal-room-number').textContent = 'Chambre ' + (roomData.number || '');
            document.getElementById('modal-room-type').textContent = 'Type : ' + (roomData.type || '');
            const statusSpan = document.getElementById('modal-room-status');
            let status = (roomData.status || 'inconnu').toLowerCase();
            statusSpan.textContent = status === 'disponible' ? 'Disponible' : (status === 'libre' ? 'Libre' : status.charAt(0).toUpperCase() + status.slice(1));
            statusSpan.className = 'px-3 py-1 rounded-full text-xs font-semibold';
            if (status === 'libre' || status === 'disponible') statusSpan.classList.add('bg-green-100','text-green-800','dark:bg-green-900/50','dark:text-green-300');
            else if (status === 'occupée') statusSpan.classList.add('bg-red-100','text-red-800','dark:bg-red-900/50','dark:text-red-300');
            else statusSpan.classList.add('bg-amber-100','text-amber-800','dark:bg-amber-900/50','dark:text-amber-300');

            document.getElementById('modal-room-capacity').textContent = (roomData.capacity || '0') + ' personne' + (parseInt(roomData.capacity) > 1 ? 's' : '');
            document.getElementById('modal-room-price').textContent = (parseFloat(roomData.price) || 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) + ' €';
            document.getElementById('modal-room-description').textContent = roomData.description || 'Aucune description disponible.';

            let url = '/book-room/' + encodeURIComponent(roomData.id);
            if (window.dateParams) url += '?date_debut=' + encodeURIComponent(window.dateParams.debut) + '&date_fin=' + encodeURIComponent(window.dateParams.fin);
            bookLink.setAttribute('href', url);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        window.openRoomModalFromButton = function(btn) {
            const dataset = btn.dataset;
            openModal({
                id: dataset.roomId,
                number: dataset.roomNumber,
                type: dataset.roomType,
                status: dataset.roomStatus,
                capacity: dataset.roomCapacity,
                price: dataset.roomPrice,
                description: dataset.roomDescription
            });
        };

        document.querySelectorAll('.view-room-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const dataset = this.dataset;
                openModal({
                    id: dataset.roomId,
                    number: dataset.roomNumber,
                    type: dataset.roomType,
                    status: dataset.roomStatus,
                    capacity: dataset.roomCapacity,
                    price: dataset.roomPrice,
                    description: dataset.roomDescription
                });
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        modalClose.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) { if(e.target === modal) closeModal(); });
        window.addEventListener('keydown', function(e) { if(e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });

        // Préparer les paramètres de date pour le lien de réservation dans la modale
        @if(!empty($date_debut) && !empty($date_fin))
            window.dateParams = { debut: '{{ $date_debut }}', fin: '{{ $date_fin }}' };
        @endif
    });
</script>
@endsection