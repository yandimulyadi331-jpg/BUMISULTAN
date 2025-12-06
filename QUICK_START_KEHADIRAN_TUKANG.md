# QUICK START - KEHADIRAN TUKANG

## 🚀 Instalasi (Sudah Selesai!)

✅ Migration tabel sudah dijalankan
✅ Permissions sudah di-setup
✅ Menu sudah muncul di sidebar

---

## 📍 Menu Lokasi

```
Sidebar → Manajemen Tukang
   ├── Data Tukang
   ├── Kehadiran Tukang  ← Absensi harian
   └── Rekap Kehadiran   ← Laporan & gaji
```

---

## ⚡ Cara Cepat Absen (Setiap Sore)

### Step 1: Buka Kehadiran Tukang
**Menu:** Manajemen Tukang → Kehadiran Tukang

### Step 2: Pilih Tanggal
Default: Hari ini (bisa diubah)

### Step 3: Absen Per Tukang (1 Klik!)
**Klik tombol status** untuk toggle:
- 🔴 Tidak Hadir → 🟢 Hadir → 🟡 Setengah Hari

**Toggle lembur** (jika ada):
- Switch ON = Ada lembur (+100% tarif)
- Switch OFF = Tidak lembur

### Step 4: Selesai!
- Upah otomatis terhitung ✅
- Data tersimpan otomatis ✅

---

## 💰 Sistem Gaji Otomatis

| Status | Jam Kerja | Upah Harian | Lembur (Opsional) |
|--------|-----------|-------------|-------------------|
| 🟢 Hadir | 8 jam | 100% tarif | +100% tarif |
| 🟡 Setengah Hari | 4 jam | 50% tarif | +100% tarif |
| 🔴 Tidak Hadir | 0 jam | 0 | Tidak bisa |

**Contoh:**
- Tarif: Rp 150.000/hari
- Status: Hadir + Lembur
- **Total: Rp 300.000** (150rb + 150rb)

---

## 📊 Lihat Rekap & Gaji

### Menu: Rekap Kehadiran

1. **Pilih Bulan & Tahun**
2. **Lihat Summary:**
   - Total hari hadir, setengah hari, tidak hadir
   - Total hari lembur
   - **TOTAL GAJI YANG HARUS DIBAYAR** ✨
3. **Klik icon mata** untuk detail per tukang

---

## 🎯 Fitur Utama

✅ **Toggle 1 Klik** - Ganti status dengan 1 klik
✅ **Auto Calculate** - Gaji otomatis terhitung
✅ **Lembur** - Toggle ON/OFF untuk lembur
✅ **Setengah Hari** - Upah 50% untuk kerja setengah hari
✅ **Hari Jumat Libur** - Otomatis skip Jumat
✅ **Real-time Update** - Upah update langsung
✅ **Rekap Bulanan** - Laporan lengkap per bulan
✅ **Detail Per Tukang** - Rincian harian per tukang

---

## 📅 Hari Jumat LIBUR

Jika buka halaman di hari Jumat:
- Akan muncul peringatan "Hari Jumat (Libur)"
- Tidak ada absensi
- Tidak masuk perhitungan gaji

---

## 🔢 Contoh Penggunaan

### Skenario: Absen 3 Tukang

**Tukang A** (Tarif: Rp 150.000)
- Klik tombol → Hadir (hijau)
- Toggle lembur → ON
- **Upah: Rp 300.000** ✅

**Tukang B** (Tarif: Rp 120.000)
- Klik tombol → Setengah Hari (kuning)
- Toggle lembur → OFF
- **Upah: Rp 60.000** ✅

**Tukang C** (Tarif: Rp 100.000)
- Klik tombol → Hadir (hijau)
- Toggle lembur → OFF
- **Upah: Rp 100.000** ✅

**Total Upah Hari Ini: Rp 460.000**

---

## 🎨 Warna Status

| Warna | Status | Upah |
|-------|--------|------|
| 🔴 **Abu-abu** | Tidak Hadir | 0% |
| 🟢 **Hijau** | Hadir (Full) | 100% |
| 🟡 **Kuning** | Setengah Hari | 50% |

---

## 📝 Tips

💡 **Absen setiap sore** untuk update kehadiran hari ini
💡 **Klik berkali-kali** untuk cycle status
💡 **Lembur hanya bisa aktif** jika status HADIR atau SETENGAH HARI
💡 **Lihat rekap** akhir bulan untuk total gaji
💡 **Detail per tukang** untuk rincian lengkap

---

## 🔐 Permissions

Untuk role selain super admin, assign:
- `kehadiran-tukang.index` - Akses absensi
- `kehadiran-tukang.absen` - Absen tukang
- `kehadiran-tukang.rekap` - Lihat rekap

---

## ⚡ Commands

```bash
# Clear cache (jika ada masalah)
php artisan optimize:clear

# Cek routes
php artisan route:list --name=kehadiran-tukang
```

---

**✨ SIAP DIGUNAKAN!**

Logout → Login → Menu "Kehadiran Tukang" sudah muncul! 🚀
