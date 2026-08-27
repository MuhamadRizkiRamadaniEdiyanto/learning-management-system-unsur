<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 1. Ambil data role user yang sedang login
        $role = $request->user()->role;

        // 2. Arahkan berdasarkan role masing-masing
        if ($role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($role === 'dosen') {
            return redirect()->intended(route('dosen.dashboard', absolute: false));
        } elseif ($role === 'mahasiswa') {
            return redirect()->intended(route('mahasiswa.dashboard', absolute: false));
        }

        // 3. Fallback jika role tidak dikenali (opsional)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
