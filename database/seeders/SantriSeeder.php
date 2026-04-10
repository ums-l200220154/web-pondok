<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SantriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('santri')->insert([
            [
                'nis' => 'S001',
                'nik' => '3201010101010001',
                'nama' => 'Ahmad Fauzi',
                'alamat' => 'Bandung',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2010-01-10',
                'nama_ayah' => 'Bapak Fauzan',
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => 'S002',
                'nik' => '3201010101010002',
                'nama' => 'Muhammad Rizki',
                'alamat' => 'Garut',
                'tempat_lahir' => 'Garut',
                'tanggal_lahir' => '2011-05-05',
                'nama_ayah' => 'Bapak Ridwan',
                'no_hp' => '081298765432',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
