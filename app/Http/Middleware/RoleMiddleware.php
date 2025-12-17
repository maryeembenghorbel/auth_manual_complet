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
        if (! $request->user() || $request->user()->role->name !== $role) {
            abort(403, 'Accès interdit');
        }
        return $next($request);
    }
}
