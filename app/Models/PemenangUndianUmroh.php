<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PemenangUndianUmroh extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemenang_undian_umroh';

    protected $fillable = [
        'undian_id',
        'jamaah_id',
        'urutan_pemenang',
        'tanggal_pengumuman',
        'tanggal_keberangkatan',
        'tanggal_kepulangan',
        'status_keberangkatan',
        'dokumentasi',
        'testimoni',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pengumuman' => 'date',
        'tanggal_keberangkatan' => 'date',
        'tanggal_kepulangan' => 'date',
        'urutan_pemenang' => 'integer',
    ];

    /**
     * Relationship dengan undian
     */
    public function undian()
    {
        return $this->belongsTo(UndianUmroh::class, 'undian_id');
    }

    /**
     * Relationship dengan jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(JamaahMajlisTaklim::class, 'jamaah_id');
    }

    /**
     * Scope untuk pemenang yang sudah berangkat
     */
    public function scopeSudahBerangkat($query)
    {
        return $query->where('status_keberangkatan', 'sudah_berangkat');
    }

    /**
     * Scope untuk pemenang yang selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status_keberangkatan', 'selesai');
    }
}
