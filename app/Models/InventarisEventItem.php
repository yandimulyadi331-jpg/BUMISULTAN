<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisEventItem extends Model
{
    use HasFactory;

    protected $table = 'inventaris_event_items';

    protected $fillable = [
        'inventaris_event_id',
        'inventaris_id',
        'jumlah_dibutuhkan',
        'jumlah_tersedia',
        'status',
        'keterangan',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(InventarisEvent::class, 'inventaris_event_id');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    // Helper methods
    public function isReady()
    {
        return $this->jumlah_tersedia >= $this->jumlah_dibutuhkan;
    }

    public function getStatusBadgeClass()
    {
        $classes = [
            'menunggu' => 'warning',
            'tersedia' => 'success',
            'terdistribusi' => 'info',
            'dikembalikan' => 'secondary',
        ];

        return $classes[$this->status] ?? 'secondary';
    }
}
