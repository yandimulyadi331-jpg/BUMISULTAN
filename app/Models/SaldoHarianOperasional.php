<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoHarianOperasional extends Model
{
    use HasFactory;

    protected $table = 'saldo_harian_operasional';

    protected $fillable = [
        'tanggal', 'pengajuan_id', 'saldo_awal', 'dana_masuk',
        'total_realisasi', 'saldo_akhir', 'status', 'catatan',
        'ditutup_oleh', 'tanggal_tutup',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_tutup' => 'datetime',
        'saldo_awal' => 'decimal:2',
        'dana_masuk' => 'decimal:2',
        'total_realisasi' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanDanaOperasional::class, 'pengajuan_id');
    }

    public function penutup()
    {
        return $this->belongsTo(User::class, 'ditutup_oleh');
    }

    public static function getSaldoKemarin($tanggal = null)
    {
        $tanggalRef = $tanggal ? $tanggal : date('Y-m-d');
        $kemarin = self::where('tanggal', '<', $tanggalRef)
            ->orderBy('tanggal', 'desc')
            ->first();
        return $kemarin ? $kemarin->saldo_akhir : 0;
    }
}
