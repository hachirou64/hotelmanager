<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (!$user->role) {
            abort(403, 'Accès non autorisé. Aucun rôle assigné.');
        }

        $allowedRoles = explode(',', $role);

        if (!in_array($user->role->nom_role, $allowedRoles)) {
            abort(403, 'Accès non autorisé. Vous devez être l\'un des rôles suivants : ' . $role . ' pour accéder à cette page.');
        }

        return $next($request);
    }
}
