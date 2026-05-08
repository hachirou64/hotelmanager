<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Hôtel Manager')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Chargement de Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Raffinement des teintes primary : teal moderne */
            --color-primary: #0ea5a4; /* teal-500 */
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
                            DEFAULT: '#0ea5a4', /* teal-500 */
                            dark: '#0b7285',
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
<body class="antialiased bg-gray-50 dark:bg-slate-900">
    <header class="bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary rounded flex items-center justify-center text-white font-bold">HM</div>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Hôtel Manager</span>
                </a>

                <nav class="space-x-4 text-sm">
                    <a href="/public-rooms" class="text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary">Chambres</a>
                    <a href="{{ route('docs') }}" class="text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary">Documentation</a>
                    <a href="{{ route('blog') }}" class="text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary">Blog</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary">Contact</a>
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" onclick="toggleDarkMode()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700 transition-colors duration-200" aria-label="Toggle Dark Mode">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                    <a href="{{ route('login') }}" class="ml-4 px-3 py-1 bg-primary text-white rounded hover:bg-primary-dark">Connexion</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4">
                    <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
