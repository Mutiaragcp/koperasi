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
        Schema::table('shus', function (Blueprint $table) {
            // Menambahkan rincian agar transparan di mata anggota
            $table->decimal('jasa_modal', 15, 2)->after('periode')->default(0);
            $table->decimal('jasa_usaha', 15, 2)->after('jasa_modal')->default(0);
            // Kolom 'jumlah' yang sudah ada di database Anda akan menjadi hasil dari (jasa_modal + jasa_usaha)
        });
    }

    public function down()
    {
        Schema::table('shus', function (Blueprint $table) {
            $table->dropColumn(['jasa_modal', 'jasa_usaha']);
        });
    }
};
