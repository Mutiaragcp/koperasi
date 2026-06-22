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
        <h5 class="fw-bold text-dark mb-0">Manajemen User & Hak Akses 123</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">User</li>
            </ol>
        </nav>
        <span class="text-muted-light small mt-2 mb-0 d-block">Kelola akun login, ubah role akses, dan reset password pengguna sistem.</span>
    </div>
    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <!-- space for buttons if needed later -->
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">TOTAL USER</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">{{ $users->count() }} Akun</h4>
            <span class="text-muted-light small">Terdaftar di sistem</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-danger-subtle border-danger-subtle h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-danger small fw-bold">ADMIN</span>
            </div>
            <h4 class="fw-bold mb-1 text-danger">{{ $users->where('role', 'admin')->count() }} User</h4>
            <span class="text-danger small">Hak akses penuh</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-primary-subtle border-primary-subtle h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-primary small fw-bold">BENDAHARA</span>
            </div>
            <h4 class="fw-bold mb-1 text-primary">{{ $users->where('role', 'bendahara')->count() }} User</h4>
            <span class="text-primary small">Kelola keuangan</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-success-subtle border-success-subtle h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-success small fw-bold">ANGGOTA</span>
            </div>
            <h4 class="fw-bold mb-1 text-success">{{ $users->where('role', 'anggota')->count() }} User</h4>
            <span class="text-success small">Member koperasi</span>
        </div>
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
        <h6 class="fw-bold text-dark mb-0">Daftar Seluruh Akun Pengguna</h6>
        <span class="badge bg-light text-muted border border-light-subtle rounded-pill">{{ $users->count() }} User</span>
    </div>

    <div class="table-responsive">
        <table id="tabelUser" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 5%;">NO</th>
                    <th class="py-3" style="width: 25%;">NAMA USER</th>
                    <th class="py-3" style="width: 25%;">EMAIL</th>
                    <th class="py-3" style="width: 15%;">NO ANGGOTA</th>
                    <th class="py-3" style="width: 12%;">ROLE AKSES</th>
                    <th class="py-3 text-end pe-4" style="width: 18%;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td class="py-3 ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $nama = $user->nama ?? 'User';
                                $words = explode(' ', $nama);
                                $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                $bgColor = $user->role == 'admin' ? 'bg-danger-subtle text-danger' : ($user->role == 'bendahara' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success');
                            @endphp
                            <div class="{{ $bgColor }} fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.8rem; min-width: 36px;">
                                {{ $inisial }}
                            </div>
                            <div>
                                <span class="fw-semibold text-dark d-block" style="font-size: 0.85rem;">{{ $nama }}</span>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-dark rounded-pill px-2" style="font-size: 0.6rem;">Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3 text-muted-light small">{{ $user->email }}</td>
                    <td class="py-3 text-muted-light small" style="white-space: nowrap;">{{ $user->anggota->no_anggota ?? '-' }}</td>
                    <td class="py-3">
                        @if($user->role == 'admin')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2">Admin</span>
                        @elseif($user->role == 'bendahara')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2">Bendahara</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">Anggota</span>
                        @endif
                    </td>
                    <td class="py-3 text-end pe-4">
                        @if($user->id !== auth()->id())
                            {{-- Ubah Role --}}
                            <div class="btn-group me-1">
                                <button type="button" class="btn btn-sm btn-light border-light-subtle text-primary shadow-none px-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Ubah Role">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256z"/><path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.646.646-.646a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708z"/></svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle">
                                    <li><h6 class="dropdown-header small text-muted">Pilih Role Baru</h6></li>
                                    @foreach(['admin', 'bendahara', 'anggota'] as $role)
                                        @if($user->role !== $role)
                                            <li>
                                                <form action="/users/{{ $user->id }}/role" method="POST" onsubmit="return confirm('Yakin ubah role {{ $user->nama }} ke {{ strtoupper($role) }}?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="role" value="{{ $role }}">
                                                    <button type="submit" class="dropdown-item small">
                                                        {{ ucfirst($role) }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Reset Password --}}
                            <form action="/users/{{ $user->id }}/reset-password" method="POST" class="d-inline" onsubmit="return confirm('Reset password {{ $user->nama }} ke default (password123)?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-light border-light-subtle text-warning shadow-none px-2" title="Reset Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/></svg>
                                </button>
                            </form>
                        @else
                            <span class="badge bg-light text-muted border border-light-subtle small">Akun Anda</span>
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
        $('#tabelUser').DataTable({
            language: {
                search: "Pencarian:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ user",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 user",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada user yang ditemukan",
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
