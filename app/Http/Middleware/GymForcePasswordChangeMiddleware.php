<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GymForcePasswordChangeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('gym')->user();

        if ($user && $user->must_change_password) {
            return redirect()->route('gym.password.change')
                ->with('warning', 'Debes cambiar tu contraseña antes de continuar.');
        }

        return $next($request);
    }
}
