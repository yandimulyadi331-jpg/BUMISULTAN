<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranTukang extends Model
{
    use HasFactory;
    
    protected $table = "kehadiran_tukangs";
    
    protected $fillable = [
        'tukang_id',
        'tanggal',
        'status',
        'lembur',
        'lembur_dibayar_cash',
        'tanggal_bayar_lembur',
        'jam_kerja',
        'upah_harian',
        'upah_lembur',
        'total_upah',
        'keterangan',
        'dicatat_oleh'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'tanggal_bayar_lembur' => 'date',
        'lembur_dibayar_cash' => 'boolean',
        'jam_kerja' => 'decimal:2',
        'upah_harian' => 'decimal:2',
        'upah_lembur' => 'decimal:2',
        'total_upah' => 'decimal:2',
    ];
    
    /**
     * Relasi ke tabel Tukang
     */
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }
    
    /**
     * Hitung upah otomatis berdasarkan status dan lembur
     */
    public function hitungUpah()
    {
        $tarifHarian = $this->tukang->tarif_harian ?? 0;
        
        // Hitung upah harian berdasarkan status
        switch ($this->status) {
            case 'hadir':
                $this->upah_harian = $tarifHarian;
                $this->jam_kerja = 8.00;
                break;
                
            case 'setengah_hari':
                $this->upah_harian = $tarifHarian * 0.5;
                $this->jam_kerja = 4.00;
                break;
                
            case 'tidak_hadir':
            default:
                $this->upah_harian = 0;
                $this->jam_kerja = 0;
                break;
        }
        
        // Hitung upah lembur berdasarkan jenis lembur
        switch ($this->lembur) {
            case 'full':
                $this->upah_lembur = $tarifHarian; // 100% dari tarif harian
                break;
                
            case 'setengah_hari':
                $this->upah_lembur = $tarifHarian * 0.5; // 50% dari tarif harian
                break;
                
            case 'tidak':
            default:
                $this->upah_lembur = 0;
                break;
        }
        
        // Jika lembur dibayar cash hari ini, set tanggal bayar
        if ($this->lembur_dibayar_cash && $this->lembur != 'tidak') {
            $this->tanggal_bayar_lembur = $this->tanggal;
        } else {
            // Lembur normal dibayar hari Kamis minggu depan
            $this->tanggal_bayar_lembur = null;
        }
        
        // Total upah: 
        // - Jika lembur dibayar cash = hanya upah harian (lembur dibayar terpisah)
        // - Jika lembur bayar kamis = upah harian + upah lembur
        if ($this->lembur_dibayar_cash && $this->lembur != 'tidak') {
            $this->total_upah = $this->upah_harian; // Lembur cash tidak masuk total
        } else {
            $this->total_upah = $this->upah_harian + $this->upah_lembur;
        }
        
        return $this;
    }
    
    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }
    
    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeBulan($query, $tahun, $bulan)
    {
        return $query->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan);
    }
    
    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopePeriode($query, $dari, $sampai)
    {
        return $query->whereBetween('tanggal', [$dari, $sampai]);
    }
}
