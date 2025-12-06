<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RealTimeNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message', 
        'type',
        'icon',
        'color',
        'data',
        'user_id',
        'reference_id',
        'reference_table',
        'is_read',
        'tanggal'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope untuk notifikasi hari ini
     */
    public function scopeToday($query)
    {
        return $query->where('tanggal', Carbon::today());
    }

    /**
     * Scope untuk notifikasi terbaru (descending)
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Method untuk membuat notifikasi baru
     */
    public static function createNotification($title, $message, $type, $options = [])
    {
        return self::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $options['icon'] ?? self::getDefaultIcon($type),
            'color' => $options['color'] ?? self::getDefaultColor($type),
            'data' => $options['data'] ?? [],
            'user_id' => $options['user_id'] ?? null,
            'reference_id' => $options['reference_id'] ?? null,
            'reference_table' => $options['reference_table'] ?? null,
            'tanggal' => Carbon::today()
        ]);
    }

    /**
     * Get default icon berdasarkan tipe
     */
    private static function getDefaultIcon($type)
    {
        $icons = [
            'presensi' => 'ti ti-clock',
            'kendaraan' => 'ti ti-car',
            'pinjaman' => 'ti ti-credit-card',
            'undangan' => 'ti ti-calendar-event',
            'inventaris' => 'ti ti-package',
            'administrasi' => 'ti ti-file-text',
            'keuangan' => 'ti ti-coins',
            'default' => 'ti ti-bell'
        ];

        return $icons[$type] ?? $icons['default'];
    }

    /**
     * Get default color berdasarkan tipe
     */
    private static function getDefaultColor($type)
    {
        $colors = [
            'presensi' => 'success',
            'kendaraan' => 'info',
            'pinjaman' => 'warning',
            'undangan' => 'primary',
            'inventaris' => 'secondary',
            'administrasi' => 'dark',
            'keuangan' => 'success',
            'default' => 'primary'
        ];

        return $colors[$type] ?? $colors['default'];
    }

    /**
     * Format waktu untuk display
     */
    public function getTimeFormatAttribute()
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Format waktu relatif
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
