<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiBank extends Model
{
    use HasFactory;

    protected $table = 'rekonsiliasi_bank';
    
    protected $fillable = [
        'nomor_rekonsiliasi',
        'kas_bank_id',
        'periode_mulai',
        'periode_selesai',
        'saldo_buku_awal',
        'saldo_bank_awal',
        'saldo_buku_akhir',
        'saldo_bank_akhir',
        'selisih',
        'catatan_selisih',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'saldo_buku_awal' => 'decimal:2',
        'saldo_bank_awal' => 'decimal:2',
        'saldo_buku_akhir' => 'decimal:2',
        'saldo_bank_akhir' => 'decimal:2',
        'selisih' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get kas bank
     */
    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    /**
     * Get user who created
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get user who approved
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get rekonsiliasi details
     */
    public function details()
    {
        return $this->hasMany(RekonsiliasiDetail::class, 'rekonsiliasi_bank_id');
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Calculate selisih
     */
    public function calculateSelisih()
    {
        $this->selisih = $this->saldo_bank_akhir - $this->saldo_buku_akhir;
        $this->save();
    }

    /**
     * Is balanced
     */
    public function isBalanced()
    {
        return abs($this->selisih) < 0.01; // Toleransi 1 sen
    }

    /**
     * Generate nomor rekonsiliasi
     */
    public static function generateNomorRekonsiliasi()
    {
        $date = now()->format('Ym');
        $lastNumber = static::where('nomor_rekonsiliasi', 'like', 'REK-' . $date . '-%')
            ->orderBy('nomor_rekonsiliasi', 'desc')
            ->first();
        
        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber->nomor_rekonsiliasi, -4);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }
        
        return 'REK-' . $date . '-' . str_pad($newSeq, 4, '0', STR_PAD_LEFT);
    }
}
