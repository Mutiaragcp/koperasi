<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;

class CekStatusAnggota
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan yang login adalah user dengan role 'anggota'
        if (Auth::check() && Auth::user()->role == 'anggota') {
            
            // Cari data profil anggotanya
            $anggota = Anggota::where('user_id', Auth::id())->first();

            // Jika datanya ketemu dan statusnya nonaktif
            if ($anggota && $anggota->status == 'nonaktif') {
                
                // Paksa keluar (logout) detik itu juga
                Auth::logout();
                
                // Hapus sesi agar bersih
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kembalikan ke halaman login dengan pesan error
                return redirect('/login')->with('error', 'Akses ditolak! Keanggotaan Anda di koperasi telah dinonaktifkan. Silakan hubungi pengurus.');
            }
        }

        // Jika dia admin/bendahara, atau anggota aktif, persilakan masuk
        return $next($request);
    }
}