<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanPeralatan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_peralatan';

    protected $fillable = [
        'nomor_peminjaman',
        'peralatan_id',
        'nama_peminjam',
        'jumlah_dipinjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'keperluan',
        'status',
        'kondisi_saat_dipinjam',
        'kondisi_saat_dikembalikan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    // Relasi ke peralatan
    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'peralatan_id');
    }

    // Helper untuk cek terlambat
    public function isTerlambat()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }
        return now()->gt($this->tanggal_kembali_rencana);
    }

    // Generate nomor peminjaman otomatis
    public static function generateNomorPeminjaman()
    {
        $lastPeminjaman = self::latest()->first();
        $lastNumber = $lastPeminjaman ? intval(substr($lastPeminjaman->nomor_peminjaman, -4)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return 'PMP-' . date('Ymd') . '-' . $newNumber;
    }
}
