# 🏢 DOKUMENTASI MANAJEMEN PERAWATAN GEDUNG

## 📋 DAFTAR ISI
1. [Overview](#overview)
2. [Fitur Utama](#fitur-utama)
3. [Cara Kerja Sistem](#cara-kerja-sistem)
4. [Panduan Penggunaan](#panduan-penggunaan)
5. [Struktur Database](#struktur-database)
6. [API Routes](#api-routes)
7. [Contoh Penggunaan](#contoh-penggunaan)

---

## 🎯 OVERVIEW

**Manajemen Perawatan Gedung** adalah sistem komprehensif untuk mengontrol dan mendokumentasikan seluruh aktivitas perawatan gedung secara terstruktur. Sistem ini memastikan setiap kegiatan perawatan terlaksana dengan baik melalui checklist yang ter-reset otomatis berdasarkan periode (harian, mingguan, bulanan, tahunan).

### Tujuan Sistem:
✅ **Kontrol Penuh** - Memastikan semua kegiatan perawatan terlaksana  
✅ **Akuntabilitas** - Setiap kegiatan tercatat dengan detail (waktu, petugas, bukti foto)  
✅ **History Lengkap** - Data tidak pernah dihapus, hanya di-reset untuk periode baru  
✅ **Laporan Otomatis** - Generate laporan PDF setelah checklist selesai  
✅ **Produktivitas** - Karyawan selalu punya kegiatan yang terstruktur  

---

## 🚀 FITUR UTAMA

### 1. **Master Checklist (CRUD)**
- **Create/Read/Update/Delete** template checklist
- Kategori: Kebersihan, Perawatan Rutin, Pengecekan, Lainnya
- Periode: Harian, Mingguan, Bulanan, Tahunan
- Status: Aktif/Nonaktif
- Urutan tampilan custom

### 2. **Eksekusi Checklist**
- Interface user-friendly untuk centang checklist
- Realtime progress tracking
- Optional: Tambah catatan & foto bukti
- Validasi: Semua harus selesai untuk generate laporan
- Auto-refresh status periode

### 3. **Sistem Auto-Reset**
| Periode   | Reset Kapan                    | Format Key          | Contoh              |
|-----------|--------------------------------|---------------------|---------------------|
| Harian    | Setiap hari pukul 00:00       | harian_YYYY-MM-DD   | harian_2024-11-14   |
| Mingguan  | Setiap Senin pukul 00:00      | mingguan_YYYY-WWW   | mingguan_2024-W46   |
| Bulanan   | Setiap tanggal 1 pukul 00:00  | bulanan_YYYY-MM     | bulanan_2024-11     |
| Tahunan   | Setiap 1 Jan pukul 00:00      | tahunan_YYYY        | tahunan_2024        |

**Catatan Penting:** Data lama tetap tersimpan di database, hanya status periode yang di-reset!

### 4. **Laporan PDF**
- Generate setelah semua checklist selesai
- Detail lengkap per kategori
- Timestamp & petugas pelaksana
- Tanda tangan digital
- Downloadable & printable

### 5. **Dashboard & Navigation**
- Quick access ke semua periode
- Progress bar realtime
- Statistik eksekusi
- Link cepat ke master & laporan

---

## ⚙️ CARA KERJA SISTEM

### Flow Diagram:

```
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN: Buat Master Checklist                  │
│  (Contoh: "Buang Sampah Ruang Tamu" - Harian - Kebersihan)     │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│              KARYAWAN: Eksekusi Checklist Periode               │
│  • Buka halaman checklist (contoh: Checklist Harian)           │
│  • Centang setiap kegiatan yang sudah dilakukan                │
│  • (Optional) Tambah catatan & foto bukti                      │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                   SISTEM: Track & Validasi                      │
│  • Save ke perawatan_log (permanent record)                    │
│  • Update perawatan_status_periode (tracking progress)         │
│  • Cek apakah semua checklist selesai                          │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
                  ┌────────┴────────┐
                  │  Semua Selesai? │
                  └────────┬────────┘
                          │
          ┌───────────────┴───────────────┐
          │ YA                            │ TIDAK
          ▼                               ▼
┌──────────────────────┐        ┌──────────────────────┐
│  Button Generate     │        │  Button Disabled     │
│  Laporan AKTIF       │        │  (Masih ada yg       │
│                      │        │   belum selesai)     │
└──────────┬───────────┘        └──────────────────────┘
           │
           ▼
┌─────────────────────────────────────────────────────────────────┐
│              KARYAWAN: Generate Laporan PDF                     │
│  • Klik button "Generate Laporan"                              │
│  • Sistem create record di perawatan_laporan                   │
│  • Generate PDF dengan semua detail eksekusi                   │
│  • Mark periode sebagai completed                              │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    SISTEM: Auto-Reset                           │
│  • Periode baru dimulai (contoh: besok untuk harian)           │
│  • Create perawatan_status_periode baru                        │
│  • Checklist kembali kosong, siap dikerjakan lagi              │
│  • Data lama TETAP TERSIMPAN di perawatan_log & laporan       │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📘 PANDUAN PENGGUNAAN

### UNTUK ADMIN:

#### 1. Membuat Master Checklist
1. Login sebagai **Super Admin**
2. Buka menu **Manajemen Perawatan** > **Master Checklist**
3. Klik **Tambah Checklist**
4. Isi form:
   - **Nama Kegiatan**: Contoh "Buang Sampah Ruang Tamu"
   - **Deskripsi**: Detail kegiatan (opsional)
   - **Tipe Periode**: Harian/Mingguan/Bulanan/Tahunan
   - **Kategori**: Kebersihan/Perawatan Rutin/Pengecekan/Lainnya
   - **Urutan**: Angka untuk sorting (kecil = atas)
5. Klik **Simpan**

#### 2. Edit/Hapus Master Checklist
1. Buka **Master Checklist**
2. Pilih tab periode yang ingin diedit
3. Klik tombol **Edit** (kuning) atau **Hapus** (merah)
4. Edit data atau konfirmasi penghapusan

**⚠️ PENTING:** 
- Checklist yang dihapus akan di-soft delete (tetap ada di database)
- History eksekusi checklist yang dihapus TETAP TERSIMPAN

#### 3. Melihat Laporan
1. Buka **Manajemen Perawatan** > **Laporan**
2. Lihat daftar semua laporan yang pernah dibuat
3. Klik **Download PDF** untuk download laporan

---

### UNTUK KARYAWAN/PETUGAS:

#### 1. Mengerjakan Checklist Harian
1. Login ke sistem
2. Buka **Manajemen Perawatan** > **Checklist Harian**
3. Lihat daftar kegiatan yang harus dikerjakan hari ini
4. Centang checkbox setelah kegiatan selesai
5. (Opsional) Tambah catatan jika ada kondisi khusus
6. Perhatikan progress bar di atas

#### 2. Generate Laporan
**Syarat:** Semua checklist HARUS sudah dicentang (100% complete)

1. Setelah semua checklist selesai, button **Generate Laporan** akan aktif
2. Klik button **Generate Laporan**
3. Konfirmasi generate laporan
4. Sistem akan:
   - Create PDF laporan
   - Auto-download PDF
   - Redirect ke halaman laporan

5. PDF bisa di-download ulang kapan saja dari menu **Laporan**

#### 3. Jika Salah Centang
- Klik checkbox lagi untuk **uncheck**
- Sistem akan konfirmasi pembatalan
- Data akan dihapus dari log

---

## 🗄️ STRUKTUR DATABASE

### Tabel 1: `master_perawatan`
Template checklist yang dibuat admin

| Field          | Type    | Keterangan                                    |
|----------------|---------|-----------------------------------------------|
| id             | bigint  | Primary key                                   |
| nama_kegiatan  | string  | Nama kegiatan perawatan                       |
| deskripsi      | text    | Detail kegiatan (nullable)                    |
| tipe_periode   | enum    | harian/mingguan/bulanan/tahunan               |
| urutan         | integer | Urutan tampilan                               |
| kategori       | enum    | kebersihan/perawatan_rutin/pengecekan/lainnya |
| is_active      | boolean | Status aktif/nonaktif                         |
| created_at     | timestamp | Waktu dibuat                                |
| updated_at     | timestamp | Waktu diupdate                              |
| deleted_at     | timestamp | Soft delete                                 |

### Tabel 2: `perawatan_log`
History LENGKAP eksekusi checklist (TIDAK DIHAPUS!)

| Field               | Type      | Keterangan                          |
|---------------------|-----------|-------------------------------------|
| id                  | bigint    | Primary key                         |
| master_perawatan_id | bigint    | FK ke master_perawatan              |
| user_id             | bigint    | FK ke users (petugas)               |
| tanggal_eksekusi    | date      | Tanggal dikerjakan                  |
| waktu_eksekusi      | time      | Jam dikerjakan                      |
| status              | enum      | completed/skipped                   |
| catatan             | text      | Catatan tambahan (nullable)         |
| foto_bukti          | string    | Path foto bukti (nullable)          |
| periode_key         | string    | Key periode (harian_2024-11-14)     |
| created_at          | timestamp | Waktu log dibuat                    |
| updated_at          | timestamp | Waktu log diupdate                  |

**Index:** `tanggal_eksekusi`, `periode_key` untuk query cepat

### Tabel 3: `perawatan_laporan`
Laporan yang sudah di-generate

| Field            | Type      | Keterangan                          |
|------------------|-----------|-------------------------------------|
| id               | bigint    | Primary key                         |
| tipe_laporan     | enum      | harian/mingguan/bulanan/tahunan     |
| periode_key      | string    | Key periode                         |
| tanggal_laporan  | date      | Tanggal laporan dibuat              |
| dibuat_oleh      | bigint    | FK ke users                         |
| total_checklist  | integer   | Total item checklist                |
| total_completed  | integer   | Total yang dikerjakan               |
| ringkasan        | text      | Ringkasan otomatis                  |
| file_pdf         | string    | Path file PDF                       |
| created_at       | timestamp | Waktu dibuat                        |
| updated_at       | timestamp | Waktu diupdate                      |

**Unique:** `tipe_laporan`, `periode_key` (1 periode = 1 laporan)

### Tabel 4: `perawatan_status_periode`
Tracking status per periode (untuk validasi & UI)

| Field           | Type      | Keterangan                          |
|-----------------|-----------|-------------------------------------|
| id              | bigint    | Primary key                         |
| tipe_periode    | enum      | harian/mingguan/bulanan/tahunan     |
| periode_key     | string    | Key periode                         |
| periode_start   | date      | Mulai periode                       |
| periode_end     | date      | Akhir periode                       |
| total_checklist | integer   | Total item aktif                    |
| total_completed | integer   | Total sudah dikerjakan              |
| is_completed    | boolean   | Semua sudah selesai?                |
| completed_at    | timestamp | Kapan selesai (nullable)            |
| completed_by    | bigint    | FK ke users (nullable)              |
| created_at      | timestamp | Waktu dibuat                        |
| updated_at      | timestamp | Waktu diupdate                      |

**Unique:** `tipe_periode`, `periode_key`

---

## 🛣️ API ROUTES

### Master Checklist (Admin Only)
```php
GET     /perawatan/master              → Daftar master checklist
GET     /perawatan/master/create       → Form tambah
POST    /perawatan/master/store        → Simpan baru
GET     /perawatan/master/{id}/edit    → Form edit
PUT     /perawatan/master/{id}         → Update
DELETE  /perawatan/master/{id}         → Hapus (soft delete)
```

### Eksekusi Checklist
```php
GET     /perawatan/checklist/harian    → Checklist harian
GET     /perawatan/checklist/mingguan  → Checklist mingguan
GET     /perawatan/checklist/bulanan   → Checklist bulanan
GET     /perawatan/checklist/tahunan   → Checklist tahunan

POST    /perawatan/checklist/execute   → Centang checklist
POST    /perawatan/checklist/uncheck   → Batalkan centang
```

**Request Body (execute):**
```json
{
    "master_perawatan_id": 1,
    "tipe_periode": "harian",
    "catatan": "Sudah selesai dengan baik",
    "foto_bukti": "(file upload)"
}
```

### Laporan
```php
GET     /perawatan/laporan             → Daftar laporan
POST    /perawatan/laporan/generate    → Generate laporan PDF
GET     /perawatan/laporan/{id}/download → Download PDF
```

**Request Body (generate):**
```json
{
    "tipe_periode": "harian"
}
```

---

## 💡 CONTOH PENGGUNAAN

### Skenario 1: Checklist Harian
**Hari Senin, 14 November 2024**

1. **Pagi hari (08:00)**
   - Petugas login
   - Buka "Checklist Harian"
   - Periode: `harian_2024-11-14`
   - Status: 0/10 checklist

2. **Mengerjakan kegiatan (08:00 - 16:00)**
   - ✅ Buang Sampah Ruang Tamu (08:15)
   - ✅ Buang Sampah Kamar Mandi (08:30)
   - ✅ Sapu Lantai Ruang Utama (09:00)
   - ... (7 kegiatan lagi)
   - Status: 10/10 checklist ✅

3. **Generate Laporan (16:30)**
   - Button "Generate Laporan" aktif
   - Klik → Konfirmasi
   - PDF ter-generate: `perawatan/laporan/harian_2024-11-14_1699963800.pdf`
   - Laporan tersimpan di database

4. **Hari Selasa, 15 November 2024 (00:00)**
   - Sistem auto-reset
   - Periode baru: `harian_2024-11-15`
   - Checklist kembali kosong: 0/10
   - Data kemarin TETAP tersimpan di `perawatan_log`

---

### Skenario 2: Checklist Mingguan
**Minggu 46, Tahun 2024 (13-19 Nov 2024)**

1. **Senin (13 Nov)**
   - Periode: `mingguan_2024-W46`
   - Checklist: 0/7

2. **Selasa - Jumat**
   - Karyawan mengerjakan secara bertahap
   - Selasa: 2/7
   - Rabu: 4/7
   - Kamis: 6/7
   - Jumat: 7/7 ✅

3. **Generate Laporan Jumat sore**
   - Semua selesai
   - Generate PDF mingguan
   - Laporan tersimpan

4. **Senin (20 Nov) - Minggu Baru**
   - Periode: `mingguan_2024-W47`
   - Checklist reset ke 0/7
   - Data minggu lalu tetap di database

---

### Skenario 3: Admin Menambah Checklist Baru
**Admin menambah kegiatan "Bersihkan Filter Air" (Mingguan)**

1. **Sebelum ditambah:**
   - Periode: `mingguan_2024-W46`
   - Total checklist: 7
   - Status: 7/7 (100%)

2. **Admin tambah checklist baru:**
   - Nama: "Bersihkan Filter Air"
   - Periode: Mingguan
   - Status: Aktif

3. **Sistem auto-update:**
   - `perawatan_status_periode` update:
     - `total_checklist`: 7 → 8
     - `is_completed`: true → false
   - Progress: 7/8 (87.5%)

4. **Karyawan:**
   - Lihat checklist baru
   - Centang setelah dikerjakan
   - Progress kembali 8/8 (100%)
   - Bisa generate laporan lagi (update)

---

## 🔒 KEAMANAN DATA

### Data TIDAK PERNAH DIHAPUS:
1. ✅ **perawatan_log** → History lengkap PERMANENT
2. ✅ **perawatan_laporan** → Laporan PDF PERMANENT
3. ✅ **master_perawatan** → Soft delete (bisa restore)

### Yang Di-Reset:
1. ⚡ **perawatan_status_periode** → Status untuk periode baru
2. ⚡ **UI Checklist** → Tampilan kosong untuk periode baru

**Kesimpulan:** Sistem ini safe! Data history lengkap tersimpan untuk audit & analisis.

---

## 📊 ANALISIS & REPORTING

Dengan data yang lengkap, admin bisa analisis:

### Query Contoh:

**1. Total eksekusi per kategori bulan ini**
```sql
SELECT 
    mp.kategori,
    COUNT(*) as total_eksekusi
FROM perawatan_log pl
JOIN master_perawatan mp ON pl.master_perawatan_id = mp.id
WHERE pl.tanggal_eksekusi BETWEEN '2024-11-01' AND '2024-11-30'
GROUP BY mp.kategori
```

**2. Petugas paling produktif**
```sql
SELECT 
    u.name,
    COUNT(*) as total_kegiatan
FROM perawatan_log pl
JOIN users u ON pl.user_id = u.id
WHERE pl.tanggal_eksekusi >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY u.id
ORDER BY total_kegiatan DESC
```

**3. Kegiatan yang sering terlewat**
```sql
SELECT 
    mp.nama_kegiatan,
    mp.tipe_periode,
    COUNT(DISTINCT pl.periode_key) as periode_dilaksanakan
FROM master_perawatan mp
LEFT JOIN perawatan_log pl ON mp.id = pl.master_perawatan_id
WHERE mp.is_active = 1
GROUP BY mp.id
HAVING periode_dilaksanakan < 10 -- Misal: dilakukan < 10x
ORDER BY periode_dilaksanakan ASC
```

---

## 🎓 BEST PRACTICES

### Untuk Admin:
1. ✅ Buat checklist yang **SPESIFIK** dan **MEASURABLE**
2. ✅ Gunakan deskripsi untuk detail SOP
3. ✅ Atur urutan berdasarkan workflow (pagi ke sore)
4. ✅ Review periodik: checklist masih relevan?
5. ✅ Jangan terlalu banyak checklist (fokus yang penting)

### Untuk Karyawan:
1. ✅ Centang **SETELAH** kegiatan selesai, bukan sebelum
2. ✅ Tambah catatan jika ada kondisi abnormal
3. ✅ Upload foto bukti untuk kegiatan krusial
4. ✅ Generate laporan sebelum pulang kerja
5. ✅ Laporkan ke admin jika ada checklist yang tidak relevan

### Untuk Sistem:
1. ✅ Backup database regular (history penting!)
2. ✅ Monitor storage foto bukti
3. ✅ Set cron job untuk cleanup file PDF lama (opsional)
4. ✅ Monitor performa query dengan index yang tepat

---

## 🐛 TROUBLESHOOTING

### Problem 1: Button "Generate Laporan" tidak muncul
**Solusi:**
- Pastikan SEMUA checklist sudah dicentang
- Cek `perawatan_status_periode.is_completed = true`
- Refresh halaman

### Problem 2: Checklist tidak bisa dicentang
**Solusi:**
- Cek koneksi internet
- Cek error di console browser (F12)
- Pastikan user sudah login
- Cek permission user

### Problem 3: PDF tidak ter-generate
**Solusi:**
- Cek folder `storage/app/public/perawatan/laporan/` writable
- Cek DomPDF terinstall: `composer require barryvdh/laravel-dompdf`
- Cek error log: `storage/logs/laravel.log`

### Problem 4: Data checklist hilang setelah ganti hari
**Ini BUKAN Bug!** 
- Data TIDAK hilang, hanya periode baru
- Data lama ada di `perawatan_log`
- Lihat di menu **Laporan** untuk history

---

## 📞 SUPPORT

Jika ada pertanyaan atau butuh bantuan:
1. Baca dokumentasi ini dengan teliti
2. Cek tabel database untuk memahami struktur data
3. Review code di `ManajemenPerawatanController.php`
4. Hubungi developer sistem

---

**Bismillah, semoga sistem ini bermanfaat untuk menjaga gedung tetap bersih dan terawat! 🏢✨**

---

*Generated: 14 November 2024*  
*Version: 1.0.0*  
*Author: AI Assistant*
