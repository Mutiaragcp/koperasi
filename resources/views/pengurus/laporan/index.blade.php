@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Laporan Keuangan & Arus Kas</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Laporan Keuangan</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <a href="/laporan/cetak-pdf?bulan={{ $bulan }}&tahun={{ $tahun }}" target="_blank" class="btn btn-outline-success btn-sm rounded-3 fw-medium px-3 flex-fill d-flex align-items-center gap-1 text-decoration-none">
            🖨️ Cetak PDF
        </a>
        <a href="/laporan/export-excel?bulan={{ $bulan }}&tahun={{ $tahun }}" class="btn btn-primary btn-sm rounded-3 fw-medium px-3 flex-fill d-flex align-items-center gap-1 text-decoration-none">
            ↓ Export Excel
        </a>
    </div>
</div>

<form action="/laporan" method="GET" class="card card-modern border-0 shadow-sm p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold small text-muted-light mb-1">Pilih Bulan</label>
            <select name="bulan" class="form-select bg-light border-0 py-2 text-dark">
                <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
            </select>
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold small text-muted-light mb-1">Pilih Tahun</label>
            <select name="tahun" class="form-select bg-light border-0 py-2 text-dark">
                {{-- Tambahan Opsi Semua Tahun --}}
                <option value="all" {{ $tahun == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                @for($i = date('Y'); $i >= 2024; $i--)
                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-12 col-md-4">
            <button type="submit" class="btn btn-dark w-100 py-2 fw-medium rounded-3 shadow-sm">Tampilkan Laporan</button>
        </div>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern p-3 bg-dark text-white shadow h-100 border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-white-50 small fw-bold">TOTAL KAS KESELURUHAN</span>
                <span class="badge bg-primary text-white rounded-pill px-2" style="font-size: 0.65rem;">Sistem Riil</span>
            </div>
            <h4 class="fw-bold mb-1 text-white">Rp {{ number_format($totalKasKeseluruhan, 0, ',', '.') }}</h4>
            <span class="text-white-50 small">Total isi dompet koperasi saat ini</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">KAS MASUK (PERIODE)</span>
                <span class="badge bg-success-subtle text-success rounded-pill px-2" style="font-size: 0.7rem;">Debit</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
            <span class="text-muted-light small">Pemasukan periode terpilih</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">KAS KELUAR (PERIODE)</span>
                <span class="badge bg-danger-subtle text-danger rounded-pill px-2" style="font-size: 0.7rem;">Kredit</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
            <span class="text-muted-light small">Pengeluaran periode terpilih</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-6">
        <div class="card card-modern p-3 bg-primary-subtle border-primary-subtle h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-primary small fw-bold">SALDO ARUS KAS</span>
                <span class="badge bg-primary text-white rounded-pill px-2" style="font-size: 0.65rem;">Periode</span>
            </div>
            <h4 class="fw-bold mb-1 text-primary">Rp {{ number_format($saldoPeriode, 0, ',', '.') }}</h4>
            <span class="text-primary small">Sisa uang khusus di periode ini</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-6">
        <div class="card card-modern card-gradient-primary border-0 p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-medium opacity-75 text-white">KEUNTUNGAN KAS (ANGSURAN)</span>
                <span class="badge bg-white text-primary rounded-pill px-2" style="font-size: 0.65rem;">Periode</span>
            </div>
            <h4 class="fw-bold mb-1 text-white">Rp {{ number_format($keuntunganAngsuran, 0, ',', '.') }}</h4>
            <span class="text-white opacity-75 small">Total laba dari bunga & denda lunas</span>
        </div>
    </div>
</div>

<div class="card card-modern border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
        {{-- Diubah memakai $namaBulan dan $namaTahun --}}
        <h6 class="fw-bold text-dark mb-0">Rincian Mutasi Kas ({{ $namaBulan }} {{ $namaTahun }})</h6>
        <span class="badge bg-light text-muted border border-light-subtle rounded-pill">Total: {{ $totalData }} Transaksi</span>
    </div>

    <div class="table-responsive">
        <table class="table datatable-modern table-hover align-middle mb-0">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 12%; white-space: nowrap;">TANGGAL</th>
                    <th class="py-3" style="width: 20%;">KATEGORI</th>
                    <th class="py-3" style="width: 38%;">KETERANGAN TRANSAKSI</th>
                    <th class="py-3 text-end" style="width: 15%;">PEMASUKAN (DEBIT)</th>
                    <th class="py-3 text-end pe-4" style="width: 15%;">PENGELUARAN (KREDIT)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $trx)
                <tr>
                    <td class="py-3 ps-4 text-muted fw-medium" style="white-space: nowrap;">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}</td>
                    <td class="py-3 text-dark fw-semibold">{{ $trx->kategori }}</td>
                    <td class="py-3">
                        <span class="d-block text-muted-light small">{{ $trx->keterangan }}</span>
                    </td>
                    <td class="py-3 text-end fw-bold text-success">
                        {{ $trx->jenis == 'masuk' ? '+ Rp ' . number_format($trx->nominal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="py-3 text-end text-muted pe-4">
                        {!! $trx->jenis == 'keluar' ? '<span class="fw-bold text-danger">- Rp ' . number_format($trx->nominal, 0, ',', '.') . '</span>' : '-' !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if($totalData > 0)
            <tfoot class="bg-light">
                <tr>
                    {{-- Diubah memakai $namaBulan dan $namaTahun --}}
                    <td colspan="3" class="py-3 ps-4 text-end fw-bold text-dark">TOTAL MUTASI ({{ strtoupper($namaBulan) }} {{ strtoupper($namaTahun) }})</td>
                    <td class="py-3 text-end fw-bold text-success">+ Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                    <td class="py-3 text-end fw-bold text-danger pe-4">- Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection