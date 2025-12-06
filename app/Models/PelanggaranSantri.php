<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelanggaranSantri extends Model
{
    use HasFactory, SoftDeletes;
    use HasFactory, SoftDeletes;

    protected $table = 'pelanggaran_santri';

    protected $fillable = [
        'user_id',
        'nama_santri',
        'nik_santri',
        'foto',
        'keterangan',
        'tanggal_pelanggaran',
        'point',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pelanggaran' => 'date',
        'point' => 'integer',
    ];

    /**
     * Relasi ke Santri yang melanggar
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'user_id');
    }

    /**
     * Relasi ke User (Petugas yang mencatat)
     */
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Hitung total pelanggaran per santri
     */
    public static function totalPelanggaranSantri($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    /**
     * Hitung total point per santri
     */
    public static function totalPointSantri($userId)
    {
        return self::where('user_id', $userId)->sum('point');
    }

    /**
     * Get status pelanggaran berdasarkan total point
     * Range 1-10:
     * Berat: 8-10
     * Sedang: 5-7
     * Ringan: 1-4
     */
    public static function getStatusPelanggaran($totalPoint)
    {
        if ($totalPoint >= 8) {
            return [
                'status' => 'Berat',
                'warna' => 'danger',
                'badge' => 'bg-red-500',
                'text' => 'text-red-700',
                'bg' => 'bg-red-100'
            ];
        } elseif ($totalPoint >= 5) {
            return [
                'status' => 'Sedang',
                'warna' => 'warning',
                'badge' => 'bg-yellow-500',
                'text' => 'text-yellow-700',
                'bg' => 'bg-yellow-100'
            ];
        } elseif ($totalPoint >= 1) {
            return [
                'status' => 'Ringan',
                'warna' => 'success',
                'badge' => 'bg-green-500',
                'text' => 'text-green-700',
                'bg' => 'bg-green-100'
            ];
        } else {
            return [
                'status' => 'Tidak Ada',
                'warna' => 'secondary',
                'badge' => 'bg-gray-500',
                'text' => 'text-gray-700',
                'bg' => 'bg-gray-100'
            ];
        }
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal_pelanggaran', [$startDate, $endDate]);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan santri
     */
    public function scopeFilterBySantri($query, $userId)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        return $query;
    }
}
