<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // 2. Buat Akun Bendahara
        User::create([
            'nama' => 'Ibu Bendahara',
            'email' => 'bendahara@koperasi.com',
            'password' => Hash::make('password'),
            'role' => 'bendahara'
        ]);

        // 3. Buat 1 Akun Anggota (Untuk Testing)
        $userAnggota = User::create([
            'nama' => 'Desta (Anggota)',
            'email' => 'desta@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'anggota'
        ]);

        // Buatkan profil anggotanya
        Anggota::create([
            'user_id' => $userAnggota->id,
            'no_anggota' => 'ANG-' . date('Ym') . '-0001',
            'nik' => '3201234567890001',
            'alamat' => 'Jl. Koperasi No. 1',
            'no_hp' => '081234567890',
            'tgl_gabung' => now()->toDateString(),
            'status' => 'aktif'
        ]);

        // 4. Input Master Jenis Simpanan
        JenisSimpanan::insert([
            ['nama' => 'Simpanan Pokok', 'keterangan' => 'Wajib dibayar sekali saat mendaftar', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Simpanan Wajib', 'keterangan' => 'Wajib dibayar setiap bulan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Simpanan Sukarela', 'keterangan' => 'Bebas dan bisa ditarik kapan saja', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}