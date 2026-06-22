<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pengurus\AnggotaController;
use App\Http\Controllers\Pengurus\DashboardController;
use App\Http\Controllers\Pengurus\SimpananController;
use App\Http\Controllers\Pengurus\JenisSimpananController;
use App\Http\Controllers\Pengurus\PinjamanController;
use App\Http\Controllers\Pengurus\AngsuranController;
use App\Http\Controllers\Pengurus\LaporanController;
use App\Http\Controllers\Pengurus\ShuController;
use App\Http\Controllers\Pengurus\PengaturanBungaController; 
use App\Http\Controllers\Pengurus\UserController;
use App\Http\Controllers\Anggota\DashboardAnggotaController;
use App\Http\Controllers\Anggota\AngsuranAnggotaController;
use App\Http\Controllers\Anggota\SimpananAnggotaController;
use App\Http\Controllers\Anggota\PengajuanPinjamanController;

// ============================================
// ROUTE AUTENTIKASI (Bisa diakses tanpa login)
// ============================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================
// ROUTE YANG MEMBUTUHKAN LOGIN
// ============================================
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD UTAMA MANAJEMEN (Admin & Bendahara) ---
    Route::get('/', [DashboardController::class, 'index'])->middleware('role:admin,bendahara');

    // ============================================
    // 1. GROUP ROLE: ANGGOTA (Prefix 'anggota')
    // ============================================
    Route::middleware(['role:anggota'])->prefix('anggota')->group(function () {
        
        Route::get('/dashboard', [DashboardAnggotaController::class, 'index']);

        // Simpanan Anggota
        Route::get('/simpanan', [SimpananAnggotaController::class, 'index']);
        Route::get('/simpanan/cetak', [SimpananAnggotaController::class, 'cetakRekeningKoran']);
        Route::get('/simpanan/setor', [SimpananAnggotaController::class, 'setor']);
        Route::post('/simpanan/setor', [SimpananAnggotaController::class, 'prosesSetor']);

        // Pinjaman Anggota
        Route::get('/pinjaman', [PengajuanPinjamanController::class, 'index']);
        Route::post('/pinjaman', [PengajuanPinjamanController::class, 'store']);

        // Angsuran Anggota
        Route::get('/angsuran', [AngsuranAnggotaController::class, 'index']);
        Route::post('/angsuran/{id}/upload-bukti', [AngsuranAnggotaController::class, 'uploadBukti']);
        Route::get('/shu', [ShuController::class, 'historyAnggota'])->name('shu.anggota');
    });

    // ============================================
    // 2. GROUP ROLE: ADMIN (Master Data & Hak Akses)
    // ============================================
    Route::middleware(['role:admin'])->group(function () {
        
        // Modul Manajemen Anggota
        Route::resource('anggota', AnggotaController::class);
        
        // Modul Master Jenis Simpanan
        Route::resource('jenis-simpanan', JenisSimpananController::class);

        // --- Tambahan: Modul Pengaturan Bunga Pinjaman ---
        Route::get('/pengaturan/bunga-pinjaman', [PengaturanBungaController::class, 'index'])->name('pengaturan.bunga-pinjaman');
        Route::post('/pengaturan/bunga-pinjaman', [PengaturanBungaController::class, 'update'])->name('pengaturan.bunga-pinjaman.update');

        // Modul Manajemen User
        Route::get('/users', [UserController::class, 'index']);
        Route::patch('/users/{id}/role', [UserController::class, 'updateRole']);
        Route::patch('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
    });

    // ============================================
    // 3. GROUP ROLE: BENDAHARA & ADMIN (Fitur Keuangan)
    // Note: Admin juga diberi akses agar bisa monitoring/bantu backup
    // ============================================
    Route::middleware(['role:bendahara,admin'])->group(function () {

        // Modul Kas Simpanan
        Route::controller(SimpananController::class)->group(function () {
            Route::get('/simpanan', 'index');
            Route::get('/simpanan/create', 'create');
            Route::post('/simpanan', 'store');
            Route::get('/simpanan/cek-saldo', 'cekSaldo'); 
            Route::get('/simpanan/export-excel', 'exportExcel');
            Route::get('/simpanan/verifikasi', 'verifikasiIndex');
            Route::post('/simpanan/{id}/approve', 'approve');
            Route::post('/simpanan/{id}/reject', 'reject');
        });

        // Modul Data Pinjaman
        Route::controller(PinjamanController::class)->group(function () {
            Route::get('/pinjaman', 'index');
            Route::get('/pinjaman/create', 'create');
            Route::get('/pinjaman/export-excel', 'exportExcel');
            Route::post('/pinjaman', 'store');
            Route::get('/pinjaman/{id}', 'show');
            Route::post('/pinjaman/{id}/approve', 'approve');
            Route::post('/pinjaman/{id}/reject', 'reject');
            Route::delete('/pinjaman/{id}', 'destroy');
        });

        // Modul Transaksi Angsuran / Kasir
        Route::get('/transaksi-angsuran', [AngsuranController::class, 'index']);
        Route::get('/angsuran/{id}/bayar', [AngsuranController::class, 'bayarForm']);
        Route::post('/angsuran/{id}/bayar', [AngsuranController::class, 'bayarAngsuran']);
        Route::post('/angsuran/{id}/tolak', [AngsuranController::class, 'tolakPembayaran']);

        // Modul Akuntansi, Laporan & SHU
        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf']);
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel']);
        Route::get('/shu', [ShuController::class, 'index']);
        Route::post('/shu/bagikan', [ShuController::class, 'bagikan']);
        Route::get('/shu/pengaturan', [ShuController::class, 'pengaturan']);
        Route::post('/shu/pengaturan', [ShuController::class, 'simpanPengaturan']);
    });

});