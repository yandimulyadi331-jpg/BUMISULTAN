<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganPinjamanPayrollDetail extends Model
{
    use HasFactory;

    protected $table = 'potongan_pinjaman_payroll_detail';

    protected $fillable = [
        'tukang_id',
        'pinjaman_tukang_id',
        'tahun',
        'minggu',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_potong',
        'nominal_cicilan',
        'alasan_tidak_potong',
        'toggle_by',
        'toggle_at',
        'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'toggle_at' => 'datetime',
        'nominal_cicilan' => 'decimal:2',
    ];

    /**
     * Relasi ke model Tukang
     */
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }

    /**
     * Relasi ke model PinjamanTukang
     */
    public function pinjaman()
    {
        return $this->belongsTo(PinjamanTukang::class, 'pinjaman_tukang_id');
    }

    /**
     * Scope: Filter berdasarkan tukang, tahun, dan minggu (ISO 8601)
     * 
     * Contoh: $query->forMinggu(123, 2026, 5)
     */
    public function scopeForMinggu($query, $tukang_id, $tahun, $minggu)
    {
        return $query->where('tukang_id', $tukang_id)
                     ->where('tahun', $tahun)
                     ->where('minggu', $minggu);
    }

    /**
     * Scope: Filter potongan yang aktif (dipotong)
     */
    public function scopeDipotong($query)
    {
        return $query->where('status_potong', 'DIPOTONG');
    }

    /**
     * Scope: Filter potongan yang tidak aktif
     */
    public function scopeTidakDipotong($query)
    {
        return $query->where('status_potong', 'TIDAK_DIPOTONG');
    }

    /**
     * Scope: Filter berdasarkan tahun dan minggu
     */
    public function scopeByTahunMinggu($query, $tahun, $minggu)
    {
        return $query->where('tahun', $tahun)
                     ->where('minggu', $minggu);
    }

    /**
     * Method: Get status potongan string dengan badge
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->status_potong === 'DIPOTONG') {
            return '<span class="badge bg-success"><i class="ti ti-check"></i> DIPOTONG</span>';
        } else {
            return '<span class="badge bg-warning text-dark"><i class="ti ti-x"></i> TIDAK DIPOTONG</span>';
        }
    }

    /**
     * Method: Get format tanggal range
     */
    public function getTanggalRangeAttribute()
    {
        $mulai = $this->tanggal_mulai->format('d/m/Y');
        $selesai = $this->tanggal_selesai->format('d/m/Y');
        return "$mulai - $selesai";
    }

    /**
     * Method: Get info minggu (Minggu 5/2026)
     */
    public function getMingguInfoAttribute()
    {
        return "Minggu {$this->minggu}/{$this->tahun}";
    }

    /**
     * Method: Dapatkan nominal yang akan dipotong (0 jika tidak dipotong)
     */
    public function getNominalPotongAttribute()
    {
        return $this->status_potong === 'DIPOTONG' ? $this->nominal_cicilan : 0;
    }

    /**
     * Method: Cek apakah minggu ini sudah recorded
     */
    public static function isMingguRecorded($tukang_id, $tahun, $minggu)
    {
        return self::where('tukang_id', $tukang_id)
                    ->where('tahun', $tahun)
                    ->where('minggu', $minggu)
                    ->exists();
    }

    /**
     * Method: Get atau create record untuk minggu tertentu
     */
    public static function getOrCreateMinggu($tukang_id, $pinjaman_tukang_id, $tahun, $minggu, $nominal_cicilan, $tanggal_mulai, $tanggal_selesai)
    {
        return self::updateOrCreate(
            [
                'tukang_id' => $tukang_id,
                'tahun' => $tahun,
                'minggu' => $minggu,
            ],
            [
                'pinjaman_tukang_id' => $pinjaman_tukang_id,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'nominal_cicilan' => $nominal_cicilan,
                'status_potong' => 'DIPOTONG', // Default dipotong
            ]
        );
    }

    /**
     * Method: Ubah status potongan dan record audit trail
     */
    public function updateStatusPotongan($status, $toggleBy = null, $alasan = null, $catatan = null)
    {
        $this->status_potong = $status;
        $this->toggle_by = $toggleBy ?? auth()->user()?->name ?? 'System';
        $this->toggle_at = now();
        
        if ($status === 'TIDAK_DIPOTONG') {
            $this->alasan_tidak_potong = $alasan;
        } else {
            $this->alasan_tidak_potong = null;
        }
        
        if ($catatan) {
            $this->catatan = $catatan;
        }
        
        $this->save();
        
        return $this;
    }
}
