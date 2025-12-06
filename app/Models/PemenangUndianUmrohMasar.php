<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PemenangUndianUmrohMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemenang_undian_umroh_masar';

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
        return $this->belongsTo(UndianUmrohMasar::class, 'undian_id');
    }

    /**
     * Relationship dengan jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(JamaahMasar::class, 'jamaah_id');
    }

    /**
     * Scope untuk filter berdasarkan status keberangkatan
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_keberangkatan', $status);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tanggal_pengumuman', $year);
    }

    /**
     * Get status keberangkatan badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu' => '<span class="badge bg-warning">Menunggu</span>',
            'siap_berangkat' => '<span class="badge bg-info">Siap Berangkat</span>',
            'berangkat' => '<span class="badge bg-primary">Berangkat</span>',
            'pulang' => '<span class="badge bg-success">Pulang</span>',
            'batal' => '<span class="badge bg-danger">Batal</span>',
        ];

        return $badges[$this->status_keberangkatan] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
