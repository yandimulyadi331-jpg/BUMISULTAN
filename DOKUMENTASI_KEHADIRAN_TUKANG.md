# DOKUMENTASI KEHADIRAN TUKANG

## 📋 Ringkasan
Sistem **Kehadiran Tukang** adalah fitur untuk mengelola absensi harian tukang bangunan dengan perhitungan gaji otomatis berdasarkan kehadiran, jam kerja, dan lembur.

## 🎯 Fitur Utama

### 1. **Absensi Harian** (Toggle 1 Klik)
- ✅ **Hadir** (Full Day) = 100% tarif harian = 8 jam kerja
- ⏰ **Setengah Hari** = 50% tarif harian = 4 jam kerja
- ❌ **Tidak Hadir** = 0% tarif = 0 jam kerja
- 🔄 **Toggle Status**: Klik tombol untuk cycle status (Tidak Hadir → Hadir → Setengah Hari)

### 2. **Lembur Tukang**
- Toggle switch untuk aktifkan/nonaktifkan lembur
- Upah lembur = 100% tarif harian (sama dengan upah harian)
- Hanya bisa aktif jika status HADIR atau SETENGAH HARI
- Total upah = Upah harian + Upah lembur

### 3. **Rekap Kehadiran**
- Laporan bulanan per tukang
- Hitung otomatis:
  - Total hari hadir
  - Total setengah hari
  - Total tidak hadir
  - Total lembur
  - **Total gaji yang harus dibayar**
- Filter by bulan & tahun

### 4. **Detail Per Tukang**
- Rincian harian per bulan
- Tanggal, hari, status, jam kerja
- Breakdown upah (harian + lembur)
- Summary bulanan

### 5. **Hari Jumat LIBUR**
- Otomatis detect hari Jumat
- Tidak ada absensi di hari Jumat
- Peringatan ditampilkan

## 💰 Sistem Perhitungan Gaji

### Formula:

```
Status HADIR (Full Day):
- Jam Kerja: 8 jam
- Upah Harian: 100% x Tarif Harian
- Upah Lembur: Tarif Harian (jika lembur aktif)
- Total: Upah Harian + Upah Lembur

Status SETENGAH HARI:
- Jam Kerja: 4 jam
- Upah Harian: 50% x Tarif Harian
- Upah Lembur: Tarif Harian (jika lembur aktif)
- Total: Upah Harian + Upah Lembur

Status TIDAK HADIR:
- Jam Kerja: 0 jam
- Upah Harian: 0
- Upah Lembur: 0 (tidak bisa lembur)
- Total: 0
```

### Contoh Perhitungan:

**Tukang A** dengan tarif harian **Rp 150.000**

| Hari | Status | Lembur | Upah Harian | Upah Lembur | Total |
|------|--------|--------|-------------|-------------|-------|
| Senin | Hadir | Ya | Rp 150.000 | Rp 150.000 | **Rp 300.000** |
| Selasa | Hadir | Tidak | Rp 150.000 | Rp 0 | **Rp 150.000** |
| Rabu | Setengah Hari | Ya | Rp 75.000 | Rp 150.000 | **Rp 225.000** |
| Kamis | Tidak Hadir | - | Rp 0 | Rp 0 | **Rp 0** |
| Jumat | **LIBUR** | - | - | - | - |
| Sabtu | Hadir | Tidak | Rp 150.000 | Rp 0 | **Rp 150.000** |

**Total Gaji Minggu Ini: Rp 825.000**

## 📂 Struktur Database

### Tabel: `kehadiran_tukangs`

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| tukang_id | foreignId | Relasi ke table tukangs |
| tanggal | date | Tanggal absensi |
| status | enum | hadir / tidak_hadir / setengah_hari |
| lembur | boolean | TRUE jika lembur |
| jam_kerja | decimal(5,2) | 8.00 / 4.00 / 0.00 |
| upah_harian | decimal(12,2) | Upah kerja hari ini |
| upah_lembur | decimal(12,2) | Upah lembur |
| total_upah | decimal(12,2) | upah_harian + upah_lembur |
| keterangan | text | Catatan tambahan |
| dicatat_oleh | string | User yang absen |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Index:**
- `tukang_id, tanggal`
- `tanggal`

**Unique Constraint:**
- `tukang_id + tanggal` (satu tukang hanya bisa absen sekali per hari)

## 🎨 Tampilan & UX

### Halaman Absensi Harian
- List semua tukang aktif
- Foto tukang (jika ada)
- Tombol status dengan warna:
  - 🔴 Abu-abu = Tidak Hadir
  - 🟢 Hijau = Hadir
  - 🟡 Kuning = Setengah Hari
- Toggle switch lembur
- Real-time update upah
- Total upah hari ini di footer

### Halaman Rekap
- Filter bulan & tahun
- Tabel summary per tukang
- Badge warna untuk setiap status
- Total gaji keseluruhan
- Summary cards (Total hadir, lembur, dll)
- Link ke detail per tukang

### Halaman Detail Per Tukang
- Info tukang lengkap dengan foto
- Rincian harian per bulan
- Hari & tanggal dalam Bahasa Indonesia
- Breakdown upah harian + lembur
- Summary cards statistik
- Total gaji bulan berjalan

## 🚀 Cara Penggunaan

### 1. Absensi Harian (Setiap Sore)

**Langkah:**
1. Login sebagai admin
2. Buka: **Manajemen Tukang → Kehadiran Tukang**
3. Pilih tanggal (default: hari ini)
4. Untuk setiap tukang:
   - **Klik tombol status** untuk ubah: Tidak Hadir → Hadir → Setengah Hari
   - **Toggle switch lembur** jika ada lembur
5. Upah otomatis terhitung
6. Selesai! Data tersimpan otomatis

**Tips:**
- Klik berkali-kali untuk cycle status
- Lembur hanya bisa aktif jika status HADIR atau SETENGAH HARI
- Hari Jumat otomatis libur

### 2. Lihat Rekap Bulanan

**Langkah:**
1. Buka: **Manajemen Tukang → Rekap Kehadiran**
2. Pilih Bulan & Tahun
3. Lihat summary:
   - Jumlah hadir, setengah hari, tidak hadir
   - Total hari lembur
   - **Total gaji yang harus dibayar**
4. Klik **icon mata** untuk detail per tukang

### 3. Detail Per Tukang

**Langkah:**
1. Dari halaman Rekap, klik icon mata
2. Atau akses: **Kehadiran Tukang → Rekap → Detail**
3. Lihat:
   - Rincian harian lengkap
   - Tanggal, hari, status, jam kerja
   - Upah per hari
   - Total gaji bulan ini

## 🔐 Permissions

| Permission | Keterangan |
|-----------|------------|
| `kehadiran-tukang.index` | Akses halaman absensi harian |
| `kehadiran-tukang.absen` | Absen tukang (toggle status & lembur) |
| `kehadiran-tukang.rekap` | Akses rekap & detail kehadiran |

**Auto-assigned ke:** Super Admin

## 📊 Integrasi dengan Keuangan

> 📝 **Note:** Saat ini modul kehadiran sudah lengkap dengan perhitungan gaji otomatis. Data sudah siap untuk integrasi dengan modul keuangan di masa depan.

**Data yang tersedia untuk integrasi:**
- Total upah per hari per tukang
- Total upah per bulan per tukang
- Rincian upah harian vs lembur
- Jam kerja aktual

**Rencana Integrasi:**
```php
// Contoh: Ambil total gaji bulan ini
$totalGaji = KehadiranTukang::bulan(2025, 11)->sum('total_upah');

// Per tukang
$gajiTukang = KehadiranTukang::where('tukang_id', $id)
                             ->bulan(2025, 11)
                             ->sum('total_upah');
```

## 🛠️ API / Method Penting

### Model: KehadiranTukang

```php
// Hitung upah otomatis
$kehadiran->hitungUpah()->save();

// Scope filter tanggal
KehadiranTukang::tanggal('2025-11-10')->get();

// Scope filter bulan
KehadiranTukang::bulan(2025, 11)->get();

// Scope filter periode
KehadiranTukang::periode('2025-11-01', '2025-11-30')->get();

// Relasi ke tukang
$kehadiran->tukang; // Get data tukang
```

### Controller: KehadiranTukangController

```php
// Halaman absensi
index(Request $request)

// Toggle status kehadiran
toggleStatus(Request $request) // AJAX

// Toggle lembur
toggleLembur(Request $request) // AJAX

// Rekap bulanan
rekap(Request $request)

// Detail per tukang
detail($tukang_id, Request $request)
```

## 📝 Validasi & Aturan Bisnis

✅ **Validasi:**
- Satu tukang hanya bisa absen sekali per hari (unique constraint)
- Lembur hanya bisa aktif jika status bukan TIDAK HADIR
- Tanggal tidak boleh kosong
- Status harus: hadir / tidak_hadir / setengah_hari

✅ **Aturan Bisnis:**
- Hari Jumat = LIBUR (no absensi)
- Hadir full = 8 jam
- Setengah hari = 4 jam
- Tidak hadir = 0 jam
- Lembur = +100% tarif harian
- Upah auto-calculate saat save

## ⚠️ Catatan Penting

1. **Data Aman:** Modul ini standalone, tidak mengubah data lain
2. **Auto Calculate:** Upah otomatis terhitung, jangan input manual
3. **Hari Jumat:** Sistem otomatis skip hari Jumat
4. **Real-time Update:** Status & upah update langsung via AJAX
5. **Integritas Data:** Unique constraint mencegah duplikasi absen

## 🐛 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Toggle tidak jalan | Clear cache: `php artisan view:clear` |
| Total upah salah | Cek tarif harian di data tukang |
| Menu tidak muncul | Logout & login kembali |
| Lembur tidak bisa aktif | Pastikan status HADIR atau SETENGAH HARI |

## ✅ Checklist Implementasi

- [x] Migration tabel kehadiran_tukangs
- [x] Model KehadiranTukang dengan relasi
- [x] Controller dengan CRUD & toggle
- [x] View absensi harian (interactive)
- [x] View rekap kehadiran
- [x] View detail per tukang
- [x] Routes dengan permission
- [x] Update sidebar menu
- [x] Setup permissions
- [x] Auto-calculate upah
- [x] Toggle status 1 klik
- [x] Toggle lembur
- [x] Filter hari Jumat (libur)
- [x] Real-time update via AJAX

---

**Dibuat:** 10 November 2025
**Status:** ✅ Complete & Ready
**Version:** 1.0.0
