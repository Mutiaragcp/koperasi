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
        <h5 class="fw-bold text-dark mb-0">Manajemen Jenis Simpanan</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Jenis Simpanan</li>
            </ol>
        </nav>
        <p class="text-muted-light small mt-2 mb-0">Kelola kategori simpanan yang berlaku di koperasi.</p>
    </div>
    
    <div>
        <a href="/jenis-simpanan/create" class="btn btn-primary btn-sm rounded-3 fw-medium px-3">
            + Tambah Jenis Baru
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card card-modern border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="tabelJenisSimpanan" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 50px;">NO</th>
                    <th class="py-3">NAMA SIMPANAN</th>
                    <th class="py-3">KETERANGAN</th>
                    <th class="py-3 text-end pe-4" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jenisSimpanans as $index => $jenis)
                <tr>
                    <td class="py-3 ps-4 text-muted">{{ $index + 1 }}</td>
                    <td class="py-3 fw-semibold text-dark">{{ $jenis->nama }}</td>
                    <td class="py-3 text-muted-light">{{ $jenis->keterangan ?? '-' }}</td>
                    <td class="py-3 text-end pe-4">
                        <a href="/jenis-simpanan/{{ $jenis->id }}/edit" class="btn btn-sm btn-light border-light-subtle text-muted px-2 me-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/></svg> Edit</a>
                        <form action="/jenis-simpanan/{{ $jenis->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jenis simpanan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border-light-subtle text-danger px-2"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg> Hapus</button>
                        </form>
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
        $('#tabelJenisSimpanan').DataTable({
            language: {
                search: "Pencarian:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ jenis simpanan",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 jenis simpanan",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
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
                { orderable: false, targets: 3 }
            ]
        });
    });
</script>
@endpush