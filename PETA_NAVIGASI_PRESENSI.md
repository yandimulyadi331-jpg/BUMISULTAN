# 🗺️ PETA NAVIGASI SISTEM PRESENSI

## 📊 STRUKTUR MENU PRESENSI DI SISTEM

```
ePresensiV2 (Menu Utama)
│
├─ 📱 Tracking Presensi ────────────────→ http://127.0.0.1:8000/trackingpresensi
│                                         - Real-time tracking GPS
│                                         - Peta lokasi karyawan
│                                         - Riwayat pergerakan
│
├─ 📋 Monitoring Presensi ──────────────→ http://127.0.0.1:8000/presensi
│                                         - Lihat data presensi harian
│                                         - Filter departemen, cabang, tanggal
│                                         - Detail jam masuk/keluar
│                                         - Export data
│
├─ 🔍 Face Recognition Presensi ───────→ http://127.0.0.1:8000/facerecognition-presensi
│                                         - Scan wajah untuk presensi
│                                         - Identifikasi otomatis
│                                         - Public access (no login)
│
├─ 📊 Laporan
│   └─ Presensi & Gaji ─────────────────→ http://127.0.0.1:8000/laporan/presensi
│                                         - Laporan presensi bulanan
│                                         - Perhitungan gaji
│                                         - Export Excel/PDF
│
└─ 👨‍🎓 Manajemen Saung Santri
    ├─ Data Santri
    ├─ Jadwal & Absensi Santri ────────→ http://127.0.0.1:8000/jadwal-santri
    │                                    - Jadwal santri
    │                                    - Monitoring absensi
    │                                    - Input ijin/sakit
    │
    └─ [BISA DITAMBAH] Yayasan Masar Presensi ──┐
                                                   │
                                                   └──→ Belum ada (Optional)
```

---

## 🎯 AKSES MENU CEPAT

### **Di Sidebar (Kiri):**

1. **Tracking Presensi**
   - Ikon: 📱 GPS
   - Level: Super Admin / Permission: `trackingpresensi.index`

2. **Monitoring Presensi**
   - Ikon: 📋 Clipboard
   - Level: Super Admin / Permission: `presensi.index`

3. **Laporan > Presensi & Gaji**
   - Ikon: 📊 Bar Chart
   - Level: Super Admin / Permission: `laporan.presensi`

4. **Manajemen Saung Santri > Jadwal & Absensi Santri**
   - Ikon: 👨‍🎓 People
   - Level: Super Admin / Permission: `santri.*`

---

## 📍 LOKASI BERBEDA UNTUK SETIAP DATA

### **UNTUK KARYAWAN:**
```
Monitoring Presensi
├─ Lihat semua karyawan check-in/out
├─ Filter by departemen, cabang, tanggal
├─ Lihat tracking GPS real-time
└─ Generate laporan presensi & gaji
```

### **UNTUK SANTRI:**
```
Jadwal & Absensi Santri
├─ Kelola jadwal santri
├─ Monitor absensi santri
├─ Input ijin/sakit
└─ Laporan absensi santri
```

### **UNTUK YAYASAN MASAR:**
```
❌ Belum Ada (Perlu dibuat)

Opsi:
1. Gunakan sistem Santri (adapt)
2. Gunakan sistem Karyawan (adapt)
3. Buat terpisah (recommended)
```

---

## 🔐 PERMISSION YANG DIPERLUKAN

| Fitur | Permission | Role |
|-------|-----------|------|
| **Tracking Presensi** | `trackingpresensi.index` | Super Admin |
| **Monitoring Presensi** | `presensi.index` | Super Admin |
| **Face Recognition** | None (Public) | Public |
| **Laporan Presensi** | `laporan.presensi` | Super Admin |
| **Jadwal Santri** | `santri.*` | Super Admin |
| **Absensi Santri** | `santri.*` | Super Admin |

---

## 🚀 QUICK NAVIGATION

Untuk cepat akses setiap fitur:

```bash
# Klik nama fitur di bawah untuk direct access:

📱 Tracking Presensi Real-Time
   → http://127.0.0.1:8000/trackingpresensi

📋 Monitoring Presensi Karyawan
   → http://127.0.0.1:8000/presensi

🔍 Scan Face Recognition
   → http://127.0.0.1:8000/facerecognition-presensi

📊 Laporan Presensi & Gaji
   → http://127.0.0.1:8000/laporan/presensi

👨‍🎓 Jadwal & Absensi Santri
   → http://127.0.0.1:8000/jadwal-santri

👥 Data Santri
   → http://127.0.0.1:8000/santri
```

---

## 📈 FITUR YANG TERSEDIA DI SETIAP MODUL

### **Monitoring Presensi (Karyawan)**
- ✅ Lihat presensi real-time
- ✅ Filter tanggal/departemen/cabang
- ✅ Lihat detail: NIK, Nama, Jam Masuk, Jam Keluar, Durasi
- ✅ Export ke Excel
- ✅ Lihat tracking GPS
- ✅ Rekap statistik (hadir, terlambat, izin, alpha)

### **Tracking Presensi**
- ✅ Peta Google Maps dengan lokasi real-time
- ✅ Update lokasi setiap 5 menit
- ✅ Riwayat pergerakan per hari
- ✅ Verifikasi kehadiran dengan lokasi

### **Laporan Presensi & Gaji**
- ✅ Export laporan bulanan
- ✅ Hitung total jam kerja
- ✅ Hitung potongan (terlambat, alfa)
- ✅ Perhitungan gaji otomatis
- ✅ Export PDF/Excel

### **Jadwal & Absensi Santri**
- ✅ Input jadwal aktivitas santri
- ✅ Monitoring kehadiran santri
- ✅ Input ijin/sakit
- ✅ Laporan absensi santri

---

## 💡 REKOMENDASI UNTUK YAYASAN MASAR

**Implementasi Presensi Yayasan Masar:**

```
PILIHAN 1: Adapt Santri System (Cepat)
├─ Mirip dengan monitoring santri
├─ Setup: Medium (30 menit)
└─ Cocok untuk: Yayasan dengan aktivitas terjadwal

PILIHAN 2: Adapt Karyawan System (Powerful)
├─ Full fitur seperti karyawan (tracking GPS, face recognition)
├─ Setup: Long (2 jam)
└─ Cocok untuk: Yayasan dengan sistem absensi ketat

PILIHAN 3: Custom Sederhana (Simple)
├─ Hanya monitoring check-in/out
├─ Setup: Short (45 menit)
└─ Cocok untuk: Yayasan dengan struktur sederhana
```

---

**Status: DOKUMENTASI LENGKAP ✅**

Semua lokasi presensi sudah ter-mapping. Silakan pilih fitur yang ingin Anda gunakan!
