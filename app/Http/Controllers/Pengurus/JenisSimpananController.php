<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\JenisSimpanan;

class JenisSimpananController extends Controller
{
    public function index()
    {
        $jenisSimpanans = JenisSimpanan::all();
        return view('pengurus.jenis_simpanan.index', compact('jenisSimpanans'));
    }

    public function create()
    {
        return view('pengurus.jenis_simpanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_simpanans,nama',
            'keterangan' => 'nullable|string'
        ]);

        JenisSimpanan::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/jenis-simpanan')->with('success', 'Jenis simpanan baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jenis = JenisSimpanan::findOrFail($id);
        return view('pengurus.jenis_simpanan.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisSimpanan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_simpanans,nama,' . $id,
            'keterangan' => 'nullable|string'
        ]);

        $jenis->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/jenis-simpanan')->with('success', 'Data jenis simpanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            JenisSimpanan::destroy($id);
            return redirect('/jenis-simpanan')->with('success', 'Jenis simpanan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect('/jenis-simpanan')->with('error', 'Gagal menghapus! Pastikan jenis simpanan ini tidak sedang digunakan oleh data transaksi.');
        }
    }
}