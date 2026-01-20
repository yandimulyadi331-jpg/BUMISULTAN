<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanCicilan extends Model
{
    use HasFactory;

    protected $table = 'pinjaman_cicilan';

    protected $fillable = [
        'pinjaman_id',
        'cicilan_ke',
        'tanggal_jatuh_tempo',
        'jumlah_pokok',
        'jumlah_bunga',
        'jumlah_cicilan',
        'status',
        'is_ditunda',
        'tanggal_ditunda',
        'ditunda_oleh',
        'alasan_ditunda',
        'is_hasil_tunda',
        'cicilan_ditunda_id',
        'tanggal_bayar',
        'jumlah_dibayar',
        'sisa_cicilan',
        'hari_terlambat',
        'metode_pembayaran',
        'no_referensi',
        'bukti_pembayaran',
        'dibayar_oleh',
        'keterangan',
        'auto_potong_gaji',
        'kode_penyesuaian_gaji',
        'sudah_dipotong',
        'tanggal_dipotong',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'tanggal_ditunda' => 'date',
        'tanggal_dipotong' => 'date',
        'jumlah_pokok' => 'decimal:2',
        'jumlah_bunga' => 'decimal:2',
        'jumlah_cicilan' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'sisa_cicilan' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel Pinjaman
     */
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    /**
     * Relasi ke User yang membayar
     */
    public function pembayar()
    {
        return $this->belongsTo(User::class, 'dibayar_oleh');
    }

    /**
     * Scope untuk cicilan yang jatuh tempo
     */
    public function scopeJatuhTempo($query)
    {
        return $query->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<=', now());
    }

    /**
     * Scope untuk cicilan yang terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', now());
    }

    /**
     * Scope untuk cicilan belum bayar
     */
    public function scopeBelumBayar($query)
    {
        return $query->where('status', 'belum_bayar');
    }

    /**
     * Proses pembayaran cicilan - SEDERHANA & AKURAT
     * Tanpa early settlement, hanya pembayaran normal ke cicilan terkait
     */
    public function prosesPembayaran($jumlahBayar, $metodePembayaran, $noReferensi = null, $buktiBayar = null, $keterangan = null)
    {
        // ✅ Validasi dasar
        if ($jumlahBayar <= 0) {
            return [
                'success' => false,
                'errors' => ['Jumlah pembayaran harus lebih dari 0'],
            ];
        }

        $cicilanNormal = $this->jumlah_cicilan;
        
        // ✅ PEMBAYARAN NORMAL - SEDERHANA
        $dataSebulum = [
            'jumlah_dibayar' => $this->jumlah_dibayar,
            'sisa_cicilan' => $this->sisa_cicilan,
            'status' => $this->status,
        ];
        
        $this->jumlah_dibayar += $jumlahBayar;
        $this->tanggal_bayar = now();
        $this->metode_pembayaran = $metodePembayaran;
        $this->no_referensi = $noReferensi;
        $this->bukti_pembayaran = $buktiBayar;
        $this->dibayar_oleh = auth()->id();
        $this->keterangan = $keterangan;

        // ✅ Hitung status cicilan berdasar nominal normal
        if ($this->jumlah_dibayar >= $this->jumlah_cicilan) {
            $this->sisa_cicilan = 0;
            $this->status = 'lunas';
            $kembalian = $this->jumlah_dibayar - $this->jumlah_cicilan;
        } else {
            $this->sisa_cicilan = $this->jumlah_cicilan - $this->jumlah_dibayar;
            $this->status = 'sebagian';
            $kembalian = 0;
        }

        $this->save();

        // ✅ UPDATE PINJAMAN INDUK
        $pinjaman = $this->pinjaman;
        $sisaPinjamanLama = $pinjaman->sisa_pinjaman;
        
        $pinjaman->total_terbayar += $jumlahBayar;
        $pinjaman->sisa_pinjaman = max(0, $pinjaman->total_pinjaman - $pinjaman->total_terbayar);
        
        // ✅ FIX: Cek SEMUA cicilan LUNAS, bukan hanya sisa_pinjaman
        $semuaCicilanLunas = $pinjaman->cicilan()->where('status', '!=', 'lunas')->count() === 0;
        $totalCicilanKe = $pinjaman->cicilan()->count();
        
        if ($semuaCicilanLunas && $totalCicilanKe > 0) {
            $pinjaman->status = 'lunas';
            $pinjaman->tanggal_lunas = now();
        } else {
            $pinjaman->status = 'berjalan';
        }

        $pinjaman->save();
        
        // ✅ AUTO-SYNC: Pastikan total_pinjaman selalu = sum(cicilan)
        // Ini menjaga konsistensi data jika ada pembayaran partial atau nominal tidak pas
        $totalCicilanSebenarnya = $pinjaman->cicilan()->sum('jumlah_cicilan');
        if ($pinjaman->total_pinjaman != $totalCicilanSebenarnya) {
            $pinjaman->update(['total_pinjaman' => $totalCicilanSebenarnya]);
        }

        $pinjaman->logHistory(
            'bayar_cicilan',
            null,
            null,
            "Pembayaran cicilan ke-{$this->cicilan_ke}: Rp " . number_format($jumlahBayar, 0, ',', '.'),
            [
                'cicilan_ke' => $this->cicilan_ke,
                'jumlah_bayar' => $jumlahBayar,
                'metode' => $metodePembayaran,
            ]
        );

        // ✅ TRIGGER EVENT REAL-TIME: Dispatch event untuk update laporan otomatis
        event(new \App\Events\PinjamanPaymentUpdated($pinjaman, $this, [
            'sebelum' => array_merge($dataSebulum, [
                'sisa_pinjaman' => $sisaPinjamanLama,
                'total_terbayar' => $pinjaman->total_terbayar - $jumlahBayar,
            ]),
            'sesudah' => [
                'jumlah_dibayar' => $this->jumlah_dibayar,
                'sisa_cicilan' => $this->sisa_cicilan,
                'status_cicilan' => $this->status,
                'sisa_pinjaman' => $pinjaman->sisa_pinjaman,
                'total_terbayar' => $pinjaman->total_terbayar,
                'status_pinjaman' => $pinjaman->status,
            ],
        ]));

        return [
            'success' => true,
            'kembalian' => $kembalian,
            'status' => $this->status,
        ];
    }

    /**
     * Get total tagihan
     */
    public function getTotalTagihanAttribute()
    {
        return $this->sisa_cicilan;
    }

    /**
     * Accessor untuk cek status terlambat otomatis
     */
    public function getIsTerlambatAttribute()
    {
        if ($this->status == 'lunas' || $this->is_ditunda) {
            return false;
        }
        return now()->isAfter($this->tanggal_jatuh_tempo);
    }

    /**
     * Accessor untuk hitung hari terlambat otomatis
     */
    public function getHariTerlambatOtomatisAttribute()
    {
        if ($this->status == 'lunas' || $this->is_ditunda) {
            return 0;
        }
        return max(0, now()->diffInDays($this->tanggal_jatuh_tempo, false) * -1);
    }

    /**
     * Hitung dan update hari terlambat (tanpa denda)
     * Method ini hanya untuk update status keterlambatan, tidak ada perhitungan denda
     */
    public function hitungDenda()
    {
        // Jika sudah lunas atau ditunda, skip
        if ($this->status == 'lunas' || $this->is_ditunda) {
            return;
        }

        // Hitung hari terlambat saja (tanpa denda)
        $hariTerlambat = 0;
        if (now()->isAfter($this->tanggal_jatuh_tempo)) {
            $hariTerlambat = now()->diffInDays($this->tanggal_jatuh_tempo);
        }

        // Update hari terlambat untuk tracking saja
        if ($this->hari_terlambat != $hariTerlambat) {
            $this->hari_terlambat = $hariTerlambat;
            $this->save();
        }
    }

    /**
     * ✅ FITUR EARLY SETTLEMENT (Pelunasan Lebih Awal)
     * 
     * Handle ketika ada pembayaran yang melunasin semua sisa pinjaman sekaligus
     * - Hapus cicilan belum bayar (tidak relevan)
     * - Update status pinjaman = LUNAS
     * - Set tanggal_lunas & tanggal_pelunasan_awal
     * - Catat di history untuk audit
     */
    public static function handleEarlySettlement(Pinjaman $pinjaman)
    {
        // Cek apakah sisa_pinjaman sudah 0 atau negatif (fully paid)
        if ($pinjaman->sisa_pinjaman <= 0) {
            try {
                // ✅ HAPUS cicilan belum bayar (tidak relevan lagi)
                $cicilanBelumBayar = self::where('pinjaman_id', $pinjaman->id)
                    ->where('status', '!=', 'lunas')
                    ->where('status', '!=', 'sebagian')
                    ->count();
                
                if ($cicilanBelumBayar > 0) {
                    self::where('pinjaman_id', $pinjaman->id)
                        ->where('status', 'belum_bayar')
                        ->delete();
                }
                
                // ✅ UPDATE status pinjaman menjadi LUNAS
                $pinjaman->update([
                    'status' => 'lunas',
                    'tanggal_lunas' => now(),
                ]);
                
                // ✅ LOG HISTORY untuk audit trail
                $pinjaman->logHistory(
                    'early_settlement',
                    'berjalan',
                    'lunas',
                    'Pinjaman LUNAS dengan pelunasan lebih awal (pembayaran satu kali untuk semua sisa)'
                );
                
                return true;
            } catch (\Exception $e) {
                \Log::error('Error dalam handleEarlySettlement: ' . $e->getMessage());
                return false;
            }
        }
        
        return false;
    }

    /**
     * ✅ SYNC DATA PINJAMAN: Auto-update saat ada pembayaran
     * Memastikan total_pinjaman, sisa_pinjaman, total_terbayar selalu sinkron
     */
    public function syncPinjamanData()
    {
        $pinjaman = $this->pinjaman;
        
        // Hitung ulang dari cicilan yang sebenarnya ada
        $totalCicilan = $pinjaman->cicilan()->sum('jumlah_cicilan');
        $totalBayar = $pinjaman->cicilan()->sum('jumlah_dibayar');
        $sisaPinjaman = max(0, $totalCicilan - $totalBayar);
        
        // Update jika ada perbedaan
        if ($pinjaman->total_pinjaman != $totalCicilan ||
            $pinjaman->total_terbayar != $totalBayar ||
            $pinjaman->sisa_pinjaman != $sisaPinjaman) {
            
            $pinjaman->update([
                'total_pinjaman' => $totalCicilan,
                'total_terbayar' => $totalBayar,
                'sisa_pinjaman' => $sisaPinjaman,
            ]);
            
            return true;
        }
        
        return false;
    }
}

