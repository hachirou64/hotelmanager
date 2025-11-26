<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Hôtel Manager')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white min-h-screen">
    <header class="bg-white dark:bg-slate-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Hôtel Manager</a>
                </div>
                <nav class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Tableau de bord</a>
                    <a href="{{ route('reservations') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Réservations</a>
                    <a href="{{ route('rooms') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Chambres</a>
                    <a href="{{ route('clients') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Clients</a>
                    <a href="{{ route('billing') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Facturation</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="py-8">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="p-3 bg-blue-100 text-blue-800 rounded">{{ session('info') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-12 py-6 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500 dark:text-gray-400">© {{ date('Y') }} Hôtel Manager</div>
    </footer>

    @stack('scripts')
</body>
</html>
