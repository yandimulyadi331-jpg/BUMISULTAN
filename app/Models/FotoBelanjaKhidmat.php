<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoBelanjaKhidmat extends Model
{
    use HasFactory;

    protected $table = 'foto_belanja_khidmat';

    protected $fillable = [
        'jadwal_khidmat_id',
        'nama_file',
        'path_file',
        'keterangan'
    ];

    // Relasi ke jadwal khidmat
    public function jadwal()
    {
        return $this->belongsTo(JadwalKhidmat::class, 'jadwal_khidmat_id');
    }
}
