<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';
    
    protected $fillable = [
        'kode_anggaran',
        'nama_anggaran',
        'tahun',
        'periode',
        'bulan',
        'triwulan',
        'akun_keuangan_id',
        'departemen_id',
        'jumlah_anggaran',
        'realisasi',
        'sisa_anggaran',
        'keterangan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'triwulan' => 'integer',
        'jumlah_anggaran' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'sisa_anggaran' => 'decimal:2',
    ];

    /**
     * Get akun keuangan
     */
    public function akunKeuangan()
    {
        return $this->belongsTo(AkunKeuangan::class, 'akun_keuangan_id');
    }

    /**
     * Get departemen
     */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    /**
     * Get user who created
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk tahun tertentu
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk periode tertentu
     */
    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk anggaran aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Update realisasi anggaran
     */
    public function updateRealisasi($jumlah)
    {
        $this->realisasi += $jumlah;
        $this->sisa_anggaran = $this->jumlah_anggaran - $this->realisasi;
        $this->save();
    }

    /**
     * Get persentase realisasi
     */
    public function getPersentaseRealisasiAttribute()
    {
        if ($this->jumlah_anggaran == 0) {
            return 0;
        }
        return ($this->realisasi / $this->jumlah_anggaran) * 100;
    }

    /**
     * Get status warna berdasarkan realisasi
     */
    public function getStatusColorAttribute()
    {
        $persentase = $this->persentase_realisasi;
        
        if ($persentase < 50) {
            return 'success'; // Hijau - masih aman
        } elseif ($persentase < 80) {
            return 'warning'; // Kuning - hati-hati
        } elseif ($persentase < 100) {
            return 'danger'; // Merah - hampir habis
        } else {
            return 'dark'; // Hitam - over budget
        }
    }

    /**
     * Check if over budget
     */
    public function isOverBudget()
    {
        return $this->realisasi > $this->jumlah_anggaran;
    }

    /**
     * Get nama bulan
     */
    public function getNamaBulanAttribute()
    {
        if (!$this->bulan) {
            return '-';
        }
        
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $bulanNames[$this->bulan] ?? '-';
    }

    /**
     * Generate kode anggaran otomatis
     */
    public static function generateKodeAnggaran($tahun, $periode)
    {
        $prefix = 'ANG';
        $lastNumber = static::where('kode_anggaran', 'like', $prefix . '-' . $tahun . '-%')
            ->orderBy('kode_anggaran', 'desc')
            ->first();
        
        if ($lastNumber) {
            $lastSeq = (int) substr($lastNumber->kode_anggaran, -4);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }
        
        return $prefix . '-' . $tahun . '-' . str_pad($newSeq, 4, '0', STR_PAD_LEFT);
    }
}
