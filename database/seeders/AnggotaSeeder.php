<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Anggota; // Pastikan Anda sudah membuat Model Anggota
use Faker\Factory as Faker;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan Faker dengan lokal Indonesia
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 20; $i++) {
            // 1. Insert ke tabel 'users' terlebih dahulu
            $user = User::create([
                'nama'     => $faker->name,
                'email'    => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // Password seragam untuk testing
                'role'     => 'anggota',
            ]);

            // 2. Insert ke tabel 'anggotas' menggunakan ID dari user yang baru dibuat
            Anggota::create([
                'user_id'    => $user->id,
                'no_anggota' => 'ANG-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'nik'        => $faker->numerify('################'), // 16 digit angka random
                'foto_ktp'   => null,
                'alamat'     => $faker->address,
                'no_hp'      => $faker->phoneNumber,
                'tgl_gabung' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'status'     => $faker->randomElement(['aktif', 'aktif', 'aktif', 'nonaktif']), // Rasio aktif lebih besar
            ]);
        }
    }
}