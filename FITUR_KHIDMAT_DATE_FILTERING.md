# Fitur Date Filtering - Menu Khidmat / Belanja Santri

## Overview
Sistem khidmat (belanja masak santri) sekarang dilengkapi dengan fitur date filtering yang memungkinkan monitoring harian dan navigasi tanggal untuk melihat aktivitas belanja pada hari-hari sebelumnya dan sesudahnya.

## Fitur Utama

### 1. **Tampilan Hari Ini (Default)**
- Halaman khidmat secara otomatis menampilkan data khidmat untuk **hari ini**
- Format tanggal: "Senin, 19/01/2026"
- Menampilkan badge status:
  - 🟢 **Hari Ini** (Jika tanggal yang dipilih adalah hari ini)
  - 🔘 **Tanggal Lalu** (Jika tanggal sudah lewat)
  - 🔵 **Tanggal Mendatang** (Jika tanggal belum tiba)

### 2. **Navigasi Tanggal**
Tiga tombol navigasi tersedia di bagian atas halaman:

#### Tombol Kiri: "← Kemarin"
- Menampilkan data khidmat untuk hari sebelumnya
- Contoh: Jika hari ini Senin, klik akan tampilkan Minggu

#### Tombol Tengah: Informasi Tanggal Terpilih
- Menampilkan hari dan tanggal yang sedang dilihat
- Badge status (Hari Ini / Tanggal Lalu / Tanggal Mendatang)

#### Tombol Kanan: "Besok →"
- Menampilkan data khidmat untuk hari berikutnya
- Contoh: Jika hari ini Senin, klik akan tampilkan Selasa

#### Tombol Kembali: "Kembali ke Hari Ini"
- Quick button untuk kembali ke tampilan hari ini dari tanggal mana pun

### 3. **Monitoring Jadwal Lama (Archive Search)**
Fitur pencarian tetap ada untuk keperluan monitoring/audit:

**Fitur:**
- **Cari Kelompok/Tanggal**: Cari berdasarkan nama kelompok atau tanggal spesifik
- **Filter Status**: Filter berdasarkan status selesai (Belum Selesai / Sudah Selesai)
- **Lihat 7 Hari Terakhir**: Quick reset untuk melihat data 7 hari terbaru

**Cara Kerja:**
- Ketik kelompok atau tanggal di search box
- Gunakan dropdown filter status
- Sistem akan melakukan pencarian di seluruh data khidmat (tidak hanya 7 hari)
- Tekan "Lihat 7 Hari Terakhir" untuk reset dan kembali ke view default

### 4. **Use Case / Monitoring**

#### Scenario 1: Melihat Data Hari Ini
1. Masuk ke menu Khidmat
2. Secara otomatis tampil data untuk hari ini
3. Lihat detail belanja, saldo, dan status

#### Scenario 2: Cek Aktivitas Hari Kemarin
1. Klik tombol "← Kemarin"
2. Tampil data khidmat untuk hari sebelumnya
3. Lakukan perubahan atau edit jika diperlukan

#### Scenario 3: Monitoring Tren Belanja
1. Navigasi ke tanggal-tanggal sebelumnya menggunakan tombol navigasi
2. Amati pola: saldo, belanja, saldo akhir per hari
3. Identifikasi anomali atau tren pengeluaran

#### Scenario 4: Cari Data Spesifik (Arsip)
1. Pada bagian "Monitor / Cari Jadwal Lama"
2. Ketik nama kelompok, misal "Kelompok Senin"
3. Sistem menampilkan semua jadwal dengan nama tersebut
4. Filter status jika diperlukan

## Technical Implementation

### Backend (KhidmatController.php)

#### Method: `index()`
**Perubahan:**
```php
// Get tanggal parameter dari request, default ke hari ini
$tanggalSelected = request('tanggal') 
    ? Carbon::parse(request('tanggal')) 
    : Carbon::today();

// Query jadwal untuk tanggal spesifik
$jadwalHari = JadwalKhidmat::with(['petugas.santri', 'belanja', 'foto'])
    ->whereDate('tanggal_jadwal', $tanggalSelected)
    ->first();

// Hitung tanggal navigasi
$tanggalKemarin = $tanggalSelected->copy()->subDay();
$tanggalBesok = $tanggalSelected->copy()->addDay();
```

**Return View:**
- `jadwal`: Koleksi jadwal untuk tanggal terpilih (0 atau 1 item)
- `tanggalSelected`: Carbon instance tanggal terpilih
- `namaHariSelected`: Nama hari (Senin, Selasa, dll)
- `tanggalDisplay`: Format tanggal DD/MM/YYYY
- `tanggalKemarin`: Untuk link navigasi sebelumnya
- `tanggalBesok`: Untuk link navigasi berikutnya

### Frontend (khidmat/index.blade.php)

#### Bagian: Date Navigation Header
**Fitur:**
- Row dengan 3 kolom: Tombol Kemarin | Info Tanggal | Tombol Besok
- Query parameter `?tanggal=YYYY-MM-DD` pada setiap link navigasi
- Conditional badges berdasarkan `$tanggalSelected->isToday()`, `isPast()`, dll

**Kode:**
```blade
<a href="{{ route('khidmat.index', ['tanggal' => $tanggalKemarin->toDateString()]) }}" 
   class="btn btn-outline-primary btn-sm w-100">
    <i class="ti ti-arrow-left me-1"></i> Kemarin ({{ $tanggalKemarin->format('d/m') }})
</a>
```

#### Bagian: Alert Tidak Ada Data
**Kondisi:** Jika `$jadwal->isEmpty()`
- Tampilkan info alert
- User dapat tetap menggunakan search atau navigasi ke tanggal lain

#### Bagian: Table Display
- Hanya tampilkan data untuk tanggal terpilih
- Jika ada data, tampilkan header "Data Khidmat - [Hari], [Tanggal]"
- Jika kosong, tampilkan pesan

## URL Structure

### Default (Today)
```
/khidmat
```

### Spesifik Tanggal
```
/khidmat?tanggal=2026-01-19
/khidmat?tanggal=2026-01-18
/khidmat?tanggal=2026-01-20
```

### Query String Format
- **Parameter**: `tanggal`
- **Value**: ISO format (YYYY-MM-DD)
- **Default**: Tidak perlu diisi, otomatis ke hari ini

## UX Improvements

### 1. **Clear Navigation**
- Tombol prev/next dengan tanggal untuk preview
- Clear indikasi tanggal mana yang sedang dilihat
- Visual badge untuk status tanggal

### 2. **Flexible Monitoring**
- Pilihan antara navigasi harian (tombol) atau search cepat
- Bisa lihat timeline harian tanpa mencari
- Bisa jump ke tanggal spesifik via search

### 3. **Backward Compatibility**
- Fitur search tetap berfungsi seperti sebelumnya
- Data 7 hari masih bisa diakses via search
- Tidak ada perubahan data atau logika bisnis

## Testing Checklist

- [ ] Buka `/khidmat` → Tampil hari ini
- [ ] Klik "← Kemarin" → Tampil data kemarin
- [ ] Klik "Besok →" → Tampil data besok
- [ ] Klik "Kembali ke Hari Ini" → Kembali ke hari ini
- [ ] Cari kelompok spesifik → Filter bekerja
- [ ] Filter status → Hanya tampil status sesuai filter
- [ ] Tanggal tanpa data → Tampil alert info
- [ ] Cache cleared → Fitur berfungsi normal

## Database Changes
**Tidak ada perubahan database.**
Fitur ini hanya menggunakan kolom `tanggal_jadwal` yang sudah ada dengan filter `whereDate()`.

## Performance Notes
- Query hanya mengambil 1 record per tanggal (`.first()`) → Cepat
- Tetap keep 7-day query di background untuk backward compatibility
- No N+1 issues, relationships sudah di-eager load

## Future Enhancements

1. **Date Range Picker**: Dropdown untuk pilih tanggal langsung
2. **Calendar View**: Lihat overview belanja sebulan
3. **Comparison**: Bandingkan belanja antar tanggal/minggu
4. **Reports**: Generate laporan monitoring per periode
5. **Export by Date**: Export data khidmat untuk tanggal tertentu

## Support / Troubleshooting

**Q: Data tidak tampil padahal di database ada**
- A: Clear cache: `php artisan cache:clear && php artisan view:clear`

**Q: Navigasi tanggal tidak bekerja**
- A: Pastikan web server support query parameters, check route `/khidmat`

**Q: Ingin lihat data 7 hari lagi**
- A: Gunakan fitur search (bagian "Monitor / Cari Jadwal Lama") atau klik "Lihat 7 Hari Terakhir"

---

**Last Updated**: 19 Januari 2026
**Status**: ✅ ACTIVE
