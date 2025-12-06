<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKeuangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_keuangan';

    protected $fillable = [
        'nomor_laporan',
        'jenis_laporan',
        'nama_laporan',
        'tanggal_mulai',
        'tanggal_selesai',
        'periode',
        'parameter',
        'data_laporan',
        'file_pdf',
        'file_excel',
        'status',
        'user_id',
        'generated_at',
        'is_published',
        'published_at',
        'published_by'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'parameter' => 'array',
        'data_laporan' => 'array',
        'generated_at' => 'datetime',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    // Relasi ke user (pembuat laporan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke user (yang publish laporan)
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
    
    // Scope untuk laporan yang sudah published
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    
    // Scope untuk laporan draft (belum published)
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    // Generate nomor laporan
    public static function generateNomorLaporan($jenis)
    {
        $prefix = match($jenis) {
            'NERACA' => 'LAP-NRC',
            'LABA_RUGI' => 'LAP-LR',
            'PERUBAHAN_MODAL' => 'LAP-PM',
            'ARUS_KAS' => 'LAP-AK',
            'CATATAN_ATAS_LAPORAN' => 'LAP-CAL',
            'NERACA_SALDO' => 'LAP-NS',
            'BUKU_BESAR' => 'LAP-BB',
            'LAPORAN_BUDGET' => 'LAP-BG',
            default => 'LAP'
        };

        $year = date('Y');
        $month = date('m');
        
        $last = self::where('nomor_laporan', 'like', "$prefix/$year/$month/%")
            ->orderBy('nomor_laporan', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->nomor_laporan, -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf("%s/%s/%s/%05d", $prefix, $year, $month, $newNumber);
    }
}
