<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;

    protected $table = 'pengunjung';

    protected $fillable = [
        'kode_pengunjung',
        'nama_lengkap',
        'instansi',
        'no_identitas',
        'no_telepon',
        'email',
        'alamat',
        'keperluan',
        'bertemu_dengan',
        'foto',
        'waktu_checkin',
        'waktu_checkout',
        'status',
        'kode_cabang',
        'jadwal_pengunjung_id',
        'catatan'
    ];

    protected $casts = [
        'waktu_checkin' => 'datetime',
        'waktu_checkout' => 'datetime',
    ];

    // Relasi
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function jadwalPengunjung()
    {
        return $this->belongsTo(JadwalPengunjung::class, 'jadwal_pengunjung_id');
    }

    // Generate kode pengunjung otomatis (format: V001, V002, dst)
    public static function generateKodePengunjung()
    {
        $lastPengunjung = self::orderBy('id', 'desc')->first();
        if (!$lastPengunjung) {
            return 'V001';
        }

        $lastNumber = (int) substr($lastPengunjung->kode_pengunjung, 1);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        
        return 'V' . $newNumber;
    }
}
