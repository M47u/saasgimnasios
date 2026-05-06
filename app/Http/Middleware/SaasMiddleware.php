<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaasMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('saas')->check()) {
            return redirect()->route('saas.login');
        }

        return $next($request);
    }
}
