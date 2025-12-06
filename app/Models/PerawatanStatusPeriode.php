<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerawatanStatusPeriode extends Model
{
    use HasFactory;

    protected $table = 'perawatan_status_periode';

    protected $fillable = [
        'tipe_periode',
        'periode_key',
        'periode_start',
        'periode_end',
        'total_checklist',
        'total_completed',
        'is_completed',
        'completed_at',
        'completed_by'
    ];

    protected $casts = [
        'periode_start' => 'date',
        'periode_end' => 'date',
        'total_checklist' => 'integer',
        'total_completed' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_periode', $tipe);
    }
}
