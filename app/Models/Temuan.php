<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Temuan extends Model
{
    use HasFactory;

    protected $table = 'temuan';
    protected $guarded = [];

    protected $casts = [
        'tanggal_temuan' => 'datetime',
        'tanggal_ditindaklanjuti' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * User yang melaporkan temuan
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Admin yang menangani temuan
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope untuk menampilkan hanya temuan yang masih aktif (belum selesai)
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['baru', 'sedang_diproses', 'sudah_diperbaiki', 'tindaklanjuti']);
    }

    /**
     * Scope untuk menampilkan hanya temuan yang sudah selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk filter berdasarkan urgensi
     */
    public function scopeUrgensi($query, $urgensi)
    {
        return $query->where('urgensi', $urgensi);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk sorting terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_temuan', 'desc');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'baru' => 'primary',
            'sedang_diproses' => 'warning',
            'sudah_diperbaiki' => 'info',
            'tindaklanjuti' => 'secondary',
            'selesai' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get status badge label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'baru' => 'Baru',
            'sedang_diproses' => 'Sedang Diproses',
            'sudah_diperbaiki' => 'Sudah Diperbaiki',
            'tindaklanjuti' => 'Tindaklanjuti',
            'selesai' => 'Selesai',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get urgensi label
     */
    public function getUrgensiLabel()
    {
        return match($this->urgensi) {
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            'kritis' => 'Kritis',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get urgensi badge color
     */
    public function getUrgensiBadgeColor()
    {
        return match($this->urgensi) {
            'rendah' => 'success',
            'sedang' => 'warning',
            'tinggi' => 'danger',
            'kritis' => 'dark',
            default => 'secondary',
        };
    }
}
