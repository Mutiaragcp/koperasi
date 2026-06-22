@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Setor Simpanan Mandiri</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/anggota/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/anggota/simpanan" class="text-decoration-none text-muted-light">Kas Simpanan</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Setor Mandiri</li>
            </ol>
        </nav>
    </div>
    
    <div>
        <a href="/anggota/simpanan" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-12 col-lg-7">
        <div class="card card-modern border-0 shadow-sm">
            <div class="card-header bg-white border-bottom border-light p-4">
                <h6 class="fw-bold text-dark mb-1">Form Pengajuan Setor</h6>
                <span class="text-muted-light small">Isi detail setoran dan lampirkan bukti transfer Anda.</span>
            </div>
            <div class="card-body p-4">
                
                {{-- Notifikasi Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                        <div class="d-flex align-items-center gap-2 fw-medium mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Mohon periksa kembali:
                        </div>
                        <ul class="mb-0 small ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/anggota/simpanan/setor" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small">Pilih Jenis Simpanan <span class="text-danger">*</span></label>
                        <select name="jenis_simpanan_id" class="form-select border-light-subtle shadow-none" required>
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            @foreach($jenisSimpanan as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small">Nominal Setoran (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light-subtle text-muted-light">Rp</span>
                            <input type="number" name="jumlah" class="form-control border-light-subtle shadow-none" placeholder="Contoh: 100000" min="10000" required>
                        </div>
                        <small class="text-muted-light" style="font-size: 0.7rem;">Minimal setoran adalah Rp 10.000</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small">Upload Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" name="bukti_transaksi" class="form-control border-light-subtle shadow-none form-control-sm" accept="image/jpeg, image/png, image/jpg" required>
                        <small class="text-muted-light d-block mt-1" style="font-size: 0.7rem;">Format yang diizinkan: JPG, JPEG, PNG. Maksimal ukuran file 2 MB.</small>
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary fw-medium px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card card-modern border-0 shadow-sm bg-primary-subtle text-primary h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Informasi Pembayaran</h6>
                        <span class="small opacity-75">Rekening Resmi Koperasi</span>
                    </div>
                </div>
                
                <p class="small mb-4 opacity-75">
                    Silakan lakukan transfer ke salah satu rekening di bawah ini sebelum mengisi form di samping. Pastikan nominal transfer sesuai dengan yang Anda ajukan.
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="bg-white rounded-3 p-3 shadow-sm">
                        <span class="d-block text-muted small fw-medium mb-1">Bank BRI</span>
                        <h5 class="fw-bold text-dark mb-0">1234-5678-9101-112</h5>
                        <span class="small text-muted" style="font-size: 0.75rem;">a.n Koperasi SIKOPSIM</span>
                    </div>
                    
                    <div class="bg-white rounded-3 p-3 shadow-sm">
                        <span class="d-block text-muted small fw-medium mb-1">Bank BCA</span>
                        <h5 class="fw-bold text-dark mb-0">0987-6543-21</h5>
                        <span class="small text-muted" style="font-size: 0.75rem;">a.n Koperasi SIKOPSIM</span>
                    </div>

                    <div class="bg-white rounded-3 p-3 shadow-sm">
                        <span class="d-block text-muted small fw-medium mb-1">DANA / OVO / GoPay</span>
                        <h5 class="fw-bold text-dark mb-0">0812-3456-7890</h5>
                        <span class="small text-muted" style="font-size: 0.75rem;">a.n Bendahara Koperasi</span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-white bg-opacity-50 rounded-3 border border-primary border-opacity-25">
                    <div class="d-flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="small fw-medium" style="font-size: 0.75rem;">
                            Proses verifikasi membutuhkan waktu maksimal 1x24 jam pada hari kerja. Saldo akan otomatis bertambah setelah disetujui.
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection