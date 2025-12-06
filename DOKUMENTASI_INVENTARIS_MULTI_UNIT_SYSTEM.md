# 📦 DOKUMENTASI SISTEM INVENTARIS MULTI-UNIT

## 🎯 ANALISIS KEBUTUHAN

### Permasalahan Sistem Saat Ini:
1. **Tidak Ada Tracking Per Unit** - Barang dengan qty banyak (misal 10 senter) tidak memiliki tracking individual
2. **Kondisi Tidak Spesifik** - Kondisi hanya untuk keseluruhan item, bukan per unit
3. **Kode Tidak Detail** - Kode inventaris hanya untuk master item, tidak ada sub-kode per unit
4. **Peminjaman Tidak Detail** - Tidak jelas unit mana yang dipinjam dari 10 senter yang ada
5. **History Tidak Lengkap** - Tidak ada tracking perjalanan masing-masing unit
6. **Halaman Terlalu Penuh** - Tombol aksi terlalu banyak di tabel utama

### Solusi yang Akan Diimplementasikan:

#### 1️⃣ **Struktur Hierarki 3 Level**
```
LEVEL 1: Master Inventaris (inventaris)
  └─ Kode: INV-00001
  └─ Nama: SENTER ASAS
  └─ Qty Total: 10 unit

LEVEL 2: Batch/Grup Unit (inventaris_units) 
  └─ Master: INV-00001
  └─ Batch ID: auto increment
  └─ Grouping berdasarkan: perolehan yang sama, kondisi awal sama

LEVEL 3: Detail Per Unit (inventaris_detail_units)
  ├─ Unit 1: INV-00001-U001 (Kondisi: Baik, Status: Tersedia)
  ├─ Unit 2: INV-00001-U002 (Kondisi: Baik, Status: Dipinjam - Ahmad)
  ├─ Unit 3: INV-00001-U003 (Kondisi: Rusak Ringan, Status: Maintenance)
  └─ ... dst
```

#### 2️⃣ **Alur UI/UX Baru**

**HALAMAN UTAMA (Master Data Index)**
```
[Tabel Master Inventaris]
Kolom: No | Kode | Nama Barang | Kategori | Qty Total | Tersedia | Status | Kondisi Dominan | Lokasi | AKSI

AKSI (Disederhanakan):
  [👁️ Detail] [✏️ Edit] [🗑️ Hapus]
```

**HALAMAN DETAIL (Klik Detail)**
```
┌─────────────────────────────────────────────────────────────┐
│  DETAIL INVENTARIS: INV-00001 - SENTER ASAS                 │
└─────────────────────────────────────────────────────────────┘

[Info Umum Master Inventaris]
- Kode: INV-00001
- Nama: SENTER ASAS  
- Kategori: Camping & Outdoor
- Merk: ASAS
- Total Unit: 10
- Tersedia: 7 unit
- Dipinjam: 2 unit
- Rusak/Maintenance: 1 unit

[TAB NAVIGATION]
┌────────────┬──────────────┬───────────────┬────────────┐
│ 📋 UNITS   │ 📤 PINJAM    │ 📥 KEMBALI   │ 📊 HISTORY │
└────────────┴──────────────┴───────────────┴────────────┘

TAB 1 - DETAIL UNITS:
┌─────────────────────────────────────────────────────────────┐
│ [+ Tambah Unit Baru] [🔄 Refresh]                           │
│                                                              │
│ [Tabel Detail Per Unit]                                     │
│ No | Kode Unit | Kondisi | Status | Lokasi | History | Aksi│
│ 1  | U001      | Baik    | ✓ Tersedia     | Gudang A | 👁️ | [✏️][🗑️]
│ 2  | U002      | Baik    | ⚠️ Dipinjam   | @ Ahmad  | 👁️ | [📥 Kembali]
│ 3  | U003      | Rusak   | 🔧 Maintenance| Workshop | 👁️ | [✏️]
│ ...                                                          │
└─────────────────────────────────────────────────────────────┘

TAB 2 - PEMINJAMAN:
[Form Peminjaman dengan dropdown pilih unit spesifik yang tersedia]
[List Peminjaman Aktif untuk item ini]

TAB 3 - PENGEMBALIAN:
[Form Pengembalian dengan pilih unit yang sedang dipinjam]
[History Pengembalian]

TAB 4 - HISTORY:
[Timeline lengkap semua aktivitas terkait item ini]
```

---

## 🗄️ DESAIN DATABASE

### 1. Tabel `inventaris` (Master - Sudah Ada, Perlu Update)
```sql
ALTER TABLE inventaris ADD COLUMN jumlah_unit INT DEFAULT 1 AFTER jumlah;
ALTER TABLE inventaris ADD COLUMN tracking_per_unit BOOLEAN DEFAULT FALSE;

-- tracking_per_unit: 
--   TRUE = barang ini punya banyak unit yang di-track individual
--   FALSE = barang satuan / tidak perlu tracking detail
```

### 2. Tabel `inventaris_units` (BARU - Grouping/Batch Level)
```sql
CREATE TABLE inventaris_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inventaris_id BIGINT UNSIGNED NOT NULL,
    batch_code VARCHAR(50) NULL, -- Optional: BATCH-001, BATCH-002
    tanggal_perolehan DATE NULL,
    supplier VARCHAR(255) NULL,
    harga_perolehan_per_unit DECIMAL(15,2) NULL,
    jumlah_unit_dalam_batch INT DEFAULT 1,
    lokasi_penyimpanan VARCHAR(255) NULL,
    keterangan TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (inventaris_id) REFERENCES inventaris(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_inventaris_id (inventaris_id)
);
```

**Contoh Data:**
```
| id | inventaris_id | batch_code | tanggal_perolehan | jumlah_unit_dalam_batch |
|----|---------------|------------|-------------------|-------------------------|
| 1  | 5 (SENTER)    | BATCH-001  | 2025-01-15        | 5                       |
| 2  | 5 (SENTER)    | BATCH-002  | 2025-03-10        | 5                       |
```

### 3. Tabel `inventaris_detail_units` (BARU - Unit Individual)
```sql
CREATE TABLE inventaris_detail_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inventaris_id BIGINT UNSIGNED NOT NULL,
    inventaris_unit_id BIGINT UNSIGNED NULL, -- Link ke batch (optional)
    kode_unit VARCHAR(50) UNIQUE NOT NULL, -- INV-00001-U001
    nomor_seri_unit VARCHAR(100) NULL, -- Serial number fisik barang
    kondisi ENUM('baik', 'rusak_ringan', 'rusak_berat') DEFAULT 'baik',
    status ENUM('tersedia', 'dipinjam', 'maintenance', 'rusak', 'hilang') DEFAULT 'tersedia',
    lokasi_saat_ini VARCHAR(255) NULL,
    tanggal_perolehan DATE NULL,
    harga_perolehan DECIMAL(15,2) NULL,
    
    -- Tracking Peminjaman Aktif
    dipinjam_oleh VARCHAR(255) NULL,
    tanggal_pinjam DATE NULL,
    peminjaman_inventaris_id BIGINT UNSIGNED NULL,
    
    -- Additional Info
    foto_unit VARCHAR(255) NULL,
    catatan_kondisi TEXT NULL,
    terakhir_maintenance DATE NULL,
    
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (inventaris_id) REFERENCES inventaris(id) ON DELETE CASCADE,
    FOREIGN KEY (inventaris_unit_id) REFERENCES inventaris_units(id) ON DELETE SET NULL,
    FOREIGN KEY (peminjaman_inventaris_id) REFERENCES peminjaman_inventaris(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_inventaris_id (inventaris_id),
    INDEX idx_status (status),
    INDEX idx_kode_unit (kode_unit)
);
```

**Contoh Data:**
```
| id | inventaris_id | kode_unit      | kondisi | status    | dipinjam_oleh | peminjaman_id |
|----|---------------|----------------|---------|-----------|---------------|---------------|
| 1  | 5             | INV-00001-U001 | baik    | tersedia  | NULL          | NULL          |
| 2  | 5             | INV-00001-U002 | baik    | dipinjam  | Ahmad         | 15            |
| 3  | 5             | INV-00001-U003 | rusak   | maintenance| NULL         | NULL          |
```

### 4. Update Tabel `peminjaman_inventaris`
```sql
ALTER TABLE peminjaman_inventaris ADD COLUMN inventaris_detail_unit_id BIGINT UNSIGNED NULL AFTER inventaris_id;
ALTER TABLE peminjaman_inventaris ADD COLUMN kode_unit_dipinjam VARCHAR(50) NULL;

ALTER TABLE peminjaman_inventaris 
  ADD FOREIGN KEY (inventaris_detail_unit_id) 
  REFERENCES inventaris_detail_units(id) ON DELETE SET NULL;
```

### 5. Update Tabel `pengembalian_inventaris`
```sql
ALTER TABLE pengembalian_inventaris ADD COLUMN inventaris_detail_unit_id BIGINT UNSIGNED NULL;
ALTER TABLE pengembalian_inventaris ADD COLUMN kondisi_saat_kembali ENUM('baik', 'rusak_ringan', 'rusak_berat') DEFAULT 'baik';
ALTER TABLE pengembalian_inventaris ADD COLUMN ada_kerusakan BOOLEAN DEFAULT FALSE;
ALTER TABLE pengembalian_inventaris ADD COLUMN deskripsi_kerusakan TEXT NULL;

ALTER TABLE pengembalian_inventaris 
  ADD FOREIGN KEY (inventaris_detail_unit_id) 
  REFERENCES inventaris_detail_units(id) ON DELETE SET NULL;
```

### 6. Tabel `inventaris_unit_history` (BARU - History Per Unit)
```sql
CREATE TABLE inventaris_unit_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inventaris_detail_unit_id BIGINT UNSIGNED NOT NULL,
    jenis_aktivitas ENUM('input', 'pinjam', 'kembali', 'maintenance', 'perbaikan', 'update_kondisi', 'pindah_lokasi', 'rusak', 'hilang') NOT NULL,
    kondisi_sebelum VARCHAR(50) NULL,
    kondisi_sesudah VARCHAR(50) NULL,
    status_sebelum VARCHAR(50) NULL,
    status_sesudah VARCHAR(50) NULL,
    lokasi_sebelum VARCHAR(255) NULL,
    lokasi_sesudah VARCHAR(255) NULL,
    keterangan TEXT NULL,
    referensi_id BIGINT UNSIGNED NULL, -- ID peminjaman/pengembalian/dll
    referensi_type VARCHAR(100) NULL, -- 'peminjaman', 'pengembalian', dll
    dilakukan_oleh BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    
    FOREIGN KEY (inventaris_detail_unit_id) REFERENCES inventaris_detail_units(id) ON DELETE CASCADE,
    FOREIGN KEY (dilakukan_oleh) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_unit (inventaris_detail_unit_id),
    INDEX idx_aktivitas (jenis_aktivitas)
);
```

---

## 🔄 ALUR LOGIKA SISTEM

### A. **Skenario 1: Tambah Inventaris Baru**

#### Case 1: Barang Satuan (Tidak Perlu Tracking Unit)
```
Contoh: Meja Rapat, Lemari, AC

Input:
- Nama: Meja Rapat Besar
- Qty: 1
- tracking_per_unit: FALSE

Hasil:
✓ Hanya buat record di tabel 'inventaris'
✓ Tidak perlu buat di 'inventaris_detail_units'
✓ Peminjaman langsung ke master inventaris
```

#### Case 2: Barang Multi-Unit (Perlu Tracking Detail)
```
Contoh: 10 Senter ASAS

Input Form:
- Nama: SENTER
- Merk: ASAS  
- Qty: 10
- tracking_per_unit: TRUE ✓
- Generate otomatis unit? [Ya/Tidak]

Jika "Ya" (Auto Generate):
  ✓ Buat 1 record di 'inventaris' (Master)
  ✓ Auto generate 10 record di 'inventaris_detail_units':
      INV-00001-U001 (Kondisi: Baik, Status: Tersedia)
      INV-00001-U002 (Kondisi: Baik, Status: Tersedia)
      ...
      INV-00001-U010 (Kondisi: Baik, Status: Tersedia)

Jika "Tidak" (Manual Input Nanti):
  ✓ Buat 1 record di 'inventaris'
  ✓ Qty di master = 0 (masih kosong)
  ✓ User bisa tambah unit via halaman detail nanti
```

### B. **Skenario 2: Peminjaman**

#### Alur Baru:
```
1. User klik "Detail" pada Master Inventaris (SENTER ASAS)

2. Masuk ke Halaman Detail → Tab "PINJAM"

3. Form Peminjaman:
   ┌─────────────────────────────────────────────┐
   │ Nama Peminjam: [Ahmad]                      │
   │ Tanggal Pinjam: [2025-11-26]               │
   │ Tanggal Rencana Kembali: [2025-12-26]     │
   │                                             │
   │ Pilih Unit yang Akan Dipinjam:             │
   │ ☐ INV-00001-U001 (Kondisi: Baik) ✓ Tersedia│
   │ ☐ INV-00001-U004 (Kondisi: Baik) ✓ Tersedia│
   │ ☐ INV-00001-U007 (Kondisi: Baik) ✓ Tersedia│
   │ ☑️ INV-00001-U002 (Kondisi: Baik) ✓ Tersedia│ ← Selected
   │                                             │
   │ Keperluan: [Jelajah Malam]                 │
   │ [SUBMIT PEMINJAMAN]                        │
   └─────────────────────────────────────────────┘

4. Setelah Submit:
   ✓ Buat record di 'peminjaman_inventaris'
      - inventaris_id = 5 (SENTER Master)
      - inventaris_detail_unit_id = 2 (U002)
      - kode_unit_dipinjam = 'INV-00001-U002'
      
   ✓ Update 'inventaris_detail_units' (U002):
      - status = 'dipinjam'
      - dipinjam_oleh = 'Ahmad'
      - tanggal_pinjam = '2025-11-26'
      - peminjaman_inventaris_id = [ID peminjaman baru]
      
   ✓ Buat history di 'inventaris_unit_history':
      - jenis_aktivitas = 'pinjam'
      - status_sebelum = 'tersedia'
      - status_sesudah = 'dipinjam'
      - keterangan = 'Dipinjam oleh Ahmad untuk Jelajah Malam'
```

### C. **Skenario 3: Pengembalian**

```
1. User klik "Detail" → Tab "KEMBALI"

2. Tampilkan List Unit yang Sedang Dipinjam:
   ┌─────────────────────────────────────────────┐
   │ Unit Sedang Dipinjam:                       │
   │                                             │
   │ 🔸 INV-00001-U002                          │
   │    Peminjam: Ahmad                         │
   │    Tanggal Pinjam: 2025-11-26             │
   │    [PROSES PENGEMBALIAN]                   │
   └─────────────────────────────────────────────┘

3. Klik "Proses Pengembalian" → Modal Form:
   ┌─────────────────────────────────────────────┐
   │ Pengembalian Unit: INV-00001-U002          │
   │                                             │
   │ Peminjam: Ahmad                            │
   │ Tanggal Kembali: [2025-11-27]             │
   │                                             │
   │ Kondisi Saat Kembali:                      │
   │ ● Baik                                     │
   │ ○ Rusak Ringan                            │
   │ ○ Rusak Berat                             │
   │                                             │
   │ Ada Kerusakan? [✓] Ya  [ ] Tidak          │
   │ Deskripsi Kerusakan:                       │
   │ [Lensa retak sedikit]                      │
   │                                             │
   │ Denda (jika ada): [Rp 0]                  │
   │ [SUBMIT PENGEMBALIAN]                      │
   └─────────────────────────────────────────────┘

4. Setelah Submit:
   ✓ Buat record 'pengembalian_inventaris'
      - kondisi_saat_kembali = 'rusak_ringan'
      - ada_kerusakan = TRUE
      
   ✓ Update 'inventaris_detail_units' (U002):
      - status = 'tersedia' (atau 'maintenance' jika rusak)
      - kondisi = 'rusak_ringan'
      - dipinjam_oleh = NULL
      - tanggal_pinjam = NULL
      - peminjaman_inventaris_id = NULL
      
   ✓ Update 'peminjaman_inventaris':
      - status_pengembalian = 'sudah_dikembalikan'
      
   ✓ Buat history:
      - jenis_aktivitas = 'kembali'
      - kondisi_sebelum = 'baik'
      - kondisi_sesudah = 'rusak_ringan'
      - keterangan = 'Dikembalikan dengan kerusakan: Lensa retak sedikit'
```

### D. **Skenario 4: Maintenance/Perbaikan Unit**

```
Di Tab "UNITS" → Klik Edit pada unit tertentu:

Form Edit Unit:
┌─────────────────────────────────────────────┐
│ Edit Unit: INV-00001-U002                   │
│                                             │
│ Kondisi:                                    │
│ ○ Baik                                     │
│ ● Rusak Ringan  ← Updated                 │
│ ○ Rusak Berat                             │
│                                             │
│ Status:                                     │
│ ○ Tersedia                                 │
│ ● Maintenance  ← Updated                   │
│ ○ Rusak                                    │
│                                             │
│ Lokasi: [Workshop Perbaikan]               │
│ Catatan: [Sedang perbaikan lensa]         │
│ [UPDATE]                                    │
└─────────────────────────────────────────────┘

✓ Update unit
✓ Log history perubahan status
```

---

## 🎨 IMPLEMENTASI UI

### File Structure Yang Akan Dibuat/Dimodifikasi:

```
📁 resources/views/inventaris/
  ├── index.blade.php          [EDIT - Sederhanakan tombol aksi]
  ├── show-detail.blade.php    [BARU - Halaman detail lengkap]
  ├── partials/
  │   ├── tab-units.blade.php           [BARU - Tab list units]
  │   ├── tab-peminjaman.blade.php      [BARU - Tab peminjaman]
  │   ├── tab-pengembalian.blade.php    [BARU - Tab pengembalian]
  │   └── tab-history.blade.php         [BARU - Tab history]
  ├── units/
  │   ├── create-modal.blade.php        [BARU - Form tambah unit]
  │   ├── edit-modal.blade.php          [BARU - Form edit unit]
  │   └── show-history-modal.blade.php  [BARU - History per unit]
```

---

## 🔧 IMPLEMENTASI BACKEND

### Models Yang Perlu Dibuat/Update:

#### 1. Model `InventarisUnit.php` (BARU)
```php
class InventarisUnit extends Model
{
    protected $fillable = [
        'inventaris_id', 'batch_code', 'tanggal_perolehan',
        'supplier', 'harga_perolehan_per_unit', 
        'jumlah_unit_dalam_batch', 'lokasi_penyimpanan',
        'keterangan', 'created_by'
    ];
    
    public function inventaris() {
        return $this->belongsTo(Inventaris::class);
    }
    
    public function detailUnits() {
        return $this->hasMany(InventarisDetailUnit::class);
    }
}
```

#### 2. Model `InventarisDetailUnit.php` (BARU)
```php
class InventarisDetailUnit extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'inventaris_id', 'inventaris_unit_id', 'kode_unit',
        'nomor_seri_unit', 'kondisi', 'status', 'lokasi_saat_ini',
        'tanggal_perolehan', 'harga_perolehan', 'dipinjam_oleh',
        'tanggal_pinjam', 'peminjaman_inventaris_id', 'foto_unit',
        'catatan_kondisi', 'terakhir_maintenance', 
        'created_by', 'updated_by'
    ];
    
    protected $casts = [
        'tanggal_perolehan' => 'date',
        'tanggal_pinjam' => 'date',
        'terakhir_maintenance' => 'date',
    ];
    
    // Auto generate kode unit
    protected static function boot() {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kode_unit)) {
                $model->kode_unit = self::generateKodeUnit($model->inventaris_id);
            }
        });
        
        static::created(function ($model) {
            $model->logHistory('input', 'Unit baru ditambahkan');
        });
        
        static::updated(function ($model) {
            $changes = [];
            
            if ($model->isDirty('status')) {
                $changes[] = 'Status: ' . $model->getOriginal('status') . ' → ' . $model->status;
            }
            
            if ($model->isDirty('kondisi')) {
                $changes[] = 'Kondisi: ' . $model->getOriginal('kondisi') . ' → ' . $model->kondisi;
            }
            
            if ($model->isDirty('lokasi_saat_ini')) {
                $changes[] = 'Lokasi: ' . $model->getOriginal('lokasi_saat_ini') . ' → ' . $model->lokasi_saat_ini;
            }
            
            if (!empty($changes)) {
                $model->logHistory('update', implode(', ', $changes));
            }
        });
    }
    
    public static function generateKodeUnit($inventarisId) {
        $inventaris = Inventaris::findOrFail($inventarisId);
        $lastUnit = self::where('inventaris_id', $inventarisId)
                        ->withTrashed()
                        ->latest('id')
                        ->first();
        
        $number = $lastUnit ? intval(substr($lastUnit->kode_unit, -3)) + 1 : 1;
        
        return $inventaris->kode_inventaris . '-U' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
    
    // Relationships
    public function inventaris() {
        return $this->belongsTo(Inventaris::class);
    }
    
    public function inventarisUnit() {
        return $this->belongsTo(InventarisUnit::class);
    }
    
    public function peminjamanAktif() {
        return $this->belongsTo(PeminjamanInventaris::class, 'peminjaman_inventaris_id');
    }
    
    public function histories() {
        return $this->hasMany(InventarisUnitHistory::class);
    }
    
    // Scopes
    public function scopeTersedia($query) {
        return $query->where('status', 'tersedia');
    }
    
    public function scopeDipinjam($query) {
        return $query->where('status', 'dipinjam');
    }
    
    public function scopeKondisiBaik($query) {
        return $query->where('kondisi', 'baik');
    }
    
    // Methods
    public function isTersedia() {
        return $this->status === 'tersedia';
    }
    
    public function setDipinjam($peminjaman) {
        $this->update([
            'status' => 'dipinjam',
            'dipinjam_oleh' => $peminjaman->nama_peminjam,
            'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
            'peminjaman_inventaris_id' => $peminjaman->id
        ]);
        
        $this->logHistory('pinjam', "Dipinjam oleh {$peminjaman->nama_peminjam}", $peminjaman->id, 'peminjaman');
    }
    
    public function setDikembalikan($pengembalian) {
        $this->update([
            'status' => $pengembalian->ada_kerusakan ? 'maintenance' : 'tersedia',
            'kondisi' => $pengembalian->kondisi_saat_kembali,
            'dipinjam_oleh' => null,
            'tanggal_pinjam' => null,
            'peminjaman_inventaris_id' => null
        ]);
        
        $ket = "Dikembalikan";
        if ($pengembalian->ada_kerusakan) {
            $ket .= " dengan kerusakan: " . $pengembalian->deskripsi_kerusakan;
        }
        
        $this->logHistory('kembali', $ket, $pengembalian->id, 'pengembalian');
    }
    
    public function logHistory($jenis, $keterangan, $refId = null, $refType = null) {
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
            'dilakukan_oleh' => auth()->id()
        ]);
    }
}
```

#### 3. Model `InventarisUnitHistory.php` (BARU)
```php
class InventarisUnitHistory extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'inventaris_detail_unit_id', 'jenis_aktivitas',
        'kondisi_sebelum', 'kondisi_sesudah',
        'status_sebelum', 'status_sesudah',
        'lokasi_sebelum', 'lokasi_sesudah',
        'keterangan', 'referensi_id', 'referensi_type',
        'dilakukan_oleh'
    ];
    
    protected $casts = [
        'created_at' => 'datetime'
    ];
    
    public function detailUnit() {
        return $this->belongsTo(InventarisDetailUnit::class, 'inventaris_detail_unit_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
```

#### 4. Update Model `Inventaris.php`
```php
// Tambahkan di $fillable
'jumlah_unit', 'tracking_per_unit'

// Tambahkan relationships baru
public function units() {
    return $this->hasMany(InventarisUnit::class);
}

public function detailUnits() {
    return $this->hasMany(InventarisDetailUnit::class);
}

public function detailUnitsTersedia() {
    return $this->hasMany(InventarisDetailUnit::class)->where('status', 'tersedia');
}

public function detailUnitsDipinjam() {
    return $this->hasMany(InventarisDetailUnit::class)->where('status', 'dipinjam');
}

// Update method jumlahTersedia()
public function jumlahTersedia() {
    if ($this->tracking_per_unit) {
        return $this->detailUnitsTersedia()->count();
    }
    
    // Logic lama untuk barang tanpa tracking unit
    $dipinjam = $this->peminjamanAktif()->sum('jumlah_pinjam');
    return max(0, $this->jumlah - $dipinjam);
}

// Method baru
public function getTotalUnits() {
    if ($this->tracking_per_unit) {
        return $this->detailUnits()->count();
    }
    return $this->jumlah;
}

public function getJumlahDipinjam() {
    if ($this->tracking_per_unit) {
        return $this->detailUnitsDipinjam()->count();
    }
    return $this->peminjamanAktif()->sum('jumlah_pinjam');
}
```

---

## 📋 CONTROLLER UPDATES

### Update `InventarisController.php`

```php
// Method baru untuk halaman detail lengkap
public function showDetail($id)
{
    $inventaris = Inventaris::with([
        'detailUnits.peminjamanAktif',
        'detailUnits.histories',
        'cabang',
        'barang'
    ])->findOrFail($id);
    
    // Stats untuk dashboard detail
    $stats = [
        'total_units' => $inventaris->detailUnits()->count(),
        'tersedia' => $inventaris->detailUnitsTersedia()->count(),
        'dipinjam' => $inventaris->detailUnitsDipinjam()->count(),
        'rusak' => $inventaris->detailUnits()->where('status', 'rusak')->count(),
        'maintenance' => $inventaris->detailUnits()->where('status', 'maintenance')->count(),
    ];
    
    $detailUnits = $inventaris->detailUnits()
        ->with(['peminjamanAktif', 'inventarisUnit'])
        ->latest()
        ->paginate(20);
    
    $peminjamanAktif = $inventaris->peminjamanAktif()
        ->with(['detailUnit', 'disetujuiOleh'])
        ->latest()
        ->get();
    
    $recentPengembalian = PengembalianInventaris::whereHas('peminjaman', function($q) use ($id) {
        $q->where('inventaris_id', $id);
    })->with(['peminjaman', 'detailUnit'])->latest()->take(10)->get();
    
    $allHistory = InventarisUnitHistory::whereHas('detailUnit', function($q) use ($id) {
        $q->where('inventaris_id', $id);
    })->with(['detailUnit', 'user'])->latest()->paginate(50);
    
    return view('inventaris.show-detail', compact(
        'inventaris', 
        'stats', 
        'detailUnits', 
        'peminjamanAktif', 
        'recentPengembalian',
        'allHistory'
    ));
}
```

### New `InventarisDetailUnitController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\InventarisDetailUnit;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventarisDetailUnitController extends Controller
{
    public function store(Request $request, $inventarisId)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1|max:100',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi_saat_ini' => 'nullable|string',
            'tanggal_perolehan' => 'nullable|date',
            'harga_perolehan' => 'nullable|numeric',
            'catatan_kondisi' => 'nullable|string',
        ]);
        
        $inventaris = Inventaris::findOrFail($inventarisId);
        $jumlah = $validated['jumlah'];
        
        $units = [];
        for ($i = 0; $i < $jumlah; $i++) {
            $unit = InventarisDetailUnit::create([
                'inventaris_id' => $inventaris->id,
                'kondisi' => $validated['kondisi'],
                'status' => 'tersedia',
                'lokasi_saat_ini' => $validated['lokasi_saat_ini'] ?? $inventaris->lokasi_penyimpanan,
                'tanggal_perolehan' => $validated['tanggal_perolehan'] ?? now(),
                'harga_perolehan' => $validated['harga_perolehan'] ?? $inventaris->harga_perolehan,
                'catatan_kondisi' => $validated['catatan_kondisi'],
                'created_by' => auth()->id(),
            ]);
            
            $units[] = $unit;
        }
        
        // Update master jumlah_unit
        $inventaris->increment('jumlah_unit', $jumlah);
        $inventaris->update(['tracking_per_unit' => true]);
        
        return response()->json([
            'success' => true,
            'message' => "$jumlah unit berhasil ditambahkan",
            'units' => $units
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $unit = InventarisDetailUnit::findOrFail($id);
        
        $validated = $request->validate([
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:tersedia,dipinjam,maintenance,rusak,hilang',
            'lokasi_saat_ini' => 'nullable|string',
            'catatan_kondisi' => 'nullable|string',
            'foto_unit' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('foto_unit')) {
            if ($unit->foto_unit) {
                Storage::disk('public')->delete($unit->foto_unit);
            }
            $validated['foto_unit'] = $request->file('foto_unit')->store('inventaris/units', 'public');
        }
        
        $validated['updated_by'] = auth()->id();
        $unit->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil diupdate'
        ]);
    }
    
    public function destroy($id)
    {
        $unit = InventarisDetailUnit::findOrFail($id);
        
        // Pastikan unit tidak sedang dipinjam
        if ($unit->status === 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Unit sedang dipinjam, tidak dapat dihapus'
            ], 422);
        }
        
        $unit->logHistory('hapus', 'Unit dihapus dari sistem');
        $unit->delete();
        
        // Decrement jumlah_unit di master
        $unit->inventaris->decrement('jumlah_unit');
        
        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil dihapus'
        ]);
    }
    
    public function showHistory($id)
    {
        $unit = InventarisDetailUnit::with(['inventaris', 'histories.user'])
                                    ->findOrFail($id);
        
        $histories = $unit->histories()
                         ->with('user')
                         ->latest()
                         ->get();
        
        return view('inventaris.units.show-history-modal', compact('unit', 'histories'));
    }
}
```

---

## 🚀 LANGKAH IMPLEMENTASI

### Phase 1: Database & Models
1. ✅ Buat migration untuk 3 tabel baru
2. ✅ Buat model InventarisUnit
3. ✅ Buat model InventarisDetailUnit
4. ✅ Buat model InventarisUnitHistory
5. ✅ Update model Inventaris
6. ✅ Update model PeminjamanInventaris
7. ✅ Update model PengembalianInventaris

### Phase 2: Controllers
1. ✅ Update InventarisController (method showDetail)
2. ✅ Buat InventarisDetailUnitController
3. ✅ Update PeminjamanInventarisController (handle unit selection)
4. ✅ Update PengembalianInventarisController (update unit status)

### Phase 3: Views
1. ✅ Edit index.blade.php (simplify action buttons)
2. ✅ Buat show-detail.blade.php (halaman detail utama)
3. ✅ Buat tab-units.blade.php
4. ✅ Buat tab-peminjaman.blade.php
5. ✅ Buat tab-pengembalian.blade.php
6. ✅ Buat tab-history.blade.php
7. ✅ Buat unit management modals

### Phase 4: Routes & Testing
1. ✅ Update routes/web.php
2. ✅ Testing alur lengkap
3. ✅ Bug fixing
4. ✅ Documentation finalization

---

## 📝 CATATAN PENTING

### Handling Backward Compatibility:
```php
// Inventaris lama (tanpa tracking_per_unit) tetap berfungsi normal
// Method-method di model sudah handle kedua kasus:

if ($inventaris->tracking_per_unit) {
    // Logic untuk multi-unit
} else {
    // Logic lama (single/no tracking)
}
```

### Data Migration Existing Items:
```php
// Untuk barang existing yang ingin diubah ke multi-unit:
Artisan::command('inventaris:convert-to-multi-unit {id}', function ($id) {
    $inventaris = Inventaris::findOrFail($id);
    
    // Generate units based on current qty
    for ($i = 0; $i < $inventaris->jumlah; $i++) {
        InventarisDetailUnit::create([
            'inventaris_id' => $inventaris->id,
            'kondisi' => $inventaris->kondisi,
            'status' => 'tersedia',
            'lokasi_saat_ini' => $inventaris->lokasi_penyimpanan,
            'created_by' => 1 // Admin
        ]);
    }
    
    $inventaris->update([
        'jumlah_unit' => $inventaris->jumlah,
        'tracking_per_unit' => true
    ]);
    
    $this->info("Inventaris {$inventaris->kode_inventaris} berhasil dikonversi");
});
```

---

## 🎯 KESIMPULAN

Sistem baru ini memberikan:

✅ **Tracking Detail Per Unit** - Setiap barang punya identitas unik
✅ **History Lengkap** - Jejak perjalanan setiap unit tercatat
✅ **Kondisi Spesifik** - Kondisi per unit, bukan general
✅ **Peminjaman Lebih Jelas** - Tahu persis unit mana yang dipinjam
✅ **UI Lebih Clean** - Halaman utama sederhana, detail di sub-page
✅ **Flexible** - Support barang satuan dan multi-unit
✅ **Scalable** - Mudah dikembangkan untuk fitur lanjutan

**Next Steps:**
1. Review dokumentasi ini
2. Approve design & flow
3. Mulai implementasi phase by phase
4. Testing menyeluruh
5. Deploy ke production

---

*Dokumentasi dibuat: 26 November 2025*
*Version: 1.0*
