<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPerawatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_perawatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tipe_periode',
        'urutan',
        'kategori',
        'is_active',
        'jam_mulai',
        'jam_selesai',
        'tanggal_target',
        'bulan_target',
        'hari_minggu',
        'tanggal_bulan'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
        'tanggal_target' => 'date',
        'hari_minggu' => 'integer',
        'tanggal_bulan' => 'integer'
    ];

    public function logs()
    {
        return $this->hasMany(PerawatanLog::class, 'master_perawatan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_periode', $tipe);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('nama_kegiatan', 'asc');
    }
}
