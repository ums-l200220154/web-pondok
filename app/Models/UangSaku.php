<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangSaku extends Model
{
    use HasFactory;

    protected $table = 'uang_saku';

    protected $primaryKey = 'id_uangsaku';

    protected $fillable = [
        'saldo',
        'jumlah',
        'jenis',
        'keterangan',
        'tanggal',
        'nis',
    ];

    /**
     * RELASI
     */

    // Uang saku milik satu santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'nis', 'nis');
    }
}
