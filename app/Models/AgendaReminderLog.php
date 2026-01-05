<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaReminderLog extends Model
{
    use HasFactory;

    protected $table = 'agenda_reminder_log';
    
    public $timestamps = false;

    protected $fillable = [
        'agenda_id',
        'tipe_reminder',
        'menit_sebelum',
        'metode_reminder',
        'tujuan',
        'status',
        'tanggal_kirim',
        'error_message',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke agenda
     */
    public function agenda()
    {
        return $this->belongsTo(AgendaPerusahaan::class, 'agenda_id');
    }
}
