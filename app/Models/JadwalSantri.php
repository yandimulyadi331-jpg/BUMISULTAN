<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class JadwalSantri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal_santri';

    protected $fillable = [
        'nama_jadwal',
        'deskripsi',
        'tipe_jadwal',
        'hari',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'tempat',
        'pembimbing',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relasi dengan absensi santri
    public function absensiSantri()
    {
        return $this->hasMany(AbsensiSantri::class, 'jadwal_santri_id');
    }

    // Method untuk cek apakah jadwal sedang berlangsung
    public function isJadwalBerlangsung()
    {
        $now = Carbon::now();
        $jamMulai = Carbon::parse($this->jam_mulai);
        $jamSelesai = Carbon::parse($this->jam_selesai);

        // Cek apakah sekarang berada di antara jam mulai dan jam selesai
        if ($this->tipe_jadwal == 'harian') {
            return $now->format('H:i') >= $jamMulai->format('H:i') 
                && $now->format('H:i') <= $jamSelesai->format('H:i');
        } elseif ($this->tipe_jadwal == 'mingguan') {
            $hariSekarang = $now->locale('id')->translatedFormat('l');
            return $hariSekarang == $this->hari 
                && $now->format('H:i') >= $jamMulai->format('H:i') 
                && $now->format('H:i') <= $jamSelesai->format('H:i');
        } elseif ($this->tipe_jadwal == 'bulanan') {
            return $now->format('Y-m-d') == $this->tanggal->format('Y-m-d')
                && $now->format('H:i') >= $jamMulai->format('H:i') 
                && $now->format('H:i') <= $jamSelesai->format('H:i');
        }

        return false;
    }

    // Scope untuk jadwal aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
