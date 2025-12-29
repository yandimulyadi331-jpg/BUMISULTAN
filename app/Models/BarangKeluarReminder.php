<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarReminder extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_reminder';

    protected $fillable = [
        'barang_keluar_id',
        'tanggal_reminder',
        'pesan_reminder',
        'tipe_reminder',
        'sudah_terkirim',
        'tanggal_terkirim',
    ];

    protected $casts = [
        'tanggal_reminder' => 'datetime',
        'tanggal_terkirim' => 'datetime',
        'sudah_terkirim' => 'boolean',
    ];

    // Relasi ke barang keluar
    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    // Scope untuk reminder yang belum terkirim
    public function scopeBelumTerkirim($query)
    {
        return $query->where('sudah_terkirim', false);
    }

    // Scope untuk reminder yang sudah waktunya
    public function scopeWaktunyaKirim($query)
    {
        return $query->where('tanggal_reminder', '<=', now())
            ->where('sudah_terkirim', false);
    }
}
