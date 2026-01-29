# Fitur Notifikasi & Penagihan Pinjaman Jatuh Tempo

## 📋 Ringkasan Fitur

Fitur ini menambahkan sistem notifikasi dan penagihan pinjaman yang **jatuh tempo** (sudah melewati tanggal jatuh tempo) di dashboard utama. Serupa dengan fitur "Karyawan Ulang Tahun", sistem ini akan:

1. **Menampilkan tabel pinjaman jatuh tempo** di dashboard dengan data detail
2. **Mengirim notifikasi WhatsApp** otomatis ke peminjam dengan template penagihan profesional
3. **Melampirkan PDF detail pinjaman** untuk referensi peminjam
4. **Mencatat history** pengiriman notifikasi untuk audit trail

---

## 🛠️ Komponen yang Diimplementasikan

### 1. **Model Enhancement** (`app/Models/Pinjaman.php`)

#### Method Baru:
```php
// Cek apakah pinjaman sudah jatuh tempo
public function isJatuhTempo(): bool

// Ambil cicilan yang sudah jatuh tempo
public function getCicilanJatuhTempo()

// Ambil cicilan pertama yang jatuh tempo
public function getCicilanPertamaJatuhTempo()

// Hitung hari tertunda untuk cicilan pertama
public function getHariTertundaAttribute(): int

// Scope untuk query pinjaman jatuh tempo
public function scopeJatuhTempo($query)
```

**Logika:**
- Pinjaman dianggap jatuh tempo jika:
  - Status = `dicairkan` atau `berjalan`
  - Ada cicilan dengan status ≠ `lunas` dan tanggal jatuh tempo ≤ hari ini

---

### 2. **Controller Enhancement** (`app/Http/Controllers/DashboardController.php`)

#### Method Baru:
```php
// Di method index() Dashboard Admin:
$data['pinjamanJatuhTempo'] = Pinjaman::jatuhTempo()
    ->with(['karyawan', 'cicilan'])
    ->orderBy('tanggal_pencairan', 'asc')
    ->get()
    ->map(function($pinjaman) { ... });

// Endpoint untuk mengirim penagihan via WhatsApp
public function kirimPenangihanPinjaman(Request $request)
```

**Fitur:**
- Mengambil semua pinjaman jatuh tempo dengan detail cicilan
- Mengirim pesan WhatsApp dengan template profesional
- Generate dan attach PDF pinjaman
- Log history pengiriman notifikasi

---

### 3. **Route Addition** (`routes/web.php`)

```php
Route::post('/dashboard/kirim-penagihan-pinjaman', 'kirimPenangihanPinjaman')
    ->name('dashboard.kirim.penagihan.pinjaman');
```

---

### 4. **UI Component** (`resources/views/dashboard/dashboard.blade.php`)

#### Bagian Baru:
- **Section "Pinjaman Jatuh Tempo"** di dashboard (sebelum KPI Crew section)
- **Tabel responsive** dengan kolom:
  - No. Pinjaman
  - Nama Peminjam
  - Cicilan Ke
  - Nominal Cicilan
  - Tanggal Jatuh Tempo
  - Tertunda (hari) - dengan badge warna berdasarkan durasi
  - Sisa Pinjaman
  - Progress Bar (% pembayaran)
  - Aksi (Tombol WhatsApp & Lihat Detail)

#### Notifikasi Status:
- **Hijau**: Jika tidak ada pinjaman jatuh tempo
- **Merah**: Jika ada pinjaman yang tertunda
- Badge warna dinamis berdasarkan hari tertunda:
  - 🔵 **Info** (< 14 hari)
  - 🟡 **Warning** (14-30 hari)
  - 🔴 **Danger** (> 30 hari)

---

### 5. **JavaScript Function** (`resources/views/dashboard/dashboard.blade.php`)

```javascript
function kirimPenangihanPinjaman(pinjamanId, namaPeminjam)
```

**Fitur:**
- Konfirmasi sebelum mengirim pesan
- Loading indicator saat proses
- Toast notification hasil pengiriman
- Auto-reload halaman setelah berhasil

---

## 📝 Template Pesan WhatsApp

```
💼 *NOTIFIKASI PENAGIHAN PINJAMAN* 💼
_Dari: Manajemen Keuangan Bumi Sultan Properti_

👤 *DATA PEMINJAM:*
Nama: *[Nama Peminjam]*
No. Pinjaman: *[Nomor Pinjaman]*

📊 *DETAIL CICILAN:*
Cicilan Ke: *[Cicilan Ke]*
Nominal Cicilan: *Rp [Nominal]*
Tgl Jatuh Tempo: *[Tanggal]*
Status: *⚠️ TERTUNDA [N] HARI*

💰 *RINGKASAN PINJAMAN:*
Total Pinjaman: *Rp [Total]*
Sisa Pinjaman: *Rp [Sisa]*
Terbayar: [Persentase]% ✅

⚠️ *TINDAKAN YANG DIPERLUKAN:*
Kami dengan hormat meminta Bapak/Ibu segera melakukan pembayaran cicilan...

📝 *INFORMASI PEMBAYARAN:*
• Pembayaran dapat dilakukan melalui transfer bank ke rekening yang terdaftar
• Detail rekening tujuan tersedia dalam dokumen pinjaman (file PDF terlampir)
• Jika ada kendala pembayaran, silakan hubungi bagian keuangan
• Ketertundaan pembayaran dapat mempengaruhi catatan kredit Anda

📎 *LAMPIRAN DOKUMEN:*
File PDF detail pinjaman sudah kami sertakan untuk referensi Anda.

Terima kasih atas perhatian dan kerjasama Anda.

*Regards,*
Tim Manajemen Keuangan
🏢 Bumi Sultan Properti
```

---

## 🔄 Alur Kerja

### 1. **Tampilan Data**
```
Dashboard Admin → Pinjaman Jatuh Tempo Section
│
├─ Ambil pinjaman dengan status = dicairkan/berjalan
├─ Filter cicilan yang status ≠ lunas dan tanggal jatuh tempo ≤ hari ini
└─ Tampilkan di tabel dengan detail lengkap
```

### 2. **Pengiriman Notifikasi**
```
Klik Tombol WhatsApp
│
├─ Konfirmasi dialog
├─ Validasi nomor HP
├─ Generate template pesan profesional
├─ Generate PDF pinjaman (temp storage)
├─ Dispatch SendWaMessage job
├─ Log history pengiriman
└─ Cleanup temporary files
```

### 3. **Audit Trail**
```
Model Pinjaman → PinjamanHistory
│
├─ aksi: kirim_notifikasi_wa_tertunda
├─ status_lama: notifikasi_pending
├─ status_baru: notifikasi_terkirim
├─ keterangan: Detail pengiriman
└─ data_perubahan: JSON dengan metadata
```

---

## 🎯 Fitur Utama

### ✅ Deteksi Otomatis Pinjaman Jatuh Tempo
- Query scope `jatuhTempo()` untuk filter data
- Method `isJatuhTempo()` untuk validasi
- Attribute `hari_tertunda` untuk perhitungan otomatis

### ✅ Notifikasi WhatsApp Profesional
- Template sesuai konteks Bumi Sultan Properti
- Format berkalimat profesional dan sopan
- Include detail pinjaman dan cicilan lengkap
- Instruksi pembayaran jelas

### ✅ Attachment PDF Detail Pinjaman
- Generate PDF otomatis menggunakan view `pinjaman.formulir-pdf`
- Simpan ke temporary storage
- Attach ke pesan WhatsApp (jika gateway support)
- Auto cleanup file setelah pengiriman

### ✅ Audit & History
- Setiap pengiriman dicatat di `pinjaman_history`
- Metadata: nomor telpon, cicilan, nominal, hari tertunda
- Bisa digunakan untuk laporan dan audit trail

### ✅ UI/UX Modern
- Tabel responsive dengan sorting & filtering
- Badge warna dinamis berdasarkan durasi tertunda
- Progress bar visual untuk persentase pembayaran
- Toast notification feedback
- Modal konfirmasi sebelum pengiriman

---

## 🚀 Cara Menggunakan

### Step 1: Akses Dashboard
```
Login → Dashboard (Admin) → Scroll ke section "Pinjaman Jatuh Tempo"
```

### Step 2: Lihat Daftar Pinjaman Jatuh Tempo
```
Tabel akan menampilkan semua pinjaman dengan:
- Detail cicilan yang tertunda
- Berapa lama tertunda
- Sisa pinjaman
- Progress pembayaran
```

### Step 3: Kirim Notifikasi WhatsApp
```
Click Tombol WhatsApp (ikon 📱) → Konfirmasi → Pesan terkirim
```

### Step 4: Verifikasi Pengiriman
```
Cek di:
- Toast notification (success/error)
- Pinjaman → Detail → Tab History (untuk audit trail)
- WhatsApp peminjam (verifikasi manual)
```

---

## 📊 Data Yang Ditampilkan

| Kolom | Deskripsi | Format |
|-------|-----------|--------|
| No. Pinjaman | Nomor identitas pinjaman | Text (PNJ-YYYYMM-XXXX) |
| Nama Peminjam | Nama lengkap peminjam | Text |
| Cicilan Ke | Urutan cicilan yang jatuh tempo | Number |
| Nominal | Jumlah cicilan yang jatuh tempo | Currency (Rp) |
| Tgl Jatuh Tempo | Tanggal jatuh tempo cicilan | Date (DD-MM-YYYY) |
| Tertunda | Jumlah hari keterlambatan | Badge (color-coded) |
| Sisa Pinjaman | Sisa pokok pinjaman | Currency (Rp) |
| Progress | Persentase pembayaran total | Progress bar |

---

## 🔧 Konfigurasi & Customization

### 1. Ubah Template Pesan
**File:** `app/Http/Controllers/DashboardController.php` (method `kirimPenangihanPinjaman`)

### 2. Ubah Warna Badge
**File:** `resources/views/dashboard/dashboard.blade.php` (CSS classes)

```blade
@php
$badgeColor = $hariTertunda > 30 ? 'danger' : ($hariTertunda > 14 ? 'warning' : 'info');
@endphp
```

### 3. Tambah Kolom Tabel
**File:** `resources/views/dashboard/dashboard.blade.php` (di table section)

---

## 🐛 Troubleshooting

### Masalah: Pinjaman Tidak Muncul di Tabel
**Solusi:**
1. Pastikan pinjaman memiliki status = `dicairkan` atau `berjalan`
2. Pastikan ada cicilan dengan status ≠ `lunas`
3. Pastikan tanggal jatuh tempo cicilan ≤ hari ini
4. Clear cache: `php artisan cache:clear`

### Masalah: WhatsApp Gagal Terkirim
**Solusi:**
1. Cek konfigurasi WA di `Pengaturan Umum`
2. Cek format nomor HP (harus valid, bukan 0)
3. Cek job queue: `php artisan queue:work`
4. Lihat log: `storage/logs/laravel.log`

### Masalah: PDF Tidak Attach
**Solusi:**
1. Pastikan view `pinjaman.formulir-pdf` ada
2. Pastikan folder `storage/app/temp` writable
3. Cek error di browser console & server logs

---

## 📋 Files Modified / Created

### Modified Files:
- ✅ `app/Models/Pinjaman.php` - Add methods untuk deteksi jatuh tempo
- ✅ `app/Http/Controllers/DashboardController.php` - Add logic & endpoint
- ✅ `routes/web.php` - Add route untuk endpoint
- ✅ `resources/views/dashboard/dashboard.blade.php` - Add UI section & JS

### Import Tambahan:
- `use Carbon\Carbon;` - Untuk datetime manipulation
- `use PDF;` - Untuk generate PDF

---

## 🔐 Security & Best Practices

✅ **CSRF Protection** - Menggunakan token CSRF di form  
✅ **Input Validation** - Validasi pinjaman_id sebelum proses  
✅ **Error Handling** - Try-catch untuk error handling  
✅ **Logging** - Setiap aksi dicatat di history  
✅ **Temporary Files** - PDF cleanup untuk prevent storage bloat  
✅ **Queue Job** - WhatsApp terkirim via job queue (non-blocking)  

---

## 📈 Future Enhancements

- [ ] Batch send ke multiple pinjaman
- [ ] Schedule otomatis setiap hari (cronjob)
- [ ] Template message dapat dikustomisasi per organisasi
- [ ] Support multiple attachment (PDF + Excel)
- [ ] SMS fallback jika WhatsApp gagal
- [ ] Dashboard analytics untuk tracking pengiriman
- [ ] Integration dengan payment reminder system

---

## 📞 Support & Documentation

Untuk pertanyaan atau bantuan lebih lanjut, silakan:
1. Cek documentation ini
2. Lihat code comments di file implementation
3. Check server logs: `storage/logs/laravel.log`
4. Review model relationships & history logs

---

**Last Updated:** 2026-01-20  
**Version:** 1.0  
**Status:** ✅ Production Ready
