<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'deskripsi',
        'warna',
        'last_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_number' => 'integer',
    ];

    /**
     * Relasi ke documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get next number untuk generate kode dokumen
     */
    public function getNextNumber()
    {
        $this->increment('last_number');
        return str_pad($this->last_number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
