<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasKhidmat extends Model
{
    use HasFactory;

    protected $table = 'petugas_khidmat';

    protected $fillable = [
        'jadwal_khidmat_id',
        'santri_id'
    ];

    // Relasi ke jadwal khidmat
    public function jadwal()
    {
        return $this->belongsTo(JadwalKhidmat::class, 'jadwal_khidmat_id');
    }

    // Relasi ke santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}
