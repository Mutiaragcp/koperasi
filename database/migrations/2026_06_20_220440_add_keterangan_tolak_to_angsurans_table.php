<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->text('keterangan_tolak')->nullable()->after('bukti_pembayaran');
        });

        // Ubah enum status menggunakan raw statement untuk menambahkan 'ditolak'
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'lunas', 'telat', 'ditolak') NOT NULL DEFAULT 'belum_bayar'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->dropColumn('keterangan_tolak');
        });
        
        // Kembalikan enum status ke versi sebelumnya
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'lunas', 'telat') NOT NULL DEFAULT 'belum_bayar'");
    }
};
