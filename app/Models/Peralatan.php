<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    protected $table = 'peralatan';

    protected $fillable = [
        'kode_peralatan',
        'nama_peralatan',
        'kategori',
        'deskripsi',
        'stok_awal',
        'stok_tersedia',
        'stok_dipinjam',
        'stok_rusak',
        'satuan',
        'lokasi_penyimpanan',
        'kondisi',
        'harga_satuan',
        'tanggal_pembelian',
        'supplier',
        'stok_minimum',
        'foto',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'harga_satuan' => 'decimal:2',
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanPeralatan::class, 'peralatan_id');
    }

    // Helper untuk cek stok tersedia
    public function isStokTersedia($jumlah)
    {
        return $this->stok_tersedia >= $jumlah;
    }

    // Helper untuk cek stok minimum
    public function isStokMenipis()
    {
        return $this->stok_tersedia <= $this->stok_minimum;
    }

    // Hitung total stok
    public function getTotalStokAttribute()
    {
        return $this->stok_tersedia + $this->stok_dipinjam + $this->stok_rusak;
    }

    // Generate kode peralatan otomatis
    public static function generateKodePeralatan()
    {
        $prefix = "PRL-";
        
        // Cari kode terakhir dengan prefix yang sama
        $lastKode = self::where('kode_peralatan', 'LIKE', "{$prefix}%")
                        ->orderBy('kode_peralatan', 'desc')
                        ->first();
        
        if ($lastKode) {
            // Ambil nomor urut dari kode terakhir
            $lastNumber = (int) substr($lastKode->kode_peralatan, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format nomor dengan 3 digit
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
