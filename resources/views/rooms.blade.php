<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chambres - Hôtel Manager</title>
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
    <!-- Reservation Modal -->
    <div id="reservation-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg max-w-2xl w-full mx-4 overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nouvelle réservation</h3>
                <button type="button" onclick="closeReservationModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>

            <div class="p-6">
                <form id="reservation-form" onsubmit="submitReservation(event)">
                    <input type="hidden" id="res-room-id" name="id_chambre">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600">Client</label>
                            <select id="res-client" name="id_client" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600">Statut</label>
                            <select name="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="confirmée">confirmée</option>
                                <option value="en cours">en cours</option>
                                <option value="annulée">annulée</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600">Date début</label>
                            <input type="date" name="date_debut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600">Date fin</label>
                            <input type="date" name="date_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-600">Demandes spéciales (optionnel)</label>
                            <textarea name="demandes_speciales" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div id="res-message" class="text-sm"></div>
                        <div class="space-x-2">
                            <button type="button" onclick="closeReservationModal()" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Annuler</button>
                            <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white">Confirmer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                        <button id="modal-book" class="px-4 py-2 rounded-md bg-primary text-white">Réserver</button>
                        <a id="modal-edit" href="#" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200">Éditer</a>
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

                const editLink = document.getElementById('modal-edit');
                editLink.setAttribute('href', '/rooms/' + roomId + '/edit');

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
            // Open reservation form when clicking Réserver
            document.getElementById('modal-book').addEventListener('click', function() {
                const roomNumberText = document.getElementById('modal-room-number').textContent;
                const roomIdMatch = roomNumberText.match(/\d+/);
                const roomId = roomIdMatch ? roomIdMatch[0] : null;
                openReservationModal(roomId);
            });
        });

        // Reservation modal helpers
        function openReservationModal(roomId) {
            const resModal = document.getElementById('reservation-modal');
            // reset form
            document.getElementById('reservation-form').reset();
            document.getElementById('res-room-id').value = roomId || '';
            // load clients into select
            const clientSelect = document.getElementById('res-client');
            clientSelect.innerHTML = '<option value="">Chargement...</option>';
            fetch('/api/clients')
                .then(r => r.json())
                .then(data => {
                    clientSelect.innerHTML = '<option value="">-- Choisir un client --</option>';
                    data.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id_client || c.id || '';
                        opt.textContent = (c.nom || '') + ' ' + (c.prenom || '') + (c.adresse_email ? ' — ' + c.adresse_email : '');
                        clientSelect.appendChild(opt);
                    });
                }).catch(err => {
                    clientSelect.innerHTML = '<option value="">Erreur chargement clients</option>';
                    console.error(err);
                });

            resModal.classList.remove('hidden');
            resModal.classList.add('flex');
        }

        function closeReservationModal() {
            const resModal = document.getElementById('reservation-modal');
            resModal.classList.remove('flex');
            resModal.classList.add('hidden');
        }

        // Submit reservation
        function submitReservation(e) {
            e.preventDefault();
            const form = document.getElementById('reservation-form');
            const payload = {
                id_client: form['id_client'].value,
                id_chambre: form['id_chambre'].value,
                date_debut: form['date_debut'].value,
                date_fin: form['date_fin'].value,
                statut: form['statut'].value,
                demandes_speciales: form['demandes_speciales'].value || null,
            };

            // basic client-side validation
            if (!payload.id_client || !payload.id_chambre || !payload.date_debut || !payload.date_fin) {
                showResMessage('Veuillez remplir tous les champs requis.', 'error');
                return;
            }

            fetch('/api/reservations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            }).then(async res => {
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    const msg = err.message || JSON.stringify(err.errors || err || 'Erreur');
                    showResMessage('Erreur: ' + msg, 'error');
                    return;
                }
                const data = await res.json();
                showResMessage('Réservation créée (ID: ' + (data.id_reservation || data.id || '') + ')', 'success');
                // close modals
                closeReservationModal();
                document.getElementById('room-modal').classList.remove('flex');
                document.getElementById('room-modal').classList.add('hidden');
            }).catch(err => {
                console.error(err);
                showResMessage('Erreur réseau', 'error');
            });
        }

        function showResMessage(text, type) {
            const container = document.getElementById('res-message');
            container.textContent = text;
            container.className = type === 'success' ? 'text-green-600' : 'text-red-600';
            setTimeout(() => container.textContent = '', 5000);
        }

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

                const editLink = document.getElementById('modal-edit');
                if (roomId) editLink.setAttribute('href', '/rooms/' + roomId + '/edit');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } catch (err) {
                console.error('Erreur ouverture modale depuis bouton:', err);
                alert('Erreur ouverture modale — voir console pour détails.');
            }
        }
        });
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
<body class="bg-gray-50 dark:bg-slate-900 {{ auth()->user()->preferences['theme'] ?? 'auto' === 'dark' ? 'dark-theme' : '' }}">
    <!-- HEADER -->
    <header class="fixed top-0 w-full bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-gray-700 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Hôtel Manager</span>
                    </a>
                </div>

                <!-- User Menu & Notifications -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM15 17H9a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V15a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/32x32/2563eb/ffffff?text=U" alt="User">
                            <span class="hidden md:block text-gray-700 dark:text-gray-300">Admin User</span>
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 z-10">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Mon Profil</a>
                            <a href="{{ route('user-settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Paramètres</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Se déconnecter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile sidebar overlay -->
    <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeMobileSidebar()"></div>

    <div class="flex pt-16">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed md:static top-16 left-0 w-64 bg-white dark:bg-slate-800 shadow-sm min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30">
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Tableau de Bord
                    </a>

                    <!-- Réservations -->
                    <a href="{{ route('reservations') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Réservations
                    </a>

                    <!-- Chambres -->
                    <a href="{{ route('rooms') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                        </svg>
                        Chambres
                    </a>

                    <!-- Clients -->
                    <a href="{{ route('clients') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Clients
                    </a>

                    <!-- Facturation -->
                    <a href="{{ route('billing') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Facturation
                    </a>

                    <!-- Personnel -->
                    <a href="{{ route('personnel') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Personnel
                    </a>

                    <!-- Paramètres -->
                    <a href="{{ route('settings') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Paramètres
                    </a>
                </div>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 md:p-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Chambres</h1>
                <p class="text-gray-600 dark:text-gray-300">Gérez les chambres de votre hôtel.</p>
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">Statut: {{ ucfirst($room->statut) }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($room->statut === 'libre') bg-green-100 text-green-800
                                    @elseif($room->statut === 'occupée') bg-red-100 text-red-800
                                    @elseif($room->statut === 'nettoyage') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($room->statut) }}
                                </span>

                                <button type="button" 
                                    class="view-room-btn inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-md"
                                    data-room-id="{{ $room->id_chambre ?? $room->id }}"
                                    data-room-number="{{ $room->numero_chambre }}"
                                    data-room-type="{{ $room->roomType->nom_type ?? '' }}"
                                    data-room-status="{{ $room->statut }}"
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
    </div>

    <script>
        // User menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userMenuButton = document.getElementById('user-menu-button');
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Mobile sidebar toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Apply theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = '{{ auth()->user()->preferences['theme'] ?? 'auto' }}';
            applyTheme(currentTheme);
        });

        // Theme switching functionality
        function applyTheme(theme) {
            const body = document.body;
            if (theme === 'dark') {
                body.classList.add('dark-theme');
            } else if (theme === 'light') {
                body.classList.remove('dark-theme');
            } else if (theme === 'auto') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (prefersDark) {
                    body.classList.add('dark-theme');
                } else {
                    body.classList.remove('dark-theme');
                }
            }
        }
    </script>
</body>
</html>
