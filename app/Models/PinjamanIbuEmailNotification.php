<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanIbuEmailNotification extends Model
{
    use HasFactory;

    protected $table = 'pinjaman_ibu_email_notifications';

    protected $fillable = [
        'pinjaman_ibu_id',
        'email_tujuan',
        'tipe_notifikasi',
        'hari_sebelum_jatuh_tempo',
        'tanggal_jatuh_tempo',
        'status',
        'sent_at',
        'error_message',
        'retry_count',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'sent_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    /**
     * Relasi ke PinjamanIbu
     */
    public function pinjaman()
    {
        return $this->belongsTo(PinjamanIbu::class, 'pinjaman_ibu_id');
    }
}
