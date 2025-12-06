<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanKeluarMasuk extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_keluar_masuk';

    protected $fillable = [
        'kode_log',
        'kendaraan_id',
        'tipe',
        'nik',
        'pengemudi',
        'tujuan',
        'waktu_keluar',
        'waktu_masuk',
        'km_keluar',
        'km_masuk',
        'km_tempuh',
        'kondisi_keluar',
        'kondisi_masuk',
        'keperluan',
        'keterangan',
        'petugas',
    ];

    protected $casts = [
        'waktu_keluar' => 'datetime',
        'waktu_masuk' => 'datetime',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
