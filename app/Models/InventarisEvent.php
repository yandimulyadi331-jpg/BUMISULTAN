<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventarisEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventaris_events';

    protected $fillable = [
        'kode_event',
        'nama_event',
        'deskripsi_event',
        'jenis_event',
        'tanggal_event',
        'tanggal_selesai',
        'lokasi_event',
        'pic_id',
        'status',
        'jumlah_peserta',
        'daftar_inventaris',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_event' => 'date',
        'tanggal_selesai' => 'date',
        'daftar_inventaris' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_event)) {
                $model->kode_event = self::generateKodeEvent();
            }
        });
    }

    public static function generateKodeEvent()
    {
        $lastEvent = self::withTrashed()->latest('id')->first();
        $number = $lastEvent ? intval(substr($lastEvent->kode_event, 4)) + 1 : 1;
        return 'EVT-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function eventItems()
    {
        return $this->hasMany(InventarisEventItem::class, 'inventaris_event_id');
    }

    public function inventaris()
    {
        return $this->belongsToMany(Inventaris::class, 'inventaris_event_items', 'inventaris_event_id', 'inventaris_id')
            ->withPivot('jumlah_dibutuhkan', 'jumlah_tersedia', 'status', 'keterangan')
            ->withTimestamps();
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanInventaris::class, 'inventaris_event_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function addInventaris($inventarisId, $jumlah, $keterangan = null)
    {
        return InventarisEventItem::create([
            'inventaris_event_id' => $this->id,
            'inventaris_id' => $inventarisId,
            'jumlah_dibutuhkan' => $jumlah,
            'keterangan' => $keterangan,
            'status' => 'menunggu',
        ]);
    }

    public function cekKetersediaanInventaris()
    {
        $results = [];
        foreach ($this->eventItems as $item) {
            $tersedia = $item->inventaris->jumlahTersedia();
            $item->update([
                'jumlah_tersedia' => $tersedia,
                'status' => $tersedia >= $item->jumlah_dibutuhkan ? 'tersedia' : 'menunggu',
            ]);
            $results[] = [
                'inventaris' => $item->inventaris->nama_barang,
                'dibutuhkan' => $item->jumlah_dibutuhkan,
                'tersedia' => $tersedia,
                'status' => $item->status,
            ];
        }
        return $results;
    }

    public function distribusiKeKaryawan($karyawanIds)
    {
        $distributed = [];
        foreach ($this->eventItems as $item) {
            if ($item->status === 'tersedia') {
                foreach ($karyawanIds as $karyawanId) {
                    $peminjaman = PeminjamanInventaris::create([
                        'inventaris_id' => $item->inventaris_id,
                        'karyawan_id' => $karyawanId,
                        'jumlah_pinjam' => 1, // 1 unit per karyawan
                        'tanggal_pinjam' => $this->tanggal_event,
                        'tanggal_kembali_rencana' => $this->tanggal_selesai ?? $this->tanggal_event->addDays(1),
                        'keperluan' => 'Event: ' . $this->nama_event,
                        'status' => 'dipinjam',
                        'inventaris_event_id' => $this->id,
                        'created_by' => auth()->id(),
                    ]);
                    $distributed[] = $peminjaman;
                }
                $item->update(['status' => 'terdistribusi']);
            }
        }
        return $distributed;
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['persiapan', 'berlangsung']);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_event', $jenis);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_event', '>=', now()->toDateString())
            ->orderBy('tanggal_event', 'asc');
    }
}
