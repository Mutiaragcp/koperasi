<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\PengaturanBungaPinjaman; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanPinjamanController extends Controller
{
    public function index()
    {
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return redirect('/dashboard-anggota')->with('error', 'Profil anggota tidak ditemukan. Silakan hubungi admin.');
        }

        $totalSetor = Simpanan::where('anggota_id', $anggota->id)->where('jenis_transaksi', 'setor')->where('status', 'disetujui')->sum('jumlah');
        $totalTarik = Simpanan::where('anggota_id', $anggota->id)->where('jenis_transaksi', 'tarik')->where('status', 'disetujui')->sum('jumlah');
        $saldoSimpanan = $totalSetor - $totalTarik;

        $maxPinjaman = $saldoSimpanan * 3; 

        if ($maxPinjaman < 1000000) {
            $maxPinjaman = 1000000; 
        }

        // Ambil data pengaturan bunga dari database
        $pengaturanBunga = PengaturanBungaPinjaman::orderBy('tenor', 'asc')->get();

        return view('anggota.pinjaman.index', compact('maxPinjaman', 'pengaturanBunga'));
    }

    // Menyimpan pengajuan pinjaman dari anggota
    public function store(Request $request)
    {
        $anggota = Auth::user()->anggota;

        $totalSetor = Simpanan::where('anggota_id', $anggota->id)->where('jenis_transaksi', 'setor')->where('status', 'disetujui')->sum('jumlah');
        $totalTarik = Simpanan::where('anggota_id', $anggota->id)->where('jenis_transaksi', 'tarik')->where('status', 'disetujui')->sum('jumlah');
        $maxPinjaman = max(($totalSetor - $totalTarik) * 3, 1000000);

        $request->validate([
            'jumlah_pinjaman' => "required|numeric|min:500000|max:$maxPinjaman",
            'tenor'           => "required|exists:pengaturan_bunga_pinjamans,tenor", 
            'tujuan'          => "required|string|min:10",
        ]);

        $pengaturanBunga = PengaturanBungaPinjaman::where('tenor', $request->tenor)->first();

        $pinjamanAktif = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($pinjamanAktif) {
            return back()->with('error', 'Pengajuan gagal. Anda masih memiliki pinjaman yang aktif atau sedang menunggu persetujuan.');
        }

        $kasMasukSimpanan = DB::table('simpanans')
            ->where('status', 'disetujui')
            ->where('jenis_transaksi', 'setor')
            ->sum('jumlah');
            
        $kasKeluarTarik = DB::table('simpanans')
            ->where('status', 'disetujui')
            ->where('jenis_transaksi', 'tarik')
            ->sum('jumlah');
            
        $kasKeluarPinjaman = DB::table('pinjamans')
            ->whereIn('status', ['disetujui'])
            ->sum('jumlah_pinjaman');

        $totalKasKoperasi = $kasMasukSimpanan - ($kasKeluarTarik + $kasKeluarPinjaman);

        if ($request->jumlah_pinjaman > $totalKasKoperasi) {
            return back()->with('error', 'Mohon maaf, pengajuan dibatalkan. Dana kas koperasi saat ini sedang tidak mencukupi untuk mencairkan nominal tersebut. (Sisa Kas Koperasi: Rp ' . number_format($totalKasKoperasi, 0, ',', '.') . ')');
        }

        Pinjaman::create([
            'anggota_id'      => $anggota->id,
            'disetujui_oleh'  => null, 
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tenor'           => $request->tenor,
            'bunga'           => $pengaturanBunga->bunga_persen,
            'status'          => 'menunggu', 
            'tgl_pengajuan'   => Carbon::now()->toDateString(),
            'tgl_cair'        => null
        ]);

        return redirect('/anggota/pinjaman')->with('success', 'Berhasil! Pengajuan pinjaman Anda telah dikirim ke pengurus koperasi.');
    }
}