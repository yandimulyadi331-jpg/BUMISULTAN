<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'kode_transaksi',
        'jenis_barang',
        'nama_barang',
        'deskripsi',
        'jumlah',
        'satuan',
        'pemilik_barang',
        'departemen',
        'no_telp_pemilik',
        'nama_vendor',
        'alamat_vendor',
        'no_telp_vendor',
        'pic_vendor',
        'tanggal_keluar',
        'estimasi_kembali',
        'tanggal_kembali',
        'estimasi_biaya',
        'biaya_aktual',
        'status',
        'kondisi_keluar',
        'kondisi_kembali',
        'foto_sebelum',
        'foto_sesudah',
        'foto_nota',
        'catatan_keluar',
        'catatan_kembali',
        'catatan_vendor',
        'prioritas',
        'rating_vendor',
        'review_vendor',
        'created_by',
        'updated_by',
        'diambil_by',
    ];

    protected $casts = [
        'foto_sebelum' => 'array',
        'foto_sesudah' => 'array',
        'tanggal_keluar' => 'datetime',
        'estimasi_kembali' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'biaya_aktual' => 'decimal:2',
        'rating_vendor' => 'integer',
        'jumlah' => 'integer',
    ];

    // Relasi ke User yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke User yang update
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke User yang mengambil
    public function pengambil()
    {
        return $this->belongsTo(User::class, 'diambil_by');
    }

    // Relasi ke history
    public function histories()
    {
        return $this->hasMany(BarangKeluarHistory::class, 'barang_keluar_id');
    }

    // Relasi ke reminder
    public function reminders()
    {
        return $this->hasMany(BarangKeluarReminder::class, 'barang_keluar_id');
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-secondary">Pending</span>',
            'dikirim' => '<span class="badge bg-info">Dikirim</span>',
            'proses' => '<span class="badge bg-warning">Proses</span>',
            'selesai_vendor' => '<span class="badge bg-primary">Selesai Vendor</span>',
            'diambil' => '<span class="badge bg-success">Diambil</span>',
            'batal' => '<span class="badge bg-danger">Batal</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>';
    }

    // Accessor untuk badge prioritas
    public function getPrioritasBadgeAttribute()
    {
        $badges = [
            'rendah' => '<span class="badge bg-success">Rendah</span>',
            'normal' => '<span class="badge bg-info">Normal</span>',
            'tinggi' => '<span class="badge bg-warning">Tinggi</span>',
            'urgent' => '<span class="badge bg-danger">Urgent</span>',
        ];

        return $badges[$this->prioritas] ?? '<span class="badge bg-info">' . ucfirst($this->prioritas) . '</span>';
    }

    // Accessor untuk durasi (berapa hari di luar)
    public function getDurasiHariAttribute()
    {
        if (!$this->tanggal_keluar) {
            return 0;
        }

        $tanggalAkhir = $this->tanggal_kembali ?? now();
        return $this->tanggal_keluar->diffInDays($tanggalAkhir);
    }

    // Accessor untuk cek apakah terlambat
    public function getIsTerlambatAttribute()
    {
        if (!$this->estimasi_kembali || $this->tanggal_kembali) {
            return false;
        }

        return now()->isAfter($this->estimasi_kembali);
    }

    // Accessor untuk hari terlambat
    public function getHariTerlambatAttribute()
    {
        if (!$this->is_terlambat) {
            return 0;
        }

        return $this->estimasi_kembali->diffInDays(now());
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk barang yang belum kembali
    public function scopeBelumKembali($query)
    {
        return $query->whereIn('status', ['pending', 'dikirim', 'proses', 'selesai_vendor']);
    }

    // Scope untuk barang yang terlambat
    public function scopeTerlambat($query)
    {
        return $query->whereIn('status', ['pending', 'dikirim', 'proses', 'selesai_vendor'])
            ->where('estimasi_kembali', '<', now());
    }

    // Scope untuk filter berdasarkan jenis barang
    public function scopeJenisBarang($query, $jenis)
    {
        return $query->where('jenis_barang', $jenis);
    }

    // Scope untuk filter berdasarkan vendor
    public function scopeVendor($query, $vendor)
    {
        return $query->where('nama_vendor', 'like', '%' . $vendor . '%');
    }

    // Method untuk generate kode transaksi
    public static function generateKodeTransaksi()
    {
        $prefix = 'BK';
        $date = date('Ymd');
        
        // Cari kode transaksi terakhir hari ini
        $count = self::whereDate('created_at', today())->count();
        
        // Tambahkan 1 untuk nomor baru
        $number = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        
        // Tambahkan random string untuk memastikan unique
        $random = strtoupper(substr(uniqid(), -3));
        
        return $prefix . $date . $number . $random;
    }

    // Method untuk update status dengan log history
    public function updateStatus($statusBaru, $catatan = null, $userId = null)
    {
        $statusLama = $this->status;

        // Update status
        $this->status = $statusBaru;
        $this->updated_by = $userId ?? auth()->id();

        // Jika status diambil, set tanggal kembali
        if ($statusBaru === 'diambil' && !$this->tanggal_kembali) {
            $this->tanggal_kembali = now();
            $this->diambil_by = $userId ?? auth()->id();
        }

        $this->save();

        // Catat ke history
        BarangKeluarHistory::create([
            'barang_keluar_id' => $this->id,
            'status_dari' => $statusLama,
            'status_ke' => $statusBaru,
            'catatan' => $catatan,
            'user_id' => $userId ?? auth()->id(),
        ]);

        return $this;
    }

    // Boot method untuk auto generate kode transaksi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $model->kode_transaksi = self::generateKodeTransaksi();
            }
            if (empty($model->created_by)) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (empty($model->updated_by)) {
                $model->updated_by = auth()->id();
            }
        });
    }
}
