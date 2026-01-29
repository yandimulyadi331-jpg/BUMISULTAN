# 📋 PANDUAN DEPLOYMENT KE TERMIUS

## ✅ Status Deployment
- **Repository**: GitHub BUMISULTAN (main branch)
- **Last Push**: ✅ e65d6e9 - "feat: pinjaman lama + laporan cicilan detail dengan potongan per minggu"
- **Files Changed**: 66 files, 19373+ insertions
- **New Features**: 
  - ✅ Toggle potongan pinjaman per-minggu
  - ✅ Auto-record history potongan
  - ✅ Laporan cicilan detail dengan akumulasi
  - ✅ Lihat pinjaman lama (all status)

---

## 🚀 Cara Deploy ke Termius

### Opsi 1: PowerShell (Jika Anda di Windows)
```powershell
cd d:\bumisultanAPP\bumisultanAPP
.\deploy-termius.ps1
```

### Opsi 2: SSH Manual ke Termius
```bash
ssh root@103.168.172.50
cd /home/bumisultan/public_html
git pull origin main
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan migrate --force
php artisan optimize
```

### Opsi 3: Bash Script di Server
```bash
ssh root@103.168.172.50
cd /home/bumisultan/public_html
bash deploy-termius.sh
```

---

## 📝 Perubahan yang Di-Deploy

### 1. **Database Migration**
- File: `database/migrations/2026_01_29_000001_create_potongan_pinjaman_payroll_detail_table.php`
- Tabel: `potongan_pinjaman_payroll_detail`
- Columns: minggu, tahun, status_potong, nominal_cicilan, alasan, dll
- Status: ✅ Sudah di-migrate lokal, perlu di-run di server

### 2. **Model Baru**
- File: `app/Models/PotonganPinjamanPayrollDetail.php`
- Relasi: belongsTo Tukang, belongsTo PinjamanTukang
- Methods: untuk query dan manipulasi potongan per minggu

### 3. **Model Updates**
- `app/Models/Tukang.php`: +8 methods untuk potongan
- `app/Models/PinjamanTukang.php`: +5 methods untuk cicilan history

### 4. **Controller Updates**
- `app/Http/Controllers/KeuanganTukangController.php`:
  - `togglePotonganPinjaman()` - Ubah status potongan + record history
  - `detailPinjaman()` - Load riwayat dengan auto-generate jika kosong
  - `pinjaman()` - Support filter "all" untuk lihat pinjaman lama

### 5. **View Updates**
- `resources/views/keuangan-tukang/pinjaman/detail.blade.php`:
  - Laporan Cicilan Pinjaman (Per Minggu) - NEW TABLE
  - Columns: No, Cicilan Ke, Tanggal, Nominal, Jumlah Cicilan, Sisa Angsuran, Status, Keterangan
  - Summary: Total Terbayar, Sisa Angsuran (dengan akumulasi otomatis)

- `resources/views/keuangan-tukang/pinjaman/index.blade.php`:
  - Filter Status: "Semua Status" untuk lihat pinjaman lama
  - Info Alert: Menjelaskan cara lihat data lama

---

## ✨ Fitur yang Sudah Berfungsi

### Di Lokal (Testing)
- ✅ Server running: `http://127.0.0.1:8000/keuangan-tukang/pinjaman`
- ✅ Database migration: Successful (Exit Code: 0)
- ✅ Laporan cicilan: Menampilkan dengan akumulasi
- ✅ Filter pinjaman lama: Working
- ✅ Toggle potongan: Recording history

### Di Server (Perlu Deploy)
- ⏳ Database migration (jalankan: `php artisan migrate --force`)
- ⏳ Cache clear (jalankan: `php artisan cache:clear`)
- ⏳ Config clear (jalankan: `php artisan config:clear`)

---

## 🔍 Testing Checklist

Setelah deploy, test fitur berikut:

- [ ] Buka `/keuangan-tukang/pinjaman`
- [ ] Klik filter "Semua Status" - harus tampil pinjaman lama
- [ ] Klik "Lihat Detail" pada pinjaman aktif
- [ ] Cek tabel "Laporan Cicilan Pinjaman"
  - [ ] Cicilan Ke: nomor urut
  - [ ] Tanggal Pembayaran: minggu range
  - [ ] Sisa Angsuran: terakumulasi jika TUNDA
  - [ ] Status: BAYAR (hijau) atau TUNDA (kuning)
- [ ] Toggle "Status Potongan Otomatis"
  - [ ] Status berubah di tabel
  - [ ] History terecord di laporan
- [ ] Cek section "Toggle Notifikasi WA" (jika ada)

---

## 📞 Support

Jika ada error saat deploy:

1. **Migration Error**: 
   ```bash
   php artisan migrate:refresh --seed
   # atau
   php artisan migrate:rollback
   php artisan migrate
   ```

2. **Cache Issue**:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   php artisan config:clear
   ```

3. **Permission Error**:
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

---

## 📌 Quick Links

- **GitHub**: https://github.com/yandimulyadi331-jpg/BUMISULTAN
- **Server**: https://bumisultan.com/keuangan-tukang/pinjaman
- **Termius SSH**: root@103.168.172.50
- **Local Dev**: http://127.0.0.1:8000/keuangan-tukang/pinjaman

---

**Last Updated**: 29 Jan 2026
**Deployed By**: GitHub Copilot (Automated)
