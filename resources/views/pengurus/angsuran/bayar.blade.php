@extends('layouts.app')

@section('content')
<div class="content-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-dark mb-0">
            {{ $angsuran->status == 'menunggu_verifikasi' ? 'Verifikasi Bukti Transfer' : 'Proses Pembayaran Kasir' }}
        </h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small mt-1">
                <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-muted-light">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/transaksi-angsuran" class="text-decoration-none text-muted-light">Loket Pembayaran</a></li>
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Eksekusi</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="/transaksi-angsuran" class="btn btn-white border border-light-subtle shadow-sm px-3">← Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-modern border-0 shadow-sm p-4 h-100 bg-light bg-opacity-50">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-light-subtle">
                <div class="bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                    {{ $angsuran->ke_berapa }}
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Angsuran Ke-{{ $angsuran->ke_berapa }}</h6>
                    <span class="text-muted small">Dari total {{ $angsuran->pinjaman->tenor }} bulan</span>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                <div>
                    <span class="d-block text-muted-light small mb-1 fw-medium">Nama Anggota</span>
                    <span class="fw-bold text-dark">{{ $angsuran->pinjaman->anggota->user->nama ?? 'Tanpa Nama' }}</span>
                </div>
                <div>
                    <span class="d-block text-muted-light small mb-1 fw-medium">Batas Jatuh Tempo</span>
                    <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($angsuran->tgl_jatuh_tempo)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="bg-white p-3 rounded-3 border border-light-subtle mt-2">
                    <span class="d-block text-muted-light small mb-1 fw-medium">Nominal Tagihan Pokok + Jasa</span>
                    <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-modern border-0 shadow-sm p-4 h-100 border-top border-primary border-3">
            
            @if($angsuran->status == 'menunggu_verifikasi')
                <h6 class="fw-bold text-dark mb-3">Foto Bukti Transfer Anggota</h6>
                
                <div class="mb-4 text-center bg-light p-2 rounded-3 border border-light-subtle">
                    @if($angsuran->bukti_pembayaran)
                        <img src="{{ asset('storage/uploads/angsuran/' . $angsuran->bukti_pembayaran) }}" alt="Bukti Transfer" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: contain;">
                    @else
                        <span class="text-muted small d-block py-5">File foto tidak ditemukan di server.</span>
                    @endif
                </div>

                <div class="d-flex flex-column gap-2 mt-auto">
                    <form action="/angsuran/{{ $angsuran->id }}/bayar" method="POST">
                        @csrf
                        <input type="hidden" name="tgl_bayar" value="{{ date('Y-m-d') }}">
                        <button type="submit" class="btn btn-success fw-bold w-100 py-2 shadow-sm" onclick="return confirm('Uang transfer sudah masuk ke rekening? Status akan diubah menjadi Lunas.')">
                            ✓ Uang Diterima (Sahkan Menjadi Lunas)
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger fw-medium w-100 py-2" data-bs-toggle="modal" data-bs-target="#modalTolak">
                        ✕ Tolak & Minta Upload Ulang
                    </button>
                </div>

            @else
                <h6 class="fw-bold text-dark mb-4">Input Pembayaran Manual</h6>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/angsuran/{{ $angsuran->id }}/bayar" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar" class="form-control shadow-none bg-light" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Denda Keterlambatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light-subtle text-muted">Rp</span>
                                <input type="number" name="denda" class="form-control shadow-none" placeholder="0" min="0" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark mb-1">Upload Struk Kasir / Bukti Transfer (Opsional)</label>
                        <input type="file" name="bukti_pembayaran" class="form-control shadow-none" accept="image/*">
                        <div class="form-text small text-muted">Boleh dikosongkan jika anggota membayar tunai langsung.</div>
                    </div>

                    <hr class="border-light-subtle my-4">

                    <div class="d-flex justify-content-end mt-auto">
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm" onclick="return confirm('Pastikan uang fisik telah diterima sebelum menyimpan data ini.')">
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>

<!-- Modal Tolak Pembayaran -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white border-bottom border-light p-4">
                <h6 class="modal-title fw-bold text-danger">Tolak Bukti Transfer</h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/angsuran/{{ $angsuran->id }}/tolak" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="alert border-0 rounded-3 mb-4 p-3 d-flex gap-3 align-items-start" style="background-color: #FEF2F2; color: #991B1B;">
                        <div class="mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>
                        </div>
                        <div class="small lh-base">
                            Berikan alasan yang jelas mengapa bukti transfer ini ditolak, agar anggota bisa memperbaikinya saat mengunggah ulang.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-dark mb-2">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="keterangan_tolak" class="form-control shadow-none border-light-subtle" rows="4" placeholder="Contoh: Gambar terlalu buram / nominal transfer tidak sesuai dengan tagihan / struk palsu..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top border-light p-3">
                    <button type="button" class="btn btn-light shadow-none fw-medium px-4 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-medium px-4 py-2 shadow-sm">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection