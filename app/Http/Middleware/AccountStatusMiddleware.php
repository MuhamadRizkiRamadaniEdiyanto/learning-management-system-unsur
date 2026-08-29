<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountStatusMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$statuses): Response
    {
        if (! $request->user()) {
            abort(403, 'Akses tidak diizinkan. Anda harus login terlebih dahulu.');
        }

        $allowedStatuses = $statuses ?: ['aktif'];

        if (in_array($request->user()->status_akun, $allowedStatuses, true)) {
            return $next($request);
        }

        abort(403, 'Akun Anda sedang menunggu verifikasi admin.');
    }
}
