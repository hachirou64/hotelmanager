<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Paiement - Hôtel Manager')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-6 sm:px-10 sm:py-8 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary rounded flex items-center justify-center text-white font-bold">HM</div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Hôtel Manager</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Paiement sécurisé</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 10-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd"/></svg>
                            <span>Sécurisé · Paiement crypté</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    @yield('content')
                </div>
            </div>
            <p class="mt-4 text-center text-xs text-gray-500">Besoin d'aide ? Contactez le support à <a href="/contact" class="text-primary underline">contact@hotel.local</a></p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
