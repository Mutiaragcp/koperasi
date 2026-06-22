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
    Schema::create('simpanans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
        $table->foreignId('jenis_simpanan_id')->constrained('jenis_simpanans')->onDelete('restrict');
        $table->enum('jenis_transaksi', ['setor', 'tarik']);
        $table->decimal('jumlah', 15, 2);
        $table->date('tanggal');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanans');
    }
};
