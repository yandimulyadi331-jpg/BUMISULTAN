<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRAttendanceEvent extends Model
{
    use HasFactory;

    protected $table = 'qr_attendance_events';

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date',
        'venue_latitude' => 'decimal:8',
        'venue_longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi dengan Cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    /**
     * Relasi dengan User (Creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi dengan QR Codes
     */
    public function qrCodes()
    {
        return $this->hasMany(QRAttendanceCode::class, 'event_id');
    }

    /**
     * Relasi dengan Logs
     */
    public function logs()
    {
        return $this->hasMany(QRAttendanceLog::class, 'event_id');
    }

    /**
     * Relasi dengan Presensi Yayasan
     */
    public function attendances()
    {
        return $this->hasMany(PresensiYayasan::class, 'qr_event_id');
    }

    /**
     * Get active QR code untuk event ini
     */
    public function getActiveQRCode()
    {
        return $this->qrCodes()
            ->where('is_active', true)
            ->where('expired_at', '>', now())
            ->first();
    }

    /**
     * Cek apakah event sedang berlangsung
     */
    public function isOngoing()
    {
        $now = now();
        $today = $now->format('Y-m-d');
        $eventDate = $this->event_date->format('Y-m-d');
        
        // Pastikan event adalah hari ini
        if ($eventDate !== $today) {
            return false;
        }
        
        // Buat datetime lengkap untuk perbandingan
        $startDateTime = \Carbon\Carbon::parse($eventDate . ' ' . $this->event_start_time);
        $endDateTime = \Carbon\Carbon::parse($eventDate . ' ' . $this->event_end_time);
        
        return $now->between($startDateTime, $endDateTime);
    }

    /**
     * Get total kehadiran untuk event ini
     */
    public function getTotalAttendance()
    {
        return $this->attendances()->count();
    }

    /**
     * Get statistics untuk event
     */
    public function getStatistics()
    {
        return [
            'total_attendance' => $this->getTotalAttendance(),
            'total_scans' => $this->logs()->count(),
            'successful_scans' => $this->logs()->where('status', 'success')->count(),
            'failed_scans' => $this->logs()->where('status', '!=', 'success')->count(),
            'qr_code_method' => $this->attendances()->where('attendance_method', 'qr_code')->count(),
            'fingerprint_method' => $this->attendances()->where('attendance_method', 'fingerprint')->count(),
        ];
    }

    /**
     * Scope: Event aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Event hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('event_date', today());
    }

    /**
     * Scope: Event sedang berlangsung
     */
    public function scopeOngoing($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->whereDate('event_date', $now->toDateString())
            ->whereTime('event_start_time', '<=', $now->toTimeString())
            ->whereTime('event_end_time', '>=', $now->toTimeString());
    }
}
