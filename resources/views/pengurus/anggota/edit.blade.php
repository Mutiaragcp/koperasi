@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Edit Data Anggota</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/anggota" class="text-decoration-none text-muted-light">Data Anggota</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Anggota</li>
            </ol>
        </nav>
        <p class="text-muted-light small mt-2 mb-0">Perbarui informasi identitas dan akun milik <b>{{ $anggota->user->nama }}</b>.</p>
    </div>
    
    <div>
        <a href="/anggota" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
    <div class="d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>
        <strong>Gagal Menyimpan!</strong> Silakan periksa kembali isian form Anda di bawah.
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="/anggota/{{ $anggota->id }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">1. Informasi Akun Sistem</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Nama Lengkap (Sesuai KTP) <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control bg-light border-0 py-2 @error('nama') is-invalid @enderror" value="{{ old('nama', $anggota->user->nama) }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Email (Username Login) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $anggota->user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Reset Password (Opsional)</label>
                        <input type="password" name="password" class="form-control bg-light border-0 py-2 @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin ganti password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">2. Identitas & Dokumen</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">NIK (16 Digit) <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control bg-light border-0 py-2 @error('nik') is-invalid @enderror" value="{{ old('nik', $anggota->nik) }}" required>
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control bg-light border-0 py-2 @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Tanggal Gabung</label>
                        <input type="date" class="form-control bg-light border-0 py-2 text-muted" value="{{ \Carbon\Carbon::parse($anggota->tgl_gabung)->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select bg-light border-0 py-2">
                            <option value="aktif" {{ old('status', $anggota->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $anggota->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Update Foto KTP (Opsional)</label>
                        <input type="file" name="foto_ktp" class="form-control bg-light border-0 py-2 @error('foto_ktp') is-invalid @enderror" accept="image/*">
                        @error('foto_ktp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <span class="d-block mt-2 small text-muted-light">
                            Biarkan kosong jika tidak ingin mengubah KTP. 
                            @if($anggota->foto_ktp)
                                <a href="{{ asset('storage/' . $anggota->foto_ktp) }}" target="_blank" class="text-primary text-decoration-none fw-medium">Lihat KTP saat ini</a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-modern border-0 shadow-sm p-4">
                <label class="form-label fw-semibold small">Alamat Domisili Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control bg-light border-0 py-2 @error('alamat') is-invalid @enderror" rows="2" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="/anggota" class="btn btn-light px-4 py-2 fw-medium border-light-subtle">Batal</a>
        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow">Simpan Perubahan</button>
    </div>
</form>
@endsection