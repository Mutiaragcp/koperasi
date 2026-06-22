<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAnggota = Anggota::where('status', 'aktif')->count();
        
        $anggotaBaruMingguIni = Anggota::where('status', 'aktif')
            ->where('tgl_gabung', '>=', Carbon::now()->startOfWeek()->toDateString())
            ->count();

        $totalSetor = Simpanan::where('status', 'disetujui')->where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = Simpanan::where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->sum('jumlah');
        $totalSimpanan = $totalSetor - $totalTarik;

        $totalPinjaman = Pinjaman::where('status', 'disetujui')->sum('jumlah_pinjaman');
        $pinjamanPending = Pinjaman::where('status', 'menunggu')->count();

        $keuntunganAngsuran = DB::table('angsurans')
            ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
            ->where('angsurans.status', 'lunas')
            ->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

        $pendingCount = Simpanan::where('status', 'pending')->count();

        $transaksis = Simpanan::with(['anggota.user', 'jenis_simpanan'])
            ->latest()
            ->limit(5)
            ->get();

        $semuaMasuk = DB::table('transaksi_kas')->where('jenis', 'masuk')->sum('nominal');
        $semuaKeluar = DB::table('transaksi_kas')->where('jenis', 'keluar')->sum('nominal');
        $kasBersih = $semuaMasuk - $semuaKeluar;

        // Kirim semua variabel ke view dashboard
        return view('dashboard', compact(
            'totalAnggota',
            'anggotaBaruMingguIni',
            'totalSimpanan',
            'kasBersih',
            'totalPinjaman',
            'pinjamanPending',
            'keuntunganAngsuran',
            'pendingCount',
            'transaksis'
        ));
    }
}