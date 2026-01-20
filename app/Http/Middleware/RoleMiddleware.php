<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Vérifie le rôle de l’utilisateur
     * Usage : ->middleware('role:Admin')
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Si l'utilisateur n'est pas connecté ou n'a pas le rôle requis
        if (! $request->user() || $request->user()->role->name !== $role) {
            abort(403, 'Accès interdit');
        }

        // Sinon, on laisse passer la requête
        return $next($request);
    }
}
