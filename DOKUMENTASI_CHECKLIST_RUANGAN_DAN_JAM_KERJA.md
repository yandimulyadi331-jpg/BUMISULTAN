# 📋 Sistem Klasifikasi Checklist - Per Ruangan & Per Jam Kerja

## Ringkasan
Fitur checklist perawatan karyawan kini didukung dengan dual classification:
1. **Per Ruangan** (sudah ada sebelumnya)
2. **Per Jam Kerja/Shift** (baru ditambahkan)

Kombinasi keduanya memastikan karyawan hanya melihat checklist yang relevan dengan tugas mereka berdasarkan shift kerja dan lokasi.

---

## 🏗️ Arsitektur Sistem

### Database Changes
```
master_perawatan
├── id (primary)
├── nama_kegiatan
├── kode_jam_kerja ← NEW (CHAR 4, nullable - FK to presensi_jamkerja)
├── ruangan_id ← existing (FK)
├── ... fields lainnya

perawatan_log
├── id (primary)
├── user_id
├── kode_jam_kerja ← NEW (stored when checklist created)
├── ... fields lainnya

jadwal_piket_karyawans
├── id
├── nik (FK to karyawan)
├── kode_jam_kerja (FK to presensi_jamkerja) ← menggunakan jam kerja yang sebenarnya
├── mulai_berlaku
├── berakhir_berlaku
```

### Perubahan Model
```php
// MasterPerawatan.php
public function jamKerja() {
    return $this->belongsTo(Jamkerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
}

// PerawatanLog.php
public function jamKerja() {
    return $this->belongsTo(Jamkerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
}
```

---

## 👨‍💼 Alur Admin Input Checklist

### Step 1: Buka Tambah Master Checklist
```
Path: /perawatan/master/create
```

### Step 2: Isi Form dengan Pilihan Jadwal Piket
```
Nama Kegiatan: [Buang Sampah]
Deskripsi: [...]
Kategori: [Kebersihan]
Tipe Periode: [Harian]

→ NEW FIELD: Jadwal Piket (Jam Kerja)
   ☐ Tanpa Jadwal Piket (Semua Karyawan)     ← default
   ☑ NON SHIFT (08:00 - 17:00)               ← SHIFT 1
   ☐ SHIFT 1 (08:00 - 20:00)
   ☐ SHIFT 2 (20:00 - 08:00)
```

### Step 3: Simpan
Checklist akan tersimpan dengan `kode_jam_kerja` sesuai pilihan shift.

---

## 📱 Alur Karyawan Lihat Checklist

### Skenario 1: Admin Input Checklist TANPA Jadwal Piket
```
Checklist: "Ngaji Subuh"
kode_jam_kerja: NULL

Tampil untuk:
✅ Karyawan Shift 1 (08:00-20:00)
✅ Karyawan Shift 2 (20:00-08:00)
✅ Karyawan NON SHIFT (08:00-17:00)

Status: "Checklist untuk semua"
```

### Skenario 2: Admin Input Checklist untuk SHIFT 2 Saja
```
Checklist: "Kunci Pintu Malam"
kode_jam_kerja: "SFT2" (SHIFT 2: 20:00-08:00)

Tampil untuk:
❌ Karyawan Shift 1 (08:00-20:00)     - TIDAK MUNCUL
✅ Karyawan Shift 2 (20:00-08:00)     - MUNCUL
❌ Karyawan NON SHIFT (08:00-17:00)   - TIDAK MUNCUL

Status: "Checklist khusus Shift 2"
```

### Skenario 3: Mix Ruangan + Jam Kerja
```
Checklist 1: 
  Nama: "Bersihkan Toilet Lantai 2"
  Ruangan: "Toilet Lantai 2"
  Jam Kerja: NULL (semua shift)
  Tampil: Semua shift, hanya untuk task di Lantai 2

Checklist 2:
  Nama: "Monitor Keamanan Malam"
  Ruangan: "Umum"
  Jam Kerja: "SFT2" (Shift 2 saja)
  Tampil: Hanya Shift 2, task umum/keliling
```

---

## 🔌 API Endpoints

### 1. Get Checklist Grouped by Jam Kerja & Ruangan
```
GET /api/checklist/by-jam-kerja
Authorization: Bearer {token}
Query: ?date=2026-01-22

Response:
{
  "success": true,
  "kode_jam_kerja": "SFT2",
  "jam_kerja": {
    "kode": "SFT2",
    "nama": "SHIFT 2",
    "jam_masuk": "20:00:00",
    "jam_pulang": "08:00:00"
  },
  "total_checklist": 15,
  "completed_count": 3,
  "grouped_by_ruangan": {
    "Toilet": [
      {
        "id": 1,
        "nama": "Bersihkan Toilet",
        "status": "pending",
        "jam_kerja_required": null,
        "jam_kerja_required_label": null,
        ...
      }
    ],
    "Umum": [
      {
        "id": 2,
        "nama": "Monitor Keamanan",
        "status": "pending",
        "jam_kerja_required": "SFT2",
        "jam_kerja_required_label": "SHIFT 2",
        ...
      }
    ]
  },
  "grouped_by_jam_kerja": {
    "null": [...],  // untuk semua shift
    "SHIFT 2": [...] // khusus shift 2
  },
  "date": "2026-01-22"
}
```

### 2. Check Status dengan Filter Jam Kerja
```
POST /api/checklist/status
Authorization: Bearer {token}
Body: {"date": "2026-01-22"}

Response:
{
  "hasIncompleteChecklist": true,
  "checklistInfo": {
    "total": 15,        ← hanya checklist untuk shift ini
    "completed": 3,
    "remaining": 12,
    "percentageCompleted": 20,
    "percentageRemaining": 80
  },
  "message": "Masih ada 12 checklist yang belum selesai"
}
```

### 3. Complete Checklist (dengan validasi jam kerja)
```
POST /api/checklist/complete
Authorization: Bearer {token}
Body: {
  "perawatan_log_id": 123,
  "catatan": "Sudah selesai",
  "foto_bukti": "base64_image"
}

Validasi:
- ✅ Checklist ada di jam kerja karyawan saat ini
- ✅ Jam saat ini ada dalam range shift
- ✅ User = karyawan yang ditugaskan
- ❌ Jika jam diluar shift → error "Di luar jam kerja shift Anda"
```

---

## 🛠️ Implementasi Details

### Query Filtering di Controller
```php
// getChecklistByJamKerja() method
$masterChecklists = MasterPerawatan::active()
    ->byTipe('harian')
    ->where(function ($query) use ($kodeJamKerja) {
        $query->whereNull('kode_jam_kerja')  // Untuk semua
            ->orWhere('kode_jam_kerja', $kodeJamKerja);  // Atau sesuai shift
    })
    ->with('ruangan')
    ->get();
```

Logic:
- Jika `kode_jam_kerja` di master_perawatan = NULL → TAMPIL untuk semua shift
- Jika `kode_jam_kerja` di master_perawatan = "SFT2" → hanya TAMPIL untuk karyawan SHIFT 2
- Query otomatis filter berdasarkan `kode_jam_kerja` dari tabel `presensi` hari ini

### Otomatis Create Log
```php
if (!$log) {
    $log = PerawatanLog::create([
        'user_id' => $user->id,
        'master_perawatan_id' => $master->id,
        'kode_jam_kerja' => $kodeJamKerja,  ← store jam kerja saat ini
        'status' => 'pending',
        'status_validity' => 'valid'
    ]);
}
```

---

## 📊 Database Integration Points

### Presensi Table (existing)
```
Column: kode_jam_kerja
- Diisi saat karyawan absen masuk
- Digunakan untuk determine shift karyawan hari itu
```

### Master Perawatan Table (updated)
```
Column: kode_jam_kerja (CHAR 4, nullable)
- NULL = untuk semua shift/karyawan
- "SFT1" = hanya Shift 1
- "SFT2" = hanya Shift 2
- "NONSHIFT" = hanya Non Shift
```

### Jadwal Piket Karyawans Table (new)
```
- Link karyawan (nik) dengan jam kerja (kode_jam_kerja)
- Validity dates: mulai_berlaku, berakhir_berlaku
- Query: find jam kerja karyawan untuk tanggal tertentu
```

---

## 🧪 Test Cases

### Test 1: Checklist untuk Semua Shift
```
Admin input: "Ngaji Subuh" → Jadwal Piket: (kosong)
Presensi Karyawan A: SHIFT 1 → ✅ Muncul
Presensi Karyawan B: SHIFT 2 → ✅ Muncul
Presensi Karyawan C: NON SHIFT → ✅ Muncul
```

### Test 2: Checklist untuk SHIFT 2 Saja
```
Admin input: "Security Check" → Jadwal Piket: "SHIFT 2"
Presensi Karyawan A: SHIFT 1 → ❌ Tidak muncul
Presensi Karyawan B: SHIFT 2 → ✅ Muncul
Presensi Karyawan C: NON SHIFT → ❌ Tidak muncul
```

### Test 3: Completed Count Akurat
```
Total checklist (dengan filter): 15
Checklist completed hari ini: 3
Expected API response: 
- total: 15 (hanya yang sesuai shift)
- completed: 3
- remaining: 12
```

---

## 🔐 Security Notes

1. **Filtering di DB**: Filter `kode_jam_kerja` dilakukan di level database query, bukan di aplikasi
2. **Authorization**: Route `/api/checklist/by-jam-kerja` protected dengan `auth:sanctum`
3. **Data Isolation**: Setiap user hanya bisa lihat checklist mereka sendiri
4. **Audit Trail**: `perawatan_log` mencatat `kode_jam_kerja` saat checklist di-complete

---

## 📝 Summary of Changes

| Component | Sebelum | Sesudah |
|-----------|---------|---------|
| Master Perawatan | No shift filter | Filter by `kode_jam_kerja` |
| Checklist Display | Semua checklist muncul | Hanya sesuai shift karyawan |
| API Response | - | Include shift info + grouped data |
| Database | 2 tables | 3 tables (tambah jadwal_piket_karyawans) |
| Validation | Ruangan saja | Ruangan + Jam Kerja |

✅ Implementasi complete dan siap deploy!
