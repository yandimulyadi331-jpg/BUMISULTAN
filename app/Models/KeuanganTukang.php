<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class KeuanganTukang extends Model
{
    use HasFactory;
    
    protected $table = "keuangan_tukangs";
    
    protected $fillable = [
        'tukang_id',
        'tanggal',
        'jenis_transaksi',
        'jumlah',
        'tipe',
        'kehadiran_tukang_id',
        'pinjaman_tukang_id',
        'potongan_tukang_id',
        'keterangan',
        'dicatat_oleh'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
    
    /**
     * Relasi ke tabel Tukang
     */
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke tabel KehadiranTukang
     */
    public function kehadiranTukang()
    {
        return $this->belongsTo(KehadiranTukang::class, 'kehadiran_tukang_id');
    }
    
    /**
     * Relasi ke tabel PinjamanTukang
     */
    public function pinjamanTukang()
    {
        return $this->belongsTo(PinjamanTukang::class, 'pinjaman_tukang_id');
    }
    
    /**
     * Relasi ke tabel PotonganTukang
     */
    public function potonganTukang()
    {
        return $this->belongsTo(PotonganTukang::class, 'potongan_tukang_id');
    }
    
    /**
     * Scope untuk filter berdasarkan bulan dan tahun
     */
    public function scopeBulan($query, $tahun, $bulan)
    {
        return $query->whereYear('tanggal', $tahun)
                     ->whereMonth('tanggal', $bulan);
    }
    
    /**
     * Scope untuk filter berdasarkan tipe transaksi
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }
    
    /**
     * Scope untuk filter berdasarkan jenis transaksi
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_transaksi', $jenis);
    }
}
