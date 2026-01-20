# 📋 ANALISA PERBAIKAN LOGIKA ANGSURAN NOMINAL GANJIL/KESALIP

## 🎯 RINGKASAN MASALAH

Sistem angsuran pinjaman saat ini memiliki keterbatasan dalam menangani **nominal pinjaman yang tidak habis dibagi dengan tenor bulan**.

### Contoh Kasus:
- **Pinjaman: Rp 2.250.000**
- **Tenor: 10 bulan**
- **Hasil pembagian: 2.250.000 ÷ 10 = 225.000**
- ✅ Dalam kasus ini benar-benar habis

Namun jika:
- **Pinjaman: Rp 2.251.000**
- **Tenor: 10 bulan**
- **Hasil pembagian: 2.251.000 ÷ 10 = 225.100** (muncul desimal: 225.100)
- ❌ Terjadi kesalip/sisa kecil

---

## 🔴 MASALAH YANG TERIDENTIFIKASI

### 1. **LOGIKA SEKARANG - TIDAK AKURAT**
```
Lokasi: app/Http/Controllers/PinjamanController.php (line ~200)

$validated['total_pinjaman'] = $validated['cicilan_per_bulan'] * $validated['tenor_bulan'];
```

**Masalah:**
- Sistem menghitung `total_pinjaman = cicilan_per_bulan × tenor`
- Jika user input nominal pinjaman = **Rp 2.251.000** dengan cicilan = **Rp 225.100**
- Maka `total_pinjaman = 225.100 × 10 = 2.251.000` ✅ KEBETULAN COCOK

**Tapi kalau user input cicilan = Rp 225.000:**
- Maka `total_pinjaman = 225.000 × 10 = 2.250.000`
- Sisa = Rp 1.000 HILANG! ❌

### 2. **LOGIKA CICILAN TETAP** 
```
Lokasi: app/Models/Pinjaman.php (line ~210)
```

**Problem:**
- Semua cicilan dibuat sama besar = `cicilan_per_bulan`
- Tidak ada mekanisme untuk mengalokasikan **sisa kecil** ke cicilan terakhir
- Jika ada pembulatan, akan muncul selisih di akhir

### 3. **LOGIKA PEMBAYARAN PARTIAL**
```
Lokasi: app/Models/PinjamanCicilan.php (line ~100)
```

**Problem:**
```php
$this->sisa_cicilan = $this->jumlah_cicilan - $this->jumlah_dibayar;
```
- Tidak mempertimbangkan sisa pinjaman induk yang tidak pas
- Bisa menyebabkan overpay atau underpay

---

## ✅ SOLUSI: LOGIKA AKURAT & TRANSPARAN

### **PRINSIP SOLUSI:**
1. **Cicilan Normal**: Cicilan per bulan 1-9 = `floor(total_pinjaman / tenor)`
2. **Cicilan Terakhir**: Cicilan bulan ke-10 = `total_pinjaman - (cicilan_normal × 9)`
3. **Benefit:**
   - Total = cicilan_1 + cicilan_2 + ... + cicilan_10 = 100% AKURAT
   - Tidak ada sisa yang hilang
   - Transparan & mudah diaudit

### **Contoh Implementasi:**

#### **KASUS 1: Nominal Pas**
```
Pinjaman: Rp 2.250.000
Tenor: 10 bulan

Cicilan per bulan (1-9): floor(2.250.000 / 10) = Rp 225.000
Cicilan bulan ke-10: 2.250.000 - (225.000 × 9) = Rp 225.000

Total = 225.000 × 10 = Rp 2.250.000 ✅ 100% AKURAT
```

#### **KASUS 2: Nominal TIDAK Pas (Ganjil)**
```
Pinjaman: Rp 2.251.000
Tenor: 10 bulan

Cicilan per bulan (1-9): floor(2.251.000 / 10) = Rp 225.100
Cicilan bulan ke-10: 2.251.000 - (225.100 × 9) = Rp 225.100

Total = 225.100 × 10 = Rp 2.251.000 ✅ 100% AKURAT
```

#### **KASUS 3: Nominal Ganjil (Sisa Kecil)**
```
Pinjaman: Rp 1.000.000
Tenor: 3 bulan

Cicilan per bulan (1-2): floor(1.000.000 / 3) = Rp 333.333
Cicilan bulan ke-3: 1.000.000 - (333.333 × 2) = Rp 333.334

Total = 333.333 + 333.333 + 333.334 = Rp 1.000.000 ✅ 100% AKURAT
```

---

## 📝 PERUBAHAN KODE YANG DIPERLUKAN

### **1️⃣ FILE: app/Http/Controllers/PinjamanController.php**
**Bagian: store() method (around line 200)**

**SEBELUM:**
```php
$validated['total_pinjaman'] = $validated['cicilan_per_bulan'] * $validated['tenor_bulan'];
```

**SESUDAH:**
```php
// Hitung total_pinjaman dari jumlah_pengajuan (sumber kebenaran tunggal)
$validated['total_pinjaman'] = $validated['jumlah_pengajuan'];

// cicilan_per_bulan akan dihitung ulang di generateJadwalCicilan
// Ini adalah cicilan normal (untuk cicilan 1-9, cicilan ke-10 bisa berbeda)
$nominalPerBulan = floor($validated['total_pinjaman'] / $validated['tenor_bulan']);
$validated['cicilan_per_bulan'] = $nominalPerBulan;
```

### **2️⃣ FILE: app/Models/Pinjaman.php**
**Method: generateJadwalCicilan() (around line 200)**

**SEBELUM:**
```php
for ($i = 1; $i <= $this->tenor_bulan; $i++) {
    // ...
    PinjamanCicilan::create([
        'jumlah_pokok' => round($cicilanPerBulan, 2),
        'jumlah_bunga' => 0,
        'jumlah_cicilan' => round($cicilanPerBulan, 2),
        'sisa_cicilan' => round($cicilanPerBulan, 2),
        'status' => 'belum_bayar',
    ]);
}
```

**SESUDAH:**
```php
// Cicilan normal (cicilan 1 sampai tenor-1)
$cicilanNormal = floor($this->total_pinjaman / $this->tenor_bulan);

// Cicilan terakhir (untuk handle sisa kecil)
$cicilanTerakhir = $this->total_pinjaman - ($cicilanNormal * ($this->tenor_bulan - 1));

for ($i = 1; $i <= $this->tenor_bulan; $i++) {
    // ... existing code ...
    
    // Tentukan nominal cicilan ini
    if ($i < $this->tenor_bulan) {
        // Cicilan normal
        $nominalCicilan = $cicilanNormal;
    } else {
        // Cicilan terakhir (bisa berbeda jika ada sisa)
        $nominalCicilan = $cicilanTerakhir;
    }
    
    PinjamanCicilan::create([
        'pinjaman_id' => $this->id,
        'cicilan_ke' => $i,
        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
        'jumlah_pokok' => $nominalCicilan,
        'jumlah_bunga' => 0,
        'jumlah_cicilan' => $nominalCicilan,
        'sisa_cicilan' => $nominalCicilan,
        'status' => 'belum_bayar',
    ]);
}
```

### **3️⃣ FILE: app/Models/PinjamanCicilan.php**
**Method: prosesPembayaran() (around line 110)**

Untuk pembayaran partial/sebagian, tidak perlu perubahan besar karena logika sudah benar. Pastikan hanya:

```php
public function prosesPembayaran($jumlahBayar, ...) {
    $totalTagihan = $this->sisa_cicilan; // ✅ Gunakan sisa_cicilan yang akurat
    
    // ... existing code ...
    
    // Update total pembayaran di pinjaman induk
    $pinjaman = $this->pinjaman;
    $pinjaman->total_terbayar += $jumlahBayar;
    
    // ✅ PENTING: Hitung sisa dari total_pinjaman - total_terbayar
    $pinjaman->sisa_pinjaman = $pinjaman->total_pinjaman - $pinjaman->total_terbayar;
    
    // Cek apakah sudah lunas semua
    if ($pinjaman->sisa_pinjaman <= 0) {
        $pinjaman->status = 'lunas';
        $pinjaman->tanggal_lunas = now();
    } else {
        $pinjaman->status = 'berjalan';
    }
}
```

---

## 📊 VERIFIKASI AKURASI

### **Checklist Validasi:**
- [ ] Total pinjaman = SUM(semua cicilan) ✅ PASTI 100% akurat
- [ ] Tidak ada sisa cicilan yang terlewat ✅ Semua di cicilan terakhir
- [ ] Partial payment bekerja dengan benar ✅ Update sisa_pinjaman otomatis
- [ ] Update nominal cicilan langsung reflect ke sisa_pinjaman ✅ Otomatis generate ulang
- [ ] No magical rounding/pembulatan ✅ Transparansi penuh

### **Testing Scenarios:**
```
1. Nominal Rp 2.250.000, tenor 10 bulan
   ✓ Expected: 225.000 × 10 = 2.250.000
   
2. Nominal Rp 1.000.000, tenor 3 bulan
   ✓ Expected: 333.333 + 333.333 + 333.334 = 1.000.000
   
3. Nominal Rp 500.000, tenor 7 bulan
   ✓ Expected: 71.428 × 6 + 71.432 = 500.000
   
4. Change cicilan nominal → sisa_pinjaman auto-update
   ✓ Expected: Update cicilan_per_bulan → regenerate jadwal → auto-update sisa
```

---

## 🔄 TIMELINE PERUBAHAN

1. Update PinjamanController (store & update method)
2. Update Pinjaman model (generateJadwalCicilan)
3. Verify PinjamanCicilan logic
4. Testing dengan sample data nominal ganjil
5. Deploy & monitoring

---

## 📌 CATATAN PENTING

✅ **Sistem sekarang sudah cukup baik**, hanya perlu:
- **Precision**: Gunakan floor untuk cicilan normal, sisa ke cicilan terakhir
- **Transparency**: Tidak ada hidden rounding
- **Verification**: Selalu total semua cicilan = total pinjaman

❌ **Yang TIDAK boleh:**
- Menambah/mengurangi nominal pinjaman
- Menggunakan pembulatan (round) yang menyembunyikan sisa
- Membuat logika kompleks dengan bunga otomatis

✅ **Yang HARUS:**
- Akurat sampai rupiah
- Transparan & auditabel
- Setiap perubahan nominal cicilan → sisa_pinjaman auto-update
