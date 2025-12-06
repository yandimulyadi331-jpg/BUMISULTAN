<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceKendaraan extends Model
{
    use HasFactory;

    protected $table = 'service_kendaraans';
    
    protected $fillable = [
        'kode_service',
        'kendaraan_id',
        'driver_service',
        'waktu_service',
        'jenis_service',
        'bengkel',
        'deskripsi_kerusakan',
        'pekerjaan',
        'km_service',
        'estimasi_biaya',
        'estimasi_selesai',
        'pic',
        'foto_before',
        'latitude_service',
        'longitude_service',
        'waktu_selesai',
        'km_selesai',
        'biaya_akhir',
        'pekerjaan_selesai',
        'catatan_mekanik',
        'kondisi_kendaraan',
        'pic_selesai',
        'foto_after',
        'latitude_selesai',
        'longitude_selesai',
        'status'
    ];

    protected $casts = [
        'waktu_service' => 'datetime',
        'waktu_selesai' => 'datetime',
        'estimasi_selesai' => 'date',
        'estimasi_biaya' => 'decimal:2',
        'biaya_akhir' => 'decimal:2',
        'km_service' => 'decimal:2',
        'km_selesai' => 'decimal:2',
    ];

    /**
     * Relasi ke Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Calculate durasi service
     */
    public function getDurasiServiceAttribute()
    {
        if ($this->tanggal_selesai && $this->tanggal_service) {
            return $this->tanggal_service->diffInDays($this->tanggal_selesai);
        }
        return null;
    }
}
