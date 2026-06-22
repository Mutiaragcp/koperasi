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
        Schema::create('pengaturan_shus', function (Blueprint $table) {
            $table->id();
            $table->decimal('persen_anggota', 5, 2)->default(40.00); // 40%
            $table->decimal('persen_cadangan', 5, 2)->default(25.00); // 25%
            $table->decimal('persen_pengurus', 5, 2)->default(10.00); // 10%
            $table->decimal('persen_karyawan', 5, 2)->default(5.00); // 5%
            $table->decimal('persen_pendidikan', 5, 2)->default(5.00); // 5%
            $table->decimal('persen_sosial', 5, 2)->default(15.00); // 15%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_shus');
    }
};
