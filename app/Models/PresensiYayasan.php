<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiYayasan extends Model
{
    use HasFactory;
    protected $table = 'presensi_yayasan';
    protected $guarded = [];

    // Relasi dengan YayasanMasar
    public function yayasan()
    {
        return $this->belongsTo(YayasanMasar::class, 'kode_yayasan', 'kode_yayasan');
    }

    // Relasi dengan Jamkerja
    public function jamKerja()
    {
        return $this->belongsTo(Jamkerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
}
