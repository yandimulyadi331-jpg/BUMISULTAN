# 📊 DIAGRAM VISUAL: LOGIKA ANGSURAN BERBASIS CICILAN USER

## 1️⃣ PERBANDINGAN SISTEM LAMA vs BARU

```
┌─────────────────────────────────────────────────────────────────────────┐
│ SISTEM LAMA (Berbasis Tenor Fixed)                                      │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│ FORM INPUT:                                                             │
│ ┌─────────────────┐        ┌─────────────────┐                         │
│ │ Jumlah Pinjaman │        │   Tenor (Fixed) │                         │
│ │   5.000.000     │        │      3 bulan    │                         │
│ └────────┬────────┘        └────────┬────────┘                         │
│          │                          │                                  │
│          └──────────┬───────────────┘                                  │
│                     ↓                                                   │
│         SISTEM HITUNG: cicilan = total ÷ tenor                         │
│         cicilan = 5.000.000 ÷ 3 = 1.666.667/bulan                      │
│                     ↓                                                   │
│    JADWAL CICILAN (Cicilan kecil, tidak sesuai kemampuan):            │
│    ├─ Bulan 1: 1.666.667                                              │
│    ├─ Bulan 2: 1.666.667                                              │
│    └─ Bulan 3: 1.666.666                                              │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│ SISTEM BARU (Berbasis Cicilan Preferred User) ✅                        │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│ FORM INPUT:                                                             │
│ ┌────────────────┐        ┌──────────────────────┐                    │
│ │ Jumlah Pinjaman│        │ Cicilan per Bulan    │                    │
│ │  5.000.000     │        │   2.000.000 (user!)  │                    │
│ └────────┬───────┘        └──────────┬───────────┘                    │
│          │                           │                                 │
│          └───────────┬───────────────┘                                 │
│                      ↓                                                  │
│    JS hitungTenor(): tenor = ceil(5.000.000 ÷ 2.000.000)              │
│                      tenor = ceil(2.5) = 3 bulan ✅                    │
│                      ↓                                                  │
│    JADWAL CICILAN (Cicilan besar, sesuai kemampuan user):             │
│    ├─ Bulan 1: 2.000.000 ✅ (sesuai user)                            │
│    ├─ Bulan 2: 2.000.000 ✅ (sesuai user)                            │
│    └─ Bulan 3: 1.000.000 ✅ (sisa otomatis adjust)                   │
│                                                                          │
│    Verifikasi: 2M + 2M + 1M = 5M ✅ AKURAT TRANSPARAN!               │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 2️⃣ FLOW DIAGRAM: USER INPUT → DATABASE

```
                    ┌──────────────────┐
                    │   USER FORM      │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │  Input Fields:   │
                    │ • Jumlah: 5M     │
                    │ • Cicilan: 2M    │
                    │ • Tenor: (auto)  │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────────────────────┐
                    │   JAVASCRIPT (FRONTEND)          │
                    │  ┌──────────────────────────┐   │
                    │  │ hitungTenor() {          │   │
                    │  │  tenor = ceil(           │   │
                    │  │    5000000 / 2000000     │   │
                    │  │  )                       │   │
                    │  │  // tenor = 3            │   │
                    │  │  Set field tenor = 3    │   │
                    │  │ }                        │   │
                    │  └──────────────────────────┘   │
                    └────────┬─────────────────────────┘
                             │ Set tenor_bulan = 3
                    ┌────────▼─────────┐
                    │   FORM VALID     │
                    │   SUBMIT         │
                    └────────┬─────────┘
                             │
              ┌──────────────▼───────────────┐
              │ POST /pinjaman               │
              │ (store method)               │
              └────────┬────────────────────┘
                       │
       ┌───────────────▼────────────────────┐
       │ PinjamanController::store()         │
       │ ┌───────────────────────────────┐ │
       │ │ • Validasi input              │ │
       │ │ • Set total = 5.000.000       │ │
       │ │ • Jangan ubah cicilan = 2M    │ │
       │ │ • Set tenor = 3 (dari form)   │ │
       │ │ • Create Pinjaman record      │ │
       │ └───────────────────────────────┘ │
       │ ┌───────────────────────────────┐ │
       │ │ Call generateJadwalCicilan()  │ │
       │ │ ┌─────────────────────────┐   │ │
       │ │ │ cicilanNormal = 2M      │   │ │
       │ │ │ (from DB cicilan_per)   │   │ │
       │ │ │                         │   │ │
       │ │ │ Loop i=1 to 3:          │   │ │
       │ │ │ • i<3: nominal = 2M     │   │ │
       │ │ │ • i=3: nominal=         │   │ │
       │ │ │   5M-(2M×2)=1M (sisa)   │   │ │
       │ │ │                         │   │ │
       │ │ │ Create cicilan records  │   │ │
       │ │ └─────────────────────────┘   │ │
       │ └───────────────────────────────┘ │
       └────────┬─────────────────────────┘
                │
       ┌────────▼──────────────┐
       │ DATABASE INSERT       │
       │ ┌──────────────────┐ │
       │ │ pinjaman table:  │ │
       │ │ • id: 1          │ │
       │ │ • total: 5M      │ │
       │ │ • cicilan: 2M    │ │
       │ │ • tenor: 3       │ │
       │ └──────────────────┘ │
       │ ┌──────────────────┐ │
       │ │ pinjaman_cicilan │ │
       │ │ • cicilan_1: 2M  │ │
       │ │ • cicilan_2: 2M  │ │
       │ │ • cicilan_3: 1M  │ │
       │ └──────────────────┘ │
       └──────────────────────┘
                │
       ┌────────▼──────────────┐
       │ RESULT: ✅ AKURAT     │
       │ TOTAL: 2M+2M+1M=5M    │
       └──────────────────────┘
```

---

## 3️⃣ TABEL CICILAN: VISUAL BREAKDOWN

### Skenario: Pinjaman 5.000.000, Cicilan 2.000.000/bulan

```
╔═══════════════════════════════════════════════════════════╗
║           JADWAL CICILAN PINJAMAN                        ║
╠═════╦═══════════════╦═══════════════╦═══════════════════╣
║  #  ║ Nominal       ║ Tipe          ║ Keterangan        ║
╠═════╬═══════════════╬═══════════════╬═══════════════════╣
║  1  ║ Rp 2.000.000  ║ Normal        ║ User input        ║
║  2  ║ Rp 2.000.000  ║ Normal        ║ User input        ║
║  3  ║ Rp 1.000.000  ║ Sisa (Adjust) ║ Total - (2×2)     ║
╠═════╩═══════════════╩═══════════════╩═══════════════════╣
║                                  TOTAL: Rp 5.000.000    ║
║                                  ✅ AKURAT 100%         ║
╚═════════════════════════════════════════════════════════╝

Rumus Cicilan ke-3:
    5.000.000 - (2.000.000 × 2) = 1.000.000 ✅
    └─ Total       └─ Cicilan Normal × (Tenor-1)
```

---

## 4️⃣ FORMULA LOGIC COMPARISON

### LAMA (Tenor-Based):
```
INPUT: Jumlah + Tenor
        5M + 3 bulan

HITUNG: cicilan_normal = floor(Jumlah ÷ Tenor)
        cicilan_normal = floor(5.000.000 ÷ 3)
        cicilan_normal = 1.666.666

HASIL:  Bulan 1: 1.666.666
        Bulan 2: 1.666.666
        Bulan 3: 1.666.668 (sisa)
```

### BARU (Cicilan-Based) ✅:
```
INPUT: Jumlah + Cicilan
       5M + 2M/bulan

HITUNG TENOR: tenor = ceil(Jumlah ÷ Cicilan)
              tenor = ceil(5.000.000 ÷ 2.000.000)
              tenor = 3 bulan

HITUNG CICILAN_TERAKHIR: 
        cicilan_terakhir = Jumlah - (Cicilan × (Tenor-1))
        cicilan_terakhir = 5.000.000 - (2.000.000 × 2)
        cicilan_terakhir = 1.000.000

HASIL:  Bulan 1: 2.000.000 ✅
        Bulan 2: 2.000.000 ✅
        Bulan 3: 1.000.000 ✅ (SISA JELAS)
        Total: 5.000.000 ✅ AKURAT
```

---

## 5️⃣ TEST CASES VISUAL

### Test 1: Pinjaman 5.000.000, Cicilan 2.000.000/bulan
```
┌─ Input ────────────────────────────────┐
│ Jumlah Pinjaman: Rp 5.000.000          │
│ Cicilan per Bulan: Rp 2.000.000        │
└────────────────────────────────────────┘
         ↓ Sistem Hitung
┌─ Tenor Otomatis ───────────────────────┐
│ ceil(5.000.000 ÷ 2.000.000) = 3 bulan  │
└────────────────────────────────────────┘
         ↓ Generate Jadwal
┌─ Jadwal Cicilan ───────────────────────┐
│ Bulan 1: Rp 2.000.000                  │
│ Bulan 2: Rp 2.000.000                  │
│ Bulan 3: Rp 1.000.000 (5M - 4M = 1M)  │
│ ────────────────────────────────────── │
│ Total: Rp 5.000.000 ✅ AKURAT         │
└────────────────────────────────────────┘
```

### Test 2: Pinjaman 3.500.000, Cicilan 1.000.000/bulan
```
┌─ Input ────────────────────────────────┐
│ Jumlah Pinjaman: Rp 3.500.000          │
│ Cicilan per Bulan: Rp 1.000.000        │
└────────────────────────────────────────┘
         ↓ Sistem Hitung
┌─ Tenor Otomatis ───────────────────────┐
│ ceil(3.500.000 ÷ 1.000.000) = 4 bulan  │
└────────────────────────────────────────┘
         ↓ Generate Jadwal
┌─ Jadwal Cicilan ───────────────────────┐
│ Bulan 1: Rp 1.000.000                  │
│ Bulan 2: Rp 1.000.000                  │
│ Bulan 3: Rp 1.000.000                  │
│ Bulan 4: Rp 500.000 (3.5M - 3M = 0.5M)│
│ ────────────────────────────────────── │
│ Total: Rp 3.500.000 ✅ AKURAT         │
└────────────────────────────────────────┘
```

### Test 3: Pinjaman 10.000.000, Cicilan 3.000.000/bulan
```
┌─ Input ────────────────────────────────┐
│ Jumlah Pinjaman: Rp 10.000.000         │
│ Cicilan per Bulan: Rp 3.000.000        │
└────────────────────────────────────────┘
         ↓ Sistem Hitung
┌─ Tenor Otomatis ───────────────────────┐
│ ceil(10.000.000 ÷ 3.000.000) = 4 bulan │
└────────────────────────────────────────┘
         ↓ Generate Jadwal
┌─ Jadwal Cicilan ───────────────────────┐
│ Bulan 1: Rp 3.000.000                  │
│ Bulan 2: Rp 3.000.000                  │
│ Bulan 3: Rp 3.000.000                  │
│ Bulan 4: Rp 1.000.000 (10M - 9M = 1M) │
│ ────────────────────────────────────── │
│ Total: Rp 10.000.000 ✅ AKURAT        │
└────────────────────────────────────────┘
```

---

## 6️⃣ FILE PERUBAHAN: VISUAL

```
┌────────────────────────────────────────────────────────────┐
│ File 1: app/Http/Controllers/PinjamanController.php        │
├────────────────────────────────────────────────────────────┤
│                                                             │
│ Lines 195-210 (store method)                              │
│                                                             │
│ ❌ DIHAPUS:                                                │
│ $nominalPerBulan = floor($validated['total_pinjaman'] /   │
│                          $validated['tenor_bulan']);       │
│ $validated['cicilan_per_bulan'] = $nominalPerBulan;       │
│                                                             │
│ ✅ DIGANTI DENGAN:                                         │
│ // cicilan_per_bulan sudah dari user input, jangan diubah │
│ // Cicilan terakhir akan dihitung di generateJadwalCicilan │
│                                                             │
└────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────┐
│ File 2: app/Models/Pinjaman.php                            │
├────────────────────────────────────────────────────────────┤
│                                                             │
│ Lines 238-247 (generateJadwalCicilan method)              │
│                                                             │
│ ❌ SEBELUM:                                                │
│ $cicilanNormal = floor($this->total_pinjaman /            │
│                        $this->tenor_bulan);               │
│                                                             │
│ ✅ SESUDAH:                                                │
│ $cicilanNormal = $this->cicilan_per_bulan;                │
│ // Ambil dari user input yang disimpan di DB              │
│                                                             │
└────────────────────────────────────────────────────────────┘
```

---

## 7️⃣ FEATURE COMPARISON TABLE

```
╔══════════════════════╦══════════════════╦═══════════════════╗
║       ASPEK          ║  SISTEM LAMA     ║  SISTEM BARU ✅   ║
╠══════════════════════╬══════════════════╬═══════════════════╣
║ User Input           ║ Tenor (bulan)    ║ Cicilan (Rp)      ║
║ Fleksibilitas        ║ Rendah           ║ Tinggi ✅         ║
║ Tenor                ║ Fixed user       ║ Auto hitung ✅    ║
║ Cicilan Normal       ║ floor(tot/tenor) ║ User input ✅     ║
║ Cicilan Terakhir     ║ Auto adjust      ║ Auto adjust ✅    ║
║ User Friendly        ║ Kompleks         ║ Sederhana ✅      ║
║ Sesuai Kemampuan     ║ Mungkin tidak    ║ Pasti sesuai ✅   ║
║ Akurasi Total        ║ Akurat           ║ Akurat ✅         ║
║ Transparansi         ║ Baik             ║ Lebih baik ✅     ║
╚══════════════════════╩══════════════════╩═══════════════════╝
```

---

## 8️⃣ DEPLOYMENT CHECKLIST VISUAL

```
┌─────────────────────────────────────────┐
│ 📋 PRE-DEPLOYMENT                       │
├─────────────────────────────────────────┤
│ ☐ Backup database                       │
│ ☐ Review code changes                   │
│ ☐ Test di staging environment           │
└─────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────┐
│ 🚀 DEPLOYMENT                           │
├─────────────────────────────────────────┤
│ ☐ Copy file 1: PinjamanController.php   │
│ ☐ Copy file 2: Pinjaman.php             │
│ ☐ php artisan cache:clear               │
│ ☐ php artisan config:clear              │
└─────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────┐
│ ✅ POST-DEPLOYMENT                      │
├─────────────────────────────────────────┤
│ ☐ Test: Buat pinjaman 5M, cicilan 2M   │
│ ☐ Verifikasi tenor auto = 3 bulan       │
│ ☐ Verifikasi cicilan: 2M+2M+1M         │
│ ☐ Monitor logs 24 jam                   │
└─────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────┐
│ ✅ STATUS: PRODUCTION READY             │
└─────────────────────────────────────────┘
```

---

**Dokumentasi Detail:** [LOGIKA_ANGSURAN_BERBASIS_CICILAN_USER.md](LOGIKA_ANGSURAN_BERBASIS_CICILAN_USER.md)
**Summary:** [SUMMARY_LOGIKA_ANGSURAN_CICILAN_USER.md](SUMMARY_LOGIKA_ANGSURAN_CICILAN_USER.md)
