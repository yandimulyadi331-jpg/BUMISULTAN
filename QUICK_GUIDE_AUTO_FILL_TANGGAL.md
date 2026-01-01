# ⚡ Quick Guide: Auto-Fill Tanggal Import Excel

## 🎯 Inti Fitur

**Tanggal kosong otomatis terisi dengan tanggal terakhir di atasnya!**

## 📝 Cara Pakai (3 Langkah)

### 1️⃣ Isi Excel Seperti Ini:
```
2025-01-02 | Pembelian ATK    | | 150000
           | Bensin motor     | | 50000  ← Tanggal kosong
           | Bayar parkir     | | 5000   ← Tanggal kosong
2025-01-03 | Transfer kas     | 5000000 |
           | Bayar listrik    | | 250000 ← Tanggal kosong
```

### 2️⃣ Upload ke Sistem
- Menu Dana Operasional → Import Excel

### 3️⃣ Hasil Otomatis:
```
2025-01-02 | Pembelian ATK    | | 150000
2025-01-02 | Bensin motor     | | 50000  ← Otomatis terisi!
2025-01-02 | Bayar parkir     | | 5000   ← Otomatis terisi!
2025-01-03 | Transfer kas     | 5000000 |
2025-01-03 | Bayar listrik    | | 250000 ← Otomatis terisi!
```

## ✅ Aturan Penting

1. **Tanggal pertama wajib diisi** (baris pertama harus ada tanggal)
2. **Kosongkan tanggal** untuk transaksi dengan tanggal yang sama
3. **Isi tanggal baru** hanya saat pindah ke tanggal berikutnya
4. **Format tanggal:** YYYY-MM-DD (contoh: 2025-01-02)

## 💡 Tips

- Kelompokkan transaksi berdasarkan tanggal untuk input lebih cepat
- Gunakan baris kosong sebagai pemisah visual (optional)
- Template Excel sudah include contoh lengkap

## 📊 Hemat Waktu

**Contoh:** 30 transaksi dengan 5 tanggal berbeda
- ❌ Cara Lama: Ketik 30 tanggal = **2.5 menit**
- ✅ Cara Baru: Ketik 5 tanggal = **25 detik**
- 🎉 **Hemat: 2+ menit (83% lebih cepat!)**

## 📚 Dokumentasi Lengkap

- [FITUR_AUTO_FILL_TANGGAL_IMPORT_EXCEL.md](FITUR_AUTO_FILL_TANGGAL_IMPORT_EXCEL.md) - Panduan lengkap
- [CONTOH_IMPORT_DENGAN_AUTO_FILL_TANGGAL.md](CONTOH_IMPORT_DENGAN_AUTO_FILL_TANGGAL.md) - Berbagai contoh kasus

---

**Update:** 1 Januari 2026 | **Versi:** 1.0.0
