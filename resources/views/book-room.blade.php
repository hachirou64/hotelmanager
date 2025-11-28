<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réserver une Chambre - Hôtel Manager</title>
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
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            border-color: #2563eb;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .floating-label {
            position: relative;
        }

        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #2563eb;
        }

        .floating-label label {
            position: absolute;
            left: 1rem;
            top: 0.75rem;
            transition: all 0.2s ease;
            pointer-events: none;
            color: #6b7280;
            background: white;
            padding: 0 0.25rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 via-purple-500 to-blue-600 min-h-screen">
    <!-- HEADER -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3 group">
                        <div class="p-2 rounded-lg bg-white/20 group-hover:bg-white/30 transition-all duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">Hôtel Manager</span>
                    </a>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-white/80 hover:text-white transition-all duration-200 font-medium relative group">
                        Accueil
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white group-hover:w-full transition-all duration-200"></span>
                    </a>
                    <a href="/public-rooms" class="text-white/80 hover:text-white transition-all duration-200 font-medium relative group">
                        Chambres
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white group-hover:w-full transition-all duration-200"></span>
                    </a>
                    @auth
                    <a href="/dashboard" class="px-4 py-2 rounded-full bg-white/20 text-white hover:bg-white/30 transition-all duration-200 font-medium">Dashboard</a>
                    @else
                    <a href="/login" class="px-4 py-2 rounded-full bg-white/20 text-white hover:bg-white/30 transition-all duration-200 font-medium">Se connecter</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="pt-24 pb-12 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">Réserver une Chambre</h1>
                <p class="text-white/80 text-lg">Chambre {{ $room->numero_chambre ?? $room->id }} - {{ $room->roomType->nom_type ?? 'Chambre Standard' }}</p>
            </div>

            <!-- Reservation Form Container -->
            <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
                <div class="px-8 py-8">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                            </div>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('book-room.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="id_chambre" value="{{ $room->id_chambre }}">

                        <!-- Dates Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="floating-label">
                                <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut', request('date_debut')) }}" min="{{ date('Y-m-d') }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                                <label for="date_debut" class="text-sm font-medium">Date d'arrivée *</label>
                                <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>

                            <div class="floating-label">
                                <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin', request('date_fin')) }}" min="{{ old('date_debut', request('date_debut')) ?: date('Y-m-d') }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                                <label for="date_fin" class="text-sm font-medium">Date de départ *</label>
                                <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Guests -->
                        <div class="floating-label">
                            <input type="number" id="guests" name="guests" min="1" max="{{ $room->capacite_max }}" value="{{ old('guests', 1) }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                            <label for="guests" class="text-sm font-medium">Nombre de personnes *</label>
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Informations de contact
                            </h3>

                            <div class="floating-label">
                                <input type="email" id="client_email" name="client_email" value="{{ old('client_email') }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                                <label for="client_email" class="text-sm font-medium">Adresse email *</label>
                                <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="floating-label">
                                    <input type="text" id="client_nom" name="client_nom" value="{{ old('client_nom') }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                                    <label for="client_nom" class="text-sm font-medium">Nom *</label>
                                </div>

                                <div class="floating-label">
                                    <input type="text" id="client_prenom" name="client_prenom" value="{{ old('client_prenom') }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                                    <label for="client_prenom" class="text-sm font-medium">Prénom *</label>
                                </div>
                            </div>

                            <div class="floating-label">
                                <input type="tel" id="client_telephone" name="client_telephone" value="{{ old('client_telephone') }}" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 placeholder-transparent" required>
                                <label for="client_telephone" class="text-sm font-medium">Téléphone *</label>
                                <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Demandes spéciales
                            </h3>
                            <textarea id="notes" name="notes" rows="4" class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-0 transition-all duration-200 text-gray-900 resize-none" placeholder="Lit supplémentaire, vue sur mer, régime alimentaire spécial...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('public.rooms') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Retour aux chambres</span>
                            </a>
                            <button type="submit" class="flex-1 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Confirmer la réservation</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto-fill dates from URL parameters if available
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const dateDebut = urlParams.get('date_debut');
            const dateFin = urlParams.get('date_fin');

            if (dateDebut) {
                document.getElementById('date_debut').value = dateDebut;
            }
            if (dateFin) {
                document.getElementById('date_fin').value = dateFin;
            }
        });

        // Update minimum departure date when arrival date changes
        document.getElementById('date_debut').addEventListener('change', function() {
            const dateFinInput = document.getElementById('date_fin');
            dateFinInput.min = this.value;
            if (dateFinInput.value && dateFinInput.value < this.value) {
                dateFinInput.value = this.value;
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réserver une Chambre - Hôtel Manager</title>
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
                            <span class="hidden md:block text-gray-700 dark:text-gray-300">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
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



    <!-- MAIN CONTENT -->
    <main class="pt-16 min-h-screen bg-gray-50 dark:bg-slate-900">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Réserver une Chambre</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Réservez votre chambre d'hôtel en quelques étapes simples.</p>
            </div>

            <!-- Reservation Form Container -->
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    @viteReactRefresh
                    @vite(['resources/js/app.jsx', 'resources/css/app.css'])
    <div id="root"></div>
    <script>
        // Pass dates to React component if available
        window.initialDates = {
            date_debut: '{{ request("date_debut") }}',
            date_fin: '{{ request("date_fin") }}'
        };
    </script>
                </div>
            </div>
        </div>
    </main>

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
