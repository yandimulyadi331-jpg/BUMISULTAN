<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventarisDetailUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'inventaris_id',
        'inventaris_unit_id',
        'kode_unit',
        'nomor_seri_unit',
        'kondisi',
        'status',
        'lokasi_saat_ini',
        'tanggal_perolehan',
        'harga_perolehan',
        'dipinjam_oleh',
        'tanggal_pinjam',
        'peminjaman_inventaris_id',
        'foto_unit',
        'catatan_kondisi',
        'terakhir_maintenance',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_pinjam' => 'date',
        'terakhir_maintenance' => 'date',
        'harga_perolehan' => 'decimal:2',
    ];

    // Auto generate kode unit
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_unit)) {
                $model->kode_unit = self::generateKodeUnit($model->inventaris_id);
            }
        });

        // Log history saat create
        static::created(function ($model) {
            $model->logHistory('input', 'Unit baru ditambahkan ke sistem');
        });

        // Log history saat update
        static::updated(function ($model) {
            $changes = [];

            if ($model->isDirty('status')) {
                $changes[] = 'Status: ' . $model->getOriginal('status') . ' → ' . $model->status;
            }

            if ($model->isDirty('kondisi')) {
                $changes[] = 'Kondisi: ' . $model->getOriginal('kondisi') . ' → ' . $model->kondisi;
            }

            if ($model->isDirty('lokasi_saat_ini')) {
                $changes[] = 'Lokasi: ' . ($model->getOriginal('lokasi_saat_ini') ?? 'N/A') . ' → ' . $model->lokasi_saat_ini;
            }

            if (!empty($changes)) {
                $model->logHistory('update_kondisi', implode(', ', $changes));
            }
        });
    }

    public static function generateKodeUnit($inventarisId)
    {
        $inventaris = Inventaris::findOrFail($inventarisId);
        $lastUnit = self::where('inventaris_id', $inventarisId)
                        ->withTrashed()
                        ->latest('id')
                        ->first();

        $number = $lastUnit ? intval(substr($lastUnit->kode_unit, -3)) + 1 : 1;

        return $inventaris->kode_inventaris . '-U' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function inventarisUnit()
    {
        return $this->belongsTo(InventarisUnit::class);
    }

    public function peminjamanAktif()
    {
        return $this->belongsTo(PeminjamanInventaris::class, 'peminjaman_inventaris_id');
    }

    public function histories()
    {
        return $this->hasMany(InventarisUnitHistory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function scopeKondisiBaik($query)
    {
        return $query->where('kondisi', 'baik');
    }

    public function scopeByInventaris($query, $inventarisId)
    {
        return $query->where('inventaris_id', $inventarisId);
    }

    // Helper Methods
    public function isTersedia()
    {
        return $this->status === 'tersedia';
    }

    public function isDipinjam()
    {
        return $this->status === 'dipinjam';
    }

    public function getKondisiBadgeClass()
    {
        return match($this->kondisi) {
            'baik' => 'bg-success',
            'rusak_ringan' => 'bg-warning',
            'rusak_berat' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'tersedia' => 'bg-success',
            'dipinjam' => 'bg-warning',
            'maintenance' => 'bg-info',
            'rusak' => 'bg-danger',
            'hilang' => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    public function getKondisiLabel()
    {
        return match($this->kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => ucfirst($this->kondisi),
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'tersedia' => 'Tersedia',
            'dipinjam' => 'Dipinjam',
            'maintenance' => 'Maintenance',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            default => ucfirst($this->status),
        };
    }

    // Business Logic Methods
    public function setDipinjam($peminjaman)
    {
        $this->update([
            'status' => 'dipinjam',
            'dipinjam_oleh' => $peminjaman->nama_peminjam ?? 'Unknown',
            'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
            'peminjaman_inventaris_id' => $peminjaman->id,
            'updated_by' => auth()->id(),
        ]);

        $namaPeminjam = $peminjaman->nama_peminjam ?? 'Unknown';
        $keperluan = $peminjaman->keperluan ?? '-';
        $this->logHistory('pinjam', "Dipinjam oleh {$namaPeminjam} untuk: {$keperluan}", $peminjaman->id, 'peminjaman');

        return $this;
    }

    public function setDikembalikan($pengembalian)
    {
        $newStatus = 'tersedia';
        $newKondisi = $pengembalian->kondisi_saat_kembali;

        // Jika ada kerusakan, set status ke maintenance
        if ($pengembalian->ada_kerusakan && $newKondisi !== 'baik') {
            $newStatus = 'maintenance';
        }

        $this->update([
            'status' => $newStatus,
            'kondisi' => $newKondisi,
            'dipinjam_oleh' => null,
            'tanggal_pinjam' => null,
            'peminjaman_inventaris_id' => null,
            'updated_by' => auth()->id(),
        ]);

        $keterangan = "Dikembalikan";
        if ($pengembalian->ada_kerusakan) {
            $keterangan .= " dengan kerusakan: " . $pengembalian->deskripsi_kerusakan;
        }

        $this->logHistory('kembali', $keterangan, $pengembalian->id, 'pengembalian');

        return $this;
    }

    public function setMaintenance($keterangan = null)
    {
        $this->update([
            'status' => 'maintenance',
            'updated_by' => auth()->id(),
        ]);

        $this->logHistory('maintenance', $keterangan ?? 'Unit dimasukkan ke maintenance');

        return $this;
    }

    public function setRusak($keterangan = null)
    {
        $this->update([
            'status' => 'rusak',
            'kondisi' => 'rusak_berat',
            'updated_by' => auth()->id(),
        ]);

        $this->logHistory('rusak', $keterangan ?? 'Unit mengalami kerusakan');

        return $this;
    }

    public function setHilang($keterangan = null)
    {
        $this->update([
            'status' => 'hilang',
            'updated_by' => auth()->id(),
        ]);

        $this->logHistory('hilang', $keterangan ?? 'Unit dilaporkan hilang');

        return $this;
    }

    public function pindahLokasi($lokasiBaru, $keterangan = null)
    {
        $lokasiLama = $this->lokasi_saat_ini;
        
        $this->update([
            'lokasi_saat_ini' => $lokasiBaru,
            'updated_by' => auth()->id(),
        ]);

        $ket = $keterangan ?? "Lokasi dipindah dari {$lokasiLama} ke {$lokasiBaru}";
        $this->logHistory('pindah_lokasi', $ket);

        return $this;
    }

    // Log History
    public function logHistory($jenis, $keterangan, $refId = null, $refType = null)
    {
        InventarisUnitHistory::create([
            'inventaris_detail_unit_id' => $this->id,
            'jenis_aktivitas' => $jenis,
            'kondisi_sebelum' => $this->getOriginal('kondisi'),
            'kondisi_sesudah' => $this->kondisi,
            'status_sebelum' => $this->getOriginal('status'),
            'status_sesudah' => $this->status,
            'lokasi_sebelum' => $this->getOriginal('lokasi_saat_ini'),
            'lokasi_sesudah' => $this->lokasi_saat_ini,
            'keterangan' => $keterangan,
            'referensi_id' => $refId,
            'referensi_type' => $refType,
            'dilakukan_oleh' => auth()->id(),
        ]);
    }
}
