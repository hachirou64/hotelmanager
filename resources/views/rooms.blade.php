@extends('layouts.app')

@section('title', 'Chambres - Hôtel Manager')

@section('content')
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
    </script>
@endsection
