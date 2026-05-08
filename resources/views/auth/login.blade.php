<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Hôtel Plaza</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#b45309',   // amber-700
                        'primary-dark': '#92400e',
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Inter', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .floating-label {
            position: relative;
        }
        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            transform: translateY(-1.6rem) scale(0.85);
            color: #b45309;
            background: white;
            padding: 0 0.25rem;
        }
        .floating-label label {
            position: absolute;
            left: 1rem;
            top: 0.85rem;
            transition: all 0.2s ease;
            pointer-events: none;
            color: #6b7280;
            background: white;
            padding: 0 0.25rem;
            font-size: 0.95rem;
        }
        .input-custom {
            border: 1.5px solid #e5e7eb;
            transition: all 0.2s;
        }
        .input-custom:focus {
            border-color: #b45309;
            box-shadow: 0 0 0 3px rgba(180, 83, 9, 0.1);
            outline: none;
        }
        .animate-fade-up {
            animation: fadeUp 0.5s ease-out;
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full animate-fade-up">
        <!-- Logo / hôtel -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur rounded-full mb-4 shadow-lg border border-white/30">
                <svg class="w-10 h-10 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"/>
                </svg>
            </div>
            <h1 class="font-serif text-4xl font-bold text-white drop-shadow-md">Hôtel Plaza</h1>
            <p class="text-amber-100 mt-2 text-sm tracking-wide">Connexion à votre espace</p>
        </div>

        <!-- Formulaire de connexion avec effet glass -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <!-- Champ email -->
                <div class="floating-label">
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="w-full px-4 py-4 text-gray-900 bg-white rounded-xl input-custom placeholder-transparent"
                           placeholder="Adresse email" value="{{ old('email') }}">
                    <label for="email" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        Adresse email
                    </label>
                </div>
                @error('email')
                    <p class="text-sm text-red-600 flex items-center gap-1 -mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror

                <!-- Champ mot de passe -->
                <div class="floating-label">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-4 py-4 text-gray-900 bg-white rounded-xl input-custom placeholder-transparent"
                           placeholder="Mot de passe">
                    <label for="password" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Mot de passe
                    </label>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 flex items-center gap-1 -mt-3">{{ $message }}</p>
                @enderror

                <!-- Options supplémentaires -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-700">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="#" class="text-primary hover:text-primary-dark transition">Mot de passe oublié ?</a>
                </div>

                <!-- Bouton de connexion -->
                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3.5 rounded-xl shadow-md transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Se connecter
                </button>

                <!-- Liens supplémentaires -->
                <div class="text-center text-gray-600">
                    Pas encore de compte ?
                    <a href="/register" class="text-primary font-semibold hover:underline">Créer un compte</a>
                </div>

                <!-- Affichage des erreurs générales -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>

        <!-- Petit texte de pied de page (légal) -->
        <p class="text-center text-white/70 text-xs mt-8">© {{ date('Y') }} Hôtel Plaza – Tous droits réservés.</p>
    </div>

    <!-- Aucun header ni footer externe n'est inclus -->
</body>
</html>