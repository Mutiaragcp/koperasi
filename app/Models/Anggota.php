<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'user_id',
        'no_anggota',
        'nik',
        'foto_ktp',
        'alamat',
        'no_hp',
        'tgl_gabung',
        'status',
    ];

    // Relasi balik ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // (Opsional) Persiapan Relasi ke Pinjaman nanti
    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class, 'anggota_id', 'id');
    }
    
    // (Opsional) Persiapan Relasi ke Simpanan nanti
    public function simpanans()
    {
        return $this->hasMany(Simpanan::class, 'anggota_id', 'id');
    }
}