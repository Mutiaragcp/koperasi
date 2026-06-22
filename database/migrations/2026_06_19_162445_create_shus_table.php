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
    Schema::create('shus', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
        $table->year('periode'); // Menggunakan tipe year untuk menyimpan tahun (misal: 2026)
        $table->decimal('jumlah', 15, 2);
        $table->date('tgl_dihitung');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shus');
    }
};
