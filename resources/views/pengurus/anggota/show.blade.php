@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Profil Detail Anggota</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/anggota" class="text-decoration-none text-muted-light">Data Anggota</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Detail Profil</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex flex-row gap-2">
        <a href="/anggota" class="btn btn-outline-secondary btn-sm rounded-3 fw-medium px-3">← Kembali</a>
        <a href="/anggota/{{ $anggota->id }}/edit" class="btn btn-primary btn-sm rounded-3 fw-medium px-4 shadow-sm">✏️ Edit Data</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-modern border-0 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom border-light">
                @if($anggota->foto_ktp)
                    <img src="{{ asset('storage/' . $anggota->foto_ktp) }}" alt="Foto" class="rounded-circle object-fit-cover shadow-sm" style="width: 80px; height: 80px;">
                @else
                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($anggota->user->nama ?? 'A', 0, 2)) }}
                    </div>
                @endif

                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ $anggota->user->nama ?? 'Tanpa Nama' }}</h4>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border border-light-subtle px-2 py-1">{{ $anggota->no_anggota }}</span>
                        @if($anggota->status == 'aktif')
                            <span class="badge bg-success-subtle text-success px-2 py-1">Aktif</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-2 py-1">Non-Aktif</span>
                        @endif
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-3">Informasi Pribadi</h6>
            <div class="row g-4">
                <div class="col-sm-6">
                    <span class="d-block text-muted-light small mb-1">Nomor Induk Kependudukan (NIK)</span>
                    <span class="fw-medium text-dark">{{ $anggota->nik }}</span>
                </div>
                <div class="col-sm-6">
                    <span class="d-block text-muted-light small mb-1">Alamat Email Login</span>
                    <span class="fw-medium text-dark">{{ $anggota->user->email ?? '-' }}</span>
                </div>
                <div class="col-sm-6">
                    <span class="d-block text-muted-light small mb-1">Nomor Handphone (WhatsApp)</span>
                    <span class="fw-medium text-dark">{{ $anggota->no_hp }}</span>
                </div>
                <div class="col-sm-6">
                    <span class="d-block text-muted-light small mb-1">Tanggal Bergabung</span>
                    <span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($anggota->tgl_gabung)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="col-12">
                    <span class="d-block text-muted-light small mb-1">Alamat Domisili Lengkap</span>
                    <span class="fw-medium text-dark">{{ $anggota->alamat }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-modern border-0 shadow-sm p-4 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-white-50 small fw-medium">Total Saldo Tabungan</span>
                <span class="badge bg-white text-primary rounded-pill px-2" style="font-size: 0.7rem;">Hak Anggota</span>
            </div>
            <h2 class="fw-bold mb-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h2>
            <span class="text-white-50 small">Akumulasi seluruh jenis simpanan</span>
        </div>

        <div class="card card-modern border-0 shadow-sm p-4 border-top border-warning border-3">
            <h6 class="fw-bold text-dark mb-3">Status Pinjaman</h6>
            
            @if($pinjamanAktif)
                <div class="alert {{ $pinjamanAktif->status == 'menunggu' ? 'bg-warning-subtle text-warning-emphasis border-warning-subtle' : 'bg-primary-subtle text-primary border-primary-subtle' }} p-3 rounded-3 mb-0">
                    <span class="d-block small fw-bold mb-1 text-uppercase">{{ $pinjamanAktif->status == 'menunggu' ? 'Sedang Dievaluasi' : 'Pinjaman Berjalan' }}</span>
                    <h5 class="fw-bold mb-2">Rp {{ number_format($pinjamanAktif->jumlah_pinjaman, 0, ',', '.') }}</h5>
                    <a href="/pinjaman/{{ $pinjamanAktif->id }}" class="btn btn-sm btn-light w-100 fw-medium shadow-sm">Cek Detail Pinjaman</a>
                </div>
            @else
                <div class="text-center py-3">
                    <span class="d-block mb-2 fs-3">✨</span>
                    <span class="d-block fw-medium text-dark mb-1">Bersih dari Hutang</span>
                    <span class="text-muted-light small">Anggota ini tidak memiliki pinjaman yang sedang berjalan.</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection