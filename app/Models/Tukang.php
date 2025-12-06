<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tukang extends Model
{
    use HasFactory;
    
    protected $table = "tukangs";
    protected $primaryKey = "id";
    
    protected $fillable = [
        'kode_tukang',
        'nama_tukang',
        'nik',
        'alamat',
        'no_hp',
        'email',
        'keahlian',
        'status',
        'auto_potong_pinjaman',
        'tarif_harian',
        'keterangan',
        'foto'
    ];
    
    protected $casts = [
        'tarif_harian' => 'decimal:2',
        'auto_potong_pinjaman' => 'boolean',
    ];
    
    /**
     * Relasi ke kehadiran tukang
     */
    public function kehadiran()
    {
        return $this->hasMany(KehadiranTukang::class, 'tukang_id');
    }
    
    /**
     * Kehadiran bulan ini
     */
    public function kehadiranBulanIni()
    {
        return $this->hasMany(KehadiranTukang::class, 'tukang_id')
                    ->whereYear('tanggal', date('Y'))
                    ->whereMonth('tanggal', date('m'));
    }
    
    /**
     * Relasi ke keuangan tukang
     */
    public function keuangan()
    {
        return $this->hasMany(KeuanganTukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke pinjaman tukang
     */
    public function pinjaman()
    {
        return $this->hasMany(PinjamanTukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke pinjaman tukang (alias plural)
     */
    public function pinjamans()
    {
        return $this->hasMany(PinjamanTukang::class, 'tukang_id');
    }
    
    /**
     * Relasi ke pinjaman aktif
     */
    public function pinjamanAktif()
    {
        return $this->hasMany(PinjamanTukang::class, 'tukang_id')->aktif();
    }
    
    /**
     * Relasi ke potongan tukang
     */
    public function potongan()
    {
        return $this->hasMany(PotonganTukang::class, 'tukang_id');
    }
}
