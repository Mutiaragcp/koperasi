<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            
            // Apakah uangnya bertambah (masuk) atau berkurang (keluar)
            $table->enum('jenis', ['masuk', 'keluar']); 
            
            // Asal usul uang (misal: 'Angsuran', 'Pencairan Pinjaman', 'Simpanan', 'Biaya Operasional')
            $table->string('kategori'); 
            
            // Jumlah uangnya
            $table->decimal('nominal', 15, 2); 
            
            // Catatan detail (misal: "Pembayaran Angsuran ke-3 atas nama Desta")
            $table->text('keterangan')->nullable(); 
            
            // Relasi ke siapa Kasir/Admin yang mengeksekusi uang ini (Opsional)
            $table->foreignId('dieksekusi_oleh')->nullable()->constrained('users')->nullOnDelete(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_kas');
    }
};