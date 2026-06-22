<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinjaman_id', 'ke_berapa', 'jumlah_bayar', 
        'denda', 'tgl_jatuh_tempo', 'tgl_bayar', 'status', 'keterangan_tolak'
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}