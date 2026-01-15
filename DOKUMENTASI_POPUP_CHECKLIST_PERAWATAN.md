# Dokumentasi Pop-Up Notifikasi Checklist Perawatan

## Overview
Fitur ini menampilkan pop-up/modal notifikasi kepada karyawan saat mereka membuka halaman dashboard, khususnya ketika akan melakukan absen pulang. Modal ini mengingatkan karyawan bahwa masih ada checklist perawatan yang belum diselesaikan.

## Fitur Utama

### 1. Modal Notifikasi Checklist
- **Tampilan**: Modal dengan ikon warning berwarna merah (#e74c3c)
- **Konten**: Pesan bahwa checklist belum selesai dengan detail jumlah yang sudah selesai
- **Posisi**: Center screen dengan background overlay semi-transparent

### 2. Dua Tombol Aksi
- **Tombol "Pulang"** (Hijau #00D25B)
  - Menutup modal tanpa mengarahkan ke checklist
  - Memungkinkan karyawan untuk tetap melakukan absen pulang meskipun checklist belum selesai
  - Hanya menutup modal dan menandai notifikasi sudah ditampilkan

- **Tombol "Selesaikan Checklist"** (Biru #0090E7)
  - Mengarahkan ke halaman checklist perawatan harian
  - URL: `/perawatan/karyawan/checklist/harian`
  - Membuka dalam tab/window yang sama

## Implementasi Teknis

### File yang Dimodifikasi

#### 1. `resources/views/dashboard/karyawan.blade.php`
- **Penambahan Modal HTML**: Bagian modal dengan styling dan struktur
- **Penambahan CSS**: Styles untuk modal, buttons, animations (fade in, slide up, pulse)
- **Penambahan JavaScript**: Logika untuk menampilkan/menyembunyikan modal

#### 2. `routes/api.php`
- **Route Baru**: `POST /api/checklist/status`
- **Middleware**: `auth:sanctum` untuk keamanan
- **Controller**: `App\Http\Controllers\Api\ChecklistController@checkStatus`

#### 3. `app/Http/Controllers/Api/ChecklistController.php` (File Baru)
- **Method `checkStatus()`**: Mengecek status checklist karyawan
  - Mengambil data dari request
  - Mengecek apakah user sudah absen masuk hari ini
  - Mengecek apakah user sudah absen pulang (jika sudah, modal tidak ditampilkan)
  - Menghitung jumlah checklist harian yang belum selesai
  - Return JSON response dengan informasi checklist

#### 4. `routes/web.php`
- **Route Existing**: `perawatan/karyawan/checklist/{tipe}` sudah ada dan siap digunakan

### Logika Alur

```
1. Halaman Dashboard Karyawan Dimuat
   ↓
2. JavaScript window.load event terpicu
   ↓
3. checkChecklistStatus() dipanggil
   ↓
4. Fetch API ke /api/checklist/status
   ↓
5. Server cek:
   - Apakah user sudah absen masuk hari ini?
   - Apakah user sudah absen pulang? (Jika ya, jangan tampilkan modal)
   - Berapa jumlah checklist total harian?
   - Berapa jumlah checklist yang sudah selesai?
   ↓
6. Server return JSON dengan status
   ↓
7. JavaScript menerima response
   ↓
8. Jika hasIncompleteChecklist = true dan shouldShowModal = true
   → Tampilkan modal dengan informasi checklist
   Else
   → Jangan tampilkan modal
```

### Modal Styling

**CSS Classes:**
- `.modal-overlay`: Background overlay dan container
- `.modal-container`: Container modal dengan animation
- `.modal-content`: Konten modal
- `.modal-header`: Header dengan ikon
- `.modal-body`: Body dengan pesan
- `.modal-footer`: Footer dengan buttons
- `.btn`: Base styling untuk button
- `.btn-pulang`: Styling untuk tombol pulang (hijau)
- `.btn-selesaikan`: Styling untuk tombol selesaikan (biru)

**Animations:**
- `fadeIn`: Modal overlay muncul dengan fade (0.3s)
- `slideUp`: Modal container masuk dari bawah (0.3s)
- `pulse`: Icon warning bergerak naik-turun (1.5s infinite)

### API Response Format

**Success Response (Ada Checklist Belum Selesai):**
```json
{
  "hasIncompleteChecklist": true,
  "shouldShowModal": true,
  "checklistInfo": {
    "total": 50,
    "completed": 34,
    "remaining": 16,
    "percentageRemaining": 32,
    "percentageCompleted": 68
  },
  "message": "Masih ada 16 checklist yang belum selesai"
}
```

**Success Response (Semua Checklist Selesai):**
```json
{
  "hasIncompleteChecklist": false,
  "shouldShowModal": false,
  "checklistInfo": {
    "total": 50,
    "completed": 50,
    "remaining": 0,
    "percentageRemaining": 0,
    "percentageCompleted": 100
  },
  "message": "Semua checklist sudah selesai"
}
```

**Success Response (Sudah Absen Pulang):**
```json
{
  "hasIncompleteChecklist": false,
  "shouldShowModal": false,
  "message": "Sudah absen pulang"
}
```

## Kondisi Penampilan Modal

Modal hanya ditampilkan jika SEMUA kondisi berikut terpenuhi:

1. ✅ User sudah login
2. ✅ User adalah karyawan (memiliki relasi userkaryawan)
3. ✅ User sudah absen masuk hari ini (presensi.jam_in != null)
4. ✅ User BELUM absen pulang (presensi.jam_out == null)
5. ✅ Ada master checklist harian yang aktif
6. ✅ Ada checklist yang belum diselesaikan oleh user

## Testing

### Test Case 1: Checklist Belum Selesai
**Kondisi:**
- Karyawan sudah absen masuk
- Belum absen pulang
- Ada checklist yang belum selesai

**Expected Result:**
- Modal muncul
- Menampilkan jumlah checklist yang sudah dan belum selesai
- Kedua button berfungsi

### Test Case 2: Semua Checklist Selesai
**Kondisi:**
- Karyawan sudah absen masuk
- Belum absen pulang
- Semua checklist sudah selesai

**Expected Result:**
- Modal tidak muncul
- Dashboard normal ditampilkan

### Test Case 3: Sudah Absen Pulang
**Kondisi:**
- Karyawan sudah absen masuk dan pulang
- Checklist belum selesai

**Expected Result:**
- Modal tidak muncul
- Checklist tidak lagi relevan karena sudah pulang

### Test Case 4: Belum Absen Masuk
**Kondisi:**
- Karyawan belum absen masuk
- Checklist belum selesai

**Expected Result:**
- Modal tidak muncul
- Checklist baru relevant setelah absen masuk

## Browser Compatibility

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile Browser (iOS Safari, Chrome Mobile)

## Dark Mode Support

Modal sudah mendukung dark mode dengan CSS media query:
```css
@media (prefers-color-scheme: dark) {
    .modal-content {
        background: #2c3e50;
    }
}
```

## Security

- API endpoint dilindungi dengan middleware `auth:sanctum`
- CSRF token disertakan dalam fetch request
- User data diambil dari authenticated session
- Validasi NIK melalui relasi userkaryawan

## Performance

- API call dilakukan saat page load (window.load event)
- Fetch request async, tidak memblokir UI
- Modal rendering menggunakan vanilla JavaScript (no library)
- CSS animations menggunakan GPU acceleration (transform, opacity)

## Troubleshooting

### Modal Tidak Muncul
**Penyebab Kemungkinan:**
1. User belum login
2. User bukan karyawan (tidak memiliki userkaryawan)
3. User belum absen masuk
4. User sudah absen pulang
5. Tidak ada master checklist harian aktif
6. API endpoint error

**Solusi:**
- Check browser console untuk error messages
- Verify API response dengan network tab
- Pastikan checkbox "Harian" enabled di checklist config
- Pastikan ada master checklist harian yang aktif

### Modal Muncul Terus Menerus
**Penyebab Kemungkinan:**
1. API return error
2. Session expired saat modal ditampilkan
3. Page refresh tanpa clear sessionStorage

**Solusi:**
- Clear browser cache dan sessionStorage
- Login ulang
- Check server logs untuk error

### Button Tidak Berfungsi
**Penyebab Kemungkinan:**
1. JavaScript error di console
2. Event listener tidak terasosiasi
3. Route name salah

**Solusi:**
- Check browser console
- Verify route dengan `php artisan route:list`
- Ensure jQuery tidak conflict (menggunakan vanilla JS)

## Future Enhancements

1. **Mandatory Mode**: Option untuk membuat checklist mandatory sebelum absen pulang
2. **Scheduling**: Modal hanya muncul dalam rentang waktu tertentu
3. **Sound Notification**: Audio alert ketika modal muncul
4. **Reminder Timer**: Countdown timer atau recurring reminder
5. **Analytics**: Track berapa banyak karyawan yang dismiss vs complete checklist
6. **Customization**: Admin bisa customize pesan dan styling modal

## Maintenance

### Update Checklist Message
Edit di `resources/views/dashboard/karyawan.blade.php` bagian modal-body:
```html
<p>Tidak dapat absen pulang! Selesaikan checklist shift Anda...</p>
```

### Change Button Colors
Edit CSS di style section:
```css
.btn-pulang { background: linear-gradient(135deg, #00D25B 0%, #00B84A 100%); }
.btn-selesaikan { background: linear-gradient(135deg, #0090E7 0%, #0080D0 100%); }
```

### Modify Modal Animation
Edit CSS keyframes:
```css
@keyframes fadeIn { /* ... */ }
@keyframes slideUp { /* ... */ }
@keyframes pulse { /* ... */ }
```

## Version History

- **v1.0** (2026-01-15): Initial implementation
  - Modal popup untuk checklist belum selesai
  - Tombol Pulang dan Selesaikan Checklist
  - API endpoint untuk check status
  - Support dark mode
