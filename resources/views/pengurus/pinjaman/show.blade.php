@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($pinjaman->status == 'menunggu')
    <div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Persetujuan Pengajuan Pinjaman</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small mt-1">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/pinjaman" class="text-decoration-none text-muted-light">Data Pinjaman</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Detail & Approval</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/pinjaman" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
        </div>
    </div>

    <div class="alert bg-warning-subtle border border-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-between rounded-3 mb-4 p-3">
        <div class="d-flex align-items-center gap-2 small">
            <span class="fw-medium">Pengajuan ini berstatus <strong>Pending (Menunggu Persetujuan Anda)</strong>.</span>
        </div>
        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill small fw-semibold">MENUNGGU REVIEW</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-modern border-0 shadow-sm p-4 mb-4">
                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom border-light">Data Pinjaman yang Diajukan</h6>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <span class="d-block text-muted-light small mb-1">Nama Pemohon</span>
                        <h5 class="fw-bold text-dark mb-0">{{ $pinjaman->anggota->user->nama ?? 'Tanpa Nama' }}</h5>
                        <small class="text-muted">{{ $pinjaman->anggota->no_anggota ?? '-' }}</small>
                    </div>
                    
                    <div class="col-sm-6">
                        <span class="d-block text-muted-light small mb-1">Nominal Pinjaman</span>
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</h4>
                    </div>
                    
                    <div class="col-sm-6">
                        <span class="d-block text-muted-light small mb-1">Jangka Waktu (Tenor)</span>
                        <h5 class="fw-bold text-dark mb-0">{{ $pinjaman->tenor }} Bulan</h5>
                        <small class="text-muted">Bunga: {{ $pinjaman->bunga }}% per bulan</small>
                    </div>

                    <div class="col-sm-6">
                        <span class="d-block text-muted-light small mb-1">Tanggal Pengajuan</span>
                        <h5 class="fw-bold text-dark mb-0">{{ \Carbon\Carbon::parse($pinjaman->tgl_pengajuan)->translatedFormat('d F Y') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-modern border-0 shadow-sm p-4 border-top border-primary border-3">
                <h6 class="fw-bold text-dark mb-3">Panel Persetujuan</h6>
                
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'bendahara']))
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark mb-1">Catatan Keputusan</label>
                        <textarea class="form-control shadow-none small" rows="3" placeholder="Tuliskan alasan..."></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <form action="/pinjaman/{{ $pinjaman->id }}/approve" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-success fw-medium py-2" onclick="return confirm('Yakin ingin menyetujui pinjaman ini? Sistem akan otomatis mencetak jadwal angsuran.')">Setujui Pengajuan</button>
                        </form>
                        <form action="/pinjaman/{{ $pinjaman->id }}/reject" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger fw-medium py-2" onclick="return confirm('Yakin ingin menolak pengajuan ini?')">Tolak Pengajuan</button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info border-0 small mb-0">
                        Hanya Admin atau Bendahara yang berhak menyetujui atau menolak pengajuan pinjaman ini.
                    </div>
                @endif
                
            </div>
        </div>
    </div>

@elseif(in_array($pinjaman->status, ['disetujui', 'lunas']))
    <div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Detail & Histori Angsuran Pinjaman</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small mt-1">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/pinjaman" class="text-decoration-none text-muted-light">Data Pinjaman</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Detail Data</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-row gap-2">
            <a href="/pinjaman" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
            <button class="btn btn-success btn-sm rounded-3 fw-medium px-4 shadow-sm" onclick="window.print()">Cetak Perjanjian (PDF)</button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-5">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.25rem;">
                        {{ strtoupper(substr($pinjaman->anggota->user->nama ?? 'A', 0, 2)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $pinjaman->anggota->user->nama ?? 'Tanpa Nama' }}</h5>
                        <span class="text-muted small">ID Anggota: {{ $pinjaman->anggota->no_anggota ?? '-' }}</span>
                    </div>
                    <div class="ms-auto">
                        @if($pinjaman->status == 'lunas')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold">LUNAS</span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold">Berjalan</span>
                        @endif
                    </div>
                </div>
                <div class="border-top border-light-subtle pt-3 mt-3">
                    <span class="d-block text-muted-light small fw-medium mb-1">Tanggal Cair</span>
                    <span class="text-dark fw-semibold">{{ \Carbon\Carbon::parse($pinjaman->tgl_cair)->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card card-modern border-0 shadow-sm p-4 h-100 bg-primary text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                <div class="row h-100 align-items-center">
                    <div class="col-sm-4 mb-3 mb-sm-0 border-end border-light border-opacity-25">
                        <span class="d-block text-white-50 small fw-medium mb-1">Total Pinjaman</span>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</h3>
                    </div>
                    <div class="col-sm-4 mb-3 mb-sm-0 border-end border-light border-opacity-25 px-sm-4">
                        <span class="d-block text-white-50 small fw-medium mb-1">Cicilan Per Bulan</span>
                        <h3 class="fw-bold mb-0 text-warning">Rp {{ number_format($cicilan_per_bulan ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="col-sm-4 px-sm-4">
                        <span class="d-block text-white-50 small fw-medium mb-1">Sisa Hutang Pokok</span>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($sisa_hutang_pokok ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">Rincian Jadwal Angsuran</h6>
            <span class="text-muted small d-none d-sm-block">Untuk membayar angsuran, silakan gunakan menu <strong>Bayar Angsuran</strong> di sidebar.</span>
        </div>
        <div class="table-responsive">
            <table class="table datatable-modern table-hover align-middle mb-0 w-100">
                <thead class="table-light text-muted small fw-bold uppercase">
                    <tr>
                        <th class="py-3 ps-4" style="width: 80px;">Bulan Ke</th>
                        <th class="py-3">Jatuh Tempo</th>
                        <th class="py-3">Nominal Cicilan</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end pe-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman->angsurans as $angsuran)
                    <tr>
                        <td class="py-3 ps-4 text-center fw-bold">{{ $angsuran->ke_berapa }}</td>
                        <td class="py-3 text-muted-light fw-medium">
                            {{ \Carbon\Carbon::parse($angsuran->tgl_jatuh_tempo)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3 fw-bold text-dark">
                            Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="py-3">
                            @if($angsuran->status == 'lunas')
                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Sudah Dibayar</span>
                            @elseif($angsuran->status == 'telat')
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Terlambat</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2">Belum Dibayar</span>
                            @endif
                        </td>
                        <td class="py-3 text-end pe-4">
                            @if($angsuran->status !== 'lunas')
                                <span class="text-muted small">Menunggu Pembayaran</span>
                            @else
                                <span class="text-success small fw-medium">✓ Dibayar pada {{ \Carbon\Carbon::parse($angsuran->tgl_bayar)->translatedFormat('d M Y') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">Jadwal angsuran belum digenerate.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@elseif($pinjaman->status == 'ditolak')
    <div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Detail Pinjaman (Ditolak)</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small mt-1">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/pinjaman" class="text-decoration-none text-muted-light">Data Pinjaman</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Ditolak</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="/pinjaman" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
        </div>
    </div>

    <div class="alert bg-danger-subtle border border-danger-subtle text-danger-emphasis d-flex flex-column align-items-center justify-content-center rounded-3 p-5 text-center mt-4">
        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 64px; height: 64px; font-size: 2rem;">
            ✕
        </div>
        <h5 class="fw-bold mb-2">Pengajuan Pinjaman Ditolak</h5>
        <p class="text-muted small mb-4">Pengajuan pinjaman atas nama <strong>{{ $pinjaman->anggota->user->nama ?? 'Tanpa Nama' }}</strong> sebesar Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }} telah ditolak oleh Admin/Bendahara.</p>
        
        <a href="/pinjaman" class="btn btn-danger px-4 py-2 rounded-pill fw-medium shadow-sm">Kembali ke Daftar Pinjaman</a>
    </div>
@endif

@endsection