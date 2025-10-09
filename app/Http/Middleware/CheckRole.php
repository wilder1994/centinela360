<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Si el middleware no tiene rol definido
        if (!$role) {
            abort(403, 'No se definió un rol en el middleware.');
        }

        // Normalizar nombres (sin espacios y en minúsculas)
        $role = strtolower(trim($role));

        // Obtener roles del usuario normalizados
        $userRoles = $user->roles->pluck('name')->map(function ($r) {
            return strtolower(trim($r));
        })->toArray();

        // Si no tiene el rol
        if (!in_array($role, $userRoles, true)) {
            // 🔎 Ayuda para debug temporal
            abort(403, 'Sin permiso de acceso. Rol requerido: '.$role.' Roles del usuario: '.implode(', ', $userRoles));
            abort(403, 'Sin permiso de acceso');
        }

        return $next($request);
    }
}
