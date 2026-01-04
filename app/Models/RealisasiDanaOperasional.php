<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealisasiDanaOperasional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'realisasi_dana_operasional';

    protected $fillable = [
        'pengajuan_id', 'nomor_transaksi', 'nomor_realisasi', 'tanggal_realisasi', 'urutan_baris',
        'uraian', 'keterangan', 'nominal', 'saldo_running', 'tipe_transaksi', 'kategori', 'file_bukti', 'foto_bukti', 'created_by', 'status',
    ];

    protected $casts = [
        'tanggal_realisasi' => 'date',
        'nominal' => 'decimal:2',
        'saldo_running' => 'decimal:2',
        'urutan_baris' => 'integer',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanDanaOperasional::class, 'pengajuan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Generate nomor transaksi simpel: BS-001, BS-002, dst
            if (empty($model->nomor_transaksi)) {
                $tanggal = $model->tanggal_realisasi ?? now();
                $prefix = 'BS-' . $tanggal->format('Ymd') . '-';
                
                // Cari nomor terakhir yang paling besar untuk tanggal ini (dengan locking untuk prevent race condition)
                $lastRecord = static::where('tanggal_realisasi', $tanggal->format('Y-m-d'))
                    ->where('nomor_transaksi', 'like', $prefix . '%')
                    ->orderByRaw("CAST(SUBSTRING(nomor_transaksi, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
                    ->lockForUpdate() // Row-level lock
                    ->first();
                
                if ($lastRecord) {
                    // Extract nomor dari nomor_transaksi terakhir (contoh: BS-20250101-003 -> 003)
                    $lastNomorStr = substr($lastRecord->nomor_transaksi, strlen($prefix));
                    $lastNomor = (int) $lastNomorStr;
                    $nextNumber = $lastNomor + 1;
                } else {
                    $nextNumber = 1;
                }
                
                $model->nomor_transaksi = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
            
            // Generate nomor realisasi (backup) dengan tambahan mikrodetik untuk unique constraint
            if (empty($model->nomor_realisasi)) {
                // Tambahkan mikrodetik untuk memastikan unique (menghindari duplicate)
                $mikrodetik = (int)(microtime(true) * 1000000); // Convert to microseconds integer
                $uniqueSuffix = substr((string)$mikrodetik, -6); // Last 6 digits
                $model->nomor_realisasi = $model->nomor_transaksi . '-' . $uniqueSuffix;
            }
            
            // AI Auto-detect kategori jika belum ada
            if (empty($model->kategori)) {
                $model->kategori = static::detectKategoriAI($model->keterangan ?? $model->uraian);
            }
        });
        
        static::created(function ($model) {
            static::recalculateSaldoHarian($model->tanggal_realisasi);
        });
        
        static::updated(function ($model) {
            static::recalculateSaldoHarian($model->tanggal_realisasi);
        });
        
        static::deleted(function ($model) {
            static::recalculateSaldoHarian($model->tanggal_realisasi);
        });
    }
    
    /**
     * AI System: Auto-detect kategori berdasarkan keterangan
     */
    public static function detectKategoriAI($text)
    {
        if (empty($text)) {
            return 'Operasional';
        }
        
        $text = strtolower($text);
        
        // Definisi kategori dengan keywords (AI Pattern Recognition)
        $kategoriRules = [
            'Transport & Kendaraan' => [
                'keywords' => ['bbm', 'bensin', 'solar', 'pertamax', 'spbu', 'oli', 'transport', 'angkut', 'mobil', 'motor', 'kendaraan', 'parkir', 'tol', 'service', 'ban', 'aki'],
                'weight' => 10
            ],
            'Utilitas' => [
                'keywords' => ['listrik', 'pln', 'air', 'pdam', 'wifi', 'internet', 'pulsa', 'token'],
                'weight' => 10
            ],
            'Konsumsi' => [
                'keywords' => ['makan', 'minum', 'konsumsi', 'snack', 'nasi', 'catering', 'warung', 'resto', 'cafe', 'kue', 'roti'],
                'weight' => 9
            ],
            'ATK & Perlengkapan' => [
                'keywords' => ['atk', 'alat tulis', 'kertas', 'pulpen', 'spidol', 'map', 'amplop', 'buku', 'tinta', 'printer', 'fotocopy'],
                'weight' => 9
            ],
            'Kebersihan' => [
                'keywords' => ['sabun', 'detergen', 'pel', 'sapu', 'lap', 'tisu', 'pembersih', 'bersih', 'cuci', 'sanitasi'],
                'weight' => 8
            ],
            'Maintenance' => [
                'keywords' => ['perbaikan', 'renovasi', 'cat', 'las', 'tukang', 'bangunan', 'maintenance', 'servis', 'ganti'],
                'weight' => 8
            ],
            'Kesehatan' => [
                'keywords' => ['obat', 'vitamin', 'apotek', 'p3k', 'kesehatan', 'dokter', 'klinik', 'rumah sakit'],
                'weight' => 8
            ],
            'Komunikasi' => [
                'keywords' => ['telepon', 'hp', 'handphone', 'komunikasi', 'paket data', 'sms'],
                'weight' => 7
            ],
            'Administrasi' => [
                'keywords' => ['admin', 'administrasi', 'legalisir', 'materai', 'surat', 'dokumen', 'pengurusan', 'izin'],
                'weight' => 7
            ],
            'Khidmat' => [
                'keywords' => ['khidmat', 'santri', 'pesantren', 'pondok', 'asrama', 'kamar'],
                'weight' => 10
            ],
            'Dana Masuk' => [
                'keywords' => ['dana masuk', 'terima', 'penerimaan', 'setoran', 'pemasukan', 'transfer masuk'],
                'weight' => 10
            ],
        ];
        
        $scores = [];
        
        // Hitung score untuk setiap kategori
        foreach ($kategoriRules as $kategori => $rules) {
            $score = 0;
            foreach ($rules['keywords'] as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    // Berikan score lebih tinggi jika keyword di awal kalimat
                    if (stripos($text, $keyword) === 0) {
                        $score += $rules['weight'] * 2;
                    } else {
                        $score += $rules['weight'];
                    }
                }
            }
            
            if ($score > 0) {
                $scores[$kategori] = $score;
            }
        }
        
        // Jika ada kategori yang cocok, ambil yang tertinggi
        if (!empty($scores)) {
            arsort($scores);
            return array_key_first($scores);
        }
        
        // Default kategori jika tidak ada yang cocok
        return 'Operasional';
    }
    
    /**
     * Recalculate saldo harian after transaction changes
     * LOGIKA BARU: Saldo positif masuk ke Dana Masuk, Saldo negatif masuk ke Dana Keluar
     */
    public static function recalculateSaldoHarian($tanggal)
    {
        $tanggalStr = is_string($tanggal) ? $tanggal : $tanggal->format('Y-m-d');
        
        // Get saldo kemarin (saldo akhir hari sebelumnya)
        $saldoKemarin = \App\Models\SaldoHarianOperasional::getSaldoKemarin($tanggalStr);
        
        // Ensure saldo harian exists
        $saldo = \App\Models\SaldoHarianOperasional::firstOrCreate(
            ['tanggal' => $tanggalStr],
            [
                'saldo_awal' => $saldoKemarin,
                'dana_masuk' => 0,
                'total_realisasi' => 0,
                'saldo_akhir' => 0,
                'status' => 'open',
            ]
        );
        
        // Update saldo_awal jika berbeda (untuk handle perubahan dari hari sebelumnya)
        if ($saldo->saldo_awal != $saldoKemarin) {
            $saldo->saldo_awal = $saldoKemarin;
        }
        
        // Calculate from transactions (ONLY ACTIVE transactions, exclude voided)
        $transaksi = static::whereDate('tanggal_realisasi', $tanggalStr)
            ->where('status', 'active') // Bank-grade: void transactions not counted
            ->get();
        
        $totalMasuk = $transaksi->where('tipe_transaksi', 'masuk')->sum('nominal');
        $totalKeluar = $transaksi->where('tipe_transaksi', 'keluar')->sum('nominal');
        
        // LOGIKA BARU: Include saldo_awal dalam perhitungan dana_masuk/dana_keluar
        // Jika saldo_awal POSITIF → masuk ke dana_masuk (ada uang tersisa)
        // Jika saldo_awal NEGATIF → masuk ke total_realisasi/dana_keluar (ada kekurangan)
        if ($saldo->saldo_awal >= 0) {
            // Saldo positif = Dana Masuk
            $saldo->dana_masuk = $saldo->saldo_awal + $totalMasuk;
            $saldo->total_realisasi = $totalKeluar;
        } else {
            // Saldo negatif = Dana Keluar (kekurangan)
            $saldo->dana_masuk = $totalMasuk;
            $saldo->total_realisasi = abs($saldo->saldo_awal) + $totalKeluar;
        }
        
        // Hitung saldo akhir: Saldo Awal + Dana Masuk - Dana Keluar
        $saldo->saldo_akhir = $saldo->saldo_awal + $totalMasuk - $totalKeluar;
        $saldo->save();
        
        \Log::info('Recalculate Saldo Harian', [
            'tanggal' => $tanggalStr,
            'saldo_awal' => $saldo->saldo_awal,
            'dana_masuk' => $saldo->dana_masuk,
            'total_realisasi' => $saldo->total_realisasi,
            'saldo_akhir' => $saldo->saldo_akhir,
        ]);
        
        // CASCADE UPDATE: Update semua hari setelah tanggal ini
        $hariBerikutnya = \App\Models\SaldoHarianOperasional::where('tanggal', '>', $tanggalStr)
            ->orderBy('tanggal', 'asc')
            ->get();
        
        $saldoSebelumnya = $saldo->saldo_akhir;
        
        foreach ($hariBerikutnya as $hariNext) {
            // Update saldo_awal hari berikutnya = saldo_akhir hari ini
            $hariNext->saldo_awal = $saldoSebelumnya;
            
            // Recalculate transaksi hari tersebut
            $transaksiNext = static::whereDate('tanggal_realisasi', $hariNext->tanggal)
                ->where('status', 'active')
                ->get();
            
            $totalMasukNext = $transaksiNext->where('tipe_transaksi', 'masuk')->sum('nominal');
            $totalKeluarNext = $transaksiNext->where('tipe_transaksi', 'keluar')->sum('nominal');
            
            // LOGIKA BARU: Include saldo_awal dalam perhitungan dana_masuk/dana_keluar
            if ($hariNext->saldo_awal >= 0) {
                $hariNext->dana_masuk = $hariNext->saldo_awal + $totalMasukNext;
                $hariNext->total_realisasi = $totalKeluarNext;
            } else {
                $hariNext->dana_masuk = $totalMasukNext;
                $hariNext->total_realisasi = abs($hariNext->saldo_awal) + $totalKeluarNext;
            }
            
            $hariNext->saldo_akhir = $hariNext->saldo_awal + $totalMasukNext - $totalKeluarNext;
            $hariNext->save();
            
            // Update saldo untuk hari selanjutnya
            $saldoSebelumnya = $hariNext->saldo_akhir;
            
            \Log::info('Cascade Update Saldo', [
                'tanggal' => $hariNext->tanggal->format('Y-m-d'),
                'saldo_awal' => $hariNext->saldo_awal,
                'saldo_akhir' => $hariNext->saldo_akhir,
            ]);
        }
    }
}