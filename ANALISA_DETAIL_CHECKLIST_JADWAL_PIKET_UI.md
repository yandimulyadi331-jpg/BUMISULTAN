# 📋 ANALISA KOMPREHENSIF: Sistem Checklist Real-Time Jadwal Piket
## Dengan Focus pada Validasi Jam Kerja & UI/UX Aplikasi Karyawan

**Status:** Analisa Mendalam + Visual Design  
**Tanggal:** 22 Januari 2026  
**Scope:** Time-Window Validation, Auto-Reset/Lock, UI/UX Mobile  

---

## 🎯 RINGKASAN REQUIREMENT LENGKAP

Sistem checklist yang **strictly time-based**, bukan global. Setiap checklist hanya bisa diakses dalam window waktu spesifik sesuai jadwal shift karyawan.

### **4 Pilar Utama:**

#### 1️⃣ **TIME-WINDOW VALIDATION** ⏰
```
Karyawan: Doni
Jadwal: NON SHIFT (08:00 - 17:00)

Skenario 1 - Karyawan ADA di Jam Kerja:
  └─ 10:00 AM: Buka aplikasi checklist
     ✅ "Anda dalam jam kerja (08:00-17:00)"
     ✅ Checklist 08:00, 12:00, 17:00 TAMPIL & BISA DIKERJAKAN

Skenario 2 - Karyawan DILUAR Jam Kerja:
  └─ 18:30 PM: Coba buka checklist
     ❌ "Checklist hanya tersedia 08:00-17:00"
     ❌ Checklist jam 18:00, 21:00 HIDDEN (tidak ditampilkan)
     ❌ Tidak bisa akses/kerjakan apapun

Skenario 3 - SHIFT 2 (20:00-08:00):
  └─ 22:00 PM: Karyawan SHIFT 2 buka aplikasi
     ✅ BISA AKSES (dalam jam kerja SHIFT 2)
     ✅ Checklist 20:00, 23:00, 02:00 TAMPIL
     ✅ Checklist 08:00-20:00 HIDDEN

Skenario 4 - SHIFT 2 (Cross-Midnight):
  └─ 06:00 AM (besok pagi): Karyawan SHIFT 2 masih bisa
     ✅ BISA AKSES (masih dalam shift 20:00-08:00)
     ✅ Checklist sebelum 08:00 BISA DIKERJAKAN
     └─ 08:00 AM: Shift SHIFT 2 berakhir
        ❌ Auto-lock - tidak bisa edit lagi
```

---

#### 2️⃣ **AUTO-RESET PER SHIFT** 🔄
```
TIMELINE SATU HARI:

08:00 - Shift NON SHIFT Dimulai
  └─ TRIGGER: Auto-reset
     ├─ Close checklist NON SHIFT hari sebelumnya (jika ada)
     ├─ Generate periode_key baru: "harian_2026-01-22_NONS"
     ├─ Load checklist baru untuk hari ini
     └─ Status: "ACTIVE - Periode Baru"

12:00 - Jam Tengah Hari
  ├─ Karyawan masih dalam shift
  └─ Checklist tetap ACCESSIBLE

17:00 - Shift NON SHIFT Berakhir
  ├─ TRIGGER: Auto-lock
  │  ├─ Lock periode_key: "harian_2026-01-22_NONS"
  │  ├─ Set periode status: "CLOSED"
  │  ├─ Prevent any further edits
  │  └─ Calculate KPI points (hanya untuk checklist on-time)
  │
  └─ Karyawan coba akses checklist jam 17:30
     ❌ "Jam kerja Anda telah berakhir"
     ❌ "Checklist tidak dapat diakses"

20:00 - Shift SHIFT 2 Dimulai
  └─ TRIGGER: Auto-reset (untuk SHIFT 2)
     ├─ Generate periode_key baru: "harian_2026-01-22_SFT2"
     ├─ Load checklist SHIFT 2
     └─ Status: "ACTIVE - Periode Baru"
```

---

#### 3️⃣ **VALIDATION SAAT BUKA CHECKLIST** 🔐
```
User Action: Karyawan klik "Buka Checklist"

Flow Validation:
  ↓ Step 1: Check user karyawan?
    ├─ Jika tidak → Reject "Bukan karyawan"
    └─ Jika ya → Continue
  ↓ Step 2: Get today's presensi
    ├─ Jika tidak ada presensi → Reject "Tidak ada presensi hari ini"
    └─ Jika ada → Continue
  ↓ Step 3: Get jam kerja dari presensi
    └─ kode_jam_kerja = "NONS" (dari presensi)
  ↓ Step 4: Check waktu SEKARANG dalam window jam kerja?
    ├─ NOW = 10:00 (dalam 08:00-17:00)
    ├─ ✅ PASS → Continue
    └─ Jika diluar jam → Reject "Diluar jam kerja"
  ↓ Step 5: Get master checklist
    ├─ Filter: tipe='harian' AND (kode_jam_kerja=NULL OR kode_jam_kerja='NONS')
    └─ Load semua checklist untuk shift ini
  ↓ Step 6: Check periode status
    ├─ periode_key = "harian_2026-01-22_NONS"
    ├─ Jika status='CLOSED' → Reject "Periode sudah berakhir"
    └─ Jika status='ACTIVE' → Allow

Result: ✅ Checklist berhasil ditampilkan
```

---

#### 4️⃣ **SMART FORCE PULANG** 🚗
```
User Action: Karyawan klik "Absen Pulang"

Case A - Semua Checklist Selesai:
  Jam 15:00 → Semua 8 checklist done
  ├─ Modal: ✅ "Semua checklist selesai!"
  ├─ Info:
  │  ├─ Total: 8/8 ✓
  │  ├─ Jam kerja: 08:00-17:00
  │  ├─ Pulang lebih awal: 15:00 (2 jam lebih cepat)
  │  └─ Status: Pulang Lebih Awal - Valid ✓
  └─ Tombol: "Absen Pulang" (Hijau) → Proceed checkout

Case B - Belum Semua Selesai + DALAM Jam Kerja:
  Jam 16:00 → Baru 5/8 selesai
  ├─ Modal: ⚠️ "Ada 3 checklist belum selesai"
  ├─ Detail:
  │  ├─ Completed: 5/8 (62.5%)
  │  ├─ Remaining: 3
  │  │  ├─ 16:00 - Cek Keamanan
  │  │  ├─ 16:30 - Bersihkan Lantai
  │  │  └─ 17:00 - Absen Pulang Verifikasi
  │  └─ Sisa waktu: 1 jam
  │
  ├─ 2 Pilihan:
  │  ├─ [1] "Selesaikan Dulu" (Putih) → Redirect ke checklist page
  │  └─ [2] "Pulang Dengan Alasan" (Oranye) → Show textarea untuk catatan
  │
  └─ Jika pilih "Pulang Dengan Alasan":
     ├─ User input: "Tidak selesai karena..." (max 255 char)
     ├─ Confirmation: "Yakin pulang? Sisa 3 checklist akan tercatat INCOMPLETE"
     └─ Result: Checklist ditandai "ABANDONED BY USER" + KPI penalty

Case C - DILUAR Jam Kerja + Coba Akses:
  Jam 18:00 → Coba buka aplikasi
  ├─ Modal: ❌ "Jam kerja Anda telah berakhir"
  ├─ Info:
  │  ├─ Jadwal kerja: 08:00-17:00
  │  ├─ Waktu sekarang: 18:00
  │  ├─ Status: DILUAR JAM KERJA
  │  ├─ Checklist: TIDAK DAPAT DIAKSES
  │  └─ Periode ditutup pada: 17:00
  └─ Tombol: "Kembali ke Dashboard" → Dismiss modal
```

---

## 📱 MOCKUP UI/UX APLIKASI KARYAWAN

### **SCREEN 1: Dashboard Checklist (Jam Kerja Aktif)**

```
┌─────────────────────────────────────────────────┐
│ PERAWATAN                                    ⚙  │ ← Jam 10:30 (Dalam jam kerja)
├─────────────────────────────────────────────────┤
│                                                 │
│ ✅ NON SHIFT AKTIF                             │
│ └─ 08:00 - 17:00 (Waktu: 10:30)               │
│    Sisa waktu: 6 jam 30 menit ⏱               │
│                                                 │
├─────────────────────────────────────────────────┤
│ PROGRESS CHECKLIST HARI INI                     │
│ [████████░░░░░░░░░░░░] 5/10 (50%)              │
│                                                 │
├─────────────────────────────────────────────────┤
│ DAFTAR CHECKLIST                                │
│                                                 │
│ ☑ 08:00 - Bersihkan Area Kerja                 │
│    Selesai oleh: Doni (08:15)                 │
│    Status: ✅ ON-TIME                          │
│    Points: +10                                 │
│                                                 │
│ ☑ 09:00 - Cek Barang di Gudang                │
│    Selesai oleh: Doni (09:20)                 │
│    Status: ✅ ON-TIME                          │
│    Points: +10                                 │
│                                                 │
│ ☐ 12:00 - Buang Sampah & Restock              │
│    Status: BELUM DIKERJAKAN                     │
│    └─ Siap dikerjakan: JAM 12:00               │
│    └─ Buka checklist     ➜                      │
│                                                 │
│ ☐ 14:00 - Bersihkan Ruang Rapat               │
│    Status: BELUM DIKERJAKAN                     │
│    └─ Siap dikerjakan: JAM 14:00               │
│    └─ Buka checklist     ➜                      │
│                                                 │
│ ☐ 17:00 - Absen Pulang Verifikasi             │
│    Status: BELUM DIKERJAKAN                     │
│    └─ Siap dikerjakan: JAM 17:00               │
│    └─ Buka checklist     ➜                      │
│                                                 │
│                                                 │
│ [ABSEN PULANG]              [LIHAT DETAIL]    │
│                                                 │
└─────────────────────────────────────────────────┘
```

### **SCREEN 2: Saat Membuka Checklist yang SIAP**

```
┌─────────────────────────────────────────────────┐
│ CHECKLIST DETAIL                          ⟲   │
├─────────────────────────────────────────────────┤
│                                                 │
│ ✅ JAM KERJA VALID                             │
│ Anda dalam jam kerja: 08:00-17:00 (10:30)    │
│ Checklist ini siap pada: 12:00 ✓              │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ 📋 BUANG SAMPAH & RESTOCK                      │
│                                                 │
│ Kategori: Kebersihan                           │
│ Jadwal Shift: NON SHIFT (08:00-17:00)          │
│ Jam Checklist: 12:00                           │
│                                                 │
│ Deskripsi:                                     │
│ Buang sampah dari area kerja, restok barang   │
│ di gudang, bersihkan tempat sampah sementara. │
│                                                 │
│ ─────────────────────────────────────────── │
│                                                 │
│ Foto Bukti:  [📷 Upload]                      │
│                                                 │
│ Catatan:     [Ketik di sini...]               │
│                                                 │
│ ─────────────────────────────────────────── │
│                                                 │
│ [BATAL]                    [SELESAIKAN] ✓    │
│                                                 │
└─────────────────────────────────────────────────┘
```

### **SCREEN 3: Mencoba Akses Checklist di LUAR Jam Kerja**

```
┌─────────────────────────────────────────────────┐
│ PERAWATAN                                       │
├─────────────────────────────────────────────────┤
│                                                 │
│ ⚠️ DILUAR JAM KERJA                            │
│                                                 │
│ Waktu sekarang: 18:30                          │
│ Jadwal kerja Anda: 08:00 - 17:00               │
│ Status: 🔒 PERIODE TERTUTUP                     │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ ❌ CHECKLIST TIDAK DAPAT DIAKSES               │
│                                                 │
│ Alasan:                                         │
│ • Jam kerja Anda telah berakhir pada 17:00    │
│ • Periode checklist ditutup otomatis           │
│ • Checklist hanya dapat diakses dalam jam    │
│   kerja yang berlaku                           │
│                                                 │
│ Checklist lain yang tidak dikerjakan:          │
│ • 14:00 - Bersihkan Ruang Rapat               │
│ • 17:00 - Absen Pulang Verifikasi             │
│                                                 │
│ Status:                                         │
│ ├─ 5/10 selesai                                │
│ ├─ 5 tidak selesai (ditandai ABANDONED)       │
│ └─ KPI: -10 points (penalty)                  │
│                                                 │
│ Hubungi admin jika ada keberatan.             │
│                                                 │
│ [KEMBALI KE DASHBOARD]                        │
│                                                 │
└─────────────────────────────────────────────────┘
```

### **SCREEN 4: Absen Pulang - Semua Checklist Selesai**

```
┌─────────────────────────────────────────────────┐
│ PULANG LEBIH AWAL                           ✓  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ✅ SEMUA CHECKLIST SELESAI!                    │
│                                                 │
│ Progress: [██████████████████] 10/10 (100%)   │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ 📊 SUMMARY:                                    │
│ ├─ Jam Kerja: 08:00 - 17:00                   │
│ ├─ Waktu Sekarang: 15:00                      │
│ ├─ Pulang Lebih Awal: 2 jam                   │
│ ├─ Total Checklist: 10/10 ✓                   │
│ ├─ Status: ✅ PULANG LEBIH AWAL - VALID       │
│ └─ KPI Points: +100 (Bonus Early Leave)       │
│                                                 │
│ ─────────────────────────────────────────── │
│                                                 │
│ Anda diizinkan pulang lebih awal karena       │
│ semua checklist telah selesai tepat waktu.    │
│                                                 │
│ Keputusan ini tercatat dalam sistem.          │
│                                                 │
│ [BATAL]                [PULANG SEKARANG] ✓   │
│                                                 │
└─────────────────────────────────────────────────┘
```

### **SCREEN 5: Absen Pulang - Ada Checklist Belum Selesai**

```
┌─────────────────────────────────────────────────┐
│ BELUM BISA PULANG                           ⚠  │
├─────────────────────────────────────────────────┤
│                                                 │
│ ⚠️ ADA CHECKLIST YANG BELUM SELESAI            │
│                                                 │
│ Progress: [████████░░░░░░░░░░░░] 6/10 (60%)   │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ 📊 SUMMARY:                                    │
│ ├─ Waktu Sekarang: 16:00                      │
│ ├─ Jam Kerja Berakhir: 17:00 (1 jam lagi)     │
│ ├─ Selesai: 6/10 ✓                            │
│ ├─ Belum: 4/10 ✗                              │
│ └─ Status: ⚠️ INCOMPLETE                       │
│                                                 │
│ CHECKLIST YANG BELUM SELESAI:                 │
│ ❌ 14:00 - Bersihkan Ruang Rapat              │
│ ❌ 15:00 - Cek Inventaris                     │
│ ❌ 16:00 - Restock Perlengkapan                │
│ ❌ 17:00 - Absen Pulang Verifikasi            │
│                                                 │
│ ─────────────────────────────────────────── │
│                                                 │
│ PILIH SALAH SATU:                              │
│                                                 │
│ ┌─────────────────────────────────────────┐   │
│ │ [1] SELESAIKAN CHECKLIST DULU           │   │
│ │                                          │   │
│ │ Lanjutkan mengerjakan 4 checklist yang  │   │
│ │ tersisa. Anda masih punya 1 jam.       │   │
│ └─────────────────────────────────────────┘   │
│ [SELESAIKAN]                                  │
│                                                 │
│ ┌─────────────────────────────────────────┐   │
│ │ [2] PULANG DENGAN CATATAN               │   │
│ │                                          │   │
│ │ Pulang sekarang, tapi 4 checklist akan  │   │
│ │ tercatat INCOMPLETE. KPI akan dikurangi.│   │
│ │                                          │   │
│ │ Alasan Pulang:                           │   │
│ │ [Ketik di sini... max 255 kar]           │   │
│ │                                          │   │
│ │ ☑ Saya memahami konsekuensinya          │   │
│ └─────────────────────────────────────────┘   │
│ [PULANG]                                      │
│                                                 │
│ [BATAL]                                        │
│                                                 │
└─────────────────────────────────────────────────┘
```

### **SCREEN 6: Setiap Item Checklist - Tampil Status Siap**

```
┌─────────────────────────────────────────────────┐
│ DAFTAR CHECKLIST HARIAN                        │
├─────────────────────────────────────────────────┤
│                                                 │
│ STATUS SHIFT: ✅ NON SHIFT (08:00-17:00)       │
│ WAKTU SEKARANG: 10:30                          │
│ SISA WAKTU: 6 jam 30 menit ⏳                  │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ ☑ SUDAH DIKERJAKAN                             │
│                                                 │
│ ├─ 08:00 - Bersihkan Area Kerja                │
│ │  ├─ Siap pada: 08:00 ✓                      │
│ │  ├─ Dikerjakan oleh: Doni                    │
│ │  ├─ Selesai: 08:15 (+15 min)                │
│ │  ├─ Status: ✅ SELESAI ON-TIME              │
│ │  └─ Points: +10                              │
│ │                                              │
│ ├─ 09:00 - Cek Barang Gudang                   │
│ │  ├─ Siap pada: 09:00 ✓                      │
│ │  ├─ Dikerjakan oleh: Doni                    │
│ │  ├─ Selesai: 09:30 (+30 min)                │
│ │  ├─ Status: ✅ SELESAI ON-TIME              │
│ │  └─ Points: +10                              │
│ │                                              │
│ ├─ 11:00 - Monitor Keamanan                    │
│ │  ├─ Siap pada: 11:00 ✓                      │
│ │  ├─ Dikerjakan oleh: Doni                    │
│ │  ├─ Selesai: 11:05 (+5 min)                 │
│ │  ├─ Status: ✅ SELESAI ON-TIME              │
│ │  └─ Points: +10                              │
│ │                                              │
│ ├─ 12:00 - Buang Sampah                        │
│ │  ├─ Siap pada: 12:00 ✓                      │
│ │  ├─ Dikerjakan oleh: Doni                    │
│ │  ├─ Selesai: 12:20 (+20 min)                │
│ │  ├─ Status: ✅ SELESAI ON-TIME              │
│ │  └─ Points: +10                              │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ ☐ BELUM DIKERJAKAN                             │
│                                                 │
│ ├─ 14:00 - Bersihkan Ruang Rapat               │
│ │  ├─ Siap pada: 14:00                        │
│ │  ├─ Status: ⏳ MENUNGGU (5 jam 30 min)      │
│ │  ├─ Dapat dikerjakan dari: 14:00             │
│ │  └─ [Buka Checklist]                        │
│ │                                              │
│ ├─ 15:00 - Cek Inventaris                      │
│ │  ├─ Siap pada: 15:00                        │
│ │  ├─ Status: ⏳ MENUNGGU (4 jam 30 min)      │
│ │  ├─ Dapat dikerjakan dari: 15:00             │
│ │  └─ [Buka Checklist]                        │
│ │                                              │
│ ├─ 17:00 - Absen Pulang Verifikasi            │
│ │  ├─ Siap pada: 17:00                        │
│ │  ├─ Status: ⏳ MENUNGGU (6 jam 30 min)      │
│ │  ├─ Dapat dikerjakan dari: 17:00             │
│ │  ├─ Catatan: WAJIB diisi sebelum jam 17:00 │
│ │  └─ [Buka Checklist]                        │
│                                                 │
├─────────────────────────────────────────────────┤
│                                                 │
│ 🔒 TERSEMBUNYI (DILUAR JAM KERJA)              │
│                                                 │
│ ├─ 18:00 - Monitor Malam                       │
│ │  ├─ Jadwal: 18:00 - 21:00                    │
│ │  ├─ Shift: SHIFT 2 (BUKAN UNTUK ANDA)       │
│ │  └─ Status: 🔒 HIDDEN - Tidak ditampilkan   │
│ │                                              │
│ ├─ 21:00 - Kunci Gudang Malam                  │
│ │  ├─ Jadwal: 21:00 - 22:00                    │
│ │  ├─ Shift: SHIFT 2 (BUKAN UNTUK ANDA)       │
│ │  └─ Status: 🔒 HIDDEN - Tidak ditampilkan   │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## 🔌 DATABASE SCHEMA & DATA FLOW

### **Master Perawatan - Contoh Data**

```sql
INSERT INTO master_perawatan VALUES:

┌─────────────────────────────────────────────────────────────┐
│ ID │ NAMA_KEGIATAN         │ TIPE_PERIODE │ KODE_JAM_KERJA  │
├─────┼──────────────────────┼──────────────┼─────────────────┤
│ 1  │ Bersihkan Area Kerja │ Harian       │ NULL (Semua)    │
│ 2  │ Cek Barang Gudang    │ Harian       │ NULL (Semua)    │
│ 3  │ Monitor Keamanan     │ Harian       │ NULL (Semua)    │
│ 4  │ Buang Sampah         │ Harian       │ NONS (08-17)    │
│ 5  │ Bersihkan Ruang Rapat│ Harian       │ NONS (08-17)    │
│ 6  │ Monitor Malam        │ Harian       │ SFT2 (20-08)    │
│ 7  │ Kunci Gudang Malam   │ Harian       │ SFT2 (20-08)    │
│ 8  │ Absen Pulang Verif   │ Harian       │ NULL (Semua)    │
└─────┴──────────────────────┴──────────────┴─────────────────┘

JAM KERJA:
- NONS = NON SHIFT (08:00 - 17:00)
- SFT1 = SHIFT 1 (08:00 - 20:00)
- SFT2 = SHIFT 2 (20:00 - 08:00)

KARYAWAN HARI INI:
- Doni: Shift NONS (dapat lihat: ID 1,2,3,4,5,8 | HIDDEN: ID 6,7)
- Rina: Shift SFT2 (dapat lihat: ID 1,2,3,6,7,8 | HIDDEN: ID 4,5)
```

### **Perawatan Log - Audit Trail**

```sql
INSERT INTO perawatan_log VALUES:

SCENARIO 1 - ON-TIME COMPLETION:
│ ID │ USER_ID │ MASTER_ID │ PERIODE_KEY              │ STATUS    │ CREATED_AT      │ KODE_JAM_KERJA │
├─────┼─────────┼───────────┼──────────────────────────┼───────────┼─────────────────┼────────────────┤
│ 10 │ 5 (Doni)│ 1         │ harian_2026-01-22_NONS  │ completed │ 2026-01-22 08:15│ NONS           │
│ 11 │ 5       │ 2         │ harian_2026-01-22_NONS  │ completed │ 2026-01-22 09:30│ NONS           │
└─────┴─────────┴───────────┴──────────────────────────┴───────────┴─────────────────┴────────────────┘

SCENARIO 2 - OUT-OF-TIME (REJECTED):
│ ID │ USER_ID │ MASTER_ID │ PERIODE_KEY              │ STATUS           │ CREATED_AT      │ ERROR_MESSAGE                    │
├─────┼─────────┼───────────┼──────────────────────────┼──────────────────┼─────────────────┼──────────────────────────────────┤
│ 12 │ 5       │ 6         │ harian_2026-01-22_NONS  │ rejected_invalid  │ 2026-01-22 18:30│ Outside working hours (NONS: 08-17)
└─────┴─────────┴───────────┴──────────────────────────┴──────────────────┴─────────────────┴──────────────────────────────────┘

SCENARIO 3 - ABANDONED BY USER:
│ ID │ USER_ID │ MASTER_ID │ PERIODE_KEY              │ STATUS        │ NOTES                    │ CREATED_AT      │
├─────┼─────────┼───────────┼──────────────────────────┼───────────────┼──────────────────────────┼─────────────────┤
│ 13 │ 5       │ 5         │ harian_2026-01-22_NONS  │ abandoned     │ Pulang jam 16:00 sblm done│ 2026-01-22 16:00│
└─────┴─────────┴───────────┴──────────────────────────┴───────────────┴──────────────────────────┴─────────────────┘
```

### **Checklist Periode Config - Status Tracking**

```sql
INSERT INTO checklist_periode_config VALUES:

│ ID │ PERIODE_KEY              │ STATUS │ KODE_JAM_KERJA │ TANGGAL      │ JAM_MULAI │ JAM_SELESAI │ CREATED_AT │ CLOSED_AT │
├─────┼──────────────────────────┼────────┼────────────────┼──────────────┼───────────┼─────────────┼────────────┼───────────┤
│ 1  │ harian_2026-01-22_NONS   │ active │ NONS           │ 2026-01-22  │ 08:00    │ 17:00      │ 08:00 tgl22│ NULL      │
│ 2  │ harian_2026-01-22_SFT2   │ active │ SFT2           │ 2026-01-22  │ 20:00    │ 08:00      │ 20:00 tgl22│ NULL      │
│ 3  │ harian_2026-01-21_NONS   │ closed │ NONS           │ 2026-01-21  │ 08:00    │ 17:00      │ 08:00 tgl21│ 17:00 tgl21│
│ 4  │ harian_2026-01-21_SFT2   │ closed │ SFT2           │ 2026-01-21  │ 20:00    │ 08:00      │ 20:00 tgl21│ 08:00 tgl22│
└─────┴──────────────────────────┴────────┴────────────────┴──────────────┴───────────┴─────────────┴────────────┴───────────┘
```

---

## 🔐 VALIDATION LOGIC & API SPECS

### **API 1: GET /api/checklist/status**

**Tujuan:** Check apakah user bisa akses checklist, berapa yang selesai, dll

**Request:**
```json
{
  "date": "2026-01-22"  // optional, default = today
}
```

**Response - SUCCESS (User dalam jam kerja):**
```json
{
  "success": true,
  "isInWorkHours": true,
  "shiftInfo": {
    "kode": "NONS",
    "nama": "NON SHIFT",
    "jam_masuk": "08:00:00",
    "jam_pulang": "17:00:00",
    "waktu_sekarang": "10:30:00"
  },
  "checklistInfo": {
    "total": 8,
    "completed": 4,
    "incomplete": 4,
    "percentageComplete": 50
  },
  "hasIncompleteChecklist": true,
  "shouldShowModal": true,
  "message": "Masih ada 4 checklist yang belum selesai"
}
```

**Response - FAIL (User diluar jam kerja):**
```json
{
  "success": false,
  "isInWorkHours": false,
  "reason": "OUTSIDE_WORK_HOURS",
  "shiftInfo": {
    "kode": "NONS",
    "nama": "NON SHIFT",
    "jam_masuk": "08:00:00",
    "jam_pulang": "17:00:00",
    "waktu_sekarang": "18:30:00"
  },
  "message": "Jam kerja Anda telah berakhir. Checklist tidak dapat diakses.",
  "shouldShowModal": true,
  "modalType": "OUTSIDE_WORK_HOURS"
}
```

---

### **API 2: GET /api/checklist/list**

**Tujuan:** Get daftar checklist yang bisa diakses user hari ini

**Request:**
```json
{
  "date": "2026-01-22",
  "include_hidden": false  // Jangan tampilkan checklist dari shift lain
}
```

**Response - SUCCESS:**
```json
{
  "success": true,
  "isInWorkHours": true,
  "kodeJamKerja": "NONS",
  "periodeKey": "harian_2026-01-22_NONS",
  "waktuSekarang": "10:30:00",
  "checklists": [
    {
      "id": 1,
      "nama": "Bersihkan Area Kerja",
      "jamMulai": "08:00",
      "jamSelesai": "09:00",
      "siapDari": "08:00",
      "status": "completed",
      "completedAt": "2026-01-22 08:15:00",
      "kodeJamKerjaRequired": null,
      "isAccessible": true,
      "reason": null
    },
    {
      "id": 4,
      "nama": "Buang Sampah & Restock",
      "jamMulai": "12:00",
      "jamSelesai": "13:00",
      "siapDari": "12:00",
      "status": "pending",
      "completedAt": null,
      "kodeJamKerjaRequired": "NONS",
      "isAccessible": true,
      "reason": "READY_TO_WORK"
    },
    {
      "id": 6,
      "nama": "Monitor Malam",
      "jamMulai": "18:00",
      "jamSelesai": "21:00",
      "siapDari": "18:00",
      "status": "pending",
      "kodeJamKerjaRequired": "SFT2",
      "isAccessible": false,
      "reason": "HIDDEN_WRONG_SHIFT",
      "message": "Checklist ini hanya untuk Shift 2. Anda sedang NON SHIFT."
    }
  ],
  "summary": {
    "totalVisible": 7,
    "totalHidden": 1,
    "completed": 4,
    "pendingAccessible": 3,
    "percentComplete": 57
  }
}
```

**Response - FAIL:**
```json
{
  "success": false,
  "isInWorkHours": false,
  "periodeStatus": "CLOSED",
  "message": "Periode checklist telah ditutup",
  "checklists": [],
  "shouldShowErrorMessage": true,
  "errorType": "OUTSIDE_WORK_HOURS"
}
```

---

### **API 3: POST /api/checklist/start/{id}**

**Tujuan:** Start/Buka checklist (dengan validation)

**Request:**
```json
{
  "master_id": 1,
  "date": "2026-01-22"
}
```

**Response - SUCCESS:**
```json
{
  "success": true,
  "isInWorkHours": true,
  "isAccessible": true,
  "accessReason": "IN_TIME_WINDOW",
  "checklist": {
    "id": 1,
    "nama": "Bersihkan Area Kerja",
    "description": "...",
    "jamMulai": "08:00",
    "jamSelesai": "09:00",
    "waktuSekarang": "08:30",
    "siapDari": "08:00",
    "isLateByMinutes": 0,
    "kodeJamKerja": "NONS"
  }
}
```

**Response - FAIL (Checklist belum siap):**
```json
{
  "success": false,
  "isAccessible": false,
  "accessReason": "NOT_YET_READY",
  "checklist": {
    "id": 4,
    "nama": "Buang Sampah",
    "siapDari": "12:00",
    "waktuSekarang": "10:30",
    "minutesUntilReady": 90,
    "message": "Checklist ini akan siap pada 12:00 (90 menit lagi)"
  }
}
```

**Response - FAIL (Diluar jam kerja):**
```json
{
  "success": false,
  "isAccessible": false,
  "accessReason": "OUTSIDE_WORK_HOURS",
  "checklist": {
    "id": 6,
    "nama": "Monitor Malam",
    "kodeJamKerjaRequired": "SFT2",
    "kodeJamKerjaUser": "NONS",
    "message": "Checklist ini hanya dapat diakses oleh Shift 2 (20:00-08:00)"
  }
}
```

---

### **API 4: POST /api/checklist/complete/{id}**

**Tujuan:** Selesaikan/Submit checklist (dengan audit trail)

**Request:**
```json
{
  "checklist_id": 1,
  "catatan": "Sudah dikerjakan dengan baik",
  "foto_bukti": "base64_image_data",
  "date": "2026-01-22"
}
```

**Response - SUCCESS:**
```json
{
  "success": true,
  "completed": true,
  "checklist": {
    "id": 1,
    "nama": "Bersihkan Area Kerja",
    "status": "completed",
    "completedAt": "2026-01-22 08:45:00",
    "points": 10,
    "isOnTime": true,
    "pointsDescription": "+10 points (ON-TIME)"
  },
  "message": "Checklist berhasil diselesaikan!"
}
```

**Response - FAIL (Diluar jam kerja):**
```json
{
  "success": false,
  "error": "VALIDATION_FAILED",
  "reason": "OUTSIDE_WORK_HOURS",
  "validation": {
    "isInWorkHours": false,
    "kodeJamKerjaRequired": "NONS",
    "jamMulai": "08:00",
    "jamSelesai": "17:00",
    "waktuSekarang": "18:30",
    "periodeStatus": "CLOSED"
  },
  "message": "Tidak dapat submit checklist diluar jam kerja",
  "auditLog": {
    "attemptTime": "2026-01-22 18:30:00",
    "userKodeJamKerja": "NONS",
    "masterKodeJamKerja": "NONS",
    "status": "REJECTED_OUTSIDE_HOURS",
    "logged": true
  }
}
```

---

## 📊 FLOW DIAGRAM - VALIDATION CHAIN

```
┌─────────────────────────────────────────────────────────────┐
│                    USER OPEN CHECKLIST                      │
└────────────────────────┬────────────────────────────────────┘
                         │
                    ┌────▼─────┐
                    │ VALIDATE  │
                    └────┬──────┘
                         │
    ┌────────────────────┼────────────────────┐
    │                    │                    │
    ▼                    ▼                    ▼
┌────────────┐    ┌──────────────┐    ┌──────────────┐
│Check User  │    │Check TODAY's │    │Get Shift from│
│is Employee?│    │Presensi      │    │Presensi      │
└─────┬──────┘    └──────┬───────┘    └──────┬───────┘
      │ NO               │ NO                │ FAIL
      └────▼─────────────┴───────────────────┘
           │
        ❌ REJECT
           │
      "Not Employee" / 
      "No Presensi"
           
      NO → User bukan karyawan / tidak ada presensi

─────────────────────────────────────────────────

    ┌────────────────────┬────────────────────┐
    │ YES - Continue     │                    │
    ▼                    │                    │
┌────────────────────┐   │                    │
│Get Current Time    │   │                    │
│(NOW)               │   │                    │
└────────┬───────────┘   │                    │
         │               │                    │
         ▼               ▼                    ▼
    ┌─────────────────────────────────┐
    │Check if NOW in Shift Window?    │
    │ Jam Masuk ≤ NOW ≤ Jam Pulang?   │
    └─────┬───────────────────────────┘
          │
     YES  │  NO
     ✅   │  ❌
          │  
          ▼
    ┌───────────────┐
    │Get Periode Key│
    │harian_{date}_ │
    │{kodeJamKerja} │
    └────┬──────────┘
         │
         ▼
    ┌──────────────────┐
    │Check Periode     │
    │Status = ACTIVE?  │
    └────┬─────────────┘
         │
    YES  │  NO
    ✅   │  ❌
         │
         ▼
    ┌──────────────────┐
    │Load Master       │
    │Checklist         │
    │Filter:           │
    │(kode_jam_kerja   │
    │= NULL OR         │
    │= user's shift)   │
    └────┬─────────────┘
         │
         ▼
    ┌──────────────────┐
    │Check Checklist   │
    │Time Window Ready?│
    │NOW ≥ Jam Mulai? │
    └────┬─────────────┘
         │
    YES  │  NO
    ✅   │  ⏳
         │
         ▼
    ✅ ALLOW OPEN
       │
       ├─ Show Checklist Form
       ├─ Enable Submit Button
       └─ Show "Siap dari: XX:XX"
```

---

## 🚨 REJECTION MESSAGES (Untuk Display)

### **Tipe 1: Bukan Jam Kerja**
```
❌ DILUAR JAM KERJA

Waktu sekarang: 18:30
Jadwal kerja Anda: 08:00 - 17:00
Status: Periode checklist telah ditutup

Checklist hanya dapat diakses selama jam kerja Anda berlangsung.

[KEMBALI KE DASHBOARD]
```

### **Tipe 2: Checklist Belum Siap**
```
⏳ CHECKLIST BELUM SIAP

Nama Checklist: Buang Sampah & Restock
Siap dikerjakan: 12:00
Waktu sekarang: 10:30
Waktu tunggu: 1 jam 30 menit

Checklist ini akan siap pada 12:00. Silakan kembali pada waktu yang tepat.

[KEMBALI KE DAFTAR CHECKLIST]
```

### **Tipe 3: Checklist untuk Shift Berbeda**
```
🔒 CHECKLIST HIDDEN

Nama Checklist: Monitor Malam
Jadwal: SHIFT 2 (20:00 - 08:00)
Status: TIDAK UNTUK SHIFT ANDA

Anda sedang bekerja pada jadwal:
✅ NON SHIFT (08:00 - 17:00)

Checklist ini tersembunyi dan tidak dapat diakses. 
Checklist ini hanya ditampilkan untuk karyawan SHIFT 2.

[KEMBALI KE DAFTAR CHECKLIST]
```

### **Tipe 4: Periode Sudah Berakhir**
```
🔒 PERIODE BERAKHIR

Jadwal Kerja: NON SHIFT (08:00 - 17:00)
Periode Berakhir: 17:00
Waktu sekarang: 18:30

Periode checklist telah ditutup otomatis pada 17:00.
Anda tidak dapat lagi menambah atau mengedit checklist hari ini.

Jika ada keberatan, hubungi administrator.

[KEMBALI KE DASHBOARD]
```

---

## 🎯 IMPLEMENTATION PHASES

### **Phase 1: Core Validation Logic**
- [ ] Add time-window validation di ChecklistController
- [ ] Add periode status tracking
- [ ] Add rejection message logic
- [ ] Database migration untuk audit fields

### **Phase 2: UI/UX Implementation**
- [ ] Update checklist list view dengan status siap/hidden
- [ ] Add countdown timer untuk checklist yang akan siap
- [ ] Update modal notifikasi dengan validation messages
- [ ] Add shift info display di dashboard

### **Phase 3: Advanced Features**
- [ ] Auto-lock mechanism saat shift berakhir
- [ ] Auto-reset mechanism saat shift baru dimulai
- [ ] Audit logging untuk semua attempt (failed/success)
- [ ] Compliance dashboard untuk admin

---

## ✅ READY FOR IMPLEMENTATION

**Analisa Status:** ✅ COMPLETE  
**Detail Level:** ✅ COMPREHENSIVE  
**API Specs:** ✅ DEFINED  
**UI Mockups:** ✅ DETAILED  
**Edge Cases:** ✅ COVERED  

**Next Step:** Approval → Code Implementation
