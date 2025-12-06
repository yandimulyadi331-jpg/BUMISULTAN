<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanHistory extends Model
{
    use HasFactory;

    protected $table = 'pinjaman_history';

    protected $fillable = [
        'pinjaman_id',
        'aksi',
        'status_lama',
        'status_baru',
        'keterangan',
        'data_perubahan',
        'user_id',
        'user_name',
    ];

    protected $casts = [
        'data_perubahan' => 'array',
    ];

    /**
     * Relasi ke tabel Pinjaman
     */
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    /**
     * Relasi ke tabel User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
