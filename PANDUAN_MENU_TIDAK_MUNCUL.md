# 📋 PANDUAN: MENU TIDAK MUNCUL - SOLUSI LENGKAP

## ⚠️ MASALAH: Menu Hanya Dashboard

Ketika login dengan user punya role tertentu, hanya dashboard yang muncul. Menu lain tidak terlihat.

---

## 🔍 PENYEBAB

**Role belum memiliki permissions yang diperlukan!**

Sidebar aplikasi mengecek setiap menu dengan Blade condition:

```php
@if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('karyawan.index'))
    <!-- Tampilkan menu Karyawan -->
@endif
```

Jika user tidak punya:
- Role: `super admin` ATAU
- Permission: `karyawan.index`

Maka menu TIDAK akan ditampilkan.

---

## ✅ SOLUSI LENGKAP

### STEP 1: Login sebagai Super Admin

1. **Buka browser**
2. **Login dengan akun Super Admin** (akun utama yang punya full akses)
3. Pastikan Anda melihat **semua menu**

### STEP 2: Buka Edit Permissions

1. **Settings > Role > Edit Permissions**
   (atau **Konfigurasi > Roles > Edit Permissions**)

2. **Pilih role** yang ingin di-atur (contoh: "Admin", "Manager", "User")

3. **Scroll down** dan lihat **59 permission groups baru!**

### STEP 3: Centang Permissions Sesuai Kebutuhan

Contoh: Atur role "Admin" untuk bisa akses menu Karyawan dan Kendaraan:

```
CENTANG INI:
☑ karyawan.index      (agar bisa LIHAT menu Karyawan)
☑ karyawan.create     (agar bisa BUAT karyawan baru)
☑ karyawan.edit       (agar bisa EDIT karyawan)
☑ karyawan.delete     (agar bisa HAPUS karyawan)

☑ kendaraan.index     (agar bisa LIHAT menu Kendaraan)
☑ kendaraan.create    (agar bisa BUAT kendaraan baru)
☑ kendaraan.show      (agar bisa LIHAT detail kendaraan)

JANGAN CENTANG:
☐ pinjaman.index      (jika admin tidak perlu akses pinjaman)
```

### STEP 4: Klik SIMPAN

**Klik tombol SIMPAN** untuk menyimpan permissions.

### STEP 5: Test dengan User Punya Role Tadi

1. **Logout** dari Super Admin
2. **Login** dengan user punya role yang baru di-atur (contoh: user dengan role "Admin")
3. **Lihat menu** - sekarang seharusnya muncul menu Karyawan dan Kendaraan!

---

## 📌 PERMISSION NAMING CONVENTION

Setiap permission memiliki format: **`modulename.action`**

### Modul & Action yang Tersedia:

**KARYAWAN:**
```
karyawan.index       (Lihat daftar karyawan)
karyawan.create      (Buat karyawan baru)
karyawan.show        (Lihat detail karyawan)
karyawan.edit        (Edit karyawan)
karyawan.delete      (Hapus karyawan)
```

**KENDARAAN:**
```
kendaraan.index      (Lihat daftar kendaraan)
kendaraan.create     (Buat kendaraan baru)
kendaraan.show       (Lihat detail kendaraan)
kendaraan.edit       (Edit kendaraan)
kendaraan.delete     (Hapus kendaraan)
kendaraan.status     (Ubah status kendaraan)
```

**PINJAMAN:**
```
pinjaman.index       (Lihat daftar pinjaman)
pinjaman.create      (Buat pinjaman baru)
pinjaman.show        (Lihat detail pinjaman)
pinjaman.edit        (Edit pinjaman)
pinjaman.delete      (Hapus pinjaman)
pinjaman.approve     (Approve pinjaman)
pinjaman.laporan     (Lihat laporan pinjaman)
pinjaman.export      (Export data pinjaman)
```

**59 MODUL LAINNYA:**
```
gedung, ruangan, barang, peralatan, santri, majlis-taklim,
masar, tukang, pengunjung, administrasi, dokumen, perawatan,
temuan, kpi-crew, tugas-luar, dan 44 modul lainnya!
```

---

## 🎯 PERMISSION GROUPS DI SIDEBAR

Sidebar mengecek permission dengan format:

```php
@if (auth()->user()->can('karyawan.index'))
    <!-- Tampilkan menu Karyawan -->
@endif
```

**Jadi MINIMAL user harus punya permission:**

- `modulename.index` → untuk LIHAT menu itu

**Contoh:**
- Untuk lihat menu **Karyawan** → butuh `karyawan.index`
- Untuk lihat menu **Kendaraan** → butuh `kendaraan.index`
- Untuk lihat menu **Pinjaman** → butuh `pinjaman.index`
- dst...

---

## 📊 PERMISSION GROUPS TERSEDIA

### FINANCIAL (10 Groups)
```
Pinjaman, Pinjaman Tukang, Dana Operasional, Laporan Keuangan,
Laporan Keuangan Karyawan, Transaksi Keuangan, Keuangan Tukang,
Keuangan Santri, Potongan Gaji, Realisasi Anggaran
```

### VEHICLE (7 Groups)
```
Kendaraan, Kendaraan Karyawan, Aktivitas Kendaraan,
Peminjaman Kendaraan, Service Kendaraan, Live Tracking,
Peminjaman Peralatan
```

### FACILITY & ASSET (5 Groups)
```
Gedung, Ruangan, Barang, Peralatan, Peminjaman Peralatan
```

### STUDENT (5 Groups)
```
Santri, Jadwal Santri, Absensi Santri, Izin Santri,
Presensi Istirahat
```

### RELIGIOUS EVENTS (7 Groups)
```
Majlis Taklim, Jamaah Majlis Taklim, Hadiah Majlis Taklim,
Jamaah Masar, Hadiah Masar, Distribusi Hadiah Masar,
Undian Umroh
```

### CONTRACTOR & VISITOR (5 Groups)
```
Tukang, Kehadiran Tukang, Pengunjung, Pengunjung Karyawan,
Jadwal Pengunjung
```

### MAINTENANCE & QUALITY (5 Groups)
```
Perawatan, Perawatan Karyawan, Temuan, KPI Crew, Tugas Luar
```

### ADMINISTRATION (8 Groups)
```
Administrasi, Dokumen, Administrasi Dokumen, Pengguna,
Departemen, Backup Data, Log Sistem, Setting Aplikasi
```

**TOTAL: 59 Permission Groups | 300+ Permissions**

---

## 💡 TIPS & TRICKS

### Tip 1: Gunakan Search Function
Di halaman Edit Permissions, gunakan **Search** untuk cari permission tertentu:

```
Search: "karyawan" → tampil hanya permission karyawan.*
Search: "index" → tampil hanya *.index permissions
```

### Tip 2: Gunakan CRUD Filter
Klik **[CRUD Only]** untuk hanya tampil 5 actions standar:
- index, create, show, edit, delete

### Tip 3: Select All Per Group
Untuk memberikan SEMUA action di satu modul:

```
Di group "Karyawan":
[Select All] Karyawan → semua action karyawan akan dichecklist
```

### Tip 4: Lihat Permission Count
Setiap group menunjukkan jumlah permissions:

```
Karyawan (5)       ← ada 5 permissions di group ini
Kendaraan (6)      ← ada 6 permissions di group ini
Pinjaman (8)       ← ada 8 permissions di group ini
```

---

## 🔒 SECURITY BEST PRACTICES

### ✅ DO:
- ✅ Beri hanya permission yang DIBUTUHKAN untuk role
- ✅ Pisahkan role berdasarkan fungsi (Admin, Manager, User, dll)
- ✅ Review permission secara berkala
- ✅ Test permission setiap kali ada perubahan role

### ❌ DON'T:
- ❌ Jangan beri semua permission ke user biasa
- ❌ Jangan gunakan super admin untuk user operasional
- ❌ Jangan share password super admin
- ❌ Jangan hapus role tanpa backup

---

## 🆘 TROUBLESHOOTING

### Problem: Menu masih tidak muncul setelah centang permission

**Solusi:**
1. **Logout & Login kembali** - Session user perlu di-refresh
2. **Clear browser cache** - Tekan Ctrl+F5
3. **Verify di Admin** - Lihat apakah permission sudah ter-assign
4. **Check permission format** - Pastikan format: `modulename.action`

### Problem: Centang permission tapi tidak bisa save

**Solusi:**
1. Refresh halaman
2. Scroll ke bawah lihat tombol SIMPAN
3. Pastikan tidak ada JavaScript error (F12 > Console)
4. Coba browser lain

### Problem: Ada permission yang error di database

**Solusi:**
Sudah di-cleanup oleh system. Database sudah CLEAN!

---

## 📞 RINGKASAN CEPAT

| Langkah | Apa yang Dikerjakan |
|---------|-------------------|
| 1 | Login Super Admin |
| 2 | Buka Settings > Role > Edit Permissions |
| 3 | Pilih role yang ingin di-atur |
| 4 | Centang permission yang diinginkan |
| 5 | Klik SIMPAN |
| 6 | Logout & Login dengan role itu |
| 7 | Menu akan muncul sesuai permission |

---

## ✨ SELESAI!

Sekarang Anda bisa:
- ✅ Atur siapa yang bisa akses menu apa
- ✅ Kelola permissions per role dengan mudah
- ✅ Lihat 59 permission groups untuk semua module
- ✅ Centang/buka permission yang diperlukan

**Tidak ada menu yang tersembunyi!** Semua permission bisa dikontrol dari halaman "Edit Permissions".

---

**Status: PRODUCTION READY** ✅
