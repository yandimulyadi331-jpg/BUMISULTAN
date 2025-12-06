<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganSantriCategory extends Model
{
    use HasFactory;

    protected $table = 'keuangan_santri_categories';

    protected $fillable = [
        'nama_kategori',
        'jenis',
        'keywords',
        'icon',
        'color',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke transaksi
     */
    public function transactions()
    {
        return $this->hasMany(KeuanganSantriTransaction::class, 'category_id');
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kategori pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    /**
     * Scope untuk kategori pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    /**
     * Auto-detect kategori berdasarkan deskripsi
     */
    public static function detectCategory($deskripsi, $jenis = 'pengeluaran')
    {
        $deskripsi = strtolower($deskripsi);
        
        $categories = self::where('jenis', $jenis)
            ->where('is_active', true)
            ->get();

        foreach ($categories as $category) {
            if ($category->keywords) {
                foreach ($category->keywords as $keyword) {
                    if (stripos($deskripsi, strtolower($keyword)) !== false) {
                        return $category;
                    }
                }
            }
        }

        // Return default category jika tidak ditemukan
        return self::where('jenis', $jenis)
            ->where('nama_kategori', 'LIKE', '%lain%')
            ->first();
    }
}
