@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Antrean Verifikasi Setoran</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/simpanan" class="text-decoration-none text-muted-light">Kas Simpanan</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Verifikasi Mandiri</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="/simpanan" class="btn btn-outline-secondary btn-sm rounded-3 fw-medium px-3">
            ← Kembali ke Kas
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card card-modern border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white border-bottom border-light p-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">Daftar Setoran Tertunda (Pending)</h6>
        <span class="badge bg-warning text-dark rounded-pill border border-warning-subtle shadow-sm px-3 py-2">
            Total Antrean: {{ $pendings->count() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table datatable-modern table-hover align-middle mb-0 w-100">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 150px;">TANGGAL UPLOAD</th>
                    <th class="py-3">INFO ANGGOTA</th>
                    <th class="py-3">NOMINAL SETORAN</th>
                    <th class="py-3 text-center">BUKTI TRANSFER</th>
                    <th class="py-3 text-end pe-4" style="width: 200px;">AKSI VERIFIKASI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendings as $item)
                <tr>
                    <td class="py-3 ps-4 text-muted small">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-3">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark">{{ $item->anggota->user->nama ?? 'Tanpa Nama' }}</span>
                            <span class="text-muted-light" style="font-size: 0.8rem;">
                                {{ $item->anggota->no_anggota ?? '-' }} • {{ $item->jenis_simpanan->nama ?? 'Tabungan' }}
                            </span>
                        </div>
                    </td>
                    <td class="py-3 fw-bold text-success">
                        + Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="py-3 text-center">
                        @if($item->bukti_transaksi)
                        <a href="{{ asset('storage/uploads/simpanan/' . $item->bukti_transaksi) }}" target="_blank" title="Klik untuk melihat struk penuh">
                            <img src="{{ asset('storage/uploads/simpanan/' . $item->bukti_transaksi) }}" alt="Struk Transfer" class="rounded border shadow-sm" style="height: 60px; width: 60px; object-fit: cover; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                        @else
                        <span class="badge bg-light text-muted border border-light-subtle">Tanpa Struk</span>
                        @endif
                    </td>
                    <td class="py-3 text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="/simpanan/{{ $item->id }}/reject" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK setoran ini? Saldo Kas Koperasi tidak akan bertambah.');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger fw-medium rounded-3 px-3 shadow-sm">Tolak</button>
                            </form>

                            <form action="/simpanan/{{ $item->id }}/approve" method="POST" onsubmit="return confirm('Apakah bukti transfer sudah benar dan uang benar-benar SUDAH MASUK ke rekening Koperasi?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success fw-medium rounded-3 px-3 shadow-sm">✓ Setujui</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-5 text-center text-muted">
                        <div class="mb-3 opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h6 class="fw-medium text-dark mb-1">Semua Antrean Bersih!</h6>
                        <span class="small">Tidak ada setoran mandiri yang menunggu verifikasi saat ini.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection