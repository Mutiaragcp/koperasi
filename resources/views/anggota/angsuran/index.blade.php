@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Jadwal Angsuran Pinjaman</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/anggota/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Jadwal Angsuran</li>
            </ol>
        </nav>
    </div>
    <div>
        @if(isset($semuaPinjaman) && $semuaPinjaman->count() > 1)
        <form action="/anggota/angsuran" method="GET" class="d-flex align-items-center gap-2">
            <label for="riwayatPinjaman" class="text-muted-light fw-medium small text-nowrap mb-0 d-none d-sm-block">Lihat Pinjaman Lain:</label>
            <div class="position-relative">
                <select name="id" id="riwayatPinjaman" class="form-select border-light-subtle bg-white shadow-sm fw-medium" onchange="this.form.submit()" style="min-width: 240px; border-radius: 8px;">
                    @foreach($semuaPinjaman as $p)
                    <option value="{{ $p->id }}" {{ ($pinjaman && $pinjaman->id == $p->id) ? 'selected' : '' }}>
                        Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }} 
                        ({{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('M Y') }})
                        @if($p->status == 'lunas') ✓ Lunas @else • Aktif @endif
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error') || $errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('error') ?? $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($pinjaman)
@php
$pokok_per_bulan = $pinjaman->jumlah_pinjaman / $pinjaman->tenor;
$bunga_per_bulan = $pinjaman->jumlah_pinjaman * ($pinjaman->bunga / 100);

$angsuranLunas = $pinjaman->angsurans->where('status', 'lunas')->count();
$totalLunasPokok = $angsuranLunas * $pokok_per_bulan;
$sisaPokok = $pinjaman->jumlah_pinjaman - $totalLunasPokok;
$persentase = ($pinjaman->tenor > 0) ? ($angsuranLunas / $pinjaman->tenor) * 100 : 0;

// Cari jatuh tempo terdekat yang belum dibayar
$angsuranTerdekat = $pinjaman->angsurans->whereIn('status', ['belum_bayar', 'telat'])->sortBy('tgl_jatuh_tempo')->first();
@endphp

<div class="row mb-5">
    <div class="col-12">
        <div class="card card-modern border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Left Info Panel -->
                    <div class="col-md-5 col-lg-4 bg-light p-4 d-flex flex-column justify-content-center border-end border-light-subtle">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 small fw-bold mb-1">Pinjaman Modal</span>
                                <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="bg-white rounded-3 p-3 border border-light-subtle d-flex justify-content-between align-items-center">
                            <span class="text-muted-light small fw-medium">Jatuh Tempo Terdekat:</span>
                            @if($angsuranTerdekat)
                                <span class="fw-bold text-danger">{{ \Carbon\Carbon::parse($angsuranTerdekat->tgl_jatuh_tempo)->translatedFormat('d M Y') }}</span>
                            @else
                                <span class="fw-bold text-success">Lunas Semua ✓</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Right Progress Panel -->
                    <div class="col-md-7 col-lg-8 p-4 bg-white d-flex flex-column justify-content-center">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <span class="d-block text-muted-light fw-medium small mb-1">Progress Pembayaran ({{ $angsuranLunas }} dari {{ $pinjaman->tenor }} Bulan)</span>
                                <h6 class="fw-bold text-success mb-0">Telah Dibayar: Rp {{ number_format($totalLunasPokok, 0, ',', '.') }}</h6>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-muted-light fw-medium small mb-1">Sisa Hutang Pokok</span>
                                <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($sisaPokok, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                        <div class="progress rounded-pill bg-light border border-light-subtle" style="height: 12px; overflow:visible;">
                            <div class="progress-bar bg-success rounded-pill position-relative" role="progressbar" style="width: {{ $persentase }}%" aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                @if($persentase > 0)
                                <div class="position-absolute end-0 top-50 translate-middle-y bg-white border border-success rounded-circle shadow-sm" style="width: 16px; height: 16px; margin-right:-8px;"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-modern border-0 shadow-sm">
            <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-1">Tabel Rincian Angsuran</h6>
                    <span class="text-muted-light small">Jadwal pembayaran cicilan pokok beserta jasa koperasi bulanan.</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-modern table-hover align-middle mb-0 text-nowrap w-100">
                        <thead class="table-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th class="border-0 ps-4 py-3 fw-semibold">Bulan Ke</th>
                                <th class="border-0 py-3 fw-semibold">Batas Bayar</th>
                                <th class="border-0 py-3 fw-semibold text-end">Angsuran Pokok</th>
                                <th class="border-0 py-3 fw-semibold text-end">Jasa ({{ $pinjaman->bunga }}%)</th>
                                <th class="border-0 py-3 fw-semibold text-end">Total Tagihan</th>
                                <th class="border-0 py-3 fw-semibold text-end pe-4">Status & Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjaman->angsurans as $angsuran)
                            <tr class="{{ $angsuran->status == 'lunas' ? 'bg-light bg-opacity-50' : '' }}">
                                <td class="ps-4 py-3 fw-bold {{ $angsuran->status == 'lunas' ? 'text-muted' : 'text-dark' }}">{{ $angsuran->ke_berapa }}</td>
                                <td class="py-3 {{ $angsuran->status == 'telat' ? 'text-danger fw-bold' : ($angsuran->status == 'lunas' ? 'text-muted' : 'text-dark') }} small fw-medium">
                                    {{ \Carbon\Carbon::parse($angsuran->tgl_jatuh_tempo)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3 text-end {{ $angsuran->status == 'lunas' ? 'text-muted' : 'text-dark' }} small">Rp {{ number_format($pokok_per_bulan, 0, ',', '.') }}</td>
                                <td class="py-3 text-end {{ $angsuran->status == 'lunas' ? 'text-muted' : 'text-dark' }} small">Rp {{ number_format($bunga_per_bulan, 0, ',', '.') }}</td>
                                <td class="py-3 text-end fw-semibold {{ $angsuran->status == 'lunas' ? 'text-muted' : 'text-primary' }} small">Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
                                <td class="py-3 text-end pe-4">

                                    @if($angsuran->status == 'lunas')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded px-2 py-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="me-1 mb-1" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/></svg>Lunas</span>

                                    @elseif($angsuran->status == 'menunggu_verifikasi')
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded px-2 py-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="me-1 mb-1" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>Menunggu Verifikasi</span>

                                    @else
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        @if($angsuran->status == 'telat')
                                        <span class="badge bg-danger text-white rounded px-2 py-1 me-2">Telat</span>
                                        @endif
                                        @if($angsuran->status == 'ditolak')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded px-2 py-1 me-2" title="{{ $angsuran->keterangan_tolak }}">Ditolak</span>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-primary rounded-3 py-1 px-3 fw-medium shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalUpload-{{ $angsuran->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                                            Upload
                                        </button>
                                    </div>


                                    @endif

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-muted">Jadwal angsuran belum dibuat oleh pengurus.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light border-top border-light p-3 text-center">
                <span class="small text-muted-light">Menampilkan seluruh jadwal angsuran untuk pinjaman ini.</span>
            </div>
        </div>

        {{-- MODALS SECTION: Must be outside table-responsive to prevent stacking context/backdrop issues --}}
        @foreach($pinjaman->angsurans as $angsuran)
            @if($angsuran->status != 'lunas' && $angsuran->status != 'menunggu_verifikasi')
            <div class="modal fade text-start text-wrap" id="modalUpload-{{ $angsuran->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                        <div class="modal-header bg-white border-bottom border-light p-4">
                            <h6 class="modal-title fw-bold text-dark">Upload Bukti Transfer</h6>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/anggota/angsuran/{{ $angsuran->id }}/upload-bukti" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-4 bg-white">
                                <div class="alert border-0 rounded-3 mb-4 p-3 d-flex gap-3 align-items-start" style="background-color: #EEF2FF; color: #4338CA;">
                                    <div class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
                                    </div>
                                    <div class="small lh-base">
                                        Silakan transfer sebesar <strong class="fs-6">Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</strong> ke rekening Koperasi SIKOPSIM, lalu unggah foto struk bukti transfer Anda di bawah ini.
                                    </div>
                                </div>
                                @if($angsuran->status == 'ditolak' && $angsuran->keterangan_tolak)
                                <div class="alert border-0 rounded-3 mb-4 p-3 d-flex gap-3 align-items-start" style="background-color: #FEF2F2; color: #991B1B;">
                                    <div class="mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>
                                    </div>
                                    <div class="small lh-base">
                                        <strong>Bukti Transfer Ditolak!</strong><br>
                                        Alasan: {{ $angsuran->keterangan_tolak }}
                                    </div>
                                </div>
                                @endif
                                <div class="mb-2">
                                    <label class="form-label fw-bold small text-dark mb-2">Pilih Foto Struk/Transfer</label>
                                    <input type="file" name="bukti_pembayaran" class="form-control form-control-lg shadow-none border-light-subtle" accept="image/*" required style="font-size: 0.9rem;">
                                    <div class="form-text small text-muted mt-2">Format yang didukung: JPG, PNG. Ukuran maksimal 2MB.</div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-top border-light p-3">
                                <button type="button" class="btn btn-light shadow-none fw-medium px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary fw-medium px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/></svg>
                                    Kirim Bukti Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endforeach

        @else
        <div class="card card-modern border-0 shadow-sm p-5 text-center bg-white">
            <div class="mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" class="text-muted opacity-50">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h6 class="fw-bold text-dark mb-1">Belum Ada Pinjaman Aktif</h6>
            <p class="text-muted small mb-4">Anda belum memiliki pinjaman yang sedang berjalan atau telah disetujui.</p>
            <a href="/anggota/ajukan-pinjaman" class="btn btn-primary px-4 py-2 fw-medium rounded-3 shadow-sm">Ajukan Pinjaman Baru</a>
        </div>
        @endif

        @endsection