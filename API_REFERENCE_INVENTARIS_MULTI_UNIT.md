# 📚 API REFERENCE - INVENTARIS MULTI-UNIT SYSTEM

## 🔧 Model Methods

### **InventarisDetailUnit** (Core Unit Model)

#### Status Management
```php
// Set unit ke status "dipinjam"
$unit->setDipinjam($peminjaman)
// Auto create history log, update status

// Set unit ke status "tersedia" setelah dikembalikan
$unit->setDikembalikan($pengembalian)
// Update status, create history log

// Set unit ke maintenance
$unit->setMaintenance($keterangan)
// Update status, log reason

// Set unit rusak permanen
$unit->setRusakPermanen($keterangan)
// Status = rusak_permanen, log

// Set unit hilang
$unit->setHilang($keterangan)
// Status = hilang, log

// Soft delete unit
$unit->setDihapus($keterangan)
// Soft delete, log
```

#### Query Helpers
```php
// Cek apakah unit tersedia untuk dipinjam
$unit->isTersedia() // boolean

// Cek apakah unit sedang dipinjam
$unit->isDipinjam() // boolean

// Cek apakah unit dalam maintenance
$unit->isMaintenance() // boolean

// Get peminjaman aktif (jika ada)
$unit->peminjamanAktif() // PeminjamanInventaris|null

// Get status badge HTML
$unit->statusBadge() // '<span class="badge bg-success">Tersedia</span>'

// Get kondisi badge HTML
$unit->kondisiBadge() // '<span class="badge bg-primary">Baik</span>'
```

#### Relationships
```php
// Get master inventaris
$unit->inventaris

// Get batch (jika ada)
$unit->unit // InventarisUnit

// Get semua history
$unit->history

// Get peminjaman terakhir
$unit->peminjamanTerakhir

// Get pengembalian terakhir
$unit->pengembalianTerakhir
```

---

### **Inventaris** (Master Model)

#### Unit Tracking
```php
// Get semua units untuk inventaris ini
$inventaris->detailUnits

// Get units yang tersedia
$inventaris->detailUnitsTersedia

// Get units yang sedang dipinjam
$inventaris->detailUnitsDipinjam

// Get batch/grouping
$inventaris->units

// Hitung jumlah tersedia (auto detect mode tracking/non-tracking)
$inventaris->jumlahTersedia() // integer

// Cek apakah menggunakan tracking per unit
if ($inventaris->tracking_per_unit) {
    // Mode tracking aktif
}
```

---

### **InventarisUnit** (Batch Model)

```php
// Get semua detail units dalam batch ini
$batch->detailUnits

// Get master inventaris
$batch->inventaris

// Get kode batch (auto-generated)
$batch->kode_batch // "BATCH-001"
```

---

### **InventarisUnitHistory** (History Model)

#### Query Helpers
```php
// Get history untuk unit spesifik
$history = InventarisUnitHistory::where('inventaris_detail_unit_id', $unit->id)
    ->orderBy('created_at', 'desc')
    ->get();

// Get history by jenis aktivitas
$history = InventarisUnitHistory::where('jenis_aktivitas', 'pinjam')->get();
```

#### Display Helpers
```php
$history->label() // "Input Unit"
$history->icon() // "ti ti-plus"
$history->badgeClass() // "bg-primary"

// Get badge HTML
$history->badge() 
// '<span class="badge bg-primary"><i class="ti ti-plus"></i> Input Unit</span>'
```

#### Relationships
```php
$history->detailUnit // InventarisDetailUnit
$history->dilakukanOleh // User
```

---

## 🌐 Controller Endpoints

### **InventarisDetailUnitController**

#### List Units
```http
GET /inventaris/{inventaris}/units
```
**Response**: JSON list of units dengan pagination

#### Create Unit Form
```http
GET /inventaris/{inventaris}/units/create
```
**Response**: Modal view untuk bulk add units

#### Store Units (Bulk Add)
```http
POST /inventaris/{inventaris}/units
```
**Request Body**:
```json
{
  "jumlah_unit": 5,
  "batch_option": "new_batch",
  "nama_batch": "Batch Baru",
  "kondisi_default": "baik",
  "lokasi_default": "Gudang Utama",
  "catatan": "Optional notes"
}
```
**Response**:
```json
{
  "success": true,
  "message": "5 unit berhasil ditambahkan",
  "units": [...]
}
```

#### Edit Unit
```http
GET /inventaris/{inventaris}/units/{unit}/edit
```
**Response**: Edit form modal

#### Update Unit
```http
PUT /inventaris/{inventaris}/units/{unit}
```
**Request Body**:
```json
{
  "kondisi": "rusak_ringan",
  "status": "maintenance",
  "lokasi": "Workshop",
  "catatan": "Perlu perbaikan",
  "foto": "file upload"
}
```

#### Delete Unit
```http
DELETE /inventaris/{inventaris}/units/{unit}
```
**Response**:
```json
{
  "success": true,
  "message": "Unit INV-00001-U001 berhasil dihapus"
}
```

#### Show History
```http
GET /inventaris/{inventaris}/units/{unit}/history
```
**Response**: Modal dengan timeline lengkap

#### Get Units Tersedia (AJAX)
```http
GET /inventaris/{inventaris}/units/tersedia
```
**Response**:
```json
{
  "success": true,
  "units": [
    {
      "id": 1,
      "kode_unit": "INV-00001-U001",
      "kondisi": "baik",
      "lokasi": "Gudang Utama"
    }
  ]
}
```

#### Bulk Update Status
```http
POST /inventaris/{inventaris}/units/bulk-update-status
```
**Request Body**:
```json
{
  "unit_ids": [1, 2, 3],
  "status": "maintenance",
  "keterangan": "Scheduled maintenance"
}
```

---

### **PeminjamanInventarisController** (Updated)

#### Store Peminjaman
```http
POST /peminjaman-inventaris
```
**Request Body** (dengan unit tracking):
```json
{
  "inventaris_id": 1,
  "inventaris_detail_unit_id": 5,  // <-- Unit spesifik
  "nama_peminjam": "Ahmad",
  "tanggal_pinjam": "2025-11-26",
  "tanggal_kembali_rencana": "2025-11-30",
  "keperluan": "Meeting",
  "ttd_peminjam": "data:image/png;base64,..."
}
```

**Logic**:
```php
if ($inventaris->tracking_per_unit && $request->filled('inventaris_detail_unit_id')) {
    $detailUnit = InventarisDetailUnit::find($validated['inventaris_detail_unit_id']);
    
    // Validasi status unit
    if ($detailUnit->status != 'tersedia') {
        return error('Unit tidak tersedia');
    }
    
    // Auto update status
    $detailUnit->setDipinjam($peminjaman);
}
```

---

### **PengembalianInventarisController** (Updated)

#### Store Pengembalian
```http
POST /pengembalian-inventaris
```
**Request Body** (dengan kondisi tracking):
```json
{
  "peminjaman_inventaris_id": 10,
  "tanggal_pengembalian": "2025-11-30",
  "kondisi_kembali": "rusak_ringan",
  "ada_kerusakan": true,
  "deskripsi_kerusakan": "Goresan di bagian belakang",
  "ttd_peminjam": "data:image/png;base64,..."
}
```

**Logic**:
```php
if ($inventaris->tracking_per_unit && $peminjaman->inventaris_detail_unit_id) {
    $detailUnit = InventarisDetailUnit::find($peminjaman->inventaris_detail_unit_id);
    
    // Update kondisi unit
    $detailUnit->kondisi = $validated['kondisi_barang'];
    $detailUnit->save();
    
    // Set status berdasarkan kondisi
    if (in_array($validated['kondisi_barang'], ['rusak_ringan', 'rusak_berat'])) {
        $detailUnit->setMaintenance($validated['deskripsi_kerusakan']);
    } else {
        $detailUnit->setDikembalikan($pengembalian);
    }
}
```

---

## 🗄️ Database Schema Reference

### **inventaris_detail_units**
```sql
id                    BIGINT UNSIGNED PRIMARY KEY
inventaris_id         BIGINT UNSIGNED FK(inventaris.id) CASCADE
inventaris_unit_id    BIGINT UNSIGNED FK(inventaris_units.id) SET NULL
kode_unit             VARCHAR(100) UNIQUE AUTO-GENERATED
kondisi               ENUM('baik', 'rusak_ringan', 'rusak_berat', 'hilang')
status                ENUM('tersedia', 'dipinjam', 'maintenance', 'rusak_permanen', 'dihapus')
lokasi                VARCHAR(255)
foto                  VARCHAR(255)
catatan               TEXT
created_by            BIGINT UNSIGNED FK(users.id)
updated_by            BIGINT UNSIGNED FK(users.id)
created_at            TIMESTAMP
updated_at            TIMESTAMP
deleted_at            TIMESTAMP (SOFT DELETE)

INDEXES:
  - kode_unit UNIQUE
  - inventaris_id, status
  - inventaris_id, kondisi
```

### **inventaris_unit_history**
```sql
id                          BIGINT UNSIGNED PRIMARY KEY
inventaris_detail_unit_id   BIGINT UNSIGNED FK CASCADE
jenis_aktivitas             ENUM(input, pinjam, kembali, maintenance, ...)
kondisi_sebelum             VARCHAR(50)
kondisi_sesudah             VARCHAR(50)
status_sebelum              VARCHAR(50)
status_sesudah              VARCHAR(50)
lokasi_sebelum              VARCHAR(255)
lokasi_sesudah              VARCHAR(255)
keterangan                  TEXT
referensi_id                BIGINT UNSIGNED (polymorphic)
referensi_type              VARCHAR(100) (polymorphic)
dilakukan_oleh              BIGINT UNSIGNED FK(users.id)
created_at                  TIMESTAMP

INDEXES:
  - inventaris_detail_unit_id
  - jenis_aktivitas
  - referensi_id, referensi_type
```

---

## 🎨 Blade Components & Helpers

### Status Badge
```blade
{!! $unit->statusBadge() !!}
<!-- Output: <span class="badge bg-success">Tersedia</span> -->
```

### Kondisi Badge
```blade
{!! $unit->kondisiBadge() !!}
<!-- Output: <span class="badge bg-primary">Baik</span> -->
```

### History Badge
```blade
{!! $history->badge() !!}
<!-- Output: <span class="badge bg-info"><i class="ti ti-arrow-up"></i> Pinjam</span> -->
```

### Unit Selector (AJAX)
```javascript
// Load units tersedia via AJAX
$.get(`/inventaris/${inventarisId}/units/tersedia`, function(response) {
    response.units.forEach(unit => {
        $('#unit_selector').append(
            `<option value="${unit.id}">${unit.kode_unit} - ${unit.kondisi}</option>`
        );
    });
});
```

---

## 🧪 Testing Code Examples

### Test 1: Create Units
```php
use App\Models\InventarisDetailUnit;

// Create 3 units
for ($i = 1; $i <= 3; $i++) {
    InventarisDetailUnit::create([
        'inventaris_id' => 1,
        'kondisi' => 'baik',
        'status' => 'tersedia',
        'lokasi' => 'Gudang Utama',
        'created_by' => auth()->id()
    ]);
}
// Kode auto-generate: INV-00001-U001, U002, U003
```

### Test 2: Pinjam Unit
```php
$unit = InventarisDetailUnit::where('status', 'tersedia')->first();

$peminjaman = PeminjamanInventaris::create([
    'inventaris_id' => $unit->inventaris_id,
    'inventaris_detail_unit_id' => $unit->id,
    'nama_peminjam' => 'Ahmad',
    // ... fields lainnya
]);

// Auto call setDipinjam via controller
// $unit->status akan berubah ke 'dipinjam'
```

### Test 3: View History
```php
$unit = InventarisDetailUnit::find(1);

$history = $unit->history()
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($history as $h) {
    echo "{$h->jenis_aktivitas}: {$h->keterangan}\n";
}
```

### Test 4: Query Stats
```php
$inventaris = Inventaris::find(1);

$stats = [
    'total_units' => $inventaris->detailUnits()->count(),
    'tersedia' => $inventaris->detailUnitsTersedia()->count(),
    'dipinjam' => $inventaris->detailUnitsDipinjam()->count(),
    'maintenance' => $inventaris->detailUnits()->where('status', 'maintenance')->count(),
];
```

---

## 📋 Constants & Enums

### Jenis Aktivitas History
```php
'input'           // Unit baru ditambahkan
'pinjam'          // Unit dipinjam
'kembali'         // Unit dikembalikan
'maintenance'     // Masuk maintenance
'perbaikan'       // Selesai perbaikan
'update_kondisi'  // Update kondisi manual
'pindah_lokasi'   // Pindah lokasi
'rusak'           // Set rusak permanen
'hilang'          // Set hilang
'hapus'           // Soft delete
```

### Status Unit
```php
'tersedia'        // Available for borrowing
'dipinjam'        // Currently borrowed
'maintenance'     // Under maintenance/repair
'rusak_permanen'  // Permanently damaged
'dihapus'         // Soft deleted
```

### Kondisi Unit
```php
'baik'           // Good condition
'rusak_ringan'   // Minor damage
'rusak_berat'    // Major damage
'hilang'         // Lost/missing
```

---

## 🔐 Authorization (Opsional)

```php
// Policy untuk unit management
Gate::define('manage-inventaris-units', function ($user) {
    return $user->hasRole('admin') || $user->hasPermission('inventaris.manage');
});

// Di controller:
$this->authorize('manage-inventaris-units');
```

---

**Reference**: Lihat kode lengkap di model & controller untuk detail implementasi.
