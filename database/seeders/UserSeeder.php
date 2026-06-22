<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Bapak Super Admin',
                'email' => 'superadmin@koperasi.com',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
            ],
            [
                'nama' => 'Bapak Admin',
                'email' => 'admin@koperasi.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'nama' => 'Ibu Bendahara',
                'email' => 'bendahara@koperasi.com',
                'password' => Hash::make('password123'),
                'role' => 'bendahara',
            ],
            [
                'nama' => 'Bapak Manajer',
                'email' => 'manajer@koperasi.com',
                'password' => Hash::make('password123'),
                'role' => 'manajer',
            ],
            [
                'nama' => 'Andi Wijaya (Anggota)',
                'email' => 'andi@email.com',
                'password' => Hash::make('password123'),
                'role' => 'anggota',
            ]
        ]);
    }
}