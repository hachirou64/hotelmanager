<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user->role) {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'role' => 'Accès non autorisé. Aucun rôle assigné. Veuillez contacter l\'administrateur.',
                ]);
            }

            $roleName = $user->role->nom_role;

            if (in_array($roleName, ['Admin', 'Manager', 'Staff'])) {
                return redirect()->intended('/dashboard');
            } elseif ($roleName === 'Client') {
                return redirect()->intended('/client-reservations');
            } else {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'role' => 'Accès non autorisé. Rôle non pris en charge.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        // Ensure the Client role exists, create it if not
        $clientRole = \App\Models\Role::firstOrCreate(
            ['nom_role' => 'Client'],
            ['nom_role' => 'Client']
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $clientRole->id_role,
            'preferences' => json_encode(['theme' => 'light']),
        ]);

        Auth::login($user);

        return redirect('/public-rooms')->with('success', 'Compte créé avec succès! Bienvenue sur Hôtel Manager.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
