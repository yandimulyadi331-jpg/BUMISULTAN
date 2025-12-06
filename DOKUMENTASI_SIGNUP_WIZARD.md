# 📋 DOKUMENTASI SIGNUP MULTI-STEP WIZARD

## 🎯 OVERVIEW

Sistem signup telah diubah dari **single page form** menjadi **multi-step wizard** dengan desain **glassmorphism** yang matching dengan halaman login.

---

## ✨ FITUR UTAMA

### 1. **Multi-Step Navigation (5 Langkah)**

#### **STEP 1: Data Pribadi**
- NIK Display
- No. KTP (16 digit)
- Nama Lengkap
- Tempat & Tanggal Lahir
- Alamat Lengkap
- Jenis Kelamin
- No. HP
- Status Perkawinan
- Pendidikan Terakhir

#### **STEP 2: Data Pekerjaan**
- Kantor Cabang
- Departemen
- Jabatan
- Tanggal Masuk
- Status Karyawan (Kontrak/Tetap)

#### **STEP 3: Foto Profil**
- 1 foto untuk tampilan profil karyawan
- Live camera preview
- Tombol "Buka Kamera", "Ambil Foto", "Ambil Ulang"
- Tersimpan sebagai: `{NIK}_profil.jpg`

#### **STEP 4: Foto Wajah Absensi**
- 5 foto dari berbagai sudut:
  1. Depan (1_front.jpg)
  2. Kiri (2_left.jpg)
  3. Kanan (3_right.jpg)
  4. Atas (4_up.jpg)
  5. Bawah (5_down.jpg)
- Progress bar visual
- Preview grid untuk semua foto
- Popup instruksi untuk setiap posisi

#### **STEP 5: Password**
- Password (minimal 6 karakter)
- Konfirmasi Password
- Ringkasan info bahwa data siap dikirim

---

## 🎨 DESAIN GLASSMORPHISM

### Visual Features:
- ✅ Background image dengan gradient overlay
- ✅ Transparent container dengan backdrop-filter blur
- ✅ Border gradient subtle
- ✅ White text dengan shadow untuk readability
- ✅ Smooth animations dan transitions
- ✅ Progress indicator dengan completed/active states
- ✅ Form controls dengan transparent background

### Matching dengan Login Page:
- Background image yang sama
- Gradient overlay yang konsisten
- Blur effect dan transparency level sama
- Color scheme matching (purple-blue gradient)
- Typography consistency

---

## 🔄 ALUR PENGGUNAAN

```
┌─────────────────────────────────────────────────┐
│ User buka http://127.0.0.1:8000/signup          │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ STEP 1: Isi Data Pribadi                        │
│ - Validasi: Semua field required wajib diisi    │
│ - Tombol: "Berikutnya"                          │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ STEP 2: Isi Data Pekerjaan                      │
│ - Validasi: Dropdown harus dipilih              │
│ - Tombol: "Kembali" / "Berikutnya"              │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ STEP 3: Ambil Foto Profil                       │
│ - Klik "Buka Kamera"                            │
│ - Posisikan wajah                               │
│ - Klik "Ambil Foto"                             │
│ - Validasi: Foto harus diambil                  │
│ - Tombol: "Kembali" / "Berikutnya"              │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ STEP 4: Ambil 5 Foto Wajah                      │
│ - Klik "Mulai Rekam Wajah"                      │
│ - Ikuti popup instruksi:                        │
│   1. Hadap DEPAN → Ambil Foto                   │
│   2. Tengok KIRI → Ambil Foto                   │
│   3. Tengok KANAN → Ambil Foto                  │
│   4. Lihat ATAS → Ambil Foto                    │
│   5. Lihat BAWAH → Ambil Foto                   │
│ - Validasi: 5 foto harus lengkap                │
│ - Progress bar: 0% → 20% → 40% → 60% → 80% → 100%│
│ - Tombol: "Kembali" / "Berikutnya"              │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ STEP 5: Buat Password                           │
│ - Input password                                │
│ - Konfirmasi password                           │
│ - Validasi: Kedua password harus match          │
│ - Tombol: "Kembali" / "Daftar Sekarang"        │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ SUBMIT DATA                                     │
│ - Loading indicator muncul                      │
│ - Data dikirim ke SignupControllerImproved     │
│ - Generate NIK otomatis                         │
│ - Simpan foto profil                            │
│ - Simpan 5 foto wajah                           │
│ - Insert ke tabel karyawan                      │
│ - Insert 5 record ke tabel karyawan_wajah       │
│ - status_aktif_karyawan = 0 (pending)          │
└─────────────────────┬───────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────┐
│ Redirect ke Login dengan pesan sukses           │
│ "Pendaftaran berhasil! Tunggu approval admin"   │
└─────────────────────────────────────────────────┘
```

---

## 📂 STRUKTUR FILE

```
resources/views/auth/
├── signup_wizard.blade.php         ← File baru (multi-step)
├── signup_improved.blade.php       ← Versi lama (single page)
└── loginuser.blade.php             ← Login page

app/Http/Controllers/
└── SignupControllerImproved.php    ← Controller (updated ke wizard view)

routes/
└── web.php                         ← Route /signup

storage/app/public/
├── karyawan/
│   └── {NIK}_profil.jpg           ← Foto profil (1 file per user)
└── karyawan/wajah/
    ├── {NIK}_1_front.jpg          ← Foto wajah depan
    ├── {NIK}_2_left.jpg           ← Foto wajah kiri
    ├── {NIK}_3_right.jpg          ← Foto wajah kanan
    ├── {NIK}_4_up.jpg             ← Foto wajah atas
    └── {NIK}_5_down.jpg           ← Foto wajah bawah
```

---

## 🗄️ DATABASE

### Tabel: `karyawan`
```sql
INSERT INTO karyawan (
    nik,                    -- Auto-generated: YYMM + 5 digit
    foto,                   -- Filename: {NIK}_profil.jpg
    nama_karyawan,
    no_ktp,
    status_aktif_karyawan,  -- 0 = Pending approval
    ...
)
```

### Tabel: `karyawan_wajah`
```sql
-- 5 records per user
INSERT INTO karyawan_wajah (nik, wajah) VALUES
    ('251100001', '251100001_1_front.jpg'),
    ('251100001', '251100001_2_left.jpg'),
    ('251100001', '251100001_3_right.jpg'),
    ('251100001', '251100001_4_up.jpg'),
    ('251100001', '251100001_5_down.jpg');
```

---

## 🎯 VALIDASI

### JavaScript Validation (Client-Side)
1. **Per Step Validation:**
   - Step 1-2: Semua required fields harus diisi
   - Step 3: Foto profil harus diambil
   - Step 4: 5 foto wajah harus lengkap
   - Step 5: Password min 6 karakter, harus match

2. **Navigation Control:**
   - Tombol "Berikutnya" disabled jika validasi gagal
   - Tombol "Kembali" selalu enabled (kecuali Step 1)

### Laravel Validation (Server-Side)
```php
$request->validate([
    'nik_show' => 'required',
    'no_ktp' => 'required|unique:karyawan,no_ktp',
    'nama_karyawan' => 'required',
    'foto_profil' => 'required',
    'foto_wajah_multiple' => 'required',
    'password' => 'required|min:6|confirmed',
    // ... dll
]);
```

---

## 🎨 CSS CLASSES & COMPONENTS

### Step Indicator
```css
.step-indicator          /* Container untuk semua steps */
.step-item               /* Individual step */
.step-item.active        /* Step yang sedang aktif */
.step-item.completed     /* Step yang sudah selesai */
.step-circle             /* Lingkaran nomor step */
.step-label              /* Label text step */
```

### Form Components
```css
.form-control            /* Input fields */
.form-group              /* Form group container */
.form-row                /* Grid layout untuk 2 kolom */
.camera-preview          /* Video/canvas preview */
.multi-photos            /* Grid untuk 5 foto */
.photo-slot              /* Slot individual foto */
.photo-slot.filled       /* Slot yang sudah ada foto */
```

### Buttons
```css
.btn                     /* Base button */
.btn-primary             /* Primary action (blue gradient) */
.btn-success             /* Success action (green gradient) */
.btn-warning             /* Warning action (orange gradient) */
.btn-secondary           /* Secondary action (transparent) */
```

---

## 🔧 CUSTOMIZATION

### Mengubah Jumlah Steps
1. Update HTML: Tambah/kurangi div `.step-content`
2. Update JavaScript: Ubah `const totalSteps = 5`
3. Update Step Indicator: Tambah/kurangi `.step-item`

### Mengubah Warna Tema
```css
/* Gradient utama */
background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);

/* Success color */
background: linear-gradient(135deg, #a8e063, #56ab2f);

/* Ubah sesuai brand color perusahaan */
```

### Mengubah Background Image
```html
<style>
    body {
        background: url('{{ asset('assets/login/images/YOUR_IMAGE.png') }}') no-repeat center center fixed;
    }
</style>
```

---

## 🐛 TROUBLESHOOTING

### Kamera tidak bisa diakses
**Penyebab:** Browser tidak punya permission atau HTTPS tidak aktif
**Solusi:**
- Gunakan HTTPS (atau localhost untuk testing)
- Allow camera permission di browser settings
- Cek browser console untuk error detail

### Foto tidak tersimpan
**Penyebab:** Storage folder tidak writable
**Solusi:**
```bash
php artisan storage:link
chmod -R 775 storage/app/public/karyawan
```

### Background image tidak muncul
**Penyebab:** File tidak ada atau path salah
**Solusi:**
1. Upload image ke `public/assets/login/images/background.png`
2. Atau update path di CSS

### Validasi gagal saat submit
**Penyebab:** Data tidak lengkap atau format salah
**Solusi:** Check browser console dan Laravel log untuk detail error

---

## 📱 RESPONSIVE DESIGN

### Breakpoints
- **Desktop:** > 768px - Layout 2 kolom
- **Mobile:** ≤ 768px - Layout 1 kolom

### Mobile Adjustments
- Step indicator: Font size lebih kecil
- Form row: Grid 1 kolom
- Button: Full width
- Camera preview: Aspect ratio maintained

---

## 🚀 DEPLOYMENT CHECKLIST

- [ ] Upload background image ke production
- [ ] Set storage permissions (775)
- [ ] Create storage symlink
- [ ] Test camera access dengan HTTPS
- [ ] Verify database tables (karyawan, karyawan_wajah)
- [ ] Test form submission end-to-end
- [ ] Test mobile responsive
- [ ] Backup database sebelum go-live

---

## 📊 METRICS & MONITORING

### Data yang Perlu Dimonitor:
1. **Conversion Rate:** Berapa % user yang complete signup
2. **Drop-off Points:** Step mana yang paling banyak ditinggalkan
3. **Photo Quality:** Apakah foto wajah cukup jelas untuk face recognition
4. **Approval Time:** Rata-rata waktu admin approve user baru

### Logging Points:
- Step completion (analytics)
- Photo upload success/failure
- Form validation errors
- Submission success/failure

---

## 🎓 BEST PRACTICES

1. **User Experience:**
   - Jangan paksa user mengisi ulang jika kembali ke step sebelumnya
   - Berikan feedback visual yang jelas untuk setiap action
   - Loading indicator saat submit data

2. **Security:**
   - Validasi di client dan server
   - Sanitize input data
   - Rate limiting untuk prevent spam

3. **Performance:**
   - Compress images sebelum upload (quality: 0.8)
   - Lazy load camera stream (hanya saat step aktif)
   - Minify CSS/JS di production

4. **Accessibility:**
   - Label yang jelas untuk screen readers
   - Keyboard navigation support
   - Color contrast yang cukup

---

## 📞 SUPPORT

Jika ada masalah atau pertanyaan:
1. Check Laravel log: `storage/logs/laravel.log`
2. Check browser console untuk JavaScript errors
3. Run verifikasi script: `php verifikasi_signup_improved.php`

---

**Created:** November 20, 2025  
**Version:** 2.0 (Multi-Step Wizard)  
**Author:** AI Assistant
