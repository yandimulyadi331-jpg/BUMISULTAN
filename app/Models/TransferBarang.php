<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBarang extends Model
{
    use HasFactory;

    protected $table = 'transfer_barangs';
    
    protected $fillable = [
        'kode_transfer',
        'barang_id',
        'ruangan_asal_id',
        'ruangan_tujuan_id',
        'jumlah_transfer',
        'tanggal_transfer',
        'petugas',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_transfer' => 'date'
    ];

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke Ruangan Asal
     */
    public function ruanganAsal()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_asal_id');
    }

    /**
     * Relasi ke Ruangan Tujuan
     */
    public function ruanganTujuan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_tujuan_id');
    }
}
