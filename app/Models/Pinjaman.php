<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar Laravel tidak mencari tabel 'pinjamen'
    protected $table = 'pinjamans';

    protected $fillable = [
        'anggota_id', 'disetujui_oleh', 'jumlah_pinjaman', 
        'tenor', 'bunga', 'status', 'tgl_pengajuan', 'tgl_cair'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function angsurans()
    {
        return $this->hasMany(Angsuran::class);
    }
}