<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranJamaah extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_jamaah';

    protected $fillable = [
        'jamaah_id',
        'tanggal_kehadiran',
        'jam_masuk',
        'jam_pulang',
        'lokasi_masuk',
        'lokasi_pulang',
        'foto_masuk',
        'foto_pulang',
        'status_kehadiran',
        'status',
        'keterangan',
        'device_id',
        'sumber_absen'
    ];

    protected $casts = [
        'tanggal_kehadiran' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_pulang' => 'datetime:H:i:s',
    ];

    /**
     * Relationship dengan jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(JamaahMajlisTaklim::class, 'jamaah_id');
    }

    /**
     * Scope untuk kehadiran hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_kehadiran', today());
    }

    /**
     * Scope untuk kehadiran bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_kehadiran', now()->month)
                     ->whereYear('tanggal_kehadiran', now()->year);
    }

    /**
     * Scope untuk kehadiran berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_kehadiran', $status);
    }
}
