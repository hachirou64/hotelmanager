<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Hôtel Manager</title>
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
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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
<body class="bg-gray-50 dark:bg-slate-900">
    <!-- HEADER -->
    <header class="shadow-md bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo & Nom de l'App -->
                <a href="/" class="flex items-center space-x-3">
                     <!-- Icone d'Hôtel -->
                     <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path></svg>
                    <span class="text-2xl font-extrabold text-gray-900 dark:text-white">Hôtel Manager</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-4">
                    <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Accueil</a>
                    <a href="/#about" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">À propos</a>
                    <a href="/#features" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Fonctionnalités</a>
                    <a href="/#pricing" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Tarifs</a>
                    <a href="/#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Contact</a>
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
                    <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Accueil</a>
                    <a href="/#about" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">À propos</a>
                    <a href="/#features" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Fonctionnalités</a>
                    <a href="/#pricing" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Tarifs</a>
                    <a href="/#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition duration-200 text-sm font-medium">Contact</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- LOGIN FORM -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full animate-fade-in">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">
                    Bienvenue
                </h2>
                <p class="text-gray-300">
                    Connectez-vous à votre compte Hôtel Manager
                </p>
            </div>

            <!-- Form Container -->
            <div class="glass-effect rounded-2xl p-8 shadow-2xl">
                <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <!-- Email Field -->
                    <div class="floating-label">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-transparent focus:outline-none input-focus transition-all duration-200"
                               placeholder="Adresse email" value="{{ old('email') }}">
                        <label for="email" class="text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            Adresse email
                        </label>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="floating-label">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-transparent focus:outline-none input-focus transition-all duration-200"
                               placeholder="Mot de passe">
                        <label for="password" class="text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Mot de passe
                        </label>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                Se souvenir de moi
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                                Mot de passe oublié ?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Se connecter
                        </span>
                    </button>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <ul class="text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Nouveau sur Hôtel Manager ?
                        <a href="/register" class="font-semibold text-blue-600 hover:text-blue-500 transition duration-200">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-800 dark:bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center flex-wrap">
                <p class="text-sm">&copy; 2025 Hôtel Manager. Tous droits réservés.</p>
                <div class="flex space-x-4 text-sm">
                    <a href="/#contact" class="hover:text-primary transition">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
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
    </script>
</body>
</html>
