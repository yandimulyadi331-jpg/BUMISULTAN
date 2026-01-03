<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistPeriodeConfig extends Model
{
    use HasFactory;

    protected $table = 'checklist_periode_config';

    protected $fillable = [
        'tipe_periode',
        'is_enabled',
        'is_mandatory',
        'keterangan',
        'dibuat_oleh',
        'diubah_oleh',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_mandatory' => 'boolean',
    ];

    /**
     * Relasi ke user yang membuat config
     */
    public function pembuatRelasi()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke user yang mengubah config
     */
    public function pengubahRelasi()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }

    /**
     * Scope untuk get config by tipe periode
     */
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_periode', $tipe);
    }

    /**
     * Scope untuk get config yang enabled
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope untuk get config yang mandatory
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Get status text untuk display
     */
    public function getStatusTextAttribute()
    {
        if (!$this->is_enabled) {
            return 'Nonaktif';
        }

        if ($this->is_mandatory) {
            return 'Aktif & Wajib';
        }

        return 'Aktif & Opsional';
    }

    /**
     * Get badge class untuk UI
     */
    public function getBadgeClassAttribute()
    {
        if (!$this->is_enabled) {
            return 'bg-secondary';
        }

        if ($this->is_mandatory) {
            return 'bg-danger';
        }

        return 'bg-success';
    }
}
