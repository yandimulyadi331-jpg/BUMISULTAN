<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerawatanLog extends Model
{
    use HasFactory;

    protected $table = 'perawatan_log';

    protected $fillable = [
        'master_perawatan_id',
        'user_id',
        'tanggal_eksekusi',
        'waktu_eksekusi',
        'status',
        'catatan',
        'foto_bukti',
        'periode_key'
    ];

    protected $casts = [
        'tanggal_eksekusi' => 'date',
    ];

    public function masterPerawatan()
    {
        return $this->belongsTo(MasterPerawatan::class, 'master_perawatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByPeriode($query, $periodeKey)
    {
        return $query->where('periode_key', $periodeKey);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_eksekusi', today());
    }
}
