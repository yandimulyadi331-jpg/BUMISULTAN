<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganTukang extends Model
{
    use HasFactory;
    
    protected $table = "potongan_tukangs";
    
    protected $fillable = [
        'tukang_id',
        'tanggal',
        'jenis_potongan',
        'jumlah',
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
     * Relasi ke tabel KeuanganTukang
     */
    public function keuanganTukang()
    {
        return $this->hasOne(KeuanganTukang::class, 'potongan_tukang_id');
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
     * Scope untuk filter berdasarkan jenis potongan
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_potongan', $jenis);
    }
}
