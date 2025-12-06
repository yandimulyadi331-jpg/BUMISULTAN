<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';
    
    protected $fillable = [
        'nomor_jurnal',
        'tanggal',
        'transaksi_keuangan_id',
        'akun_keuangan_id',
        'debit',
        'kredit',
        'keterangan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'debit' => 'decimal:2',
        'kredit' => 'decimal:2',
    ];

    /**
     * Get transaksi keuangan
     */
    public function transaksiKeuangan()
    {
        return $this->belongsTo(TransaksiKeuangan::class, 'transaksi_keuangan_id');
    }

    /**
     * Get akun keuangan
     */
    public function akunKeuangan()
    {
        return $this->belongsTo(AkunKeuangan::class, 'akun_keuangan_id');
    }

    /**
     * Get user who created
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk periode tertentu
     */
    public function scopeInPeriode($query, $start, $end)
    {
        return $query->whereBetween('tanggal', [$start, $end]);
    }

    /**
     * Scope untuk jurnal yang sudah diposting
     */
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    /**
     * Scope untuk akun tertentu
     */
    public function scopeByAkun($query, $akunId)
    {
        return $query->where('akun_keuangan_id', $akunId);
    }
}
