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
        'denda',
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
        'denda' => 'decimal:2',
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
     * Hitung denda keterlambatan
     * Asumsi: 0.1% per hari dari cicilan
     */
    public function hitungDenda()
    {
        if ($this->status == 'lunas') {
            return 0;
        }

        $hariTerlambat = max(0, now()->diffInDays($this->tanggal_jatuh_tempo, false) * -1);
        
        if ($hariTerlambat > 0) {
            $this->hari_terlambat = $hariTerlambat;
            // Denda 0.1% per hari
            $this->denda = round($this->sisa_cicilan * 0.001 * $hariTerlambat, 2);
            $this->status = 'terlambat';
            $this->save();
        }

        return $this->denda;
    }

    /**
     * Proses pembayaran cicilan
     */
    public function prosesPembayaran($jumlahBayar, $metodePembayaran, $noReferensi = null, $buktiBayar = null, $keterangan = null)
    {
        // Hitung denda jika terlambat
        $this->hitungDenda();

        $totalTagihan = $this->sisa_cicilan + $this->denda;
        
        // Update pembayaran
        $this->jumlah_dibayar += $jumlahBayar;
        $this->tanggal_bayar = now();
        $this->metode_pembayaran = $metodePembayaran;
        $this->no_referensi = $noReferensi;
        $this->bukti_pembayaran = $buktiBayar;
        $this->dibayar_oleh = auth()->id();
        $this->keterangan = $keterangan;

        // Update sisa cicilan
        if ($this->jumlah_dibayar >= $totalTagihan) {
            $this->sisa_cicilan = 0;
            $this->status = 'lunas';
            $kembalian = $this->jumlah_dibayar - $totalTagihan;
        } else {
            $this->sisa_cicilan = $this->jumlah_cicilan - $this->jumlah_dibayar;
            $this->status = 'sebagian';
            $kembalian = 0;
        }

        $this->save();

        // Update total pembayaran di pinjaman induk
        $pinjaman = $this->pinjaman;
        $pinjaman->total_terbayar += $jumlahBayar;
        $pinjaman->sisa_pinjaman = $pinjaman->total_pinjaman - $pinjaman->total_terbayar;
        $pinjaman->denda_keterlambatan += $this->denda;
        
        // Cek apakah sudah lunas semua
        if ($pinjaman->sisa_pinjaman <= 0) {
            $pinjaman->status = 'lunas';
            $pinjaman->tanggal_lunas = now();
        } else {
            $pinjaman->status = 'berjalan';
        }

        $pinjaman->save();

        // Log history
        $pinjaman->logHistory(
            'bayar_cicilan',
            null,
            null,
            "Pembayaran cicilan ke-{$this->cicilan_ke}: Rp " . number_format($jumlahBayar, 0, ',', '.'),
            [
                'cicilan_ke' => $this->cicilan_ke,
                'jumlah_bayar' => $jumlahBayar,
                'metode' => $metodePembayaran,
                'denda' => $this->denda,
            ]
        );

        return [
            'success' => true,
            'kembalian' => $kembalian,
            'status' => $this->status,
        ];
    }

    /**
     * Get total tagihan termasuk denda
     */
    public function getTotalTagihanAttribute()
    {
        $this->hitungDenda();
        return $this->sisa_cicilan + $this->denda;
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
}
