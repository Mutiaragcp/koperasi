@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Edit Jenis Simpanan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/jenis-simpanan" class="text-decoration-none text-muted-light">Jenis Simpanan</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Jenis</li>
            </ol>
        </nav>
    </div>
    
    <div>
        <a href="/jenis-simpanan" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

<div class="card card-modern border-0 shadow-sm p-4 col-lg-6">
    <form action="/jenis-simpanan/{{ $jenis->id }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label fw-semibold small">Nama Jenis Simpanan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control bg-light border-0 py-2 @error('nama') is-invalid @enderror" value="{{ old('nama', $jenis->nama) }}" required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-semibold small">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control bg-light border-0 py-2" rows="3">{{ old('keterangan', $jenis->keterangan) }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="/jenis-simpanan" class="btn btn-light px-4 py-2 border-light-subtle">Batal</a>
            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">Update Kategori</button>
        </div>
    </form>
</div>
@endsection