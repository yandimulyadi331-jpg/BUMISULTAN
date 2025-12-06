<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'informasi';

    protected $fillable = [
        'judul',
        'konten',
        'tipe',
        'banner_path',
        'link_url',
        'is_active',
        'priority',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function reads()
    {
        return $this->hasMany(InformasiRead::class);
    }

    public function isReadByUser($userId)
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            });
    }

    public function scopeUnreadByUser($query, $userId)
    {
        return $query->whereDoesntHave('reads', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
