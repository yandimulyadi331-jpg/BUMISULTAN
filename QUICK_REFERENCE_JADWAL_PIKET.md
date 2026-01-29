# 🎯 QUICK REFERENCE CARD - CHECKLIST JADWAL PIKET

## ⚡ 5 MENIT QUICK START

### 1. Run Migrations
```bash
php artisan migrate
php artisan db:seed --class=JadwalPiketSeeder
```

### 2. Map Karyawan
```bash
php artisan tinker
>>> \App\Models\JadwalPiketKaryawan::create(['nik' => '12345678', 'jadwal_piket_id' => 1, 'mulai_berlaku' => now()]);
```

### 3. Assign Checklist
```bash
# Via Admin: Manajemen Perawatan → Edit Checklist → Pilih Jadwal Piket → Save
# Atau via SQL:
UPDATE master_perawatan SET jadwal_piket_id = 1 WHERE nama_kegiatan LIKE '%Lap Lantai%';
```

### 4. Start Queue Worker
```bash
php artisan queue:work
```

### 5. Test API
```bash
curl -H "Authorization: Bearer TOKEN" http://localhost:8000/api/checklist/by-schedule
```

---

## 📌 KEY FILES

| File | Type | Purpose |
|------|------|---------|
| JadwalPiket.php | Model | Master jadwal piket |
| JadwalPiketKaryawan.php | Model | Mapping karyawan ↔ jadwal |
| JadwalPiketService.php | Service | Validasi & helper methods |
| ClassifyPerawatanBySchedule.php | Job | Auto-classify checklist |
| ResetPerawatanBySchedule.php | Job | Auto-reset checklist |
| ChecklistController.php | Controller | 4 new API methods |

---

## 🔌 4 API ENDPOINTS

### 1️⃣ GET /api/checklist/jadwal-piket
Ambil jadwal piket karyawan
```bash
curl -H "Auth: Bearer TOKEN" .../jadwal-piket
```

### 2️⃣ GET /api/checklist/by-schedule
Ambil checklist grouped by jadwal + status
```bash
curl -H "Auth: Bearer TOKEN" .../by-schedule?date=2026-01-22
```

### 3️⃣ POST /api/checklist/complete
Complete checklist (VALIDASI: hanya dalam jam piket!)
```bash
curl -X POST -H "Auth: Bearer TOKEN" -d '{"checklist_id":1}' .../complete
```

### 4️⃣ GET /api/checklist/riwayat
Get history dengan nama_karyawan & jam_ceklis
```bash
curl -H "Auth: Bearer TOKEN" .../riwayat?date=2026-01-22
```

---

## 📊 DATABASE SCHEMA

### jadwal_pikets
```sql
id | nama_piket | jam_mulai | jam_selesai | hari | is_active
1  | Pagi       | 08:00     | 20:00       | NULL | 1
2  | Siang      | 06:00     | 18:00       | NULL | 1
3  | Malam      | 20:00     | 06:00       | NULL | 1
```

### jadwal_piket_karyawans
```sql
id | nik      | jadwal_piket_id | mulai_berlaku | berakhir_berlaku
1  | 12345678 | 1               | 2026-01-22    | NULL
```

### master_perawatan (UPDATE)
```sql
... | jadwal_piket_id | ...
... | 1 (Pagi)        | ...
```

### perawatan_log (UPDATE - NEW COLUMNS)
```sql
... | jam_ceklis | nama_karyawan | jadwal_piket_id | status_validity | last_reset_at | ...
... | 14:35:00   | Budi Santoso  | 1               | valid           | NULL          | ...
```

---

## ✨ LOGIKA ALUR

```
ADMIN INPUT CHECKLIST
├─ Pilih Jadwal Piket (Pagi/Siang/Malam)
└─ Save → master_perawatan.jadwal_piket_id = selected

SETIAP 1 MENIT
├─ ClassifyPerawatanBySchedule:
│  └─ For each karyawan + jadwal piket:
│     ├─ Create perawatan_log (jika belum ada)
│     └─ Set status_validity (valid/expired/outside_shift)
└─ ResetPerawatanBySchedule:
   └─ If shift selesai:
      └─ Mark pending → expired

KARYAWAN BUKA APP
├─ GET /api/checklist/by-schedule
└─ Response:
   ├─ current_shift (jadwal sedang berlangsung + items)
   ├─ upcoming_shifts (jadwal yang akan datang + items)
   └─ completed_today (checklist sudah selesai)

KARYAWAN KLIK COMPLETE
├─ POST /api/checklist/complete
├─ Validasi: current_time dalam jam piket?
│  ├─ YES → Update status='completed', jam_ceklis=now()
│  └─ NO → Error "Diluar jam piket"
└─ Return success/error

KARYAWAN LIHAT HISTORY
├─ GET /api/checklist/riwayat
└─ Response dengan:
   ├─ nama_karyawan
   ├─ jam_ceklis
   ├─ jadwal_piket
   └─ points_earned
```

---

## ⚠️ MOST IMPORTANT

### 🔴 VALIDATION RULE
```
POST /api/checklist/complete hanya bekerja jika:
- current_time >= jam_mulai AND current_time < jam_selesai
- Jika tidak → Error 403 "Checklist hanya bisa diselesaikan pada jam piket Anda"
```

### 🟢 QUEUE WORKER WAJIB
```
Queue worker HARUS berjalan agar ClassifyPerawatan & ResetPerawatan berjalan!
php artisan queue:work
```

### 🔵 OVERNIGHT SHIFTS SUPPORTED
```
Jadwal Malam: 20:00 - 06:00 (next day)
System otomatis handle overflow ke hari berikutnya
```

---

## 🧪 TESTING SKENARIO

### Test 1: Valid (Jam Piket 08:00-20:00, Time 14:00)
```
GET /api/checklist/by-schedule
→ is_valid: true, status: "AKTIF"

POST /api/checklist/complete {checklist_id: 1}
→ success: true, jam_ceklis: "14:00:00"
```

### Test 2: Invalid (Jam Piket 08:00-20:00, Time 21:00)
```
GET /api/checklist/by-schedule
→ is_valid: false, status: "TERTUTUP (SELESAI)"

POST /api/checklist/complete {checklist_id: 1}
→ success: false, message: "Diluar jam piket Anda (08:00-20:00)"
```

### Test 3: History dengan Detail
```
GET /api/checklist/riwayat
→ [
    {
      id: 1,
      nama_karyawan: "Budi Santoso",
      jam_ceklis: "14:35",
      jadwal_piket: "Pagi",
      points_earned: 5
    }
  ]
```

---

## 🚨 TROUBLESHOOTING (30 DETIK FIX)

### Jobs tidak berjalan?
```bash
# Check queue
php artisan queue:work

# Or check queue worker
ps aux | grep queue
```

### Checklist tidak muncul?
```sql
-- Check mapping
SELECT * FROM jadwal_piket_karyawans WHERE nik='12345678';

-- Check master checklist
SELECT * FROM master_perawatan WHERE jadwal_piket_id = 1;

-- Check logs
SELECT * FROM perawatan_log WHERE tanggal_eksekusi = CURDATE();
```

### API error?
```php
// Debug service
php artisan tinker
>>> $s = new \App\Services\JadwalPiketService();
>>> $jp = \App\Models\JadwalPiket::find(1);
>>> $s->isInSchedule($jp, now());
```

---

## 📋 FILES CHECKLIST

```
MIGRATIONS:
✅ 2026_01_22_create_jadwal_piket_tables.php
✅ 2026_01_22_update_perawatan_for_jadwal_piket.php

MODELS:
✅ JadwalPiket.php (NEW)
✅ JadwalPiketKaryawan.php (NEW)
✅ MasterPerawatan.php (UPDATED)
✅ PerawatanLog.php (UPDATED)

SERVICES:
✅ JadwalPiketService.php (NEW)

JOBS:
✅ ClassifyPerawatanBySchedule.php (NEW)
✅ ResetPerawatanBySchedule.php (NEW)

CONTROLLERS:
✅ Api/ChecklistController.php (UPDATED)
✅ ManajemenPerawatanController.php (UPDATED)

CONFIG:
✅ routes/api.php (UPDATED)
✅ app/Console/Kernel.php (UPDATED)

SEEDERS:
✅ JadwalPiketSeeder.php (NEW)

DOCS:
✅ 5 documentation files
```

---

## 🎯 ONE-LINER COMMANDS

```bash
# Full setup
php artisan migrate && php artisan db:seed --class=JadwalPiketSeeder && php artisan queue:work

# Test all endpoints
curl -H "Auth: Bearer TOKEN" http://localhost:8000/api/checklist/jadwal-piket && \
curl -H "Auth: Bearer TOKEN" http://localhost:8000/api/checklist/by-schedule && \
curl -H "Auth: Bearer TOKEN" http://localhost:8000/api/checklist/riwayat

# Check logs
tail -f storage/logs/laravel.log | grep -i "perawatan\|jadwal\|piket"

# Check database
mysql -e "SELECT * FROM jadwal_pikets LIMIT 1; SELECT * FROM perawatan_log LIMIT 1;"
```

---

## 📚 DOCUMENTATION FILES

| File | Read Time | Best For |
|------|-----------|----------|
| INDEX_DOKUMENTASI | 5 min | Navigation |
| SUMMARY_IMPLEMENTASI | 10 min | Overview |
| RINGKASAN_IMPLEMENTASI | 10 min | Reference |
| IMPLEMENTASI_JADWAL_PIKET | 15 min | Setup & Testing |
| DOKUMENTASI_FITUR | 20 min | Technical Deep Dive |
| FINAL_CHECKLIST | 5 min | Pre-Launch |

---

## ✅ PRE-LAUNCH QUICK CHECK

```
☐ php artisan migrate ran successfully
☐ Seeder created sample jadwal piket (Pagi, Siang, Malam)
☐ Karyawan mapped to jadwal piket
☐ Master checklist assigned to jadwal piket
☐ php artisan queue:work running
☐ GET /api/checklist/by-schedule returns data
☐ POST /api/checklist/complete validates jam piket
☐ GET /api/checklist/riwayat shows nama_karyawan & jam_ceklis
☐ Error message clear when outside jam piket
☐ Logs show ClassifyPerawatan & ResetPerawatan jobs running
☐ All 4 new API endpoints working
☐ Admin UI allows jadwal piket selection
```

---

## 🎓 LEARNING PATH

1. **5 min:** Read this card
2. **10 min:** Run migrations & seed
3. **5 min:** Setup karyawan mapping
4. **5 min:** Test API with curl
5. **10 min:** Read RINGKASAN_IMPLEMENTASI for details
6. **20 min:** Read DOKUMENTASI_FITUR for deep dive

**Total: ~55 minutes to full understanding**

---

## 💡 KEY CONCEPTS

| Concept | Explanation |
|---------|-------------|
| **Jadwal Piket** | Schedule/shift (Pagi 08:00-20:00, etc) |
| **Validity** | Status of checklist (valid/expired/outside_shift) |
| **Classify** | Auto-identify checklist for active schedule |
| **Reset** | Auto-clear checklist when shift ends |
| **Overtime** | 20:00-06:00 shift spans 2 days |
| **periode_key** | Unique key for schedule period (piket_1_2026-01-22) |

---

## 🎯 SUCCESS CRITERIA

✅ When you see this, you're done:
- Migrations run successfully
- Seeder creates jadwal piket
- Karyawan assigned to jadwal piket
- Checklist assigned to jadwal piket
- Queue worker running
- POST /api/checklist/complete returns 403 when outside jam piket
- GET /api/checklist/riwayat shows nama_karyawan & jam_ceklis
- Admin can select jadwal piket when creating/editing checklist

---

**Generated:** 2026-01-22  
**Status:** ✅ READY  
**Version:** 1.0  

🚀 **YOU'RE ALL SET TO GO LIVE!**

