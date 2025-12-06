<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelanjaKhidmat extends Model
{
    use HasFactory;

    protected $table = 'belanja_khidmat';

    protected $fillable = [
        'jadwal_khidmat_id',
        'nama_barang',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total_harga',
        'keterangan'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    // Relasi ke jadwal khidmat
    public function jadwal()
    {
        return $this->belongsTo(JadwalKhidmat::class, 'jadwal_khidmat_id');
    }
}
