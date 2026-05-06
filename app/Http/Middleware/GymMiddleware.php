<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GymMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('gym')->check()) {
            return redirect()->route('gym.login');
        }

        config(['gimnasio.id' => auth('gym')->user()->gimnasio_id]);

        return $next($request);
    }
}
