<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Library untuk Export Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class SimpananController extends Controller
{
    public function index(Request $request)
    {
        // Asumsi ID Jenis Simpanan
        // Misal: 1 = Pokok, 2 = Wajib, 3 = Sukarela
        $idPokok = 1;
        $idWajib = 2;
        $idSukarela = 3;

        $totalPokok = Simpanan::where('jenis_simpanan_id', $idPokok)->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->sum('jumlah')
            - Simpanan::where('jenis_simpanan_id', $idPokok)->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->sum('jumlah');

        $totalWajib = Simpanan::where('jenis_simpanan_id', $idWajib)->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->sum('jumlah')
            - Simpanan::where('jenis_simpanan_id', $idWajib)->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->sum('jumlah');

        $totalSukarela = Simpanan::where('jenis_simpanan_id', $idSukarela)->where('status', 'disetujui')->where('jenis_transaksi', 'setor')->sum('jumlah')
            - Simpanan::where('jenis_simpanan_id', $idSukarela)->where('status', 'disetujui')->where('jenis_transaksi', 'tarik')->sum('jumlah');

        // Total Seluruh Kas Simpanan
        $totalKas = $totalPokok + $totalWajib + $totalSukarela;

        // Query Data Riwayat
        $query = Simpanan::with(['anggota.user', 'jenis_simpanan'])->latest();

        if ($request->filled('jenis_simpanan_id')) {
            $query->where('jenis_simpanan_id', $request->jenis_simpanan_id);
        }

        $simpanans = $query->get();
        $jenisSimpanans = JenisSimpanan::all(); // Untuk dropdown filter

        $pendingCount = Simpanan::where('status', 'pending')->count();

        return view('pengurus.simpanan.index', compact(
            'totalPokok',
            'totalWajib',
            'totalSukarela',
            'totalKas',
            'simpanans',
            'jenisSimpanans',
            'pendingCount'
        ));
    }

    public function create()
    {
        $anggotas = Anggota::with('user')->where('status', 'aktif')->get();
        $jenisSimpanans = JenisSimpanan::all();

        return view('pengurus.simpanan.create', compact('anggotas', 'jenisSimpanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jenis_simpanan_id' => 'required|exists:jenis_simpanans,id',
            'jenis_transaksi' => 'required|in:setor,tarik',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
        ]);

        if ($request->jenis_transaksi == 'tarik') {
            $totalSetor = Simpanan::where('anggota_id', $request->anggota_id)
                ->where('jenis_simpanan_id', $request->jenis_simpanan_id)
                ->where('jenis_transaksi', 'setor')
                ->where('status', 'disetujui')
                ->sum('jumlah');

            $totalTarik = Simpanan::where('anggota_id', $request->anggota_id)
                ->where('jenis_simpanan_id', $request->jenis_simpanan_id)
                ->where('jenis_transaksi', 'tarik')
                ->where('status', 'disetujui')
                ->sum('jumlah');

            $saldoMaksimal = $totalSetor - $totalTarik;

            if ($request->jumlah > $saldoMaksimal) {
                return back()
                    ->with('error', 'Gagal menyimpan! Nominal penarikan (Rp ' . number_format($request->jumlah, 0, ',', '.') . ') melebihi sisa saldo simpanan anggota yang tersedia (Rp ' . number_format($saldoMaksimal, 0, ',', '.') . ').')
                    ->withInput();
            }
        }

        DB::beginTransaction();

        try {
            $simpanan = new Simpanan();
            $simpanan->anggota_id = $request->anggota_id;
            $simpanan->jenis_simpanan_id = $request->jenis_simpanan_id;
            $simpanan->jenis_transaksi = $request->jenis_transaksi;
            $simpanan->jumlah = $request->jumlah;
            $simpanan->tanggal = $request->tanggal;
            $simpanan->status = 'disetujui';
            $simpanan->save();

            $anggota = Anggota::with('user')->findOrFail($request->anggota_id);
            $jenisSimpanan = JenisSimpanan::findOrFail($request->jenis_simpanan_id);

            $jenisKas = $request->jenis_transaksi == 'setor' ? 'masuk' : 'keluar';
            $kategoriText = ($request->jenis_transaksi == 'setor' ? 'Setoran ' : 'Penarikan ') . $jenisSimpanan->nama;

            DB::table('transaksi_kas')->insert([
                'tanggal' => $request->tanggal,
                'jenis' => $jenisKas,
                'kategori' => $kategoriText,
                'nominal' => $request->jumlah,
                'keterangan' => $kategoriText . ' oleh Admin/Kasir untuk Anggota: ' . ($anggota->user->nama ?? 'Tanpa Nama') . ' (No: ' . $anggota->no_anggota . ')',
                'dieksekusi_oleh' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $pesan = $request->jenis_transaksi == 'setor' ? 'Setoran' : 'Penarikan';
            return redirect('/simpanan')->with('success', "Transaksi $pesan sebesar Rp " . number_format($request->jumlah, 0, ',', '.') . " berhasil dicatat di riwayat tabungan dan Buku Kas Utama!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    // Menampilkan daftar antrean setoran
    public function verifikasiIndex()
    {
        $pendings = Simpanan::with(['anggota.user', 'jenis_simpanan'])
            ->where('status', 'pending')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('pengurus.simpanan.verifikasi', compact('pendings'));
    }

    // Memproses persetujuan (Kas bertambah)
    public function approve(string $id)
    {
        $simpanan = Simpanan::with(['anggota.user', 'jenis_simpanan'])->findOrFail($id);

        if ($simpanan->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $simpanan->status = 'disetujui';
            $simpanan->save();

            $kategoriText = 'Setoran Mandiri ' . $simpanan->jenis_simpanan->nama;

            DB::table('transaksi_kas')->insert([
                'tanggal' => now()->toDateString(),
                'jenis' => 'masuk',
                'kategori' => $kategoriText,
                'nominal' => $simpanan->jumlah,
                'keterangan' => $kategoriText . ' oleh Anggota: ' . ($simpanan->anggota->user->nama ?? 'Tanpa Nama') . ' (No: ' . $simpanan->anggota->no_anggota . ')',
                'dieksekusi_oleh' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Setoran mandiri berhasil disetujui dan kas koperasi telah bertambah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    // Memproses penolakan (Struk palsu/tidak valid)
    public function reject(string $id)
    {
        $simpanan = Simpanan::findOrFail($id);

        if ($simpanan->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $simpanan->status = 'ditolak';
        $simpanan->save();

        return back()->with('success', 'Setoran mandiri telah ditolak. Saldo kas tidak terpengaruh.');
    }

    public function cekSaldo(Request $request)
    {
        $anggotaId = $request->anggota_id;
        $jenisSimpananId = $request->jenis_simpanan_id;

        if (!$anggotaId || !$jenisSimpananId) {
            return response()->json(['saldo' => 0]);
        }

        $totalSetor = Simpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('jenis_transaksi', 'setor')
            ->where('status', 'disetujui')
            ->sum('jumlah');

        $totalTarik = Simpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('jenis_transaksi', 'tarik')
            ->where('status', 'disetujui')
            ->sum('jumlah');

        // Sisa Saldo Aktif
        $saldoAktif = $totalSetor - $totalTarik;

        return response()->json([
            'saldo' => $saldoAktif < 0 ? 0 : $saldoAktif
        ]);
    }

    public function exportExcel()
    {
        $simpanans = Simpanan::with(['anggota.user', 'jenis_simpanan'])
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Simpanan');

        $sheet->setCellValue('A1', 'LAPORAN DATA SIMPANAN ANGGOTA KOPERASI');
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'Waktu Export: ' . Carbon::now()->translatedFormat('d F Y H:i') . ' WIB');
        $sheet->mergeCells('A2:F2');

        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(9)->setItalic(true);

        // Header Tabel
        $headers = ['No', 'Nama Anggota', 'No Anggota', 'Jenis Simpanan', 'Jenis Transaksi', 'Nominal'];
        $sheet->fromArray($headers, NULL, 'A4');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);

        $row = 5;
        foreach ($simpanans as $index => $s) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $s->anggota->user->nama ?? '-');
            $sheet->setCellValue('C' . $row, $s->anggota->no_anggota ?? '-');
            $sheet->setCellValue('D' . $row, $s->jenis_simpanan->nama ?? '-');
            $sheet->setCellValue('E' . $row, ucfirst($s->jenis_transaksi));
            $sheet->setCellValue('F' . $row, $s->jumlah);

            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->getStyle('A' . $row . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Laporan_Simpanan_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
