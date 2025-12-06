<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanProsesHistory extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_proses_history';

    protected $fillable = [
        'proses_id',
        'tahap_id',
        'event_type',
        'old_value',
        'new_value',
        'description',
        'user_id',
        'user_name',
        'payload',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Relasi ke KendaraanProses
     */
    public function proses()
    {
        return $this->belongsTo(KendaraanProses::class, 'proses_id');
    }

    /**
     * Relasi ke KendaraanProsesTahap
     */
    public function tahap()
    {
        return $this->belongsTo(KendaraanProsesTahap::class, 'tahap_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get formatted event type
     */
    public function getFormattedEventType()
    {
        return match($this->event_type) {
            'created' => 'Dibuat',
            'stage_changed' => 'Tahap Berubah',
            'stage_updated' => 'Tahap Diperbarui',
            'status_updated' => 'Status Diperbarui',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->event_type),
        };
    }

    /**
     * Get event icon
     */
    public function getEventIcon()
    {
        return match($this->event_type) {
            'created' => 'ti ti-plus-circle',
            'stage_changed' => 'ti ti-arrow-right',
            'stage_updated' => 'ti ti-edit',
            'status_updated' => 'ti ti-refresh',
            'completed' => 'ti ti-check-circle',
            'cancelled' => 'ti ti-x-circle',
            default => 'ti ti-circle',
        };
    }

    /**
     * Get event color
     */
    public function getEventColor()
    {
        return match($this->event_type) {
            'created' => 'primary',
            'stage_changed' => 'info',
            'stage_updated' => 'warning',
            'status_updated' => 'secondary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
