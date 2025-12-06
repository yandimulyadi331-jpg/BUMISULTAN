# FITUR LEMBUR CASH (DIBAYAR HARI ITU JUGA) - DOKUMENTASI

## 🎯 Konsep Sistem Pembayaran

### Gaji Harian (Upah Pokok)
- **Dibayar:** Setiap hari **Kamis** (mingguan)
- **Termasuk:** Upah harian untuk hari Senin-Kamis

### Lembur Normal
- **Dibayar:** Hari **Kamis** bersamaan dengan gaji mingguan
- **Jenis:** Lembur Full (8 jam) atau Lembur Setengah (4 jam)

### Lembur Cash (FITUR BARU ✨)
- **Dibayar:** **Hari itu juga** (cash langsung)
- **Jenis:** Lembur Full Cash atau Lembur Setengah Cash
- **Kegunaan:** Untuk lembur mendesak yang perlu dibayar segera

---

## 🚀 Pilihan Lembur (5 Opsi)

Klik tombol **Lembur** untuk cycle melalui 5 pilihan:

| No | Pilihan | Warna | Bayar | Bonus | Ikon |
|----|---------|-------|-------|-------|------|
| 1️⃣ | **Tidak Lembur** | Abu-abu | - | 0% | ➖ |
| 2️⃣ | **Lembur Full (Kamis)** | Merah | Kamis | +100% | 🕐 |
| 3️⃣ | **Lembur 1/2 (Kamis)** | Orange | Kamis | +50% | 🕒 |
| 4️⃣ | **Lembur Full CASH** | Hijau | Hari Ini | +100% | 💰 |
| 5️⃣ | **Lembur 1/2 CASH** | Biru | Hari Ini | +50% | 💰 |

---

## 💰 Contoh Perhitungan (Tarif Rp 150.000/hari)

### Skenario 1: Hadir Biasa (Tanpa Lembur)
```
Upah Harian: Rp 150.000
Lembur: Tidak
Total: Rp 150.000
Dibayar: Kamis
```

### Skenario 2: Hadir + Lembur Full (Kamis)
```
Upah Harian: Rp 150.000
Lembur Full: Rp 150.000 (100%)
Total: Rp 300.000
Dibayar: Semua dibayar Kamis
```

### Skenario 3: Hadir + Lembur Setengah (Kamis)
```
Upah Harian: Rp 150.000
Lembur 1/2: Rp 75.000 (50%)
Total: Rp 225.000
Dibayar: Semua dibayar Kamis
```

### Skenario 4: Hadir + Lembur Full CASH ✨
```
Upah Harian: Rp 150.000 → dibayar Kamis
Lembur Full CASH: Rp 150.000 → dibayar HARI INI 💵
Total: Rp 300.000
```

### Skenario 5: Hadir + Lembur Setengah CASH ✨
```
Upah Harian: Rp 150.000 → dibayar Kamis
Lembur 1/2 CASH: Rp 75.000 → dibayar HARI INI 💵
Total: Rp 225.000
```

---

## 📱 Cara Menggunakan

### Di Halaman Kehadiran Tukang:

1. **Set Status Kehadiran:**
   - Klik tombol status sampai **HIJAU (Hadir)**

2. **Pilih Jenis Lembur:**
   - Klik tombol **Lembur** untuk cycle:
   
   ```
   Abu-abu (Tidak) 
      ↓
   Merah (Full - Kamis) 
      ↓
   Orange (1/2 - Kamis) 
      ↓
   Hijau (Full CASH - Hari Ini) 💰
      ↓
   Biru (1/2 CASH - Hari Ini) 💰
      ↓
   Kembali ke Abu-abu
   ```

3. **Notifikasi:**
   - Akan muncul popup menunjukkan:
     - Jenis lembur yang dipilih
     - Jumlah bonus lembur
     - Kapan dibayar (Kamis / Hari Ini)

---

## 🎨 Visual Guide

### Tombol Lembur:
- **Abu-abu** 🔘 = Tidak ada lembur
- **Merah** 🔴 = Lembur Full dibayar Kamis
- **Orange** 🟠 = Lembur Setengah dibayar Kamis
- **Hijau** 🟢 = Lembur Full CASH hari ini
- **Biru** 🔵 = Lembur Setengah CASH hari ini

### Badge di Detail:
- "**Full (Kamis)**" - badge merah
- "**Setengah (Kamis)**" - badge orange
- "**💰 Full CASH**" - badge hijau + icon cash
- "**💰 1/2 CASH**" - badge biru + icon cash
- Tanggal bayar cash ditampilkan di bawah upah

---

## 📊 Halaman Rekap

Rekap kehadiran sekarang menampilkan **7 kolom terpisah**:

| Kolom | Keterangan |
|-------|------------|
| Hadir | Jumlah hari hadir full |
| 1/2 Hari | Jumlah hari hadir setengah |
| Alfa | Jumlah hari tidak hadir |
| **L.Full** | Lembur full dibayar Kamis (merah) |
| **L.1/2** | Lembur setengah dibayar Kamis (orange) |
| **💰Full** | Lembur full CASH hari ini (hijau) |
| **💰1/2** | Lembur setengah CASH hari ini (biru) |

---

## 🔧 Perubahan Teknis

### 1. Database Migration
```php
// Tambah 2 kolom baru
$table->boolean('lembur_dibayar_cash')->default(false);
$table->date('tanggal_bayar_lembur')->nullable();
```

### 2. Model KehadiranTukang
```php
// Auto-set tanggal bayar untuk lembur cash
if ($this->lembur_dibayar_cash && $this->lembur != 'tidak') {
    $this->tanggal_bayar_lembur = $this->tanggal; // Hari ini
}
```

### 3. Controller - Toggle Cycle
```php
// 5 state cycle:
tidak -> full -> setengah_hari -> full_cash -> setengah_hari_cash -> tidak
```

### 4. View - 5 Warna Tombol
- CSS classes: `.lembur-tidak`, `.lembur-full`, `.lembur-setengah_hari`, 
  `.lembur-full-cash`, `.lembur-setengah_hari-cash`

---

## 📈 Use Case / Kapan Pakai Lembur Cash?

### Lembur Normal (Dibayar Kamis):
✅ Lembur terencana
✅ Budget sudah disetujui
✅ Tidak mendesak
✅ Part of regular workflow

### Lembur Cash (Dibayar Hari Ini):
✅ Lembur mendadak/urgent
✅ Proyek deadline hari ini
✅ Tukang butuh uang segera
✅ Emergency work
✅ Extra motivation needed

---

## 📋 Contoh Kasus Nyata

### Senin:
- **Jaenudin** hadir + Lembur Full (Kamis)
- Upah hari: Rp 150.000 (nanti Kamis)
- Lembur: Rp 150.000 (nanti Kamis)

### Selasa:
- **Jaenudin** hadir + **Lembur Full CASH** 💰
- Upah hari: Rp 150.000 (nanti Kamis)
- Lembur: Rp 150.000 (**DIBAYAR HARI INI**)
  - *Kasir langsung kasih Rp 150.000 cash ke Jaenudin*

### Kamis (Hari Gajian):
- Jaenudin terima:
  - Upah Senin: Rp 150.000
  - Upah Selasa: Rp 150.000
  - Upah Rabu: Rp 150.000
  - Upah Kamis: Rp 150.000
  - Lembur Senin: Rp 150.000
  - **Total: Rp 750.000**
  
- Lembur Selasa (Rp 150.000) sudah dibayar Selasa, jadi tidak termasuk

---

## 🔍 Tracking & Laporan

### Sistem Otomatis Mencatat:
- ✅ Jenis lembur (full/setengah)
- ✅ Metode bayar (cash/kamis)
- ✅ Tanggal pembayaran lembur
- ✅ Jumlah uang yang dibayar
- ✅ Status pembayaran

### Rekap Bulanan:
- Total lembur normal (dibayar Kamis)
- Total lembur cash (dibayar harian)
- Pisah perhitungan untuk audit keuangan

---

## 🎯 Keuntungan Sistem Ini

### Untuk Perusahaan:
✅ Tracking jelas lembur normal vs cash
✅ Kontrol cash flow lebih baik
✅ Audit pembayaran lebih mudah
✅ Fleksibilitas pembayaran

### Untuk Tukang:
✅ Bisa dapat uang lembur hari itu juga kalau urgent
✅ Transparansi kapan dibayar
✅ Motivasi kerja lembur lebih tinggi

### Untuk Admin:
✅ Satu klik pilih jenis lembur
✅ Notifikasi jelas
✅ Laporan otomatis terpisah

---

## 🚦 Status Implementasi

- ✅ Database migration (2 kolom baru)
- ✅ Model logic (auto-calculate & set tanggal bayar)
- ✅ Controller (5-state cycle toggle)
- ✅ View index (5 warna tombol)
- ✅ View detail (badge cash dengan tanggal bayar)
- ✅ View rekap (7 kolom terpisah)
- ✅ JavaScript (notifikasi & update UI)
- ✅ CSS (styling 5 state)

---

## 📅 Update Info

- **Tanggal:** 10 November 2025
- **Migration File:** `2025_11_10_110000_add_lembur_cash_to_kehadiran_tukangs.php`
- **Fitur:** Lembur Cash (Dibayar Hari Itu Juga)
- **Status:** ✅ **SIAP DIGUNAKAN!**

---

## 🎉 Testing

### Skenario Test:
1. ✅ Hadir + Tidak Lembur = Rp 150.000
2. ✅ Hadir + Lembur Full (Kamis) = Rp 300.000
3. ✅ Hadir + Lembur 1/2 (Kamis) = Rp 225.000
4. ✅ Hadir + Lembur Full CASH = Rp 300.000 (lembur dibayar hari ini)
5. ✅ Hadir + Lembur 1/2 CASH = Rp 225.000 (lembur dibayar hari ini)

---

## 📞 Cara Pakai Singkat

**3 Langkah Mudah:**

1. ✅ Klik status → **Hijau (Hadir)**
2. ✅ Klik lembur → Pilih warna sesuai kebutuhan:
   - **Hijau 💰** = Butuh cash hari ini (full)
   - **Biru 💰** = Butuh cash hari ini (setengah)
   - **Merah** = Lembur normal full (Kamis)
   - **Orange** = Lembur normal setengah (Kamis)
3. ✅ Selesai! Total upah otomatis dihitung

**Refresh halaman sekarang dan coba klik tombol lembur! 🎨**
