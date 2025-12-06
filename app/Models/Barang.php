<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';
    
    protected $fillable = [
        'kode_barang',
        'ruangan_id',
        'nama_barang',
        'kategori',
        'merk',
        'jumlah',
        'satuan',
        'kondisi',
        'tanggal_perolehan',
        'harga_perolehan',
        'keterangan',
        'foto'
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan' => 'decimal:2'
    ];

    /**
     * Relasi ke Ruangan
     * Setiap barang berada di satu ruangan
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    /**
     * Relasi ke Transfer Barang
     */
    public function transferBarangs()
    {
        return $this->hasMany(TransferBarang::class, 'barang_id');
    }

    /**
     * Get gedung dari barang melalui ruangan
     */
    public function getGedungAttribute()
    {
        return $this->ruangan ? $this->ruangan->gedung : null;
    }

    /**
     * Generate kode barang otomatis berdasarkan ruangan
     * Format: GD01-RU01-BR01, GD01-RU01-BR02, GD01-RU02-BR01, dst
     * @param int $ruangan_id
     * @return string
     */
    public static function generateKodeBarang($ruangan_id)
    {
        // Ambil ruangan untuk mendapatkan kode ruangan
        $ruangan = Ruangan::with('gedung')->findOrFail($ruangan_id);
        
        // Ambil barang terakhir di ruangan ini
        $lastBarang = self::where('ruangan_id', $ruangan_id)
            ->orderBy('kode_barang', 'desc')
            ->first();
        
        if (!$lastBarang) {
            // Barang pertama di ruangan ini
            return $ruangan->kode_ruangan . '-BR01';
        }
        
        // Extract nomor dari kode terakhir (GD01-RU01-BR01 -> 01)
        $parts = explode('-BR', $lastBarang->kode_barang);
        $lastNumber = (int) end($parts);
        $newNumber = $lastNumber + 1;
        
        // Format menjadi GD01-RU01-BR01, GD01-RU01-BR02, dst
        return $ruangan->kode_ruangan . '-BR' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }
}
