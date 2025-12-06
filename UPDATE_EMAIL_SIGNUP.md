# Update Email di Form Signup

## Perubahan yang Dilakukan

### 1. Form Signup (View)
Menambahkan input field **Email** di halaman signup untuk keperluan pengiriman slip gaji:

✅ **File yang diupdate:**
- `resources/views/auth/signup_wizard.blade.php`
- `resources/views/auth/signup.blade.php`
- `resources/views/auth/signup_improved.blade.php`

**Lokasi Input Email:**
- Diletakkan setelah field **No. HP**
- Sebelum field **Status Perkawinan**
- Dilengkapi dengan informasi: "Email diperlukan untuk pengiriman slip gaji"

### 2. Controller Validation
**File:** `app/Http/Controllers/SignupController.php`

**Validasi yang ditambahkan:**
```php
'email' => 'required|email|unique:karyawan,email'
```

**Custom Error Messages:**
```php
'email.unique' => 'Email sudah terdaftar',
'email.email' => 'Format email tidak valid',
```

### 3. Database
**Migration:** `database/migrations/2025_11_25_000000_add_email_to_karyawan_table.php`

Kolom email sudah ditambahkan ke tabel `karyawan`:
- Tipe: `string(100)`
- Nullable: `yes`
- Posisi: setelah kolom `no_hp`

## Cara Penggunaan

1. **Buka halaman signup:** `http://127.0.0.1:8000/signup`

2. **Isi form Step 1 - Data Pribadi:**
   - NIK Display
   - No. KTP
   - Nama Lengkap
   - Tempat Lahir
   - Tanggal Lahir
   - Alamat
   - Jenis Kelamin
   - No. HP
   - **Email** ← (Field baru)
   - Status Perkawinan
   - Pendidikan Terakhir

3. **Lanjutkan ke Step berikutnya** untuk melengkapi data pekerjaan, foto profil, foto wajah, dan password.

## Manfaat

✅ Email karyawan akan tersimpan di database
✅ Slip gaji dapat dikirim via email secara otomatis
✅ Validasi email mencegah duplikasi dan format yang salah
✅ Informasi jelas kepada user tentang tujuan penggunaan email

## Status

✅ **COMPLETED** - Field email sudah ditambahkan dan terintegrasi penuh dengan sistem signup.

## Testing

Untuk test:
```bash
php artisan tinker

# Cek data karyawan terakhir dengan email
$karyawan = \App\Models\Karyawan::latest()->first();
echo $karyawan->email;
```

---
**Updated:** 27 November 2025
