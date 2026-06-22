<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user saat ini cocok dengan role yang diizinkan di rute
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // 3. Jika rolenya tidak sesuai, tampilkan halaman error 403 (Akses Ditolak)
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}