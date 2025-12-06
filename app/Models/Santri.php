<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Santri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'santri';

    protected $fillable = [
        // Data Pribadi
        'nis',
        'nama_lengkap',
        'nama_panggilan',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'no_hp',
        'email',
        'foto',
        
        // Data Keluarga
        'nama_ayah',
        'pekerjaan_ayah',
        'no_hp_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_hp_ibu',
        'nama_wali',
        'hubungan_wali',
        'no_hp_wali',
        
        // Data Pendidikan
        'asal_sekolah',
        'tingkat_pendidikan',
        'tahun_masuk',
        'tanggal_masuk',
        'status_santri',
        
        // Data Hafalan
        'jumlah_juz_hafalan',
        'jumlah_halaman_hafalan',
        'target_hafalan',
        'tanggal_mulai_tahfidz',
        'tanggal_khatam_terakhir',
        'catatan_hafalan',
        
        // Data Asrama
        'nama_asrama',
        'nomor_kamar',
        'nama_pembina',
        
        // Status
        'status_aktif',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_mulai_tahfidz' => 'date',
        'tanggal_khatam_terakhir' => 'date',
        'tahun_masuk' => 'integer',
        'jumlah_juz_hafalan' => 'integer',
        'jumlah_halaman_hafalan' => 'integer',
    ];

    // Accessor untuk umur santri
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    // Accessor untuk lama mondok
    public function getLamaMondokAttribute()
    {
        if (!$this->tanggal_masuk) return null;
        
        $diff = $this->tanggal_masuk->diff(now());
        $tahun = $diff->y;
        $bulan = $diff->m;
        
        if ($tahun > 0 && $bulan > 0) {
            return "{$tahun} tahun {$bulan} bulan";
        } elseif ($tahun > 0) {
            return "{$tahun} tahun";
        } else {
            return "{$bulan} bulan";
        }
    }

    // Accessor untuk persentase hafalan (asumsi 30 juz)
    public function getPersentaseHafalanAttribute()
    {
        $totalJuz = 30;
        if ($this->jumlah_juz_hafalan > 0) {
            return round(($this->jumlah_juz_hafalan / $totalJuz) * 100, 2);
        }
        return 0;
    }

    // Scope untuk filter santri aktif
    public function scopeAktif($query)
    {
        return $query->where('status_santri', 'aktif')
                    ->where('status_aktif', 'aktif');
    }

    // Scope untuk filter berdasarkan jenis kelamin
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    // Scope untuk filter berdasarkan tahun masuk
    public function scopeTahunMasuk($query, $tahun)
    {
        return $query->where('tahun_masuk', $tahun);
    }

    /**
     * Relasi untuk integrasi masa depan
     * Uncomment ketika tabel sudah dibuat
     */
    
    // Relasi dengan Absensi Santri
    public function absensiSantri()
    {
        return $this->hasMany(AbsensiSantri::class);
    }

    // Relasi dengan Ijin Santri
    public function ijinSantri()
    {
        return $this->hasMany(IjinSantri::class, 'santri_id');
    }

    // Relasi dengan Keuangan Santri
    public function keuanganSaldo()
    {
        return $this->hasOne(KeuanganSantriSaldo::class, 'santri_id');
    }

    public function keuanganTransaksi()
    {
        return $this->hasMany(KeuanganSantriTransaction::class, 'santri_id');
    }

    // public function pelanggaran()
    // {
    //     return $this->hasMany(PelanggaranSantri::class);
    // }
}
