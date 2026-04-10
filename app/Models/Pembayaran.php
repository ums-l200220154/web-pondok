<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'tanggal_pembayaran',
        'jumlah',
        'total_bayar',
        'status',
        'nis',
        'bukti',
        'keterangan_bendahara'
    ];

    /**
     * RELASI
     */

    // Pembayaran milik satu santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'nis', 'nis');
    }

    // Pembayaran memiliki banyak rincian pembayaran
    public function rincian()
    {
        return $this->hasMany(RincianPembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
