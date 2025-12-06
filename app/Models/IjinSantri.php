<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IjinSantri extends Model
{
    use HasFactory;

    protected $table = 'ijin_santri';

    protected $fillable = [
        'santri_id',
        'tanggal_ijin',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'alasan_ijin',
        'nomor_surat',
        'status',
        'ttd_ustadz_at',
        'ttd_ustadz_by',
        'verifikasi_pulang_at',
        'verifikasi_pulang_by',
        'verifikasi_kembali_at',
        'verifikasi_kembali_by',
        'foto_surat_ttd_ortu',
        'catatan',
        'created_by'
    ];

    protected $casts = [
        'tanggal_ijin' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'ttd_ustadz_at' => 'datetime',
        'verifikasi_pulang_at' => 'datetime',
        'verifikasi_kembali_at' => 'datetime',
    ];

    // Relasi
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ttdUstadzBy()
    {
        return $this->belongsTo(User::class, 'ttd_ustadz_by');
    }

    public function verifikasiPulangBy()
    {
        return $this->belongsTo(User::class, 'verifikasi_pulang_by');
    }

    public function verifikasiKembaliBy()
    {
        return $this->belongsTo(User::class, 'verifikasi_kembali_by');
    }

    // Accessor
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '<span class="badge bg-warning">Menunggu TTD Ustadz</span>',
            'ttd_ustadz' => '<span class="badge bg-info">TTD Ustadz - Siap Pulang</span>',
            'dipulangkan' => '<span class="badge bg-primary">Sedang Pulang</span>',
            'kembali' => '<span class="badge bg-success">Sudah Kembali</span>',
        ];

        return $labels[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getStatusTextAttribute()
    {
        $labels = [
            'pending' => 'Menunggu TTD Ustadz',
            'ttd_ustadz' => 'TTD Ustadz - Siap Pulang',
            'dipulangkan' => 'Sedang Pulang',
            'kembali' => 'Sudah Kembali',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    // Helper method untuk generate nomor surat
    public static function generateNomorSurat()
    {
        $year = date('Y');
        $month = date('m');
        $lastIjin = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastIjin ? (int) substr($lastIjin->nomor_surat, 0, 3) + 1 : 1;
        
        return sprintf('%03d/IJIN-SANTRI/%s/%s', $sequence, $month, $year);
    }
}
