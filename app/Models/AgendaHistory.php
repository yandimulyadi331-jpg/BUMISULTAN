<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaHistory extends Model
{
    use HasFactory;

    protected $table = 'agenda_history';
    
    public $timestamps = false;

    protected $fillable = [
        'agenda_id',
        'aksi',
        'perubahan',
        'catatan',
        'user_id',
        'user_name',
    ];

    protected $casts = [
        'perubahan' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke agenda
     */
    public function agenda()
    {
        return $this->belongsTo(AgendaPerusahaan::class, 'agenda_id');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
