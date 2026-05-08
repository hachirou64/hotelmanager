<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - Hôtel Plaza</title>
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
            <p class="text-amber-100 mt-2 text-sm tracking-wide">Créez votre espace personnel</p>
        </div>

        <!-- Formulaire d'inscription avec effet glass -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf

                <!-- Nom complet -->
                <div class="floating-label">
                    <input id="name" name="name" type="text" autocomplete="name" required
                           class="w-full px-4 py-4 text-gray-900 bg-white rounded-xl input-custom placeholder-transparent"
                           placeholder="Nom complet" value="{{ old('name') }}">
                    <label for="name" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Nom complet
                    </label>
                </div>
                @error('name')
                    <p class="text-sm text-red-600 flex items-center gap-1 -mt-3">{{ $message }}</p>
                @enderror

                <!-- Email -->
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
                    <p class="text-sm text-red-600 flex items-center gap-1 -mt-3">{{ $message }}</p>
                @enderror

                <!-- Mot de passe -->
                <div class="floating-label">
                    <input id="password" name="password" type="password" autocomplete="new-password" required
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

                <!-- Confirmation mot de passe -->
                <div class="floating-label">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="w-full px-4 py-4 text-gray-900 bg-white rounded-xl input-custom placeholder-transparent"
                           placeholder="Confirmer le mot de passe">
                    <label for="password_confirmation" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Confirmer le mot de passe
                    </label>
                </div>
                @error('password_confirmation')
                    <p class="text-sm text-red-600 flex items-center gap-1 -mt-3">{{ $message }}</p>
                @enderror

                <!-- Conditions d'utilisation -->
                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        J'accepte les
                        <a href="#" class="font-semibold text-primary hover:text-primary-dark transition">conditions d'utilisation</a>
                        et la
                        <a href="#" class="font-semibold text-primary hover:text-primary-dark transition">politique de confidentialité</a>
                    </label>
                </div>

                <!-- Bouton d'inscription -->
                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3.5 rounded-xl shadow-md transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Créer mon compte
                </button>

                <!-- Lien vers connexion -->
                <div class="text-center text-gray-600">
                    Déjà un compte ?
                    <a href="/login" class="text-primary font-semibold hover:underline">Se connecter</a>
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

        <!-- Petit texte de pied de page -->
        <p class="text-center text-white/70 text-xs mt-8">© {{ date('Y') }} Hôtel Plaza – Tous droits réservés.</p>
    </div>

</body>
</html>