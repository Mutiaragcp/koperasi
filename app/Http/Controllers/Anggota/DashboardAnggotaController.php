<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAnggotaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Asumsi: tabel anggotas punya kolom 'user_id'
        $anggota = DB::table('anggotas')->where('user_id', $user->id)->first();

        if (!$anggota) {
            abort(404, 'Data keanggotaan tidak ditemukan.');
        }

        $anggotaId = $anggota->id;

        $simpPokok = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('jenis_simpanans.nama', 'like', '%Pokok%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        $simpWajib = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('jenis_simpanans.nama', 'like', '%Wajib%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        // Untuk sukarela biasanya bisa setor dan tarik
        $setorSukarela = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('jenis_simpanans.nama', 'like', '%Sukarela%')
            ->where('simpanans.jenis_transaksi', 'setor')
            ->sum('simpanans.jumlah');

        $tarikSukarela = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->where('simpanans.anggota_id', $anggotaId)
            ->where('jenis_simpanans.nama', 'like', '%Sukarela%')
            ->where('simpanans.jenis_transaksi', 'tarik')
            ->sum('simpanans.jumlah');

        $simpSukarela = $setorSukarela - $tarikSukarela;
        $totalSimpanan = $simpPokok + $simpWajib + $simpSukarela;

        $pinjamanAktif = DB::table('pinjamans')
            ->where('anggota_id', $anggotaId)
            ->where('status', 'disetujui') // Status yang benar di database adalah 'disetujui'
            ->first();

        $sisaPinjaman = 0;
        $progressBulan = 0;
        $totalBulan = 0;
        $persenProgress = 0;
        $jatuhTempoNext = null;

        if ($pinjamanAktif) {
            // Hitung sisa pinjaman dari total angsuran yang belum lunas
            $sisaPinjaman = DB::table('angsurans')
                ->where('pinjaman_id', $pinjamanAktif->id)
                ->where('status', '!=', 'lunas')
                ->sum('jumlah_bayar');
                
            $totalBulan = $pinjamanAktif->tenor ?? 12; // Kolom yang benar adalah 'tenor'
            
            $progressBulan = DB::table('angsurans')
                ->where('pinjaman_id', $pinjamanAktif->id)
                ->where('status', 'lunas')
                ->count();

            // Hitung persentase bar
            if ($totalBulan > 0) {
                $persenProgress = round(($progressBulan / $totalBulan) * 100);
            }

            $angsuranSelanjutnya = DB::table('angsurans')
                ->where('pinjaman_id', $pinjamanAktif->id)
                ->whereIn('status', ['belum_bayar', 'telat', 'menunggu_verifikasi', 'ditolak'])
                ->orderBy('tgl_jatuh_tempo', 'asc') 
                ->first();
            
            if ($angsuranSelanjutnya) {
                $jatuhTempoNext = $angsuranSelanjutnya->tgl_jatuh_tempo;
            }
        }

        // Ambil persentase SHU bagian anggota dari database (tabel pengaturan_shus)
        $pengaturanShu = DB::table('pengaturan_shus')->first();
        $persenAnggota = $pengaturanShu ? ($pengaturanShu->persen_anggota / 100) : 0.40; // Default 40% jika belum diatur

        // Contoh kalkulasi estimasi SHU dinamis: Total Simpanan x Asumsi Keuntungan Koperasi (misal 10%) x Persentase Hak Anggota
        $estimasiShu = $totalSimpanan * 0.10 * $persenAnggota;

        $riwayatTransaksi = DB::table('simpanans')
            ->join('jenis_simpanans', 'simpanans.jenis_simpanan_id', '=', 'jenis_simpanans.id')
            ->select(
                'simpanans.created_at as tanggal',          // Jadi $trx->tanggal
                'simpanans.jenis_transaksi as jenis',       // Jadi $trx->jenis (setor/tarik)
                'simpanans.jumlah as nominal',              // Jadi $trx->nominal
                'jenis_simpanans.nama as keterangan'
            )
            ->where('simpanans.anggota_id', $anggotaId)
            ->orderBy('simpanans.created_at', 'desc')
            ->limit(5) // Ambil 5 riwayat terbaru saja
            ->get();

        return view('anggota.dashboard', compact(
            'user',
            'anggota',
            'totalSimpanan',
            'simpPokok',
            'simpWajib',
            'simpSukarela',
            'pinjamanAktif',
            'sisaPinjaman',
            'progressBulan',
            'totalBulan',
            'persenProgress',
            'jatuhTempoNext',
            'estimasiShu',
            'riwayatTransaksi'
        ));
    }
}