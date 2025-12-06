<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UndianUmrohMasar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'undian_umroh_masar';

    protected $fillable = [
        'nomor_undian',
        'nama_program',
        'deskripsi',
        'tanggal_undian',
        'periode_keberangkatan_dari',
        'periode_keberangkatan_sampai',
        'jumlah_pemenang',
        'minimal_kehadiran',
        'status_undian',
        'syarat_ketentuan',
        'biaya_program',
        'sponsor',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_undian' => 'date',
        'periode_keberangkatan_dari' => 'date',
        'periode_keberangkatan_sampai' => 'date',
        'jumlah_pemenang' => 'integer',
        'minimal_kehadiran' => 'integer',
        'biaya_program' => 'decimal:2',
    ];

    /**
     * Generate nomor undian otomatis
     * Format: UUM-TAHUN-URUT (UUM = Undian Umroh Masar)
     */
    public static function generateNomorUndian()
    {
        $tahun = date('Y');
        $urut = str_pad(self::whereYear('created_at', $tahun)->count() + 1, 4, '0', STR_PAD_LEFT);
        
        return "UUM-{$tahun}-{$urut}";
    }

    /**
     * Relationship dengan pemenang
     */
    public function pemenang()
    {
        return $this->hasMany(PemenangUndianUmrohMasar::class, 'undian_id');
    }

    /**
     * Get jamaah yang memenuhi syarat untuk ikut undian
     */
    public function jamaahMemenuhi()
    {
        return JamaahMasar::where('jumlah_kehadiran', '>=', $this->minimal_kehadiran)
                          ->where('status_aktif', 'aktif')
                          ->get();
    }

    /**
     * Scope untuk undian aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_undian', 'aktif');
    }

    /**
     * Scope untuk undian selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status_undian', 'selesai');
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'aktif' => '<span class="badge bg-success">Aktif</span>',
            'selesai' => '<span class="badge bg-primary">Selesai</span>',
            'batal' => '<span class="badge bg-danger">Batal</span>',
        ];

        return $badges[$this->status_undian] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
