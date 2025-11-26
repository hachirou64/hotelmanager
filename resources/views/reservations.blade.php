<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réservations - Hôtel Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f8fafc; }
        .dark-theme { background-color: #0f172a !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 {{ auth()->user()->preferences['theme'] ?? 'auto' === 'dark' ? 'dark-theme' : '' }}">
    <!-- HEADER -->
    <header class="fixed top-0 w-full bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-gray-700 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path></svg>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Hôtel Manager</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/32x32/2563eb/ffffff?text=U" alt="User">
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md shadow-lg py-1 z-10">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Mon Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex pt-16">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed md:static top-16 left-0 w-64 bg-white dark:bg-slate-800 shadow-sm min-h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30">
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <a href="/dashboard" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md"> 
                        <span>Tableau de Bord</span>
                    </a>

                    <!-- Réservations (actif) -->
                    <a href="{{ route('reservations') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-md">
                        <span>Réservations</span>
                    </a>

                    <a href="{{ route('rooms') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">Chambres</a>
                    <a href="{{ route('clients') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">Clients</a>
                </div>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 md:p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Réservations</h1>
                <p class="text-gray-600 dark:text-gray-300">Gérez les réservations de votre hôtel.</p>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    @if(isset($reservations) && $reservations->isEmpty())
                        <p class="text-gray-600">Aucune réservation trouvée.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chambre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Arrivée</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Départ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                        <th class="px-6 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reservations ?? collect() as $reservation)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $reservation->id_reservation ?? $reservation->id ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ optional($reservation->client)->nom ?? '—' }} {{ optional($reservation->client)->prenom ?? '' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ optional($reservation->room)->numero_chambre ?? optional($reservation->room)->numero ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $reservation->date_debut ?? $reservation->checkin_date ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $reservation->date_fin ?? $reservation->checkout_date ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ strtolower($reservation->statut ?? $reservation->status ?? '') === 'payée' || strtolower($reservation->status ?? '') === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($reservation->statut ?? $reservation->status ?? '—') }}</span></td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <a href="{{ route('rooms.modal', [$reservation->id_chambre ?? ($reservation->room->id_chambre ?? ($reservation->room->id ?? 0))]) }}" class="text-primary hover:underline open-room-modal">Voir la chambre</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });
    </script>

    <!-- Room quick-view modal -->
    <div id="room-modal" class="fixed inset-0 hidden items-center justify-center z-50">
        <div id="room-modal-overlay" class="absolute inset-0 bg-black opacity-50"></div>
        <div id="room-modal-container" class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 overflow-auto" style="max-height:90vh;">
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Détails de la chambre</h3>
                    <button id="room-modal-close" class="text-gray-600 hover:text-gray-800">✕</button>
                </div>
                <div id="room-modal-content" class="mt-4"></div>
            </div>
        </div>
    </div>

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
                        document.body.classList.add('overflow-hidden');
                    })
                    .catch(function(err) {
                        content.innerHTML = '<p class="text-red-600">Impossible de charger les détails de la chambre.</p>';
                        modal.classList.remove('hidden');
                    });
            });
        });

        // Close modal
        document.getElementById('room-modal-close').addEventListener('click', function() {
            var modal = document.getElementById('room-modal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        document.getElementById('room-modal-overlay').addEventListener('click', function() {
            var modal = document.getElementById('room-modal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    </script>
</body>
</html>
