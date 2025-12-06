<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryInventaris extends Model
{
    use HasFactory;

    protected $table = 'history_inventaris';

    protected $fillable = [
        'inventaris_id',
        'jenis_aktivitas',
        'deskripsi',
        'status_sebelum',
        'status_sesudah',
        'lokasi_sebelum',
        'lokasi_sesudah',
        'jumlah',
        'karyawan_id',
        'peminjaman_id',
        'pengembalian_id',
        'data_perubahan',
        'foto',
        'user_id',
    ];

    protected $casts = [
        'data_perubahan' => 'array',
    ];

    // Relationships
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanInventaris::class, 'peminjaman_id');
    }

    public function pengembalian()
    {
        return $this->belongsTo(PengembalianInventaris::class, 'pengembalian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper methods
    public function getJenisAktivitasLabel()
    {
        $labels = [
            'input' => 'Input Barang Baru',
            'update' => 'Update Data',
            'pinjam' => 'Peminjaman',
            'kembali' => 'Pengembalian',
            'pindah_lokasi' => 'Pindah Lokasi',
            'maintenance' => 'Maintenance',
            'perbaikan' => 'Perbaikan',
            'hapus' => 'Hapus',
        ];

        return $labels[$this->jenis_aktivitas] ?? $this->jenis_aktivitas;
    }

    public function getJenisAktivitasColor()
    {
        $colors = [
            'input' => 'success',
            'update' => 'info',
            'pinjam' => 'warning',
            'kembali' => 'primary',
            'pindah_lokasi' => 'secondary',
            'maintenance' => 'warning',
            'perbaikan' => 'danger',
            'hapus' => 'dark',
        ];

        return $colors[$this->jenis_aktivitas] ?? 'secondary';
    }

    // Scopes
    public function scopeByInventaris($query, $inventarisId)
    {
        return $query->where('inventaris_id', $inventarisId);
    }

    public function scopeByJenisAktivitas($query, $jenis)
    {
        return $query->where('jenis_aktivitas', $jenis);
    }

    public function scopeByKaryawan($query, $karyawanId)
    {
        return $query->where('karyawan_id', $karyawanId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
