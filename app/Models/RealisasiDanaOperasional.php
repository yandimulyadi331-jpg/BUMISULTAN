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
        'uraian', 'keterangan', 'nominal', 'saldo_running', 'tipe_transaksi', 'kategori', 'file_bukti', 'foto_bukti', 'created_by',
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
            
            // Generate nomor realisasi (backup)
            if (empty($model->nomor_realisasi)) {
                $model->nomor_realisasi = $model->nomor_transaksi;
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
     */
    public static function recalculateSaldoHarian($tanggal)
    {
        $tanggalStr = is_string($tanggal) ? $tanggal : $tanggal->format('Y-m-d');
        
        // Ensure saldo harian exists
        $saldo = \App\Models\SaldoHarianOperasional::firstOrCreate(
            ['tanggal' => $tanggalStr],
            [
                'saldo_awal' => \App\Models\SaldoHarianOperasional::getSaldoKemarin($tanggalStr),
                'dana_masuk' => 0,
                'total_realisasi' => 0,
                'saldo_akhir' => 0,
                'status' => 'open',
            ]
        );
        
        // Calculate from transactions (ONLY ACTIVE transactions, exclude voided)
        $transaksi = static::whereDate('tanggal_realisasi', $tanggalStr)
            ->where('status', 'active') // Bank-grade: void transactions not counted
            ->get();
        
        $totalMasuk = $transaksi->where('tipe_transaksi', 'masuk')->sum('nominal');
        $totalKeluar = $transaksi->where('tipe_transaksi', 'keluar')->sum('nominal');
        
        $saldo->dana_masuk = $totalMasuk;
        $saldo->total_realisasi = $totalKeluar;
        $saldo->saldo_akhir = $saldo->saldo_awal + $totalMasuk - $totalKeluar;
        $saldo->save();
        
        // Update next day's saldo_awal (cascade effect)
        $besok = \Carbon\Carbon::parse($tanggalStr)->addDay()->format('Y-m-d');
        $saldoBesok = \App\Models\SaldoHarianOperasional::where('tanggal', $besok)->first();
        
        if ($saldoBesok) {
            $saldoBesok->saldo_awal = $saldo->saldo_akhir;
            $saldoBesok->saldo_akhir = $saldoBesok->saldo_awal + $saldoBesok->dana_masuk - $saldoBesok->total_realisasi;
            $saldoBesok->save();
            
            // Recursive cascade for days after tomorrow
            $lusa = \Carbon\Carbon::parse($besok)->addDay()->format('Y-m-d');
            if (\App\Models\SaldoHarianOperasional::where('tanggal', $lusa)->exists()) {
                static::recalculateSaldoHarian($besok);
            }
        }
    }
}