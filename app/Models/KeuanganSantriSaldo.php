<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganSantriSaldo extends Model
{
    use HasFactory;

    protected $table = 'keuangan_santri_saldo';

    protected $fillable = [
        'santri_id',
        'saldo_awal',
        'total_pemasukan',
        'total_pengeluaran',
        'saldo_akhir',
        'last_transaction_date'
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
        'total_pemasukan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'last_transaction_date' => 'date',
    ];

    /**
     * Relasi ke santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    /**
     * Update saldo setelah transaksi
     */
    public function updateSaldo($transaction)
    {
        if ($transaction->jenis === 'pemasukan') {
            $this->total_pemasukan += $transaction->jumlah;
            $this->saldo_akhir += $transaction->jumlah;
        } else {
            $this->total_pengeluaran += $transaction->jumlah;
            $this->saldo_akhir -= $transaction->jumlah;
        }

        $this->last_transaction_date = $transaction->tanggal_transaksi;
        $this->save();
    }

    /**
     * Get atau create saldo untuk santri
     */
    public static function getOrCreateSaldo($santriId, $saldoAwal = 0)
    {
        return self::firstOrCreate(
            ['santri_id' => $santriId],
            [
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAwal
            ]
        );
    }
}
