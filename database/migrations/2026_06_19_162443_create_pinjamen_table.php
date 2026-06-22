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
    Schema::create('pinjamans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
        // Arahkan foreign key disetujui_oleh ke kolom id di tabel users
        $table->foreignId('disetujui_oleh')->nullable()->constrained('users', 'id')->onDelete('set null');
        
        $table->decimal('jumlah_pinjaman', 15, 2);
        $table->integer('tenor'); // dalam satuan bulan
        $table->decimal('bunga', 5, 2); // persentase bunga (flat)
        $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'lunas'])->default('menunggu');
        $table->date('tgl_pengajuan');
        $table->date('tgl_cair')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamen');
    }
};
