<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKhidmat extends Model
{
    use HasFactory;

    protected $table = 'jadwal_khidmat';

    protected $fillable = [
        'nama_kelompok',
        'tanggal_jadwal',
        'status_kebersihan',
        'status_selesai',
        'saldo_awal',
        'saldo_masuk',
        'total_belanja',
        'saldo_akhir',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_jadwal' => 'date',
        'status_selesai' => 'boolean',
        'saldo_awal' => 'decimal:2',
        'saldo_masuk' => 'decimal:2',
        'total_belanja' => 'decimal:2',
        'saldo_akhir' => 'decimal:2'
    ];

    // Relasi ke petugas khidmat
    public function petugas()
    {
        return $this->hasMany(PetugasKhidmat::class);
    }

    // Relasi ke belanja khidmat
    public function belanja()
    {
        return $this->hasMany(BelanjaKhidmat::class);
    }

    // Relasi ke foto belanja
    public function foto()
    {
        return $this->hasMany(FotoBelanjaKhidmat::class);
    }
}
