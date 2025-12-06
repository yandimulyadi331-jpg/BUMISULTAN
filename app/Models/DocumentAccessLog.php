<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'nik',
        'nama_user',
        'action',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relasi ke document
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    /**
     * Log access
     */
    public static function logAccess($documentId, $action)
    {
        $user = auth()->user();
        
        return self::create([
            'document_id' => $documentId,
            'nik' => $user->nik ?? null,
            'nama_user' => $user->name ?? 'Guest',
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get action badge
     */
    public function getActionBadgeAttribute()
    {
        $badges = [
            'view' => 'badge bg-info',
            'download' => 'badge bg-success',
            'preview' => 'badge bg-warning',
        ];

        return $badges[$this->action] ?? 'badge bg-secondary';
    }

    /**
     * Get action text
     */
    public function getActionTextAttribute()
    {
        $texts = [
            'view' => 'Melihat',
            'download' => 'Mengunduh',
            'preview' => 'Preview',
        ];

        return $texts[$this->action] ?? $this->action;
    }
}
