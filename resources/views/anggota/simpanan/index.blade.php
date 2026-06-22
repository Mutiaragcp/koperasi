@extends('layouts.app')

@section('content')

<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Riwayat Simpanan Saya</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/anggota/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Kas Simpanan</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex gap-2 w-100 w-sm-auto">
        <a href="/anggota/simpanan/setor" class="btn btn-primary btn-sm rounded-3 fw-medium px-3 d-flex align-items-center justify-content-center gap-2 shadow-sm flex-fill flex-sm-grow-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span class="d-none d-sm-inline">Setor</span> Simpanan
        </a>

        <a href="/anggota/simpanan/cetak" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3 fw-medium px-3 d-flex align-items-center justify-content-center gap-2 flex-fill flex-sm-grow-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
            </svg>
            <span class="d-none d-sm-inline">Cetak Rekening Koran</span><span class="d-sm-none">Cetak</span>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <strong>Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4 col-xl-3">
        <div class="card-balance h-100">
            <span class="card-balance-label">TOTAL SALDO AKTIF</span>
            <h3 class="card-balance-amount">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h3>
            <div class="card-balance-footer">
                <span>Perkiraan SHU: Rp {{ number_format($estimasiShu, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8 col-xl-9">
        <div class="card-modern p-4 h-100">
            <h6 class="fw-bold text-dark mb-3">Rincian Simpanan</h6>
            <div class="row g-3 g-sm-0">
                <div class="col-6 col-sm-4 pb-3 pb-sm-0 border-bottom border-sm-bottom-0 border-sm-end">
                    <span class="d-block text-muted-light small mb-1">Simpanan Pokok</span>
                    <h5 class="fw-bold text-dark mb-1">Rp {{ number_format($simpPokok, 0, ',', '.') }}</h5>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2" style="font-size: 0.65rem;">Lunas</span>
                </div>
                <div class="col-6 col-sm-4 pb-3 pb-sm-0 ps-sm-4 border-bottom border-sm-bottom-0 border-sm-end">
                    <span class="d-block text-muted-light small mb-1">Simpanan Wajib</span>
                    <h5 class="fw-bold text-dark mb-1">Rp {{ number_format($simpWajib, 0, ',', '.') }}</h5>
                    <span class="text-muted-light d-block" style="font-size: 0.7rem;">Akumulasi setoran wajib</span>
                </div>
                <div class="col-12 col-sm-4 pt-3 pt-sm-0 ps-sm-4">
                    <span class="d-block text-muted-light small mb-1">Simpanan Sukarela</span>
                    <h5 class="fw-bold text-success mb-1">Rp {{ number_format($simpSukarela, 0, ',', '.') }}</h5>
                    <span class="text-muted-light d-block" style="font-size: 0.7rem;">Dapat ditarik kapan saja</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-modern mb-4">
    <div class="card-header bg-white border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h6 class="fw-bold text-dark mb-1">Mutasi Simpanan</h6>
            <span class="text-muted-light small">Riwayat setoran mandiri dan penarikan Anda.</span>
        </div>

        <div class="d-flex gap-2 w-100 w-md-auto">
            <select class="form-select form-select-sm shadow-none flex-fill flex-md-grow-0 text-muted-light">
                <option selected>Semua Jenis</option>
                <option value="masuk">Uang Masuk (Setoran)</option>
                <option value="keluar">Uang Keluar (Penarikan)</option>
            </select>
            <select class="form-select form-select-sm shadow-none flex-fill flex-md-grow-0 text-muted-light">
                <option selected>Tahun 2026</option>
                <option value="2025">Tahun 2025</option>
            </select>
        </div>
    </div>

    <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive">
            <table class="table datatable-modern table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="border-0 ps-4 py-3 fw-semibold">ID Transaksi</th>
                        <th class="border-0 py-3 fw-semibold">Tanggal</th>
                        <th class="border-0 py-3 fw-semibold">Kategori Simpanan</th>
                        <th class="border-0 py-3 fw-semibold">Nominal</th>
                        <th class="border-0 py-3 fw-semibold text-center">Bukti</th>
                        <th class="border-0 text-end pe-4 py-3 fw-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $row)
                    <tr>
                        <td class="ps-4 py-3 text-muted-light small fw-medium">
                            TRX-{{ Carbon\Carbon::parse($row->created_at)->format('mdY') }}-{{ $row->id }}
                        </td>
                        <td class="py-3">
                            <span class="d-block text-dark fw-medium small">{{ Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y') }}</span>
                            <span class="text-muted-light" style="font-size: 0.7rem;">{{ Carbon\Carbon::parse($row->created_at)->format('H:i') }} WIB</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($row->jenis_transaksi == 'setor')
                                    <span class="bg-primary-subtle text-primary rounded px-2 py-1 fw-bold" style="font-size: 0.7rem;">Setor</span>
                                @else
                                    <span class="bg-warning-subtle text-warning rounded px-2 py-1 fw-bold" style="font-size: 0.7rem;">Tarik</span>
                                @endif
                                <span class="text-dark small">{{ $row->jenis_nama }}</span>
                            </div>
                        </td>
                        <td class="py-3 fw-bold {{ $row->jenis_transaksi == 'setor' ? 'text-success' : 'text-danger' }} small">
                            {{ $row->jenis_transaksi == 'setor' ? '+' : '-' }} Rp {{ number_format($row->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="py-3 text-center">
                            @if($row->bukti_transaksi)
                                <a href="{{ asset('storage/uploads/simpanan/'.$row->bukti_transaksi) }}" target="_blank" class="btn btn-sm btn-light border text-primary fw-medium py-1 px-2" style="font-size: 0.7rem;">
                                    Lihat File
                                </a>
                            @else
                                <span class="text-muted-light small" style="font-size: 0.7rem;">Offline / Kasir</span>
                            @endif
                        </td>
                        <td class="py-3 text-end pe-4">
                            @if($row->status == 'pending')
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.7rem;">Pending</span>
                            @elseif($row->status == 'disetujui')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.7rem;">Sukses</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.7rem;">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">Belum ada riwayat mutasi simpanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body p-3 d-md-none">
        @forelse($riwayat as $row)
        <div class="mutasi-item">
            <div class="mutasi-item-top">
                <div class="d-flex align-items-center gap-2">
                    @if($row->jenis_transaksi == 'setor')
                        <span class="bg-primary-subtle text-primary rounded px-2 py-1 fw-bold" style="font-size: 0.68rem;">Setor</span>
                    @else
                        <span class="bg-warning-subtle text-warning rounded px-2 py-1 fw-bold" style="font-size: 0.68rem;">Tarik</span>
                    @endif
                    <span class="text-dark small fw-medium">{{ $row->jenis_nama }}</span>
                </div>
                @if($row->status == 'pending')
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.65rem;">Pending</span>
                @elseif($row->status == 'disetujui')
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.65rem;">Sukses</span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.65rem;">Ditolak</span>
                @endif
            </div>

            <div class="mutasi-item-amount {{ $row->jenis_transaksi == 'setor' ? 'text-success' : 'text-danger' }}">
                {{ $row->jenis_transaksi == 'setor' ? '+' : '-' }} Rp {{ number_format($row->jumlah, 0, ',', '.') }}
            </div>

            <div class="mutasi-item-bottom">
                <div>
                    <span class="d-block text-muted-light" style="font-size: 0.72rem;">{{ Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y') }} &middot; {{ Carbon\Carbon::parse($row->created_at)->format('H:i') }} WIB</span>
                    <span class="d-block text-muted-light" style="font-size: 0.68rem;">TRX-{{ Carbon\Carbon::parse($row->created_at)->format('mdY') }}-{{ $row->id }}</span>
                </div>
                @if($row->bukti_transaksi)
                    <a href="{{ asset('storage/uploads/simpanan/'.$row->bukti_transaksi) }}" target="_blank" class="btn btn-sm btn-light border text-primary fw-medium py-1 px-2" style="font-size: 0.7rem;">
                        Lihat
                    </a>
                @else
                    <span class="text-muted-light" style="font-size: 0.68rem;">Offline</span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted small">Belum ada riwayat mutasi simpanan.</div>
        @endforelse
    </div>


</div>
@endsection