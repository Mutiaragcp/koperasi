<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('no_anggota')->unique();
            
            // Tambahan field KTP sesuai standar SOP Koperasi
            $table->string('nik', 16)->unique(); 
            $table->string('foto_ktp')->nullable(); // Path untuk menyimpan gambar
            
            $table->text('alamat');
            $table->string('no_hp');
            $table->date('tgl_gabung');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};