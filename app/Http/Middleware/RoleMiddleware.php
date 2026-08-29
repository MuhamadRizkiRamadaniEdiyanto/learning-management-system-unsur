<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, mixed ...$roles): Response
    {
        if (! $request->user()) {
            abort(403, 'Akses tidak diizinkan. Anda harus login terlebih dahulu.');
        }

        if (in_array($request->user()->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Akses tidak diizinkan. Halaman ini bukan untuk Anda.');
    }
}
