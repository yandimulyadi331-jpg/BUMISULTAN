# 🚀 QUICK START - STOK UKURAN HADIAH

## ⚡ CARA PAKAI (3 LANGKAH)

### 1️⃣ TAMBAH HADIAH DENGAN UKURAN
```
Menu: Majlis Ta'lim → Tab Hadiah → Tambah Hadiah

1. Isi data hadiah (nama, jenis, stok awal)
2. AKTIFKAN: Toggle "Tracking stok per ukuran" ✅
3. Pilih tipe ukuran:
   - Huruf → untuk baju/sarung/gamis (S, M, L, XL, XXL)
   - Angka → untuk sepatu/peci (38-44)
   - Custom → untuk ukuran khusus
4. Input jumlah per ukuran
5. PENTING: Total ukuran harus = Stok Awal
6. Simpan
```

**Contoh:**
```
Nama: Sarung Premium
Jenis: Sarung
Stok Awal: 50

Tracking Ukuran: ✅ AKTIF
Tipe: Huruf

M: 15
L: 25
XL: 10
Total: 50 ✅ (sama dengan stok awal)
```

---

### 2️⃣ DISTRIBUSI DENGAN PILIH UKURAN
```
Menu: Majlis Ta'lim → Tab Distribusi → Form Distribusi

1. Pilih Hadiah → Dropdown ukuran muncul otomatis
2. Pilih Ukuran → Stok tersedia ditampilkan
3. Input Jumlah (max sesuai stok ukuran)
4. Pilih Jamaah penerima
5. Isi penerima & petugas
6. Simpan

✅ Stok ukuran berkurang otomatis!
```

**Contoh:**
```
Hadiah: Sarung Premium
Ukuran: L (Stok: 25) ← dropdown
Jumlah: 2
Jamaah: Ahmad bin Ali

HASIL:
- Ukuran L: 25 → 23 ✅
- Stok tersedia: 50 → 48 ✅
- Record distribusi tersimpan dengan ukuran "L"
```

---

### 3️⃣ LIHAT LAPORAN
```
Menu: Majlis Ta'lim → Tab Laporan

A. LAPORAN STOK PER UKURAN
   → Lihat stok detail setiap ukuran
   → Filter by jenis hadiah
   → Print / Export

B. LAPORAN REKAP DISTRIBUSI
   → Rekap lengkap distribusi
   → Filter tanggal, hadiah, ukuran
   → Export Excel (XLSX)
   → Statistik per ukuran
```

---

## 🎯 TIPS & TRICKS

### ✅ DO's
- Aktifkan tracking ukuran saat create hadiah
- Total ukuran HARUS sama dengan stok awal
- Pilih tipe ukuran sesuai jenis hadiah
- Check laporan stok sebelum distribusi

### ❌ DON'Ts
- Jangan skip validasi total ukuran
- Jangan distribusi tanpa pilih ukuran (kalau hadiah punya tracking)
- Jangan lupa isi penerima saat distribusi

---

## 🔥 FITUR KEREN

### 1. AUTO-SUGGEST TIPE UKURAN
```
Sarung/Gamis/Mukena → Saran: Huruf (S/M/L/XL)
Peci/Sepatu → Saran: Angka (38-44)
Lainnya → Custom
```

### 2. REAL-TIME VALIDATION
```javascript
Submit Form → Check Total
Total Ukuran ≠ Stok Awal → ❌ ERROR (Swal alert)
Total Ukuran = Stok Awal → ✅ SUCCESS
```

### 3. DYNAMIC DROPDOWN
```
Hadiah tanpa tracking → Dropdown ukuran HIDDEN
Hadiah dengan tracking → Dropdown ukuran SHOW
Only ukuran dengan stok > 0 → Displayed
```

### 4. SMART STOCK REDUCTION
```
Distribusi ukuran L (2 pcs):
- stok_ukuran["L"] -= 2
- stok_tersedia -= 2
- distribusi.ukuran = "L"

Hapus distribusi:
- stok_ukuran["L"] += 2 (RESTORED!)
- stok_tersedia += 2
```

### 5. VISUAL INDICATORS
```
Stok > 10 → 🟢 Badge Hijau
Stok 5-10 → 🟡 Badge Kuning
Stok < 5 → 🔴 Badge Merah
```

---

## 📸 SCREENSHOT GUIDE

### Form Input Stok Ukuran
```
┌─────────────────────────────────────┐
│ ☑ Aktifkan tracking stok per ukuran│
│                                     │
│ Tipe Ukuran: [Huruf ▼]             │
│                                     │
│ S  [  5] M  [ 10] L  [ 15]         │
│ XL [ 12] XXL[  8] XXXL[ 0]         │
│                                     │
│ ℹ Total stok ukuran harus sama     │
│   dengan Stok Awal                 │
└─────────────────────────────────────┘
```

### Dropdown Ukuran (Distribusi)
```
┌──────────────────────────────┐
│ Ukuran: [Pilih Ukuran ▼]    │
│         ├─ M (Stok: 15)      │
│         ├─ L (Stok: 25) ✓    │
│         └─ XL (Stok: 10)     │
│                               │
│ Stok ukuran ini: 25          │
└──────────────────────────────┘
```

### Laporan Stok Ukuran
```
┌─────────────────────────────────────────┐
│ No │ Hadiah        │ Ukuran │ Stok    │
├────┼───────────────┼────────┼─────────┤
│ 1  │ Sarung A      │ M      │ 🟢 15   │
│    │ SR-001-001    │ L      │ 🟢 25   │
│    │               │ XL     │ 🟡 8    │
├────┼───────────────┼────────┼─────────┤
│ 2  │ Peci Hitam    │ 38     │ 🔴 3    │
│    │ PC-002-002    │ 40     │ 🟢 12   │
└────┴───────────────┴────────┴─────────┘

Total: 63 pcs
```

---

## 🆘 TROUBLESHOOTING CEPAT

### ❓ "Dropdown ukuran tidak muncul"
**Solusi:** Edit hadiah → Aktifkan tracking ukuran → Simpan

### ❓ "Validasi: Total ukuran harus sama dengan stok awal"
**Solusi:** Hitung ulang total input ukuran = stok awal

### ❓ "Ukuran yang dipilih tidak tersedia"
**Solusi:** Ukuran habis stoknya, pilih ukuran lain atau tambah stok

### ❓ "Stok tidak berkurang"
**Solusi:** Check apakah hadiah punya tracking ukuran & ukuran terpilih

---

## 🎓 CONTOH KASUS LENGKAP

### SCENARIO: Distribusi Sarung untuk 3 Jamaah

**Initial State:**
```
Hadiah: Sarung Premium
Stok Awal: 50
Ukuran:
  M: 15
  L: 25
  XL: 10
```

**Action 1:** Distribusi ke Ahmad (ukuran L, 1 pcs)
```
Before: L = 25
After: L = 24 ✅
Total: 50 → 49
```

**Action 2:** Distribusi ke Fatimah (ukuran M, 2 pcs)
```
Before: M = 15
After: M = 13 ✅
Total: 49 → 47
```

**Action 3:** Distribusi ke Umar (ukuran XL, 3 pcs)
```
Before: XL = 10
After: XL = 7 ✅
Total: 47 → 44
```

**Final State:**
```
Ukuran:
  M: 13 (dari 15)
  L: 24 (dari 25)
  XL: 7 (dari 10)
Total: 44 (dari 50)

Distribusi: 3 transaksi tersimpan dengan ukuran
```

**Laporan Rekap Per Ukuran:**
```
M → 1 transaksi, 2 pcs
L → 1 transaksi, 1 pcs
XL → 1 transaksi, 3 pcs
Total → 3 transaksi, 6 pcs
```

---

## 📞 BANTUAN

Jika ada pertanyaan atau butuh bantuan:
1. Baca dokumentasi lengkap: `DOKUMENTASI_STOK_UKURAN.md`
2. Check error log: `storage/logs/laravel.log`
3. Test di development environment dulu

---

**Selamat menggunakan fitur Stok Ukuran! 🎉**
**Sistem sudah terintegrasi penuh dan siap digunakan.**
