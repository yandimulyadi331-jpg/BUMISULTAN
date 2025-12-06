<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistribusiHadiahMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'distribusi_hadiah_masar';

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
     * Format: DM-TAHUN-BULAN-URUT
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
        
        return "DM-{$tahun}-{$bulan}-{$urut}";
    }

    /**
     * Relationship dengan jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(YayasanMasar::class, 'jamaah_id', 'kode_yayasan');
    }

    /**
     * Relationship dengan hadiah
     */
    public function hadiah()
    {
        return $this->belongsTo(HadiahMasar::class, 'hadiah_id');
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
