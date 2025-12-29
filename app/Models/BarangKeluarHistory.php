<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarHistory extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_history';

    protected $fillable = [
        'barang_keluar_id',
        'status_dari',
        'status_ke',
        'catatan',
        'foto',
        'user_id',
    ];

    protected $casts = [
        'foto' => 'array',
    ];

    // Relasi ke barang keluar
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
