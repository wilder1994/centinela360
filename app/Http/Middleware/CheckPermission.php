<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'No tienes los permisos necesarios.');
        }

        return $next($request);
    }
}
