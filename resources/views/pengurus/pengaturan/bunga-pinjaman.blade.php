@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/bunga.css') }}">
@endpush

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Pengaturan Bunga Pinjaman</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Pengaturan Bunga</li>
            </ol>
        </nav>
    </div>
    <div></div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: var(--radius); background-color: #d1fae5; color: #065f46;" role="alert">
        <strong class="fw-bold">Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card-modern">
            <div class="loan-form-header">
                <h6 class="fw-bold text-dark mb-1">Bunga Flat per Tenor</h6>
                <span class="text-muted-light small">Persentase bunga dihitung flat per bulan dari nominal pokok pinjaman.</span>
            </div>

            <div class="p-4">
                <form action="{{ route('pengaturan.bunga-pinjaman.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="bunga-tenor-grid">
                        @foreach($pengaturanBunga as $item)
                        <div class="bunga-tenor-item">
                            <label class="form-label fw-semibold text-dark small mb-2">Tenor {{ $item->tenor }} Bulan</label>
                            <div class="bunga-input-group">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    max="99.99"
                                    name="bunga[{{ $item->tenor }}]"
                                    value="{{ old('bunga.' . $item->tenor, $item->bunga_persen) }}"
                                    class="bunga-input @error('bunga.' . $item->tenor) is-invalid @enderror"
                                    placeholder="0.00"
                                    required
                                >
                                <span class="bunga-input-suffix">% / bln</span>
                            </div>
                            @error('bunga.' . $item->tenor)
                                <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>

                    <div class="loan-hint mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
                        <span>Perubahan ini akan langsung berlaku untuk semua pengajuan pinjaman baru, termasuk simulasi angsuran. Pinjaman yang sudah berjalan <strong>tidak akan terpengaruh</strong>.</span>
                    </div>

                    <div class="d-flex justify-content-end pt-3" style="border-top: 1px solid var(--border);">
                        <button type="submit" class="btn btn-primary fw-medium px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" style="background-color: var(--primary); border: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card-modern p-4 h-100 d-flex flex-column">
            <h6 class="fw-bold text-dark mb-3">Bagaimana Bunga Dihitung?</h6>
            
            <div class="bunga-formula mb-3">
                <span class="bunga-formula-label">Angsuran per Bulan</span>
                <code class="bunga-formula-code">(Pokok ÷ Tenor) + (Pokok × Bunga%)</code>
            </div>
            
            <p class="text-muted-light small mb-0" style="line-height: 1.6;">
                <strong>Contoh Simulasi:</strong><br>
                Pinjaman <strong>Rp 5.000.000</strong> dengan tenor <strong>12 bulan</strong> dan bunga <strong>2% per bulan</strong> akan menghasilkan angsuran sekitar <strong>Rp 516.667</strong> per bulan.
            </p>
        </div>
    </div>
</div>
@endsection