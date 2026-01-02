<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamaahDevice extends Model
{
    use HasFactory;

    protected $table = 'jamaah_devices';

    protected $guarded = [];

    protected $casts = [
        'first_login_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi dengan Yayasan Masar (Jamaah)
     */
    public function jamaah()
    {
        return $this->belongsTo(YayasanMasar::class, 'kode_yayasan', 'kode_yayasan');
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Nonaktifkan device
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Aktifkan device
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Cek apakah device masih aktif
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Scope: Device aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate device fingerprint dari request
     */
    public static function generateFingerprint($request)
    {
        $fingerprint = [
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'accept_language' => $request->header('Accept-Language'),
            'accept_encoding' => $request->header('Accept-Encoding'),
        ];

        return hash('sha256', json_encode($fingerprint));
    }

    /**
     * Detect device model dari user agent
     */
    public static function detectDeviceModel($userAgent)
    {
        // Simple detection
        if (preg_match('/Android/i', $userAgent)) {
            if (preg_match('/SM-[A-Z0-9]+/', $userAgent, $matches)) {
                return 'Samsung ' . $matches[0];
            } elseif (preg_match('/Redmi\s+[A-Za-z0-9\s]+/', $userAgent, $matches)) {
                return trim($matches[0]);
            }
            return 'Android Device';
        } elseif (preg_match('/iPhone/i', $userAgent)) {
            return 'iPhone';
        } elseif (preg_match('/iPad/i', $userAgent)) {
            return 'iPad';
        }
        
        return 'Unknown Device';
    }

    /**
     * Detect OS
     */
    public static function detectOS($userAgent)
    {
        if (preg_match('/Android\s+([0-9\.]+)/', $userAgent, $matches)) {
            return ['name' => 'Android', 'version' => $matches[1]];
        } elseif (preg_match('/OS\s+([0-9_]+)/', $userAgent, $matches)) {
            return ['name' => 'iOS', 'version' => str_replace('_', '.', $matches[1])];
        } elseif (preg_match('/Windows NT\s+([0-9\.]+)/', $userAgent, $matches)) {
            return ['name' => 'Windows', 'version' => $matches[1]];
        }
        
        return ['name' => 'Unknown', 'version' => 'Unknown'];
    }

    /**
     * Detect browser
     */
    public static function detectBrowser($userAgent)
    {
        if (preg_match('/Chrome\/([0-9\.]+)/', $userAgent, $matches)) {
            return 'Chrome ' . $matches[1];
        } elseif (preg_match('/Safari\/([0-9\.]+)/', $userAgent, $matches)) {
            return 'Safari ' . $matches[1];
        } elseif (preg_match('/Firefox\/([0-9\.]+)/', $userAgent, $matches)) {
            return 'Firefox ' . $matches[1];
        }
        
        return 'Unknown Browser';
    }
}
