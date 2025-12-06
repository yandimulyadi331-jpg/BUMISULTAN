<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistribusiHadiahYayasanMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'distribusi_hadiah_yayasan_masar';

    protected $fillable = [
        'nomor_distribusi',
        'hadiah_id',
        'jamaah_id',
        'jumlah',
        'ukuran',
        'tanggal_distribusi',
        'metode_distribusi',
        'penerima',
        'petugas_distribusi',
        'keterangan',
        'status_distribusi'
    ];

    protected $casts = [
        'tanggal_distribusi' => 'date',
        'jumlah' => 'integer'
    ];

    /**
     * Relationship dengan hadiah
     */
    public function hadiah()
    {
        return $this->belongsTo(HadiahYayasanMasar::class, 'hadiah_id');
    }

    /**
     * Relationship dengan jamaah (YayasanMasar)
     */
    public function jamaah()
    {
        return $this->belongsTo(YayasanMasar::class, 'jamaah_id');
    }

    /**
     * Generate nomor distribusi otomatis
     * Format: DSY-TANGGAL-URUT
     */
    public static function generateNomorDistribusi()
    {
        $tanggal = date('dmy');
        $lastDistribusi = self::where('nomor_distribusi', 'LIKE', "DSY-{$tanggal}%")
            ->orderBy('id', 'DESC')
            ->first();
        
        $sequence = $lastDistribusi ? ((int)substr($lastDistribusi->nomor_distribusi, -4) + 1) : 1;
        return 'DSY-' . $tanggal . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
