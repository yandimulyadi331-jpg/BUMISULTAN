# 📝 PEMBAGIAN GAJI KAMIS DENGAN TTD DIGITAL

## ✨ Fitur Utama

- ✅ **Paperless** - Tidak perlu slip gaji fisik
- ✅ **TTD Digital** - Tukang tanda tangan langsung di layar
- ✅ **Auto-Calculate** - Otomatis hitung upah + potongan
- ✅ **Audit Trail** - Semua pembayaran tercatat dengan timestamp
- ✅ **One-Time Payment** - Tidak bisa bayar 2x untuk periode sama
- ✅ **Slip PDF** - Simpan slip gaji dengan TTD (coming soon)

---

## 🚀 Quick Start

### 1. **Akses Menu**
```
Dashboard → Keuangan Tukang → Gaji Kamis (TTD)
```

### 2. **Periode Otomatis**
Sistem otomatis menghitung periode minggu ini:
- **Mulai:** Sabtu minggu lalu
- **Akhir:** Jumat minggu ini
- **Bayar:** Kamis (hari ini)

### 3. **Proses Pembayaran**

**Step 1:** Lihat daftar tukang dengan rincian:
- Upah Harian (dari kehadiran)
- Upah Lembur (yang belum dibayar cash)
- Total Kotor
- Potongan (pinjaman, denda, dll)
- **Total Nett** (yang akan diterima)

**Step 2:** Klik tombol **"Bayar Gaji"** pada tukang

**Step 3:** Modal muncul dengan:
- Detail slip gaji lengkap
- Canvas untuk TTD digital
- Rincian potongan

**Step 4:** Tukang membubuhkan tanda tangan di canvas

**Step 5:** Klik **"Simpan & Bayar"**

**Step 6:** ✅ Pembayaran tersimpan dengan TTD!

---

## 💾 Database

### Tabel: `pembayaran_gaji_tukangs`

Menyimpan:
- Periode pembayaran
- Total upah & potongan
- TTD digital (base64)
- IP address & device info
- Status pembayaran
- Admin yang bayar
- Timestamp

---

## 🎨 Teknologi TTD Digital

### **Library:** Signature Pad JS v4.0.0
- Canvas HTML5
- Support mouse & touchscreen
- Export ke base64 PNG
- Smooth drawing

### **Cara Kerja:**
```javascript
// Initialize
const canvas = document.getElementById('signature-pad');
signaturePad = new SignaturePad(canvas);

// Check kosong
if (signaturePad.isEmpty()) {
    alert('Belum TTD!');
}

// Get base64
const ttdBase64 = signaturePad.toDataURL();

// Simpan ke database
$.ajax({
    data: {
        tanda_tangan: ttdBase64
    }
});
```

---

## 📊 Kalkulasi Otomatis

### **Upah Harian**
```sql
SELECT SUM(upah_harian) 
FROM kehadiran_tukangs
WHERE tukang_id = ?
  AND tanggal BETWEEN periode_mulai AND periode_akhir
```

### **Upah Lembur (Non-Cash)**
```sql
SELECT SUM(upah_lembur)
FROM kehadiran_tukangs
WHERE tukang_id = ?
  AND lembur != 'tidak'
  AND lembur_dibayar_cash = false
  AND tanggal BETWEEN periode_mulai AND periode_akhir
```

### **Potongan Pinjaman**
```sql
SELECT SUM(cicilan_per_minggu)
FROM pinjaman_tukangs
WHERE tukang_id = ?
  AND status = 'aktif'
```

### **Potongan Lain (Denda, dll)**
```sql
SELECT SUM(jumlah_potongan)
FROM potongan_tukangs
WHERE tukang_id = ?
  AND tanggal_potongan BETWEEN periode_mulai AND periode_akhir
```

### **Total Nett**
```
Total Nett = (Upah Harian + Upah Lembur - Lembur Cash) - Total Potongan
```

---

## 🔒 Keamanan

✅ **Validasi TTD** - Wajib ada sebelum simpan  
✅ **IP Logging** - Record IP address yang TTD  
✅ **Device Info** - Simpan info browser/device  
✅ **Timestamp** - Catat waktu pembayaran  
✅ **User Audit** - Catat admin yang bayar  
✅ **One-Time** - Cek duplikasi periode  
✅ **DB Transaction** - Rollback jika error

---

## 🎯 Use Case Scenarios

### **Scenario 1: Tukang Hadir Normal**
```
Upah Harian:  Rp 750.000 (5 hari x Rp 150.000)
Upah Lembur:  Rp 300.000 (4x lembur)
Lembur Cash:  Rp -100.000 (dibayar langsung)
Total Kotor:  Rp 950.000
Potongan:     Rp -150.000 (cicilan pinjaman)
TOTAL NETT:   Rp 800.000 ✅
```

### **Scenario 2: Tukang Ada Denda**
```
Upah Harian:  Rp 450.000 (3 hari)
Upah Lembur:  Rp 0
Total Kotor:  Rp 450.000
Potongan:     Rp -200.000 (cicilan + denda kerusakan)
TOTAL NETT:   Rp 250.000 ✅
```

### **Scenario 3: Tukang Banyak Lembur**
```
Upah Harian:  Rp 750.000
Upah Lembur:  Rp 600.000 (6x lembur full)
Lembur Cash:  Rp -300.000 (3x dibayar cash)
Total Kotor:  Rp 1.050.000
Potongan:     Rp -100.000
TOTAL NETT:   Rp 950.000 ✅
```

---

## 📱 Responsive Design

✅ Desktop - Full width table  
✅ Tablet - Scrollable table  
✅ Mobile - Touch signature support

---

## 🐛 Troubleshooting

### **TTD tidak muncul?**
- Pastikan JavaScript enabled
- Check console error
- Reload halaman

### **Tidak bisa simpan?**
- Cek TTD sudah diisi
- Pastikan tidak double pembayaran
- Check network connection

### **Total tidak sesuai?**
- Refresh halaman untuk recalculate
- Cek data kehadiran minggu ini
- Verifikasi potongan aktif

---

## 📅 Next Features (Coming Soon)

- [ ] Download Slip Gaji PDF dengan TTD
- [ ] Kirim Slip via WhatsApp
- [ ] History pembayaran per tukang
- [ ] Export Excel laporan gaji bulanan
- [ ] Notifikasi auto setiap Kamis

---

## 🎓 Training Users

### **Untuk Admin:**
1. Buka menu setiap Kamis pagi
2. Panggil tukang satu per satu
3. Klik "Bayar Gaji"
4. Minta tukang TTD
5. Klik "Simpan & Bayar"
6. Selesai! ✅

### **Untuk Tukang:**
1. Datang ke meja admin saat dipanggil
2. Lihat detail slip di layar
3. Bubuhkan TTD di canvas
4. Terima gaji cash
5. Selesai! ✅

---

**Dibuat:** 10 November 2025  
**Status:** ✅ Production Ready  
**Developer:** AI Assistant
