<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';
    
    protected $fillable = [
        'kode_ruangan',
        'gedung_id',
        'nama_ruangan',
        'lantai',
        'luas',
        'kapasitas',
        'keterangan',
        'foto'
    ];

    /**
     * Relasi ke Gedung
     * Setiap ruangan berada di satu gedung
     */
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }

    /**
     * Relasi ke Barang
     * Satu ruangan memiliki banyak barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'ruangan_id');
    }

    /**
     * Get total barang di ruangan ini
     */
    public function getTotalBarangAttribute()
    {
        return $this->barangs()->sum('jumlah');
    }

    /**
     * Relasi transfer barang sebagai ruangan asal
     */
    public function transferBarangAsal()
    {
        return $this->hasMany(TransferBarang::class, 'ruangan_asal_id');
    }

    /**
     * Relasi transfer barang sebagai ruangan tujuan
     */
    public function transferBarangTujuan()
    {
        return $this->hasMany(TransferBarang::class, 'ruangan_tujuan_id');
    }

    /**
     * Generate kode ruangan otomatis berdasarkan gedung
     * Format: GD01-RU01, GD01-RU02, GD02-RU01, dst
     * @param int $gedung_id
     * @return string
     */
    public static function generateKodeRuangan($gedung_id)
    {
        // Ambil gedung untuk mendapatkan kode gedung
        $gedung = Gedung::findOrFail($gedung_id);
        
        // Ambil ruangan terakhir di gedung ini
        $lastRuangan = self::where('gedung_id', $gedung_id)
            ->orderBy('kode_ruangan', 'desc')
            ->first();
        
        if (!$lastRuangan) {
            // Ruangan pertama di gedung ini
            return $gedung->kode_gedung . '-RU01';
        }
        
        // Extract nomor dari kode terakhir (GD01-RU01 -> 01)
        $parts = explode('-RU', $lastRuangan->kode_ruangan);
        $lastNumber = (int) end($parts);
        $newNumber = $lastNumber + 1;
        
        // Format menjadi GD01-RU01, GD01-RU02, dst
        return $gedung->kode_gedung . '-RU' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }
}
