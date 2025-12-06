<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsTrackingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'aktivitas_id',
        'latitude',
        'longitude',
        'speed',
        'accuracy',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed' => 'decimal:2',
        'accuracy' => 'decimal:2',
    ];

    /**
     * Relasi ke aktivitas kendaraan
     */
    public function aktivitas()
    {
        return $this->belongsTo(AktivitasKendaraan::class, 'aktivitas_id');
    }
}
