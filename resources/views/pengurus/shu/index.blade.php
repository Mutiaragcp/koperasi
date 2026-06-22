@extends('layouts.app') 

@section('content')
    <div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Sisa Hasil Usaha (SHU) - {{ $tahunIni }}</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small mt-1">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Akuntansi SHU</li>
                </ol>
            </nav>
            <span class="text-muted-light small mt-2 mb-0 d-block">Kelola, pantau simulasi, dan distribusikan laba koperasi secara transparan.</span>
        </div>
        
        <div class="d-flex flex-row gap-2 align-self-stretch align-self-sm-auto">
            <form action="/shu" method="GET" class="d-flex align-items-center m-0">
                <div class="input-group input-group-sm">
                    <label class="input-group-text bg-light text-muted fw-medium border-secondary" for="filterTahun">Tahun Buku</label>
                    <select name="tahun" id="filterTahun" class="form-select fw-bold border-secondary text-primary" onchange="this.form.submit()" style="width: 110px;">
                        @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" {{ $tahunIni == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($sudahDibagikan)
                <button type="button" class="btn btn-success btn-sm rounded-3 fw-medium px-3 shadow-sm d-flex align-items-center gap-2" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> SHU Terkunci & Sudah Dibagikan
                </button>
            @else
                <button type="button" class="btn btn-primary btn-sm rounded-3 fw-medium px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiShu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z"/><path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z"/><path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z"/><path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z"/></svg> Bagikan SHU Sekarang
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($sudahDibagikan)
        <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
            <h4 class="mb-0 text-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
            </h4>
            <div>
                <strong>Periode Terkunci:</strong> SHU tahun {{ $tahunIni }} telah sukses dibagikan secara permanen kepada anggota. Tabel di bawah saat ini menampilkan <strong>Laporan Resmi Kas Keuangan</strong> yang terkunci di database, bukan lagi simulasi perkiraan.
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-modern h-100 border-0 p-1">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-primary-subtle text-primary rounded-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/></svg>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalShu, 0, ',', '.') }}</h4>
                    <span class="text-muted-light small fw-medium">Total Laba Bersih (SHU)</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-modern h-100 border-0 p-1">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-success-subtle text-success rounded-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $pctModal }}%</span>
                    </div>
                    <h4 class="fw-bold mb-1 text-success">Rp {{ number_format($alokasiModal, 0, ',', '.') }}</h4>
                    <span class="text-muted-light small fw-medium">Porsi Jasa Modal</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-modern h-100 border-0 p-1">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-warning-subtle text-warning rounded-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z"/></svg>
                        </div>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ $pctAnggota }}%</span>
                    </div>
                    <h4 class="fw-bold mb-1 text-warning">Rp {{ number_format($alokasiAnggota, 0, ',', '.') }}</h4>
                    <span class="text-muted-light small fw-medium">Porsi Jasa Usaha</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-modern h-100 border-0 p-1">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-shape bg-info-subtle text-info rounded-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-3zM5.5 8a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-1zm3-3a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5h-1z"/></svg>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-info">Rp {{ number_format($totalShuDibagikan, 0, ',', '.') }}</h4>
                    <span class="text-muted-light small fw-medium">Total Cair ke Anggota</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap gap-4 text-muted justify-content-center justify-content-md-start" style="font-size: 0.85rem;">
                <div><strong>Porsi Pengurus:</strong> {{ $pctPengurus }}% (Rp {{ number_format($alokasiPengurus, 0, ',', '.') }})</div>
                <div><strong>Porsi Dana Sosial:</strong> {{ $pctSosial }}% (Rp {{ number_format($alokasiSosial, 0, ',', '.') }})</div>
                <div><span class="badge bg-secondary">Info</span> Persentase ditarik otomatis dari aturan Pengaturan SHU.</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold text-dark">
                {!! $sudahDibagikan ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary me-1 mb-1" viewBox="0 0 16 16"><path d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3zM4.5 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6z"/></svg> Laporan Resmi Penerimaan SHU Anggota' : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-fill me-1 mb-1" viewBox="0 0 16 16"><path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/></svg> Simulasi Estimasi Penerimaan Anggota' !!}
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table datatable-modern table-hover align-middle mb-0">
                <thead class="table-light text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">
                    <tr>
                        <th class="ps-3" style="width: 5%;">No</th>
                        <th style="width: 25%;">Anggota</th>
                        <th style="width: 14%; white-space: nowrap;">Simpanan Bersih</th>
                        <th style="width: 14%; white-space: nowrap;">SHU Jasa Modal</th>
                        <th style="width: 14%; white-space: nowrap;">Kontribusi Bunga</th>
                        <th style="width: 14%; white-space: nowrap;">SHU Jasa Anggota</th>
                        <th class="pe-3 text-end" style="width: 14%; white-space: nowrap;">Total Diterima</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    @foreach($dataSimulasi as $index => $row)
                        <tr>
                            <td class="ps-3 text-muted fw-medium">{{ $index + 1 }}</td>
                            <td>
                                <span class="d-block fw-bold text-dark">{{ $row->nama }}</span>
                                <small class="text-muted">{{ $row->no_anggota }}</small>
                            </td>
                            <td style="white-space: nowrap;">Rp {{ number_format($row->simpanan_pribadi, 0, ',', '.') }}</td>
                            <td class="text-success fw-medium" style="white-space: nowrap;">+ Rp {{ number_format($row->shu_modal, 0, ',', '.') }}</td>
                            <td style="white-space: nowrap;">Rp {{ number_format($row->bunga_dibayar, 0, ',', '.') }}</td>
                            <td class="text-warning fw-medium" style="white-space: nowrap;">+ Rp {{ number_format($row->shu_anggota, 0, ',', '.') }}</td>
                            <td class="pe-3 text-end fw-bold text-primary" style="white-space: nowrap;">
                                Rp {{ number_format($row->total_diterima, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                @if(count($dataSimulasi) > 0)
                    <tfoot class="table-light fw-bold" style="font-size: 0.9rem;">
                        <tr>
                            <td colspan="3" class="ps-3 text-end">TOTAL AKUMULASI:</td>
                            <td class="text-success" style="white-space: nowrap;">Rp {{ number_format($totalShuModalSemua, 0, ',', '.') }}</td>
                            <td style="white-space: nowrap;">-</td>
                            <td class="text-warning" style="white-space: nowrap;">Rp {{ number_format($totalShuAnggotaSemua, 0, ',', '.') }}</td>
                            <td class="pe-3 text-end text-primary" style="white-space: nowrap;">Rp {{ number_format($totalShuDibagikan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

@if(!$sudahDibagikan)
<div class="modal fade" id="modalKonfirmasiShu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalKonfirmasiShuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="modalKonfirmasiShuLabel"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 mb-1" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg> Peringatan Kunci Finansial</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <p class="mb-3">Anda akan mengunci dan membagikan dana SHU sebesar <strong>Rp {{ number_format($totalShuDibagikan, 0, ',', '.') }}</strong> kepada anggota yang berhak.</p>
                
                <div class="card bg-light border-0 p-3 mb-3" style="font-size: 0.85rem;">
                    <ul class="mb-0 ps-3 text-muted">
                        <li>Saldo <strong>Simpanan Sukarela</strong> masing-masing anggota otomatis bertambah saat ini juga.</li>
                        <li>Kas Koperasi otomatis mencatatkan data pengeluaran permanen.</li>
                        <li><strong>Tindakan ini mutlak kunci periode dan TIDAK BISA dibatalkan otomatis!</strong></li>
                    </ul>
                </div>

                <form action="/shu/bagikan" method="POST" id="formBagikanShu">
                    @csrf
                    <div class="mb-3">
                        <label align="left" for="inputTahun" class="form-label fw-bold small text-secondary">Untuk melanjutkan, ketik tahun periode aktif saat ini (<span class="text-danger fw-bold">{{ $tahunIni }}</span>):</label>
                        <input type="text" class="form-control form-control-lg text-center fw-bold text-danger bg-light" id="inputTahun" autocomplete="off" placeholder="Contoh: {{ $tahunIni }}">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg rounded-3 fw-bold shadow-sm" id="btnSubmitShu" disabled>
                            YA, SAYA MENGERTI & BAGIKAN SEKARANG
                        </button>
                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputTahun = document.getElementById('inputTahun');
        const btnSubmitShu = document.getElementById('btnSubmitShu');
        const tahunSistem = "{{ $tahunIni }}";

        inputTahun.addEventListener('input', function() {
            if (this.value.trim() === tahunSistem) {
                btnSubmitShu.removeAttribute('disabled');
                btnSubmitShu.classList.remove('btn-danger');
                btnSubmitShu.classList.add('btn-success');
            } else {
                btnSubmitShu.setAttribute('disabled', 'true');
                btnSubmitShu.classList.remove('btn-success');
                btnSubmitShu.classList.add('btn-danger');
            }
        });
    });
</script>
@endif

@endsection