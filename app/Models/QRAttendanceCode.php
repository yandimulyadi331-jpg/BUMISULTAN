<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRAttendanceCode extends Model
{
    use HasFactory;

    protected $table = 'qr_attendance_codes';

    protected $guarded = [];

    protected $casts = [
        'generated_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi dengan Event
     */
    public function event()
    {
        return $this->belongsTo(QRAttendanceEvent::class, 'event_id');
    }

    /**
     * Relasi dengan Logs
     */
    public function logs()
    {
        return $this->hasMany(QRAttendanceLog::class, 'qr_code_id');
    }

    /**
     * Cek apakah QR code masih valid
     * Note: QR permanent - hanya cek is_active
     */
    public function isValid()
    {
        return $this->is_active;
    }

    /**
     * Cek apakah QR sudah expired
     * Note: QR permanent - always false
     */
    public function isExpired()
    {
        return false; // QR permanent, validasi di event timing
    }

    /**
     * Nonaktifkan QR code
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Increment scan count
     */
    public function incrementScanCount()
    {
        $this->increment('scan_count');
    }

    /**
     * Scope: QR code aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: QR code valid (aktif dan belum expired)
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where('expired_at', '>', now());
    }

    /**
     * Generate QR token unik
     */
    public static function generateToken()
    {
        do {
            $token = 'QR' . date('YmdHis') . strtoupper(bin2hex(random_bytes(6)));
        } while (self::where('qr_token', $token)->exists());

        return $token;
    }
}
