<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanDanaOperasional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengajuan_dana_operasional';

    protected $fillable = [
        'nomor_pengajuan', 'tanggal_pengajuan', 'user_id', 'saldo_sebelumnya',
        'rincian_kebutuhan', 'total_pengajuan', 'status', 'nominal_cair',
        'tanggal_cair', 'dicairkan_oleh', 'keterangan', 'catatan_pencairan',
        'file_pengajuan', 'file_pencairan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_cair' => 'datetime',
        'saldo_sebelumnya' => 'decimal:2',
        'total_pengajuan' => 'decimal:2',
        'nominal_cair' => 'decimal:2',
        'rincian_kebutuhan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pencair()
    {
        return $this->belongsTo(User::class, 'dicairkan_oleh');
    }

    public function realisasi()
    {
        // Urut berdasarkan tanggal dan ID (sesuai urutan input/Excel)
        return $this->hasMany(RealisasiDanaOperasional::class, 'pengajuan_id')
            ->orderBy('tanggal_realisasi', 'asc')
            ->orderBy('id', 'asc');
    }

    public function saldoHarian()
    {
        return $this->hasOne(SaldoHarianOperasional::class, 'pengajuan_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->nomor_pengajuan)) {
                $model->nomor_pengajuan = self::generateNomorPengajuan();
            }
        });
    }

    public static function generateNomorPengajuan()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $lastPengajuan = self::whereYear('tanggal_pengajuan', $tahun)
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->orderBy('id', 'desc')->first();
        $urutan = $lastPengajuan ? (int)substr($lastPengajuan->nomor_pengajuan, -3) + 1 : 1;
        return sprintf('PGJ/%s/%s/%03d', $tahun, $bulan, $urutan);
    }

    public function getTotalRealisasiAttribute()
    {
        // Hitung total pengeluaran (tipe_transaksi = 'keluar')
        return $this->realisasi()->where('tipe_transaksi', 'keluar')->sum('nominal');
    }
    
    public function getTotalDanaMasukRealisasiAttribute()
    {
        // Hitung total pemasukan dari realisasi (tipe_transaksi = 'masuk')
        return $this->realisasi()->where('tipe_transaksi', 'masuk')->sum('nominal');
    }

    public function getSisaDanaAttribute()
    {
        return ($this->nominal_cair ?? 0) - ($this->total_realisasi ?? 0);
    }

    public function cairkan($nominal, $userId, $catatan = null)
    {
        $this->update([
            'status' => 'dicairkan',
            'nominal_cair' => $nominal,
            'tanggal_cair' => now(),
            'dicairkan_oleh' => $userId,
            'catatan_pencairan' => $catatan,
        ]);
        $this->updateSaldoHarian();
    }

    public function updateSaldoHarian()
    {
        $saldo = SaldoHarianOperasional::firstOrCreate(
            ['tanggal' => $this->tanggal_pengajuan],
            ['pengajuan_id' => $this->id, 'saldo_awal' => $this->saldo_sebelumnya]
        );
        
        // Hitung dana masuk: pencairan + dana masuk dari realisasi
        $totalDanaMasuk = ($this->nominal_cair ?? 0) + ($this->total_dana_masuk_realisasi ?? 0);
        
        // Hitung dana keluar: hanya realisasi dengan tipe 'keluar'
        $totalDanaKeluar = $this->total_realisasi ?? 0;
        
        $saldo->update([
            'dana_masuk' => $totalDanaMasuk,
            'total_realisasi' => $totalDanaKeluar,
            'saldo_akhir' => $saldo->saldo_awal + $totalDanaMasuk - $totalDanaKeluar,
        ]);
    }
}
