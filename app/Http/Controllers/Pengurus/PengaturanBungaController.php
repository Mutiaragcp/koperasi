<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use App\Models\PengaturanBungaPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan import Facade Auth

class PengaturanBungaController extends Controller
{
    /**
     * Tampilkan halaman pengaturan bunga pinjaman.
     * Hanya admin yang boleh akses (dicek juga di route middleware).
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        $pengaturanBunga = PengaturanBungaPinjaman::orderBy('tenor')->get();

        return view('pengurus.pengaturan.bunga-pinjaman', compact('pengaturanBunga'));
    }

    /**
     * Update seluruh pengaturan bunga sekaligus.
     */
    public function update(Request $request)
    {
        // Gunakan Auth::user() di sini juga
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengubah pengaturan ini.');
        }

        $request->validate([
            'bunga' => ['required', 'array'],
            'bunga.*' => ['required', 'numeric', 'min:0', 'max:99.99'],
        ], [
            'bunga.*.required' => 'Semua kolom bunga wajib diisi.',
            'bunga.*.numeric' => 'Bunga harus berupa angka.',
            'bunga.*.min' => 'Bunga tidak boleh negatif.',
        ]);

        foreach ($request->bunga as $tenor => $persen) {
            PengaturanBungaPinjaman::updateOrCreate(
                ['tenor' => $tenor],
                ['bunga_persen' => $persen]
            );
        }

        return redirect()
            ->route('pengaturan.bunga-pinjaman')
            ->with('success', 'Pengaturan bunga pinjaman berhasil diperbarui.');
    }
}