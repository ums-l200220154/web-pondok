<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianPembayaran extends Model
{
    use HasFactory;

    protected $table = 'rincian_pembayaran';

    protected $primaryKey = 'id_rincian';

    protected $fillable = [
        'jenis',
        'jumlah',
        'kategori',
        'id_pembayaran',
        'bulan',
        'tahun',
    ];

    /**
     * RELASI
     */

    // Rincian pembayaran milik satu pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
