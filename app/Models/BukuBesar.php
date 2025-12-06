<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBesar extends Model
{
    use HasFactory;

    protected $table = 'buku_besar';

    protected $fillable = [
        'chart_of_account_id',
        'jurnal_umum_id',
        'jurnal_detail_id',
        'tanggal',
        'nomor_jurnal',
        'keterangan',
        'debit',
        'kredit',
        'saldo',
        'periode',
        'tahun_buku',
        'bulan_buku',
        'is_closing'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'debit' => 'decimal:2',
        'kredit' => 'decimal:2',
        'saldo' => 'decimal:2',
        'is_closing' => 'boolean'
    ];

    // Relasi ke chart of account
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    // Relasi ke jurnal umum
    public function jurnalUmum()
    {
        return $this->belongsTo(JurnalUmum::class);
    }

    // Relasi ke jurnal detail
    public function jurnalDetail()
    {
        return $this->belongsTo(JurnalDetail::class);
    }

    // Scope berdasarkan periode
    public function scopePeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    // Scope berdasarkan tahun
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_buku', $tahun);
    }

    // Scope berdasarkan akun
    public function scopeAccount($query, $accountId)
    {
        return $query->where('chart_of_account_id', $accountId);
    }

    // Hitung running balance
    public static function calculateRunningBalance($accountId, $tanggal)
    {
        $entries = self::where('chart_of_account_id', $accountId)
            ->where('tanggal', '<=', $tanggal)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $saldo = 0;
        foreach ($entries as $entry) {
            $saldo += ($entry->debit - $entry->kredit);
            $entry->update(['saldo' => $saldo]);
        }

        return $saldo;
    }
}
