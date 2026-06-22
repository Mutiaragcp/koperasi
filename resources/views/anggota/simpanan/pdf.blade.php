<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekening Koran Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px;
        }
        .info-table .label {
            font-weight: bold;
            width: 150px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .data-table th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-success {
            color: #198754;
        }
        .text-danger {
            color: #dc3545;
        }
        .summary-box {
            border: 1px solid #000;
            padding: 10px;
            width: 300px;
            float: right;
            margin-bottom: 40px;
        }
        .summary-box p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }
        .clear {
            clear: both;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>KOPERASI SIKOPSIM</h2>
        <p>Laporan Mutasi Kas Simpanan Anggota</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nomor Anggota</td>
            <td>: {{ $anggota->no_anggota }}</td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>: {{ $user->name }}</td>
            <td class="label">Dicetak Oleh</td>
            <td>: Sistem (Self-Service)</td>
        </tr>
        <tr>
            <td class="label">Tanggal Gabung</td>
            <td>: {{ \Carbon\Carbon::parse($anggota->tgl_gabung)->format('d/m/Y') }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Jenis Transaksi</th>
                <th width="20%">Debit (Masuk)</th>
                <th width="20%">Kredit (Keluar)</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($trx->created_at)->format('d/m/Y') }}</td>
                <td>
                    {{ $trx->jenis_nama }}
                </td>
                <td class="text-right">
                    @if($trx->jenis_transaksi == 'setor')
                        Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">
                    @if($trx->jenis_transaksi == 'tarik')
                        Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center" style="text-transform: capitalize;">
                    {{ $trx->status }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada riwayat transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <h4 style="margin-top: 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Ringkasan Saldo Akhir</h4>
        <table width="100%">
            <tr>
                <td>Total Saldo Aktif</td>
                <td class="text-right"><strong>Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
    
    <div class="clear"></div>

    <div class="footer">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <br><br><br>
            <div class="signature-line">
                <strong>Ketua Koperasi / Bendahara</strong>
            </div>
        </div>
    </div>

</body>
</html>
