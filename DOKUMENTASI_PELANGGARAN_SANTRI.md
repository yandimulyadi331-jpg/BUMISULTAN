# DOKUMENTASI PELANGGARAN SANTRI

## 📋 Deskripsi
Modul **Pelanggaran Santri** adalah fitur untuk mencatat dan mengelola pelanggaran yang dilakukan santri di pondok pesantren. Sistem ini mencakup upload foto bukti, kategorisasi pelanggaran berdasarkan jumlah, dan laporan lengkap dengan status kode warna.

## 🎯 Fitur Utama

### 1. **Input Pelanggaran**
- Upload foto pelanggaran santri
- Input keterangan detail pelanggaran
- Sistem point pelanggaran
- Auto-fill data santri
- Real-time info total pelanggaran santri

### 2. **Manajemen Data**
- List semua pelanggaran dengan filter
- Edit data pelanggaran
- Hapus data pelanggaran
- Detail pelanggaran per santri

### 3. **Sistem Kategori Pelanggaran**

| Status | Jumlah Pelanggaran | Warna Badge | Keterangan |
|--------|-------------------|-------------|------------|
| **Ringan** | < 35x | 🟢 Hijau | Pelanggaran masih dalam batas wajar |
| **Sedang** | 35-74x | 🟡 Kuning | Perlu perhatian khusus |
| **Berat** | ≥ 75x | 🔴 Merah | Sudah berulang dan memerlukan tindakan serius |

### 4. **Laporan & Export**
- Laporan rekap per santri
- Filter berdasarkan tanggal
- Export ke PDF
- Export ke Excel
- Statistik status pelanggaran

## 📁 Struktur File

```
app/
├── Http/Controllers/
│   └── PelanggaranSantriController.php
├── Models/
│   └── PelanggaranSantri.php
└── Exports/
    └── PelanggaranSantriExport.php

database/
├── migrations/
│   └── 2025_11_08_000001_create_pelanggaran_santri_table.php
└── seeders/
    └── PelanggaranSantriPermissionSeeder.php

resources/views/pelanggaran-santri/
├── index.blade.php      # List pelanggaran
├── create.blade.php     # Form tambah pelanggaran
├── edit.blade.php       # Form edit pelanggaran
├── laporan.blade.php    # Laporan rekap
└── pdf.blade.php        # Template PDF

routes/
└── web.php              # Routes pelanggaran-santri
```

## 🗄️ Database Schema

### Tabel: `pelanggaran_santri`

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | FK ke users (santri) |
| nama_santri | string | Nama santri yang melanggar |
| nik_santri | string (nullable) | NIK santri |
| foto | string (nullable) | Path foto bukti pelanggaran |
| keterangan | text | Detail pelanggaran |
| tanggal_pelanggaran | date | Tanggal kejadian |
| point | integer | Point pelanggaran (default: 1) |
| created_by | bigint | FK ke users (petugas pencatat) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |
| deleted_at | timestamp (nullable) | Soft delete |

**Indexes:**
- `user_id` - untuk query per santri
- `tanggal_pelanggaran` - untuk filter tanggal

## 🔐 Permissions

```php
'pelanggaran-santri.index'    // View list pelanggaran
'pelanggaran-santri.create'   // Tambah pelanggaran
'pelanggaran-santri.edit'     // Edit pelanggaran
'pelanggaran-santri.delete'   // Hapus pelanggaran
'pelanggaran-santri.laporan'  // Akses laporan & export
```

## 🚀 Instalasi

### 1. Jalankan Migration

```bash
php artisan migrate
```

### 2. Setup Permissions

```bash
php artisan db:seed --class=PelanggaranSantriPermissionSeeder
```

### 3. Pastikan Storage Link Sudah Ada

```bash
php artisan storage:link
```

## 📖 Cara Penggunaan

### A. Menambah Pelanggaran

1. Login sebagai admin/super admin
2. Buka menu **Saung Santri** > **Pelanggaran Santri**
3. Klik tombol **"Tambah Pelanggaran"**
4. Isi form:
   - Pilih santri dari dropdown
   - Nama dan NIK akan otomatis terisi
   - Pilih tanggal pelanggaran
   - Input point pelanggaran
   - Upload foto (opsional, max 5MB)
   - Tulis keterangan detail pelanggaran
5. Sistem akan menampilkan info total pelanggaran santri
6. Klik **"Simpan Data"**

### B. Melihat List Pelanggaran

1. Buka menu **Pelanggaran Santri**
2. Gunakan filter:
   - **Santri**: Filter berdasarkan santri tertentu
   - **Tanggal**: Filter range tanggal
3. Data ditampilkan dengan:
   - Foto thumbnail (klik untuk preview)
   - Nama santri & NIK
   - Keterangan pelanggaran
   - Point pelanggaran
   - Total pelanggaran santri
   - Status (badge warna)
   - Dicatat oleh

### C. Melihat Laporan

1. Klik tombol **"Laporan"**
2. Gunakan filter tanggal jika diperlukan
3. Laporan menampilkan:
   - Statistik total santri bermasalah
   - Jumlah santri per status (berat, sedang, ringan)
   - Tabel rekap per santri dengan status warna
4. Export laporan:
   - **PDF**: Klik "Export PDF"
   - **Excel**: Klik "Export Excel"

### D. Status Warna di Data Santri

Status pelanggaran juga akan muncul di **Data Santri**:
- Santri dengan status **Merah** (≥75 pelanggaran) akan terlihat jelas
- Santri dengan status **Kuning** (35-74 pelanggaran)
- Santri dengan status **Hijau** (<35 pelanggaran)

## 🔧 Customization

### Mengubah Threshold Status

Edit file `app/Models/PelanggaranSantri.php`, method `getStatusPelanggaran()`:

```php
public static function getStatusPelanggaran($totalPelanggaran)
{
    if ($totalPelanggaran >= 75) {  // Ubah nilai 75
        return ['status' => 'Berat', ...];
    } elseif ($totalPelanggaran >= 35) {  // Ubah nilai 35
        return ['status' => 'Sedang', ...];
    } else {
        return ['status' => 'Ringan', ...];
    }
}
```

### Menambah Jenis Pelanggaran

Bisa menambahkan tabel terpisah untuk jenis pelanggaran:

```php
// Migration baru
Schema::create('jenis_pelanggaran', function (Blueprint $table) {
    $table->id();
    $table->string('nama_jenis');
    $table->integer('point_default');
    $table->text('keterangan')->nullable();
    $table->timestamps();
});

// Tambahkan field di pelanggaran_santri
$table->foreignId('jenis_pelanggaran_id')->nullable()
      ->constrained('jenis_pelanggaran');
```

## 📊 Integrasi dengan Data Santri

Untuk menampilkan status pelanggaran di halaman **Data Santri**:

```php
// Di controller Data Santri
use App\Models\PelanggaranSantri;

public function index()
{
    $santri = User::where('role', 'user')->get();
    
    foreach ($santri as $s) {
        $s->total_pelanggaran = PelanggaranSantri::totalPelanggaranSantri($s->id);
        $s->status_pelanggaran = PelanggaranSantri::getStatusPelanggaran($s->total_pelanggaran);
    }
    
    return view('santri.index', compact('santri'));
}
```

Di view:

```blade
<span class="badge {{ $santri->status_pelanggaran['badge'] }} text-white">
    {{ $santri->status_pelanggaran['status'] }}
    ({{ $santri->total_pelanggaran }}x)
</span>
```

## 🎨 UI Components

### Badge Status

```blade
<!-- Hijau (Ringan) -->
<span class="badge bg-green-500 text-white">Ringan</span>

<!-- Kuning (Sedang) -->
<span class="badge bg-yellow-500 text-white">Sedang</span>

<!-- Merah (Berat) -->
<span class="badge bg-red-500 text-white">Berat</span>
```

### Alert dengan Warna Latar

```blade
<!-- Hijau -->
<tr class="bg-green-100">

<!-- Kuning -->
<tr class="bg-yellow-100">

<!-- Merah -->
<tr class="bg-red-100">
```

## 🐛 Troubleshooting

### Foto tidak muncul
```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Cek permission folder storage
chmod -R 775 storage
chown -R www-data:www-data storage
```

### Permission denied
```bash
# Jalankan seeder permission
php artisan db:seed --class=PelanggaranSantriPermissionSeeder

# Atau manual assign permission ke role
php artisan tinker
>>> $role = Spatie\Permission\Models\Role::findByName('super admin');
>>> $role->givePermissionTo('pelanggaran-santri.index');
```

### Export PDF tidak berfungsi
```bash
# Install dompdf jika belum
composer require barryvdh/laravel-dompdf
```

## 📝 API Endpoints

### Get Total Pelanggaran Santri

```
GET /pelanggaran-santri/api/total/{userId}
```

Response:
```json
{
    "total": 45,
    "status": {
        "status": "Sedang",
        "warna": "warning",
        "badge": "bg-yellow-500",
        "text": "text-yellow-700",
        "bg": "bg-yellow-100"
    }
}
```

## 🔄 Future Enhancements

1. **Notifikasi Otomatis**
   - Kirim notifikasi ke wali santri saat status berubah
   - WhatsApp notification untuk status berat

2. **Sistem Poin Detail**
   - Kategori pelanggaran dengan poin berbeda
   - Ringan (1 poin), Sedang (3 poin), Berat (5 poin)

3. **Tindakan & Sanksi**
   - Pencatatan tindakan yang diambil
   - History sanksi per santri

4. **Dashboard Analytics**
   - Grafik trend pelanggaran per bulan
   - Top 10 santri bermasalah
   - Jenis pelanggaran terbanyak

5. **Mobile App Integration**
   - Upload foto langsung dari HP
   - Push notification real-time

## 👤 Role & Permission

Module ini dapat diakses oleh:
- ✅ Super Admin (full access)
- ✅ Admin (sesuai permission yang diberikan)
- ❌ User biasa (tidak bisa akses)

## 📞 Support

Jika ada kendala atau pertanyaan terkait modul ini, silakan hubungi tim IT Pondok Pesantren Bumi Sultan.

---

**Versi:** 1.0  
**Tanggal:** 8 November 2025  
**Developer:** Bumi Sultan Super App Team
