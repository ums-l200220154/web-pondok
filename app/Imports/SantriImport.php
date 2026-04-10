<?php

namespace App\Imports;

use App\Models\Santri;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class SantriImport implements ToCollection, WithStartRow, SkipsEmptyRows
{
    /**
     * Tentukan baris mana data dimulai.
     * Jika baris 1 header, maka data mulai baris 2.
     */
    public function startRow(): int
    {
        return 2; 
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Filter baris jika semua kolom kosong
            if ($row->filter()->isEmpty()) {
                continue;
            }

            // Ambil data berdasarkan urutan kolom (0, 1, 2, dst)
            // Sesuaikan indeks [0] dengan letak kolom di Excel kamu
            $nis           = trim($row[0] ?? '');
            $nik           = trim($row[1] ?? '');
            $nama          = trim($row[2] ?? '');
            $alamat        = trim($row[3] ?? '');
            $tempat_lahir  = trim($row[4] ?? '');
            $tgl_mentah    = $row[5] ?? null; // Kolom tanggal_lahir
            $nama_ayah     = trim($row[6] ?? '');
            $no_hp         = trim($row[7] ?? '');

            // Validasi sederhana: Jika NIS atau Nama kosong, lewati baris ini
            if (!$nis || !$nama) {
                Log::warning("Baris ke-" . ($index + 2) . " dilewati karena NIS/Nama kosong.");
                continue;
            }

            // Cek apakah Santri dengan NIS ini sudah ada di database (untuk menghindari error duplicate)
            if (Santri::where('nis', $nis)->exists()) {
                Log::info("Santri dengan NIS $nis sudah ada, dilewati.");
                continue;
            }

            // Logika konversi tanggal
            $tanggal_lahir = null;
            if ($tgl_mentah) {
                try {
                    if (is_numeric($tgl_mentah)) {
                        $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_mentah);
                    } else {
                        // Coba parse teks (misal: 10/01/2010)
                        $tanggal_lahir = Carbon::parse($tgl_mentah);
                    }
                } catch (\Exception $e) {
                    $tanggal_lahir = now(); // Fallback jika gagal parse
                }
            }

            // Simpan ke Database
            Santri::create([
                'nis'           => $nis,
                'nik'           => $nik,
                'nama'          => $nama,
                'alamat'        => $alamat,
                'tempat_lahir'  => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'nama_ayah'     => $nama_ayah,
                'no_hp'         => $no_hp,
            ]);
        }
    }
}