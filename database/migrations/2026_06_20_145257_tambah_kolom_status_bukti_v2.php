<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('simpanans', function (Blueprint $table) {
            // Kita cek dulu, kalau kolomnya belum ada, baru ditambahkan
            if (!Schema::hasColumn('simpanans', 'bukti_transaksi')) {
                $table->string('bukti_transaksi')->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('simpanans', 'status')) {
                $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('disetujui')->after('jumlah');
            }
        });
    }

    public function down()
    {
        Schema::table('simpanans', function (Blueprint $table) {
            $table->dropColumn(['bukti_transaksi', 'status']);
        });
    }
};