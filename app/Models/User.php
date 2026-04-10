<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel
    protected $table = 'users';

    // Primary key custom
    protected $primaryKey = 'id_user';

    // Primary key bukan string & bukan increment default id
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'nis_FK',
    ];

    // Kolom yang disembunyikan
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Nonaktifkan email & email verification
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * RELASI
     * User (role user) bisa terhubung ke satu santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'nis_FK', 'nis');
    }
}
