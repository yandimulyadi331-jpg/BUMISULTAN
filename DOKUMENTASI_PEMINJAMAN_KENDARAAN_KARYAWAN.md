# DOKUMENTASI FITUR PEMINJAMAN KENDARAAN - KARYAWAN
## Implementasi Lengkap dengan Workflow & Tracking

### 📋 RINGKASAN

Fitur peminjaman kendaraan telah diperbaiki dan diintegrasikan dengan halaman karyawan baru (`index_new.blade.php`). Sistem mengikuti alur yang sama dengan fitur kendaraan keluar/masuk dengan workflow approval yang terstruktur.

---

## 🎯 FITUR YANG TELAH DIPERBAIKI

### 1. **Route Management**
✅ Ditambahkan route baru untuk submit peminjaman dari halaman karyawan
✅ Route untuk return peminjaman
✅ Route terintegrasi dengan KendaraanKaryawanController

**Routes yang ditambahkan:**
```php
Route::prefix('kendaraan-karyawan')->controller(KendaraanKaryawanController::class)->group(function () {
    Route::get('/', 'index')->name('kendaraan.karyawan.index');
    Route::get('/new', 'indexNew')->name('kendaraan.karyawan.index.new'); // NEW
    Route::get('/{id}/detail', 'show')->name('kendaraan.karyawan.detail');
    
    // Submit actions from karyawan page
    Route::post('/submit-keluar', 'submitKeluarKendaraan')->name('kendaraan.karyawan.submit.keluar');
    Route::post('/submit-return', 'submitReturnKendaraan')->name('kendaraan.karyawan.submit.return');
    Route::post('/submit-pinjam', 'submitPeminjamanKendaraan')->name('kendaraan.karyawan.submit.pinjam'); // FIXED
    Route::post('/submit-return-pinjam', 'submitReturnPeminjaman')->name('kendaraan.karyawan.submit.return.pinjam');
    Route::post('/submit-service', 'submitServiceRequest')->name('kendaraan.karyawan.submit.service');
});
```

### 2. **Controller Methods**
✅ Fungsi `indexNew()` - Menampilkan list kendaraan dengan action buttons
✅ Fungsi `submitPeminjamanKendaraan()` - Sudah ada, sekarang bisa diakses dari route
✅ Fungsi `submitReturnPeminjaman()` - Untuk mengembalikan kendaraan yang dipinjam

### 3. **View Integration**
✅ Dashboard karyawan sekarang mengarah ke `kendaraan.karyawan.index.new`
✅ Modal peminjaman sudah terintegrasi dengan form lengkap
✅ Validasi otomatis dengan `canPerformAction('pinjam')`

---

## 📱 ALUR PEMINJAMAN KENDARAAN

### Dari Sisi Karyawan (Mobile)

#### 1. **Akses Menu Kendaraan**
```
Dashboard Karyawan → Manajemen Kendaraan → List Kendaraan
```

#### 2. **Pilih Kendaraan & Klik Pinjam**
- Tombol "Pinjam" akan **enabled** jika kendaraan tersedia
- Tombol **disabled** jika:
  - Kendaraan sedang dipinjam orang lain
  - Kendaraan sedang dalam proses keluar
  - Kendaraan sedang service

#### 3. **Form Peminjaman**
Modal akan muncul dengan field:
- **Tanggal Pinjam** (required)
- **Tanggal Kembali** (required)
- **Tujuan Penggunaan** (required)
- **Keperluan** (required, textarea)
- **No. HP** (required)
- **Jumlah Penumpang** (optional)
- **Keterangan Tambahan** (optional)
- **Tanda Tangan Digital** (signature pad)
- **Upload Foto** (optional, multiple files)

#### 4. **Submit & Workflow Dimulai**
Setelah submit:
```
Pengajuan → Menunggu Verifikasi Admin
```

#### 5. **Workflow Timeline**
Karyawan bisa melihat status real-time di card kendaraan:
```
┌─────────────────────────────────────┐
│ Proses: Peminjaman                  │
│ PJM-20251114-0001                   │
├─────────────────────────────────────┤
│ ✓ Pengajuan          [Completed]    │
│ ⏳ Verifikasi        [In Progress]  │
│ ○ Disetujui         [Pending]       │
│ ○ Diambil           [Pending]       │
│ ○ Dalam Penggunaan  [Pending]       │
│ ○ Selesai           [Pending]       │
└─────────────────────────────────────┘
```

#### 6. **Tombol Return Muncul**
Setelah disetujui dan diambil, tombol **"Klik Kembali"** akan muncul di card kendaraan.

#### 7. **Proses Pengembalian**
Klik tombol "Klik Kembali":
- Form return akan muncul
- Isi data pengembalian (km akhir, kondisi, dll)
- Submit → Workflow selesai

---

## 👨‍💼 ALUR DARI SISI ADMIN

### 1. **Melihat Pengajuan**
Admin akan melihat notifikasi ada pengajuan peminjaman baru.

### 2. **Verifikasi & Approval**
Admin bisa:
- ✅ **Approve** → Workflow lanjut ke tahap berikutnya
- ❌ **Reject** → Workflow berhenti, kendaraan kembali tersedia

### 3. **Monitoring Real-time**
Admin bisa melihat:
- Siapa yang meminjam
- Kapan mulai peminjaman
- Estimasi pengembalian
- Status saat ini (dengan/belum dikembalikan)
- Keterlambatan (jika ada)

### 4. **Data Lengkap Peminjaman**
Admin memiliki akses ke semua data:
```
/admin/kendaraan/{id}/peminjaman/index
```
Halaman ini menampilkan:
- Daftar semua peminjaman
- Filter by status
- Export PDF
- Edit/Delete (jika diperlukan)

---

## 🔧 STRUKTUR DATA

### Tabel: kendaraan_proses
Menyimpan workflow peminjaman:

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| kendaraan_id | bigint | FK ke kendaraan |
| jenis_proses | enum | 'keluar', 'pinjam', 'service' |
| kode_proses | varchar | PJM-20251114-0001 |
| user_id | bigint | User yang mengajukan |
| user_name | varchar | Nama user |
| status | enum | 'pending', 'approved', 'rejected', 'completed' |
| data_proses | json | Semua data form peminjaman |
| latitude_start | decimal | GPS lokasi peminjaman |
| longitude_start | decimal | GPS lokasi peminjaman |
| signature | varchar | Path file signature |
| created_at | timestamp | Waktu pengajuan |
| updated_at | timestamp | Waktu update terakhir |

### JSON Structure: data_proses
```json
{
  "nik": "12345",
  "nama_peminjam": "John Doe",
  "jabatan": "Staff IT",
  "departemen": "IT",
  "no_hp": "08123456789",
  "tanggal_pinjam": "2025-11-14",
  "tanggal_kembali": "2025-11-16",
  "tujuan_penggunaan": "Dinas ke Jakarta",
  "keperluan": "Meeting dengan klien",
  "jumlah_penumpang": 3,
  "keterangan": "Perlu kendaraan untuk 3 orang",
  "latitude_pinjam": -6.2088,
  "longitude_pinjam": 106.8456,
  "foto": ["foto1.jpg", "foto2.jpg"]
}
```

---

## 📊 VALIDASI & BUSINESS LOGIC

### Method: canPerformAction('pinjam')
Cek apakah kendaraan bisa dipinjam:

```php
public function canPerformAction($action)
{
    // Check if there's active process
    if (!$this->hasActiveProcess()) {
        return true; // No active process, all actions allowed
    }
    
    $activeProses = $this->proses()->where('status', '!=', 'completed')->latest()->first();
    
    if (!$activeProses) {
        return true;
    }
    
    // Cannot perform any action if vehicle is in active process
    return false;
}
```

### Method: getBlockingReason()
Memberikan alasan mengapa kendaraan tidak bisa dipinjam:

```php
public function getBlockingReason()
{
    if (!$this->hasActiveProcess()) {
        return null;
    }
    
    $prosesDetails = $this->getActiveProcessDetails();
    
    return "Kendaraan sedang dalam proses: " . 
           ucfirst($prosesDetails['jenis_proses']) . 
           " oleh " . $prosesDetails['user_name'];
}
```

---

## 🎨 UI/UX FEATURES

### 1. **Status Badge**
Setiap card kendaraan memiliki badge status:
- 🟢 **Tersedia** - Hijau
- 🔵 **Keluar** - Biru
- 🟠 **Dipinjam** - Orange
- 🔴 **Service** - Merah

### 2. **Disabled Button State**
Tombol yang disabled akan:
- Warna abu-abu
- Cursor not-allowed
- Opacity 0.5

### 3. **Workflow Timeline**
- Node berwarna sesuai status
- ✓ Checkmark untuk completed
- ⏳ Hourglass untuk in progress
- ○ Circle untuk pending
- ✗ Cross untuk rejected

### 4. **Alert Banner**
Jika kendaraan sedang digunakan orang lain:
```
⚠️ Sedang digunakan oleh: Ahmad Rizki
```

### 5. **Signature Pad**
- Canvas responsive
- Touch-action: none (untuk mobile)
- Tombol "Hapus Tanda Tangan"
- Validasi: signature tidak boleh kosong

---

## 🧪 TESTING

### Test Case 1: Pengajuan Peminjaman Sukses
1. Buka halaman kendaraan karyawan
2. Pilih kendaraan dengan status "Tersedia"
3. Klik tombol "Pinjam"
4. Isi form lengkap
5. Tanda tangan digital
6. Submit
7. ✅ Workflow dimulai
8. ✅ Card menampilkan workflow timeline
9. ✅ Tombol "Pinjam" disabled

### Test Case 2: Tombol Disabled
1. Pilih kendaraan dengan status "Dipinjam"
2. ✅ Tombol "Pinjam" disabled
3. ✅ Tooltip/Pesan: "Kendaraan sedang dipinjam"

### Test Case 3: Return Peminjaman
1. Kendaraan yang user pinjam sudah disetujui
2. ✅ Tombol "Klik Kembali" muncul
3. Klik tombol
4. Isi form return
5. Submit
6. ✅ Workflow completed
7. ✅ Kendaraan kembali tersedia

---

## 📂 FILE STRUCTURE

```
app/
├── Http/Controllers/
│   └── KendaraanKaryawanController.php
│       ├── indexNew()                          [NEW]
│       ├── submitPeminjamanKendaraan()         [EXISTING]
│       └── submitReturnPeminjaman()            [EXISTING]
│
├── Models/
│   └── Kendaraan.php
│       ├── hasActiveProcess()
│       ├── getActiveProcessDetails()
│       ├── canPerformAction()
│       ├── getBlockingReason()
│       ├── startWorkflowProcess()
│       └── getWorkflowStages()
│
resources/views/
├── kendaraan/karyawan/
│   ├── index_new.blade.php                     [MAIN VIEW]
│   └── modals/
│       ├── pinjam.blade.php                    [MODAL FORM]
│       ├── return.blade.php                    [MODAL RETURN]
│       ├── keluar.blade.php
│       ├── service.blade.php
│       └── detail.blade.php
│
└── fasilitas/
    └── dashboard-karyawan.blade.php            [UPDATED LINK]
```

---

## 🚀 DEPLOYMENT CHECKLIST

- [x] Routes ditambahkan di `web.php`
- [x] Controller method `indexNew()` dibuat
- [x] Use statements ditambahkan (DB, Auth, Storage)
- [x] Dashboard karyawan link diupdate
- [x] Modal peminjaman terintegrasi
- [x] Validasi canPerformAction berfungsi
- [x] Workflow visual berfungsi
- [x] Signature pad berfungsi
- [x] GPS location capture
- [x] File upload support

---

## 📝 CATATAN PENTING

### 1. **Route Naming Convention**
```php
kendaraan.karyawan.submit.pinjam    // Submit peminjaman
kendaraan.karyawan.submit.return.pinjam  // Return peminjaman
```

### 2. **Middleware**
Semua route kendaraan-karyawan sudah protected dengan auth middleware.

### 3. **Permission**
Karyawan dengan role apapun bisa mengajukan peminjaman, tapi perlu approval admin.

### 4. **GPS Location**
Sistem akan capture GPS location saat:
- Submit peminjaman (latitude_pinjam, longitude_pinjam)
- Submit return (latitude_kembali, longitude_kembali)

### 5. **File Storage**
- Signature: `storage/kendaraan/signatures/`
- Foto peminjaman: `storage/kendaraan/peminjaman/`

---

## 🐛 TROUBLESHOOTING

### Error: Route not found
**Solusi:** Jalankan `php artisan route:clear`

### Error: Method not found
**Solusi:** Pastikan use statements sudah lengkap di controller

### Tombol tetap disabled
**Solusi:** Cek method `canPerformAction()` di model Kendaraan

### Signature tidak tersimpan
**Solusi:** Pastikan folder `storage/kendaraan/signatures/` ada dan writable

---

## ✅ KESIMPULAN

Fitur peminjaman kendaraan sekarang sudah **FULLY FUNCTIONAL** dengan:
- ✅ Route terintegrasi
- ✅ Modal form lengkap
- ✅ Workflow visual
- ✅ Validasi business logic
- ✅ GPS tracking
- ✅ Signature digital
- ✅ Real-time status update

Karyawan bisa dengan mudah:
1. Melihat daftar kendaraan
2. Mengajukan peminjaman
3. Tracking status approval
4. Mengembalikan kendaraan
5. Melihat history peminjaman

---

**Dokumentasi dibuat:** 14 November 2025
**Status:** ✅ IMPLEMENTASI LENGKAP
