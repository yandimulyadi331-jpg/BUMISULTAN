<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanTukang extends Model
{
    use HasFactory;
    
    protected $table = "pinjaman_tukangs";
    
    protected $fillable = [
        'tukang_id',
        'tanggal_pinjaman',
        'jumlah_pinjaman',
        'jumlah_terbayar',
        'sisa_pinjaman',
        'status',
        'cicilan_per_minggu',
        'keterangan',
        'foto_bukti',
        'tanggal_lunas',
        'dicatat_oleh'
    ];
    
    protected $casts = [
        'tanggal_pinjaman' => 'date',
        'tanggal_lunas' => 'date',
        'jumlah_pinjaman' => 'decimal:2',
        'jumlah_terbayar' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'cicilan_per_minggu' => 'decimal:2',
    ];
    
    /**
     * Relasi ke tabel Tukang
     */
    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke tabel KeuanganTukang (pembayaran cicilan)
     */
    public function pembayaran()
    {
        return $this->hasMany(KeuanganTukang::class, 'pinjaman_tukang_id');
    }
    
    /**
     * Scope untuk filter pinjaman aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->where('sisa_pinjaman', '>', 0);
    }
    
    /**
     * Scope untuk filter pinjaman lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }
    
    /**
     * Relasi ke riwayat potongan per-minggu
     * (NEW: 29 Januari 2026)
     */
    public function riwayatPotonganMinggu()
    {
        return $this->hasMany(PotonganPinjamanPayrollDetail::class, 'pinjaman_tukang_id');
    }

    /**
     * Method untuk bayar cicilan
     */
    public function bayarCicilan($jumlah)
    {
        $this->jumlah_terbayar += $jumlah;
        $this->sisa_pinjaman = $this->jumlah_pinjaman - $this->jumlah_terbayar;
        
        if ($this->sisa_pinjaman <= 0) {
            $this->sisa_pinjaman = 0;
            $this->status = 'lunas';
            $this->tanggal_lunas = now();
        }
        
        $this->save();
    }

    /**
     * Method: Record riwayat potongan saat toggle di-ubah
     * 
     * Digunakan di Controller saat user mengubah toggle potongan
     * 
     * Contoh: 
     * $pinjaman->recordPotonganHistory(
     *     tahun: 2026,
     *     minggu: 5,
     *     status: 'TIDAK_DIPOTONG',
     *     toggleBy: 'Admin Name',
     *     alasan: 'Tukang sakit'
     * );
     */
    public function recordPotonganHistory($tahun, $minggu, $status, $toggleBy = null, $alasan = null, $catatan = null)
    {
        // Hitung range tanggal untuk minggu tersebut (ISO 8601)
        $dateTime = new \DateTime();
        $dateTime->setISODate($tahun, $minggu, 1); // 1 = Senin
        $tanggal_mulai = $dateTime->format('Y-m-d');

        $dateTime->modify('+6 days'); // +6 hari = Minggu
        $tanggal_selesai = $dateTime->format('Y-m-d');

        // Update atau create record
        return PotonganPinjamanPayrollDetail::updateOrCreate(
            [
                'tukang_id' => $this->tukang_id,
                'pinjaman_tukang_id' => $this->id,
                'tahun' => $tahun,
                'minggu' => $minggu,
            ],
            [
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'status_potong' => $status,
                'nominal_cicilan' => $this->cicilan_per_minggu,
                'alasan_tidak_potong' => $alasan,
                'toggle_by' => $toggleBy ?? auth()->user()?->name ?? 'System',
                'toggle_at' => now(),
                'catatan' => $catatan,
            ]
        );
    }

    /**
     * Method: Dapatkan status potongan untuk minggu tertentu
     */
    public function getStatusPotonganMinggu($tahun, $minggu)
    {
        $record = $this->riwayatPotonganMinggu()
                       ->where('tahun', $tahun)
                       ->where('minggu', $minggu)
                       ->first();

        return $record ? $record->status_potong : 'TIDAK_TERCATAT';
    }

    /**
     * Method: Dapatkan nominal cicilan untuk minggu tertentu
     */
    public function getNominalCicilanMinggu($tahun, $minggu)
    {
        $record = $this->riwayatPotonganMinggu()
                       ->where('tahun', $tahun)
                       ->where('minggu', $minggu)
                       ->first();

        if (!$record) {
            return 0;
        }

        if ($record->status_potong === 'TIDAK_DIPOTONG') {
            return 0;
        }

        return $record->nominal_cicilan ?? 0;
    }

    /**
     * Method: Dapatkan total nominal cicilan yang dipotong dalam range bulan
     */
    public function getTotalCicilanDipotongBulan($tahun, $bulan)
    {
        $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();

        return $this->riwayatPotonganMinggu()
                    ->where('tahun', $tahun)
                    ->where('status_potong', 'DIPOTONG')
                    ->whereBetween('tanggal_mulai', [$startDate, $endDate])
                    ->sum('nominal_cicilan');
    }
}
