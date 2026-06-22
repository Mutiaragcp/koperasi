@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Pencatatan Transaksi Simpanan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/simpanan" class="text-decoration-none text-muted-light">Kas Simpanan</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tambah Transaksi</li>
            </ol>
        </nav>
        <p class="text-muted-light small mt-2 mb-0">Input mutasi setoran kas atau penarikan simpanan anggota.</p>
    </div>
    
    <div>
        <a href="/simpanan" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
    <strong>Gagal Menyimpan!</strong> Silakan periksa kembali isian form Anda.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="/simpanan" method="POST">
    @csrf
    
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">1. Informasi Anggota & Waktu</h6>
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
                        <label class="form-label fw-semibold small">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control bg-light border-0 py-2 @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select name="jenis_transaksi" class="form-select bg-light border-0 py-2 @error('jenis_transaksi') is-invalid @enderror" required>
                            <option value="setor" {{ old('jenis_transaksi') == 'setor' ? 'selected' : '' }}>Setoran Kas (Uang Masuk)</option>
                            <option value="tarik" {{ old('jenis_transaksi') == 'tarik' ? 'selected' : '' }}>Penarikan Dana (Uang Keluar)</option>
                        </select>
                        @error('jenis_transaksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">2. Kategori & Nominal Kas</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Jenis Simpanan <span class="text-danger">*</span></label>
                        <select name="jenis_simpanan_id" class="form-select bg-light border-0 py-2 @error('jenis_simpanan_id') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Pilih Jenis Simpanan --</option>
                            @foreach($jenisSimpanans as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_simpanan_id') == $jenis->id ? 'selected' : '' }}>
                                    Simpanan {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_simpanan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Jumlah Nominal (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-semibold text-muted" style="font-size: 0.9rem;">Rp</span>
                            <input type="number" name="jumlah" class="form-control bg-light border-0 py-2 @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" placeholder="Contoh: 100000" min="1" required>
                            @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Nomor Referensi / Bukti (Opsional)</label>
                        <input type="text" name="no_referensi" class="form-control bg-light border-0 py-2" value="{{ old('no_referensi') }}" placeholder="TRF-20260618-001">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-modern border-0 shadow-sm p-4">
                <label class="form-label fw-semibold small">Keterangan / Catatan Transaksi (Opsional)</label>
                <textarea name="keterangan" class="form-control bg-light border-0 py-2" rows="2" placeholder="Tulis catatan tambahan di sini jika diperlukan...">{{ old('keterangan') }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="/simpanan" class="btn btn-light px-4 py-2 fw-medium border-light-subtle">Batal</a>
        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">Simpan Transaksi</button>
    </div>
</form>
@endsection