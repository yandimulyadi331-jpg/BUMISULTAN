<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PeminjamanInventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peminjaman_inventaris';

    protected $fillable = [
        'kode_peminjaman',
        'inventaris_id',
        'inventaris_detail_unit_id',
        'karyawan_id',
        'nama_peminjam',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'keperluan',
        'status_peminjaman',
        'foto_barang',
        'ttd_peminjam',
        'ttd_petugas',
        'disetujui_oleh',
        'catatan_peminjaman',
        'catatan_approval',
        'inventaris_event_id',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali_rencana' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_peminjaman)) {
                $model->kode_peminjaman = self::generateKodePeminjaman();
            }
        });

        // Log history saat create
        static::created(function ($model) {
            $namaPeminjam = $model->nama_peminjam ?? ($model->karyawan ? $model->karyawan->nama_lengkap : 'Unknown');
            
            $model->inventaris->logHistory('peminjaman', 'Barang dipinjam oleh ' . $namaPeminjam, [
                'jumlah' => $model->jumlah_pinjam,
                'karyawan_id' => $model->karyawan_id,
                'peminjaman_id' => $model->id,
            ]);
        });

        // Auto update status terlambat
        static::updating(function ($model) {
            if ($model->status === 'dipinjam' && Carbon::now()->gt($model->tanggal_kembali_rencana)) {
                $model->status = 'terlambat';
            }
        });
    }

    public static function generateKodePeminjaman()
    {
        $lastPeminjaman = self::withTrashed()->latest('id')->first();
        $number = $lastPeminjaman ? intval(substr($lastPeminjaman->kode_peminjaman, 4)) + 1 : 1;
        return 'PJM-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function pengembalian()
    {
        return $this->hasOne(PengembalianInventaris::class, 'peminjaman_inventaris_id');
    }

    public function detailUnit()
    {
        return $this->belongsTo(InventarisDetailUnit::class, 'inventaris_detail_unit_id');
    }

    public function event()
    {
        return $this->belongsTo(InventarisEvent::class, 'inventaris_event_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function isTerlambat()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }
        return Carbon::now()->gt($this->tanggal_kembali_rencana);
    }

    public function hariKeterlambatan()
    {
        if (!$this->isTerlambat()) {
            return 0;
        }
        // Gunakan tanggal pengembalian dari relasi, atau hari ini jika belum dikembalikan
        $tanggalAkhir = $this->pengembalian ? 
            $this->pengembalian->tanggal_pengembalian : 
            Carbon::now();
        return $this->tanggal_kembali_rencana->diffInDays($tanggalAkhir);
    }

    public function hitungDenda($tarifPerHari = 10000)
    {
        return $this->hariKeterlambatan() * $tarifPerHari;
    }

    public function setujui($userId, $catatan = null)
    {
        $this->update([
            'status' => 'disetujui',
            'disetujui_oleh' => $userId,
            'tanggal_approval' => Carbon::now(),
            'catatan_approval' => $catatan,
        ]);
    }

    public function tolak($userId, $catatan)
    {
        $this->update([
            'status' => 'ditolak',
            'disetujui_oleh' => $userId,
            'tanggal_approval' => Carbon::now(),
            'catatan_approval' => $catatan,
        ]);
    }

    public function prosesPeminjaman()
    {
        $this->update(['status' => 'dipinjam']);
        
        // Update status inventaris jika semua unit dipinjam
        $inventaris = $this->inventaris;
        if ($inventaris->jumlahTersedia() <= 0) {
            $inventaris->update(['status' => 'dipinjam']);
        }
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['disetujui', 'dipinjam', 'terlambat']);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'dipinjam')
                  ->where('tanggal_kembali_rencana', '<', Carbon::now());
            });
    }

    public function scopeByKaryawan($query, $karyawanId)
    {
        return $query->where('karyawan_id', $karyawanId);
    }

    public function scopeByEvent($query, $eventId)
    {
        return $query->where('inventaris_event_id', $eventId);
    }
}
