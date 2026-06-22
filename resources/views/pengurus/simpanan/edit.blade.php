@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Edit Setoran Simpanan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/simpanan" class="text-decoration-none text-muted-light">Kas Simpanan</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Transaksi</li>
            </ol>
        </nav>
        <p class="text-muted-light small mt-2 mb-0">Perbarui informasi mutasi setoran kas simpanan pokok, wajib, atau sukarela anggota.</p>
    </div>
    
    <div>
        <a href="/simpanan" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

<form action="#" method="POST">
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">1. Informasi Anggota & Waktu</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Anggota Koperasi</label>
                        <select class="form-select bg-light border-0 py-2 text-dark fw-medium" disabled>
                            <option value="1" selected>Andi Wijaya (ANG-00241)</option>
                            <option value="2">Siti Rahmawati (ANG-00105)</option>
                            <option value="3">Budi Santoso (ANG-00388)</option>
                        </select>
                        <span class="text-muted small" style="font-size: 0.75rem;">*Nama pemilik simpanan tidak dapat diubah demi validitas audit laporan keuangan.</span>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Tanggal Transaksi Setoran</label>
                        <input type="date" class="form-control bg-light border-0 py-2" value="2026-06-18">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Metode Penyetoran</label>
                        <select class="form-select bg-light border-0 py-2">
                            <option value="tunai">Tunai / Cash</option>
                            <option value="transfer" selected>Transfer Bank</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-modern border-0 shadow-sm p-4 h-100">
                <h6 class="fw-bold text-primary mb-3">2. Kategori & Nominal Kas</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Jenis Simpanan</label>
                        <select class="form-select bg-light border-0 py-2">
                            <option value="pokok">Simpanan Pokok (Setoran Awal)</option>
                            <option value="wajib" selected>Simpanan Wajib (Bulanan)</option>
                            <option value="sukarela">Simpanan Sukarela</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Jumlah Setoran (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 fw-semibold text-muted" style="font-size: 0.9rem;">Rp</span>
                            <input type="number" class="form-control bg-light border-0 py-2 fw-bold text-dark" value="100000">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Nomor Referensi / Bukti Transfer (Opsional)</label>
                        <input type="text" class="form-control bg-light border-0 py-2" value="TRF-20260618-001">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-modern border-0 shadow-sm p-4">
                <label class="form-label fw-semibold small">Keterangan / Catatan Transaksi</label>
                <textarea class="form-control bg-light border-0 py-2" rows="2" placeholder="Tulis catatan tambahan di sini...">Setoran simpanan wajib reguler bulan Juni 2026.</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="/simpanan" class="btn btn-light px-4 py-2 fw-medium border-light-subtle">Batal</a>
        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">Simpan Perubahan</button>
    </div>
</form>
@endsection