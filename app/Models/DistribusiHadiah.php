<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistribusiHadiah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'distribusi_hadiah';

    protected $fillable = [
        'nomor_distribusi',
        'jamaah_id',
        'hadiah_id',
        'tanggal_distribusi',
        'jumlah',
        'ukuran',
        'ukuran_diterima',
        'warna_diterima',
        'penerima',
        'foto_bukti',
        'tanda_tangan',
        'status_distribusi',
        'keterangan',
        'petugas_distribusi'
    ];

    protected $casts = [
        'tanggal_distribusi' => 'date',
        'jumlah' => 'integer',
    ];

    /**
     * Generate nomor distribusi otomatis
     * Format: DH-TAHUN-BULAN-URUT
     */
    public static function generateNomorDistribusi()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $urut = str_pad(
            self::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->count() + 1, 
            4, '0', STR_PAD_LEFT
        );
        
        return "DH-{$tahun}-{$bulan}-{$urut}";
    }

    /**
     * Relationship dengan jamaah (from yayasan_masar)
     */
    public function jamaah()
    {
        return $this->belongsTo(YayasanMasar::class, 'jamaah_id');
    }

    /**
     * Relationship dengan hadiah
     */
    public function hadiah()
    {
        return $this->belongsTo(HadiahMajlisTaklim::class, 'hadiah_id');
    }

    /**
     * Scope untuk distribusi hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_distribusi', today());
    }

    /**
     * Scope untuk distribusi bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_distribusi', now()->month)
                     ->whereYear('tanggal_distribusi', now()->year);
    }

    /**
     * Cek apakah jamaah sudah menerima hadiah tertentu
     */
    public static function sudahMenerima($jamaah_id, $hadiah_id)
    {
        return self::where('jamaah_id', $jamaah_id)
                   ->where('hadiah_id', $hadiah_id)
                   ->where('status_distribusi', 'diterima')
                   ->exists();
    }
}
