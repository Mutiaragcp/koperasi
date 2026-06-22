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
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Pencatatan Simpanan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Kas Simpanan</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <a href="/simpanan/export-excel" class="btn btn-outline-secondary btn-sm rounded-3 fw-medium flex-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z" />
            </svg>
            Export
        </a>
        <a href="/simpanan/create" class="btn btn-primary btn-sm rounded-3 fw-medium px-3 flex-fill">
            + Setor / Tarik Simpanan
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">TOTAL SIMPANAN</span>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size: 0.7rem;">Semua</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalKas, 0, ',', '.') }}</h4>
            <span class="text-success small fw-medium">↑ Akumulasi Bersih</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">SIMPANAN POKOK</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalPokok, 0, ',', '.') }}</h4>
            <span class="text-muted-light small">Wajib saat daftar</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">SIMPANAN WAJIB</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalWajib, 0, ',', '.') }}</h4>
            <span class="text-muted-light small">Setoran bulanan</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">SIMPANAN SUKARELA</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalSukarela, 0, ',', '.') }}</h4>
            <span class="text-muted-light small">Bisa ditarik</span>
        </div>
    </div>
</div>

@if(isset($pendingCount) && $pendingCount > 0)
<div class="alert alert-warning border-warning shadow-sm d-flex flex-column flex-md-row align-items-center justify-content-between mb-4" role="alert">
    <div class="mb-3 mb-md-0">
        <strong class="d-block mb-1 text-dark fs-5">⚠️ Ada {{ $pendingCount }} Setoran Mandiri Menunggu Verifikasi!</strong>
        <span class="text-dark small">Terdapat transaksi setoran dari anggota yang belum disahkan. Saldo Buku Kas belum akan bertambah sebelum Anda menyetujuinya.</span>
    </div>
    <a href="/simpanan/verifikasi" class="btn btn-warning fw-bold shadow-sm px-4 text-nowrap border border-dark border-opacity-25">Lihat & Proses</a>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-alert="dismiss" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card card-modern border-0 shadow-sm overflow-hidden p-4">
    <form action="/simpanan" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-12 col-md-4 col-lg-3">
            <label class="small text-muted-light fw-medium mb-1">Filter Jenis Simpanan:</label>
            <select name="jenis_simpanan_id" class="form-select form-select-sm bg-light border-0 py-2 rounded-3 text-dark fw-medium" onchange="this.form.submit()">
                <option value="">Semua Jenis Simpanan</option>
                @foreach($jenisSimpanans as $jenis)
                    <option value="{{ $jenis->id }}" {{ request('jenis_simpanan_id') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-8 col-lg-9 text-end mt-auto">
            <span class="small text-muted-light">Gunakan kotak pencarian di bawah untuk mencari nama anggota.</span>
        </div>
    </form>

    <div class="table-responsive">
        <table id="tabelSimpanan" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 50px;">NO</th>
                    <th class="py-3">INFO ANGGOTA</th>
                    <th class="py-3">JENIS</th>
                    <th class="py-3">NOMINAL TRANSAKSI</th>
                    <th class="py-3">TANGGAL</th>
                    <th class="py-3 text-end pe-4" style="width: 100px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($simpanans as $index => $simpanan)
                <tr>
                    <td class="py-3 ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="py-3">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark">{{ $simpanan->anggota->user->nama ?? 'Tanpa Nama' }}</span>
                            <span class="text-muted-light" style="font-size: 0.8rem;">{{ $simpanan->anggota->no_anggota ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-light text-dark border border-light-subtle rounded-pill px-2">
                            {{ $simpanan->jenis_simpanan->nama ?? 'Tidak Diketahui' }}
                        </span>
                    </td>
                    <td class="py-3 fw-medium">
                        @if($simpanan->jenis_transaksi == 'setor')
                            <span class="text-success" data-sort="{{ $simpanan->jumlah }}">+ Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}</span>
                        @else
                            <span class="text-danger" data-sort="{{ $simpanan->jumlah }}">- Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="py-3 text-muted-light small" data-sort="{{ $simpanan->tanggal }}">
                        {{ \Carbon\Carbon::parse($simpanan->tanggal)->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-3 text-end pe-4">
                        <button class="btn btn-sm btn-light border-light-subtle text-danger shadow-none px-2" title="Hapus (Akan Segera Aktif)" disabled><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button>
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
        $('#tabelSimpanan').DataTable({
            language: {
                search: "Pencarian:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ transaksi",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 transaksi",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada transaksi yang cocok ditemukan",
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
                { orderable: false, targets: 5 }
            ]
        });
    });
</script>
@endpush