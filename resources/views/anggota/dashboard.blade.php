@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="/css/dashboard-anggota.css">

{{-- Header --}}
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Selamat datang, {{ explode(' ', $user->name)[0] }} 👋</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/anggota/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Dashboard</li>
            </ol>
        </nav>
        <p class="text-muted-light small mt-2 mb-0">
            Ringkasan aktivitas keuangan Anda di koperasi.
        </p>
    </div>

    <div></div>
</div>

{{-- Top Stats --}}
<div class="row g-3 mb-4">

    {{-- Simpanan --}}
    <div class="col-md-6 col-xl-4">
        <div class="kop-card p-4 h-100">
            <p class="kop-label mb-3">Total Simpanan</p>
            <p class="kop-amount mb-0">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</p>
            <div class="kop-divider"></div>
            <div class="d-flex gap-4">
                <div class="kop-stat-mini">
                    <span>Pokok</span>
                    <span>Rp {{ number_format($simpPokok, 0, ',', '.') }}</span>
                </div>
                <div class="kop-stat-mini">
                    <span>Wajib</span>
                    <span>Rp {{ number_format($simpWajib, 0, ',', '.') }}</span>
                </div>
                <div class="kop-stat-mini">
                    <span>Sukarela</span>
                    <span>Rp {{ number_format($simpSukarela, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Pinjaman --}}
    <div class="col-md-6 col-xl-4">
        <div class="kop-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="kop-label mb-0">Sisa Pinjaman</p>
                @if($pinjamanAktif)
                    <span class="kop-pill kop-pill-warning">
                        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                        Aktif
                    </span>
                @else
                    <span class="kop-pill kop-pill-success">
                        <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                        Lunas
                    </span>
                @endif
            </div>
            <p class="kop-amount mb-0">Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</p>
            <div class="kop-divider"></div>
            @if($pinjamanAktif)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:0.72rem;color:var(--kop-muted);">Progress {{ $progressBulan }}/{{ $totalBulan }} bulan</span>
                    <span style="font-size:0.72rem;font-weight:700;color:var(--kop-ink);">{{ $persenProgress }}%</span>
                </div>
                <div class="kop-progress-track">
                    <div class="kop-progress-fill" id="kopProgressBar"></div>
                </div>
                <script>
                    document.getElementById('kopProgressBar').style.width = '{{ $persenProgress }}%';
                </script>
            @else
                <p style="font-size:0.78rem;color:var(--kop-dim);margin:0;">Tidak ada pinjaman aktif saat ini.</p>
            @endif
        </div>
    </div>

    {{-- Member Card --}}
    <div class="col-md-12 col-xl-4">
        <div class="kop-member-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-4" style="position:relative;z-index:1;">
                <span class="kop-label" style="color:rgba(255,255,255,0.4);">Keanggotaan</span>
                <span style="font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.5);background:rgba(255,255,255,0.1);padding:3px 10px;border-radius:20px;">
                    {{ $anggota->no_anggota }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-3 mb-4" style="position:relative;z-index:1;">
                <div class="kop-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                <div>
                    <p style="font-weight:700;font-size:0.95rem;margin:0;color:#fff;">{{ $user->name }}</p>
                    <p style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin:0;">
                        Bergabung {{ \Carbon\Carbon::parse($anggota->tgl_gabung)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:1rem;position:relative;z-index:1;">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:0.72rem;color:rgba(255,255,255,0.4);">Estimasi SHU Tahun Depan</span>
                    <span style="font-size:0.9rem;font-weight:700;color:#fff;">Rp {{ number_format($estimasiShu, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Bottom Row --}}
<div class="row g-3">

    {{-- Riwayat Transaksi --}}
    <div class="col-lg-8">
        <div class="kop-card h-100 overflow-hidden">
            <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid var(--kop-border);">
                <p style="font-size:0.88rem;font-weight:700;color:var(--kop-ink);margin:0;">Riwayat Transaksi</p>
                <a href="/anggota/simpanan" style="font-size:0.78rem;color:var(--kop-primary);font-weight:600;text-decoration:none;">Lihat semua →</a>
            </div>
            <div class="table-responsive">
                <table class="table kop-table mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Transaksi</th>
                            <th>Nominal</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatTransaksi as $trx)
                        <tr>
                            <td style="color:var(--kop-muted);white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="kop-trx-icon {{ $trx->jenis == 'setor' ? 'in' : 'out' }}">
                                        {{ $trx->jenis == 'setor' ? '↓' : '↑' }}
                                    </span>
                                    <span style="font-weight:500;">{{ $trx->keterangan }}</span>
                                </div>
                            </td>
                            <td class="{{ $trx->jenis == 'setor' ? 'kop-trx-amount-in' : 'kop-trx-amount-out' }}" style="white-space:nowrap;">
                                {{ $trx->jenis == 'setor' ? '+' : '−' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <span class="kop-pill kop-pill-success">Sukses</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color:var(--kop-dim);font-size:0.82rem;">
                                Belum ada riwayat transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Layanan --}}
    <div class="col-lg-4">
        <div class="kop-card p-4 h-100">
            <p style="font-size:0.88rem;font-weight:700;color:var(--kop-ink);margin:0 0 1rem;">Layanan Anggota</p>
            <div class="d-flex flex-column gap-2">
                <a href="/anggota/ajukan-pinjaman" class="kop-service-btn">
                    <div class="kop-service-icon indigo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="kop-service-title mb-0">Ajukan Pinjaman</p>
                        <p class="kop-service-sub mb-0">Proses cepat &amp; mudah</p>
                    </div>
                </a>
                <a href="/anggota/angsuran" class="kop-service-btn">
                    <div class="kop-service-icon amber">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="kop-service-title mb-0">Jadwal Angsuran</p>
                        @if($jatuhTempoNext)
                            <p class="kop-service-sub danger mb-0">
                                Jatuh tempo: {{ \Carbon\Carbon::parse($jatuhTempoNext)->translatedFormat('d M Y') }}
                            </p>
                        @else
                            <p class="kop-service-sub mb-0">Tidak ada tagihan bulan ini</p>
                        @endif
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection