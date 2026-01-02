<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRAttendanceLog extends Model
{
    use HasFactory;

    protected $table = 'qr_attendance_logs';

    protected $guarded = [];

    protected $casts = [
        'scan_latitude' => 'decimal:8',
        'scan_longitude' => 'decimal:8',
        'distance_from_venue' => 'decimal:2',
        'scan_at' => 'datetime',
    ];

    /**
     * Relasi dengan Event
     */
    public function event()
    {
        return $this->belongsTo(QRAttendanceEvent::class, 'event_id');
    }

    /**
     * Relasi dengan QR Code
     */
    public function qrCode()
    {
        return $this->belongsTo(QRAttendanceCode::class, 'qr_code_id');
    }

    /**
     * Relasi dengan Jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(YayasanMasar::class, 'kode_yayasan', 'kode_yayasan');
    }

    /**
     * Cek apakah scan berhasil
     */
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    /**
     * Cek apakah scan gagal
     */
    public function isFailed()
    {
        return $this->status !== 'success';
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'success' => 'Berhasil',
            'failed_expired_qr' => 'QR Kadaluarsa',
            'failed_geofence' => 'Di Luar Area',
            'failed_device' => 'Device Tidak Valid',
            'failed_duplicate' => 'Sudah Absen',
            'failed_time' => 'Waktu Tidak Valid',
            'failed_face_verification' => 'Verifikasi Wajah Gagal',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'success' => 'bg-success',
            'failed_expired_qr' => 'bg-warning',
            'failed_geofence' => 'bg-danger',
            'failed_device' => 'bg-danger',
            'failed_duplicate' => 'bg-info',
            'failed_time' => 'bg-warning',
            'failed_face_verification' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    /**
     * Scope: Log berhasil
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: Log gagal
     */
    public function scopeFailed($query)
    {
        return $query->where('status', '!=', 'success');
    }

    /**
     * Scope: Hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('scan_at', today());
    }

    /**
     * Log attempt scan
     */
    public static function logAttempt($data)
    {
        return self::create([
            'event_id' => $data['event_id'],
            'qr_code_id' => $data['qr_code_id'],
            'kode_yayasan' => $data['kode_yayasan'] ?? null,
            'device_id' => $data['device_id'] ?? null,
            'scan_latitude' => $data['latitude'] ?? null,
            'scan_longitude' => $data['longitude'] ?? null,
            'distance_from_venue' => $data['distance'] ?? null,
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'status' => $data['status'],
            'failure_reason' => $data['failure_reason'] ?? null,
            'photo_selfie' => $data['photo_selfie'] ?? null,
        ]);
    }
}
