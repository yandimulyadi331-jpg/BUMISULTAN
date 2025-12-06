<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keuangan';
    
    protected $fillable = [
        'nomor_transaksi',
        'tanggal_transaksi',
        'jenis_transaksi',
        'kas_bank_id',
        'akun_debit_id',
        'akun_kredit_id',
        'jumlah',
        'kategori',
        'keterangan',
        'bukti_transaksi',
        'referensi',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'rejection_note',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get kas/bank
     */
    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    /**
     * Get akun debit
     */
    public function akunDebit()
    {
        return $this->belongsTo(AkunKeuangan::class, 'akun_debit_id');
    }

    /**
     * Get akun kredit
     */
    public function akunKredit()
    {
        return $this->belongsTo(AkunKeuangan::class, 'akun_kredit_id');
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
     * Get jurnal umum entries
     */
    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'transaksi_keuangan_id');
    }

    /**
     * Get rekonsiliasi detail
     */
    public function rekonsiliasiDetail()
    {
        return $this->hasOne(RekonsiliasiDetail::class, 'transaksi_keuangan_id');
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk jenis transaksi
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_transaksi', $jenis);
    }

    /**
     * Scope untuk transaksi dalam periode
     */
    public function scopeInPeriode($query, $start, $end)
    {
        return $query->whereBetween('tanggal_transaksi', [$start, $end]);
    }

    /**
     * Scope untuk transaksi yang sudah diposting
     */
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    /**
     * Generate nomor transaksi otomatis
     */
    public static function generateNomorTransaksi($jenis = 'masuk')
    {
        $prefix = match($jenis) {
            'masuk' => 'TM',
            'keluar' => 'TK',
            'transfer' => 'TT',
            default => 'TX'
        };
        
        $date = now()->format('Ymd');
        $lastNumber = static::where('nomor_transaksi', 'like', $prefix . '-' . $date . '-%')
            ->orderBy('nomor_transaksi', 'desc')
            ->first();
        
        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber->nomor_transaksi, -4);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }
        
        return $prefix . '-' . $date . '-' . str_pad($newSeq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Approve transaksi
     */
    public function approve($userId)
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->save();

        // Create jurnal entries
        $this->createJurnalEntries();
    }

    /**
     * Reject transaksi
     */
    public function reject($userId, $note)
    {
        $this->status = 'rejected';
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->rejection_note = $note;
        $this->save();
    }

    /**
     * Post transaksi to ledger
     */
    public function post()
    {
        if ($this->status !== 'approved') {
            return false;
        }

        // Update saldo akun
        $this->akunDebit->updateSaldo($this->jumlah, 'debit');
        $this->akunKredit->updateSaldo($this->jumlah, 'kredit');

        // Update saldo kas/bank
        if ($this->kas_bank_id) {
            if ($this->jenis_transaksi === 'masuk') {
                $this->kasBank->updateSaldo($this->jumlah, 'masuk');
            } else {
                $this->kasBank->updateSaldo($this->jumlah, 'keluar');
            }
        }

        // Update status jurnal
        $this->jurnalUmum()->update(['status' => 'posted']);

        $this->status = 'posted';
        $this->save();

        return true;
    }

    /**
     * Create jurnal entries
     */
    protected function createJurnalEntries()
    {
        $nomorJurnal = $this->generateNomorJurnal();

        // Entry Debit
        JurnalUmum::create([
            'nomor_jurnal' => $nomorJurnal,
            'tanggal' => $this->tanggal_transaksi,
            'transaksi_keuangan_id' => $this->id,
            'akun_keuangan_id' => $this->akun_debit_id,
            'debit' => $this->jumlah,
            'kredit' => 0,
            'keterangan' => $this->keterangan,
            'status' => 'draft',
            'created_by' => $this->created_by,
        ]);

        // Entry Kredit
        JurnalUmum::create([
            'nomor_jurnal' => $nomorJurnal,
            'tanggal' => $this->tanggal_transaksi,
            'transaksi_keuangan_id' => $this->id,
            'akun_keuangan_id' => $this->akun_kredit_id,
            'debit' => 0,
            'kredit' => $this->jumlah,
            'keterangan' => $this->keterangan,
            'status' => 'draft',
            'created_by' => $this->created_by,
        ]);
    }

    /**
     * Generate nomor jurnal
     */
    protected function generateNomorJurnal()
    {
        $date = now()->format('Ymd');
        $lastNumber = JurnalUmum::where('nomor_jurnal', 'like', 'JU-' . $date . '-%')
            ->orderBy('nomor_jurnal', 'desc')
            ->first();
        
        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber->nomor_jurnal, -4);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }
        
        return 'JU-' . $date . '-' . str_pad($newSeq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get jumlah formatted
     */
    public function getJumlahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'posted' => 'success',
            default => 'secondary'
        };
    }
}
