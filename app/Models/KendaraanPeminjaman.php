<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'kendaraan_id',
        'nik',
        'nama_peminjam',
        'jabatan',
        'departemen',
        'no_hp',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tujuan_penggunaan',
        'keperluan',
        'jumlah_penumpang',
        'status_pengajuan',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'catatan_persetujuan',
        'waktu_ambil',
        'waktu_kembali_actual',
        'km_awal',
        'km_akhir',
        'kondisi_ambil',
        'kondisi_kembali',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'waktu_ambil' => 'datetime',
        'waktu_kembali_actual' => 'datetime',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    public function scopePending($query)
    {
        return $query->where('status_pengajuan', 'Pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_pengajuan', 'Disetujui');
    }
}
