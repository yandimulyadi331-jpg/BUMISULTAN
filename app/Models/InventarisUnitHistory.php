<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisUnitHistory extends Model
{
    use HasFactory;

    protected $table = 'inventaris_unit_history';

    public $timestamps = false;

    protected $fillable = [
        'inventaris_detail_unit_id',
        'jenis_aktivitas',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'status_sebelum',
        'status_sesudah',
        'lokasi_sebelum',
        'lokasi_sesudah',
        'keterangan',
        'referensi_id',
        'referensi_type',
        'dilakukan_oleh',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }

    // Relationships
    public function detailUnit()
    {
        return $this->belongsTo(InventarisDetailUnit::class, 'inventaris_detail_unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
    
    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanInventaris::class, 'referensi_id');
    }
    
    public function pengembalian()
    {
        return $this->belongsTo(PengembalianInventaris::class, 'referensi_id');
    }

    // Helper Methods
    public function getJenisAktivitasLabel()
    {
        return match($this->jenis_aktivitas) {
            'input' => 'Input Baru',
            'pinjam' => 'Peminjaman',
            'kembali' => 'Pengembalian',
            'maintenance' => 'Maintenance',
            'perbaikan' => 'Perbaikan',
            'update_kondisi' => 'Update Kondisi',
            'pindah_lokasi' => 'Pindah Lokasi',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            'hapus' => 'Dihapus',
            default => ucfirst($this->jenis_aktivitas),
        };
    }

    public function getJenisAktivitasIcon()
    {
        return match($this->jenis_aktivitas) {
            'input' => 'ti ti-plus',
            'pinjam' => 'ti ti-hand-grab',
            'kembali' => 'ti ti-arrow-back-up',
            'maintenance' => 'ti ti-tools',
            'perbaikan' => 'ti ti-tool',
            'update_kondisi' => 'ti ti-edit',
            'pindah_lokasi' => 'ti ti-map-pin',
            'rusak' => 'ti ti-alert-triangle',
            'hilang' => 'ti ti-exclamation-circle',
            'hapus' => 'ti ti-trash',
            default => 'ti ti-clock',
        };
    }

    public function getJenisAktivitasBadgeClass()
    {
        return match($this->jenis_aktivitas) {
            'input' => 'bg-primary',
            'pinjam' => 'bg-warning',
            'kembali' => 'bg-success',
            'maintenance' => 'bg-info',
            'perbaikan' => 'bg-info',
            'update_kondisi' => 'bg-secondary',
            'pindah_lokasi' => 'bg-dark',
            'rusak' => 'bg-danger',
            'hilang' => 'bg-danger',
            'hapus' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function formatTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }
}
