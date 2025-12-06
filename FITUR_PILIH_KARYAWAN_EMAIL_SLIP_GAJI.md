# Fitur Pilih Karyawan untuk Kirim Email Slip Gaji

## 📋 Deskripsi
Fitur ini memungkinkan admin untuk **memilih karyawan tertentu** yang akan menerima slip gaji via email, tidak lagi otomatis ke semua karyawan.

## ✨ Fitur Utama

### 1. **Modal Pemilihan Karyawan**
- Form interaktif untuk memilih periode (bulan & tahun)
- Daftar lengkap karyawan yang memiliki email terdaftar
- Informasi detail: NIK, Nama, Email, Departemen

### 2. **Checkbox "Pilih Semua"**
- ✅ Centang sekali untuk memilih semua karyawan
- ✅ Visual yang jelas dengan badge "X dipilih" dan "Y total"
- ✅ Background highlight warna hijau untuk checkbox "Pilih Semua"

### 3. **Fitur Pencarian**
- 🔍 Search box untuk mencari nama karyawan
- Filter real-time saat mengetik
- Memudahkan menemukan karyawan spesifik

### 4. **Visual Feedback**
- ✅ Item yang dipilih mendapat background biru muda
- ✅ Icon check hijau muncul saat item dipilih
- ✅ Hover effect untuk pengalaman yang lebih baik
- ✅ Counter jumlah karyawan yang dipilih

### 5. **Validasi & Konfirmasi**
- Validasi periode harus dipilih
- Validasi minimal 1 karyawan harus dipilih
- Konfirmasi sebelum pengiriman email
- Loading indicator saat proses pengiriman

## 🎯 Cara Penggunaan

### Langkah 1: Buka Halaman Slip Gaji
```
Menu: Payroll > Slip Gaji
URL: http://127.0.0.1:8000/slipgaji
```

### Langkah 2: Klik Tombol "Kirim Email Slip Gaji"
- Tombol hijau dengan icon envelope
- Akan membuka modal pemilihan karyawan

### Langkah 3: Pilih Periode
1. Pilih **Bulan** dari dropdown
2. Pilih **Tahun** dari dropdown

### Langkah 4: Pilih Karyawan

**Opsi A - Pilih Semua:**
- Centang checkbox **"Pilih Semua Karyawan"** di bagian atas
- Semua karyawan akan terpilih otomatis

**Opsi B - Pilih Manual:**
- Centang checkbox di samping nama karyawan yang diinginkan
- Bisa pilih beberapa karyawan sekaligus

**Opsi C - Gunakan Pencarian:**
- Ketik nama karyawan di search box
- Pilih dari hasil filter

### Langkah 5: Kirim Email
1. Pastikan ada counter "X dipilih" menunjukkan jumlah > 0
2. Klik tombol **"Kirim Email Slip Gaji"**
3. Konfirmasi pengiriman di popup
4. Tunggu proses selesai

## 📁 File yang Dimodifikasi/Ditambahkan

### 1. View - Modal Pemilihan
**File:** `resources/views/payroll/slipgaji/select_karyawan.blade.php` (NEW)
- Form dengan checkbox untuk setiap karyawan
- Search functionality
- Checkbox "Pilih Semua"
- Counter dan visual feedback

### 2. View - Index Slip Gaji
**File:** `resources/views/payroll/slipgaji/index.blade.php` (MODIFIED)
- Update JavaScript untuk membuka modal pemilihan
- Mengganti SweetAlert dengan modal load

### 3. Controller
**File:** `app/Http/Controllers/SlipgajiController.php` (MODIFIED)

**Method Baru:**
```php
// Menampilkan halaman pilih karyawan
public function selectKaryawan()

// Kirim email ke karyawan yang dipilih
public function sendSlipGajiEmailSelected(Request $request)
```

### 4. Routes
**File:** `routes/web.php` (MODIFIED)

**Route Baru:**
```php
Route::get('/slipgaji/select-karyawan', 'selectKaryawan')->name('slipgaji.selectKaryawan');
Route::post('/slipgaji/send-email-selected', 'sendSlipGajiEmailSelected')->name('slipgaji.sendEmailSelected');
```

## 🔧 Teknologi yang Digunakan

### Frontend
- **Bootstrap 5** - Styling dan layout
- **jQuery** - DOM manipulation dan AJAX
- **SweetAlert2** - Konfirmasi dan notifikasi
- **Font Awesome** - Icons

### Backend
- **Laravel** - Framework PHP
- **Mail System** - Pengiriman email
- **DomPDF** - Generate PDF slip gaji

## 📊 Fitur Detail

### Validasi Form
```javascript
✅ Bulan dan tahun wajib dipilih
✅ Minimal 1 karyawan harus dipilih
✅ Email karyawan valid dan terdaftar
✅ Slip gaji periode tersebut harus ada
```

### Response Pengiriman
```json
{
  "success": true,
  "message": "Berhasil mengirim slip gaji ke X dari Y karyawan",
  "berhasil": 5,
  "gagal": 0,
  "errors": []
}
```

### Filter Karyawan
Hanya karyawan yang memenuhi kriteria:
- ✅ Memiliki email (tidak null & tidak kosong)
- ✅ Status aktif karyawan = 1
- ✅ Email valid dan terdaftar

## 🎨 UI/UX Features

### Visual Elements
1. **Badge Counter**
   - Badge biru: "X dipilih"
   - Badge abu-abu: "Y total"

2. **Checkbox Styling**
   - Size: 18px x 18px untuk visibility
   - Cursor pointer untuk UX

3. **Item Highlighting**
   - Background biru muda untuk item terpilih
   - Icon check hijau di sebelah kanan
   - Hover effect abu-abu terang

4. **Search Box**
   - Icon search di kiri
   - Placeholder yang jelas
   - Real-time filtering

### Responsive Design
- Scrollable list (max-height: 400px)
- Mobile-friendly checkbox size
- Bootstrap responsive grid

## 🔐 Security & Permission

**Permission Required:**
```php
@can('slipgaji.index')
```

**CSRF Protection:**
- Token Laravel untuk form submission
- AJAX dengan CSRF token

## 📝 Testing

### Test Case 1: Pilih Semua
1. Buka modal kirim email
2. Pilih bulan dan tahun
3. Centang "Pilih Semua Karyawan"
4. Verify semua checkbox terpilih
5. Kirim email

### Test Case 2: Pilih Manual
1. Buka modal kirim email
2. Pilih bulan dan tahun
3. Pilih 2-3 karyawan secara manual
4. Verify counter "3 dipilih"
5. Kirim email

### Test Case 3: Pencarian
1. Ketik nama karyawan di search box
2. Verify hanya karyawan yang cocok yang muncul
3. Pilih dari hasil pencarian
4. Kirim email

### Test Case 4: Validasi
1. Coba kirim tanpa pilih bulan/tahun
2. Verify error muncul
3. Coba kirim tanpa pilih karyawan
4. Verify error muncul

## 🚀 Deployment Checklist

- [x] Create view `select_karyawan.blade.php`
- [x] Update `index.blade.php` JavaScript
- [x] Add controller methods
- [x] Add routes
- [x] Clear all cache
- [x] Test functionality

## 📌 Notes

### Kelebihan Fitur Ini:
✅ **Fleksibilitas** - Pilih karyawan sesuai kebutuhan
✅ **Efisiensi** - Tidak perlu kirim ke semua karyawan
✅ **User-Friendly** - Interface intuitif dengan visual feedback
✅ **Pencarian Cepat** - Mudah menemukan karyawan spesifik
✅ **Batch Selection** - Opsi "Pilih Semua" untuk kecepatan

### Improvement Ideas (Future):
- [ ] Filter berdasarkan departemen
- [ ] Filter berdasarkan jabatan
- [ ] Save selection template
- [ ] Export list karyawan yang dipilih
- [ ] History pengiriman email per karyawan

## 🐛 Troubleshooting

### Issue: Modal tidak muncul
**Solution:** Clear cache dan reload
```bash
php artisan view:clear
php artisan route:clear
```

### Issue: Checkbox tidak berfungsi
**Solution:** Pastikan jQuery loaded
```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

### Issue: Email tidak terkirim
**Solution:** 
1. Cek konfigurasi SMTP di `.env`
2. Cek log di `storage/logs/laravel.log`
3. Pastikan email karyawan valid

---

## 📅 Update Log
**Date:** 27 November 2025  
**Version:** 1.0.0  
**Status:** ✅ COMPLETED  
**Developer:** System

## 🎯 Summary
Fitur pilih karyawan untuk kirim email slip gaji berhasil diimplementasikan dengan lengkap. Admin sekarang bisa memilih karyawan spesifik atau pilih semua dengan mudah melalui interface yang user-friendly.
