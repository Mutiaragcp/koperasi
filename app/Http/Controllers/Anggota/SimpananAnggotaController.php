<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SimpananAnggotaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $anggota = DB::table('anggotas')->where('user_id', $user->id)->first();

        if (!$anggota) {
            abort(404, 'Data anggota tidak ditemukan.');
        }

        $anggotaId = $anggota->id;

        $simpPokok = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Pokok%')
            ->sum('simpanans.jumlah');

        $simpWajib = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Wajib%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        $setorSukarela = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Sukarela%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        $tarikSukarela = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Sukarela%')
            ->where('simpanans.jenis_transaksi', 'tarik')
            ->sum('simpanans.jumlah');

        $simpSukarela = $setorSukarela - $tarikSukarela;
        $totalSimpanan = $simpPokok + $simpWajib + $simpSukarela;
        
        $pengaturanShu = DB::table('pengaturan_shus')->first();
        $persenAnggota = $pengaturanShu ? ($pengaturanShu->persen_anggota / 100) : 0.40;
        $estimasiShu = $totalSimpanan * 0.10 * $persenAnggota; 

        // Ambil Semua Riwayat Mutasi
        $riwayat = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->select('simpanans.*', 'jenis_simpanans.nama as jenis_nama')
            ->where('simpanans.anggota_id', $anggotaId)
            ->orderBy('simpanans.created_at', 'desc')
            ->get();

        return view('anggota.simpanan.index', compact(
            'totalSimpanan', 'simpPokok', 'simpWajib', 'simpSukarela', 'estimasiShu', 'riwayat'
        ));
    }

    public function cetakRekeningKoran()
    {
        $user = Auth::user();
        $anggota = DB::table('anggotas')->where('user_id', $user->id)->first();

        if (!$anggota) {
            abort(404, 'Data anggota tidak ditemukan.');
        }

        $anggotaId = $anggota->id;

        $simpPokok = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Pokok%')
            ->sum('simpanans.jumlah');

        $simpWajib = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Wajib%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        $setorSukarela = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Sukarela%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        $tarikSukarela = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('simpanans.status', 'disetujui')
            ->where('jenis_simpanans.nama', 'like', '%Sukarela%')
            ->where('simpanans.jenis_transaksi', 'tarik')
            ->sum('simpanans.jumlah');

        $simpSukarela = $setorSukarela - $tarikSukarela;
        $totalSimpanan = $simpPokok + $simpWajib + $simpSukarela;

        $riwayat = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->select('simpanans.*', 'jenis_simpanans.nama as jenis_nama')
            ->where('simpanans.anggota_id', $anggotaId)
            ->orderBy('simpanans.created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('anggota.simpanan.pdf', compact('user', 'anggota', 'riwayat', 'totalSimpanan'));
        
        return $pdf->download('Rekening_Koran_'.$user->name.'.pdf');
    }

    public function setor()
    {
        $jenisSimpanan = DB::table('jenis_simpanans')->get();
        return view('anggota.simpanan.setor', compact('jenisSimpanan'));
    }

    public function prosesSetor(Request $request)
    {
        $request->validate([
            'jenis_simpanan_id' => 'required',
            'jumlah'            => 'required|numeric|min:10000',
            'bukti_transaksi'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $anggota = DB::table('anggotas')->where('user_id', $user->id)->first();

        if (!$anggota) {
            return redirect()->back()->withErrors(['error' => 'Data profil anggota belum lengkap.']);
        }

        $namaFile = null;
        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $namaFile = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('uploads/simpanan', $namaFile, 'public');
        }

        DB::table('simpanans')->insert([
            'anggota_id'        => $anggota->id,
            'jenis_simpanan_id' => $request->jenis_simpanan_id,
            'jenis_transaksi'   => 'setor',
            'jumlah'            => $request->jumlah,
            'bukti_transaksi'   => $namaFile,
            'status'            => 'pending',
            'tanggal'           => date('Y-m-d'), 
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect('/anggota/simpanan')->with('success', 'Pengajuan setoran mandiri berhasil dikirim! Menunggu verifikasi admin.');
    }
}