<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerawatanLog extends Model
{
    use HasFactory;

    protected $table = 'perawatan_log';

    protected $fillable = [
        'master_perawatan_id',
        'user_id',
        'tanggal_eksekusi',
        'waktu_eksekusi',
        'jam_ceklis',
        'nama_karyawan',
        'kode_jam_kerja',
        'status',
        'status_validity',
        'catatan',
        'foto_bukti',
        'periode_key',
        'points_earned',
        'last_reset_at'
    ];

    protected $casts = [
        'tanggal_eksekusi' => 'date',
    ];

    public function masterPerawatan()
    {
        return $this->belongsTo(MasterPerawatan::class, 'master_perawatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Jam Kerja (Jadwal Piket)
     */
    public function jamKerja()
    {
        return $this->belongsTo(Jamkerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }

    public function scopeByPeriode($query, $periodeKey)
    {
        return $query->where('periode_key', $periodeKey);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_eksekusi', today());
    }
}
