<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

// Library untuk Export Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->filled('tahun') ? $request->tahun : Carbon::now()->year;
        $bulan = $request->filled('bulan') ? $request->bulan : Carbon::now()->month;

        $semuaMasuk = DB::table('transaksi_kas')->where('jenis', 'masuk')->sum('nominal');
        $semuaKeluar = DB::table('transaksi_kas')->where('jenis', 'keluar')->sum('nominal');
        $totalKasKeseluruhan = $semuaMasuk - $semuaKeluar;

        // Inisialisasi Query Dasar
        $queryBase = DB::table('transaksi_kas');

        // Filter Tahun
        if ($tahun != 'all') {
            $queryBase->whereYear('tanggal', $tahun);
            $namaTahun = $tahun;
        } else {
            $namaTahun = 'Semua Tahun';
        }

        // Filter Bulan
        if ($bulan != 'all') {
            $queryBase->whereMonth('tanggal', (int) $bulan);
            $namaBulan = Carbon::create()->month((int) $bulan)->translatedFormat('F');
        } else {
            $namaBulan = 'Semua Bulan';
        }

        $transaksis = (clone $queryBase)
                        ->orderBy('tanggal', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();

        $totalMasuk = (clone $queryBase)->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = (clone $queryBase)->where('jenis', 'keluar')->sum('nominal');
        $saldoPeriode = $totalMasuk - $totalKeluar;
        
        $totalData = $transaksis->count();

        // Hitung Keuntungan Angsuran
        $queryAngsuran = DB::table('angsurans')
            ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
            ->where('angsurans.status', 'lunas');

        if ($tahun != 'all') {
            $queryAngsuran->whereYear('angsurans.tgl_bayar', $tahun);
        }
        if ($bulan != 'all') {
            $queryAngsuran->whereMonth('angsurans.tgl_bayar', (int) $bulan);
        }
        $keuntunganAngsuran = $queryAngsuran->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

        return view('pengurus.laporan.index', compact(
            'transaksis', 'totalMasuk', 'totalKeluar', 'saldoPeriode', 'totalKasKeseluruhan',
            'bulan', 'tahun', 'namaBulan', 'namaTahun', 'totalData', 'keuntunganAngsuran'
        ));
    }

    public function cetakPdf(Request $request)
    {
        $tahun = $request->tahun; 
        $bulan = $request->bulan; 

        $queryBase = DB::table('transaksi_kas');

        // Logika Filter Tahun
        if ($tahun && $tahun != 'all') {
            $queryBase->whereYear('tanggal', $tahun);
        }

        // Logika Filter Bulan
        if ($bulan && $bulan != 'all') {
            $queryBase->whereMonth('tanggal', (int)$bulan);
        }

        // Ambil Data
        $transaksis = $queryBase->orderBy('tanggal', 'asc')->get();
        
        // Hitung Total Periode
        $totalMasuk = (clone $queryBase)->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = (clone $queryBase)->where('jenis', 'keluar')->sum('nominal');
        $saldoPeriode = $totalMasuk - $totalKeluar;

        // Hitung Total Keseluruhan
        $semuaMasuk = DB::table('transaksi_kas')->where('jenis', 'masuk')->sum('nominal');
        $semuaKeluar = DB::table('transaksi_kas')->where('jenis', 'keluar')->sum('nominal');
        $totalKasKeseluruhan = $semuaMasuk - $semuaKeluar;
        
        $namaBulan = ($bulan == 'all' || !$bulan) ? 'Semua Bulan' : Carbon::create()->month((int)$bulan)->translatedFormat('F');
        $namaTahun = ($tahun == 'all' || !$tahun) ? 'Semua Tahun' : $tahun;
        
        $totalData = $transaksis->count();

        // Hitung Keuntungan Angsuran
        $queryAngsuran = DB::table('angsurans')
            ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
            ->where('angsurans.status', 'lunas');

        if ($tahun && $tahun != 'all') {
            $queryAngsuran->whereYear('angsurans.tgl_bayar', $tahun);
        }
        if ($bulan && $bulan != 'all') {
            $queryAngsuran->whereMonth('angsurans.tgl_bayar', (int) $bulan);
        }
        $keuntunganAngsuran = $queryAngsuran->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

        $pdf = Pdf::loadView('pengurus.laporan.pdf', compact(
            'transaksis', 'totalMasuk', 'totalKeluar', 'saldoPeriode', 'totalKasKeseluruhan',
            'namaBulan', 'namaTahun', 'totalData', 'keuntunganAngsuran'
        ));

        // Format nama file PDF otomatis sesuai filter
        $namaFile = 'Laporan_Keuangan_' . str_replace(' ', '_', $namaBulan) . '_' . str_replace(' ', '_', $namaTahun) . '.pdf';

        return $pdf->stream($namaFile);
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $queryBase = DB::table('transaksi_kas');

        if ($tahun && $tahun != 'all') {
            $queryBase->whereYear('tanggal', $tahun);
        }
        if ($bulan && $bulan != 'all') {
            $queryBase->whereMonth('tanggal', (int)$bulan);
        }

        $transaksis = $queryBase->orderBy('tanggal', 'asc')->get();

        // Hitung Ringkasan Data
        $totalMasuk = (clone $queryBase)->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = (clone $queryBase)->where('jenis', 'keluar')->sum('nominal');
        $saldoPeriode = $totalMasuk - $totalKeluar;

        $semuaMasuk = DB::table('transaksi_kas')->where('jenis', 'masuk')->sum('nominal');
        $semuaKeluar = DB::table('transaksi_kas')->where('jenis', 'keluar')->sum('nominal');
        $totalKasKeseluruhan = $semuaMasuk - $semuaKeluar;

        $namaBulan = ($bulan == 'all' || !$bulan) ? 'Semua Bulan' : Carbon::create()->month((int)$bulan)->translatedFormat('F');
        $namaTahun = ($tahun == 'all' || !$tahun) ? 'Semua Tahun' : $tahun;

        // Hitung Keuntungan Angsuran
        $queryAngsuran = DB::table('angsurans')
            ->join('pinjamans', 'angsurans.pinjaman_id', '=', 'pinjamans.id')
            ->where('angsurans.status', 'lunas');

        if ($tahun && $tahun != 'all') {
            $queryAngsuran->whereYear('angsurans.tgl_bayar', $tahun);
        }
        if ($bulan && $bulan != 'all') {
            $queryAngsuran->whereMonth('angsurans.tgl_bayar', (int) $bulan);
        }
        $keuntunganAngsuran = $queryAngsuran->sum(DB::raw('angsurans.denda + (pinjamans.jumlah_pinjaman * pinjamans.bunga / 100)'));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Keuangan');

        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN & MUTASI ARUS KAS KOPERASI');
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A2', 'Periode Mutasi: ' . strtoupper($namaBulan) . ' ' . strtoupper($namaTahun));
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A3', 'Waktu Export: ' . Carbon::now()->translatedFormat('d F Y H:i') . ' WIB');
        $sheet->mergeCells('A3:E3');
        
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(11)->setBold(true);
        $sheet->getStyle('A3')->getFont()->setSize(9)->setItalic(true);

        $sheet->setCellValue('A5', 'RINGKASAN SALDO KAS');
        $sheet->mergeCells('A5:E5');
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);

        $sheet->setCellValue('A6', 'TOTAL KAS KESELURUHAN (SISTEM RIIL)');
        $sheet->mergeCells('A6:C6');
        $sheet->setCellValue('D6', $totalKasKeseluruhan);
        $sheet->mergeCells('D6:E6');
        
        $sheet->setCellValue('A7', 'Total Kas Masuk (Periode Pilihan Ini)');
        $sheet->mergeCells('A7:C7');
        $sheet->setCellValue('D7', $totalMasuk);
        $sheet->mergeCells('D7:E7');
        
        $sheet->setCellValue('A8', 'Total Kas Keluar (Periode Pilihan Ini)');
        $sheet->mergeCells('A8:C8');
        $sheet->setCellValue('D8', $totalKeluar);
        $sheet->mergeCells('D8:E8');
        
        $sheet->setCellValue('A9', 'Saldo Arus Kas Tercipta (Periode Ini)');
        $sheet->mergeCells('A9:C9');
        $sheet->setCellValue('D9', $saldoPeriode);
        $sheet->mergeCells('D9:E9');
        
        $sheet->setCellValue('A10', 'Laba Bersih Angsuran (Periode Ini)');
        $sheet->mergeCells('A10:C10');
        $sheet->setCellValue('D10', $keuntunganAngsuran);
        $sheet->mergeCells('D10:E10');

        // Styling Summary Box
        $sheet->getStyle('D6:D10')->getNumberFormat()->setFormatCode('"Rp" #,##0');
        $sheet->getStyle('A6:E10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A6:C10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F2F2');
        $sheet->getStyle('D6:E10')->getFont()->setBold(true);

        $sheet->setCellValue('A13', 'RINCIAN MUTASI TRANSAKSI KAS');
        $sheet->mergeCells('A13:E13');
        $sheet->getStyle('A13')->getFont()->setBold(true)->setSize(12);

        // Header Tabel
        $headers = ['Tanggal', 'Kategori', 'Keterangan Transaksi', 'Pemasukan (Debit)', 'Pengeluaran (Kredit)'];
        $sheet->fromArray($headers, NULL, 'A14');
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A14:E14')->applyFromArray($headerStyle);

        // Isi Data Transaksi
        $row = 15;
        foreach ($transaksis as $trx) {
            $masuk = $trx->jenis == 'masuk' ? $trx->nominal : 0;
            $keluar = $trx->jenis == 'keluar' ? $trx->nominal : 0;

            $sheet->setCellValue('A' . $row, Carbon::parse($trx->tanggal)->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $trx->kategori);
            $sheet->setCellValue('C' . $row, $trx->keterangan);
            $sheet->setCellValue('D' . $row, $masuk);
            $sheet->setCellValue('E' . $row, $keluar);

            // Format Angka Selama Looping Data
            $sheet->getStyle('D'.$row.':E'.$row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->getStyle('A'.$row.':E'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            // Tengah-kan teks tanggal
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }

        // Baris Total Akumulasi Paling Bawah
        $sheet->setCellValue('A' . $row, 'TOTAL AKUMULASI MUTASI');
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->setCellValue('D' . $row, $totalMasuk);
        $sheet->setCellValue('E' . $row, $totalKeluar);

        // Style Baris Total
        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:E{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D{$row}:E{$row}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Export menjadi file format .xlsx resmi
        $fileName = 'Laporan_Keuangan_' . str_replace(' ', '_', $namaBulan) . '_' . str_replace(' ', '_', $namaTahun) . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}