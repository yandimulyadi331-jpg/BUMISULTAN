# DOKUMENTASI FITUR KPI CREW
## Key Performance Indicator - Monitoring Kinerja Karyawan

### 📋 DESKRIPSI
Fitur KPI Crew adalah sistem monitoring dan penilaian kinerja karyawan secara realtime berdasarkan 3 indikator utama:
1. **Kehadiran** - Tingkat kehadiran karyawan
2. **Aktivitas** - Jumlah aktivitas yang diupload
3. **Perawatan** - Checklist perawatan yang diselesaikan

### 🎯 TUJUAN
- Memantau kinerja seluruh karyawan secara realtime
- Memberikan sistem poin yang adil dan transparan
- Memberikan peringkat berdasarkan performa
- Motivasi karyawan untuk meningkatkan kinerja

---

## 📊 SISTEM PENILAIAN

### Bobot Point per Indikator:

#### 1. Kehadiran (Primary Indicator)
- **Point**: 4 point per kehadiran
- **Maksimal**: 100 point (25 hari kerja)
- **Sumber Data**: Tabel `presensi`
- **Kriteria**: Data presensi dengan `jam_in` tidak null

#### 2. Aktivitas Karyawan
- **Point**: 3 point per aktivitas
- **Tidak ada batas maksimal**
- **Sumber Data**: Tabel `aktivitas_karyawan`
- **Kriteria**: Semua aktivitas yang diupload karyawan

#### 3. Perawatan/Checklist
- **Point**: 2 point per checklist
- **Tidak ada batas maksimal**
- **Sumber Data**: Tabel `perawatan_log`
- **Kriteria**: Status `completed`

### Formula Total Point:
```
Total Point = (Kehadiran × 4) + (Aktivitas × 3) + (Perawatan × 2)
```

### Sistem Ranking:
- Ranking diurutkan berdasarkan **Total Point tertinggi**
- Ranking 1, 2, 3 mendapat badge khusus (Trophy, Medal, Award)
- Ranking diperbarui otomatis saat recalculate

---

## 🗄️ DATABASE STRUCTURE

### Tabel: `kpi_crew`

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| id | BIGINT | Primary Key |
| nik | VARCHAR | Foreign Key ke `karyawan.nik` |
| bulan | INT | Bulan periode (1-12) |
| tahun | INT | Tahun periode |
| kehadiran_count | INT | Jumlah kehadiran |
| aktivitas_count | INT | Jumlah aktivitas |
| perawatan_count | INT | Jumlah perawatan |
| kehadiran_point | DECIMAL | Point dari kehadiran |
| aktivitas_point | DECIMAL | Point dari aktivitas |
| perawatan_point | DECIMAL | Point dari perawatan |
| total_point | DECIMAL | Total akumulasi point |
| ranking | INT | Peringkat karyawan |
| created_at | TIMESTAMP | Tanggal dibuat |
| updated_at | TIMESTAMP | Tanggal diupdate |

**Unique Constraint**: `nik + bulan + tahun`

---

## 🔧 STRUKTUR KODE

### 1. Migration
**File**: `database/migrations/2025_11_19_233226_create_kpi_crew_table.php`

### 2. Model
**File**: `app/Models/KpiCrew.php`

**Relasi**:
- `belongsTo` ke `Karyawan` melalui `nik`

**Scopes**:
- `periode($bulan, $tahun)` - Filter by periode
- `orderedByRanking()` - Order by ranking
- `orderedByPoint()` - Order by total point

**Accessor**:
- `periode_text` - Return formatted periode text

### 3. Controller
**File**: `app/Http/Controllers/KpiCrewController.php`

**Methods**:
- `index()` - Display KPI dashboard with rankings
- `show($nik)` - Show detail KPI for specific employee
- `calculateKpi($bulan, $tahun)` - Calculate KPI for all employees
- `updateRanking($bulan, $tahun)` - Update ranking based on points
- `recalculate()` - Force recalculate KPI

### 4. Routes
**File**: `routes/web.php`

```php
Route::middleware(['auth', 'role:super admin'])
    ->prefix('kpicrew')
    ->name('kpicrew.')
    ->controller(KpiCrewController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{nik}', 'show')->name('show');
        Route::post('/recalculate', 'recalculate')->name('recalculate');
    });
```

### 5. Views
**Folder**: `resources/views/kpicrew/`

#### `index.blade.php`
- Dashboard KPI dengan tabel ranking
- Statistik cards (Total Karyawan, Rata-rata Point, Top Performer)
- Filter periode (Bulan & Tahun)
- Tombol Hitung Ulang
- Detail breakdown per indikator

#### `show.blade.php`
- Detail KPI per karyawan
- Info karyawan lengkap
- Breakdown point per indikator
- Tabs detail: Kehadiran, Aktivitas, Perawatan
- Tabel detail untuk setiap indikator

---

## 🎨 TAMPILAN UI

### Dashboard KPI (`/kpicrew`)

**Header Cards**:
1. Total Karyawan
2. Rata-rata Point
3. Top Performer

**Tabel Utama**:
- Rank (dengan badge khusus untuk top 3)
- NIK
- Nama Karyawan
- Departemen
- Kehadiran (count + point)
- Aktivitas (count + point)
- Perawatan (count + point)
- Total Point
- Action (Detail button)

**Features**:
- Filter Periode (Modal)
- Tombol Hitung Ulang
- DataTable dengan search & pagination
- Keterangan perhitungan point

### Detail KPI (`/kpicrew/{nik}`)

**Top Section**:
- Info Karyawan lengkap
- Total Point (large display)
- Ranking dengan badge

**Summary Cards**:
- Card Kehadiran
- Card Aktivitas
- Card Perawatan

**Detail Tabs**:
1. **Tab Kehadiran**: Tabel detail presensi harian
2. **Tab Aktivitas**: Tabel detail aktivitas yang diupload
3. **Tab Perawatan**: Tabel detail checklist perawatan

---

## 📍 SIDEBAR MENU

**Lokasi**: Di bawah menu "Pusat Informasi"

```
└── Super Admin Menu
    ├── Pusat Informasi
    └── KPI Crew ⭐ (NEW)
```

**Icon**: `ti ti-chart-line`

**Akses**: Hanya Super Admin

---

## 🔄 CARA KERJA SISTEM

### 1. Perhitungan Otomatis
Saat mengakses halaman KPI Crew, sistem akan:
1. Check data KPI untuk periode yang dipilih
2. Jika belum ada atau perlu update, akan menghitung ulang
3. Mengambil data dari 3 sumber:
   - `presensi` untuk kehadiran
   - `aktivitas_karyawan` untuk aktivitas
   - `perawatan_log` untuk perawatan
4. Menghitung point berdasarkan bobot
5. Menyimpan ke tabel `kpi_crew`
6. Update ranking berdasarkan total point

### 2. Update Data
- KPI dapat di-recalculate manual dengan tombol "Hitung Ulang"
- Data existing **TIDAK DIHAPUS** - hanya di-update
- Setiap karyawan hanya punya 1 record per periode (unique constraint)

### 3. Filter Periode
- Default: Bulan dan tahun saat ini
- Dapat filter bulan dan tahun berbeda
- Data ditampilkan sesuai periode yang dipilih

---

## 🚀 CARA PENGGUNAAN

### Untuk Super Admin:

#### 1. Akses Menu KPI Crew
- Login sebagai Super Admin
- Klik menu "KPI Crew" di sidebar

#### 2. Lihat Dashboard
- Otomatis menampilkan KPI bulan berjalan
- Lihat statistik di card atas
- Lihat ranking di tabel

#### 3. Filter Periode
- Klik tombol "Filter Periode"
- Pilih Bulan dan Tahun
- Klik "Tampilkan"

#### 4. Hitung Ulang KPI
- Jika ingin refresh data terbaru
- Klik tombol "Hitung Ulang"
- Konfirmasi
- Data akan di-update

#### 5. Lihat Detail Karyawan
- Klik tombol "Eye" di kolom Action
- Akan menampilkan detail lengkap karyawan
- Lihat breakdown di setiap tab

---

## ⚠️ PENTING - KEAMANAN DATA

### Data Existing AMAN:
✅ Migration hanya CREATE table baru, tidak mengubah table existing
✅ Tidak ada foreign key cascade DELETE ke tabel lain
✅ Data presensi, aktivitas, dan perawatan tetap utuh
✅ Update menggunakan `updateOrCreate` - safe operation

### Foreign Key:
- `kpi_crew.nik` → `karyawan.nik` (onDelete: CASCADE)
- Jika karyawan dihapus, record KPI nya ikut terhapus
- Tidak mempengaruhi tabel lain

---

## 🧪 TESTING CHECKLIST

### ✅ Migration
- [x] Table `kpi_crew` created successfully
- [x] All columns exist dengan tipe data correct
- [x] Unique constraint working
- [x] Foreign key working

### ✅ Routes
- [x] `/kpicrew` accessible for super admin
- [x] `/kpicrew/{nik}` accessible
- [x] POST `/kpicrew/recalculate` working

### ✅ Functionality
- [x] Dashboard menampilkan data dengan benar
- [x] Perhitungan point akurat
- [x] Ranking di-update dengan benar
- [x] Filter periode working
- [x] Detail karyawan lengkap
- [x] Tabs switching working

### ✅ UI/UX
- [x] Menu sidebar muncul di posisi yang tepat
- [x] Cards statistik tampil dengan benar
- [x] Tabel responsive dan sortable
- [x] Badge ranking tampil dengan benar
- [x] Modal filter working

---

## 📈 FITUR TAMBAHAN (Future Enhancement)

### Saran Pengembangan:
1. **Export to Excel/PDF** - Export data KPI per periode
2. **Email Notification** - Notifikasi ranking bulanan ke karyawan
3. **Chart Visualization** - Grafik trend KPI per karyawan
4. **Comparison View** - Bandingkan KPI antar periode
5. **Target Setting** - Set target point per karyawan
6. **Reward System** - Integrasi dengan sistem reward/bonus

---

## 🔍 TROUBLESHOOTING

### Issue: Data tidak muncul
**Solution**: 
- Pastikan ada data presensi/aktivitas/perawatan di periode tersebut
- Klik tombol "Hitung Ulang"

### Issue: Ranking tidak sesuai
**Solution**:
- Ranking otomatis di-update saat calculate
- Pastikan sudah klik "Hitung Ulang" untuk data terbaru

### Issue: Error saat akses menu
**Solution**:
- Pastikan login sebagai Super Admin
- Clear cache: `php artisan optimize:clear`

---

## 📞 SUPPORT

Jika ada pertanyaan atau issue, hubungi tim development.

---

**Tanggal Implementasi**: 19 November 2025
**Versi**: 1.0
**Status**: ✅ COMPLETED & TESTED
