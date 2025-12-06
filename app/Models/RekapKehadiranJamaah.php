<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapKehadiranJamaah extends Model
{
    use HasFactory;

    protected $table = 'rekap_kehadiran_jamaah';

    protected $fillable = [
        'tanggal',
        'jumlah_hadir',
        'total_jamaah',
        'persentase',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_hadir' => 'integer',
        'total_jamaah' => 'integer',
        'persentase' => 'decimal:2',
    ];

    // Relationship ke User (creator)
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // Auto-calculate persentase sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->total_jamaah && $model->total_jamaah > 0) {
                $model->persentase = round(($model->jumlah_hadir / $model->total_jamaah) * 100, 2);
            }
        });
    }
}
