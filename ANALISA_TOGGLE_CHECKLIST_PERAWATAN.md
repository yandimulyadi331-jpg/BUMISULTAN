# 📋 Analisa Fitur Toggle Checklist Perawatan Gedung

## 🎯 Objektif

Implementasi toggle untuk mengaktifkan/menonaktifkan checklist perawatan dengan fitur:
1. **Toggle ON** → Checklist aktif, karyawan HARUS menyelesaikan checklist sebelum absen pulang
2. **Toggle OFF** → Checklist nonaktif, karyawan bisa langsung absen pulang tanpa checklist

---

## 📊 Analisa Current System

### Database Tables (Existing)

#### 1. `master_perawatan`
```sql
- id
- nama_kegiatan
- deskripsi
- tipe_periode (harian, mingguan, bulanan, tahunan)
- urutan
- kategori
- is_active (BOOLEAN) ← Saat ini hanya untuk aktif/nonaktif master
- timestamps
- softDeletes
```

#### 2. `perawatan_log`
```sql
- id
- master_perawatan_id
- user_id (siapa yang execute)
- tanggal_eksekusi
- waktu_eksekusi
- status (completed, skipped)
- catatan
- foto_bukti
- periode_key (harian_2024-11-14, mingguan_2024-W46, dll)
- timestamps
```

#### 3. `perawatan_status_periode`
```sql
- id
- tipe_periode (harian, mingguan, bulanan, tahunan)
- periode_key
- periode_start
- periode_end
- total_checklist
- total_completed
- is_completed (BOOLEAN)
- completed_at
- completed_by
- timestamps
```

#### 4. `presensi_yayasan` (Absensi Karyawan)
```sql
- id
- kode_yayasan
- tanggal
- kode_jam_kerja
- jam_in
- jam_out ← Absen pulang
- foto_in
- foto_out
- lokasi_in
- lokasi_out
- status
- keterangan
- timestamps
```

### Current Flow

1. **Checklist Perawatan**
   - Admin buat master checklist (by tipe periode)
   - Karyawan akses halaman checklist (harian/mingguan/bulanan/tahunan)
   - Karyawan centang checklist → data masuk `perawatan_log`
   - Sistem track status per periode di `perawatan_status_periode`

2. **Absensi Karyawan**
   - Karyawan absen masuk (jam_in)
   - Karyawan kerja
   - Karyawan absen pulang (jam_out)
   - **TIDAK ADA** koneksi dengan checklist perawatan

### Gap Analysis

❌ **Tidak ada relasi** antara checklist perawatan dengan absensi pulang  
❌ **Tidak ada kolom** untuk toggle aktif/nonaktif per tipe periode  
❌ **Tidak ada validasi** checklist sebelum absen pulang  
❌ **Tidak ada notifikasi** karyawan untuk checklist wajib  

---

## 🎨 Design Solution

### 1. Database Schema Changes

#### A. Tabel Baru: `checklist_periode_config`

Untuk menyimpan konfigurasi toggle per tipe periode:

```sql
CREATE TABLE checklist_periode_config (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipe_periode ENUM('harian', 'mingguan', 'bulanan', 'tahunan') UNIQUE,
    is_enabled BOOLEAN DEFAULT TRUE COMMENT 'Toggle ON/OFF untuk periode ini',
    is_mandatory BOOLEAN DEFAULT FALSE COMMENT 'Apakah wajib diselesaikan sebelum absen pulang',
    keterangan TEXT NULL COMMENT 'Catatan untuk karyawan',
    dibuat_oleh BIGINT UNSIGNED NULL,
    diubah_oleh BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (diubah_oleh) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_tipe_enabled (tipe_periode, is_enabled)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Columns:**
- `tipe_periode`: harian/mingguan/bulanan/tahunan (UNIQUE)
- `is_enabled`: TRUE = toggle ON, FALSE = toggle OFF
- `is_mandatory`: TRUE = harus selesai checklist sebelum absen pulang
- `keterangan`: Info untuk karyawan (misal: "Checklist harian sedang dinonaktifkan")

#### B. Alter Table: `presensi_yayasan`

Tambah kolom untuk tracking checklist:

```sql
ALTER TABLE presensi_yayasan 
ADD COLUMN checklist_harian_completed BOOLEAN DEFAULT FALSE COMMENT 'Apakah checklist harian sudah diselesaikan',
ADD COLUMN checklist_harian_skipped BOOLEAN DEFAULT FALSE COMMENT 'Apakah checklist harian di-skip (karena nonaktif)',
ADD COLUMN checklist_harian_periode_key VARCHAR(50) NULL COMMENT 'Periode key checklist yang divalidasi',
ADD INDEX idx_checklist_status (tanggal, checklist_harian_completed);
```

**Penjelasan:**
- `checklist_harian_completed`: TRUE jika karyawan sudah selesai checklist harian
- `checklist_harian_skipped`: TRUE jika checklist nonaktif (tidak wajib)
- `checklist_harian_periode_key`: Track periode checklist mana yang divalidasi

**Note:** Untuk mingguan/bulanan/tahunan bisa ditambahkan kolom serupa jika diperlukan.

---

### 2. Business Logic Flow

#### Flow A: Toggle ON (Checklist Aktif & Wajib)

```
1. Admin aktifkan toggle untuk "Checklist Harian"
   → checklist_periode_config: is_enabled = TRUE, is_mandatory = TRUE
   
2. Karyawan mulai kerja (absen masuk)
   
3. Karyawan kerjakan checklist harian
   → Update perawatan_log (per item checklist)
   → Update perawatan_status_periode (total_completed++)
   
4. Karyawan coba absen pulang
   ├─ Sistem CEK: Apakah checklist harian enabled & mandatory?
   │  ├─ CEK: Apakah karyawan sudah selesaikan semua checklist harian?
   │  │  ├─ Sudah selesai → ✅ Izinkan absen pulang
   │  │  │  └─ Update presensi_yayasan.checklist_harian_completed = TRUE
   │  │  └─ Belum selesai → ❌ TOLAK absen pulang
   │  │     └─ Show error: "Anda harus menyelesaikan checklist harian terlebih dahulu"
   │  └─ Checklist tidak enabled → ✅ Izinkan absen pulang
   │     └─ Update presensi_yayasan.checklist_harian_skipped = TRUE
```

#### Flow B: Toggle OFF (Checklist Nonaktif)

```
1. Admin nonaktifkan toggle untuk "Checklist Harian"
   → checklist_periode_config: is_enabled = FALSE
   
2. Karyawan mulai kerja (absen masuk)
   
3. Karyawan coba akses halaman checklist harian
   → Show banner: "Checklist harian sedang dinonaktifkan"
   → Tombol checklist disabled / form readonly
   
4. Karyawan coba absen pulang
   ├─ Sistem CEK: Apakah checklist harian enabled?
   │  ├─ Enabled = FALSE → ✅ Langsung izinkan absen pulang
   │  │  └─ Update presensi_yayasan.checklist_harian_skipped = TRUE
   │  └─ Enabled = TRUE → Lakukan validasi (Flow A step 4)
```

#### Flow C: Toggle ON tapi NOT Mandatory

```
1. Admin aktifkan toggle tapi set is_mandatory = FALSE
   → checklist_periode_config: is_enabled = TRUE, is_mandatory = FALSE
   
2. Karyawan mulai kerja
   
3. Karyawan bisa pilih:
   ├─ Kerjakan checklist → Status "completed"
   └─ Tidak kerjakan checklist → Status "skipped"
   
4. Karyawan coba absen pulang
   → Sistem CEK: is_mandatory = FALSE
   → ✅ Langsung izinkan absen pulang (tidak ada validasi)
   → Show info: "Checklist harian opsional (tidak wajib diselesaikan)"
```

---

### 3. UI/UX Design

#### A. Toggle Switch di Dashboard Admin

**Lokasi:** `/perawatan` (halaman utama)

```html
┌─────────────────────────────────────────────────────────┐
│  🏢 MANAJEMEN PERAWATAN GEDUNG                         │
│  Sistem kontrol perawatan dan kebersihan gedung         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  📋 Konfigurasi Checklist Per Periode                   │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📅 Checklist Harian                                    │
│  Reset setiap hari pukul 00:00                          │
│                                                          │
│  [🔄 Aktif]  ON ●━━━━━ OFF                              │
│  [✓] Wajib diselesaikan sebelum absen pulang           │
│                                                          │
│  Keterangan (opsional):                                 │
│  [Checklist harian wajib diselesaikan...    ]          │
│                                                          │
│  [💾 Simpan Konfigurasi]                                │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📅 Checklist Mingguan                                  │
│  Reset setiap Senin pukul 00:00                         │
│                                                          │
│  [🔄 Nonaktif]  ON ━━━━━● OFF                           │
│  [ ] Wajib diselesaikan sebelum absen pulang           │
│                                                          │
│  Keterangan:                                            │
│  [Checklist mingguan sedang dinonaktifkan...]          │
│                                                          │
│  [💾 Simpan Konfigurasi]                                │
├─────────────────────────────────────────────────────────┤
│  (Similar untuk Bulanan & Tahunan)                      │
└─────────────────────────────────────────────────────────┘
```

#### B. Banner di Halaman Checklist (untuk Karyawan)

**Scenario 1: Checklist AKTIF & WAJIB**

```html
┌─────────────────────────────────────────────────────────┐
│  ℹ️  CHECKLIST HARIAN WAJIB DISELESAIKAN                │
│  Anda harus menyelesaikan checklist ini sebelum absen  │
│  pulang. Total: 10 item | Selesai: 3 item (30%)       │
└─────────────────────────────────────────────────────────┘
```

**Scenario 2: Checklist NONAKTIF**

```html
┌─────────────────────────────────────────────────────────┐
│  ⚠️  CHECKLIST HARIAN SEDANG DINONAKTIFKAN              │
│  Checklist harian tidak aktif untuk saat ini.          │
│  Anda dapat absen pulang tanpa menyelesaikan checklist.│
└─────────────────────────────────────────────────────────┘

[Semua checkbox disabled / readonly]
```

**Scenario 3: Checklist AKTIF tapi OPSIONAL**

```html
┌─────────────────────────────────────────────────────────┐
│  ℹ️  CHECKLIST HARIAN OPSIONAL (TIDAK WAJIB)            │
│  Checklist ini tidak wajib diselesaikan. Anda dapat    │
│  absen pulang meskipun belum menyelesaikan checklist.  │
└─────────────────────────────────────────────────────────┘
```

#### C. Validasi saat Absen Pulang

**Scenario: Checklist WAJIB tapi BELUM SELESAI**

```javascript
// SweetAlert saat klik tombol absen pulang
Swal.fire({
    icon: 'warning',
    title: 'Checklist Belum Selesai!',
    html: `
        <p>Anda harus menyelesaikan <strong>Checklist Harian</strong> terlebih dahulu.</p>
        <br>
        <p>Progress saat ini:</p>
        <div class="progress mb-2">
            <div class="progress-bar bg-warning" style="width: 30%">30%</div>
        </div>
        <p class="text-muted">3 dari 10 item sudah diselesaikan</p>
    `,
    showCancelButton: true,
    confirmButtonText: 'Buka Checklist',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#3085d6'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = '/perawatan/checklist-harian';
    }
});
```

---

### 4. API Endpoints

#### A. Admin - Toggle Configuration

```php
// GET /perawatan/config
// Tampilkan halaman konfigurasi toggle

// POST /perawatan/config/update
// Update konfigurasi toggle
Request: {
    "tipe_periode": "harian",
    "is_enabled": true,
    "is_mandatory": true,
    "keterangan": "Checklist harian wajib diselesaikan sebelum pulang"
}

Response: {
    "success": true,
    "message": "Konfigurasi checklist harian berhasil diupdate",
    "data": {
        "tipe_periode": "harian",
        "is_enabled": true,
        "is_mandatory": true,
        "status_text": "Aktif & Wajib"
    }
}
```

#### B. Karyawan - Cek Status Checklist

```php
// GET /api/perawatan/status/{tipe_periode}
// Cek apakah checklist aktif & wajib

Response: {
    "success": true,
    "data": {
        "tipe_periode": "harian",
        "is_enabled": true,
        "is_mandatory": true,
        "keterangan": "Checklist harian wajib diselesaikan...",
        "current_periode_key": "harian_2026-01-03",
        "total_checklist": 10,
        "total_completed": 3,
        "completion_percentage": 30,
        "is_all_completed": false,
        "can_checkout": false // Apakah boleh absen pulang
    }
}
```

#### C. Karyawan - Validasi sebelum Absen Pulang

```php
// POST /api/presensi/validate-checkout
// Validasi sebelum absen pulang

Request: {
    "kode_yayasan": "251200002",
    "tanggal": "2026-01-03",
    "jam_out": "17:00:00"
}

Response (jika checklist wajib & belum selesai): {
    "success": false,
    "can_checkout": false,
    "reason": "checklist_incomplete",
    "message": "Anda harus menyelesaikan Checklist Harian terlebih dahulu",
    "data": {
        "tipe_periode": "harian",
        "total_checklist": 10,
        "total_completed": 3,
        "remaining": 7,
        "completion_percentage": 30,
        "checklist_url": "/perawatan/checklist-harian"
    }
}

Response (jika checklist nonaktif atau sudah selesai): {
    "success": true,
    "can_checkout": true,
    "message": "Anda dapat absen pulang",
    "data": {
        "checklist_status": "skipped" // atau "completed"
    }
}
```

---

### 5. Validation Rules

#### Rule 1: Checklist ENABLED & MANDATORY

```php
if (
    $config->is_enabled === true && 
    $config->is_mandatory === true
) {
    // CEK: Apakah karyawan sudah selesaikan semua checklist?
    $statusPeriode = PerawatanStatusPeriode::where('tipe_periode', 'harian')
        ->where('periode_key', $periodeKey)
        ->first();
    
    if ($statusPeriode->is_completed === false) {
        // TOLAK absen pulang
        return [
            'can_checkout' => false,
            'message' => 'Checklist harian belum selesai'
        ];
    }
}
```

#### Rule 2: Checklist DISABLED

```php
if ($config->is_enabled === false) {
    // Langsung izinkan absen pulang
    // Update presensi_yayasan.checklist_harian_skipped = TRUE
    return [
        'can_checkout' => true,
        'checklist_status' => 'skipped'
    ];
}
```

#### Rule 3: Checklist ENABLED tapi NOT MANDATORY

```php
if (
    $config->is_enabled === true && 
    $config->is_mandatory === false
) {
    // Langsung izinkan absen pulang (opsional)
    return [
        'can_checkout' => true,
        'checklist_status' => 'optional'
    ];
}
```

---

## 📝 Implementation Steps

### Phase 1: Database Migration

```bash
# 1. Create migration untuk tabel config
php artisan make:migration create_checklist_periode_config_table

# 2. Create migration untuk alter presensi_yayasan
php artisan make:migration add_checklist_tracking_to_presensi_yayasan

# 3. Run migrations
php artisan migrate

# 4. Seed default config
php artisan db:seed --class=ChecklistPeriodeConfigSeeder
```

### Phase 2: Model & Controller

```bash
# 1. Create model
php artisan make:model ChecklistPeriodeConfig

# 2. Update ManajemenPerawatanController
#    - Tambah method showConfig()
#    - Tambah method updateConfig()
#    - Tambah method getStatusChecklist()

# 3. Create middleware untuk validasi checklist
php artisan make:middleware ValidateChecklistBeforeCheckout
```

### Phase 3: Frontend

```bash
# 1. Create view untuk config page
resources/views/perawatan/config.blade.php

# 2. Update view checklist
resources/views/perawatan/checklist.blade.php
# - Tambah banner status (aktif/nonaktif)
# - Tambah progress bar
# - Disable checkbox jika nonaktif

# 3. Update view absensi
resources/views/yayasan-presensi/*
# - Tambah validasi sebelum absen pulang
# - Show SweetAlert jika checklist belum selesai
```

### Phase 4: Testing

```bash
# Test Scenario 1: Toggle ON & Mandatory
1. Admin aktifkan toggle harian
2. Karyawan buka checklist harian
3. Karyawan centang 3 dari 10 item
4. Karyawan coba absen pulang → DITOLAK
5. Karyawan selesaikan semua checklist
6. Karyawan absen pulang → BERHASIL

# Test Scenario 2: Toggle OFF
1. Admin nonaktifkan toggle harian
2. Karyawan buka checklist harian → Show banner "nonaktif"
3. Checkbox disabled
4. Karyawan absen pulang → BERHASIL (tanpa checklist)

# Test Scenario 3: Toggle ON tapi NOT Mandatory
1. Admin aktifkan toggle tapi uncheck "wajib"
2. Karyawan bisa skip checklist
3. Karyawan absen pulang → BERHASIL (meskipun checklist kosong)
```

---

## 🔧 Code Examples

### Migration: Create Config Table

```php
Schema::create('checklist_periode_config', function (Blueprint $table) {
    $table->id();
    $table->enum('tipe_periode', ['harian', 'mingguan', 'bulanan', 'tahunan'])->unique();
    $table->boolean('is_enabled')->default(true)->comment('Toggle ON/OFF');
    $table->boolean('is_mandatory')->default(false)->comment('Wajib sebelum absen pulang');
    $table->text('keterangan')->nullable();
    $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->onDelete('set null');
    $table->foreignId('diubah_oleh')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
    
    $table->index(['tipe_periode', 'is_enabled']);
});
```

### Migration: Alter Presensi Table

```php
Schema::table('presensi_yayasan', function (Blueprint $table) {
    $table->boolean('checklist_harian_completed')->default(false)->after('status');
    $table->boolean('checklist_harian_skipped')->default(false)->after('checklist_harian_completed');
    $table->string('checklist_harian_periode_key', 50)->nullable()->after('checklist_harian_skipped');
    
    $table->index(['tanggal', 'checklist_harian_completed']);
});
```

### Seeder: Default Config

```php
class ChecklistPeriodeConfigSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            [
                'tipe_periode' => 'harian',
                'is_enabled' => true,
                'is_mandatory' => true,
                'keterangan' => 'Checklist harian wajib diselesaikan sebelum absen pulang',
            ],
            [
                'tipe_periode' => 'mingguan',
                'is_enabled' => true,
                'is_mandatory' => false,
                'keterangan' => 'Checklist mingguan bersifat opsional',
            ],
            [
                'tipe_periode' => 'bulanan',
                'is_enabled' => true,
                'is_mandatory' => false,
                'keterangan' => null,
            ],
            [
                'tipe_periode' => 'tahunan',
                'is_enabled' => false,
                'is_mandatory' => false,
                'keterangan' => 'Checklist tahunan sedang dinonaktifkan',
            ],
        ];
        
        foreach ($configs as $config) {
            ChecklistPeriodeConfig::create($config);
        }
    }
}
```

### Controller: Update Config

```php
public function updateConfig(Request $request)
{
    $validated = $request->validate([
        'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
        'is_enabled' => 'required|boolean',
        'is_mandatory' => 'required|boolean',
        'keterangan' => 'nullable|string|max:500',
    ]);
    
    $config = ChecklistPeriodeConfig::updateOrCreate(
        ['tipe_periode' => $validated['tipe_periode']],
        [
            'is_enabled' => $validated['is_enabled'],
            'is_mandatory' => $validated['is_mandatory'],
            'keterangan' => $validated['keterangan'],
            'diubah_oleh' => Auth::id(),
        ]
    );
    
    return response()->json([
        'success' => true,
        'message' => 'Konfigurasi checklist ' . $validated['tipe_periode'] . ' berhasil diupdate',
        'data' => $config
    ]);
}
```

### Controller: Validate Checkout

```php
public function validateCheckout(Request $request)
{
    $validated = $request->validate([
        'kode_yayasan' => 'required',
        'tanggal' => 'required|date',
    ]);
    
    // Cek config checklist harian
    $config = ChecklistPeriodeConfig::where('tipe_periode', 'harian')->first();
    
    // Jika nonaktif, langsung izinkan
    if (!$config || !$config->is_enabled) {
        return response()->json([
            'success' => true,
            'can_checkout' => true,
            'checklist_status' => 'disabled',
            'message' => 'Checklist nonaktif, Anda dapat absen pulang'
        ]);
    }
    
    // Jika aktif tapi tidak mandatory, langsung izinkan
    if (!$config->is_mandatory) {
        return response()->json([
            'success' => true,
            'can_checkout' => true,
            'checklist_status' => 'optional',
            'message' => 'Checklist opsional, Anda dapat absen pulang'
        ]);
    }
    
    // Jika mandatory, cek apakah sudah selesai
    $periodeKey = 'harian_' . $validated['tanggal'];
    $statusPeriode = PerawatanStatusPeriode::where('tipe_periode', 'harian')
        ->where('periode_key', $periodeKey)
        ->first();
    
    if (!$statusPeriode || $statusPeriode->total_completed < $statusPeriode->total_checklist) {
        $completed = $statusPeriode ? $statusPeriode->total_completed : 0;
        $total = $statusPeriode ? $statusPeriode->total_checklist : 0;
        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
        
        return response()->json([
            'success' => false,
            'can_checkout' => false,
            'reason' => 'checklist_incomplete',
            'message' => 'Anda harus menyelesaikan Checklist Harian terlebih dahulu',
            'data' => [
                'tipe_periode' => 'harian',
                'total_checklist' => $total,
                'total_completed' => $completed,
                'remaining' => $total - $completed,
                'completion_percentage' => $percentage,
                'checklist_url' => route('perawatan.checklist.harian')
            ]
        ], 400);
    }
    
    // Semua checklist selesai, izinkan absen pulang
    return response()->json([
        'success' => true,
        'can_checkout' => true,
        'checklist_status' => 'completed',
        'message' => 'Semua checklist selesai, Anda dapat absen pulang'
    ]);
}
```

---

## 📊 Impact Analysis

### Positive Impact

✅ **Flexibility**: Admin bisa aktif/nonaktifkan checklist sesuai kebutuhan  
✅ **Control**: Karyawan tidak bisa pulang sebelum checklist selesai (jika mandatory)  
✅ **Transparency**: Karyawan tahu status checklist (aktif/nonaktif/wajib/opsional)  
✅ **Tracking**: Sistem track apakah absensi pulang valid dengan checklist  
✅ **Reporting**: Data lengkap untuk audit (siapa skip, siapa complete)  

### Potential Issues

⚠️ **User Resistance**: Karyawan mungkin komplain jika checklist wajib  
⚠️ **Emergency Case**: Bagaimana jika karyawan harus pulang cepat (darurat)?  
⚠️ **System Down**: Jika sistem error, karyawan tidak bisa absen pulang  
⚠️ **Multi-Device**: Validasi harus work di mobile & desktop  

### Mitigation

1. **Emergency Override**: Tambah button "Emergency Checkout" untuk manager/supervisor
2. **Offline Mode**: Cache status checklist di localStorage untuk fallback
3. **Grace Period**: Kasih toleransi 15 menit setelah jam pulang sebelum enforce checklist
4. **Notification**: Push notification reminder ke karyawan 30 menit sebelum jam pulang

---

## 🚀 Recommended Priority

### Priority 1 (Must Have)
- ✅ Database migration (config & tracking columns)
- ✅ Toggle switch di admin dashboard
- ✅ Banner status di halaman checklist
- ✅ Validasi sebelum absen pulang (API)
- ✅ SweetAlert error jika checklist belum selesai

### Priority 2 (Should Have)
- ⚠️ Progress bar checklist di dashboard karyawan
- ⚠️ Push notification reminder
- ⚠️ Emergency override untuk manager
- ⚠️ Reporting dashboard (compliance rate)

### Priority 3 (Nice to Have)
- 💡 Auto-toggle based on schedule (misal: nonaktif di weekend)
- 💡 Individual exemption (karyawan tertentu di-exempt dari checklist)
- 💡 Gamification (badge untuk karyawan yang selalu complete checklist)
- 💡 Integration dengan payroll (insentif untuk compliance)

---

## ✅ Checklist Implementation

- [ ] Create migration `create_checklist_periode_config_table`
- [ ] Create migration `add_checklist_tracking_to_presensi_yayasan`
- [ ] Run migrations & seed default config
- [ ] Create model `ChecklistPeriodeConfig`
- [ ] Update controller `ManajemenPerawatanController` (config methods)
- [ ] Create API endpoint `/api/perawatan/status/{tipe}`
- [ ] Create API endpoint `/api/presensi/validate-checkout`
- [ ] Create view `perawatan/config.blade.php` (toggle UI)
- [ ] Update view `perawatan/checklist.blade.php` (banner status)
- [ ] Update view `yayasan-presensi/*` (validasi absen pulang)
- [ ] Create middleware `ValidateChecklistBeforeCheckout`
- [ ] Add routes untuk config & validation
- [ ] Test scenario 1: Toggle ON & Mandatory
- [ ] Test scenario 2: Toggle OFF
- [ ] Test scenario 3: Toggle ON tapi NOT Mandatory
- [ ] Documentation (user manual & API docs)

---

**Prepared by**: GitHub Copilot  
**Date**: 3 Januari 2026  
**Status**: ✅ Ready for Implementation  
**Estimated Time**: 2-3 hari development + 1 hari testing
