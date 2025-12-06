<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganPinjamanPayroll extends Model
{
    use HasFactory;

    protected $table = 'potongan_pinjaman_payroll';

    protected $fillable = [
        'kode_potongan',
        'bulan',
        'tahun',
        'nik',
        'pinjaman_id',
        'cicilan_id',
        'cicilan_ke',
        'jumlah_potongan',
        'tanggal_jatuh_tempo',
        'status',
        'tanggal_dipotong',
        'diproses_oleh',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_dipotong' => 'date',
        'jumlah_potongan' => 'decimal:2',
    ];

    /**
     * Generate kode potongan unik
     */
    public static function generateKode($bulan, $tahun)
    {
        $bulanPadded = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $tahunShort = substr($tahun, 2, 2); // 2025 -> 25
        
        // Hitung jumlah record yang sudah ada untuk periode ini
        $count = static::where('bulan', $bulan)
                      ->where('tahun', $tahun)
                      ->count();
        
        $urutan = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        // Format: PPP + MM + YY + NNN (contoh: PPP112501, PPP112502)
        return 'PPP' . $bulanPadded . $tahunShort . $urutan;
    }

    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Relasi ke Pinjaman
     */
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    /**
     * Relasi ke Cicilan
     */
    public function cicilan()
    {
        return $this->belongsTo(PinjamanCicilan::class, 'cicilan_id');
    }

    /**
     * Relasi ke User yang memproses
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
     * Scope untuk status pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk status dipotong
     */
    public function scopeDipotong($query)
    {
        return $query->where('status', 'dipotong');
    }
}
