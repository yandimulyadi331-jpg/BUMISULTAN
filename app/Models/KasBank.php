<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasBank extends Model
{
    use HasFactory;

    protected $table = 'kas_bank';
    
    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'akun_keuangan_id',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'cabang',
        'saldo_awal',
        'saldo_berjalan',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
        'saldo_berjalan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the akun keuangan
     */
    public function akunKeuangan()
    {
        return $this->belongsTo(AkunKeuangan::class, 'akun_keuangan_id');
    }

    /**
     * Get all transaksi
     */
    public function transaksi()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'kas_bank_id');
    }

    /**
     * Get all rekonsiliasi
     */
    public function rekonsiliasi()
    {
        return $this->hasMany(RekonsiliasiBank::class, 'kas_bank_id');
    }

    /**
     * Scope untuk kas/bank aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Update saldo
     */
    public function updateSaldo($jumlah, $tipe = 'masuk')
    {
        if ($tipe === 'masuk') {
            $this->saldo_berjalan += $jumlah;
        } else {
            $this->saldo_berjalan -= $jumlah;
        }
        $this->save();
    }

    /**
     * Get saldo formatted
     */
    public function getSaldoFormattedAttribute()
    {
        return 'Rp ' . number_format($this->saldo_berjalan, 0, ',', '.');
    }
}
