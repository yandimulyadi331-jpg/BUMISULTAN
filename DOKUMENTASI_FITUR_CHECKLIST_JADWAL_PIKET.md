# 📋 FITUR CHECKLIST BERDASARKAN JADWAL PIKET - DOKUMENTASI LENGKAP

## 🎯 Ringkasan Perubahan

**Konteks Sebelumnya:**
- Checklist perawatan/gedung dibuat tanpa terikat pada jadwal piket
- Checklist bisa dikerjakan kapan saja
- Reset checklist harian (tengah malam)

**Konteks Baru:**
- Checklist TERIKAT pada jadwal piket karyawan
- Checklist hanya bisa dikerjakan saat DALAM jam piket
- Checklist tidak bisa dikerjakan di LUAR jam piket
- Reset checklist menurut JAM PIKET, bukan harian
- Klasifikasi checklist di aplikasi mobile sesuai jadwal piket

---

## 📊 ANALISIS KEBUTUHAN

### 1. Jadwal Piket yang Ada di Aplikasi
Diasumsikan sudah ada tabel/data jadwal piket dengan struktur:
```
jadwal_piket:
  - id
  - karyawan_id
  - hari_mulai (Senin, Selasa, dll)
  - jam_mulai (08:00)
  - jam_selesai (20:00)
  - departemen
  - active
```

### 2. Kebutuhan Utama

| Aspek | Kebutuhan |
|-------|----------|
| **Input Checklist** | Kolom "Jadwal Piket" di form tambah/edit perawatan |
| **Klasifikasi** | Checklist digroup berdasarkan jadwal piket |
| **Aksesibilitas** | Checklist hanya buka saat jam piket berlaku |
| **Pembatasan** | Checklist tidak bisa dikerjakan di luar jam piket |
| **Reset** | Menurut jam piket, bukan harian |
| **Keharusan** | Checklist harus selesai untuk bisa absen pulang |
| **Fleksibilitas** | Jika checklist sudah selesai, bisa absen pulang kapan saja |

### 3. Use Case Detil

**Skenario:**
- Karyawan shift pagi: 08:00 - 20:00
- Ada 3 checklist untuk shift pagi

**Timeline:**
```
08:00 - 20:00 (JAM PIKET)
├─ 08:00 → Checklist terbuka, bisa dikerjakan
├─ 15:00 → Checklist terbuka, bisa dikerjakan
├─ 19:45 → Checklist terbuka, bisa dikerjakan
├─ 20:30 → Checklist DITUTUP, tidak bisa dikerjakan (sudah di luar jam)
└─ 23:00 → Checklist DITUTUP (baru buka besok jam 08:00 lagi)

21:00 - 23:59 (LUAR JAM PIKET)
├─ Checklist tidak ada di list karyawan (hidden)
└─ Jika sudah selesai → bisa absen pulang

00:00 - 07:59 (LUAR JAM PIKET)
├─ Checklist tidak ada di list karyawan (hidden)
└─ Jika sudah selesai → bisa absen pulang
```

---

## 🗄️ PERUBAHAN DATABASE

### 1. Tabel `master_checklists` (Existing)
**Tambahan Kolom:**
```sql
ALTER TABLE master_checklists ADD COLUMN jadwal_piket_id BIGINT UNSIGNED AFTER deskripsi;
ALTER TABLE master_checklists ADD FOREIGN KEY (jadwal_piket_id) REFERENCES jadwal_pikets(id) ON DELETE SET NULL;

-- OR jika jadwal piket banyak:
ALTER TABLE master_checklists ADD COLUMN jadwal_piket_ids JSON AFTER deskripsi;
-- Format: [1, 3, 5] → untuk multiple jadwal piket
```

### 2. Tabel `checklist_karyawans` (Existing)
**Perubahan Logic:**
```sql
-- Kolom status_validity untuk tracking kevalidan checklist
ALTER TABLE checklist_karyawans ADD COLUMN status_validity VARCHAR(20) AFTER status;
-- Nilai: 'valid', 'expired', 'outside_shift'

-- Kolom untuk tracking kapan checklist di-reset
ALTER TABLE checklist_karyawans ADD COLUMN last_reset_at TIMESTAMP AFTER updated_at;
```

### 3. Tabel Baru (Opsional): `jadwal_piket_history`
Untuk tracking karyawan pindah shift:
```sql
CREATE TABLE jadwal_piket_histories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    jadwal_piket_id BIGINT UNSIGNED,
    mulai_berlaku DATE NOT NULL,
    berakhir_berlaku DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawans(id),
    FOREIGN KEY (jadwal_piket_id) REFERENCES jadwal_pikets(id)
);
```

---

## 🔄 FLOW LOGIKA

### Flow 1: Admin Input/Edit Perawatan
```
Admin masuk Menu Perawatan (Master Checklist)
    ↓
Klik "Tambah Checklist" / Edit
    ↓
Form menampilkan kolom baru:
├─ Nama Kegiatan
├─ Deskripsi
├─ Jadwal Piket ← NEW (Dropdown/Multi-select)
│  └─ Pagi (08:00 - 20:00)
│  └─ Siang (06:00 - 18:00)
│  └─ Malam (20:00 - 06:00)
├─ Kategori
└─ ...field lainnya
    ↓
Admin pilih jadwal piket → Save
    ↓
Data tersimpan dengan jadwal_piket_id
```

### Flow 2: Sistem Klasifikasi Checklist (Job/Scheduler)
```
Setiap 1 menit → Run Job: ClassifyChecklistBySchedule
    ↓
Untuk setiap karyawan:
  ├─ Get jadwal piket hari ini
  │  └─ Contoh: 08:00 - 20:00
  ├─ Get current time
  │  └─ Contoh: 14:30
  ├─ Get semua master checklist
  │  └─ Filter yang sesuai jadwal piket karyawan
  ├─ Untuk setiap checklist:
  │  ├─ Cek apakah sudah ada record di checklist_karyawans hari ini
  │  │  ├─ JIKA ADA: Skip (sudah dikerjakan)
  │  │  └─ JIKA TIDAK ADA: Create record baru dengan status 'available'
  │  └─ Set status_validity sesuai jam:
  │     ├─ Dalam jam piket → 'valid'
  │     └─ Luar jam piket → 'expired'
  └─ Process next karyawan
    ↓
Checklist siap di aplikasi mobile
```

### Flow 3: Karyawan Buka Aplikasi
```
Karyawan buka aplikasi mobile
    ↓
GET /api/checklist/karyawan
    ├─ Get jadwal piket karyawan hari ini
    ├─ Get semua checklist untuk jadwal piket tersebut
    ├─ Filter berdasarkan jam saat ini (valid/expired)
    └─ Return grouped by jadwal_piket + status validity
    ↓
Tampilkan di UI:
┌─ JADWAL PIKET PAGI (08:00 - 20:00) ← Group Name
│  ├─ Status: AKTIF (sedang berlangsung) / TERTUTUP (belum/sudah)
│  ├─ Waktu Tersisa: 4 jam 30 menit
│  └─ Checklist Items:
│     ├─ [✓] Lap Lantai
│     ├─ [ ] Sapu Lantai
│     └─ [ ] Cuci Piring
├─
└─ JADWAL PIKET MALAM (20:00 - 06:00)
   ├─ Status: TERTUTUP (belum berlangsung)
   ├─ Waktu Dimulai: 5 jam 30 menit
   └─ Checklist Items: [DISABLED/HIDDEN]
```

### Flow 4: Karyawan Checklist (Dalam Jam Piket)
```
Karyawan jam 14:30, shift pagi 08:00-20:00
    ↓
Klik checklist → Bisa open & selesaikan ✅
    ↓
POST /api/checklist/karyawan/complete
├─ Validasi: current_time dalam jam piket?
│  └─ YES → Process (lanjut)
│  └─ NO → Return error (JANGAN proses)
├─ Update status → 'completed'
└─ Return success
```

### Flow 5: Karyawan Checklist (Luar Jam Piket)
```
Karyawan jam 21:30, shift pagi 08:00-20:00
    ↓
Checklist TIDAK ditampilkan di list (hidden)
    ↓
Jika ada request langsung ke endpoint:
  POST /api/checklist/karyawan/complete
    └─ Validasi: current_time dalam jam piket?
       ├─ YES → Process
       └─ NO → Return error "Diluar jam piket Anda"
```

### Flow 6: Reset Checklist (Menurut Jam Piket)
```
Setiap 1 menit → Run Job: ResetChecklistBySchedule
    ↓
Untuk setiap karyawan:
  ├─ Get jadwal piket hari ini
  │  └─ Contoh: 08:00 - 20:00
  ├─ Get jam selesai jadwal piket
  │  └─ Contoh: 20:00
  ├─ Cek: apakah current_time > jam_selesai?
  │  ├─ YES → Reset semua checklist untuk jadwal piket ini
  │  │  ├─ Set status: 'pending'
  │  │  ├─ Update last_reset_at: now()
  │  │  └─ Hapus data lama (atau soft-delete)
  │  └─ NO → Skip
  └─ Process next karyawan
    ↓
Besok jam 08:00 → Checklist ready lagi
```

---

## 🔌 API ENDPOINTS

### 1. GET /api/checklist/karyawan
**Purpose:** Get checklist grouped by jadwal piket + validity status
**Response:**
```json
{
  "success": true,
  "current_shift": {
    "id": 1,
    "nama": "Pagi",
    "jam_mulai": "08:00",
    "jam_selesai": "20:00",
    "status": "AKTIF",
    "waktu_tersisa_menit": 270,
    "checklists": [
      {
        "id": 1,
        "nama": "Lap Lantai",
        "deskripsi": "Lap seluruh lantai",
        "status": "pending",
        "is_valid": true,
        "created_at": "2025-01-22"
      }
    ]
  },
  "upcoming_shifts": [
    {
      "id": 2,
      "nama": "Malam",
      "jam_mulai": "20:00",
      "jam_selesai": "06:00",
      "status": "TERTUTUP",
      "waktu_dimulai_menit": 330,
      "checklists": []
    }
  ],
  "completed_today": [...]
}
```

### 2. POST /api/checklist/karyawan/{id}/complete
**Purpose:** Complete checklist (dengan validasi jam piket)
**Request:**
```json
{
  "id": 1
}
```
**Response:**
```json
{
  "success": true,
  "message": "Checklist berhasil diselesaikan",
  "completed_at": "2025-01-22 14:35:00"
}
```
**Error Cases:**
```json
{
  "success": false,
  "message": "Checklist diluar jam piket Anda. Silakan coba lagi saat jam piket berlangsung."
}
```

### 3. GET /api/checklist/jadwal-piket-karyawan
**Purpose:** Get jadwal piket karyawan (untuk init data)
**Response:**
```json
{
  "success": true,
  "jadwal_pikets": [
    {
      "id": 1,
      "nama": "Pagi",
      "jam_mulai": "08:00",
      "jam_selesai": "20:00",
      "hari": ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"],
      "is_active": true
    }
  ]
}
```

### 4. POST /api/checklist/master (Admin)
**Purpose:** Create master checklist dengan jadwal piket
**Request:**
```json
{
  "nama": "Lap Lantai",
  "deskripsi": "Lap seluruh lantai kantor",
  "jadwal_piket_ids": [1, 2],
  "kategori": "Kebersihan",
  "points": 5
}
```

---

## 💾 MIGRATION FILES

### Migration 1: Add Jadwal Piket ke Master Checklists
```php
// database/migrations/2025_01_22_add_jadwal_piket_to_master_checklists.php
Schema::table('master_checklists', function (Blueprint $table) {
    $table->foreignId('jadwal_piket_id')
          ->nullable()
          ->after('deskripsi')
          ->constrained('jadwal_pikets')
          ->onDelete('set null');
});
```

### Migration 2: Update Checklist Karyawan
```php
// database/migrations/2025_01_22_update_checklist_karyawans.php
Schema::table('checklist_karyawans', function (Blueprint $table) {
    $table->enum('status_validity', ['valid', 'expired', 'outside_shift'])
          ->default('valid')
          ->after('status');
    
    $table->timestamp('last_reset_at')
          ->nullable()
          ->after('updated_at');
});
```

---

## 📁 FILE-FILE YANG PERLU DIUBAH/DIBUAT

### Backend (Laravel)

1. **Migration Files** (NEW)
   - `database/migrations/2025_01_22_add_jadwal_piket_to_master_checklists.php`
   - `database/migrations/2025_01_22_update_checklist_karyawans.php`

2. **Models** (UPDATE)
   - `app/Models/MasterChecklist.php` - Add relation ke JadwalPiket
   - `app/Models/ChecklistKaryawan.php` - Add status_validity, last_reset_at
   - `app/Models/JadwalPiket.php` - Add relation ke MasterChecklist
   - `app/Models/Karyawan.php` - Add relation ke JadwalPiket

3. **Controllers** (UPDATE)
   - `app/Http/Controllers/Api/ChecklistController.php` - Update methods:
     - `getChecklistKaryawan()` - Add jadwal piket grouping & validity check
     - `completeChecklist()` - Add jam piket validation
   - `app/Http/Controllers/Admin/MasterChecklistController.php` - Add jadwal_piket_id input

4. **Jobs/Schedulers** (NEW)
   - `app/Jobs/ClassifyChecklistBySchedule.php` - Classify checklist by jadwal piket
   - `app/Jobs/ResetChecklistBySchedule.php` - Reset berdasarkan jam piket

5. **Services** (NEW)
   - `app/Services/JadwalPiketService.php` - Helper methods:
     - `isInSchedule($jadwalPiket, $time)` - Cek apakah dalam jam piket
     - `getActiveScheduleForKaryawan($karyawanId)` - Get jadwal piket aktif saat ini
     - `getTimeUntilScheduleEnd($jadwalPiket)` - Hitung waktu tersisa
     - `shouldResetSchedule($jadwalPiket, $lastReset)` - Cek apakah perlu reset

6. **Routes** (UPDATE)
   - `routes/api.php` - Add/update endpoints

### Frontend (Web Admin)

1. **Views** (UPDATE)
   - `resources/views/admin/master-checklist/form.blade.php` - Add jadwal piket select
   - `resources/views/admin/master-checklist/index.blade.php` - Display jadwal piket info

2. **JavaScript/AJAX** (UPDATE)
   - Update form submission untuk include jadwal_piket_ids

### Frontend (Mobile/SPA)

1. **Components** (NEW/UPDATE)
   - Checklist list grouped by jadwal piket
   - Jadwal piket header dengan status (AKTIF/TERTUTUP)
   - Countdown timer untuk waktu tersisa
   - Disabled state untuk checklist diluar jam piket

2. **Store/State** (UPDATE)
   - Add jadwal_piket_aktif state
   - Add checklist_validity status

---

## 🚀 IMPLEMENTASI STEP-BY-STEP

### Phase 1: Database & Models (Week 1)
- [ ] Create migrations
- [ ] Run migrations
- [ ] Update models dengan relations

### Phase 2: API Endpoints (Week 1)
- [ ] Update ChecklistController endpoints
- [ ] Add validation logic untuk jam piket
- [ ] Test endpoints via Postman

### Phase 3: Jobs/Schedulers (Week 2)
- [ ] Create ClassifyChecklistBySchedule job
- [ ] Create ResetChecklistBySchedule job
- [ ] Register ke schedule (kernel.php)
- [ ] Test dengan manual trigger

### Phase 4: Admin UI (Week 2)
- [ ] Update master checklist form untuk input jadwal piket
- [ ] Add display jadwal piket di list view
- [ ] Test create/edit checklist

### Phase 5: Mobile/Employee UI (Week 3)
- [ ] Update checklist list component (group by jadwal piket)
- [ ] Add validity check untuk disable/hide checklist
- [ ] Add countdown timer
- [ ] Test dengan berbagai skenario waktu

### Phase 6: Testing & Refinement (Week 3)
- [ ] QA testing semua flows
- [ ] Edge case handling
- [ ] Performance optimization

---

## 📋 CHECKLIST VALIDASI

**Sebelum Go-Live:**
- [ ] Database migration berjalan lancar
- [ ] API endpoints bekerja sesuai spec
- [ ] Job scheduler berjalan setiap 1 menit
- [ ] Checklist hanya terbuka dalam jam piket
- [ ] Checklist ter-reset sesuai jadwal piket
- [ ] UI mobile menampilkan klasifikasi dengan benar
- [ ] Countdown timer akurat
- [ ] Error messages jelas untuk user
- [ ] Performance test dengan 1000+ karyawan
- [ ] Load test untuk job scheduler

---

## 🔍 DEBUGGING TIPS

```php
// Log untuk debugging flow
Log::info('Checklist validity check', [
    'karyawan_id' => $karyawanId,
    'jadwal_piket' => $jadwalPiket,
    'current_time' => now(),
    'is_valid' => $this->isInSchedule($jadwalPiket, now())
]);

// Cek jadwal piket karyawan saat ini
$karyawan = Karyawan::find(1);
$jadwalAktif = $this->jadwalPiketService->getActiveScheduleForKaryawan($karyawan->id);
dd($jadwalAktif);

// Manual reset checklist (testing)
$checklist = ChecklistKaryawan::where([
    'karyawan_id' => 1,
    'tanggal' => today()
])->update(['status' => 'pending', 'last_reset_at' => now()]);
```

---

## 📌 CATATAN PENTING

1. **Timezone:** Pastikan timezone aplikasi konsisten (gunakan APP_TIMEZONE di .env)
2. **Jadwal Piket:** Asumsi jadwal piket sudah ada dan terstruktur dengan baik
3. **Reset Schedule:** Harus run minimal setiap 1 menit untuk akurasi
4. **Multiple Jadwal Piket:** Jika satu checklist untuk multiple jadwal piket, gunakan JSON array
5. **Backward Compatibility:** Checklist yang tidak punya jadwal_piket_id tetap berfungsi seperti dulu
6. **Performance:** Index pada (karyawan_id, tanggal) di checklist_karyawans

---

## 🎓 REFERENSI

- [Laravel Job Scheduling](https://laravel.com/docs/10.x/scheduling)
- [Laravel Task Scheduling](https://laravel.com/docs/10.x/scheduling#running-jobs)
- [Eloquent Relationships](https://laravel.com/docs/10.x/eloquent-relationships)

