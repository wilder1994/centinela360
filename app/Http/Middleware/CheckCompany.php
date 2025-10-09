<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->company || !$user->company->active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'company' => 'Tu empresa no está activa o no tienes empresa asignada.',
            ]);
        }

        return $next($request);
    }
}
