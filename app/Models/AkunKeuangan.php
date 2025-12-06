<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunKeuangan extends Model
{
    use HasFactory;

    protected $table = 'akun_keuangan';
    
    protected $fillable = [
        'kategori_id',
        'kode_akun',
        'nama_akun',
        'deskripsi',
        'posisi_normal',
        'parent_id',
        'level',
        'saldo_awal',
        'saldo_berjalan',
        'is_active',
        'is_kas_bank',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
        'saldo_berjalan' => 'decimal:2',
        'is_active' => 'boolean',
        'is_kas_bank' => 'boolean',
        'level' => 'integer',
    ];

    /**
     * Get the kategori that owns the akun
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriAkunKeuangan::class, 'kategori_id');
    }

    /**
     * Get parent akun (untuk sub-akun)
     */
    public function parent()
    {
        return $this->belongsTo(AkunKeuangan::class, 'parent_id');
    }

    /**
     * Get child akun (sub-akun)
     */
    public function children()
    {
        return $this->hasMany(AkunKeuangan::class, 'parent_id');
    }

    /**
     * Get all transaksi debit
     */
    public function transaksiDebit()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'akun_debit_id');
    }

    /**
     * Get all transaksi kredit
     */
    public function transaksiKredit()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'akun_kredit_id');
    }

    /**
     * Get all jurnal entries
     */
    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'akun_keuangan_id');
    }

    /**
     * Get kas bank jika akun ini adalah kas/bank
     */
    public function kasBank()
    {
        return $this->hasOne(KasBank::class, 'akun_keuangan_id');
    }

    /**
     * Get anggaran for this akun
     */
    public function anggaran()
    {
        return $this->hasMany(Anggaran::class, 'akun_keuangan_id');
    }

    /**
     * Scope untuk akun aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk akun kas/bank
     */
    public function scopeKasBank($query)
    {
        return $query->where('is_kas_bank', true);
    }

    /**
     * Scope untuk root level akun (tidak punya parent)
     */
    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Update saldo berjalan
     */
    public function updateSaldo($jumlah, $tipe = 'debit')
    {
        if ($this->posisi_normal === $tipe) {
            $this->saldo_berjalan += $jumlah;
        } else {
            $this->saldo_berjalan -= $jumlah;
        }
        $this->save();
    }

    /**
     * Get full kode akun dengan parent
     */
    public function getFullKodeAttribute()
    {
        if ($this->parent) {
            return $this->parent->full_kode . '.' . $this->kode_akun;
        }
        return $this->kode_akun;
    }

    /**
     * Get full nama akun dengan parent
     */
    public function getFullNamaAttribute()
    {
        if ($this->parent) {
            return $this->parent->full_nama . ' - ' . $this->nama_akun;
        }
        return $this->nama_akun;
    }
}
