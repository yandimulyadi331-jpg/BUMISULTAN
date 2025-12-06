<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HadiahMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hadiah_masar';

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
     * Format: HM-JENIS-TAHUN-URUT
     */
    public static function generateKodeHadiah($jenis)
    {
        $tahun = date('Y');
        $jenis_prefix = strtoupper(substr($jenis, 0, 3));
        $urut = str_pad(self::whereYear('created_at', $tahun)->count() + 1, 4, '0', STR_PAD_LEFT);
        
        return "HM-{$jenis_prefix}-{$tahun}-{$urut}";
    }

    /**
     * Update stok setelah distribusi
     */
    public function updateStokSetelahDistribusi($jumlah)
    {
        $this->stok_tersedia -= $jumlah;
        $this->stok_terbagikan += $jumlah;
        
        if ($this->stok_tersedia <= 0) {
            $this->status = 'habis';
        }
        
        $this->save();
    }

    /**
     * Relationship dengan distribusi (legacy)
     */
    public function distribusi()
    {
        return $this->hasMany(DistribusiHadiahMasar::class, 'hadiah_id');
    }

    /**
     * Alias untuk distribusi (untuk compatibility)
     */
    public function distribusiHadiah()
    {
        return $this->hasMany(DistribusiHadiahMasar::class, 'hadiah_id');
    }

    /**
     * Relationship dengan distribusi hadiah yayasan masar
     */
    public function distribusiYayasan()
    {
        return $this->hasMany(DistribusiHadiahYayasanMasar::class, 'hadiah_id');
    }

    /**
     * Scope untuk hadiah tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')->where('stok_tersedia', '>', 0);
    }

    /**
     * Scope untuk hadiah berdasarkan jenis
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_hadiah', $jenis);
    }
}
