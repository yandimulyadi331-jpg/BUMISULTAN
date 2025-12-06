# 🔗 Integrasi Otomatis: Kehadiran Tukang ↔️ Keuangan Tukang

## 📋 Overview
Sistem ini secara **otomatis** mencatat transaksi keuangan setiap kali status kehadiran atau lembur diubah di halaman Absensi Tukang.

---

## ✨ Fitur Auto-Integration

### 1️⃣ **Toggle Status Kehadiran**
Saat Anda mengklik tombol status kehadiran (Tidak Hadir → Hadir → Setengah Hari):

**Otomatis mencatat ke `keuangan_tukangs`:**
- ✅ **Jika HADIR**: Record upah harian penuh
- ✅ **Jika SETENGAH HARI**: Record upah setengah hari
- ❌ **Jika TIDAK HADIR**: Hapus record upah harian (jika ada)

**Data yang tercatat:**
```php
[
    'tipe_transaksi' => 'pemasukan',
    'jenis_transaksi' => 'upah_harian',
    'jumlah' => Rp 150.000, // sesuai tarif tukang
    'keterangan' => 'Upah harian - Hadir',
    'tanggal' => '2025-11-10'
]
```

---

### 2️⃣ **Toggle Lembur**
Saat Anda mengklik tombol lembur (Tidak → Full → Setengah Hari):

**Otomatis mencatat ke `keuangan_tukangs`:**
- ✅ **Jika LEMBUR FULL**: Record upah lembur full (75% dari tarif)
- ✅ **Jika LEMBUR SETENGAH HARI**: Record upah lembur setengah (37.5% dari tarif)
- ❌ **Jika TIDAK LEMBUR**: Hapus record upah lembur (jika ada)

**Data yang tercatat:**
```php
[
    'tipe_transaksi' => 'pemasukan',
    'jenis_transaksi' => 'lembur_full', // atau 'lembur_setengah_hari'
    'jumlah' => Rp 112.500, // 75% dari tarif
    'keterangan' => 'Upah lembur - Full (Cash)' // atau '(Kamis)'
]
```

---

## 🔄 Alur Kerja

```
┌─────────────────────────────────────────────────────┐
│  HALAMAN ABSENSI TUKANG                             │
│  (Fokus: Kehadiran & Lembur SAJA)                   │
└─────────────────────────────────────────────────────┘
                       │
                       │ Klik Toggle Status/Lembur
                       ▼
┌─────────────────────────────────────────────────────┐
│  KehadiranTukangController                          │
│  - toggleStatus() atau toggleLembur()               │
│  - hitungUpah()                                     │
│  - syncKeuangan() ← AUTO-SYNC KE KEUANGAN           │
└─────────────────────────────────────────────────────┘
                       │
                       │ Automatic Insert/Update/Delete
                       ▼
┌─────────────────────────────────────────────────────┐
│  TABEL keuangan_tukangs                             │
│  - Upah harian tercatat otomatis                    │
│  - Upah lembur tercatat otomatis                    │
│  - Linked ke kehadiran_tukang_id                    │
└─────────────────────────────────────────────────────┘
                       │
                       │ Lihat akumulasi
                       ▼
┌─────────────────────────────────────────────────────┐
│  HALAMAN KEUANGAN TUKANG                            │
│  (Melihat: Total Upah, Pinjaman, Potongan, Laporan) │
└─────────────────────────────────────────────────────┘
```

---

## 🎯 Pemisahan Fungsi

### **Halaman Absensi Tukang** (kehadiran-tukang.index)
**Fokus HANYA pada:**
- ✅ Toggle status kehadiran
- ✅ Toggle lembur
- ❌ TIDAK ADA kolom Tarif/Hari
- ❌ TIDAK ADA kolom Total Upah
- ❌ TIDAK ADA tombol Aksi (Hapus)

**Kolom yang ditampilkan:**
| No | Kode | Nama Tukang | Status Kehadiran | Lembur |
|----|------|-------------|------------------|---------|
| 1  | TK001| JAENUDIN    | 🟢 Hadir         | 🔴 Full |

---

### **Halaman Keuangan Tukang** (keuangan-tukang.index)
**Fokus pada:**
- 💰 Akumulasi upah harian
- 💰 Akumulasi upah lembur
- 💸 Pembayaran cash lembur
- 💳 Pinjaman tukang
- ✂️ Potongan/denda
- 📊 Laporan keuangan

**Dashboard menampilkan:**
```
Total Upah Bulan Ini    Total Lembur      Total Pinjaman    Total Potongan
   Rp 4.500.000         Rp 1.200.000       Rp 500.000        Rp 200.000
```

---

## 💾 Struktur Database

### Tabel: `kehadiran_tukangs`
```sql
- id
- tukang_id
- tanggal
- status (hadir/setengah_hari/tidak_hadir)
- lembur (tidak/full/setengah_hari)
- lembur_dibayar_cash (boolean)
- upah_harian (calculated)
- upah_lembur (calculated)
- total_upah (calculated)
```

### Tabel: `keuangan_tukangs` (Auto-generated dari kehadiran)
```sql
- id
- tukang_id
- tanggal
- kehadiran_tukang_id ← Link ke kehadiran
- tipe_transaksi (pemasukan/pengeluaran)
- jenis_transaksi (upah_harian/lembur_full/lembur_setengah_hari)
- jumlah
- keterangan
- dicatat_oleh
```

---

## 🧪 Testing Flow

1. **Buka halaman Absensi Tukang** (Menu: Manajemen Tukang → Kehadiran Tukang)
2. **Klik toggle status** pada tukang (misal: JAENUDIN)
   - Status berubah: Tidak Hadir → Hadir
   - ✅ Auto-create record di `keuangan_tukangs` dengan `jenis_transaksi = 'upah_harian'`

3. **Klik toggle lembur** pada tukang yang sama
   - Lembur berubah: Tidak → Full
   - ✅ Auto-create record di `keuangan_tukangs` dengan `jenis_transaksi = 'lembur_full'`

4. **Buka halaman Keuangan Tukang** (Menu: Manajemen Tukang → Keuangan Tukang)
   - Lihat dashboard: Total upah hari ini sudah terupdate
   - Klik "Lihat Detail" pada tukang JAENUDIN
   - ✅ Muncul 2 transaksi: Upah harian + Upah lembur

5. **Kembali ke Absensi, klik lagi toggle status** → Setengah Hari
   - ✅ Auto-update record upah harian menjadi setengah
   - ✅ Jumlah upah otomatis berubah di keuangan

6. **Klik lagi toggle status** → Tidak Hadir
   - ❌ Auto-delete record upah harian
   - ❌ Auto-delete record upah lembur (karena tidak hadir = tidak bisa lembur)

---

## 🔧 Method Sync (Backend)

### File: `KehadiranTukangController.php`

```php
/**
 * Sync data kehadiran ke tabel keuangan_tukangs
 * Auto-create/update transaksi upah harian dan lembur
 */
private function syncKeuangan(KehadiranTukang $kehadiran)
{
    // 1. Sync Upah Harian
    if (in_array($kehadiran->status, ['hadir', 'setengah_hari'])) {
        KeuanganTukang::updateOrCreate([...], [...]);
    } else {
        // Hapus jika tidak hadir
        KeuanganTukang::where(...)->delete();
    }
    
    // 2. Sync Upah Lembur
    if ($kehadiran->lembur != 'tidak') {
        KeuanganTukang::updateOrCreate([...], [...]);
    } else {
        // Hapus jika tidak lembur
        KeuanganTukang::where(...)->delete();
    }
}
```

**Dipanggil otomatis di:**
- ✅ `toggleStatus()` - Setiap kali status diubah
- ✅ `toggleLembur()` - Setiap kali lembur diubah
- ✅ `store()` - Saat menyimpan absensi manual

---

## 🎉 Keuntungan Sistem Ini

✅ **Tidak perlu input manual** di 2 tempat (absensi + keuangan)
✅ **Data selalu sinkron** antara kehadiran dan keuangan
✅ **Pemisahan UI** yang jelas: Absensi fokus kehadiran, Keuangan fokus finansial
✅ **Audit trail** lengkap dengan `kehadiran_tukang_id` sebagai foreign key
✅ **Otomatis update/delete** saat status berubah

---

## 📚 File Terkait

- **View Absensi**: `resources/views/manajemen-tukang/kehadiran/index.blade.php`
- **View Keuangan**: `resources/views/keuangan-tukang/index.blade.php`
- **Controller Kehadiran**: `app/Http/Controllers/KehadiranTukangController.php`
- **Controller Keuangan**: `app/Http/Controllers/KeuanganTukangController.php`
- **Model**: `app/Models/KeuanganTukang.php`
- **Routes**: `routes/web.php`

---

**Dibuat:** 10 November 2025  
**Status:** ✅ Production Ready
