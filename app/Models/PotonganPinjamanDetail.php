<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganPinjamanDetail extends Model
{
    use HasFactory;

    protected $table = 'potongan_pinjaman_detail';

    protected $fillable = [
        'master_id',
        'bulan',
        'tahun',
        'tanggal_jatuh_tempo',
        'jumlah_potongan',
        'cicilan_ke',
        'status',
        'tanggal_dipotong',
        'diproses_oleh',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_potongan' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_dipotong' => 'date',
    ];

    /**
     * Relasi ke Master
     */
    public function master()
    {
        return $this->belongsTo(PotonganPinjamanMaster::class, 'master_id');
    }

    /**
     * Relasi ke Karyawan (through master)
     */
    public function karyawan()
    {
        return $this->hasOneThrough(
            Karyawan::class,
            PotonganPinjamanMaster::class,
            'id',          // Foreign key on master table
            'nik',         // Foreign key on karyawan table
            'master_id',   // Local key on detail table
            'nik'          // Local key on master table
        );
    }

    /**
     * Relasi ke User pemroses
     */
    public function prosesor()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    /**
     * Scope untuk filter by periode
     */
    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk dipotong
     */
    public function scopeDipotong($query)
    {
        return $query->where('status', 'dipotong');
    }

    /**
     * Mark as dipotong
     */
    public function markAsDipotong()
    {
        $this->status = 'dipotong';
        $this->tanggal_dipotong = now();
        $this->diproses_oleh = auth()->id();
        $this->save();

        // Update master progress
        $this->master->updateProgress();
    }

    /**
     * Mark as batal
     */
    public function markAsBatal($keterangan = null)
    {
        $this->status = 'batal';
        if ($keterangan) {
            $this->keterangan = $keterangan;
        }
        $this->save();

        // Update master progress
        $this->master->updateProgress();
    }
}
