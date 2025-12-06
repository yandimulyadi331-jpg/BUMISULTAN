<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanService extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_service';

    protected $fillable = [
        'kode_service',
        'kendaraan_id',
        'tanggal_service',
        'jenis_service',
        'bengkel',
        'deskripsi_pekerjaan',
        'km_service',
        'biaya',
        'mekanik',
        'sparepart_diganti',
        'service_selanjutnya',
        'km_service_selanjutnya',
        'pelapor',
        'keterangan',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal_service' => 'date',
        'service_selanjutnya' => 'date',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
