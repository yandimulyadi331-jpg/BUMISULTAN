<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KehadiranJamaahMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kehadiran_jamaah_masar';

    protected $fillable = [
        'jamaah_id',
        'tanggal_kehadiran',
        'jam_kehadiran',
        'jam_masuk',
        'jam_pulang',
        'lokasi',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_kehadiran' => 'date',
    ];

    /**
     * Relationship dengan jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(JamaahMasar::class, 'jamaah_id');
    }
}
