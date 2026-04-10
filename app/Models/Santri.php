<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';

    // Primary key bukan id
    protected $primaryKey = 'nis';

    // Karena primary key bertipe string
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nis',
        'nik',
        'nama',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ayah',
        'no_hp',
    ];

    /**
     * RELASI
     */

    // Santri memiliki banyak absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'nis', 'nis');
    }

    // Santri memiliki banyak pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'nis', 'nis');
    }

    // Santri memiliki banyak uang saku
    public function uangSaku()
    {
        return $this->hasMany(UangSaku::class, 'nis', 'nis');
    }
}
