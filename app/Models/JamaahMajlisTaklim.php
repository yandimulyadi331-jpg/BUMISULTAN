<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JamaahMajlisTaklim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jamaah_majlis_taklim';

    protected $fillable = [
        'nomor_jamaah',
        'nama_jamaah',
        'nik',
        'alamat',
        'tanggal_lahir',
        'tahun_masuk',
        'no_telepon',
        'email',
        'jenis_kelamin',
        'pin_fingerprint',
        'jumlah_kehadiran',
        'status_umroh',
        'tanggal_umroh',
        'foto',
        'status_aktif',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_umroh' => 'date',
        'status_umroh' => 'boolean',
        'jumlah_kehadiran' => 'integer',
    ];

    /**
     * Generate nomor jamaah otomatis
     * Format: JA{URUT}{NIK2DIGIT}{TAHUN2DIGIT}
     * Contoh: JA00133625 (Urut: 0013, NIK 2 digit: 36, Tahun: 25)
     */
    public static function generateNomorJamaah($nik, $tahun_masuk, $id)
    {
        // Ambil nomor urut pendaftaran (4 digit)
        $urut = str_pad(self::count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Ambil 2 digit terakhir NIK
        $nik2digit = substr($nik, -2);
        
        // Ambil 2 digit terakhir tahun masuk
        $tahun2digit = substr($tahun_masuk, -2);
        
        // Format: JA00133625
        return "JA{$urut}{$nik2digit}{$tahun2digit}";
    }

    /**
     * Get warna badge berdasarkan tingkat kehadiran
     * Hijau: >= 25, Kuning: 10-24, Merah: < 10
     */
    public function getBadgeColorAttribute()
    {
        if ($this->jumlah_kehadiran >= 25) {
            return 'success'; // Hijau
        } elseif ($this->jumlah_kehadiran >= 10) {
            return 'warning'; // Kuning
        } else {
            return 'danger'; // Merah
        }
    }

    /**
     * Get warna badge text
     */
    public function getBadgeColorNameAttribute()
    {
        if ($this->jumlah_kehadiran >= 25) {
            return 'Hijau - Kehadiran Tinggi';
        } elseif ($this->jumlah_kehadiran >= 10) {
            return 'Kuning - Kehadiran Sedang';
        } else {
            return 'Merah - Kehadiran Rendah';
        }
    }

    /**
     * Relationship dengan kehadiran
     */
    public function kehadiran()
    {
        return $this->hasMany(KehadiranJamaah::class, 'jamaah_id');
    }

    /**
     * Relationship dengan distribusi hadiah
     */
    public function distribusiHadiah()
    {
        return $this->hasMany(DistribusiHadiah::class, 'jamaah_id');
    }

    /**
     * Relationship dengan pemenang undian umroh
     */
    public function pemenangUndian()
    {
        return $this->hasMany(PemenangUndianUmroh::class, 'jamaah_id');
    }

    /**
     * Scope untuk jamaah aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', 'aktif');
    }

    /**
     * Scope untuk jamaah yang sudah umroh
     */
    public function scopeSudahUmroh($query)
    {
        return $query->where('status_umroh', true);
    }

    /**
     * Scope untuk jamaah berdasarkan tahun masuk
     */
    public function scopeTahunMasuk($query, $tahun)
    {
        return $query->where('tahun_masuk', $tahun);
    }
}
