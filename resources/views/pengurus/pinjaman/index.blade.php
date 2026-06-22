@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<style>
    .dataTables_wrapper .row { margin-bottom: 0.5rem; }
    .dataTables_length select, .dataTables_filter input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }
    .table-responsive { overflow-x: auto; }
</style>
@endpush

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h5 class="fw-bold text-dark mb-0">Manajemen Data Pinjaman</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Pinjaman</li>
            </ol>
        </nav>
    </div>
    
    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <a href="/pinjaman/export-excel" class="btn btn-outline-secondary btn-sm rounded-3 fw-medium flex-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z" />
            </svg>
            Export
        </a>
        
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'bendahara', 'anggota']))
            <a href="/pinjaman/create" class="btn btn-primary btn-sm rounded-3 fw-medium px-3 flex-fill">
                + Ajukan Pinjaman
            </a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">TOTAL PINJAMAN</span>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;">Berjalan</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalPinjamanBerjalan, 0, ',', '.') }}</h4>
            <span class="text-success small fw-medium">Dana Keluar</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-warning-subtle border-warning-subtle h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-warning-emphasis small fw-bold">MENUNGGU APPROVAL</span>
            </div>
            <h4 class="fw-bold mb-1 text-warning-emphasis">{{ $menungguApproval }} Pengajuan</h4>
            <span class="text-warning-emphasis small">Butuh verifikasi Pengurus</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">PINJAMAN LUNAS</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">{{ $pinjamanLunas }} Data</h4>
            <span class="text-muted-light small">Telah diselesaikan</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-danger-subtle border-danger-subtle h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-danger small fw-bold">TELAT ANGSURAN</span>
            </div>
            <h4 class="fw-bold mb-1 text-danger">{{ $telatAngsuran }} Pinjaman</h4>
            <span class="text-danger small">Melewati jatuh tempo</span>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card card-modern border-0 shadow-sm overflow-hidden p-4">
    <form action="/pinjaman" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-12 col-md-4 col-lg-3">
            <label class="small text-muted-light fw-medium mb-1">Filter Status Pinjaman:</label>
            <select name="status" class="form-select form-select-sm bg-light border-0 py-2 rounded-3 text-dark fw-medium" onchange="this.form.submit()">
                <option value="">Semua Status Pinjaman</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Approval</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui (Sedang Berjalan)</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-12 col-md-8 col-lg-9 text-end mt-auto">
            <span class="small text-muted-light">Gunakan kotak pencarian di bawah untuk mencari nama anggota.</span>
        </div>
    </form>

    <div class="table-responsive">
        <table id="tabelPinjaman" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 50px;">NO</th>
                    <th class="py-3">INFO ANGGOTA</th>
                    <th class="py-3">NOMINAL & TENOR</th>
                    <th class="py-3">TGL PENGAJUAN</th>
                    <th class="py-3">STATUS</th>
                    <th class="py-3 text-end pe-4" style="width: 140px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pinjamans as $index => $pinjaman)
                <tr>
                    <td class="py-3 ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="py-3">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark">{{ $pinjaman->anggota->user->nama ?? 'Tanpa Nama' }}</span>
                            <span class="text-muted-light" style="font-size: 0.8rem;">{{ $pinjaman->anggota->no_anggota ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark" data-sort="{{ $pinjaman->jumlah_pinjaman }}">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</span>
                            <span class="text-muted-light" style="font-size: 0.8rem;">{{ $pinjaman->tenor }} Bulan (Bunga {{ $pinjaman->bunga }}%)</span>
                        </div>
                    </td>
                    <td class="py-3 text-muted-light small" data-sort="{{ $pinjaman->tgl_pengajuan }}">
                        {{ \Carbon\Carbon::parse($pinjaman->tgl_pengajuan)->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-3">
                        @if($pinjaman->status == 'menunggu')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2">Menunggu Approval</span>
                        @elseif($pinjaman->status == 'disetujui')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2">Disetujui / Berjalan</span>
                        @elseif($pinjaman->status == 'lunas')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">Lunas</span>
                        @elseif($pinjaman->status == 'ditolak')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2">Ditolak</span>
                        @endif
                    </td>
                    <td class="py-3 text-end pe-4">
                        
                        <a href="/pinjaman/{{ $pinjaman->id }}" class="btn btn-sm btn-light border-light-subtle text-primary shadow-none px-2 me-1" title="Detail / Bayar / Approval">
                            👁
                        </a>

                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'bendahara']))
                            @if($pinjaman->status == 'menunggu')
                                <form action="/pinjaman/{{ $pinjaman->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan dan menghapus pengajuan pinjaman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border-light-subtle text-danger shadow-none px-2" title="Batalkan/Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-light border-light-subtle text-muted shadow-none px-2" title="Tidak dapat dihapus karena status sudah diproses" disabled><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button>
                            @endif
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#tabelPinjaman').DataTable({
            language: {
                search: "Pencarian:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ pengajuan",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 pengajuan",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data pinjaman yang ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            ordering: true, 
            pageLength: 10, 
            columnDefs: [
                { orderable: false, targets: 5 } // Mematikan fitur sorting untuk kolom Aksi
            ]
        });
    });
</script>
@endpush