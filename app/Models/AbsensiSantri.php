<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsensiSantri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'absensi_santri';

    protected $fillable = [
        'jadwal_santri_id',
        'santri_id',
        'tanggal_absensi',
        'waktu_absensi',
        'status_kehadiran',
        'keterangan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_absensi' => 'date',
    ];

    // Relasi dengan jadwal santri
    public function jadwalSantri()
    {
        return $this->belongsTo(JadwalSantri::class, 'jadwal_santri_id');
    }

    // Relasi dengan santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    // Scope untuk filter berdasarkan status
    public function scopeHadir($query)
    {
        return $query->where('status_kehadiran', 'hadir');
    }

    public function scopeIjin($query)
    {
        return $query->where('status_kehadiran', 'ijin');
    }

    public function scopeSakit($query)
    {
        return $query->where('status_kehadiran', 'sakit');
    }

    public function scopeKhidmat($query)
    {
        return $query->where('status_kehadiran', 'khidmat');
    }

    public function scopeAbsen($query)
    {
        return $query->where('status_kehadiran', 'absen');
    }
}
