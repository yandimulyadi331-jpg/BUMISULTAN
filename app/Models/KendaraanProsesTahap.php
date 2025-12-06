<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanProsesTahap extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_proses_tahap';

    protected $fillable = [
        'proses_id',
        'kode_tahap',
        'nama_tahap',
        'urutan',
        'status_tahap',
        'updated_by_user_id',
        'updated_by_user_name',
        'catatan',
        'metadata',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'metadata' => 'array',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Relasi ke KendaraanProses
     */
    public function proses()
    {
        return $this->belongsTo(KendaraanProses::class, 'proses_id');
    }

    /**
     * Check if stage is pending
     */
    public function isPending()
    {
        return $this->status_tahap === 'pending';
    }

    /**
     * Check if stage is in progress
     */
    public function isInProgress()
    {
        return $this->status_tahap === 'in_progress';
    }

    /**
     * Check if stage is completed
     */
    public function isCompleted()
    {
        return $this->status_tahap === 'completed';
    }

    /**
     * Check if stage is rejected
     */
    public function isRejected()
    {
        return $this->status_tahap === 'rejected';
    }

    /**
     * Start the stage
     */
    public function start($userId = null, $userName = null)
    {
        $oldStatus = $this->status_tahap;
        
        $this->update([
            'status_tahap' => 'in_progress',
            'waktu_mulai' => now(),
            'updated_by_user_id' => $userId ?? auth()->id(),
            'updated_by_user_name' => $userName ?? auth()->user()->name,
        ]);
        
        // Log history
        $this->proses->logHistory(
            'stage_changed',
            $oldStatus,
            'in_progress',
            "Tahap '{$this->nama_tahap}' dimulai",
            $userId ?? auth()->id(),
            $userName ?? auth()->user()->name,
            ['tahap_id' => $this->id, 'kode_tahap' => $this->kode_tahap]
        );
    }

    /**
     * Complete the stage
     */
    public function complete($catatan = null, $metadata = [], $userId = null, $userName = null)
    {
        $oldStatus = $this->status_tahap;
        
        $this->update([
            'status_tahap' => 'completed',
            'waktu_selesai' => now(),
            'catatan' => $catatan,
            'metadata' => array_merge($this->metadata ?? [], $metadata),
            'updated_by_user_id' => $userId ?? auth()->id(),
            'updated_by_user_name' => $userName ?? auth()->user()->name,
        ]);
        
        // Log history
        $this->proses->logHistory(
            'stage_changed',
            $oldStatus,
            'completed',
            "Tahap '{$this->nama_tahap}' diselesaikan" . ($catatan ? ": {$catatan}" : ''),
            $userId ?? auth()->id(),
            $userName ?? auth()->user()->name,
            ['tahap_id' => $this->id, 'kode_tahap' => $this->kode_tahap]
        );
    }

    /**
     * Reject the stage
     */
    public function reject($reason, $userId = null, $userName = null)
    {
        $oldStatus = $this->status_tahap;
        
        $this->update([
            'status_tahap' => 'rejected',
            'waktu_selesai' => now(),
            'catatan' => $reason,
            'updated_by_user_id' => $userId ?? auth()->id(),
            'updated_by_user_name' => $userName ?? auth()->user()->name,
        ]);
        
        // Log history
        $this->proses->logHistory(
            'stage_changed',
            $oldStatus,
            'rejected',
            "Tahap '{$this->nama_tahap}' ditolak: {$reason}",
            $userId ?? auth()->id(),
            $userName ?? auth()->user()->name,
            ['tahap_id' => $this->id, 'kode_tahap' => $this->kode_tahap]
        );
    }

    /**
     * Skip the stage
     */
    public function skip($reason, $userId = null, $userName = null)
    {
        $oldStatus = $this->status_tahap;
        
        $this->update([
            'status_tahap' => 'skipped',
            'catatan' => $reason,
            'updated_by_user_id' => $userId ?? auth()->id(),
            'updated_by_user_name' => $userName ?? auth()->user()->name,
        ]);
        
        // Log history
        $this->proses->logHistory(
            'stage_changed',
            $oldStatus,
            'skipped',
            "Tahap '{$this->nama_tahap}' dilewati: {$reason}",
            $userId ?? auth()->id(),
            $userName ?? auth()->user()->name,
            ['tahap_id' => $this->id, 'kode_tahap' => $this->kode_tahap]
        );
    }

    /**
     * Update stage with custom status and note
     */
    public function updateStage($status, $catatan = null, $metadata = [], $userId = null, $userName = null)
    {
        $oldStatus = $this->status_tahap;
        
        $updateData = [
            'status_tahap' => $status,
            'updated_by_user_id' => $userId ?? auth()->id(),
            'updated_by_user_name' => $userName ?? auth()->user()->name,
        ];
        
        if ($catatan) {
            $updateData['catatan'] = $catatan;
        }
        
        if (!empty($metadata)) {
            $updateData['metadata'] = array_merge($this->metadata ?? [], $metadata);
        }
        
        if (in_array($status, ['completed', 'rejected', 'skipped'])) {
            $updateData['waktu_selesai'] = now();
        }
        
        $this->update($updateData);
        
        // Log history
        $this->proses->logHistory(
            'stage_updated',
            $oldStatus,
            $status,
            "Tahap '{$this->nama_tahap}' diperbarui" . ($catatan ? ": {$catatan}" : ''),
            $userId ?? auth()->id(),
            $userName ?? auth()->user()->name,
            ['tahap_id' => $this->id, 'kode_tahap' => $this->kode_tahap]
        );
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        return match($this->status_tahap) {
            'pending' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success',
            'rejected' => 'danger',
            'skipped' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get status badge text
     */
    public function getStatusBadgeText()
    {
        return match($this->status_tahap) {
            'pending' => 'Menunggu',
            'in_progress' => 'Proses',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'skipped' => 'Dilewati',
            default => 'Pending',
        };
    }

    /**
     * Get duration in minutes
     */
    public function getDurationMinutes()
    {
        if (!$this->waktu_mulai) {
            return 0;
        }
        
        $endTime = $this->waktu_selesai ?? now();
        return $this->waktu_mulai->diffInMinutes($endTime);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration()
    {
        $minutes = $this->getDurationMinutes();
        
        if ($minutes < 60) {
            return "{$minutes} menit";
        }
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        return "{$hours} jam {$mins} menit";
    }
}
