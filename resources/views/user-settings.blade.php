<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paramètres Utilisateur - Hôtel Manager</title>
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
                    <a href="{{ route('rooms') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-md">
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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Paramètres Utilisateur</h1>
                <p class="text-gray-600 dark:text-gray-300">Personnalisez votre expérience utilisateur.</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- User Settings Form -->
            <form method="POST" action="{{ route('user-settings.update') }}" class="space-y-6">
                @csrf
                <!-- Theme Settings -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Thème</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input id="light-theme" name="theme" value="light" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600" {{ (auth()->user()->preferences['theme'] ?? 'auto') === 'light' ? 'checked' : '' }}>
                                <label for="light-theme" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Thème clair
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="dark-theme" name="theme" value="dark" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600" {{ (auth()->user()->preferences['theme'] ?? 'auto') === 'dark' ? 'checked' : '' }}>
                                <label for="dark-theme" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                        </svg>
                                        Thème sombre
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="auto-theme" name="theme" value="auto" type="radio" class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600" {{ (auth()->user()->preferences['theme'] ?? 'auto') === 'auto' ? 'checked' : '' }}>
                                <label for="auto-theme" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        Automatique (selon le système)
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Language Settings -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Langue</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Langue de l'interface</label>
                                <select id="language" name="language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-slate-700 dark:text-white">
                                    <option value="fr" {{ (auth()->user()->preferences['language'] ?? 'fr') === 'fr' ? 'selected' : '' }}>Français</option>
                                    <option value="en" {{ (auth()->user()->preferences['language'] ?? 'fr') === 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ (auth()->user()->preferences['language'] ?? 'fr') === 'es' ? 'selected' : '' }}>Español</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Notifications</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email-notifications" name="email_notifications" type="checkbox" value="1" class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600 rounded" {{ (auth()->user()->preferences['email_notifications'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email-notifications" class="font-medium text-gray-700 dark:text-gray-300">Notifications par email</label>
                                    <p class="text-gray-500 dark:text-gray-400">Recevoir des notifications par email pour les événements importants.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="push-notifications" name="push_notifications" type="checkbox" value="1" class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600 rounded" {{ (auth()->user()->preferences['push_notifications'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="push-notifications" class="font-medium text-gray-700 dark:text-gray-300">Notifications push</label>
                                    <p class="text-gray-500 dark:text-gray-400">Recevoir des notifications push dans le navigateur.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Sauvegarder les paramètres
                    </button>
                </div>
            </form>
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

        // Apply theme on radio button change
        document.querySelectorAll('input[name="theme"]').forEach(radio => {
            radio.addEventListener('change', function() {
                applyTheme(this.value);
            });
        });

        // Apply current theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = '{{ auth()->user()->preferences['theme'] ?? 'auto' }}';
            applyTheme(currentTheme);
        });
    </script>
</body>
</html>
