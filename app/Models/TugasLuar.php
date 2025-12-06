<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasLuar extends Model
{
    use HasFactory;

    protected $table = 'tugas_luar';

    protected $fillable = [
        'kode_tugas',
        'karyawan_list',
        'tanggal',
        'tujuan',
        'keterangan',
        'waktu_keluar',
        'waktu_kembali',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'karyawan_list' => 'array',
    ];

    /**
     * Generate kode tugas otomatis
     */
    public static function generateKodeTugas()
    {
        $tanggal = date('Ymd');
        $lastTugas = self::whereDate('created_at', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTugas) {
            $lastNumber = intval(substr($lastTugas->kode_tugas, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'TL' . $tanggal . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi ke Karyawan (many to many via JSON)
     */
    public function karyawanList()
    {
        $nikList = $this->karyawan_list ?? [];
        return Karyawan::whereIn('nik', $nikList)->get();
    }

    /**
     * Scope untuk tugas yang sedang berlangsung
     */
    public function scopeKeluar($query)
    {
        return $query->where('status', 'keluar');
    }

    /**
     * Scope untuk tugas yang sudah kembali
     */
    public function scopeKembali($query)
    {
        return $query->where('status', 'kembali');
    }

    /**
     * Scope untuk hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', date('Y-m-d'));
    }
}
