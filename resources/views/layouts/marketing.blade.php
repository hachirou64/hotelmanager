<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hôtel Manager')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS CDN & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #b45309;   /* amber-700 */
            --color-primary-dark: #92400e;
        }
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .text-primary { color: var(--color-primary); }
        .bg-primary { background-color: var(--color-primary); }
        .border-primary { border-color: var(--color-primary); }
        .hover\:bg-primary-dark:hover { background-color: var(--color-primary-dark); }
        .hover\:text-primary-dark:hover { color: var(--color-primary-dark); }

        /* Dark mode overrides */
        body.dark {
            background-color: #0f172a;
            color: #f8fafc;
        }
        .dark .bg-white-soft { background-color: #1e293b; }
        .dark .text-gray-900 { color: #f8fafc; }
        .dark .text-gray-600 { color: #94a3b8; }
        .dark .border-gray-200 { border-color: #334155; }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#b45309',
                            dark: '#92400e',
                        },
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                    },
                },
            },
        };

        function updateThemeIcon() {
            const desktopBtn = document.getElementById('theme-toggle');
            const mobileBtn = document.getElementById('theme-toggle-mobile');
            const isDark = document.body.classList.contains('dark');
            const iconHtml = isDark
                ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>'
                : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>';
            if (desktopBtn) desktopBtn.innerHTML = iconHtml;
            if (mobileBtn) mobileBtn.innerHTML = iconHtml;
        }

        function setupDarkMode() {
            const isDark = localStorage.getItem('theme') === 'dark' ||
                (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) document.body.classList.add('dark');
            else document.body.classList.remove('dark');
            updateThemeIcon();
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
            updateThemeIcon();
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const btn = document.getElementById('mobile-menu-button');
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
            } else {
                menu.classList.add('hidden');
                btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>';
            }
        }

        window.addEventListener('load', setupDarkMode);
    </script>
</head>
<body class="bg-gray-50">

<!-- HEADER (Navigation élégante) -->
<header class="shadow-sm bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"/>
                </svg>
                <span class="text-2xl font-serif font-extrabold text-gray-900 dark:text-white">Hôtel Plaza</span>
            </a>

            <!-- Desktop navigation -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="#rooms" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Chambres</a>
                <a href="#services" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Services</a>
                <a href="#gallery" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Galerie</a>
                <a href="#testimonials" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Avis</a>
                <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Contact</a>

                <button id="theme-toggle" onclick="toggleDarkMode()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700 transition-colors" aria-label="Dark mode">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <a href="/login" class="px-5 py-2.5 text-sm font-semibold rounded-full bg-primary text-white shadow-lg transition-all duration-300 hover:bg-primary-dark hover:shadow-xl transform hover:scale-[1.02]">
                    Connexion
                </a>
            </nav>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-primary transition-colors" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <!-- Mobile navigation -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-200 dark:border-gray-700">
            <nav class="flex flex-col space-y-3 pt-4">
                <a href="#rooms" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Chambres</a>
                <a href="#services" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Services</a>
                <a href="#gallery" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Galerie</a>
                <a href="#testimonials" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Avis</a>
                <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary transition-colors text-sm font-medium">Contact</a>

                <div class="flex items-center justify-between pt-2">
                    <button id="theme-toggle-mobile" onclick="toggleDarkMode()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700 transition-colors" aria-label="Dark mode">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <a href="/login" class="px-5 py-2.5 text-sm font-semibold rounded-full bg-primary text-white shadow-lg transition-all duration-300 hover:bg-primary-dark">
                        Connexion
                    </a>
                </div>
            </nav>
        </div>
    </div>
</header>

<!-- MAIN CONTENT (chaque page injecte son contenu ici) -->
<main>
    @yield('content')
</main>

<!-- FOOTER professionnel -->
<footer class="border-t border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-slate-900/50 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="text-lg font-serif font-bold text-gray-900 dark:text-white">Hôtel Plaza</div>
            <div class="text-gray-600 dark:text-gray-300">123 avenue des Hôtels, Bénin 229 01 69 16 21 07</div>
        </div>
        <div class="flex flex-wrap gap-4 text-sm">
            <a href="#rooms" class="text-gray-600 dark:text-gray-300 hover:text-primary">Chambres</a>
            <a href="#services" class="text-gray-600 dark:text-gray-300 hover:text-primary">Services</a>
            <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-primary">Contact</a>
            <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-primary">Mentions légales</a>
        </div>
    </div>
    <div class="text-center text-xs text-gray-500 pb-6">© {{ date('Y') }} Hôtel Plaza – Tous droits réservés.</div>
</footer>

</body>
</html>