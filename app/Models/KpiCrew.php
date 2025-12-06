<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiCrew extends Model
{
    use HasFactory;

    protected $table = 'kpi_crew';

    protected $fillable = [
        'nik',
        'bulan',
        'tahun',
        'kehadiran_count',
        'aktivitas_count',
        'perawatan_count',
        'kehadiran_point',
        'aktivitas_point',
        'perawatan_point',
        'total_point',
        'ranking'
    ];

    protected $casts = [
        'kehadiran_point' => 'decimal:2',
        'aktivitas_point' => 'decimal:2',
        'perawatan_point' => 'decimal:2',
        'total_point' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the karyawan that owns the KPI.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    /**
     * Scope untuk mengurutkan berdasarkan ranking
     */
    public function scopeOrderedByRanking($query)
    {
        return $query->orderBy('ranking', 'asc')->orderBy('total_point', 'desc');
    }

    /**
     * Scope untuk mengurutkan berdasarkan total point
     */
    public function scopeOrderedByPoint($query)
    {
        return $query->orderBy('total_point', 'desc');
    }

    /**
     * Get periode display text
     */
    public function getPeriodeTextAttribute()
    {
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $bulanIndo[$this->bulan] . ' ' . $this->tahun;
    }
}
