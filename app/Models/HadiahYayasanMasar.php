<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HadiahYayasanMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hadiah_yayasan_masar';

    protected $fillable = [
        'kode_hadiah',
        'nama_hadiah',
        'jenis_hadiah',
        'ukuran',
        'warna',
        'deskripsi',
        'stok_awal',
        'stok_tersedia',
        'stok_terbagikan',
        'stok_ukuran',
        'nilai_hadiah',
        'tanggal_pengadaan',
        'supplier',
        'foto',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'nilai_hadiah' => 'decimal:2',
        'stok_awal' => 'integer',
        'stok_tersedia' => 'integer',
        'stok_terbagikan' => 'integer',
        'stok_ukuran' => 'array',
    ];

    /**
     * Generate kode hadiah otomatis
     * Format: HD-JENIS-TAHUN-URUT
     */
    public static function generateKodeHadiah($jenis)
    {
        $tahun = date('Y');
        $jenis_prefix = strtoupper(substr($jenis, 0, 3));
        $lastRecord = self::where('kode_hadiah', 'LIKE', "HD-{$jenis_prefix}-{$tahun}%")
            ->orderBy('id', 'DESC')
            ->first();
        
        $urut = $lastRecord ? ((int)substr($lastRecord->kode_hadiah, -4) + 1) : 1;
        return 'HD-' . $jenis_prefix . '-' . $tahun . '-' . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship dengan distribusi hadiah
     */
    public function distribusi()
    {
        return $this->hasMany(DistribusiHadiahYayasanMasar::class, 'hadiah_id');
    }
}
