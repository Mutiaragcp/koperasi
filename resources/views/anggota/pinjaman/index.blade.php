@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Pengajuan Pinjaman</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="/anggota/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Ajukan Pinjaman</li>
            </ol>
        </nav>
    </div>

    <div>
        <a href="/anggota/dashboard" class="btn btn-outline-secondary btn-sm rounded-3 fw-medium px-3 d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    
    <div class="col-12 col-lg-7">
        <div class="card-modern">
            <div class="loan-form-header">
                <h6 class="fw-bold text-dark mb-1">Formulir Pengajuan Dana</h6>
                <span class="text-muted-light small">Isi nominal dan tujuan pinjaman Anda dengan jelas.</span>
            </div>

            <div class="p-4">
                <form action="/anggota/pinjaman" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small mb-2">Nominal Pinjaman yang Diajukan <span class="text-danger">*</span></label>
                        <div class="loan-amount-input">
                            <span class="loan-amount-prefix">Rp</span>
                            <input type="text" id="inputNominalDisplay" class="loan-amount-field @error('jumlah_pinjaman') is-invalid @enderror" placeholder="5.000.000" required autocomplete="off">
                            <input type="hidden" name="jumlah_pinjaman" id="inputNominal" value="{{ old('jumlah_pinjaman') }}">
                        </div>
                        @error('jumlah_pinjaman')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <div class="loan-hint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
                            <span>Batas maksimal pinjaman Anda saat ini adalah <strong class="text-dark">Rp {{ number_format($maxPinjaman, 0, ',', '.') }}</strong>.</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small mb-2">Jangka Waktu (Tenor) <span class="text-danger">*</span></label>
                        <div class="tenor-options" id="tenorOptions">
                            @foreach($pengaturanBunga as $item)
                            <label class="tenor-chip">
                                <input type="radio" name="tenor" value="{{ $item->tenor }}" data-bunga="{{ $item->bunga_persen }}" {{ old('tenor') == $item->tenor ? 'checked' : '' }} required>
                                <span>
                                    {{ $item->tenor }} Bulan
                                    <small>{{ number_format($item->bunga_persen, 2) }}%/bln</small>
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('tenor')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small mb-2">Tujuan Penggunaan Dana <span class="text-danger">*</span></label>
                        <textarea name="tujuan" class="form-control border-light-subtle shadow-none @error('tujuan') is-invalid @enderror" rows="3" placeholder="Ceritakan secara singkat untuk apa dana ini akan digunakan (Maks. 255 karakter)..." required>{{ old('tujuan') }}</textarea>
                        @error('tujuan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="border-light-subtle my-4">

                    <div class="form-check mb-4">
                        <input class="form-check-input shadow-none" type="checkbox" id="syaratCheck" required>
                        <label class="form-check-label small text-muted-light mt-1" for="syaratCheck" style="line-height: 1.4;">
                            Saya menyetujui seluruh <a href="#" class="text-decoration-none fw-medium">syarat dan ketentuan</a> Koperasi SIKOPSIM, bersedia mematuhi jadwal pembayaran angsuran bulanan, dan memberikan informasi yang sebenar-benarnya.
                        </label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary fw-medium px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            Kirim Pengajuan ke Manajer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="sim-card">
            <div class="sim-card-header">
                <div class="sim-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-9m-2 9h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Simulasi Angsuran</h6>
                    <span class="small text-muted-light">Estimasi otomatis berdasarkan isian Anda</span>
                </div>
            </div>

            <div class="sim-result">
                <span class="sim-result-label">Estimasi Angsuran per Bulan</span>
                <div class="sim-result-amount" id="simAngsuran">Rp 0</div>
                <span class="sim-result-note" id="simTenorLabel">Isi nominal &amp; pilih tenor untuk melihat estimasi</span>
            </div>

            <div class="sim-breakdown">
                <div class="sim-breakdown-row">
                    <span>Pokok Pinjaman</span>
                    <strong id="simPokok">Rp 0</strong>
                </div>
                <div class="sim-breakdown-row">
                    <span>Bunga Flat (<span id="simBungaPersen">0</span>%/bulan)</span>
                    <strong id="simBunga">Rp 0</strong>
                </div>
                <div class="sim-breakdown-row sim-breakdown-total">
                    <span>Total Pengembalian</span>
                    <strong id="simTotal">Rp 0</strong>
                </div>
            </div>

            <div class="sim-info-list">
                <div class="sim-info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-warning"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Proses peninjauan oleh Bendahara &amp; Ketua maksimal <strong>1x24 jam</strong>.</span>
                </div>
                <div class="sim-info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-danger"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>Pengajuan ditolak otomatis jika masih ada pinjaman aktif atau status "Menunggu".</span>
                </div>
            </div>

            <div class="sim-cta">
                <span>Butuh nominal lebih besar atau ada pertanyaan?</span>
                <a href="#" class="fw-medium text-primary text-decoration-none">Hubungi admin via WhatsApp &rarr;</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputNominalDisplay = document.getElementById('inputNominalDisplay');
    const inputNominal  = document.getElementById('inputNominal');
    const tenorOptions  = document.getElementById('tenorOptions');
    const simAngsuran   = document.getElementById('simAngsuran');
    const simTenorLabel = document.getElementById('simTenorLabel');
    const simPokok      = document.getElementById('simPokok');
    const simBunga      = document.getElementById('simBunga');
    const simBungaPersen= document.getElementById('simBungaPersen');
    const simTotal      = document.getElementById('simTotal');

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function hitungSimulasi() {
        const nominal = parseFloat(inputNominal.value) || 0;
        const tenorChecked = tenorOptions.querySelector('input[name="tenor"]:checked');
        const tenor = tenorChecked ? parseInt(tenorChecked.value) : 0;
        const bungaPersen = tenorChecked ? parseFloat(tenorChecked.dataset.bunga) : 0;

        if (nominal <= 0 || tenor <= 0) {
            simAngsuran.textContent = 'Rp 0';
            simTenorLabel.textContent = 'Isi nominal & pilih tenor untuk melihat estimasi';
            simPokok.textContent = 'Rp 0';
            simBunga.textContent = 'Rp 0';
            simBungaPersen.textContent = '0';
            simTotal.textContent = 'Rp 0';
            return;
        }

        const totalBunga = nominal * (bungaPersen / 100) * tenor;
        const totalPengembalian = nominal + totalBunga;
        const angsuranPerBulan = totalPengembalian / tenor;

        simAngsuran.textContent = formatRupiah(angsuranPerBulan);
        simTenorLabel.textContent = 'Selama ' + tenor + ' bulan';
        simPokok.textContent = formatRupiah(nominal);
        simBunga.textContent = formatRupiah(totalBunga);
        simBungaPersen.textContent = bungaPersen.toFixed(2);
        simTotal.textContent = formatRupiah(totalPengembalian);
    }

    inputNominalDisplay.addEventListener('input', function(e) {
        let rawValue = this.value.replace(/[^0-9]/g, '');
        if (rawValue) {
            this.value = parseInt(rawValue, 10).toLocaleString('id-ID');
            inputNominal.value = rawValue;
        } else {
            this.value = '';
            inputNominal.value = '';
        }
        hitungSimulasi();
    });

    // Inisialisasi awal jika ada nilai old() dari Laravel
    if(inputNominal.value) {
        inputNominalDisplay.value = parseInt(inputNominal.value, 10).toLocaleString('id-ID');
    }

    tenorOptions.addEventListener('change', hitungSimulasi);

    hitungSimulasi();
});
</script>
@endpush
@endsection