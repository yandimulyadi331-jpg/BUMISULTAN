# QUICK START - PELANGGARAN SANTRI

## 🚀 Setup Cepat (5 Menit)

### 1. Jalankan Migration & Seeder
```bash
cd c:\Users\user\Desktop\bumisultansuperapp_v2\presensigpsv2-main
php artisan migrate
php artisan db:seed --class=PelanggaranSantriPermissionSeeder
```

### 2. Pastikan Storage Link
```bash
php artisan storage:link
```

### 3. Akses Menu
1. Login sebagai **Super Admin**
2. Buka menu **Manajemen Saung Santri** > **Pelanggaran Santri**
3. Selesai! ✅

## ⚡ Penggunaan Cepat

### Tambah Pelanggaran (1 Menit)
1. Klik **"Tambah Pelanggaran"**
2. Pilih **Santri**
3. Upload **Foto** (opsional)
4. Tulis **Keterangan** (contoh: "Merokok di asrama")
5. Set **Point** (default: 1)
6. **Simpan** ✅

### Lihat Laporan
1. Klik **"Laporan"** di halaman pelanggaran
2. Lihat rekap santri dengan **status warna**:
   - 🟢 **Hijau** = Ringan (<35x)
   - 🟡 **Kuning** = Sedang (35-74x)
   - 🔴 **Merah** = Berat (≥75x)
3. Export **PDF** atau **Excel** jika perlu

## 📊 Status Pelanggaran

```
< 35x    → 🟢 RINGAN  (Hijau)
35-74x   → 🟡 SEDANG  (Kuning)
≥ 75x    → 🔴 BERAT   (Merah)
```

## 🎯 Routes

```
/pelanggaran-santri              → List semua pelanggaran
/pelanggaran-santri/create       → Form tambah
/pelanggaran-santri/laporan/index → Laporan rekap
```

## ✅ Checklist

- [ ] Migration berhasil
- [ ] Permission berhasil di-seed
- [ ] Menu muncul di sidebar
- [ ] Bisa tambah pelanggaran
- [ ] Foto bisa diupload
- [ ] Status warna muncul dengan benar
- [ ] Export PDF & Excel berfungsi

## 🐛 Quick Fix

### Foto tidak muncul?
```bash
php artisan storage:link
```

### Permission error?
```bash
php artisan db:seed --class=PelanggaranSantriPermissionSeeder
```

### Clear cache jika ada masalah:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📱 Fitur Utama

✅ Upload foto pelanggaran  
✅ Auto-calculate total pelanggaran  
✅ Status warna otomatis (hijau/kuning/merah)  
✅ Filter by santri & tanggal  
✅ Export PDF & Excel  
✅ Real-time info pelanggaran  
✅ Soft delete (bisa restore)  

## 🎨 UI Preview

**List Pelanggaran:**
```
╔═══════════════════════════════════════════════╗
║ Foto | Santri | Tanggal | Keterangan | Status ║
║------|---------|---------|------------|--------║
║ 📷   | Ahmad   | 08/11/25| Merokok   | 🔴 Berat║
║ 📷   | Budi    | 07/11/25| Telat     | 🟡 Sedang║
╚═══════════════════════════════════════════════╝
```

**Laporan:**
```
╔══════════════════════════════════════╗
║ Statistik                           ║
║-------------------------------------|║
║ Total Santri Bermasalah: 25        ║
║ Status Berat (🔴): 5               ║
║ Status Sedang (🟡): 10             ║
║ Status Ringan (🟢): 10             ║
╚══════════════════════════════════════╝
```

## 💡 Tips

1. **Upload foto** untuk bukti yang kuat
2. Gunakan **point system** untuk membedakan tingkat pelanggaran
3. Cek **laporan** secara berkala untuk monitor santri
4. **Export PDF** untuk rapat evaluasi
5. Status warna memudahkan identifikasi cepat

## 🔗 Link Terkait

- Data Santri: `/santri`
- Ijin Santri: `/ijin-santri`
- Keuangan Santri: `/keuangan-santri`

---

**Ready to use! 🎉**

Dokumentasi lengkap: `DOKUMENTASI_PELANGGARAN_SANTRI.md`
