<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanKendaraan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_kendaraans';
    
    protected $fillable = [
        'kode_peminjaman',
        'kendaraan_id',
        'nama_peminjam',
        'email_peminjam',
        'no_hp_peminjam',
        'foto_identitas',
        'driver',
        'tujuan',
        'keperluan',
        'waktu_pinjam',
        'estimasi_kembali',
        'waktu_kembali',
        'status',
        'km_awal',
        'km_akhir',
        'foto_pinjam',
        'ttd_pinjam',
        'foto_kembali',
        'ttd_kembali',
        'keterangan_pinjam',
        'keterangan_kembali',
        'tracking_data',
        'latitude_pinjam',
        'longitude_pinjam',
        'latitude_kembali',
        'longitude_kembali',
        'status_bbm_pinjam',
        'status_bbm_kembali',
        'kondisi_kendaraan'
    ];

    protected $casts = [
        'waktu_pinjam' => 'datetime',
        'estimasi_kembali' => 'datetime',
        'waktu_kembali' => 'datetime',
        'km_awal' => 'decimal:2',
        'km_akhir' => 'decimal:2',
    ];


    /**
     * Relasi ke Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Get tracking data sebagai array
     */
    public function getTrackingDataArrayAttribute()
    {
        return json_decode($this->tracking_data, true) ?? [];
    }

    /**
     * Check apakah terlambat
     */
    public function isTerlambat()
    {
        try {
            if (!empty($this->waktu_kembali) && !empty($this->estimasi_kembali)) {
                return $this->waktu_kembali > $this->estimasi_kembali;
            }
            
            // Jika belum kembali, cek apakah sudah lewat estimasi
            if (empty($this->waktu_kembali) && !empty($this->estimasi_kembali) && now() > $this->estimasi_kembali) {
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Error in isTerlambat: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Calculate durasi peminjaman
     */
    public function getDurasiAttribute()
    {
        try {
            if (!empty($this->waktu_kembali) && !empty($this->waktu_pinjam)) {
                return $this->waktu_pinjam->diff($this->waktu_kembali);
            }
        } catch (\Exception $e) {
            \Log::error('Error in getDurasiAttribute: ' . $e->getMessage());
        }
        
        return null;
    }

    /**
     * Calculate jarak tempuh
     */
    public function getJarakTempuhAttribute()
    {
        if ($this->km_akhir && $this->km_awal) {
            return $this->km_akhir - $this->km_awal;
        }
        return 0;
    }
}
