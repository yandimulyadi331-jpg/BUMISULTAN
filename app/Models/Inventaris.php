<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventaris';

    protected $fillable = [
        'kode_inventaris',
        'nama_barang',
        'deskripsi',
        'kategori',
        'barang_id',
        'merk',
        'tipe_model',
        'nomor_seri',
        'jumlah',
        'jumlah_unit',
        'tracking_per_unit',
        'satuan',
        'harga_perolehan',
        'tanggal_perolehan',
        'kondisi',
        'status',
        'lokasi_penyimpanan',
        'cabang_id',
        'foto',
        'spesifikasi',
        'masa_pakai_bulan',
        'tanggal_kadaluarsa',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'harga_perolehan' => 'decimal:2',
        'spesifikasi' => 'array',
        'tracking_per_unit' => 'boolean',
    ];

    // Generate kode inventaris otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_inventaris)) {
                $model->kode_inventaris = self::generateKodeInventaris();
            }
        });

        // Log history saat create
        static::created(function ($model) {
            $model->logHistory('input', 'Inventaris baru ditambahkan');
            
            // Auto create detail units berdasarkan jumlah
            if ($model->jumlah > 0) {
                for ($i = 1; $i <= $model->jumlah; $i++) {
                    \App\Models\InventarisDetailUnit::create([
                        'inventaris_id' => $model->id,
                        'kode_unit' => $model->kode_inventaris . '-U' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'nomor_seri_unit' => $model->nomor_seri ?? null,
                        'kondisi' => $model->kondisi,
                        'status' => 'tersedia',
                        'lokasi_saat_ini' => $model->lokasi_penyimpanan,
                        'tanggal_perolehan' => $model->tanggal_perolehan,
                        'harga_perolehan' => $model->harga_perolehan,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        });

        // Log history saat update
        static::updated(function ($model) {
            if ($model->isDirty('status')) {
                $model->logHistory('update', 'Status diubah dari ' . $model->getOriginal('status') . ' ke ' . $model->status);
            }
            if ($model->isDirty('lokasi_penyimpanan')) {
                $model->logHistory('pindah_lokasi', 'Lokasi dipindah dari ' . $model->getOriginal('lokasi_penyimpanan') . ' ke ' . $model->lokasi_penyimpanan);
            }
        });
    }

    public static function generateKodeInventaris()
    {
        $lastInventaris = self::withTrashed()->latest('id')->first();
        $number = $lastInventaris ? intval(substr($lastInventaris->kode_inventaris, 3)) + 1 : 1;
        return 'INV' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanInventaris::class, 'inventaris_id');
    }

    public function peminjamanAktif()
    {
        return $this->hasMany(PeminjamanInventaris::class, 'inventaris_id')
            ->where('status_peminjaman', 'disetujui')
            ->whereDoesntHave('pengembalian');
    }

    public function units()
    {
        return $this->hasMany(InventarisUnit::class);
    }

    public function detailUnits()
    {
        return $this->hasMany(InventarisDetailUnit::class);
    }

    public function detailUnitsTersedia()
    {
        return $this->hasMany(InventarisDetailUnit::class)->where('status', 'tersedia');
    }

    public function detailUnitsDipinjam()
    {
        return $this->hasMany(InventarisDetailUnit::class)->where('status', 'dipinjam');
    }

    public function histories()
    {
        return $this->hasMany(HistoryInventaris::class, 'inventaris_id');
    }

    public function eventItems()
    {
        return $this->hasMany(InventarisEventItem::class, 'inventaris_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper methods
    public function isTersedia()
    {
        return $this->status === 'tersedia';
    }

    public function jumlahTersedia()
    {
        // Jika tracking per unit, hitung dari detail units
        if ($this->tracking_per_unit) {
            return $this->detailUnitsTersedia()->count();
        }

        // Logic lama untuk barang tanpa tracking unit
        $dipinjam = $this->peminjamanAktif()->sum('jumlah_pinjam');
        return max(0, $this->jumlah - $dipinjam);
    }

    public function getTotalUnits()
    {
        if ($this->tracking_per_unit) {
            return $this->detailUnits()->count();
        }
        return $this->jumlah;
    }

    public function getJumlahDipinjam()
    {
        if ($this->tracking_per_unit) {
            return $this->detailUnitsDipinjam()->count();
        }
        return $this->peminjamanAktif()->sum('jumlah_pinjam');
    }

    public function getJumlahRusak()
    {
        if ($this->tracking_per_unit) {
            return $this->detailUnits()->where('status', 'rusak')->count();
        }
        return 0;
    }

    public function getJumlahMaintenance()
    {
        if ($this->tracking_per_unit) {
            return $this->detailUnits()->where('status', 'maintenance')->count();
        }
        return 0;
    }

    public function getKondisiDominan()
    {
        if (!$this->tracking_per_unit) {
            return $this->kondisi;
        }

        // Hitung kondisi dominan dari detail units
        $kondisi = $this->detailUnits()
            ->select('kondisi', \DB::raw('count(*) as total'))
            ->groupBy('kondisi')
            ->orderBy('total', 'desc')
            ->first();

        return $kondisi ? $kondisi->kondisi : 'baik';
    }

    public function logHistory($jenis, $deskripsi, $data = [])
    {
        return HistoryInventaris::create([
            'inventaris_id' => $this->id,
            'jenis_aktivitas' => $jenis,
            'deskripsi' => $deskripsi,
            'status_sebelum' => $data['status_sebelum'] ?? null,
            'status_sesudah' => $data['status_sesudah'] ?? null,
            'lokasi_sebelum' => $data['lokasi_sebelum'] ?? null,
            'lokasi_sesudah' => $data['lokasi_sesudah'] ?? null,
            'jumlah' => $data['jumlah'] ?? null,
            'karyawan_id' => $data['karyawan_id'] ?? null,
            'peminjaman_id' => $data['peminjaman_id'] ?? null,
            'pengembalian_id' => $data['pengembalian_id'] ?? null,
            'data_perubahan' => $data['data_perubahan'] ?? null,
            'foto' => $data['foto'] ?? null,
            'user_id' => auth()->id(),
        ]);
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByCabang($query, $cabangId)
    {
        return $query->where('cabang_id', $cabangId);
    }
}
