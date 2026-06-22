@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Dashboard Overview</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <span class="text-muted-light small mt-2 mb-0 d-block">Sistem Informasi Koperasi Simpan Pinjam</span>
    </div>

    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <button class="btn btn-primary btn-sm rounded-3 fw-medium px-3 flex-fill">+ Transaksi Baru</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">TOTAL ANGGOTA</span>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;">Aktif</span>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ isset($totalAnggota) ? number_format($totalAnggota, 0, ',', '.') : '0' }}</h3>
            <span class="text-success small fw-medium" style="font-size: 0.75rem;">▲ +{{ $anggotaBaruMingguIni ?? 0 }} minggu ini</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">KAS SIMPANAN</span>
                <span class="text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </span>
            </div>
            <h3 class="fw-bold mb-1 text-primary">Rp {{ isset($totalSimpanan) ? number_format($totalSimpanan, 0, ',', '.') : '0' }}</h3>
            <span class="text-muted-light small" style="font-size: 0.75rem;">Total saldo terkumpul</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">PINJAMAN BERJALAN</span>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2" style="font-size: 0.7rem;">Active</span>
            </div>
            <h3 class="fw-bold mb-1 text-dark">Rp {{ isset($totalPinjaman) ? number_format($totalPinjaman, 0, ',', '.') : '0' }}</h3>
            <span class="text-warning small fw-medium" style="font-size: 0.75rem;">{{ $pinjamanPending ?? 0 }} Butuh Verifikasi</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-6">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">KAS TUNAI (BERSIH)</span>
                <span class="text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <h3 class="fw-bold mb-1 text-success">Rp {{ isset($kasBersih) ? number_format($kasBersih, 0, ',', '.') : '0' }}</h3>
            <span class="text-muted-light small" style="font-size: 0.75rem;">Uang siap pakai (Laci & Bank)</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-6">
        <div class="card card-modern card-gradient-primary border-0 p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-medium opacity-75 text-white">KEUNTUNGAN KAS (ANGSURAN)</span>
                <span class="text-white opacity-75">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </span>
            </div>
            <h3 class="fw-bold mb-1 text-white">Rp {{ isset($keuntunganAngsuran) ? number_format($keuntunganAngsuran, 0, ',', '.') : '0' }}</h3>
            <span class="text-white opacity-75 small" style="font-size: 0.75rem;">Total laba dari bunga & denda lunas</span>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card card-modern h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-3 px-sm-4 d-flex justify-content-between align-items-center gap-2">
                <div>
                    <h6 class="fw-bold text-dark mb-1">Transaksi Terkini</h6>
                    <span class="text-muted-light d-block" style="font-size: 0.75rem;">Transaksi terakhir hari ini</span>
                </div>
                <a href="/simpanan" class="btn btn-sm btn-light border-light-subtle text-primary fw-medium rounded-3 px-3 text-nowrap flex-shrink-0">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th class="border-0 ps-4 py-3 fw-semibold">Data Anggota</th>
                                <th class="border-0 py-3 fw-semibold">Jenis Transaksi</th>
                                <th class="border-0 py-3 fw-semibold">Nominal</th>
                                <th class="border-0 py-3 fw-semibold">Waktu</th>
                                <th class="border-0 text-end pe-4 py-3 fw-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $trx)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $nama = $trx->anggota->user->nama ?? 'User';
                                            $words = explode(' ', $nama);
                                            $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                        @endphp
                                        <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                            {{ $inisial }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.85rem;">{{ $nama }}</h6>
                                            <span class="text-muted-light" style="font-size: 0.75rem;">ID: {{ $trx->anggota->no_anggota ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($trx->jenis_transaksi == 'setor')
                                            <span class="text-success bg-success-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z" /></svg>
                                            </span>
                                            <span class="fw-medium text-dark" style="font-size: 0.85rem;">Setoran {{ $trx->jenis_simpanan->nama ?? '' }}</span>
                                        @else
                                            <span class="text-danger bg-danger-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z" /></svg>
                                            </span>
                                            <span class="fw-medium text-dark" style="font-size: 0.85rem;">Penarikan {{ $trx->jenis_simpanan->nama ?? '' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 fw-bold text-dark" style="font-size: 0.85rem;">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</td>
                                <td class="py-3" style="font-size: 0.8rem;">
                                    <span class="d-block text-dark fw-medium">
                                        {{ \Carbon\Carbon::parse($trx->created_at)->isToday() ? 'Hari ini' : \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y') }}
                                    </span>
                                    <span class="text-muted-light">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    @if($trx->status == 'disetujui')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <span class="d-inline-block bg-success rounded-circle me-1" style="width: 6px; height: 6px;"></span> Sukses
                                        </span>
                                    @elseif($trx->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <span class="d-inline-block bg-warning rounded-circle me-1" style="width: 6px; height: 6px;"></span> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <span class="d-inline-block bg-danger rounded-circle me-1" style="width: 6px; height: 6px;"></span> Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    <span class="d-block mb-1 fs-3"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-mailbox mb-2 text-muted" viewBox="0 0 16 16"><path d="M3 10.5V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v5.5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2M5 3a3 3 0 0 0-3 3v4.5A4 4 0 0 0 6 14.5h4a4 4 0 0 0 4-4V6a3 3 0 0 0-3-3z"/></svg></span>
                                    Belum ada transaksi simpanan yang dicatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-modern p-3">
            <h6 class="fw-bold text-dark mb-3">Pintasan Menu Cepat</h6>
            <div class="d-grid gap-2">
                
                @if(auth()->check() && auth()->user()->role == 'admin')
                    <a href="/anggota/create" class="btn btn-light text-start border border-light-subtle rounded-3 py-2 small d-flex justify-content-between align-items-center">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person me-1 mb-1" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/></svg> Input Anggota Baru</span>
                        <span class="text-muted-light">→</span>
                    </a>
                    <a href="/pinjaman" class="btn btn-light text-start border border-light-subtle rounded-3 py-2 small d-flex justify-content-between align-items-center">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text me-1 mb-1" viewBox="0 0 16 16"><path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/><path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg> Review Ajuan Pinjaman</span>
                        @if(isset($pinjamanPending) && $pinjamanPending > 0)
                            <span class="badge bg-danger rounded-pill px-2">{{ $pinjamanPending }}</span>
                        @else
                            <span class="text-muted-light">→</span>
                        @endif
                    </a>
                    <a href="/jenis-simpanan" class="btn btn-light text-start border border-light-subtle rounded-3 py-2 small d-flex justify-content-between align-items-center">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear me-1 mb-1" viewBox="0 0 16 16"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/></svg> Kelola Jenis Simpanan</span>
                        <span class="text-muted-light">→</span>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->role == 'bendahara')
                    <a href="/simpanan" class="btn btn-light text-start border border-light-subtle rounded-3 py-2 small d-flex justify-content-between align-items-center">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2 me-1 mb-1" viewBox="0 0 16 16"><path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/></svg> Cek Verifikasi Simpanan</span>
                        @if(isset($pendingCount) && $pendingCount > 0)
                            <span class="badge bg-warning text-dark rounded-pill px-2">{{ $pendingCount }}</span>
                        @else
                            <span class="text-muted-light">→</span>
                        @endif
                    </a>
                    <a href="/transaksi-angsuran" class="btn btn-light text-start border border-light-subtle rounded-3 py-2 small d-flex justify-content-between align-items-center">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card me-1 mb-1" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/></svg> Terima Bayar Angsuran</span>
                        <span class="text-muted-light">→</span>
                    </a>
                    <a href="/laporan" class="btn btn-light text-start border border-light-subtle rounded-3 py-2 small d-flex justify-content-between align-items-center">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-fill me-1 mb-1" viewBox="0 0 16 16"><path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/></svg> Buka Laporan Keuangan</span>
                        <span class="text-muted-light">→</span>
                    </a>
                @endif

            </div>

            <div class="mt-auto pt-3 border-top text-muted-light small">
                <div class="d-flex justify-content-between mb-1">
                    <span>User Login:</span>
                    <span class="text-primary fw-medium">{{ auth()->user()->nama ?? 'Pengurus' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Role Akses:</span>
                    <span class="badge bg-dark fw-medium text-uppercase">{{ auth()->user()->role ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection