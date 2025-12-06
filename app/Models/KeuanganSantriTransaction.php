<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeuanganSantriTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'keuangan_santri_transactions';

    protected $fillable = [
        'santri_id',
        'category_id',
        // 'kode_transaksi', // Auto-generated, tidak boleh di-fill manual
        'jenis',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'tanggal_transaksi',
        'deskripsi',
        'catatan',
        'bukti_file',
        'is_verified',
        'verified_by',
        'verified_at',
        'metode_pembayaran',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Boot method untuk auto-generate kode transaksi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->kode_transaksi) {
                $transaction->kode_transaksi = self::generateKodeTransaksi();
            }
        });
    }

    /**
     * Generate kode transaksi unik dengan format TRX-YYYYMMDD-HHMMSS-XXX
     */
    public static function generateKodeTransaksi()
    {
        $maxAttempts = 20;
        
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Format: TRX-20251108-143530-001
            $date = now()->format('Ymd');
            $time = now()->format('His');
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            $kode = "TRX-{$date}-{$time}-{$random}";
            
            // Check if kode already exists
            if (!self::where('kode_transaksi', $kode)->exists()) {
                return $kode;
            }
            
            // Wait a bit and try again
            usleep(100); // 0.1ms
        }
        
        // Fallback: use microtime for absolute uniqueness
        $microtime = str_replace('.', '', microtime(true));
        return "TRX-{$date}-" . substr($microtime, -9);
    }

    /**
     * Relasi ke santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(KeuanganSantriCategory::class, 'category_id');
    }

    /**
     * Relasi ke user yang membuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang update
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi ke user yang verifikasi
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope untuk transaksi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_transaksi', now());
    }

    /**
     * Scope untuk transaksi minggu ini
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('tanggal_transaksi', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope untuk transaksi bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year);
    }

    /**
     * Scope untuk transaksi tahun ini
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('tanggal_transaksi', now()->year);
    }

    /**
     * Scope untuk pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    /**
     * Scope untuk pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    /**
     * Scope untuk transaksi terverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
