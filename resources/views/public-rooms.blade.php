<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chambres Disponibles - Hôtel Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        'primary-dark': '#1d4ed8',
                    }
                }
            }
        }
    </script>
    <!-- Room Detail Modal -->
    <div id="room-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg max-w-3xl w-full mx-4 overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 id="modal-room-number" class="text-lg font-semibold text-gray-900 dark:text-white">Chambre 101</h2>
                    <p id="modal-room-type" class="text-sm text-gray-500 dark:text-gray-300">Type: Chambre Simple</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="modal-room-status" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Libre</span>
                    <button id="modal-close" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 flex items-center justify-center">
                    <div class="w-40 h-28 bg-gray-100 dark:bg-slate-700 rounded-md flex items-center justify-center text-gray-400">Image</div>
                </div>

                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <h3 class="text-sm text-gray-500">Capacité</h3>
                            <p id="modal-room-capacity" class="text-base text-gray-900 dark:text-white">1 personne</p>
                        </div>

                        <div>
                            <h3 class="text-sm text-gray-500">Prix (base)</h3>
                            <p id="modal-room-price" class="text-base text-gray-900 dark:text-white">50,00 €</p>
                        </div>

                        <div>
                            <h3 class="text-sm text-gray-500">Description</h3>
                            <p id="modal-room-description" class="text-gray-700 dark:text-gray-300">Chambre avec un lit simple</p>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-2">
                        <a href="#" id="modal-book-room-link" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Réserver cette chambre</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal logic for viewing a room (wait for DOM ready)
        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation so dynamically added buttons still work
            document.addEventListener('click', function(e) {
                const btn = e.target.closest && e.target.closest('.view-room-btn');
                if (!btn) return;

                const roomId = btn.getAttribute('data-room-id');
                console.log('view-room clicked, roomId=', roomId);
                if (!roomId) return;

            // Prefer reading data-attributes rendered server-side to avoid API/auth issues
            const dataset = btn.dataset;
            const modal = document.getElementById('room-modal');
            document.getElementById('modal-room-number').textContent = 'Chambre ' + (dataset.roomNumber ?? 'N/A');
            document.getElementById('modal-room-type').textContent = 'Type: ' + (dataset.roomType ?? 'N/A');

            const statusEl = document.getElementById('modal-room-status');

            // Set booking link href dynamically
            const bookLink = document.getElementById('modal-book-room-link');
            if(bookLink) {
                let url = '/book-room/' + encodeURIComponent(dataset.roomId || '');
                if (window.date_debut && window.date_fin) {
                    url += '?date_debut=' + encodeURIComponent(window.date_debut) + '&date_fin=' + encodeURIComponent(window.date_fin);
                }
                bookLink.setAttribute('href', url);
            }
                const statut = (dataset.roomStatus ?? 'inconnu').toLowerCase();
                statusEl.textContent = statut.charAt(0).toUpperCase() + statut.slice(1);
                statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium';
                if (statut === 'libre') {
                    statusEl.classList.add('bg-green-100','text-green-800');
                } else if (statut === 'occupée' || statut === 'occupee') {
                    statusEl.classList.add('bg-red-100','text-red-800');
                } else if (statut === 'nettoyage') {
                    statusEl.classList.add('bg-yellow-100','text-yellow-800');
                } else {
                    statusEl.classList.add('bg-gray-100','text-gray-800');
                }

                document.getElementById('modal-room-capacity').textContent = (dataset.roomCapacity ?? 'N/A') + ' personne' + ((parseInt(dataset.roomCapacity) ?? 0) > 1 ? 's' : '');
                document.getElementById('modal-room-price').textContent = (dataset.roomPrice ? parseFloat(dataset.roomPrice).toLocaleString('fr-FR',{minimumFractionDigits:2,maximumFractionDigits:2}) + ' €' : 'N/A');
                document.getElementById('modal-room-description').textContent = (dataset.roomDescription ?? 'Pas de description disponible.');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            document.getElementById('modal-close').addEventListener('click', function() {
                const modal = document.getElementById('room-modal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });

            // Close modal on background click
            document.getElementById('room-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('flex');
                    this.classList.add('hidden');
                }
            });
        });

        // Fallback global function called by inline onclick on the "Voir" buttons
        function openRoomModalFromButton(btn) {
            try {
                const dataset = btn.dataset;
                const roomId = dataset.roomId || btn.getAttribute('data-room-id');
                console.log('openRoomModalFromButton called for', roomId);

                const modal = document.getElementById('room-modal');
                document.getElementById('modal-room-number').textContent = 'Chambre ' + (dataset.roomNumber ?? 'N/A');
                document.getElementById('modal-room-type').textContent = 'Type: ' + (dataset.roomType ?? 'N/A');

                const statusEl = document.getElementById('modal-room-status');
                const statut = (dataset.roomStatus ?? 'inconnu').toLowerCase();
                statusEl.textContent = statut.charAt(0).toUpperCase() + statut.slice(1);
                statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium';
                if (statut === 'libre') {
                    statusEl.classList.add('bg-green-100','text-green-800');
                } else if (statut === 'occupée' || statut === 'occupee') {
                    statusEl.classList.add('bg-red-100','text-red-800');
                } else if (statut === 'nettoyage') {
                    statusEl.classList.add('bg-yellow-100','text-yellow-800');
                } else {
                    statusEl.classList.add('bg-gray-100','text-gray-800');
                }

                document.getElementById('modal-room-capacity').textContent = (dataset.roomCapacity ?? 'N/A') + ' personne' + ((parseInt(dataset.roomCapacity) ?? 0) > 1 ? 's' : '');
                document.getElementById('modal-room-price').textContent = (dataset.roomPrice ? parseFloat(dataset.roomPrice).toLocaleString('fr-FR',{minimumFractionDigits:2,maximumFractionDigits:2}) + ' €' : 'N/A');
                document.getElementById('modal-room-description').textContent = (dataset.roomDescription ?? 'Pas de description disponible.');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } catch (err) {
                console.error('Erreur ouverture modale depuis bouton:', err);
                alert('Erreur ouverture modale — voir console pour détails.');
            }
        }
    </script>

    <style>
        body {
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            body.dark-theme {
                background-color: #0f172a;
            }
        }

        .dark-theme {
            background-color: #0f172a !important;
        }

        .dark-theme .bg-white {
            background-color: #1e293b !important;
        }

        .dark-theme .text-gray-900 {
            color: #f1f5f9 !important;
        }

        .dark-theme .text-gray-600 {
            color: #94a3b8 !important;
        }

        .dark-theme .text-gray-500 {
            color: #64748b !important;
        }

        .dark-theme .border-gray-200 {
            border-color: #334155 !important;
        }

        .dark-theme .hover\:bg-gray-50:hover {
            background-color: #334155 !important;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900">
    <!-- HEADER -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md shadow-lg border-b border-gray-200 dark:border-gray-700 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3 group">
                        <div class="p-2 rounded-lg bg-gradient-to-r from-primary to-primary-dark group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">Hôtel Manager</span>
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-all duration-200 font-medium relative group">
                        Accueil
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary group-hover:w-full transition-all duration-200"></span>
                    </a>
                    <a href="/login" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary to-primary-dark text-white hover:from-primary-dark hover:to-primary hover:shadow-lg hover:scale-105 transition-all duration-200 font-medium">Se connecter</a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-24">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Chambres Disponibles</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Découvrez nos chambres disponibles. Connectez-vous pour effectuer une réservation.</p>
        </div>

        <!-- Date Selection Form -->
        <div class="bg-gradient-to-r from-white to-gray-50 dark:from-slate-800 dark:to-slate-700 shadow-xl rounded-2xl mb-8 border border-gray-100 dark:border-slate-600">
            <div class="px-6 py-8 sm:px-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Vérifier la disponibilité</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sélectionnez vos dates pour voir les chambres disponibles</p>
                </div>
                <form method="GET" action="{{ route('public.rooms') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label for="date_debut" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date d'arrivée</label>
                        <div class="relative">
                            <input type="date" id="date_debut" name="date_debut" value="{{ $date_debut }}" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 text-lg">
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="date_fin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date de départ</label>
                        <div class="relative">
                            <input type="date" id="date_fin" name="date_fin" value="{{ $date_fin }}" min="{{ $date_debut ?: date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 text-lg">
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="md:col-span-4 flex justify-center">
                        <button type="submit" class="px-8 py-4 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Rechercher</span>
                        </button>
                    </div>
                </form>
                @if($date_debut && $date_fin)
                <div class="mt-6 text-center">
                    <p class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Chambres disponibles du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Rooms List -->
        <div class="mb-8">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Liste des Chambres</h3>
                <p class="text-gray-600 dark:text-gray-300">Découvrez nos chambres confortables et modernes</p>
            </div>

            @forelse($rooms as $room)
            <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-slate-600 hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] mb-6">
                <div class="md:flex">
                    <!-- Room Image -->
                    <div class="md:w-1/3 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center p-8">
                        <img src="/images/image{{ ($loop->index % 5) + 1 }}.jpg" alt="Chambre {{ $room->numero_chambre }}" class="w-full h-48 object-cover rounded-lg shadow-md">
                    </div>

                    <!-- Room Details -->
                    <div class="md:w-2/3 p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $room->numero_chambre }}</h4>
                                <p class="text-lg text-gray-600 dark:text-gray-300">{{ $room->roomType->nom_type ?? 'Chambre Standard' }}</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                    @if($date_debut && $date_fin) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($room->statut === 'libre') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($room->statut === 'occupée') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @elseif($room->statut === 'nettoyage') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-slate-300
                                    @endif">
                                    @if($date_debut && $date_fin) Disponible @else {{ ucfirst($room->statut) }} @endif
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Capacité: {{ $room->capacite_max }} personne{{ $room->capacite_max > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Prix: {{ $room->roomType->prix_base ?? 'N/A' }} €/nuit</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-300">Équipée</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button"
                                class="view-room-btn flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2"
                                data-room-id="{{ $room->id_chambre ?? $room->id }}"
                                data-room-number="{{ $room->numero_chambre }}"
                                data-room-type="{{ $room->roomType->nom_type ?? '' }}"
                                data-room-status="{{ $date_debut && $date_fin ? 'disponible' : $room->statut }}"
                                data-room-capacity="{{ $room->capacite_max }}"
                                data-room-price="{{ $room->roomType->prix_base ?? '' }}"
                                data-room-description="{{ $room->description ?? $room->roomType->description ?? '' }}"
                                onclick="openRoomModalFromButton(this)"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Voir les détails</span>
                            </button>

                            <a href="/book-room/{{ $room->id_chambre ?? $room->id }}{!! $date_debut && $date_fin ? '?date_debut=' . urlencode($date_debut) . '&date_fin=' . urlencode($date_fin) : '' !!}" id="book-room-link-{{ $room->id_chambre ?? $room->id }}" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Réserver</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-12 text-center border border-gray-100 dark:border-slate-600">
                <svg class="w-16 h-16 text-gray-400 dark:text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Aucune chambre trouvée</h3>
                <p class="text-gray-600 dark:text-gray-300">Essayez de modifier vos dates de recherche.</p>
            </div>
            @endforelse
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-800 dark:bg-slate-950 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center flex-wrap">
                <p class="text-sm">&copy; 2025 Hôtel Manager. Tous droits réservés.</p>
                <div class="flex space-x-4 text-sm">
                    <a href="/" class="hover:text-primary transition">Accueil</a>
                    @auth
                    <a href="{{ route('public.rooms') }}" class="hover:text-primary transition">Réserver</a>
                    <a href="{{ route('reservations') }}" class="hover:text-primary transition">Mes Réservations</a>
                    @else
                    <a href="/login" class="hover:text-primary transition">Connexion</a>
                    @endauth
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
