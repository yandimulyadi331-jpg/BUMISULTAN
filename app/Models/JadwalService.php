<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalService extends Model
{
    use HasFactory;

    protected $table = 'jadwal_services';
    
    protected $fillable = [
        'kendaraan_id',
        'jenis_service',
        'tipe_interval',
        'interval_km',
        'km_terakhir',
        'interval_hari',
        'tanggal_terakhir',
        'jadwal_berikutnya',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_terakhir' => 'date',
        'jadwal_berikutnya' => 'date',
    ];

    /**
     * Relasi ke Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Check apakah sudah jatuh tempo (untuk tipe interval waktu)
     */
    public function isOverdue()
    {
        if ($this->tipe_interval === 'waktu' && $this->jadwal_berikutnya) {
            return $this->jadwal_berikutnya < now();
        }
        return false;
    }
}
