<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAkunKeuangan extends Model
{
    use HasFactory;

    protected $table = 'kategori_akun_keuangan';
    
    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all akun keuangan for this kategori
     */
    public function akunKeuangan()
    {
        return $this->hasMany(AkunKeuangan::class, 'kategori_id');
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
