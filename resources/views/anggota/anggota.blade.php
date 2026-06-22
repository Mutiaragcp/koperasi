@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h5 class="fw-bold text-dark mb-0">Manajemen Data Anggota</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted-light">Dashboard</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Data Anggota</li>
            </ol>
        </nav>
    </div>
    
    <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
        <button class="btn btn-outline-secondary btn-sm rounded-3 fw-medium flex-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="me-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Import
        </button>
        <button class="btn btn-primary btn-sm rounded-3 fw-medium px-3 flex-fill">
            + Tambah Anggota
        </button>
    </div>
</div>

<div class="card card-modern border-0">
    <div class="card-header bg-white border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2 w-100" style="max-width: 350px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted" id="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </span>
                <input type="text" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Cari nama atau ID anggota..." aria-label="Search" aria-describedby="search-icon">
            </div>
        </div>
        
        <div class="d-flex gap-2 w-100 w-md-auto justify-content-md-end">
            <select class="form-select form-select-sm shadow-none w-auto text-muted-light" aria-label="Filter Status">
                <option selected>Semua Status</option>
                <option value="1">Aktif</option>
                <option value="2">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table datatable-modern table-hover align-middle mb-0 text-nowrap">
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
                    <tr>
                        <td class="ps-4 py-3 fw-semibold text-dark" style="font-size: 0.85rem;">AK-0921</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                    AW
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Andi Wijaya</h6>
                                    <span class="text-muted-light" style="font-size: 0.75rem;">Jl. Merdeka No. 45, Blok C</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="d-block text-dark fw-medium" style="font-size: 0.85rem;">0812-3456-7890</span>
                        </td>
                        <td class="py-3" style="font-size: 0.85rem;">
                            <span class="text-dark fw-medium">12 Jan 2024</span>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">
                                Aktif
                            </span>
                        </td>
                        <td class="py-3 text-end pe-4">
                            <button class="btn btn-sm btn-light border-light-subtle text-muted shadow-none px-2 me-1" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/></svg>
                            </button>
                            <button class="btn btn-sm btn-light border-light-subtle text-danger shadow-none px-2" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="ps-4 py-3 fw-semibold text-dark" style="font-size: 0.85rem;">AK-0842</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning-subtle text-warning fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                    SR
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Siti Rahma</h6>
                                    <span class="text-muted-light" style="font-size: 0.75rem;">Perum Indah Sari Blok A2</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="d-block text-dark fw-medium" style="font-size: 0.85rem;">0857-1234-9876</span>
                        </td>
                        <td class="py-3" style="font-size: 0.85rem;">
                            <span class="text-dark fw-medium">05 Mar 2024</span>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">
                                Aktif
                            </span>
                        </td>
                        <td class="py-3 text-end pe-4">
                            <button class="btn btn-sm btn-light border-light-subtle text-muted shadow-none px-2 me-1" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/></svg>
                            </button>
                            <button class="btn btn-sm btn-light border-light-subtle text-danger shadow-none px-2" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="ps-4 py-3 fw-semibold text-dark" style="font-size: 0.85rem;">AK-0112</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-secondary bg-opacity-10 text-secondary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                    DD
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Dedi Darmawan</h6>
                                    <span class="text-muted-light" style="font-size: 0.75rem;">Jl. Sudirman No. 10</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="d-block text-dark fw-medium" style="font-size: 0.85rem;">0896-5555-1234</span>
                        </td>
                        <td class="py-3" style="font-size: 0.85rem;">
                            <span class="text-dark fw-medium">10 Nov 2023</span>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2 py-1 fw-medium" style="font-size: 0.75rem;">
                                Nonaktif
                            </span>
                        </td>
                        <td class="py-3 text-end pe-4">
                            <button class="btn btn-sm btn-light border-light-subtle text-muted shadow-none px-2 me-1" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/></svg>
                            </button>
                            <button class="btn btn-sm btn-light border-light-subtle text-danger shadow-none px-2" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-top border-light p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
        <span class="text-muted-light small">Menampilkan 1 hingga 3 dari 1,248 entri</span>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link shadow-none" href="#">Sebelumnya</a></li>
                <li class="page-item active"><a class="page-link shadow-none" href="#">1</a></li>
                <li class="page-item"><a class="page-link shadow-none text-dark" href="#">2</a></li>
                <li class="page-item"><a class="page-link shadow-none text-dark" href="#">3</a></li>
                <li class="page-item"><a class="page-link shadow-none text-dark" href="#">Selanjutnya</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection