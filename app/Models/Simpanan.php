<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    // Pastikan ini mengarah ke tabel simpanans, BUKAN angsurans!
    protected $table = 'simpanans';

    protected $fillable = [
        'anggota_id',
        'jenis_simpanan_id',
        'jenis_transaksi',
        'jumlah',
        'tanggal',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function jenis_simpanan()
    {
        return $this->belongsTo(JenisSimpanan::class, 'jenis_simpanan_id');
    }
}