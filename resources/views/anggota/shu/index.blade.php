@extends('layouts.app') 

@section('content')

    <div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Riwayat SHU Anggota</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small mt-1">
                    <li class="breadcrumb-item"><a href="/anggota/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Pembagian SHU</li>
                </ol>
            </nav>
        </div>
        <div></div>
    </div>
    <div class="card card-modern border-0 shadow-sm mb-4 overflow-hidden position-relative text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
        <!-- Background decoration -->
        <div class="position-absolute top-0 end-0 opacity-10" style="transform: translate(10%, -20%) scale(1.5);">
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10zm0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
            </svg>
        </div>
        
        <div class="card-body p-4 p-lg-5 position-relative z-1">
            <div class="d-flex align-items-center justify-content-between gap-4">
                <div>
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold mb-3 shadow-sm d-inline-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/></svg>
                        Riwayat Bonus SHU
                    </span>
                    <h3 class="fw-bold mb-2">Halo, {{ Auth::user()->nama }} 👋</h3>
                    <p class="mb-0 text-white opacity-75 fs-6" style="max-width: 600px;">Berikut adalah rincian pembagian Sisa Hasil Usaha (SHU) Anda berdasarkan keaktifan menabung dan bertransaksi di Koperasi SIKOPSIM.</p>
                </div>
                <div class="d-none d-md-flex flex-shrink-0 align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle" style="width: 100px; height: 100px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/><path fill-rule="evenodd" d="M1 10a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-2zm12-1a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h10z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold text-dark mb-1">Daftar Penerimaan SHU Anda</h6>
                <span class="text-muted-light small">Rincian bonus SHU tahunan yang telah masuk ke Simpanan Sukarela.</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table datatable-modern table-hover align-middle mb-0 text-nowrap w-100">
                    <thead class="table-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="border-0 ps-4 py-3 fw-semibold">Tahun Buku</th>
                            <th class="border-0 py-3 fw-semibold">Jasa Modal (Tabungan)</th>
                            <th class="border-0 py-3 fw-semibold">Jasa Anggota (Pinjaman)</th>
                            <th class="border-0 py-3 fw-semibold">Tanggal Cair</th>
                            <th class="border-0 py-3 fw-semibold text-end pe-4">Total Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatShu as $shu)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="badge bg-dark fw-bold px-3 py-2 rounded-2">Tahun {{ $shu->periode }}</span>
                                </td>
                                <td class="py-3 text-success fw-medium small">
                                    + Rp {{ number_format($shu->jasa_modal, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-warning fw-medium small">
                                    + Rp {{ number_format($shu->jasa_usaha, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-muted-light fw-medium small">
                                    {{ \Carbon\Carbon::parse($shu->tgl_dihitung)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3 pe-4 text-end fw-bold text-primary fs-5">
                                    Rp {{ number_format($shu->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" class="text-muted opacity-50">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat SHU</h6>
                                    <p class="text-muted small mb-0">SHU Anda akan muncul di sini setelah disetujui dan dibagikan oleh Pengurus Koperasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm bg-info-subtle text-info-emphasis d-flex gap-3 align-items-start p-4 rounded-4 mt-4 mb-5">
        <div class="bg-white rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
        </div>
        <div>
            <h6 class="fw-bold mb-2 text-dark">Catatan Transparansi Sistem</h6>
            <ol class="mb-0 ps-3 small lh-lg text-muted">
                <li><strong>Jasa Modal</strong> dihitung proporsional dari total saldo simpanan bersih Anda.</li>
                <li><strong>Jasa Anggota</strong> dihitung dari kontribusi bunga angsuran pinjaman yang Anda bayarkan tahun ini.</li>
                <li>Seluruh dana SHU <u>langsung dimasukkan otomatis</u> ke <strong>Simpanan Sukarela</strong> dan dapat ditarik sewaktu-waktu.</li>
            </ol>
        </div>
    </div>

@endsection