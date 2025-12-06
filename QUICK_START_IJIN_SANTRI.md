# QUICK START - SISTEM IJIN SANTRI
## Panduan Cepat Penggunaan

---

## 🚀 AKSES MENU

1. Login sebagai **Super Admin**
2. Sidebar → **Manajemen Saung Santri** → **Ijin Santri**

---

## 📝 CARA MEMBUAT IJIN SANTRI

1. Klik tombol **"Buat Ijin Santri"**
2. Isi form:
   - Pilih Santri dari dropdown
   - Tanggal Ijin (default: hari ini)
   - Tanggal Rencana Kembali
   - Alasan Ijin
   - Catatan (opsional)
3. Klik **"Simpan Ijin"**
4. Status: **PENDING** ✅

---

## 📄 CARA DOWNLOAD SURAT IJIN

1. Di halaman list ijin santri
2. Cari ijin dengan status **PENDING**
3. Klik tombol **"Download PDF"** (merah)
4. PDF akan terdownload otomatis
5. Serahkan ke santri untuk TTD Ustadz

---

## ✅ VERIFIKASI TTD USTADZ

**Setelah surat di-TTD Ustadz (fisik):**

1. Buka list ijin santri
2. Klik tombol **"TTD Ustadz"** (kuning)
3. Konfirmasi: Klik **"Ya, Verifikasi"**
4. Status berubah: **TTD USTADZ** ✅

---

## 🚪 VERIFIKASI KEPULANGAN

**Setelah TTD Ustadz terverifikasi:**

1. Klik tombol **"Pulangkan"** (biru)
2. Konfirmasi: Klik **"Ya, Pulangkan"**
3. Status berubah: **DIPULANGKAN** ✅
4. Santri pulang dengan surat (untuk TTD Ortu)

---

## 🏠 VERIFIKASI KEMBALI

**Setelah santri kembali dengan surat ber-TTD Ortu:**

1. Klik tombol **"Sudah Kembali"** (hijau)
2. Isi form:
   - **Tanggal Kembali Aktual**
   - **Upload Foto Surat** (JPG/PNG, max 2MB)
3. Klik **"Ya, Verifikasi Kembali"**
4. Status berubah: **KEMBALI** ✅
5. **SELESAI** 🎉

---

## 🔍 LIHAT DETAIL IJIN

1. Klik tombol **"Detail"** (biru)
2. Akan tampil:
   - Timeline status lengkap
   - Data santri
   - Detail ijin
   - Riwayat verifikasi
   - Foto surat (jika sudah upload)

---

## 🗑️ HAPUS IJIN

**Hanya bisa hapus ijin dengan status PENDING:**

1. Klik tombol **"Hapus"** (merah)
2. Konfirmasi: OK
3. Ijin terhapus

---

## 🎯 STATUS IJIN

| Badge | Status | Artinya |
|-------|--------|---------|
| 🟡 | Menunggu TTD Ustadz | Baru dibuat, belum verifikasi |
| 🔵 | TTD Ustadz - Siap Pulang | Sudah TTD Ustadz, siap pulang |
| 🟣 | Sedang Pulang | Santri pulang, bawa surat |
| 🟢 | Sudah Kembali | Santri kembali, surat di-upload |

---

## ⚡ TOMBOL AKSI CEPAT

| Status | Tombol yang Muncul |
|--------|-------------------|
| **PENDING** | Detail • Download PDF • TTD Ustadz • Hapus |
| **TTD_USTADZ** | Detail • Pulangkan |
| **DIPULANGKAN** | Detail • Sudah Kembali |
| **KEMBALI** | Detail |

---

## 📋 CHECKLIST PROSES IJIN

### ✅ **TAHAP 1: Buat Ijin**
- [ ] Admin input data ijin
- [ ] Status: PENDING

### ✅ **TAHAP 2: Download & TTD Ustadz**
- [ ] Download PDF surat
- [ ] Santri minta TTD Ustadz (fisik)
- [ ] Admin verifikasi TTD Ustadz
- [ ] Status: TTD_USTADZ

### ✅ **TAHAP 3: Kepulangan**
- [ ] Admin verifikasi kepulangan
- [ ] Status: DIPULANGKAN
- [ ] Santri pulang dengan surat

### ✅ **TAHAP 4: Kembali**
- [ ] Santri kembali dengan surat ber-TTD Ortu
- [ ] Admin upload foto surat
- [ ] Admin verifikasi kembali
- [ ] Status: KEMBALI
- [ ] ✅ **SELESAI**

---

## 💡 TIPS & TRIK

### **Tip 1: Nomor Surat Otomatis**
Nomor surat dibuat otomatis saat simpan ijin:
```
Format: 001/IJIN-SANTRI/11/2025
```

### **Tip 2: Timeline Visual**
Di halaman detail, ada timeline visual yang menunjukkan:
- Proses mana yang sudah selesai
- Tanggal & waktu setiap tahap
- Siapa yang verifikasi

### **Tip 3: Foto Surat Wajib**
Saat verifikasi kembali, **WAJIB** upload foto surat.
- Format: JPG atau PNG
- Maksimal: 2MB
- Kualitas: Jelas & terbaca

### **Tip 4: Tidak Bisa Edit**
Ijin yang sudah dibuat **TIDAK BISA DIEDIT**.
Hanya bisa hapus jika masih status PENDING.

---

## ⚠️ HAL PENTING

1. **Urutan Proses Harus Berurutan**
   - Tidak bisa skip tahap
   - Status flow: PENDING → TTD_USTADZ → DIPULANGKAN → KEMBALI

2. **Download PDF Berkali-kali**
   - Bisa download PDF berkali-kali
   - PDF selalu update dengan data terbaru

3. **Tanggal Harus Logis**
   - Tanggal kembali harus >= tanggal ijin
   - Validasi otomatis di form

4. **Backup Foto Surat**
   - Foto tersimpan di: `storage/app/public/ijin_santri/`
   - Jangan hapus folder ini!

---

## 🆘 TROUBLESHOOTING

### **Masalah: Tidak bisa upload foto**
- Pastikan format JPG/PNG
- Cek ukuran file < 2MB
- Pastikan koneksi internet stabil

### **Masalah: PDF tidak terdownload**
- Cek browser allow download
- Refresh halaman
- Coba browser lain

### **Masalah: Menu tidak muncul**
- Pastikan login sebagai Super Admin
- Check role user
- Refresh halaman / clear cache

---

## 📞 BUTUH BANTUAN?

Baca dokumentasi lengkap di:
```
DOKUMENTASI_IJIN_SANTRI.md
```

---

**Update Terakhir:** 8 November 2025  
**Versi:** 1.0
