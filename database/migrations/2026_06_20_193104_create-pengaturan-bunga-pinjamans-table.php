<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line to import the DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_bunga_pinjamans', function (Blueprint $table) {
            $table->id();
            $table->integer('tenor')->unique()->comment('Lama angsuran dalam bulan: 3, 6, 12, 24');
            $table->decimal('bunga_persen', 5, 2)->comment('Bunga flat per bulan dalam persen, contoh 1.50 = 1.5%');
            $table->timestamps();
        });

        // Seed nilai default, bisa diubah lagi lewat halaman pengaturan
        DB::table('pengaturan_bunga_pinjamans')->insert([
            ['tenor' => 3,  'bunga_persen' => 1.50, 'created_at' => now(), 'updated_at' => now()],
            ['tenor' => 6,  'bunga_persen' => 1.75, 'created_at' => now(), 'updated_at' => now()],
            ['tenor' => 12, 'bunga_persen' => 2.00, 'created_at' => now(), 'updated_at' => now()],
            ['tenor' => 24, 'bunga_persen' => 2.25, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_bunga_pinjamans');
    }
};