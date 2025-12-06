<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PembayaranGajiTukang extends Model
{
    use HasFactory;
    
    protected $table = 'pembayaran_gaji_tukangs';
    
    protected $fillable = [
        'tukang_id',
        'periode_mulai',
        'periode_akhir',
        'tanggal_bayar',
        'total_upah_harian',
        'total_upah_lembur',
        'lembur_cash_terbayar',
        'total_kotor',
        'total_potongan',
        'rincian_potongan',
        'total_nett',
        'tanda_tangan_base64',
        'tanda_tangan_path',
        'ip_address',
        'device_info',
        'status',
        'dibayar_oleh',
        'catatan'
    ];
    
    protected $casts = [
        'periode_mulai' => 'date',
        'periode_akhir' => 'date',
        'tanggal_bayar' => 'datetime',
        'total_upah_harian' => 'decimal:2',
        'total_upah_lembur' => 'decimal:2',
        'lembur_cash_terbayar' => 'decimal:2',
        'total_kotor' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'total_nett' => 'decimal:2',
        'rincian_potongan' => 'array',
    ];
    
    /**
     * Relasi ke tabel Tukang
     */
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }
    
    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopePeriode(Builder $query, $mulai, $akhir)
    {
        return $query->where('periode_mulai', $mulai)
                    ->where('periode_akhir', $akhir);
    }
    
    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope untuk pembayaran yang sudah lunas
     */
    public function scopeLunas(Builder $query)
    {
        return $query->where('status', 'lunas');
    }
    
    /**
     * Scope untuk pembayaran yang belum lunas
     */
    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }
}
