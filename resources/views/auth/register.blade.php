<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - Hôtel Manager</title>
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
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
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

    <!-- REGISTER FORM -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                    Créer votre compte
                </h2>
                <p class="mt-2 text-center text-sm text-gray-200">
                    Ou
                    <a href="/login" class="font-medium text-primary hover:text-primary-dark transition duration-200">
                        connectez-vous à votre compte existant
                    </a>
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="sr-only">Nom complet</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm bg-white/90 backdrop-blur-sm"
                           placeholder="Nom complet" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="sr-only">Adresse email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm bg-white/90 backdrop-blur-sm"
                           placeholder="Adresse email" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm bg-white/90 backdrop-blur-sm"
                           placeholder="Mot de passe">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="sr-only">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm bg-white/90 backdrop-blur-sm"
                           placeholder="Confirmer le mot de passe">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-200">
                        J'accepte les
                        <a href="#" class="font-medium text-primary hover:text-primary-dark transition duration-200">
                            conditions d'utilisation
                        </a>
                        et la
                        <a href="#" class="font-medium text-primary hover:text-primary-dark transition duration-200">
                            politique de confidentialité
                        </a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 transform hover:scale-[1.02]">
                        Créer mon compte
                    </button>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
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
