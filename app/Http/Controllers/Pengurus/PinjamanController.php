<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Library untuk Export Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjaman::with(['anggota.user']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pinjamans = $query->latest()->get();

        $totalPinjamanBerjalan = Pinjaman::where('status', 'disetujui')->sum('jumlah_pinjaman');
        $menungguApproval = Pinjaman::where('status', 'menunggu')->count();
        $pinjamanLunas = Pinjaman::where('status', 'lunas')->count();

        $telatAngsuran = DB::table('angsurans')->where('status', 'telat')->distinct('pinjaman_id')->count();

        return view('pengurus.pinjaman.index', compact('pinjamans', 'totalPinjamanBerjalan', 'menungguApproval', 'pinjamanLunas', 'telatAngsuran'));
    }

    public function create()
    {
        $anggotas = Anggota::with('user')->where('status', 'aktif')->get();
        return view('pengurus.pinjaman.create', compact('anggotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jumlah_pinjaman' => 'required|numeric|min:10000',
            'tenor' => 'required|integer|min:1',
            'bunga' => 'required|numeric|min:0'
        ], [
            'anggota_id.required' => 'Pilih anggota terlebih dahulu.',
            'jumlah_pinjaman.min' => 'Nominal pinjaman terlalu kecil.',
            'tenor.min' => 'Tenor minimal 1 bulan.'
        ]);

        DB::beginTransaction();

        try {
            $pinjaman = new Pinjaman();
            $pinjaman->anggota_id = $request->anggota_id;
            $pinjaman->jumlah_pinjaman = $request->jumlah_pinjaman;
            $pinjaman->tenor = $request->tenor;
            $pinjaman->bunga = $request->bunga;
            $pinjaman->tgl_pengajuan = now()->toDateString();

            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'bendahara'])) {
                $pinjaman->status = 'disetujui';
                $pinjaman->disetujui_oleh = Auth::id();
                $pinjaman->tgl_cair = now()->toDateString();
                $pinjaman->save();

                // Jalankan fungsi otomatis cetak kas & angsuran
                $this->eksekusiPencairanDanAngsuran($pinjaman);

                DB::commit();
                return redirect('/pinjaman/' . $pinjaman->id)->with('success', 'Pinjaman berhasil diinput dan OTOMATIS DISETUJUI. Dana dicairkan & jadwal angsuran telah dicetak.');
            } else {
                $pinjaman->status = 'menunggu';
                $pinjaman->save();

                DB::commit();
                return redirect('/pinjaman')->with('success', 'Pengajuan pinjaman berhasil dicatat dan masuk daftar tunggu persetujuan.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string $id)
    {
        $pinjaman = Pinjaman::with(['anggota.user', 'angsurans'])->findOrFail($id);

        $pokok = $pinjaman->jumlah_pinjaman / $pinjaman->tenor;
        $bunga = $pinjaman->jumlah_pinjaman * ($pinjaman->bunga / 100);
        $cicilan_per_bulan = $pokok + $bunga;

        $angsuranLunas = $pinjaman->angsurans->where('status', 'lunas')->count();
        $sisa_hutang_pokok = $pinjaman->jumlah_pinjaman - ($angsuranLunas * $pokok);

        return view('pengurus.pinjaman.show', compact('pinjaman', 'cicilan_per_bulan', 'sisa_hutang_pokok'));
    }

    public function approve(string $id)
    {
        $pinjaman = Pinjaman::with('anggota.user')->findOrFail($id);

        if ($pinjaman->status != 'menunggu') {
            return back()->with('error', 'Pengajuan pinjaman ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $pinjaman->status = 'disetujui';
            $pinjaman->disetujui_oleh = Auth::id();
            $pinjaman->tgl_cair = now()->toDateString();
            $pinjaman->save();

            // Panggil helper fungsi yang sama
            $this->eksekusiPencairanDanAngsuran($pinjaman);

            DB::commit();
            return redirect('/pinjaman/' . $pinjaman->id)->with('success', 'Pinjaman disetujui! Dana berhasil dicairkan (tercatat di Kas) dan jadwal angsuran dicetak otomatis.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function reject(string $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        if ($pinjaman->status != 'menunggu') {
            return back()->with('error', 'Pengajuan pinjaman ini sudah diproses.');
        }

        $pinjaman->status = 'ditolak';
        $pinjaman->save();

        return redirect('/pinjaman')->with('success', 'Pengajuan pinjaman ini telah resmi ditolak.');
    }

    public function destroy(string $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        if ($pinjaman->status != 'menunggu') {
            return back()->with('error', 'Tidak dapat menghapus data yang sudah diproses.');
        }

        $pinjaman->delete();
        return redirect('/pinjaman')->with('success', 'Data pengajuan pinjaman berhasil dihapus.');
    }

    /* =========================================================================
       HELPER METHOD (Dipakai bersama oleh store() dan approve())
       ========================================================================= */
    private function eksekusiPencairanDanAngsuran(Pinjaman $pinjaman)
    {
        DB::table('transaksi_kas')->insert([
            'tanggal' => now()->toDateString(),
            'jenis' => 'keluar',
            'kategori' => 'Pencairan Pinjaman',
            'nominal' => $pinjaman->jumlah_pinjaman,
            'keterangan' => 'Pencairan dana pinjaman kepada Anggota: ' . ($pinjaman->anggota->user->nama ?? 'Tanpa Nama') . ' (No Anggota: ' . $pinjaman->anggota->no_anggota . ')',
            'dieksekusi_oleh' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pokok_bulanan = $pinjaman->jumlah_pinjaman / $pinjaman->tenor;
        $bunga_bulanan = $pinjaman->jumlah_pinjaman * ($pinjaman->bunga / 100);
        $total_tagihan_bulanan = $pokok_bulanan + $bunga_bulanan;

        for ($i = 1; $i <= $pinjaman->tenor; $i++) {
            DB::table('angsurans')->insert([
                'pinjaman_id' => $pinjaman->id,
                'ke_berapa' => $i,
                'jumlah_bayar' => $total_tagihan_bulanan,
                'bukti_pembayaran' => null,
                'denda' => 0,
                'tgl_jatuh_tempo' => now()->addMonths($i)->toDateString(),
                'tgl_bayar' => null,
                'status' => 'belum_bayar',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $query = Pinjaman::with(['anggota.user']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pinjamans = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pinjaman');

        $sheet->setCellValue('A1', 'LAPORAN DATA PINJAMAN ANGGOTA KOPERASI');
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A2', 'Waktu Export: ' . Carbon::now()->translatedFormat('d F Y H:i') . ' WIB');
        $sheet->mergeCells('A2:G2');

        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(9)->setItalic(true);

        // Header Tabel
        $headers = ['No', 'Nama Anggota', 'No Anggota', 'Jumlah Pinjaman', 'Tenor (Bulan)', 'Bunga (%)', 'Status'];
        $sheet->fromArray($headers, NULL, 'A4');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A4:G4')->applyFromArray($headerStyle);

        $row = 5;
        foreach ($pinjamans as $index => $p) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $p->anggota->user->nama ?? '-');
            $sheet->setCellValue('C' . $row, $p->anggota->no_anggota ?? '-');
            $sheet->setCellValue('D' . $row, $p->jumlah_pinjaman);
            $sheet->setCellValue('E' . $row, $p->tenor);
            $sheet->setCellValue('F' . $row, $p->bunga . '%');
            $sheet->setCellValue('G' . $row, ucfirst($p->status));

            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->getStyle('A' . $row . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Laporan_Pinjaman_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
