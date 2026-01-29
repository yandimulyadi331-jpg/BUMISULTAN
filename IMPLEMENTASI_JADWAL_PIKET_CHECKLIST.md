# 🚀 IMPLEMENTASI FITUR CHECKLIST JADWAL PIKET - PANDUAN STEP BY STEP

## 📋 RINGKASAN PERUBAHAN

Fitur baru ini memungkinkan:
- Checklist terikat pada jadwal piket karyawan
- Hanya bisa dikerjakan saat JAM PIKET berlangsung
- Riwayat checklist menampilkan jam dan nama karyawan yang ceklis
- Reset checklist menurut jam selesai shift, bukan harian
- Klasifikasi checklist berdasarkan jadwal piket

---

## ✅ CHECKLIST IMPLEMENTASI

### Phase 1: Database Setup (WAJIB)
```bash
# 1. Run migration untuk create jadwal piket tables
php artisan migrate

# 2. Seed sample jadwal piket data
php artisan db:seed --class=JadwalPiketSeeder
```

**Output yang diharapkan:**
```
Migration 2026_01_22_create_jadwal_piket_tables.php
Migration 2026_01_22_update_perawatan_for_jadwal_piket.php
Jadwal Piket seeded successfully!
```

**Tabel yang dibuat:**
- `jadwal_pikets` - Master jadwal piket
- `jadwal_piket_karyawans` - Mapping karyawan ke jadwal piket
- Update columns di `master_perawatan` - Add `jadwal_piket_id`
- Update columns di `perawatan_log` - Add `jam_ceklis`, `nama_karyawan`, `jadwal_piket_id`, etc

---

### Phase 2: Test Jobs (PENTING)
```bash
# 1. Test ClassifyPerawatanBySchedule job
php artisan tinker
>>> \App\Jobs\ClassifyPerawatanBySchedule::dispatch();

# 2. Test ResetPerawatanBySchedule job
>>> \App\Jobs\ResetPerawatanBySchedule::dispatch();

# 3. Verifikasi job berjalan di queue
php artisan queue:work
```

---

### Phase 3: Test API Endpoints (via Postman)

#### 3.1 GET /api/checklist/jadwal-piket
**Tujuan:** Ambil jadwal piket karyawan untuk hari ini
```bash
curl -X GET "http://localhost:8000/api/checklist/jadwal-piket" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

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
      "is_active": true
    }
  ]
}
```

#### 3.2 GET /api/checklist/by-schedule
**Tujuan:** Ambil checklist grouped by jadwal piket dengan validasi
```bash
curl -X GET "http://localhost:8000/api/checklist/by-schedule?date=2026-01-22" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "current_shift": {
    "id": 1,
    "nama": "Pagi",
    "jam_mulai": "08:00",
    "jam_selesai": "20:00",
    "is_active": true,
    "status": "AKTIF",
    "waktu_tersisa_menit": 240,
    "checklists": [
      {
        "id": 1,
        "master_id": 5,
        "nama": "Lap Lantai",
        "status": "pending",
        "is_valid": true,
        "status_validity": "valid"
      }
    ]
  },
  "upcoming_shifts": [],
  "completed_today": []
}
```

#### 3.3 POST /api/checklist/complete
**Tujuan:** Complete checklist dengan validasi jam piket
```bash
curl -X POST "http://localhost:8000/api/checklist/complete" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "checklist_id": 1
  }'
```

**Success Response:**
```json
{
  "success": true,
  "message": "Checklist berhasil diselesaikan",
  "points_earned": 5,
  "completed_at": "2026-01-22T14:35:00.000000Z"
}
```

**Error Response (Luar Jam Piket):**
```json
{
  "success": false,
  "message": "Checklist ini hanya bisa diselesaikan pada jam piket Anda (08:00 - 20:00)"
}
```

#### 3.4 GET /api/checklist/riwayat
**Tujuan:** Ambil riwayat checklist dengan jam dan nama karyawan
```bash
curl -X GET "http://localhost:8000/api/checklist/riwayat?date=2026-01-22&limit=50" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nama_kegiatan": "Lap Lantai",
      "nama_karyawan": "Budi Santoso",
      "nik": "12345678",
      "jadwal_piket": "Pagi",
      "jam_ceklis": "14:35:00",
      "jam_ceklis_formatted": "14:35",
      "tanggal": "2026-01-22",
      "points_earned": 5,
      "completed_at_formatted": "22/01/2026 14:35"
    }
  ],
  "total": 1,
  "tanggal": "2026-01-22"
}
```

---

### Phase 4: Setup Karyawan-Jadwal Piket Mapping

**Via Database (SQL):**
```sql
-- Contoh: Assign karyawan ke jadwal piket pagi
INSERT INTO jadwal_piket_karyawans (nik, jadwal_piket_id, mulai_berlaku, berakhir_berlaku, created_at, updated_at)
VALUES 
  ('12345678', 1, CURDATE(), NULL, NOW(), NOW()),
  ('87654321', 1, CURDATE(), NULL, NOW(), NOW());
```

**Via Tinker:**
```php
php artisan tinker

>>> $karyawan = \App\Models\Karyawan::find('12345678');
>>> $jadwalPiket = \App\Models\JadwalPiket::find(1);
>>> \App\Models\JadwalPiketKaryawan::create([
    'nik' => $karyawan->nik,
    'jadwal_piket_id' => $jadwalPiket->id,
    'mulai_berlaku' => now(),
    'berakhir_berlaku' => null
]);
```

---

### Phase 5: Setup Master Checklist dengan Jadwal Piket

**Via Admin Panel (RECOMMENDED):**
1. Buka Menu: Manajemen Perawatan → Master Checklist
2. Klik Tambah Checklist atau Edit checklist yang ada
3. Pilih kolom "Jadwal Piket" → Select "Pagi", "Siang", atau "Malam"
4. Save

**Via Database:**
```sql
-- Update master perawatan dengan jadwal piket
UPDATE master_perawatan 
SET jadwal_piket_id = 1 
WHERE nama_kegiatan LIKE '%Lap Lantai%';
```

---

## 🧪 TESTING SKENARIO

### Skenario 1: Checklist Valid (Dalam Jam Piket)
```
1. Setup:
   - Jadwal Piket Pagi: 08:00 - 20:00
   - Current time: 14:00
   - Karyawan: Assigned ke Pagi shift
   - Checklist: Assigned ke Pagi jadwal piket

2. Test:
   - GET /api/checklist/by-schedule
   - Expected: is_valid = true, status = "AKTIF"
   - POST /api/checklist/complete
   - Expected: success = true, checklist completed
```

### Skenario 2: Checklist Invalid (Luar Jam Piket)
```
1. Setup:
   - Jadwal Piket Pagi: 08:00 - 20:00
   - Current time: 21:00 (luar jam)
   - Karyawan: Assigned ke Pagi shift
   - Checklist: Assigned ke Pagi jadwal piket

2. Test:
   - GET /api/checklist/by-schedule
   - Expected: is_valid = false, status = "TERTUTUP (SELESAI)"
   - POST /api/checklist/complete
   - Expected: success = false, message about outside shift hours
```

### Skenario 3: Multiple Shifts
```
1. Setup:
   - Karyawan assigned ke 2 shifts: Pagi (08:00-20:00) & Malam (20:00-06:00)
   - Checklist untuk masing-masing shift

2. Test:
   - GET /api/checklist/by-schedule
   - Expected: current_shift populated, upcoming_shifts populated
   - Verify riwayat menampilkan jam ceklis dan jadwal piket
```

---

## 🔧 TROUBLESHOOTING

### Issue 1: Jobs tidak berjalan
```bash
# Check queue worker status
php artisan queue:work

# Check jobs di database
php artisan tinker
>>> \DB::table('jobs')->get();

# Manual trigger job
>>> \App\Jobs\ClassifyPerawatanBySchedule::dispatch();
```

### Issue 2: Checklist tidak muncul
```php
# Debug: Cek apakah jadwal piket assign ke karyawan
>>> \App\Models\JadwalPiketKaryawan::where('nik', '12345678')->get();

# Debug: Cek master perawatan ada jadwal_piket_id
>>> \App\Models\MasterPerawatan::where('jadwal_piket_id', 1)->get();

# Debug: Cek perawatan_log records
>>> \App\Models\PerawatanLog::whereDate('tanggal_eksekusi', today())->get();
```

### Issue 3: Validasi jam piket tidak bekerja
```php
# Check JadwalPiketService
>>> $service = new \App\Services\JadwalPiketService();
>>> $jadwalPiket = \App\Models\JadwalPiket::find(1);
>>> $service->isInSchedule($jadwalPiket, now());
```

---

## 📱 FRONTEND CHECKLIST (Mobile App)

### Components yang perlu diupdate:
1. **Checklist List View** - Group by jadwal piket
   - Display jadwal piket header dengan status (AKTIF/TERTUTUP)
   - Show countdown timer
   - Show checklist items

2. **Checklist Complete Flow**
   - Validasi UI: Disable tombol jika luar jam piket
   - Show error message jika luar jam
   - Save jam_ceklis saat complete

3. **History/Riwayat View**
   - Display nama karyawan
   - Display jam ceklis
   - Display jadwal piket
   - Sort by tanggal descending

### API URLs untuk Frontend:
- `GET /api/checklist/jadwal-piket` - Get jadwal piket list
- `GET /api/checklist/by-schedule` - Get checklist grouped
- `POST /api/checklist/complete` - Complete checklist
- `GET /api/checklist/riwayat` - Get history

---

## 📊 MONITORING

### Log files:
```bash
# Check logs
tail -f storage/logs/laravel.log | grep "JadwalPiket"
tail -f storage/logs/laravel.log | grep "ClassifyPerawatan"
tail -f storage/logs/laravel.log | grep "ResetPerawatan"
```

### Database monitoring:
```sql
-- Check active schedules
SELECT * FROM jadwal_pikets WHERE is_active = true;

-- Check karyawan assignments
SELECT * FROM jadwal_piket_karyawans;

-- Check perawatan logs
SELECT * FROM perawatan_log WHERE tanggal_eksekusi = CURDATE();

-- Check invalid checklists
SELECT * FROM perawatan_log WHERE status_validity != 'valid';
```

---

## 🎯 SUCCESS CRITERIA

✅ Database migrations berjalan lancar
✅ Scheduler jobs terdaftar di Kernel.php
✅ API endpoints berfungsi
✅ Checklist hanya bisa dikerjakan saat jam piket
✅ Riwayat menampilkan jam dan nama karyawan
✅ Multiple jadwal piket support
✅ Overnight shifts support (20:00 - 06:00)
✅ Error handling yang clear

---

## 📞 SUPPORT

Jika ada error, check:
1. Migration status: `php artisan migrate:status`
2. Queue status: `php artisan queue:work`
3. API response: Check Postman atau browser console
4. Logs: `storage/logs/laravel.log`

