@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<style>
    /* Sedikit penyesuaian agar DataTables rapi dengan desain card kita */
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
        <h5 class="fw-bold text-dark mb-0">Manajemen Data Anggota</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Data Anggota</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <button class="btn btn-outline-secondary btn-sm rounded-3 fw-medium flex-fill d-flex align-items-center justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Import
        </button>
        <a href="/anggota/create" class="btn btn-primary btn-sm rounded-3 fw-medium px-3 flex-fill d-flex align-items-center justify-content-center">
            + Tambah Anggota
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 text-success" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </svg>
    <div>
        {{ session('success') }}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 text-danger" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
    </svg>
    <div>
        {{ session('error') }}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card card-modern border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tabelAnggota" class="table table-hover align-middle mb-0 text-nowrap w-100">
                <thead class="table-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="border-0 ps-4 py-3 fw-semibold">No. Anggota</th>
                        <th class="border-0 py-3 fw-semibold">Profil Anggota</th>
                        <th class="border-0 py-3 fw-semibold">Kontak</th>
                        <th class="border-0 py-3 fw-semibold">Tgl Gabung</th>
                        <th class="border-0 py-3 fw-semibold">Status</th>
                        <th class="border-0 text-end pe-4 py-3 fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggotas as $anggota)
                    <tr>
                        <td class="ps-4 py-3 fw-semibold text-dark" style="font-size: 0.85rem;">
                            {{ $anggota->no_anggota }}
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $nama = $anggota->user->nama ?? 'Tanpa Nama';
                                    $inisial = strtoupper(substr($nama, 0, 2));
                                    $colors = ['primary', 'warning', 'success', 'danger', 'info'];
                                    $randColor = $colors[strlen($nama) % 5];
                                @endphp
                                <div class="bg-{{ $randColor }}-subtle text-{{ $randColor }} fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                    {{ $inisial }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $nama }}</h6>
                                    <span class="text-muted-light" style="font-size: 0.75rem;">{{ \Illuminate\Support\Str::limit($anggota->alamat, 30) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="d-block text-dark fw-medium" style="font-size: 0.85rem;">{{ $anggota->no_hp }}</span>
                        </td>
                        <td class="py-3" style="font-size: 0.85rem;">
                            <span class="text-dark fw-medium" data-sort="{{ $anggota->tgl_gabung }}">
                                {{ \Carbon\Carbon::parse($anggota->tgl_gabung)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if(strtolower($anggota->status) == 'aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 text-end pe-4">
                            <a href="/anggota/{{ $anggota->id }}" class="btn btn-sm btn-light border-light-subtle text-primary shadow-none px-2 me-1" title="Detail Profil"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg></a>
                            <a href="/anggota/{{ $anggota->id }}/edit" class="btn btn-sm btn-light border-light-subtle text-muted shadow-none px-2 me-1" title="Edit Data"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/></svg></a>
                            <form action="/anggota/{{ $anggota->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini beserta akun loginnya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border-light-subtle text-danger shadow-none px-2" title="Hapus Data"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        $('#tabelAnggota').DataTable({
            language: {
                search: "Pencarian:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ anggota",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 anggota",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            ordering: true, // Mengaktifkan fitur sorting
            pageLength: 10, // Default jumlah data per halaman
            columnDefs: [
                { orderable: false, targets: 5 } // Mematikan fitur sorting untuk kolom Aksi
            ]
        });
    });
</script>
@endpush