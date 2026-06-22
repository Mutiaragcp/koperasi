<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('simpanans', function (Blueprint $table) {
            $table->string('bukti_transaksi')->nullable()->after('jumlah');
            // Status default-nya adalah pending saat anggota pertama kali setor
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('disetujui')->after('bukti_transaksi');
        });
    }

    public function down()
    {
        Schema::table('simpanans', function (Blueprint $table) {
            $table->dropColumn(['bukti_transaksi', 'status']);
        });
    }
};
