<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JumlahKehadiranMingguan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jumlah_kehadiran_mingguan';

    protected $fillable = [
        'jamaah_id',
        'tahun',
        'minggu_ke',
        'jumlah_kehadiran',
        'tanggal_kehadiran',
        'last_updated'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'minggu_ke' => 'integer',
        'jumlah_kehadiran' => 'integer',
        'tanggal_kehadiran' => 'date',
        'last_updated' => 'datetime',
    ];

    /**
     * Relationship dengan jamaah
     */
    public function jamaah()
    {
        return $this->belongsTo(JamaahMasar::class, 'jamaah_id');
    }

    /**
     * Static method untuk mendapatkan minggu ke berapa saat ini
     * Format ISO 8601: Minggu dimulai hari Senin, berakhir hari Minggu
     */
    public static function getMingguKe($date = null)
    {
        if (!$date) {
            $date = now();
        } else {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->isoWeek;
    }

    /**
     * Static method untuk mendapatkan hari apa tanggal tertentu
     * Mengembalikan nama hari dalam Bahasa Indonesia
     */
    public static function getNamaHari($date = null)
    {
        if (!$date) {
            $date = now();
        } else {
            $date = \Carbon\Carbon::parse($date);
        }

        $hari_array = [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
        ];

        // Monday = 1, Sunday = 7 (ISO 8601)
        $day_num = $date->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        // Konversi ke ISO 8601: Monday = 1
        $iso_day = ($day_num == 0) ? 7 : $day_num;

        return $hari_array[$iso_day - 1];
    }

    /**
     * Static method untuk cek apakah hari ini Jumat
     */
    public static function isJumat($date = null)
    {
        if (!$date) {
            $date = now();
        } else {
            $date = \Carbon\Carbon::parse($date);
        }

        // Friday = 5 (ISO 8601: Monday = 1, Friday = 5)
        return $date->dayOfWeek == 5;
    }

    /**
     * Scope untuk mendapatkan kehadiran dalam tahun tertentu
     */
    public function scopeOfYear($query, $tahun = null)
    {
        if (!$tahun) {
            $tahun = now()->year;
        }

        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk mendapatkan kehadiran jamaah tertentu
     */
    public function scopeOfJamaah($query, $jamaah_id)
    {
        return $query->where('jamaah_id', $jamaah_id);
    }

    /**
     * Get total kehadiran dalam tahun tertentu
     */
    public static function getTotalKehadiranTahun($jamaah_id, $tahun = null)
    {
        if (!$tahun) {
            $tahun = now()->year;
        }

        return self::where('jamaah_id', $jamaah_id)
            ->where('tahun', $tahun)
            ->sum('jumlah_kehadiran');
    }
}
