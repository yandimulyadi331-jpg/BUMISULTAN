<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans';
    
    protected $fillable = [
        'kode_kendaraan',
        'nama_kendaraan',
        'jenis_kendaraan',
        'merk',
        'model',
        'tahun',
        'no_polisi',
        'no_rangka',
        'no_mesin',
        'warna',
        'kapasitas_penumpang',
        'jenis_bbm',
        'foto',
        'status',
        'proses_aktif_id',
        'status_workflow',
        'stnk_berlaku',
        'pajak_berlaku',
        'kode_cabang',
        'keterangan'
    ];

    protected $casts = [
        'stnk_berlaku' => 'date',
        'pajak_berlaku' => 'date',
    ];

    /**
     * Relasi ke Cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    /**
     * Relasi ke Aktivitas Kendaraan
     */
    public function aktivitas()
    {
        return $this->hasMany(AktivitasKendaraan::class, 'kendaraan_id');
    }

    /**
     * Relasi ke Peminjaman Kendaraan
     */
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanKendaraan::class, 'kendaraan_id');
    }

    /**
     * Relasi ke Service Kendaraan
     */
    public function services()
    {
        return $this->hasMany(ServiceKendaraan::class, 'kendaraan_id');
    }

    /**
     * Relasi ke Jadwal Service
     */
    public function jadwalServices()
    {
        return $this->hasMany(JadwalService::class, 'kendaraan_id');
    }

    /**
     * Generate kode kendaraan otomatis berdasarkan jenis kendaraan
     * Format: 
     * - Mobil: MB01, MB02, MB03, ...
     * - Motor: MT01, MT02, MT03, ...
     * - Truk: TK01, TK02, TK03, ...
     * - Bus: BS01, BS02, BS03, ...
     * - Lainnya: LN01, LN02, LN03, ...
     * 
     * @param string $jenis_kendaraan
     * @return string
     */
    public static function generateKodeKendaraan($jenis_kendaraan)
    {
        // Mapping jenis kendaraan ke prefix
        $prefixMap = [
            'Mobil' => 'MB',
            'Motor' => 'MT',
            'Truk' => 'TK',
            'Bus' => 'BS',
            'Lainnya' => 'LN'
        ];
        
        $prefix = $prefixMap[$jenis_kendaraan] ?? 'XX';
        
        // Ambil kendaraan terakhir dengan jenis yang sama
        $lastKendaraan = self::where('jenis_kendaraan', $jenis_kendaraan)
            ->where('kode_kendaraan', 'LIKE', $prefix . '%')
            ->orderBy('kode_kendaraan', 'desc')
            ->first();
        
        if (!$lastKendaraan) {
            return $prefix . '01';
        }
        
        // Extract nomor dari kode terakhir (MB01 -> 01)
        $lastNumber = (int) substr($lastKendaraan->kode_kendaraan, 2);
        $newNumber = $lastNumber + 1;
        
        // Format menjadi MB01, MB02, dst dengan 2 digit
        return $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Get aktivitas yang sedang aktif (belum kembali)
     */
    public function aktivitasAktif()
    {
        return $this->hasOne(AktivitasKendaraan::class, 'kendaraan_id')
            ->where('status', 'keluar')
            ->latest();
    }

    /**
     * Get peminjaman yang sedang aktif (belum kembali)
     */
    public function peminjamanAktif()
    {
        return $this->hasOne(PeminjamanKendaraan::class, 'kendaraan_id')
            ->where('status', 'dipinjam')
            ->latest();
    }

    /**
     * Get service yang sedang proses
     */
    public function serviceAktif()
    {
        return $this->hasOne(ServiceKendaraan::class, 'kendaraan_id')
            ->where('status', 'proses')
            ->latest();
    }

    /**
     * Check apakah kendaraan tersedia
     */
    public function isTersedia()
    {
        return $this->status === 'tersedia';
    }

    /**
     * Check apakah perlu service
     */
    public function perluService()
    {
        // Cek jadwal service berdasarkan waktu
        $jadwalWaktu = $this->jadwalServices()
            ->where('tipe_interval', 'waktu')
            ->whereNotNull('jadwal_berikutnya')
            ->where('jadwal_berikutnya', '<=', now())
            ->exists();
        
        if ($jadwalWaktu) {
            return true;
        }
        
        // Cek jadwal service berdasarkan kilometer
        // (perlu implementasi tracking KM kendaraan)
        $jadwalKm = $this->jadwalServices()
            ->where('tipe_interval', 'kilometer')
            ->whereNotNull('interval_km')
            ->exists();
        
        return $jadwalKm;
    }

    /**
     * Relasi ke Proses Workflow Aktif
     */
    public function prosesAktif()
    {
        return $this->belongsTo(KendaraanProses::class, 'proses_aktif_id');
    }

    /**
     * Relasi ke Semua Proses Workflow
     */
    public function semuaProses()
    {
        return $this->hasMany(KendaraanProses::class, 'kendaraan_id')->orderBy('created_at', 'desc');
    }

    /**
     * Check apakah kendaraan memiliki proses aktif
     */
    public function hasActiveProcess()
    {
        return $this->proses_aktif_id !== null && $this->status_workflow !== 'idle';
    }

    /**
     * Get active process details
     */
    public function getActiveProcessDetails()
    {
        if (!$this->hasActiveProcess()) {
            return null;
        }

        $proses = $this->prosesAktif;
        if (!$proses) {
            return null;
        }

        return [
            'id' => $proses->id,
            'kode_proses' => $proses->kode_proses,
            'jenis_proses' => $proses->jenis_proses,
            'tahap_saat_ini' => $proses->tahap_saat_ini,
            'progress' => $proses->getProgressPercentage(),
            'stages' => $proses->tahapan,
            'current_stage' => $proses->getCurrentStage(),
            'user_name' => $proses->user_name,
            'waktu_mulai' => $proses->waktu_mulai,
            'data_proses' => $proses->data_proses,
        ];
    }

    /**
     * Check if kendaraan can be used (no active workflow blocking it)
     */
    public function canBeUsed()
    {
        return $this->status_workflow === 'idle' && $this->status === 'tersedia';
    }

    /**
     * Check if specific action is allowed
     */
    public function canPerformAction($action)
    {
        // If no active process, all actions are allowed (if status is tersedia)
        if (!$this->hasActiveProcess()) {
            return $this->status === 'tersedia';
        }

        // If there's an active process, check what action is blocked
        $blockedActions = [
            'in_keluar' => ['keluar', 'pinjam', 'service'],
            'in_pinjam' => ['keluar', 'pinjam', 'service'],
            'in_service' => ['keluar', 'pinjam', 'service'],
        ];

        $currentWorkflow = $this->status_workflow;
        
        if (isset($blockedActions[$currentWorkflow]) && in_array($action, $blockedActions[$currentWorkflow])) {
            return false;
        }

        return true;
    }

    /**
     * Get blocking reason for display
     */
    public function getBlockingReason()
    {
        if (!$this->hasActiveProcess()) {
            if ($this->status !== 'tersedia') {
                return "Kendaraan sedang {$this->status}";
            }
            return null;
        }

        $proses = $this->prosesAktif;
        if (!$proses) {
            return null;
        }

        $messages = [
            'in_keluar' => "Kendaraan sedang keluar (digunakan oleh {$proses->user_name})",
            'in_pinjam' => "Kendaraan sedang dipinjam (peminjam: {$proses->user_name})",
            'in_service' => "Kendaraan sedang dalam proses service",
        ];

        return $messages[$this->status_workflow] ?? "Kendaraan sedang dalam proses {$proses->jenis_proses}";
    }

    /**
     * Start new workflow process
     */
    public function startWorkflowProcess($jenisProses, $userId, $userName, $dataProses = [])
    {
        // Check if there's already an active process
        if ($this->hasActiveProcess()) {
            throw new \Exception($this->getBlockingReason());
        }

        // Create new process
        $kodeProses = KendaraanProses::generateKodeProses($jenisProses);
        
        $proses = KendaraanProses::create([
            'kode_proses' => $kodeProses,
            'kendaraan_id' => $this->id,
            'jenis_proses' => $jenisProses,
            'status_proses' => 'aktif',
            'user_id' => $userId,
            'user_name' => $userName,
            'data_proses' => $dataProses,
            'waktu_mulai' => now(),
        ]);

        // Initialize workflow stages
        $proses->initializeStages();

        // Update kendaraan status
        $statusWorkflow = 'in_' . $jenisProses;
        
        // Map jenis_proses to valid status ENUM values
        $statusMap = [
            'keluar' => 'keluar',
            'pinjam' => 'dipinjam',
            'service' => 'service',
        ];
        $status = $statusMap[$jenisProses] ?? $jenisProses;
        
        $this->update([
            'proses_aktif_id' => $proses->id,
            'status_workflow' => $statusWorkflow,
            'status' => $status,
        ]);

        // Log history
        $proses->logHistory(
            'created',
            null,
            'aktif',
            "Proses {$jenisProses} dimulai untuk kendaraan {$this->nama_kendaraan}",
            $userId,
            $userName,
            $dataProses
        );

        return $proses;
    }

    /**
     * Complete active workflow process
     */
    public function completeWorkflowProcess($catatan = null)
    {
        if (!$this->hasActiveProcess()) {
            throw new \Exception('Tidak ada proses aktif yang dapat diselesaikan');
        }

        $proses = $this->prosesAktif;
        
        // Complete the process
        $proses->complete();

        // Reset kendaraan status
        $this->update([
            'proses_aktif_id' => null,
            'status_workflow' => 'idle',
            'status' => 'tersedia',
        ]);

        return true;
    }

    /**
     * Cancel active workflow process
     */
    public function cancelWorkflowProcess($reason)
    {
        if (!$this->hasActiveProcess()) {
            throw new \Exception('Tidak ada proses aktif yang dapat dibatalkan');
        }

        $proses = $this->prosesAktif;
        
        // Cancel the process
        $proses->cancel($reason);

        // Reset kendaraan status
        $this->update([
            'proses_aktif_id' => null,
            'status_workflow' => 'idle',
            'status' => 'tersedia',
        ]);

        return true;
    }

    /**
     * Get workflow stages for display
     */
    public function getWorkflowStages()
    {
        if (!$this->hasActiveProcess()) {
            return [];
        }

        $proses = $this->prosesAktif;
        return $proses->tahapan->map(function($tahap) {
            return [
                'id' => $tahap->id,
                'kode' => $tahap->kode_tahap,
                'nama' => $tahap->nama_tahap,
                'urutan' => $tahap->urutan,
                'status' => $tahap->status_tahap,
                'status_color' => $tahap->getStatusBadgeColor(),
                'status_text' => $tahap->getStatusBadgeText(),
                'catatan' => $tahap->catatan,
                'updated_by' => $tahap->updated_by_user_name,
                'waktu_mulai' => $tahap->waktu_mulai,
                'waktu_selesai' => $tahap->waktu_selesai,
                'duration' => $tahap->getFormattedDuration(),
            ];
        })->toArray();
    }
}

