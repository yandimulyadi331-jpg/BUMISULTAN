<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KendaraanProses extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kendaraan_proses';

    protected $fillable = [
        'kode_proses',
        'kendaraan_id',
        'jenis_proses',
        'status_proses',
        'tahap_saat_ini',
        'user_id',
        'user_name',
        'data_proses',
        'waktu_mulai',
        'waktu_selesai',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'data_proses' => 'array',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'locked_at' => 'datetime',
    ];

    /**
     * Relasi ke Kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /**
     * Relasi ke Tahap-tahap proses
     */
    public function tahapan()
    {
        return $this->hasMany(KendaraanProsesTahap::class, 'proses_id')->orderBy('urutan');
    }

    /**
     * Relasi ke History
     */
    public function history()
    {
        return $this->hasMany(KendaraanProsesHistory::class, 'proses_id')->orderBy('created_at', 'desc');
    }

    /**
     * Relasi ke User yang menginisiasi
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Check if proses is locked
     */
    public function isLocked()
    {
        if (!$this->locked_at) {
            return false;
        }
        
        // Lock expires after 5 minutes
        return $this->locked_at->diffInMinutes(now()) < 5;
    }

    /**
     * Lock proses for editing
     */
    public function lock($userId)
    {
        if ($this->isLocked() && $this->locked_by != $userId) {
            return false;
        }
        
        $this->update([
            'locked_at' => now(),
            'locked_by' => $userId,
        ]);
        
        return true;
    }

    /**
     * Unlock proses
     */
    public function unlock()
    {
        $this->update([
            'locked_at' => null,
            'locked_by' => null,
        ]);
    }

    /**
     * Get current stage
     */
    public function getCurrentStage()
    {
        return $this->tahapan()
            ->where('status_tahap', 'in_progress')
            ->orWhere('status_tahap', 'pending')
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get completed stages count
     */
    public function getCompletedStagesCount()
    {
        return $this->tahapan()->where('status_tahap', 'completed')->count();
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage()
    {
        $total = $this->tahapan()->count();
        if ($total == 0) return 0;
        
        $completed = $this->getCompletedStagesCount();
        return round(($completed / $total) * 100);
    }

    /**
     * Check if proses is completed
     */
    public function isCompleted()
    {
        return $this->status_proses === 'selesai';
    }

    /**
     * Check if proses is active
     */
    public function isActive()
    {
        return $this->status_proses === 'aktif';
    }

    /**
     * Complete the proses
     */
    public function complete()
    {
        $this->update([
            'status_proses' => 'selesai',
            'waktu_selesai' => now(),
        ]);
        
        // Unlock
        $this->unlock();
        
        // Log history
        $this->logHistory('completed', null, 'selesai', 'Proses diselesaikan', auth()->id(), auth()->user()->name);
    }

    /**
     * Cancel the proses
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status_proses' => 'dibatalkan',
            'waktu_selesai' => now(),
        ]);
        
        // Unlock
        $this->unlock();
        
        // Log history
        $this->logHistory('cancelled', null, 'dibatalkan', $reason ?? 'Proses dibatalkan', auth()->id(), auth()->user()->name);
    }

    /**
     * Log history entry
     */
    public function logHistory($eventType, $oldValue = null, $newValue = null, $description = null, $userId = null, $userName = null, $payload = [])
    {
        KendaraanProsesHistory::create([
            'proses_id' => $this->id,
            'event_type' => $eventType,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description,
            'user_id' => $userId ?? auth()->id(),
            'user_name' => $userName ?? auth()->user()->name,
            'payload' => $payload,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get workflow stages definition based on jenis_proses
     */
    public static function getWorkflowDefinition($jenisProses)
    {
        $workflows = [
            'keluar' => [
                ['kode' => 'pengajuan', 'nama' => 'Pengajuan Keluar', 'urutan' => 1],
                ['kode' => 'dalam_perjalanan', 'nama' => 'Dalam Perjalanan', 'urutan' => 2],
                ['kode' => 'menunggu_kembali', 'nama' => 'Menunggu Kembali', 'urutan' => 3],
            ],
            'pinjam' => [
                ['kode' => 'pengajuan', 'nama' => 'Pengajuan', 'urutan' => 1],
                ['kode' => 'verifikasi', 'nama' => 'Verifikasi', 'urutan' => 2],
                ['kode' => 'disetujui', 'nama' => 'Disetujui', 'urutan' => 3],
                ['kode' => 'diambil', 'nama' => 'Diambil', 'urutan' => 4],
                ['kode' => 'dalam_penggunaan', 'nama' => 'Dalam Penggunaan', 'urutan' => 5],
                ['kode' => 'selesai', 'nama' => 'Selesai', 'urutan' => 6],
            ],
            'service' => [
                ['kode' => 'diajukan', 'nama' => 'Diajukan', 'urutan' => 1],
                ['kode' => 'dijadwalkan', 'nama' => 'Dijadwalkan', 'urutan' => 2],
                ['kode' => 'dalam_perbaikan', 'nama' => 'Dalam Perbaikan', 'urutan' => 3],
                ['kode' => 'selesai', 'nama' => 'Selesai', 'urutan' => 4],
            ],
        ];

        return $workflows[$jenisProses] ?? [];
    }

    /**
     * Initialize workflow stages
     */
    public function initializeStages()
    {
        $stages = self::getWorkflowDefinition($this->jenis_proses);
        
        foreach ($stages as $stage) {
            KendaraanProsesTahap::create([
                'proses_id' => $this->id,
                'kode_tahap' => $stage['kode'],
                'nama_tahap' => $stage['nama'],
                'urutan' => $stage['urutan'],
                'status_tahap' => $stage['urutan'] == 1 ? 'in_progress' : 'pending',
                'waktu_mulai' => $stage['urutan'] == 1 ? now() : null,
            ]);
        }
        
        // Update current stage
        $this->update(['tahap_saat_ini' => $stages[0]['kode']]);
    }

    /**
     * Move to next stage
     */
    public function moveToNextStage($catatan = null, $metadata = [])
    {
        $currentStage = $this->getCurrentStage();
        
        if (!$currentStage) {
            return false;
        }
        
        // Complete current stage
        $currentStage->complete($catatan, $metadata);
        
        // Get next stage
        $nextStage = $this->tahapan()
            ->where('urutan', '>', $currentStage->urutan)
            ->where('status_tahap', 'pending')
            ->orderBy('urutan')
            ->first();
        
        if ($nextStage) {
            $nextStage->start();
            $this->update(['tahap_saat_ini' => $nextStage->kode_tahap]);
            
            return true;
        } else {
            // No more stages, complete the process
            $this->complete();
            return true;
        }
    }

    /**
     * Generate unique kode_proses
     */
    public static function generateKodeProses($jenisProses)
    {
        $prefix = strtoupper(substr($jenisProses, 0, 3));
        $date = date('Ymd');
        $count = self::whereDate('created_at', today())
            ->where('jenis_proses', $jenisProses)
            ->count() + 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }
}
