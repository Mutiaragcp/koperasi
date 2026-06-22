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
    .row-telat { background-color: #fff5f5 !important; }
    .row-verif { background-color: #f0fdf4 !important; border-left: 4px solid #16a34a; }
</style>
@endpush

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">Loket & Verifikasi Pembayaran</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Transaksi Angsuran</li>
            </ol>
        </nav>
    </div>
    <div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-success-subtle border-success-subtle border-start border-success border-4 h-100 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-success small fw-bold">BUTUH VERIFIKASI</span>
            </div>
            <h4 class="fw-bold mb-1 text-success">{{ $totalVerifikasi }} Transfer</h4>
            <span class="text-success small">Tunggu divalidasi</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100 border-start border-primary border-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted-light small fw-medium">POTENSI BULAN INI</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalTagihanBulanIni, 0, ',', '.') }}</h4>
            <span class="text-primary small fw-medium">Kas masuk diharapkan</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 h-100 border-start border-warning border-4 bg-warning-subtle border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-warning-emphasis small fw-bold">BELUM BAYAR</span>
            </div>
            <h4 class="fw-bold mb-1 text-warning-emphasis">{{ $totalMenunggu }} Tagihan</h4>
            <span class="text-warning-emphasis small">Belum jatuh tempo</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern p-3 bg-danger-subtle border-danger-subtle border-start border-danger border-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-danger small fw-bold">TELAT / MENUNGGAK</span>
            </div>
            <h4 class="fw-bold mb-1 text-danger">{{ $totalTelat }} Tagihan</h4>
            <span class="text-danger small">Segera tagih</span>
        </div>
    </div>
</div>

<div class="card card-modern border-0 shadow-sm overflow-hidden p-4">
    <form action="/transaksi-angsuran" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-12 col-md-4 col-lg-3">
            <select name="status" class="form-select form-select-sm bg-light border-0 py-2 rounded-3 text-dark fw-medium" onchange="this.form.submit()">
                <option value="">Semua Tagihan Aktif</option>
                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Butuh Verifikasi (Prioritas)</option>
                <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar (Aman)</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak (Menunggu Revisi)</option>
                <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat Bayar (Menunggak)</option>
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table id="tabelTagihan" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light text-muted small fw-bold uppercase border-bottom border-light">
                <tr>
                    <th class="py-3 ps-4" style="width: 50px;">NO</th>
                    <th class="py-3">IDENTITAS ANGGOTA</th>
                    <th class="py-3">TAGIHAN BULAN KE-</th>
                    <th class="py-3">JATUH TEMPO</th>
                    <th class="py-3">NOMINAL BAYAR</th>
                    <th class="py-3">STATUS</th>
                    <th class="py-3 text-end pe-4" style="width: 160px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($angsurans as $index => $item)
                <tr class="{{ $item->status == 'telat' ? 'row-telat' : '' }} {{ $item->status == 'menunggu_verifikasi' ? 'row-verif' : '' }}">
                    <td class="py-3 ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="py-3">
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark">{{ $item->pinjaman->anggota->user->nama ?? 'Tanpa Nama' }}</span>
                            <span class="text-muted-light small">ID: {{ $item->pinjaman->anggota->no_anggota ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-light text-dark border border-light-subtle rounded-3 px-3 py-2 fw-bold">
                            Ke-{{ $item->ke_berapa }} <span class="fw-normal text-muted">dari {{ $item->pinjaman->tenor }}</span>
                        </span>
                    </td>
                    <td class="py-3">
                        <span class="fw-semibold {{ $item->status == 'telat' ? 'text-danger' : 'text-dark' }}" data-sort="{{ $item->tgl_jatuh_tempo }}">
                            {{ \Carbon\Carbon::parse($item->tgl_jatuh_tempo)->translatedFormat('d M Y') }}
                        </span>
                    </td>
                    <td class="py-3">
                        <span class="fw-bold text-primary fs-6">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</span>
                    </td>
                    <td class="py-3">
                        @if($item->status == 'menunggu_verifikasi')
                            <span class="badge bg-info text-dark rounded-pill px-2 shadow-sm animate-pulse">Perlu Dicek</span>
                        @elseif($item->status == 'belum_bayar')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2">Menunggu</span>
                        @elseif($item->status == 'ditolak')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2" title="{{ $item->keterangan_tolak }}">Ditolak</span>
                        @elseif($item->status == 'telat')
                            <span class="badge bg-danger text-white rounded-pill px-2 shadow-sm">Menunggak</span>
                        @endif
                    </td>
                    <td class="py-3 text-end pe-4">
                        @if($item->status == 'menunggu_verifikasi')
                            <a href="/angsuran/{{ $item->id }}/bayar" class="btn btn-sm btn-success fw-bold px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                🔍 Verifikasi
                            </a>
                        @else
                            <a href="/angsuran/{{ $item->id }}/bayar" class="btn btn-sm btn-primary fw-medium px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                Input Manual
                            </a>
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
        $('#tabelTagihan').DataTable({
            language: {
                search: "Cari Tagihan Nasabah:",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ tagihan aktif",
                infoEmpty: "Menampilkan 0 tagihan",
                infoFiltered: "(disaring dari _MAX_ total tagihan)",
                zeroRecords: "Tidak ada tagihan yang ditemukan.",
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
                { orderable: false, targets: 6 } 
            ]
        });
    });
</script>
@endpush