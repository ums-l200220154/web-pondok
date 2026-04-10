<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username'   => 'admin',
                'email'      => 'admin@pondok.com', // Tambahkan email
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'nis_FK'     => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'bendahara',
                'email'      => 'bendahara@pondok.com', // Tambahkan email
                'password'   => Hash::make('bendahara123'),
                'role'       => 'bendahara',
                'nis_FK'     => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'keamanan',
                'email'      => 'keamanan@pondok.com', // Tambahkan email
                'password'   => Hash::make('keamanan123'),
                'role'       => 'keamanan',
                'nis_FK'     => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'santri1',
                'email'      => 'santri1@gmail.com', // Tambahkan email
                'password'   => Hash::make('santri123'),
                'role'       => 'user',
                'nis_FK'     => 'S001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}