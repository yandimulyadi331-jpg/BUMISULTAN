<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PotonganPinjamanMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'potongan_pinjaman_master';

    protected $fillable = [
        'kode_potongan',
        'nik',
        'pinjaman_id',
        'jumlah_pinjaman',
        'cicilan_per_bulan',
        'jumlah_bulan',
        'bulan_mulai',
        'tahun_mulai',
        'bulan_selesai',
        'tahun_selesai',
        'tanggal_potongan',
        'jumlah_terbayar',
        'sisa_pinjaman',
        'bulan_terakhir_dipotong',
        'tahun_terakhir_dipotong',
        'cicilan_terbayar',
        'status',
        'tanggal_selesai',
        'keterangan',
        'dibuat_oleh',
        'diupdate_oleh',
    ];

    protected $casts = [
        'jumlah_pinjaman' => 'decimal:2',
        'cicilan_per_bulan' => 'decimal:2',
        'jumlah_terbayar' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Generate kode potongan unik
     */
    public static function generateKode()
    {
        $tahun = date('y'); // 2025 -> 25
        $prefix = 'PPM' . $tahun;
        
        // Ambil kode terakhir di tahun ini (termasuk yang soft deleted untuk menghindari duplicate)
        $lastCode = static::withTrashed()
                          ->where('kode_potongan', 'like', $prefix . '%')
                          ->orderBy('kode_potongan', 'desc')
                          ->first();
        
        if ($lastCode) {
            $lastNumber = intval(substr($lastCode->kode_potongan, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $newNumber; // PPM250001, PPM250002, dst
    }

    /**
     * Calculate periode selesai berdasarkan periode mulai dan jumlah bulan
     */
    public static function calculatePeriodeSelesai($bulanMulai, $tahunMulai, $jumlahBulan)
    {
        $startDate = Carbon::create($tahunMulai, $bulanMulai, 1);
        $endDate = $startDate->copy()->addMonths($jumlahBulan - 1);
        
        return [
            'bulan_selesai' => $endDate->month,
            'tahun_selesai' => $endDate->year,
        ];
    }

    /**
     * Update progress pembayaran
     */
    public function updateProgress()
    {
        $this->cicilan_terbayar = $this->details()->where('status', 'dipotong')->count();
        $this->jumlah_terbayar = $this->details()->where('status', 'dipotong')->sum('jumlah_potongan');
        $this->sisa_pinjaman = $this->jumlah_pinjaman - $this->jumlah_terbayar;
        
        // Cek apakah sudah lunas
        if ($this->cicilan_terbayar >= $this->jumlah_bulan || $this->sisa_pinjaman <= 0) {
            $this->status = 'selesai';
            $this->tanggal_selesai = now();
        }
        
        $this->save();
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        if ($this->jumlah_bulan == 0) return 0;
        return round(($this->cicilan_terbayar / $this->jumlah_bulan) * 100, 2);
    }

    /**
     * Get progress text
     */
    public function getProgressTextAttribute()
    {
        return $this->cicilan_terbayar . '/' . $this->jumlah_bulan;
    }

    /**
     * Check if periode is active for given month/year
     */
    public function isActivePeriode($bulan, $tahun)
    {
        $targetDate = Carbon::create($tahun, $bulan, 1);
        $startDate = Carbon::create($this->tahun_mulai, $this->bulan_mulai, 1);
        $endDate = Carbon::create($this->tahun_selesai, $this->bulan_selesai, 1);
        
        return $targetDate->between($startDate, $endDate);
    }

    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Relasi ke Pinjaman
     */
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    /**
     * Relasi ke Details
     */
    public function details()
    {
        return $this->hasMany(PotonganPinjamanDetail::class, 'master_id');
    }

    /**
     * Relasi ke User pembuat
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke User pengupdate
     */
    public function pengupdate()
    {
        return $this->belongsTo(User::class, 'diupdate_oleh');
    }

    /**
     * Scope untuk filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter by NIK
     */
    public function scopeByNik($query, $nik)
    {
        return $query->where('nik', $nik);
    }

    /**
     * Scope untuk filter active periode
     */
    public function scopeActivePeriode($query, $bulan, $tahun)
    {
        $targetDate = Carbon::create($tahun, $bulan, 1);
        
        return $query->where(function($q) use ($targetDate) {
            $q->whereRaw("
                DATE(CONCAT(tahun_mulai, '-', LPAD(bulan_mulai, 2, '0'), '-01')) <= ?
                AND DATE(CONCAT(tahun_selesai, '-', LPAD(bulan_selesai, 2, '0'), '-01')) >= ?
            ", [$targetDate, $targetDate]);
        });
    }

    /**
     * Scope untuk search by karyawan
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }
}
