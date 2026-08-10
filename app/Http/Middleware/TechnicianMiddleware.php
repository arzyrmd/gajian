<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TechnicianMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_admin) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('monitoring.index');
        }

        abort(403, 'Aksi tidak diizinkan. Hanya untuk Teknisi.');
    }
}
