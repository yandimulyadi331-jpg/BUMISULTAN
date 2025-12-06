<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasKendaraan extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_kendaraans';
    
    protected $fillable = [
        'kode_aktivitas',
        'kendaraan_id',
        'driver',
        'email_driver',
        'penumpang',
        'tujuan',
        'waktu_keluar',
        'waktu_kembali',
        'km_awal',
        'km_akhir',
        'status',
        'keterangan_keluar',
        'keterangan_kembali',
        'tracking_data'
    ];

    protected $casts = [
        'waktu_keluar' => 'datetime',
        'waktu_kembali' => 'datetime',
        'km_awal' => 'decimal:2',
        'km_akhir' => 'decimal:2',
    ];

    /**
     * Relasi ke Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Get penumpang sebagai array
     */
    public function getPenumpangArrayAttribute()
    {
        return json_decode($this->penumpang, true) ?? [];
    }

    /**
     * Get tracking data sebagai array
     */
    public function getTrackingDataArrayAttribute()
    {
        return json_decode($this->tracking_data, true) ?? [];
    }

    /**
     * Calculate durasi perjalanan
     */
    public function getDurasiAttribute()
    {
        if ($this->waktu_kembali) {
            return $this->waktu_keluar->diff($this->waktu_kembali);
        }
        return null;
    }

    /**
     * Calculate jarak tempuh
     */
    public function getJarakTempuhAttribute()
    {
        if ($this->km_akhir && $this->km_awal) {
            return $this->km_akhir - $this->km_awal;
        }
        return 0;
    }
}
