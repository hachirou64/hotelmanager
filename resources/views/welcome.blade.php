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
                    <a href="#about" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">À propos</a>
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

    <!-- ABOUT SECTION -->
    <section id="about" class="py-16 sm:py-24 lg:py-32 bg-gray-50 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Titre Principal -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
                Le Logiciel de Gestion Hôtelière (PMS) <span class="text-primary">Complet</span>
            </h1>

            <!-- Sous-titre descriptif -->
            <p class="max-w-3xl mx-auto text-xl text-gray-600 dark:text-gray-300 mb-8">
                Centralisez la planification, la facturation, la gestion client et le personnel dans une interface unique, moderne et ultra-rapide, développée avec Laravel et React.
            </p>

            <!-- Description détaillée -->
            <div class="max-w-4xl mx-auto space-y-6 text-gray-600 dark:text-gray-300 mb-12">
                <p class="text-lg">
                    <strong>Hôtel Manager</strong> est une solution complète de Property Management System (PMS) conçue spécifiquement pour les hôtels de toutes tailles. Notre plateforme intègre tous les aspects essentiels de la gestion hôtelière :
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
                        <div class="text-primary mb-3">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Gestion des réservations</h3>
                        <p class="text-sm">Calendrier visuel interactif pour une attribution optimale des chambres</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
                        <div class="text-primary mb-3">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">CRM intégré</h3>
                        <p class="text-sm">Base de données clients centralisée avec historique complet</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
                        <div class="text-primary mb-3">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Facturation automatique</h3>
                        <p class="text-sm">Génération de factures et suivi des paiements en temps réel</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
                        <div class="text-primary mb-3">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Tableaux de bord</h3>
                        <p class="text-sm">Analyses et rapports pour optimiser vos performances</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
                        <div class="text-primary mb-3">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Gestion du personnel</h3>
                        <p class="text-sm">Planification des tâches et suivi des équipes</p>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md">
                        <div class="text-primary mb-3">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Technologies modernes</h3>
                        <p class="text-sm">Laravel backend + React frontend pour des performances optimales</p>
                    </div>
                </div>

                <p class="text-lg max-w-3xl mx-auto">
                    Développée avec les technologies les plus modernes, Hôtel Manager offre une expérience utilisateur fluide et des performances exceptionnelles pour répondre aux besoins des hôtels contemporains.
                </p>
            </div>

            <!-- Call to Action -->
            <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                 <!-- Utilisation de chemins statiques pour l'exemple Blade -->
                <a href="/login" class="px-8 py-4 text-lg font-bold rounded-full bg-primary text-white shadow-xl transition-all duration-300 hover:bg-primary-dark transform hover:scale-105 text-center">
                    Accéder au Tableau de Bord
                </a>
                <a href="/register" class="px-8 py-4 text-lg font-bold rounded-full border-2 border-primary text-primary bg-white dark:bg-slate-800 transition-all duration-300 hover:bg-primary hover:text-white transform hover:scale-105 text-center">
                    Créer un Compte
                </a>
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
                    <form class="space-y-4">
                        <div>
                            <input type="text" placeholder="Votre nom" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white">
                        </div>
                        <div>
                            <input type="email" placeholder="Votre email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white">
                        </div>
                        <div>
                            <textarea rows="4" placeholder="Votre message" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-700 dark:text-white"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary-dark transition">Envoyer</button>
                    </form>
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