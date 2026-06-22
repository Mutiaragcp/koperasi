@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Form Pengajuan Pinjaman</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/pinjaman" class="text-decoration-none text-muted-light">Pinjaman</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Ajukan Pinjaman</li>
            </ol>
        </nav>
        <p class="text-muted-light small mt-2 mb-0">Input data pengajuan kredit/pinjaman baru untuk anggota koperasi.</p>
    </div>
    
    <div>
        <a href="/pinjaman" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
    <strong>Gagal Menyimpan!</strong> Silakan periksa kembali isian form Anda.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="/pinjaman" method="POST">
    @csrf

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">1. Data Peminjam</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Pilih Anggota Koperasi <span class="text-danger">*</span></label>
                        <select name="anggota_id" class="form-select bg-light border-0 py-2 @error('anggota_id') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Cari Nama atau No ID Anggota --</option>
                            @foreach($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                    {{ $anggota->user->nama ?? 'Tanpa Nama' }} ({{ $anggota->no_anggota }})
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Tanggal Pengajuan <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_pengajuan" class="form-control bg-light border-0 py-2 @error('tgl_pengajuan') is-invalid @enderror" value="{{ old('tgl_pengajuan', date('Y-m-d')) }}" required>
                        @error('tgl_pengajuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Penanggung Jawab / Jaminan (Opsional)</label>
                        <input type="text" class="form-control bg-light border-0 py-2" placeholder="Contoh: BPKB Motor / Nama Pasangan">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">2. Nominal & Tenor</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Nominal Pengajuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-semibold text-muted" style="font-size: 0.9rem;">Rp</span>
                            <input type="number" name="jumlah_pinjaman" class="form-control bg-light border-0 py-2 @error('jumlah_pinjaman') is-invalid @enderror" value="{{ old('jumlah_pinjaman') }}" placeholder="Contoh: 5000000" min="1000" required>
                            @error('jumlah_pinjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Lama Pinjaman (Tenor) <span class="text-danger">*</span></label>
                        <select name="tenor" class="form-select bg-light border-0 py-2 @error('tenor') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Pilih Bulan --</option>
                            <option value="3" {{ old('tenor') == '3' ? 'selected' : '' }}>3 Bulan</option>
                            <option value="6" {{ old('tenor') == '6' ? 'selected' : '' }}>6 Bulan</option>
                            <option value="12" {{ old('tenor') == '12' ? 'selected' : '' }}>12 Bulan</option>
                            <option value="24" {{ old('tenor') == '24' ? 'selected' : '' }}>24 Bulan</option>
                        </select>
                        @error('tenor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Estimasi Bunga (%) per Bulan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="bunga" step="0.1" class="form-control bg-light border-0 py-2 @error('bunga') is-invalid @enderror" value="{{ old('bunga', '1.5') }}" required>
                            <span class="input-group-text bg-light border-0 fw-semibold text-muted" style="font-size: 0.9rem;">%</span>
                            @error('bunga') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-modern border-0 shadow-sm p-4">
                <label class="form-label fw-semibold small">Tujuan Penggunaan Pinjaman (Opsional)</label>
                <textarea class="form-control bg-light border-0 py-2 mb-3" rows="2" placeholder="Contoh: Tambahan modal usaha warung sembako..."></textarea>
                
                <div class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning-emphasis small mb-0 rounded-3">
                    <strong class="fw-bold">Penting:</strong> Setelah form ini disimpan, status pinjaman akan menjadi <strong>"Menunggu Approval"</strong>. Manajer atau pengurus berwenang harus menyetujui pengajuan ini sebelum dana dapat dicairkan.
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="/pinjaman" class="btn btn-light px-4 py-2 fw-medium border-light-subtle">Batal</a>
        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">Simpan Pengajuan</button>
    </div>
</form>
@endsection