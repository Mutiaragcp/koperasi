<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBungaPinjaman extends Model
{
    protected $table = 'pengaturan_bunga_pinjamans';

    protected $fillable = [
        'tenor',
        'bunga_persen',
    ];

    protected $casts = [
        'tenor' => 'integer',
        'bunga_persen' => 'decimal:2',
    ];

    /**
     * Ambil semua pengaturan bunga sebagai array [tenor => bunga_persen],
     * siap dipakai langsung di Blade lewat @json() untuk kalkulator JS.
     */
    public static function mapTenorKeBunga(): array
    {
        return self::orderBy('tenor')
            ->pluck('bunga_persen', 'tenor')
            ->map(fn ($v) => (float) $v)
            ->toArray();
    }
}