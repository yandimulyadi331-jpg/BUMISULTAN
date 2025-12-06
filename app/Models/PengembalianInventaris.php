<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PengembalianInventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengembalian_inventaris';

    protected $fillable = [
        'kode_pengembalian',
        'peminjaman_inventaris_id',
        'tanggal_pengembalian',
        'jumlah_kembali',
        'kondisi_barang',
        'terlambat',
        'hari_keterlambatan',
        'denda',
        'foto_pengembalian',
        'ttd_peminjam',
        'ttd_petugas',
        'keterangan',
        'catatan_kerusakan',
        'diterima_oleh',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'datetime',
        'terlambat' => 'boolean',
        'denda' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_pengembalian)) {
                $model->kode_pengembalian = self::generateKodePengembalian();
            }

            // Auto calculate keterlambatan
            $peminjaman = $model->peminjaman;
            if ($model->tanggal_pengembalian->gt($peminjaman->tanggal_kembali_rencana)) {
                $model->terlambat = true;
                $model->hari_keterlambatan = $peminjaman->tanggal_kembali_rencana->diffInDays($model->tanggal_pengembalian);
                $model->denda = $model->hari_keterlambatan * 10000; // Tarif denda per hari
            }
        });

        // Log history saat create
        static::created(function ($model) {
            $peminjaman = $model->peminjaman;
            $inventaris = $peminjaman->inventaris;

            // Update peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali_realisasi' => $model->tanggal_pengembalian,
            ]);

            // Update status inventaris jika sudah dikembalikan semua
            $jumlahDipinjam = $inventaris->peminjamanAktif()->sum('jumlah_pinjam');
            if ($jumlahDipinjam <= 0) {
                $inventaris->update(['status' => 'tersedia']);
            }

            // Update kondisi inventaris jika ada kerusakan
            if ($model->kondisi_barang !== 'baik') {
                $inventaris->update(['kondisi' => $model->kondisi_barang]);
                if ($model->kondisi_barang === 'rusak_berat') {
                    $inventaris->update(['status' => 'rusak']);
                }
            }

            // Log history
            $namaPeminjam = $peminjaman->karyawan ? $peminjaman->karyawan->nama_lengkap : $peminjaman->nama_peminjam;
            $inventaris->logHistory('pengembalian', 'Barang dikembalikan oleh ' . $namaPeminjam, [
                'jumlah' => $model->jumlah_kembali,
                'karyawan_id' => $peminjaman->karyawan_id,
                'peminjaman_id' => $peminjaman->id,
                'pengembalian_id' => $model->id,
                'foto' => $model->foto_pengembalian,
            ]);
        });
    }

    public static function generateKodePengembalian()
    {
        $lastPengembalian = self::withTrashed()->latest('id')->first();
        $number = $lastPengembalian ? intval(substr($lastPengembalian->kode_pengembalian, 4)) + 1 : 1;
        return 'KMB-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanInventaris::class, 'peminjaman_inventaris_id');
    }

    public function detailUnit()
    {
        return $this->hasOneThrough(
            InventarisDetailUnit::class,
            PeminjamanInventaris::class,
            'id', // Foreign key on peminjaman_inventaris
            'id', // Foreign key on inventaris_detail_units
            'peminjaman_inventaris_id', // Local key on pengembalian_inventaris
            'inventaris_detail_unit_id' // Local key on peminjaman_inventaris
        );
    }

    public function diterimаOleh()
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function statusKondisi()
    {
        $status = [
            'baik' => ['text' => 'Baik', 'class' => 'success'],
            'rusak_ringan' => ['text' => 'Rusak Ringan', 'class' => 'warning'],
            'rusak_berat' => ['text' => 'Rusak Berat', 'class' => 'danger'],
            'hilang' => ['text' => 'Hilang', 'class' => 'danger'],
        ];

        return $status[$this->kondisi_barang] ?? ['text' => 'Unknown', 'class' => 'secondary'];
    }

    // Scopes
    public function scopeTerlambat($query)
    {
        return $query->where('terlambat', true);
    }

    public function scopeByPeminjaman($query, $peminjamanId)
    {
        return $query->where('peminjaman_inventaris_id', $peminjamanId);
    }
}
