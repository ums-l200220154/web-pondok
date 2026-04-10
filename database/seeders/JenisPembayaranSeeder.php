<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPembayaran;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        JenisPembayaran::insert([
            [
                'nama' => 'Uang Makan',
                'nominal' => 200000,
                'keterangan' => 'Biaya makan bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Uang Pondok',
                'nominal' => 75000,
                'keterangan' => 'Biaya operasional pondok',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Uang Diniyah',
                'nominal' => 25000,
                'keterangan' => 'Biaya kegiatan diniyah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}