# ⚡ QUICK REFERENCE: Import Bulanan

## 🎯 Kapan Pakai Import Bulanan?

✅ **Pakai** jika:
- Upload data **1 bulan penuh** sekaligus
- Tidak tahu tanggal pasti setiap transaksi
- Mau **cepat** dan **praktis**
- Data dari laporan bulanan (tidak ada tanggal detail)

❌ **Jangan pakai** jika:
- Data sudah ada tanggal spesifik → Pakai **Import Reguler**
- Transaksi lintas bulan → Pisah per bulan

---

## 🚀 Quick Steps (3 Langkah!)

### 1️⃣ Download Template
```
Dashboard → Template ▼ → Template Bulanan
```

### 2️⃣ Isi Excel (3 Kolom Saja!)
```
Keterangan          | Dana Masuk | Dana Keluar
Khidmat Ramadhan    |            | 350000
BBM RMX             |            | 50000
Tambahan dana       | 2000000    |
```

### 3️⃣ Upload
```
Import Bulanan → Pilih Bulan/Tahun → Upload → Done! ✅
```

---

## 📋 Apa yang Auto-Generate?

| Item | Sebelum | Sesudah Import |
|------|---------|----------------|
| Tanggal | - | 1 Jan, 2 Jan, 3 Jan... |
| Jam | - | 08:00, 08:45, 09:30... |
| Nomor | - | BS/001, BS/002, BS/003 |

---

## 🔢 Format Nomor Transaksi

```
BS/001 = Bumi Sultan nomor 1
BS/002 = Bumi Sultan nomor 2
BS/100 = Bumi Sultan nomor 100
```

**Simpel, mudah diucapkan!** 🎉

---

## ⚠️ Aturan Penting

1. **JANGAN** isi kolom tanggal (sistem otomatis!)
2. Isi **Dana Masuk** ATAU **Dana Keluar** (salah satu)
3. Setiap **5 transaksi** = ganti hari otomatis
4. Max file: **5 MB**
5. Format: `.xlsx`, `.xls`, `.csv`

---

## 🆚 Import Reguler vs Bulanan

| | Reguler | Bulanan |
|-|---------|---------|
| **Kolom Excel** | 4 (ada tanggal) | 3 (tanpa tanggal) |
| **Tanggal** | Manual | Auto ✨ |
| **Nomor** | Auto panjang | BS/XXX 👍 |
| **Cocok untuk** | Data historis spesifik | Laporan bulanan |

---

## 💡 Tips

✅ Test dulu dengan file kecil (5-10 baris)  
✅ Backup sebelum import besar  
✅ Cek preview hasil import  
✅ Jangan import bulan yang sama 2x  

---

## 🐛 Troubleshooting Cepat

**Q: Error "Baris X: Harus isi Dana Masuk atau Dana Keluar"**  
A: Pastikan setiap baris punya nominal (Masuk ATAU Keluar)

**Q: Nomor transaksi tidak muncul**  
A: Refresh page atau clear cache

**Q: Tanggal tidak sesuai**  
A: Cek pilihan bulan/tahun di form upload

---

Dokumentasi lengkap: **DOKUMENTASI_IMPORT_BULANAN.md**
