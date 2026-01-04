# 🎯 QUICK REFERENCE: Akumulasi Saldo Dana Operasional

## 📋 CHEAT SHEET - Print & Tempel di Kantor!

---

### ✅ ATURAN EMAS (Harus Diingat!)

```
┌────────────────────────────────────────────────────────────┐
│  1. Saldo Akhir Hari Ini = Saldo Awal Hari Besok          │
│                                                            │
│  2. Saldo POSITIF → Masuk ke Kolom DANA MASUK             │
│                                                            │
│  3. Saldo NEGATIF → Masuk ke Kolom DANA KELUAR            │
│                                                            │
│  4. Sistem Auto-Cascade (Edit 1 hari → Update semua)      │
│                                                            │
│  5. JANGAN Edit Manual Database! (Biar sistem hitung)     │
└────────────────────────────────────────────────────────────┘
```

---

### 🧮 RUMUS CEPAT

```
Saldo Akhir = Saldo Awal + Dana Masuk - Dana Keluar

Contoh:
  Saldo Awal: Rp 100.000
  + Dana Masuk: Rp 500.000
  - Dana Keluar: Rp 200.000
  ─────────────────────────
  = Saldo Akhir: Rp 400.000 ← Jadi saldo awal besok
```

---

### 🔄 CONTOH KASUS

#### ✅ KASUS 1: Saldo Positif (Normal)
```
SENIN:
  Saldo Awal: Rp 1.000.000
  Masuk: Rp 500.000
  Keluar: Rp 300.000
  ─────────────────────────
  Dana Masuk Display: Rp 1.000.000 + Rp 500.000 = Rp 1.500.000 ✅
  Dana Keluar Display: Rp 300.000 ✅
  Saldo Akhir: Rp 1.200.000

SELASA:
  Saldo Awal: Rp 1.200.000 ← Dari Senin
  Masuk: Rp 0
  Keluar: Rp 200.000
  ─────────────────────────
  Dana Masuk Display: Rp 1.200.000 + Rp 0 = Rp 1.200.000 ✅
  Dana Keluar Display: Rp 200.000 ✅
  Saldo Akhir: Rp 1.000.000
```

#### ⚠️ KASUS 2: Saldo Negatif (Minus)
```
RABU:
  Saldo Awal: Rp 500.000
  Masuk: Rp 0
  Keluar: Rp 700.000
  ─────────────────────────
  Dana Masuk Display: Rp 500.000 ✅
  Dana Keluar Display: Rp 700.000 ✅
  Saldo Akhir: -Rp 200.000 ⚠️

KAMIS (Saldo kemarin MINUS!):
  Saldo Awal: -Rp 200.000 ← Dari Rabu (NEGATIF)
  Masuk: Rp 1.000.000
  Keluar: Rp 100.000
  ─────────────────────────
  Dana Masuk Display: Rp 1.000.000 ✅ (tidak include negatif)
  Dana Keluar Display: Rp 200.000 + Rp 100.000 = Rp 300.000 ✅
  Saldo Akhir: -Rp 200.000 + Rp 1.000.000 - Rp 100.000 = Rp 700.000
```

---

### 🛠️ TROUBLESHOOTING

#### ❓ Saldo Tidak Akurat?
```
✅ SOLUSI:
1. Backup database dulu: php artisan backup:run
2. Jalankan: php recalculate_all_saldo.php
3. Refresh browser (Ctrl+F5)
4. Cek lagi - seharusnya sudah benar!
```

#### ❓ Saldo Hari Besok Tidak Update Otomatis?
```
✅ CEK:
1. Apakah transaksi sudah di-save? (klik Simpan)
2. Cek console browser (F12) ada error?
3. Cek storage/logs/laravel.log
4. Kalau masih error, hubungi IT
```

#### ❓ Ada Hari yang Hilang/Gap?
```
✅ NORMAL:
- Sistem hanya buat record saat ada transaksi
- Hari libur/weekend tanpa transaksi = tidak ada record
- Saldo tetap ter-carry ke hari berikutnya yang ada transaksi
```

---

### 📊 INTERPRETASI LAPORAN

```
┌──────────────────────────────────────────────────────────┐
│ CARA BACA TABEL:                                         │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ BARIS "SALDO AWAL":                                      │
│   - Warna biru                                           │
│   - Keterangan: "Sisa saldo sebelumnya"                  │
│   - Ini carry-over dari hari kemarin                     │
│                                                          │
│ KOLOM "DANA MASUK":                                      │
│   - Angka hijau (positif)                                │
│   - Include saldo awal jika positif                      │
│   - Plus transaksi masuk hari ini                        │
│                                                          │
│ KOLOM "DANA KELUAR":                                     │
│   - Angka merah (negatif)                                │
│   - Include saldo awal jika negatif (kekurangan)         │
│   - Plus transaksi keluar hari ini                       │
│                                                          │
│ KOLOM "SALDO":                                           │
│   - Saldo running per baris                              │
│   - Update setiap transaksi                              │
│   - Baris terakhir = saldo akhir hari                    │
│                                                          │
│ BARIS "SUBTOTAL":                                        │
│   - Warna kuning                                         │
│   - Ringkasan per hari                                   │
│   - Total Masuk, Total Keluar, Saldo Akhir               │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

---

### 🚨 YANG TIDAK BOLEH DILAKUKAN

```
❌ JANGAN:
1. Edit langsung di database (phpMyAdmin/MySQL)
2. Ubah field saldo_awal, dana_masuk, total_realisasi, saldo_akhir
3. Hapus record saldo_harian_operasional tanpa sepengetahuan IT
4. Import Excel tanpa cek format dulu

✅ HARUS:
1. Pakai interface aplikasi untuk tambah/edit/hapus transaksi
2. Backup dulu sebelum import Excel
3. Test di development dulu sebelum production
4. Konsultasi IT jika ragu
```

---

### 📞 KONTAK SUPPORT

```
┌────────────────────────────────────────────────────────────┐
│ Jika Ada Masalah:                                          │
├────────────────────────────────────────────────────────────┤
│                                                            │
│ 1. Screenshoot error/masalah                               │
│ 2. Catat tanggal & waktu kejadian                          │
│ 3. Catat transaksi yang bermasalah (nomor transaksi)       │
│ 4. Hubungi IT Support                                      │
│                                                            │
│ ⚠️ JANGAN PANIK & JANGAN EDIT MANUAL! ⚠️                   │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

---

### 📝 CHECKLIST HARIAN BENDAHARA

```
□ Cek saldo awal = saldo akhir kemarin
□ Input semua transaksi hari ini
□ Upload foto bukti (jika ada)
□ Set kategori transaksi
□ Cek subtotal sesuai dengan perhitungan manual
□ Cek saldo akhir masuk akal (tidak tiba-tiba loncat)
□ Backup data (minimal 1x seminggu)
```

---

### 🎯 TIPS & TRICKS

```
💡 TIP 1: Foto Bukti
   - Foto pakai HP → Langsung upload dari HP
   - Aplikasi bisa diakses dari HP (responsive)

💡 TIP 2: Kategori Otomatis
   - Ketik keterangan lengkap (misal: "BBM Motor Dinas")
   - Sistem auto-detect kategori (AI)
   - Bisa diubah manual jika salah

💡 TIP 3: Filter Cepat
   - Filter per bulan: paling sering dipakai
   - Filter per minggu: untuk laporan mingguan
   - Filter range: untuk laporan custom

💡 TIP 4: Download PDF
   - Klik "Download PDF" untuk cetak laporan
   - Bisa filter dulu sebelum download
   - PDF sudah include logo & kop surat

💡 TIP 5: Nomor Transaksi
   - Auto-generate, tidak perlu input manual
   - Format: BS-YYYYMMDD-XXX
   - Contoh: BS-20260104-001
```

---

### 📊 CONTOH LAPORAN LENGKAP

```
MANAJEMEN KEUANGAN - JANUARI 2026
════════════════════════════════════════════════════════════

RINGKASAN BULAN:
  Total Dana Masuk    : Rp 50.000.000
  Total Dana Keluar   : Rp 45.000.000
  Saldo Awal Bulan    : Rp 10.000.000
  Saldo Akhir Bulan   : Rp 15.000.000
  ────────────────────────────────────
  Selisih (Surplus)   : Rp 5.000.000 ✅

RINCIAN HARIAN:
┌──────┬───────────┬─────────────┬──────────────┬─────────────┐
│ Tgl  │ Dana Masuk│ Dana Keluar │ Saldo Akhir  │  Status     │
├──────┼───────────┼─────────────┼──────────────┼─────────────┤
│ 01/1 │ 1.500.000 │   800.000   │ 10.700.000   │ ✅ Balance  │
│ 02/1 │ 2.000.000 │ 1.500.000   │ 11.200.000   │ ✅ Balance  │
│ 03/1 │   500.000 │ 2.000.000   │  9.700.000   │ ✅ Balance  │
│ ...  │    ...    │     ...     │     ...      │     ...     │
└──────┴───────────┴─────────────┴──────────────┴─────────────┘

✅ Semua saldo akurat & ter-akumulasi dengan benar!
```

---

**💾 SIMPAN FILE INI!**  
Print & tempel di dekat komputer bendahara/keuangan

**Terakhir Update:** 4 Januari 2026  
**Versi:** 1.0  
**Status:** ✅ PRODUCTION READY
