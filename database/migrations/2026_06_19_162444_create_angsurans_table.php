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
    Schema::create('angsurans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pinjaman_id')->constrained('pinjamans')->onDelete('cascade');
        $table->integer('ke_berapa'); // angsuran bulan ke-1, ke-2, dst.
        $table->decimal('jumlah_bayar', 15, 2);
        $table->decimal('denda', 15, 2)->default(0);
        $table->date('tgl_jatuh_tempo');
        $table->date('tgl_bayar')->nullable();
        $table->enum('status', ['belum_bayar', 'lunas', 'telat'])->default('belum_bayar');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsurans');
    }
};
