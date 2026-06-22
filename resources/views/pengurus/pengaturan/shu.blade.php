@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Pengaturan Persentase SHU</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/shu" class="text-decoration-none text-muted-light">Akuntansi SHU</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Pengaturan Persentase</li>
            </ol>
        </nav>
    </div>

    <div></div>
</div>

{{-- Notifikasi Sukses / Error dari Controller --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error') || $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center gap-2 mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <strong>Gagal Menyimpan!</strong>
        </div>
        <ul class="mb-0 small ps-4">
            @if(session('error')) <li>{{ session('error') }}</li> @endif
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Sisi Kiri: Form Input --}}
    <div class="col-12 col-lg-8">
        <div class="card card-modern border-0 shadow-sm">
            <div class="card-header bg-white border-bottom border-light p-4">
                <h6 class="fw-bold text-dark mb-1">Alokasi Pembagian Sisa Hasil Usaha (SHU)</h6>
                <span class="text-muted-light small">Sesuaikan angka di bawah ini dengan hasil keputusan Rapat Anggota Tahunan (RAT) terbaru.</span>
            </div>
            
            <div class="card-body p-4">
                <form action="/shu/pengaturan" method="POST" id="formPengaturanSHU">
                    @csrf

                    <div class="row g-4">
                        {{-- Dana Anggota --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark small">Bagian Anggota (Jasa Modal & Usaha)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="persen_anggota" class="form-control persen-input border-light-subtle shadow-none" value="{{ old('persen_anggota', $pengaturan->persen_anggota) }}" required>
                                <span class="input-group-text bg-light border-light-subtle">%</span>
                            </div>
                        </div>

                        {{-- Dana Cadangan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark small">Cadangan Koperasi</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="persen_cadangan" class="form-control persen-input border-light-subtle shadow-none" value="{{ old('persen_cadangan', $pengaturan->persen_cadangan) }}" required>
                                <span class="input-group-text bg-light border-light-subtle">%</span>
                            </div>
                        </div>

                        {{-- Dana Pengurus --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark small">Dana Pengurus & Pengawas</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="persen_pengurus" class="form-control persen-input border-light-subtle shadow-none" value="{{ old('persen_pengurus', $pengaturan->persen_pengurus) }}" required>
                                <span class="input-group-text bg-light border-light-subtle">%</span>
                            </div>
                        </div>

                        {{-- Dana Karyawan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark small">Dana Karyawan / Admin</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="persen_karyawan" class="form-control persen-input border-light-subtle shadow-none" value="{{ old('persen_karyawan', $pengaturan->persen_karyawan) }}" required>
                                <span class="input-group-text bg-light border-light-subtle">%</span>
                            </div>
                        </div>

                        {{-- Dana Pendidikan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark small">Dana Pendidikan</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="persen_pendidikan" class="form-control persen-input border-light-subtle shadow-none" value="{{ old('persen_pendidikan', $pengaturan->persen_pendidikan) }}" required>
                                <span class="input-group-text bg-light border-light-subtle">%</span>
                            </div>
                        </div>

                        {{-- Dana Sosial --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark small">Dana Sosial & Lingkungan</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="persen_sosial" class="form-control persen-input border-light-subtle shadow-none" value="{{ old('persen_sosial', $pengaturan->persen_sosial) }}" required>
                                <span class="input-group-text bg-light border-light-subtle">%</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light-subtle my-4">

                    {{-- Kalkulator Total Real-Time --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-light p-3 rounded-3 border border-light-subtle mb-4">
                        <div>
                            <span class="d-block fw-bold text-dark mb-1">Total Kalkulasi Persentase:</span>
                            <small class="text-muted" id="teksBantuan">Total seluruh alokasi wajib berjumlah tepat 100%.</small>
                        </div>
                        <div class="text-end">
                            <h3 class="fw-bold mb-0" id="totalPersenTampil">0%</h3>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" id="btnSimpan" class="btn btn-primary fw-medium px-4 py-2.5 rounded-3 shadow-sm d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Panduan --}}
    <div class="col-12 col-lg-4">
        <div class="card card-modern border-0 shadow-sm bg-primary-subtle text-primary h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-1 mb-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Informasi Penting
                </h6>
                <p class="small opacity-75 mb-3" style="line-height: 1.6;">
                    Halaman ini digunakan untuk menentukan porsi pembagian Laba Bersih / Sisa Hasil Usaha (SHU) Koperasi pada saat tutup buku tahunan.
                </p>
                <ul class="small opacity-75 ps-3 mb-0" style="line-height: 1.6;">
                    <li class="mb-2"><strong>Bagian Anggota:</strong> Akan dibagikan ke anggota secara proporsional sesuai saldo simpanan dan keaktifan meminjam.</li>
                    <li class="mb-2"><strong>Cadangan Koperasi:</strong> Dana ditahan untuk memperkuat modal dan menutup risiko kerugian.</li>
                    <li>Perubahan persentase ini akan langsung berlaku pada saat Anda mengeksekusi fitur <strong>"Bagikan SHU"</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll('.persen-input');
        const tampilTotal = document.getElementById('totalPersenTampil');
        const teksBantuan = document.getElementById('teksBantuan');
        const btnSimpan = document.getElementById('btnSimpan');

        function hitungTotal() {
            let total = 0;
            inputs.forEach(input => {
                let val = parseFloat(input.value) || 0;
                total += val;
            });

            total = Math.round(total * 100) / 100;

            tampilTotal.innerText = total + "%";

            if (total === 100) {
                tampilTotal.classList.remove('text-danger');
                tampilTotal.classList.add('text-success');
                teksBantuan.innerText = "Sempurna! Total persentase sudah tepat 100%.";
                teksBantuan.classList.remove('text-danger');
                teksBantuan.classList.add('text-success');
                btnSimpan.disabled = false;
            } else {
                tampilTotal.classList.remove('text-success');
                tampilTotal.classList.add('text-danger');
                
                if (total > 100) {
                    teksBantuan.innerText = "Melebihi batas! Kurangi " + (total - 100).toFixed(2) + "% lagi agar pas 100%.";
                } else {
                    teksBantuan.innerText = "Belum pas! Tambahkan " + (100 - total).toFixed(2) + "% lagi agar pas 100%.";
                }
                
                teksBantuan.classList.remove('text-success');
                teksBantuan.classList.add('text-danger');
                btnSimpan.disabled = true; // Kunci tombol jika belum 100%
            }
        }

        // Jalankan hitungan saat halaman pertama dibuka
        hitungTotal();

        inputs.forEach(input => {
            input.addEventListener('input', hitungTotal);
        });
    });
</script>
@endpush