<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('member')->check()) {
            return redirect()->route('member.login');
        }

        config(['gimnasio.id' => auth('member')->user()->gimnasio_id]);

        return $next($request);
    }
}
