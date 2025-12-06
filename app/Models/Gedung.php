<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedungs';
    
    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
        'alamat',
        'kode_cabang',
        'jumlah_lantai',
        'keterangan',
        'foto'
    ];

    /**
     * Relasi ke Ruangan
     * Satu gedung memiliki banyak ruangan
     */
    public function ruangans()
    {
        return $this->hasMany(Ruangan::class, 'gedung_id');
    }

    /**
     * Relasi ke Cabang (jika diperlukan)
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    /**
     * Get total ruangan di gedung ini
     */
    public function getTotalRuanganAttribute()
    {
        return $this->ruangans()->count();
    }

    /**
     * Get total barang di gedung ini
     */
    public function getTotalBarangAttribute()
    {
        return Barang::whereHas('ruangan', function($query) {
            $query->where('gedung_id', $this->id);
        })->sum('jumlah');
    }

    /**
     * Generate kode gedung otomatis dengan format GD01, GD02, dst
     * @return string
     */
    public static function generateKodeGedung()
    {
        // Ambil kode gedung terakhir
        $lastGedung = self::orderBy('kode_gedung', 'desc')->first();
        
        if (!$lastGedung) {
            return 'GD01';
        }
        
        // Extract nomor dari kode terakhir (GD01 -> 01)
        $lastNumber = (int) substr($lastGedung->kode_gedung, 2);
        $newNumber = $lastNumber + 1;
        
        // Format menjadi GD01, GD02, dst
        return 'GD' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }
}
