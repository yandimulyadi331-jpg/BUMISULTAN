<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerawatanLaporan extends Model
{
    use HasFactory;

    protected $table = 'perawatan_laporan';

    protected $fillable = [
        'tipe_laporan',
        'periode_key',
        'tanggal_laporan',
        'dibuat_oleh',
        'total_checklist',
        'total_completed',
        'ringkasan',
        'file_pdf'
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'total_checklist' => 'integer',
        'total_completed' => 'integer'
    ];

    public function pembuatLaporan()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
