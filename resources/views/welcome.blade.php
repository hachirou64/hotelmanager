<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôtel Manager | Accueil</title>
    <!-- Chargement de Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #2563eb; /* blue-600 */
        }
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        .text-primary { color: var(--color-primary); }
        .bg-primary { background-color: var(--color-primary); }
        .hover\:bg-primary-dark:hover { background-color: #1d4ed8; }
        
        /* Styles Dark Mode */
        body.dark {
            background-color: #0f172a; /* slate-900 */
            color: #f8fafc; /* slate-50 */
        }
        .dark .bg-white { background-color: #1e293b; /* slate-800 */ }
        .dark .text-gray-900 { color: #f8fafc; }
        .dark .text-gray-600 { color: #94a3b8; }
    </style>
    
    <script>
        // Configuration Tailwind pour le Dark Mode
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#2563eb',
                            dark: '#1d4ed8',
                        },
                    }
                }
            }
        }
        
        function updateThemeIcon() {
            const desktopButton = document.getElementById('theme-toggle');
            const mobileButton = document.getElementById('theme-toggle-mobile');
            if (document.body.classList.contains('dark')) {
                const moonIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>';
                if (desktopButton) desktopButton.innerHTML = moonIcon;
                if (mobileButton) mobileButton.innerHTML = moonIcon;
            } else {
                const sunIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>';
                if (desktopButton) desktopButton.innerHTML = sunIcon;
                if (mobileButton) mobileButton.innerHTML = sunIcon;
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            const isOpen = !menu.classList.contains('hidden');

            if (isOpen) {
                menu.classList.add('hidden');
                button.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>';
            } else {
                menu.classList.remove('hidden');
                button.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }
        }

        function setupDarkMode() {
            const isDark = localStorage.getItem('theme') === 'dark' ||
                           (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
            updateThemeIcon();
        }

        function toggleDarkMode() {
            if (document.body.classList.contains('dark')) {
                document.body.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.body.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcon();
        }

        window.onload = setupDarkMode;
    </script>
</head>
<body class="bg-gray-50">

    <!-- HEADER (Navigation Complète) -->
    <header class="shadow-md bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo & Nom de l'App -->
                <a href="#" class="flex items-center space-x-3">
                     <!-- Icone d'Hôtel -->
                     <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path></svg>
                    <span class="text-2xl font-extrabold text-gray-900 dark:text-white">Hôtel Manager</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-4">
                    <!-- Liens de Navigation Principaux AJOUTÉS -->
                    <!-- Utiliser une route nommée ou une URL statique pour Laravel -->
                    <a href="#about" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">À propos</a>
                    <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Fonctionnalités</a>
                    <a href="#pricing" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Tarifs</a>
                    <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Contact</a>

                    <!-- Toggle Dark Mode -->
                    <button id="theme-toggle" onclick="toggleDarkMode()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700 transition-colors duration-200" aria-label="Toggle Dark Mode">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Bouton "Connexion" -->
                    <!-- En Laravel, vous utiliseriez la directive {{ route('login') }} ou un chemin statique si non configuré -->
                    <a href="/login" class="px-5 py-2.5 text-sm font-semibold rounded-full bg-primary text-white shadow-lg transition-all duration-300 hover:bg-primary-dark hover:shadow-xl transform hover:scale-[1.02]">
                        Connexion
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-200 dark:border-gray-700">
                <nav class="flex flex-col space-y-3 pt-4">
                    <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">À propos</a>
                    <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Fonctionnalités</a>
                    <a href="#pricing" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Tarifs</a>
                    <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Contact</a>

                    <div class="flex items-center justify-between pt-2">
                        <!-- Toggle Dark Mode Mobile -->
                        <button id="theme-toggle-mobile" onclick="toggleDarkMode()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700 transition-colors duration-200" aria-label="Toggle Dark Mode">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </button>

                        <!-- Bouton "Connexion" Mobile -->
                        <a href="/login" class="px-5 py-2.5 text-sm font-semibold rounded-full bg-primary text-white shadow-lg transition-all duration-300 hover:bg-primary-dark hover:shadow-xl transform hover:scale-[1.02]">
                            Connexion
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900" style="background-image: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm font-medium mb-8">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Solution PMS Complète
                </div>

                <!-- Main Headline -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight text-white mb-8">
                    Gestion Hôtelière
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">
                        Intelligente
                    </span>
                </h1>

                <!-- Subheadline -->
                <p class="max-w-3xl mx-auto text-xl sm:text-2xl text-gray-300 mb-12 leading-relaxed">
                    Révolutionnez la gestion de votre hôtel avec une plateforme moderne qui centralise réservations, clients, facturation et personnel en une interface intuitive et puissante. Réservez une chambre en quelques clics, sans inscription préalable.
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-2xl mx-auto mb-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">99.9%</div>
                        <div class="text-sm text-gray-400">Disponibilité</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">50+</div>
                        <div class="text-sm text-gray-400">Hôtels Clients</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-2">24/7</div>
                        <div class="text-sm text-gray-400">Support</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.rooms') }}" class="group inline-flex items-center px-8 py-4 text-lg font-semibold rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                        </svg>
                        Découvrir les Chambres
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('public.rooms') }}" class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-xl border-2 border-white/30 text-white hover:bg-white/10 hover:border-white/50 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Commencer Gratuitement
                    </a>
                </div>


            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="features" class="py-24 bg-white dark:bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Fonctionnalités Puissantes
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Tout ce dont vous avez besoin pour gérer votre hôtel efficacement, dans une interface moderne et intuitive.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Réservations -->
                <div class="group relative bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-700 dark:to-slate-600 p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-blue-100 dark:border-slate-600">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Gestion des Réservations</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Calendrier visuel interactif avec attribution automatique des chambres et gestion intelligente des conflits.</p>
                    </div>
                </div>

                <!-- Feature 2: Clients -->
                <div class="group relative bg-gradient-to-br from-green-50 to-emerald-100 dark:from-slate-700 dark:to-slate-600 p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-green-100 dark:border-slate-600">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">CRM Intégré</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Base de données clients complète avec historique des séjours, préférences et programme de fidélité intégré.</p>
                    </div>
                </div>

                <!-- Feature 3: Facturation -->
                <div class="group relative bg-gradient-to-br from-purple-50 to-violet-100 dark:from-slate-700 dark:to-slate-600 p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-purple-100 dark:border-slate-600">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-violet-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Facturation Automatisée</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Génération automatique de factures, suivi des paiements et intégrations comptables pour une gestion financière simplifiée.</p>
                    </div>
                </div>

                <!-- Feature 4: Analytics -->
                <div class="group relative bg-gradient-to-br from-orange-50 to-red-100 dark:from-slate-700 dark:to-slate-600 p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-orange-100 dark:border-slate-600">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Tableaux de Bord</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Analyses en temps réel avec KPIs essentiels : taux d'occupation, RevPAR, revenus et tendances de performance.</p>
                    </div>
                </div>

                <!-- Feature 5: Personnel -->
                <div class="group relative bg-gradient-to-br from-cyan-50 to-blue-100 dark:from-slate-700 dark:to-slate-600 p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-cyan-100 dark:border-slate-600">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Gestion du Personnel</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Planification des équipes, suivi des tâches de nettoyage et gestion des droits d'accès pour une organisation optimale.</p>
                    </div>
                </div>

                <!-- Feature 6: Intégrations -->
                <div class="group relative bg-gradient-to-br from-gray-50 to-slate-100 dark:from-slate-700 dark:to-slate-600 p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-slate-600">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-500/5 to-slate-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-r from-gray-600 to-slate-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">API & Intégrations</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Connectez votre PMS avec des OTAs, systèmes de paiement et autres outils hôteliers pour une automatisation complète.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FEATURE SECTION (Détail des 5 modules) -->
    <section id="features" class="py-16 bg-white dark:bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-16">Un outil pour chaque besoin de votre hôtel</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Feature 1: Réservations (Planning) -->
                <div class="p-6 bg-gray-50 dark:bg-slate-900 rounded-xl shadow-lg border-t-4 border-primary hover:shadow-2xl transition duration-300">
                    <div class="w-12 h-12 mb-4 p-2 rounded-full bg-primary/20 text-primary flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 4h.01M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Module Réservations</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Gérez toutes les arrivées, départs et séjours sur un planning visuel (calendrier React) ultra-rapide. Attributions de chambre simplifiées et gestion des surbookings.</p>
                </div>
                
                <!-- Feature 2: Gestion Client -->
                <div class="p-6 bg-gray-50 dark:bg-slate-900 rounded-xl shadow-lg border-t-4 border-green-500 hover:shadow-2xl transition duration-300">
                    <div class="w-12 h-12 mb-4 p-2 rounded-full bg-green-500/20 text-green-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-2m2 0h-2M13 18H5V3a2 2 0 00-2 2v13a2 2 0 002 2h8m0 0h3"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Fichiers Clients (CRM)</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Base de données clients centralisée. Historique des séjours, informations de contact et préférences pour un service personnalisé.</p>
                </div>

                <!-- Feature 3: Facturation & Paiements -->
                <div class="p-6 bg-gray-50 dark:bg-slate-900 rounded-xl shadow-lg border-t-4 border-yellow-500 hover:shadow-2xl transition duration-300">
                    <div class="w-12 h-12 mb-4 p-2 rounded-full bg-yellow-500/20 text-yellow-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Gestion Facturation</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Génération automatique des factures, suivi des acomptes et des paiements en attente, intégration comptable simplifiée.</p>
                </div>
                
                <!-- Feature 4: Reporting et Analyse -->
                <div class="p-6 bg-gray-50 dark:bg-slate-900 rounded-xl shadow-lg border-t-4 border-purple-500 hover:shadow-2xl transition duration-300">
                    <div class="w-12 h-12 mb-4 p-2 rounded-full bg-purple-500/20 text-purple-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m4 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tableaux de Bord & Rapports</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Accédez à des analyses de performance claires : taux d'occupation, RevPAR, ADR, et tendances des revenus pour des décisions éclairées.</p>
                </div>
                
                <!-- Feature 5: Gestion du Personnel -->
                <div class="p-6 bg-gray-50 dark:bg-slate-900 rounded-xl shadow-lg border-t-4 border-red-500 hover:shadow-2xl transition duration-300">
                    <div class="w-12 h-12 mb-4 p-2 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20h-2m2 0h-2M13 18H5V3a2 2 0 00-2 2v13a2 2 0 002 2h8m0 0h3M12 12a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Administration du Personnel</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Gestion des droits d'accès des employés, planification des tâches de ménage et suivi des performances des équipes.</p>
                </div>
                
                <!-- Reste de l'espace sur les grands écrans -->
                <div class="hidden lg:block"></div> 
            </div>
        </div>
    </section>
    
    <!-- PRICING SECTION -->
    <section id="pricing" class="py-16 bg-gray-50 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-16">Tarifs et Plans</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Plan Basic -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg border-t-4 border-blue-500">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Basic</h3>
                    <p class="text-4xl font-bold text-blue-600 mb-6">€29<span class="text-lg font-normal">/mois</span></p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-400 mb-8">
                        <li>✓ Gestion des réservations</li>
                        <li>✓ Fichiers clients (jusqu'à 100)</li>
                        <li>✓ Facturation simple</li>
                        <li>✓ Support email</li>
                    </ul>
                    <a href="/register" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Commencer</a>
                </div>
                <!-- Plan Pro -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg border-t-4 border-green-500 relative">
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Populaire</div>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Pro</h3>
                    <p class="text-4xl font-bold text-green-600 mb-6">€79<span class="text-lg font-normal">/mois</span></p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-400 mb-8">
                        <li>✓ Tout du plan Basic</li>
                        <li>✓ Clients illimités</li>
                        <li>✓ Rapports avancés</li>
                        <li>✓ Gestion du personnel</li>
                        <li>✓ Support prioritaire</li>
                    </ul>
                    <a href="/register" class="block w-full bg-green-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-green-700 transition">Commencer</a>
                </div>
                <!-- Plan Enterprise -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg border-t-4 border-purple-500">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Enterprise</h3>
                    <p class="text-4xl font-bold text-purple-600 mb-6">€199<span class="text-lg font-normal">/mois</span></p>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-400 mb-8">
                        <li>✓ Tout du plan Pro</li>
                        <li>✓ Intégrations API</li>
                        <li>✓ Formation personnalisée</li>
                        <li>✓ Support 24/7</li>
                        <li>✓ Hébergement dédié</li>
                    </ul>
                    <a href="/register" class="block w-full bg-purple-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-purple-700 transition">Contactez-nous</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact" class="py-16 bg-white dark:bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-16">Contactez-nous</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informations de contact</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-gray-600 dark:text-gray-400">contact@hotelmanager.com</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-gray-600 dark:text-gray-400">+33 1 23 45 67 89</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-gray-600 dark:text-gray-400">123 Avenue des Hôtels, Paris, France</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Envoyez-nous un message</h3>
                    <form class="space-y-4" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div>
                            <input type="text" name="name" placeholder="Votre nom" value="{{ old('name') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white">
                            @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="email" name="email" placeholder="Votre email" value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white">
                            @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <textarea name="message" rows="4" placeholder="Votre message" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-dark transition">Envoyer</button>
                    </form>

                    @if(session('success'))
                        <p class="mt-4 text-green-600 font-semibold">{{ session('success') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-800 dark:bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center flex-wrap">
                <p class="text-sm">&copy; 2025 Hôtel Manager. Tous droits réservés.</p>
                <div class="flex space-x-4 text-sm">
                    <a href="#" class="hover:text-primary transition">Confidentialité</a>
                    <a href="#" class="hover:text-primary transition">Conditions</a>
                    <a href="#contact" class="hover:text-primary transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>