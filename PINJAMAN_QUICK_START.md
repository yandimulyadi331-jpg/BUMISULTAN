# 🚀 QUICK START - Sistem Pinjaman

## ✅ Sistem Sudah Siap Digunakan!

### 📦 Yang Sudah Diimplementasikan:

✅ Database (3 tabel: pinjaman, pinjaman_cicilan, pinjaman_history)  
✅ Models dengan relasi lengkap  
✅ Controller dengan 15+ fungsi  
✅ Routes terintegrasi  
✅ Views (Index, Create, Show, Edit)  
✅ Menu sidebar aktif  
✅ Perhitungan bunga Flat & Efektif/Anuitas  
✅ Workflow approval 7 status  
✅ Auto-generate nomor pinjaman  
✅ Auto-calculate denda keterlambatan  
✅ Integrasi transaksi keuangan  
✅ History & audit trail  

---

## 🎯 CARA MENGGUNAKAN (5 Menit)

### 1️⃣ **Akses Menu**
```
Login → Sidebar → Manajemen Pinjaman (di bawah Manajemen Keuangan)
```

### 2️⃣ **Buat Pengajuan Pinjaman**
```
Dashboard Pinjaman → Tombol "Pengajuan Pinjaman Baru"

Isi form:
- Pilih kategori: Crew atau Non-Crew
- Isi data peminjam
- Tentukan: Jumlah, Tenor, Bunga
- Upload dokumen (opsional)
- Submit
```

### 3️⃣ **Proses Approval**
```
Dashboard → Klik salah satu pinjaman → Detail

Workflow:
1. Review (opsional) → Tombol "Review Pinjaman"
2. Approve → Tombol "Setujui Pinjaman" (tentukan jumlah disetujui)
3. Cairkan → Tombol "Cairkan Dana" (input detail pencairan)

Status otomatis berubah setiap step
```

### 4️⃣ **Bayar Cicilan**
```
Detail Pinjaman → Scroll ke "Jadwal Cicilan"

Untuk setiap cicilan:
- Klik tombol "Bayar"
- Input jumlah & metode pembayaran
- Upload bukti (opsional)
- Submit

Sistem auto-update:
- Status cicilan
- Total terbayar
- Sisa pinjaman
- Jika semua lunas → Status pinjaman = LUNAS
```

---

## 🔥 FITUR UNGGULAN

### 💰 Kalkulator Cicilan Real-time
Saat input jumlah & tenor → Estimasi cicilan langsung muncul!

### 🎨 Dashboard Statistik
4 Card stats:
- 🟡 Pengajuan Baru
- 🔵 Dalam Review
- 🟢 Pinjaman Berjalan + Nominal
- ✅ Lunas + Total Dicairkan

### 🔍 Filter Pintar
- Kategori: Crew/Non-Crew
- Status: 7 pilihan
- Bulan & Tahun
- Search: Nomor/Nama

### ⚡ Automasi
- ✅ Nomor pinjaman: PNJ-202511-0001
- ✅ Jadwal cicilan auto-generate
- ✅ Denda keterlambatan auto-hitung (0.1%/hari)
- ✅ Progress pembayaran auto-update
- ✅ Status pinjaman auto-change
- ✅ Transaksi keuangan auto-record

### 📊 Timeline Visual
Lihat history lengkap:
- Siapa yang mengajukan
- Siapa yang review/approve
- Kapan dicairkan
- Setiap pembayaran tercatat

---

## 🎓 CONTOH CEPAT

### Skenario: Pinjaman Karyawan Rp 10 Juta
```
1. Pengajuan:
   - Kategori: Crew
   - Pilih karyawan: John Doe (NIK: 123456789)
   - Jumlah: Rp 10.000.000
   - Tenor: 12 bulan
   - Bunga: 10% flat
   - Sistem hitung: Cicilan Rp 916.667/bulan

2. Approval:
   - Klik "Setujui" → Jumlah disetujui: Rp 10.000.000
   - Status: DISETUJUI

3. Pencairan:
   - Klik "Cairkan Dana"
   - Tanggal: Hari ini
   - Metode: Transfer
   - Sistem generate 12 jadwal cicilan
   - Status: DICAIRKAN
   - Transaksi keuangan: -Rp 10.000.000 (pengeluaran)

4. Pembayaran Cicilan 1:
   - Bayar: Rp 916.667
   - Metode: Tunai
   - Status cicilan: LUNAS
   - Status pinjaman: BERJALAN
   - Sisa: Rp 9.083.333
   - Transaksi keuangan: +Rp 916.667 (pemasukan)

5. ... Lanjut sampai cicilan 12

6. Setelah cicilan 12 lunas:
   - Status pinjaman: LUNAS ✅
   - Sisa: Rp 0
```

---

## ⚙️ SISTEM PERHITUNGAN

### Bunga FLAT (Cicilan Tetap)
```
Contoh: Rp 10.000.000, 12 bulan, 10% flat

Total Bunga = 10.000.000 × 10% × (12/12) = Rp 1.000.000
Total Pinjaman = 10.000.000 + 1.000.000 = Rp 11.000.000
Cicilan/Bulan = 11.000.000 / 12 = Rp 916.667

Setiap bulan bayar: Rp 916.667 (tetap)
```

### Bunga EFEKTIF/Anuitas (Bunga Menurun)
```
Contoh: Rp 10.000.000, 12 bulan, 12% efektif

Bunga bulanan = 12% / 12 = 1% = 0.01
Cicilan/Bulan = 10.000.000 × [0.01(1.01)^12] / [(1.01)^12 - 1]
             ≈ Rp 888.488

Cicilan: Rp 888.488 (tetap)
Tapi komposisi:
- Bulan 1: Bunga Rp 100.000, Pokok Rp 788.488
- Bulan 2: Bunga Rp 92.115, Pokok Rp 796.373
- Bulan 3: Bunga Rp 84.152, Pokok Rp 804.336
- ... dst (bunga turun, pokok naik)
```

---

## 🛡️ KEAMANAN & VALIDASI

✅ Role-based: Hanya super admin  
✅ Validasi form ketat  
✅ File upload aman (max 2MB)  
✅ Soft delete (data tidak hilang permanen)  
✅ Audit trail lengkap  
✅ Status workflow terkontrol  

---

## 📱 INTEGRASI

### Dengan Transaksi Keuangan:
- **Pencairan** → Pengeluaran otomatis
- **Pembayaran** → Pemasukan otomatis
- Kategori: `pinjaman_karyawan`, `pembayaran_pinjaman`

### Dengan Data Karyawan:
- Auto-load data karyawan untuk crew
- Relasi via NIK

---

## 🎉 SELESAI!

Sistem pinjaman siap digunakan dengan:
- **Database**: 3 tabel migrasi sukses ✅
- **Backend**: Controller + Models + Routes ✅
- **Frontend**: 4 views lengkap ✅
- **UI/UX**: Dashboard modern & interaktif ✅
- **Workflow**: 7 status approval ✅
- **Automasi**: 6+ proses otomatis ✅

**Total Development**: 8 task completed 🎯

---

## 📚 Dokumentasi Lengkap
Lihat: `DOKUMENTASI_PINJAMAN_CREW_NON_CREW.md`

## 🐛 Troubleshooting

**Q: Menu tidak muncul?**  
A: Pastikan login sebagai super admin

**Q: Error saat cairkan?**  
A: Pastikan sudah di-approve terlebih dahulu

**Q: Cicilan tidak auto-generate?**  
A: Generate saat pencairan, bukan saat approve

**Q: Denda tidak terhitung?**  
A: Denda auto-hitung saat pembayaran cicilan

---

**Ready to Use!** 🚀  
Langsung coba di aplikasi Anda sekarang!
