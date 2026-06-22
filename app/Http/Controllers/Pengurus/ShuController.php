<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class ShuController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role == 'anggota') {
            return redirect()->route('shu.anggota');
        }

        $tahunIni = $request->get('tahun', date('Y'));

        $daftarTahun = DB::table('shus')->distinct()->pluck('periode')->toArray();
        if (!in_array(date('Y'), $daftarTahun)) {
            $daftarTahun[] = date('Y');
        }
        rsort($daftarTahun); // Urutkan dari tahun terbaru

        $sudahDibagikan = DB::table('shus')->where('periode', $tahunIni)->exists();

        // Ambil Persentase Dasar dari Database
        $pengaturan = DB::table('pengaturan_shus')->first();
        $defaultAnggota = $pengaturan ? $pengaturan->persen_anggota : 40;
        
        $pctModal    = $defaultAnggota / 2; 
        $pctAnggota  = $defaultAnggota / 2; 
        $pctPengurus = $pengaturan ? $pengaturan->persen_pengurus : 10;
        $pctSosial   = $pengaturan ? $pengaturan->persen_sosial : 15;

        $totalBungaKoperasi = DB::table('angsurans')
            ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
            ->where('angsurans.status', 'lunas')
            ->whereYear('angsurans.updated_at', $tahunIni)
            ->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

        $pengeluaranOperasional = DB::table('transaksi_kas')
            ->where('jenis', 'keluar')
            ->whereNotIn('kategori', ['Pencairan Pinjaman', 'Penarikan Simpanan', 'Penarikan', 'Pencairan'])
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal'); 

        $totalShu = max(0, $totalBungaKoperasi - $pengeluaranOperasional);

        $alokasiModal    = ($pctModal / 100) * $totalShu;
        $alokasiAnggota  = ($pctAnggota / 100) * $totalShu;
        $alokasiPengurus = ($pctPengurus / 100) * $totalShu;
        $alokasiSosial   = ($pctSosial / 100) * $totalShu;

        $dataSimulasi = [];
        $totalShuModalSemua = 0;
        $totalShuAnggotaSemua = 0;
        $totalShuDibagikan = 0;

        if ($sudahDibagikan) {
            $riwayatShu = DB::table('shus')
                ->join('anggotas', 'shus.anggota_id', '=', 'anggotas.id')
                ->join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('shus.periode', $tahunIni)
                ->select('users.nama', 'anggotas.no_anggota', 'shus.jasa_modal', 'shus.jasa_usaha', 'shus.jumlah', 'anggotas.id as anggota_id')
                ->get();

            foreach ($riwayatShu as $row) {
                $setorPribadi = DB::table('simpanans')->where('anggota_id', $row->anggota_id)->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->whereYear('tanggal', $tahunIni)->sum('jumlah');
                $tarikPribadi = DB::table('simpanans')->where('anggota_id', $row->anggota_id)->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->whereYear('tanggal', $tahunIni)->sum('jumlah');
                $simpananPribadi = $setorPribadi - $tarikPribadi;

                $bungaPribadi = DB::table('angsurans')
                    ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
                    ->where('pinjamans.anggota_id', $row->anggota_id)
                    ->where('angsurans.status', 'lunas')
                    ->whereYear('angsurans.updated_at', $tahunIni)
                    ->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

                $dataSimulasi[] = (object)[
                    'nama'             => $row->nama,
                    'no_anggota'       => $row->no_anggota,
                    'simpanan_pribadi' => $simpananPribadi,
                    'shu_modal'        => $row->jasa_modal,
                    'bunga_dibayar'    => $bungaPribadi,
                    'shu_anggota'      => $row->jasa_usaha,
                    'total_diterima'   => $row->jumlah
                ];

                $totalShuModalSemua += $row->jasa_modal;
                $totalShuAnggotaSemua += $row->jasa_usaha;
                $totalShuDibagikan += $row->jumlah;
            }
        } else {
            $anggotas = DB::table('anggotas')->join('users', 'anggotas.user_id', '=', 'users.id')->select('anggotas.id', 'users.nama', 'anggotas.no_anggota')->get();
            $totalSetorKoperasi = DB::table('simpanans')->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->whereYear('tanggal', $tahunIni)->sum('jumlah');
            $totalTarikKoperasi = DB::table('simpanans')->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->whereYear('tanggal', $tahunIni)->sum('jumlah');
            $totalSimpananKoperasi = max(1, $totalSetorKoperasi - $totalTarikKoperasi);
            $totalBungaPenyebut = max(1, $totalBungaKoperasi);

            foreach ($anggotas as $anggota) {
                $setorPribadi = DB::table('simpanans')->where('anggota_id', $anggota->id)->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->whereYear('tanggal', $tahunIni)->sum('jumlah');
                $tarikPribadi = DB::table('simpanans')->where('anggota_id', $anggota->id)->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->whereYear('tanggal', $tahunIni)->sum('jumlah');
                $simpananPribadi = $setorPribadi - $tarikPribadi;

                $bungaPribadi = DB::table('angsurans')
                    ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
                    ->where('pinjamans.anggota_id', $anggota->id)
                    ->where('angsurans.status', 'lunas')
                    ->whereYear('angsurans.updated_at', $tahunIni)
                    ->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

                $shuModal   = ($simpananPribadi / $totalSimpananKoperasi) * $alokasiModal;
                $shuAnggota = ($bungaPribadi / $totalBungaPenyebut) * $alokasiAnggota;
                $totalDiterima = $shuModal + $shuAnggota;

                if ($totalDiterima > 0) {
                    $dataSimulasi[] = (object)[
                        'nama'             => $anggota->nama,
                        'no_anggota'       => $anggota->no_anggota,
                        'simpanan_pribadi' => $simpananPribadi,
                        'shu_modal'        => $shuModal,
                        'bunga_dibayar'    => $bungaPribadi,
                        'shu_anggota'      => $shuAnggota,
                        'total_diterima'   => $totalDiterima
                    ];

                    $totalShuModalSemua += $shuModal;
                    $totalShuAnggotaSemua += $shuAnggota;
                    $totalShuDibagikan += $totalDiterima;
                }
            }
        }

        usort($dataSimulasi, function($a, $b) { return $b->total_diterima <=> $a->total_diterima; });

        return view('pengurus.shu.index', compact(
            'tahunIni', 'daftarTahun', 'totalShu', 'pctModal', 'pctAnggota', 'pctPengurus', 'pctSosial',
            'alokasiModal', 'alokasiAnggota', 'alokasiPengurus', 'alokasiSosial',
            'dataSimulasi', 'totalShuModalSemua', 'totalShuAnggotaSemua', 'totalShuDibagikan', 'sudahDibagikan'
        ));
    }

    public function historyAnggota()
    {
        $user = Auth::user();
        $anggota = DB::table('anggotas')->where('user_id', $user->id)->first();

        if (!$anggota) {
            abort(404, 'Data Profil Anggota Anda tidak ditemukan.');
        }

        $riwayatShu = DB::table('shus')
            ->where('anggota_id', $anggota->id)
            ->orderBy('periode', 'desc')
            ->get();

        return view('anggota.shu.index', compact('riwayatShu', 'anggota'));
    }

    public function bagikan(Request $request)
    {
        $tahunIni = date('Y'); // Pembagian selalu mengunci tahun berjalan

        $sudahDibagikan = DB::table('shus')->where('periode', $tahunIni)->exists();
        if ($sudahDibagikan) {
            return back()->with('error', 'Gagal! SHU untuk periode tahun ' . $tahunIni . ' sudah pernah dibagikan sebelumnya.');
        }

        $pengaturan = DB::table('pengaturan_shus')->first();
        $defaultAnggota = $pengaturan ? $pengaturan->persen_anggota : 40;
        $pctModal = $defaultAnggota / 2; 
        $pctAnggota = $defaultAnggota / 2; 

        $totalBungaKoperasi = DB::table('angsurans')
            ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
            ->where('angsurans.status', 'lunas')
            ->whereYear('angsurans.updated_at', $tahunIni)
            ->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

        $pengeluaranOperasional = DB::table('transaksi_kas')
            ->where('jenis', 'keluar')
            ->whereNotIn('kategori', ['Pencairan Pinjaman', 'Penarikan Simpanan', 'Penarikan', 'Pencairan'])
            ->whereYear('tanggal', $tahunIni)
            ->sum('nominal');

        $totalShu = max(0, $totalBungaKoperasi - $pengeluaranOperasional);

        if ($totalShu == 0) {
            return back()->with('error', 'Tidak ada laba / keuntungan Koperasi yang bisa dibagikan tahun ini.');
        }

        $alokasiModal = $totalShu * ($pctModal / 100);
        $alokasiAnggota = $totalShu * ($pctAnggota / 100);

        $totalSetorKoperasi = DB::table('simpanans')->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->whereYear('tanggal', $tahunIni)->sum('jumlah');
        $totalTarikKoperasi = DB::table('simpanans')->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->whereYear('tanggal', $tahunIni)->sum('jumlah');
        $totalSimpananKoperasi = max(1, $totalSetorKoperasi - $totalTarikKoperasi);
        $totalBungaPenyebut = max(1, $totalBungaKoperasi);

        $anggotas = Anggota::all();
        $totalDibagikan = 0;

        DB::beginTransaction();
        try {
            $jenisSukarela = DB::table('jenis_simpanans')->where('nama', 'like', '%sukarela%')->first();
            $simpananId = $jenisSukarela ? $jenisSukarela->id : 2;

            foreach ($anggotas as $anggota) {
                $setorPribadi = DB::table('simpanans')->where('anggota_id', $anggota->id)->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->whereYear('tanggal', $tahunIni)->sum('jumlah');
                $tarikPribadi = DB::table('simpanans')->where('anggota_id', $anggota->id)->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->whereYear('tanggal', $tahunIni)->sum('jumlah');
                $simpananPribadi = $setorPribadi - $tarikPribadi;

                $bungaPribadi = DB::table('angsurans')
                    ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
                    ->where('pinjamans.anggota_id', $anggota->id)
                    ->where('angsurans.status', 'lunas')
                    ->whereYear('angsurans.updated_at', $tahunIni)
                    ->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

                $shuModal = ($simpananPribadi / $totalSimpananKoperasi) * $alokasiModal;
                $shuAnggota = ($bungaPribadi / $totalBungaPenyebut) * $alokasiAnggota;
                $totalDiterima = $shuModal + $shuAnggota;

                if ($totalDiterima > 0) {
                    DB::table('shus')->insert([
                        'anggota_id' => $anggota->id,
                        'periode' => $tahunIni,
                        'jasa_modal' => $shuModal,
                        'jasa_usaha' => $shuAnggota,
                        'jumlah' => $totalDiterima,
                        'tgl_dihitung' => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    DB::table('simpanans')->insert([
                        'anggota_id' => $anggota->id,
                        'jenis_simpanan_id' => $simpananId,
                        'jenis_transaksi' => 'setor',
                        'jumlah' => $totalDiterima,
                        'bukti_transaksi' => 'Otomatis_Sistem_SHU.png', 
                        'status' => 'disetujui',
                        'tanggal' => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $totalDibagikan += $totalDiterima;
                }
            }

            if ($totalDibagikan > 0) {
                DB::table('transaksi_kas')->insert([
                    'tanggal' => now()->toDateString(),
                    'jenis' => 'keluar',
                    'kategori' => 'Pembagian SHU',
                    'nominal' => $totalDibagikan,
                    'keterangan' => 'Pembagian SHU Koperasi Tahun Keuntungan ' . $tahunIni,
                    'dieksekusi_oleh' => Auth::id() ?? 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return back()->with('success', 'Berhasil! SHU telah dibagikan secara permanen. Saldo Simpanan Sukarela anggota terkait otomatis bertambah.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membagikan SHU: ' . $e->getMessage());
        }
    }

    public function pengaturan()
    {
        $pengaturan = DB::table('pengaturan_shus')->first();
        if (!$pengaturan) {
            // Default fallback jika tabel kosong
            $pengaturan = (object)[
                'persen_anggota' => 40,
                'persen_cadangan' => 20,
                'persen_pengurus' => 10,
                'persen_karyawan' => 5,
                'persen_pendidikan' => 5,
                'persen_sosial' => 20,
            ];
        }
        return view('pengurus.pengaturan.shu', compact('pengaturan'));
    }

    public function simpanPengaturan(Request $request)
    {
        $request->validate([
            'persen_anggota' => 'required|numeric|min:0|max:100',
            'persen_cadangan' => 'required|numeric|min:0|max:100',
            'persen_pengurus' => 'required|numeric|min:0|max:100',
            'persen_karyawan' => 'required|numeric|min:0|max:100',
            'persen_pendidikan' => 'required|numeric|min:0|max:100',
            'persen_sosial' => 'required|numeric|min:0|max:100',
        ]);

        $total = $request->persen_anggota + $request->persen_cadangan + $request->persen_pengurus + $request->persen_karyawan + $request->persen_pendidikan + $request->persen_sosial;

        // Toleransi perhitungan desimal
        if (abs($total - 100) > 0.01) {
            return back()->with('error', 'Total persentase harus tepat 100%. Total saat ini: ' . $total . '%')->withInput();
        }

        $data = [
            'persen_anggota' => $request->persen_anggota,
            'persen_cadangan' => $request->persen_cadangan,
            'persen_pengurus' => $request->persen_pengurus,
            'persen_karyawan' => $request->persen_karyawan,
            'persen_pendidikan' => $request->persen_pendidikan,
            'persen_sosial' => $request->persen_sosial,
            'updated_at' => now()
        ];

        $exists = DB::table('pengaturan_shus')->first();
        if ($exists) {
            DB::table('pengaturan_shus')->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('pengaturan_shus')->insert($data);
        }

        return redirect('/shu/pengaturan')->with('success', 'Pengaturan persentase SHU berhasil disimpan!');
    }
}