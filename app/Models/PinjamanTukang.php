<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanTukang extends Model
{
    use HasFactory;
    
    protected $table = "pinjaman_tukangs";
    
    protected $fillable = [
        'tukang_id',
        'tanggal_pinjaman',
        'jumlah_pinjaman',
        'jumlah_terbayar',
        'sisa_pinjaman',
        'status',
        'cicilan_per_minggu',
        'keterangan',
        'foto_bukti',
        'tanggal_lunas',
        'dicatat_oleh'
    ];
    
    protected $casts = [
        'tanggal_pinjaman' => 'date',
        'tanggal_lunas' => 'date',
        'jumlah_pinjaman' => 'decimal:2',
        'jumlah_terbayar' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'cicilan_per_minggu' => 'decimal:2',
    ];
    
    /**
     * Relasi ke tabel Tukang
     */
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke tabel KeuanganTukang (pembayaran cicilan)
     */
    public function pembayaran()
    {
        return $this->hasMany(KeuanganTukang::class, 'pinjaman_tukang_id');
    }
    
    /**
     * Scope untuk filter pinjaman aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->where('sisa_pinjaman', '>', 0);
    }
    
    /**
     * Scope untuk filter pinjaman lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }
    
    /**
     * Method untuk bayar cicilan
     */
    public function bayarCicilan($jumlah)
    {
        $this->jumlah_terbayar += $jumlah;
        $this->sisa_pinjaman = $this->jumlah_pinjaman - $this->jumlah_terbayar;
        
        if ($this->sisa_pinjaman <= 0) {
            $this->sisa_pinjaman = 0;
            $this->status = 'lunas';
            $this->tanggal_lunas = now();
        }
        
        $this->save();
    }
}
