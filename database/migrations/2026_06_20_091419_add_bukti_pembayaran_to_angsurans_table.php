<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan perintah penambahan kolom dan modifikasi ENUM
     */
    public function up(): void
    {
        // 1. Tambahkan kolom untuk menyimpan nama file foto struk
        Schema::table('angsurans', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('jumlah_bayar');
        });

        // 2. Modifikasi struktur ENUM bawaan database menggunakan query SQL mentah
        // (Cara ini paling aman untuk menghindari error library saat mengubah ENUM di Laravel)
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'lunas', 'telat') NOT NULL DEFAULT 'belum_bayar'");
    }

    /**
     * Kembalikan ke kondisi semula jika di-rollback
     */
    public function down(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });

        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('belum_bayar', 'lunas', 'telat') NOT NULL DEFAULT 'belum_bayar'");
    }
};