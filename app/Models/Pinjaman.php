<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pinjaman';

    protected $fillable = [
        'nomor_pinjaman',
        'kategori_peminjam',
        'karyawan_id',
        'nama_peminjam',
        'nama_peminjam_lengkap',
        'nik_peminjam',
        'alamat_peminjam',
        'no_telp_peminjam',
        'email_peminjam',
        'pekerjaan_peminjam',
        'tanggal_pengajuan',
        'jumlah_pengajuan',
        'jumlah_disetujui',
        'tujuan_pinjaman',
        'tenor_bulan',
        'tenor',
        'tanggal_jatuh_tempo_setiap_bulan', // NEW: Tanggal jatuh tempo setiap bulan (1-31)
        'bunga_persen',
        'tipe_bunga',
        'total_pokok',
        'total_bunga',
        'total_pinjaman',
        'cicilan_per_bulan',
        'status',
        'diajukan_oleh',
        'direview_oleh',
        'tanggal_review',
        'catatan_review',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'catatan_persetujuan',
        'tanggal_pencairan',
        'dicairkan_oleh',
        'metode_pencairan',
        'no_rekening_tujuan',
        'nama_bank',
        'bukti_pencairan',
        'total_terbayar',
        'sisa_pinjaman',
        'persentase_pembayaran',
        'tanggal_jatuh_tempo_pertama',
        'tanggal_jatuh_tempo_terakhir',
        'tanggal_lunas',
        'denda_keterlambatan',
        'hari_telat',
        'dokumen_ktp',
        'dokumen_slip_gaji',
        'dokumen_pendukung_lain',
        // Jaminan
        'jenis_jaminan',
        'nomor_jaminan',
        'deskripsi_jaminan',
        'nilai_jaminan',
        'atas_nama_jaminan',
        'kondisi_jaminan',
        'keterangan_jaminan',
        // Penjamin
        'nama_penjamin',
        'hubungan_penjamin',
        'no_telp_penjamin',
        'alamat_penjamin',
        'keterangan',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_review' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'tanggal_pencairan' => 'date',
        'tanggal_jatuh_tempo_pertama' => 'date',
        'tanggal_jatuh_tempo_terakhir' => 'date',
        'tanggal_lunas' => 'date',
        'jumlah_pengajuan' => 'decimal:2',
        'jumlah_disetujui' => 'decimal:2',
        'bunga_persen' => 'decimal:2',
        'total_pokok' => 'decimal:2',
        'total_bunga' => 'decimal:2',
        'total_pinjaman' => 'decimal:2',
        'cicilan_per_bulan' => 'decimal:2',
        'total_terbayar' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'persentase_pembayaran' => 'decimal:2',
        'denda_keterlambatan' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel Karyawan (untuk crew)
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'nik');
    }

    /**
     * Relasi ke User yang mengajukan
     */
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    /**
     * Relasi ke User yang mereview
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'direview_oleh');
    }

    /**
     * Relasi ke User yang menyetujui
     */
    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi ke User yang mencairkan
     */
    public function pencair()
    {
        return $this->belongsTo(User::class, 'dicairkan_oleh');
    }

    /**
     * Relasi ke tabel PinjamanCicilan
     */
    public function cicilan()
    {
        return $this->hasMany(PinjamanCicilan::class, 'pinjaman_id');
    }

    /**
     * Relasi ke tabel PinjamanHistory
     */
    public function history()
    {
        return $this->hasMany(PinjamanHistory::class, 'pinjaman_id');
    }

    /**
     * Relasi ke tabel PinjamanEmailNotification
     */
    public function emailNotifications()
    {
        return $this->hasMany(PinjamanEmailNotification::class, 'pinjaman_id');
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori_peminjam', $kategori);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pinjaman yang sedang berjalan
     */
    public function scopeBerjalan($query)
    {
        return $query->whereIn('status', ['dicairkan', 'berjalan']);
    }

    /**
     * Scope untuk pinjaman yang perlu approval
     */
    public function scopePerluApproval($query)
    {
        return $query->whereIn('status', ['pengajuan', 'review']);
    }

    /**
     * Generate nomor pinjaman otomatis
     */
    public static function generateNomorPinjaman()
    {
        $prefix = 'PNJ-' . date('Ym') . '-';
        $lastPinjaman = self::where('nomor_pinjaman', 'like', $prefix . '%')
            ->orderBy('nomor_pinjaman', 'desc')
            ->first();

        if ($lastPinjaman) {
            $lastNumber = (int) substr($lastPinjaman->nomor_pinjaman, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate jadwal cicilan (tanpa bunga)
     */
    public function generateJadwalCicilan()
    {
        // Hapus jadwal cicilan lama jika ada
        $this->cicilan()->delete();

        $tanggalMulai = $this->tanggal_pencairan ?? $this->tanggal_pengajuan;
        
        // Ambil tanggal jatuh tempo yang di-set (default tanggal 1)
        $tanggalJatuhTempoSetiapBulan = $this->tanggal_jatuh_tempo_setiap_bulan ?? 1;
        
        // Cicilan tetap setiap bulan (tanpa bunga)
        $cicilanPerBulan = $this->cicilan_per_bulan;
        
        for ($i = 1; $i <= $this->tenor_bulan; $i++) {
            // Hitung bulan berikutnya
            $bulanBerikutnya = $tanggalMulai->copy()->addMonths($i);
            
            // Set tanggal jatuh tempo sesuai setting
            // Jika tanggal yang di-set melebihi jumlah hari di bulan tersebut, gunakan hari terakhir bulan
            $hariTerakhirBulan = $bulanBerikutnya->daysInMonth;
            $tanggalJatuhTempo = $bulanBerikutnya->copy()->day(
                min($tanggalJatuhTempoSetiapBulan, $hariTerakhirBulan)
            );
            
            PinjamanCicilan::create([
                'pinjaman_id' => $this->id,
                'cicilan_ke' => $i,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'jumlah_pokok' => round($cicilanPerBulan, 2),
                'jumlah_bunga' => 0,
                'jumlah_cicilan' => round($cicilanPerBulan, 2),
                'sisa_cicilan' => round($cicilanPerBulan, 2),
                'status' => 'belum_bayar',
            ]);
        }

        // Update tanggal jatuh tempo pertama dan terakhir
        $this->tanggal_jatuh_tempo_pertama = $this->cicilan()->orderBy('cicilan_ke')->first()->tanggal_jatuh_tempo;
        $this->tanggal_jatuh_tempo_terakhir = $this->cicilan()->orderBy('cicilan_ke', 'desc')->first()->tanggal_jatuh_tempo;
        $this->save();
    }

    /**
     * Log history perubahan
     */
    public function logHistory($aksi, $statusLama, $statusBaru, $keterangan = null, $dataPerubahan = null)
    {
        PinjamanHistory::create([
            'pinjaman_id' => $this->id,
            'aksi' => $aksi,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'keterangan' => $keterangan,
            'data_perubahan' => $dataPerubahan,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'System',
        ]);
    }

    /**
     * Get nama peminjam (crew atau non-crew)
     */
    public function getNamaPeminjamLengkapAttribute()
    {
        // Prioritas 1: Field nama_peminjam_lengkap (untuk data baru)
        if (!empty($this->attributes['nama_peminjam_lengkap'])) {
            return $this->attributes['nama_peminjam_lengkap'];
        }
        
        // Prioritas 2: Dari relasi karyawan (untuk data lama crew)
        if ($this->kategori_peminjam == 'crew' && $this->karyawan) {
            return $this->karyawan->nama_karyawan ?? $this->karyawan->nama_lengkap ?? 'Nama tidak tersedia';
        }
        
        // Prioritas 3: Field nama_peminjam (fallback)
        if (!empty($this->attributes['nama_peminjam'])) {
            return $this->attributes['nama_peminjam'];
        }
        
        // Default jika semua kosong
        return 'Nama tidak tersedia';
    }

    /**
     * Get persentase pembayaran
     */
    public function getPersentasePembayaranAttribute()
    {
        if ($this->total_pinjaman <= 0) {
            return 0;
        }
        return round(($this->total_terbayar / $this->total_pinjaman) * 100, 2);
    }

    /**
     * Cek apakah ada cicilan yang terlambat
     */
    public function hasCicilanTerlambat()
    {
        return $this->cicilan()
            ->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', now())
            ->exists();
    }
}
