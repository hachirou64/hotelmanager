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
                        @auth
                        <a href="#" id="modal-book-room-link" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Réserver cette chambre</a>
                        @else
                        <a href="/login" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark">Se connecter pour réserver</a>
                        @endauth
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
    <header class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Hôtel Manager</span>
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200">Accueil</a>
                    <a href="/login" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark">Se connecter</a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Chambres Disponibles</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Découvrez nos chambres disponibles. Connectez-vous pour effectuer une réservation.</p>
        </div>

        <!-- Date Selection Form -->
        <div class="bg-white dark:bg-slate-800 shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Vérifier la disponibilité</h3>
                <form method="GET" action="{{ route('public.rooms') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'arrivée</label>
                        <input type="date" id="date_debut" name="date_debut" value="{{ $date_debut }}" min="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de départ</label>
                        <input type="date" id="date_fin" name="date_fin" value="{{ $date_fin }}" min="{{ $date_debut ?: date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-900 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded">Rechercher</button>
                    </div>
                </form>
                @if($date_debut && $date_fin)
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">Affichage des chambres disponibles du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}.</p>
                @endif
            </div>
        </div>

        <!-- Rooms List -->
        <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Liste des Chambres</h3>
                <div class="space-y-3">
                    @forelse($rooms as $room)
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $room->numero_chambre }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Type: {{ $room->roomType->nom_type ?? 'N/A' }} - Capacité: {{ $room->capacite_max }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Prix: {{ $room->roomType->prix_base ?? 'N/A' }} €/nuit</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($date_debut && $date_fin) bg-green-100 text-green-800
                                @elseif($room->statut === 'libre') bg-green-100 text-green-800
                                @elseif($room->statut === 'occupée') bg-red-100 text-red-800
                                @elseif($room->statut === 'nettoyage') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($date_debut && $date_fin) Disponible @else {{ ucfirst($room->statut) }} @endif
                            </span>

                            <button type="button"
                                class="view-room-btn inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-md"
                                data-room-id="{{ $room->id_chambre ?? $room->id }}"
                                data-room-number="{{ $room->numero_chambre }}"
                                data-room-type="{{ $room->roomType->nom_type ?? '' }}"
                                data-room-status="{{ $date_debut && $date_fin ? 'disponible' : $room->statut }}"
                                data-room-capacity="{{ $room->capacite_max }}"
                                data-room-price="{{ $room->roomType->prix_base ?? '' }}"
                                data-room-description="{{ $room->description ?? $room->roomType->description ?? '' }}"
                                onclick="openRoomModalFromButton(this)"
                            >Voir</button>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Aucune chambre trouvée</p>
                    @endforelse
                </div>
            </div>
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
