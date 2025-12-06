# DOKUMENTASI INTEGRASI PINJAMAN DENGAN PAYROLL

## 📋 DESKRIPSI

Fitur integrasi pinjaman dengan sistem payroll memungkinkan **potongan cicilan pinjaman karyawan** secara otomatis dipotong dari gaji bulanan mereka. Sistem ini terintegrasi dengan menu **Penyesuaian Gaji** yang sudah ada di payroll.

---

## ✨ FITUR UTAMA

### 1. **Auto Generate Potongan Gaji**
- Otomatis mendeteksi cicilan yang jatuh tempo di periode payroll
- Generate potongan ke sistem penyesuaian gaji
- Hanya untuk karyawan (crew), bukan non-crew

### 2. **Tracking Status Potongan**
- Status "Dipotong Gaji" pada cicilan yang sudah diproses
- Tanggal kapan dipotong via payroll
- Kode penyesuaian gaji yang terkait

### 3. **Summary Report**
- Laporan detail potongan per karyawan
- Total cicilan dan total nominal
- Detail nomor pinjaman dan cicilan ke berapa

### 4. **Integrasi dengan Slip Gaji**
- Potongan otomatis muncul di slip gaji karyawan
- Kolom "Pengurang" akan bertambah sesuai cicilan
- Keterangan detail nomor pinjaman

---

## 🗄️ DATABASE STRUCTURE

### Tabel: `pinjaman_cicilan`

| Field | Type | Description |
|-------|------|-------------|
| `auto_potong_gaji` | boolean | Default true - Otomatis potong via payroll |
| `kode_penyesuaian_gaji` | char(9) | Kode penyesuaian gaji (contoh: PYG112025) |
| `sudah_dipotong` | boolean | Status sudah dipotong atau belum |
| `tanggal_dipotong` | date | Tanggal saat dipotong via payroll |

---

## 📊 ALUR KERJA SISTEM

### Step 1: Pinjaman Disetujui & Dicairkan
```
1. Admin approve pinjaman karyawan
2. System generate jadwal cicilan dengan tanggal jatuh tempo
3. Setiap cicilan default: auto_potong_gaji = true
```

### Step 2: Generate Potongan di Payroll
```
1. Admin buka menu: Payroll > Penyesuaian Gaji > Pilih Periode (Bulan/Tahun)
2. Klik tombol "Generate Potongan Pinjaman"
3. System akan:
   ✓ Cari semua cicilan yang jatuh tempo di bulan tersebut
   ✓ Filter hanya karyawan (kategori_peminjam = 'crew')
   ✓ Filter yang belum dipotong (sudah_dipotong = false)
   ✓ Filter yang tidak ditunda (is_ditunda = false)
   ✓ Filter yang status != 'lunas'
   
4. Untuk setiap cicilan:
   ✓ Buat/update entry di karyawan_penyesuaian_gaji_detail
   ✓ Kolom "pengurang" += jumlah_cicilan
   ✓ Keterangan: "Cicilan Pinjaman [NOMOR] ke-[X]: Rp [JUMLAH]"
   ✓ Update cicilan: sudah_dipotong = true, tanggal_dipotong = now()
   ✓ Log history di tabel pinjaman_history
```

### Step 3: Slip Gaji Generate
```
1. Admin generate slip gaji untuk periode tersebut
2. System ambil data dari karyawan_penyesuaian_gaji_detail
3. Kolom "Pengurang" otomatis include potongan pinjaman
4. Gaji bersih = Bruto - Potongan (include pinjaman) + Penambah
```

---

## 🎯 CARA PENGGUNAAN

### A. Generate Potongan Pinjaman Bulanan

**Menu**: `Payroll > Penyesuaian Gaji`

1. **Pilih/Buat Periode**
   - Klik "Tambah Data"
   - Pilih Bulan dan Tahun
   - Simpan

2. **Generate Potongan Pinjaman**
   - Klik "Set Karyawan" pada periode yang dipilih
   - Klik tombol "Generate Potongan Pinjaman"
   - System akan proses otomatis

3. **Review Summary**
   - Klik tombol "Summary Pinjaman"
   - Lihat detail karyawan yang dipotong
   - Cek total cicilan dan nominal

4. **Generate Slip Gaji**
   - Lanjut ke menu Slip Gaji
   - Cetak slip gaji untuk periode tersebut
   - Potongan pinjaman akan otomatis muncul

### B. Melihat Status Potongan di Detail Pinjaman

**Menu**: `Pinjaman > Detail Pinjaman`

1. Buka detail pinjaman karyawan
2. Di tabel jadwal cicilan, perhatikan:
   - Badge **"Dipotong Gaji"** (hijau) = Sudah dipotong via payroll
   - Info tanggal kapan dipotong
   - Keterangan kode penyesuaian gaji

### C. Membatalkan Potongan (Jika Diperlukan)

Jika ada kesalahan dan perlu dibatalkan:

1. **Manual via Penyesuaian Gaji**
   - Buka menu Penyesuaian Gaji
   - Edit/Hapus entry karyawan yang salah

2. **Update Status di Database** (untuk advanced user)
   ```sql
   UPDATE pinjaman_cicilan 
   SET sudah_dipotong = 0, 
       kode_penyesuaian_gaji = NULL,
       tanggal_dipotong = NULL
   WHERE id = [ID_CICILAN];
   ```

---

## 📁 FILE YANG DIMODIFIKASI/DIBUAT

### 1. Migration
- `database/migrations/2025_11_24_100000_add_payroll_integration_to_pinjaman.php`
  - Menambah field integrasi payroll

### 2. Service Class
- `app/Services/PinjamanPayrollService.php`
  - Method `generatePotonganPinjaman()` - Generate potongan bulanan
  - Method `getSummaryPotongan()` - Get summary report
  - Method `batalkanPotonganPayroll()` - Batalkan potongan

### 3. Controller
- `app/Http/Controllers/PenyesuaiangajiController.php`
  - Method `generatePotonganPinjaman()` - Handler generate
  - Method `summaryPotonganPinjaman()` - Handler summary

### 4. Model
- `app/Models/PinjamanCicilan.php`
  - Tambah fillable dan casts untuk field baru

### 5. Routes
- `routes/web.php`
  - Route `penyesuaiangaji.generatePotonganPinjaman`
  - Route `penyesuaiangaji.summaryPinjaman`

### 6. Views
- `resources/views/payroll/penyesuaiangaji/setkaryawan.blade.php`
  - Tambah tombol Generate dan Summary
  
- `resources/views/payroll/penyesuaiangaji/summary_pinjaman.blade.php`
  - View baru untuk summary report
  
- `resources/views/pinjaman/show.blade.php`
  - Tambah badge status "Dipotong Gaji"
  - Tambah info tanggal dipotong

---

## 🔄 BUSINESS RULES

### ✅ Cicilan yang AKAN Dipotong:
1. ✓ Kategori peminjam = 'crew' (karyawan)
2. ✓ Status pinjaman = 'berjalan'
3. ✓ Status cicilan != 'lunas'
4. ✓ is_ditunda = false
5. ✓ auto_potong_gaji = true
6. ✓ sudah_dipotong = false
7. ✓ Jatuh tempo di bulan/tahun yang dipilih

### ❌ Cicilan yang TIDAK Dipotong:
1. ✗ Non-crew (peminjam non-karyawan)
2. ✗ Cicilan sudah lunas
3. ✗ Cicilan yang ditunda
4. ✗ auto_potong_gaji = false (manual)
5. ✗ Sudah dipotong sebelumnya

---

## 💡 CONTOH SKENARIO

### Skenario 1: Karyawan dengan 1 Pinjaman
```
Karyawan: Budi (NIK: K001)
Pinjaman: PIN-2025-001
Cicilan: 12x @ Rp 500.000
Periode Payroll: November 2025

Generate Potongan:
- System cek cicilan yang jatuh tempo November 2025
- Ditemukan: Cicilan ke-5 = Rp 500.000
- Buat entry di penyesuaian_gaji_detail:
  * NIK: K001
  * Pengurang: Rp 500.000
  * Keterangan: "Cicilan Pinjaman PIN-2025-001 ke-5: Rp 500.000"
- Update cicilan: sudah_dipotong = true

Slip Gaji November:
Gaji Pokok: Rp 5.000.000
Tunjangan: Rp 1.000.000
Bruto: Rp 6.000.000
Potongan BPJS: Rp 100.000
Potongan Pinjaman: Rp 500.000 ← OTOMATIS
Total Potongan: Rp 600.000
GAJI BERSIH: Rp 5.400.000
```

### Skenario 2: Karyawan dengan Multiple Pinjaman
```
Karyawan: Ani (NIK: K002)
Pinjaman 1: PIN-2025-010 → Cicilan ke-3 = Rp 300.000
Pinjaman 2: PIN-2025-020 → Cicilan ke-1 = Rp 200.000
Periode Payroll: November 2025

Generate Potongan:
- Ditemukan 2 cicilan jatuh tempo
- Buat 1 entry di penyesuaian_gaji_detail:
  * NIK: K002
  * Pengurang: Rp 500.000 (300rb + 200rb)
  * Keterangan: 
    "Cicilan Pinjaman PIN-2025-010 ke-3: Rp 300.000
     Cicilan Pinjaman PIN-2025-020 ke-1: Rp 200.000"

Slip Gaji:
Potongan Pinjaman: Rp 500.000 ← Total dari 2 pinjaman
```

---

## 📊 REPORT & MONITORING

### Summary Report Menampilkan:
1. **Header Info**
   - Periode (Bulan/Tahun)
   - Kode Penyesuaian Gaji
   - Total Karyawan yang dipotong
   - Total Cicilan
   - Total Nominal Potongan

2. **Detail per Karyawan**
   - NIK dan Nama
   - Jumlah cicilan
   - Total potongan
   - List detail pinjaman:
     * Nomor pinjaman
     * Cicilan ke berapa
     * Nominal
     * Tanggal jatuh tempo

---

## 🔐 SECURITY & PERMISSION

### Required Permissions:
- `penyesuaiangaji.edit` - Untuk generate dan view summary
- `slipgaji.index` - Untuk lihat slip gaji

### User yang Bisa Akses:
- Admin Payroll
- HR Manager
- Super Admin

---

## ⚠️ TROUBLESHOOTING

### Problem 1: Cicilan Tidak Muncul Saat Generate
**Solusi:**
1. Cek kategori_peminjam = 'crew'
2. Cek tanggal_jatuh_tempo sesuai periode
3. Cek status != 'lunas'
4. Cek is_ditunda = false
5. Cek sudah_dipotong = false

### Problem 2: Potongan Tidak Muncul di Slip Gaji
**Solusi:**
1. Pastikan generate potongan sudah dilakukan
2. Cek data di tabel karyawan_penyesuaian_gaji_detail
3. Pastikan NIK karyawan match
4. Regenerate slip gaji

### Problem 3: Duplikasi Potongan
**Solusi:**
1. Jangan klik generate 2x untuk periode yang sama
2. System sudah ada proteksi dengan flag sudah_dipotong
3. Jika tetap duplikat, hapus manual di penyesuaian gaji

---

## 📈 FUTURE ENHANCEMENT

### Roadmap:
1. ⏳ **Auto Generate Scheduled** - Cron job otomatis setiap awal bulan
2. ⏳ **Email Notification** - Notif ke karyawan tentang potongan gaji
3. ⏳ **Mobile View** - Karyawan bisa lihat via mobile
4. ⏳ **Bulk Import** - Upload excel untuk potongan custom
5. ⏳ **Report Export** - Export PDF/Excel summary potongan

---

## 🎓 BEST PRACTICES

### Untuk Admin Payroll:
1. ✓ Generate potongan pinjaman **sebelum** generate slip gaji
2. ✓ Review summary report sebelum finalize payroll
3. ✓ Backup data sebelum generate (jaga-jaga)
4. ✓ Koordinasi dengan bagian keuangan tentang cash flow

### Untuk Manager:
1. ✓ Monitor laporan bulanan potongan pinjaman
2. ✓ Pastikan cicilan tidak melebihi 30% gaji karyawan
3. ✓ Review karyawan yang sering telat bayar

---

## 📞 SUPPORT

Jika ada pertanyaan atau issue:
1. Cek dokumentasi ini terlebih dahulu
2. Lihat log di `storage/logs/laravel.log`
3. Contact: IT Support / Developer

---

**Created:** November 24, 2025
**Last Updated:** November 24, 2025
**Version:** 1.0.0
**Status:** ✅ Production Ready
