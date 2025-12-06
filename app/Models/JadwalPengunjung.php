<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPengunjung extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pengunjung';

    protected $fillable = [
        'kode_jadwal',
        'nama_lengkap',
        'instansi',
        'no_telepon',
        'email',
        'keperluan',
        'bertemu_dengan',
        'tanggal_kunjungan',
        'waktu_kunjungan',
        'status',
        'kode_cabang',
        'catatan'
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    // Relasi
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function pengunjung()
    {
        return $this->hasMany(Pengunjung::class, 'jadwal_pengunjung_id');
    }

    // Generate kode jadwal otomatis
    public static function generateKodeJadwal()
    {
        $lastJadwal = self::orderBy('id', 'desc')->first();
        if (!$lastJadwal) {
            return 'JDW-' . date('Ymd') . '-0001';
        }

        $lastNumber = (int) substr($lastJadwal->kode_jadwal, -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return 'JDW-' . date('Ymd') . '-' . $newNumber;
    }
}
