<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Koperasi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #666; font-weight: bold; }
        
        .summary-table { width: 100%; margin-bottom: 20px; }
        .summary-table td { padding: 8px; border: 1px solid #ddd; width: 50%; }
        .summary-title { font-size: 10px; color: #666; text-transform: uppercase; }
        .summary-value { font-size: 16px; font-weight: bold; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table th { background-color: #f4f4f4; text-transform: uppercase; font-size: 10px; }
        .text-right { text-align: right; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN KEUANGAN KOPERASI</h2>
        
        <p>Periode Mutasi: {{ strtoupper($namaBulan) }} {{ strtoupper($namaTahun) }}</p>
    </div>

    <table class="summary-table" cellspacing="0">
        <tr>
            <td colspan="2" style="background-color: #f8f9fa; text-align: center;">
                <div class="summary-title">TOTAL KAS KESELURUHAN (SISTEM RIIL)</div>
                <div class="summary-value" style="font-size: 20px;">Rp {{ number_format($totalKasKeseluruhan, 0, ',', '.') }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="summary-title">Total Kas Masuk (Periode Ini)</div>
                <div class="summary-value text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Total Kas Keluar (Periode Ini)</div>
                <div class="summary-value text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="summary-title">Saldo Arus Kas (Periode Ini)</div>
                <div class="summary-value">Rp {{ number_format($saldoPeriode, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Laba Bersih Angsuran (Periode Ini)</div>
                <div class="summary-value" style="color: #0d6efd;">Rp {{ number_format($keuntunganAngsuran ?? 0, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 14px; margin-bottom: 5px;">Rincian Mutasi Transaksi</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="25%">Kategori</th>
                <th width="30%">Keterangan</th>
                <th width="15%" class="text-right">Masuk (Rp)</th>
                <th width="15%" class="text-right">Keluar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $trx->kategori }}</td>
                <td>{{ $trx->keterangan }}</td>
                <td class="text-right text-success">
                    {{ $trx->jenis == 'masuk' ? number_format($trx->nominal, 0, ',', '.') : '-' }}
                </td>
                <td class="text-right text-danger">
                    {{ $trx->jenis == 'keluar' ? number_format($trx->nominal, 0, ',', '.') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                {{-- Keterangan dinamis jika data kosong --}}
                <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada transaksi pada periode {{ $namaBulan }} {{ $namaTahun }}.</td>
            </tr>
            @endforelse
        </tbody>
        @if($totalData > 0)
        <tfoot>
            <tr>
                
                <th colspan="3" class="text-right">TOTAL MUTASI {{ strtoupper($namaBulan) }} {{ strtoupper($namaTahun) }}</th>
                <th class="text-right text-success">{{ number_format($totalMasuk, 0, ',', '.') }}</th>
                <th class="text-right text-danger">{{ number_format($totalKeluar, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
        @endif
    </table>

</body>
</html>