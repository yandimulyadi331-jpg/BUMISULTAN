# 🎉 FITUR SURAT PERINGATAN - IMPLEMENTASI LENGKAP

## ✅ Status: SELESAI 100%

---

## 📋 Fitur Baru: Surat Peringatan Otomatis

### Deskripsi
Sistem akan **otomatis** menyediakan **Surat Peringatan PDF** untuk santri yang telah melakukan pelanggaran **≥ 75 kali** (Status BERAT).

---

## 🎯 Cara Kerja

### 1. **Trigger Otomatis**
```
Jumlah Pelanggaran ≥ 75x → Tombol "Surat Peringatan" Muncul
```

### 2. **Lokasi Tombol Download**

#### A. Di Halaman Laporan (`/pelanggaran-santri/laporan/index`)
- Santri dengan status **BERAT** (≥75 pelanggaran) akan ada tombol merah **"Surat Peringatan"**
- Klik tombol → Download PDF langsung

#### B. Di Halaman List Pelanggaran (`/pelanggaran-santri`)
- Alert merah muncul di atas jika ada santri dengan pelanggaran ≥75
- Tombol hitam **"Surat Peringatan"** di kolom aksi untuk santri yang memenuhi syarat

---

## 📄 Format Surat Peringatan

### Kop Surat Resmi
```
═══════════════════════════════════════════
         PONDOK PESANTREN
           SAUNG SANTRI
    
Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02
        Jonggol, Kec. Jonggol
        Kabupaten Bogor, Jawa Barat 16830
Telp: (021) 1234567 | Email: info@saungsantri.ac.id
═══════════════════════════════════════════
```

### Isi Surat Meliputi:

1. **Nomor Surat** (Auto-generate)
   - Format: `SP/2025/0001/11`
   - SP = Surat Peringatan
   - 2025 = Tahun
   - 0001 = ID Santri (4 digit)
   - 11 = Bulan

2. **Data Santri**
   - Nama lengkap
   - NIK/No. Induk
   - Total pelanggaran
   - Total point
   - Status (BERAT - badge merah)
   - Tanggal surat

3. **Riwayat Pelanggaran**
   - Tabel 10 pelanggaran terakhir
   - Tanggal, keterangan, point, dicatat oleh

4. **Isi Peringatan**
   - Pernyataan formal
   - Harapan perbaikan (4 poin)
   - Konsekuensi jika berlanjut

5. **TTD**
   - Kepala Pondok Pesantren
   - Lokasi: Jonggol
   - Tanggal otomatis (bahasa Indonesia)

6. **Tembusan**
   - Arsip Pondok Pesantren
   - Orang Tua/Wali Santri
   - Santri yang bersangkutan

---

## 🚀 Cara Menggunakan

### Skenario 1: Dari Halaman Laporan

1. Buka menu **Pelanggaran Santri** > **Laporan**
2. Lihat tabel rekap santri
3. Santri dengan **status BERAT** (≥75 pelanggaran) akan ada tombol merah:
   ```
   [👁️ Detail] [📄 Surat Peringatan]
   ```
4. Klik **"Surat Peringatan"**
5. PDF akan otomatis ter-download
6. Filename: `Surat-Peringatan-[Nama-Santri]-2025-11-08.pdf`

### Skenario 2: Dari Halaman List

1. Buka **Pelanggaran Santri**
2. Jika ada santri dengan pelanggaran ≥75, muncul alert:
   ```
   ⚠️ PERHATIAN!
   Terdapat X santri dengan pelanggaran ≥ 75 kali (Status BERAT).
   Lihat Laporan untuk download Surat Peringatan.
   ```
3. Di kolom aksi, ada tombol hitam dengan icon dokumen
4. Klik untuk download

### Skenario 3: Validasi

Jika mencoba download surat untuk santri dengan pelanggaran **< 75**:
```
❌ Error: Surat peringatan hanya untuk santri dengan 
          pelanggaran >= 75 kali. 
          Total pelanggaran saat ini: XX
```

---

## 📊 Contoh Tampilan Surat

```
═══════════════════════════════════════════════
              PONDOK PESANTREN
                SAUNG SANTRI
        
Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol
        Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830
        Telp: (021) 1234567 | Email: info@saungsantri.ac.id
═══════════════════════════════════════════════

Nomor      : SP/2025/0012/11
Lampiran   : -
Perihal    : Surat Peringatan

           ═══ SURAT PERINGATAN ═══

Assalamu'alaikum Warahmatullahi Wabarakatuh

Dengan ini kami sampaikan surat peringatan kepada santri 
yang bersangkutan atas pelanggaran tata tertib pondok 
pesantren yang telah dilakukan secara berulang kali.

Nama Santri              : Ahmad Fauzi
NIK/No. Induk           : 1234567890
Total Pelanggaran       : 78 kali  [STATUS: BERAT 🔴]
Total Point Pelanggaran : 95 point
Tanggal Surat           : 8 November 2025

Riwayat Pelanggaran Terakhir (10 Pelanggaran):

┌────┬────────────┬─────────────────────┬───────┬─────────┐
│ No │  Tanggal   │     Keterangan      │ Point │ Dicatat │
├────┼────────────┼─────────────────────┼───────┼─────────┤
│ 1  │ 07/11/2025 │ Merokok di kamar    │   2   │ Admin   │
│ 2  │ 06/11/2025 │ Keluar tanpa izin   │   3   │ Ustadz  │
│ 3  │ 05/11/2025 │ Tidak sholat jamaah │   2   │ Admin   │
...
└────┴────────────┴─────────────────────┴───────┴─────────┘

Dengan melihat catatan pelanggaran yang telah mencapai 
78 kali, kami memberikan surat peringatan ini sebagai 
bentuk perhatian serius...

Kami mengharapkan yang bersangkutan untuk:
1. Memperbaiki sikap dan perilaku
2. Mentaati seluruh peraturan pondok pesantren
3. Menjadi teladan bagi santri lainnya
4. Meningkatkan kedisiplinan dalam beribadah dan belajar

...

                           Jonggol, 8 November 2025
                   Kepala Pondok Pesantren Saung Santri
                   
                   
                   
                   _________________________
                        Pengasuh Pondok

Tembusan:
1. Arsip Pondok Pesantren
2. Orang Tua/Wali Santri
3. Santri yang bersangkutan
```

---

## 🔧 Technical Details

### Files Created/Updated

```
✅ Controller Update:
   - PelanggaranSantriController.php
     → Method: suratPeringatan($userId)
     
✅ View Baru:
   - resources/views/pelanggaran-santri/surat-peringatan.blade.php
     → Template PDF dengan kop surat resmi
     
✅ Routes Update:
   - routes/web.php
     → GET /pelanggaran-santri/surat-peringatan/{userId}
     
✅ Views Updated:
   - laporan.blade.php → Tombol surat peringatan
   - index.blade.php → Alert + tombol surat peringatan
```

### Controller Method

```php
public function suratPeringatan($userId)
{
    // 1. Validasi: Cek apakah pelanggaran >= 75
    // 2. Ambil data santri
    // 3. Ambil 10 pelanggaran terakhir
    // 4. Generate nomor surat otomatis
    // 5. Format tanggal bahasa Indonesia
    // 6. Generate PDF dengan DomPDF
    // 7. Download dengan nama file dinamis
}
```

### Route

```php
Route::get('/surat-peringatan/{userId}', 'suratPeringatan')
    ->name('pelanggaran-santri.surat-peringatan')
    ->can('pelanggaran-santri.laporan');
```

### Permission
Menggunakan permission yang sudah ada: `pelanggaran-santri.laporan`

---

## 🎨 UI Components

### Alert di Index Page

```blade
@if($santriBeratCount > 0)
<div class="alert alert-danger">
    ⚠️ PERHATIAN!
    Terdapat {{ $santriBeratCount }} santri dengan 
    pelanggaran ≥ 75 kali (Status BERAT).
    [Lihat Laporan]
</div>
@endif
```

### Tombol di Laporan

```blade
@if($item->total_pelanggaran >= 75)
<a href="{{ route('pelanggaran-santri.surat-peringatan', $item->id) }}"
   class="btn btn-sm btn-danger">
   📄 Surat Peringatan
</a>
@endif
```

### Tombol di List Pelanggaran

```blade
@if($total >= 75)
<a href="{{ route('pelanggaran-santri.surat-peringatan', $item->user_id) }}"
   class="btn btn-sm btn-dark">
   📄 Download Surat
</a>
@endif
```

---

## 📝 Format Nomor Surat

```
SP/[TAHUN]/[ID_SANTRI]/[BULAN]

Contoh:
SP/2025/0012/11
SP/2025/0045/12
SP/2026/0001/01
```

---

## 🎯 Business Logic

### Threshold Surat Peringatan

```php
if ($totalPelanggaran >= 75) {
    // Tombol Surat Peringatan MUNCUL ✅
    // Status: BERAT (Merah)
} else {
    // Tombol TIDAK muncul ❌
    // Error jika force access
}
```

### Auto-Generated Data

1. **Nomor Surat**: SP/YYYY/XXXX/MM
2. **Tanggal**: Format Indonesia (8 November 2025)
3. **Filename**: Surat-Peringatan-[Nama]-YYYY-MM-DD.pdf
4. **Footer**: Timestamp cetak

---

## 📊 Statistics

### Code Added

```
Controller Method : 50+ lines
View Template     : 320+ lines
Routes            : 1 new route
UI Updates        : 3 files updated
Total Added       : ~400 lines
```

### Features

✅ Auto-generate nomor surat  
✅ Kop surat resmi  
✅ Data santri lengkap  
✅ Riwayat 10 pelanggaran terakhir  
✅ Format profesional  
✅ TTD placeholder  
✅ Tembusan  
✅ Footer otomatis  
✅ Validation (only ≥75)  
✅ Dynamic filename  
✅ Portrait A4 format  
✅ Bahasa Indonesia  

---

## 🔒 Security & Validation

### 1. Permission Check
```php
->can('pelanggaran-santri.laporan')
```
Hanya user dengan permission yang bisa download.

### 2. Minimum Threshold
```php
if ($totalPelanggaran < 75) {
    return redirect()->back()
        ->with('error', 'Surat peringatan hanya untuk...');
}
```

### 3. User Validation
```php
$santri = User::findOrFail($userId);
```
Auto 404 jika user tidak ditemukan.

---

## 🎉 Preview Flow

```
1. Santri melakukan pelanggaran berkali-kali
   ↓
2. Total pelanggaran mencapai 75x
   ↓
3. Status berubah menjadi BERAT (Merah) 🔴
   ↓
4. Alert muncul di halaman index
   ↓
5. Tombol "Surat Peringatan" muncul
   ↓
6. Admin klik tombol
   ↓
7. PDF ter-generate dengan:
   - Kop surat resmi ✅
   - Data lengkap santri ✅
   - Riwayat pelanggaran ✅
   - Nomor surat otomatis ✅
   ↓
8. PDF ter-download
   ↓
9. Cetak & serahkan ke:
   - Santri yang bersangkutan
   - Orang tua/wali
   - Arsip pondok
```

---

## 💡 Use Cases

### 1. Rapat Evaluasi Santri
- Download surat untuk semua santri status BERAT
- Bahas satu per satu
- Panggil santri & orang tua

### 2. Dokumentasi Arsip
- Simpan semua surat peringatan
- Tracking perkembangan santri
- Bukti tindakan formal

### 3. Komunikasi dengan Wali
- Kirim surat ke orang tua
- Bukti pelanggaran tertulis
- Formal & profesional

### 4. Tindak Lanjut
- Surat peringatan pertama
- Monitoring 1 bulan
- Jika berlanjut → tindakan lebih lanjut

---

## 🔄 Maintenance

### Update Kop Surat

Edit file: `surat-peringatan.blade.php`

```html
<div class="kop-surat">
    <h1>PONDOK PESANTREN</h1>
    <h2>SAUNG SANTRI</h2>
    <p>Alamat: [EDIT DISINI]</p>
    <p>Telp: [EDIT DISINI]</p>
</div>
```

### Update Format Nomor Surat

Edit controller method:

```php
$nomorSurat = 'SP/' . date('Y') . '/' 
            . str_pad($userId, 4, '0', STR_PAD_LEFT) 
            . '/' . date('m');
```

### Update Threshold

Ubah angka 75 di controller & views jika perlu threshold berbeda.

---

## 📞 Support

Jika ada kendala:

1. **PDF tidak ter-generate**
   ```bash
   composer require barryvdh/laravel-dompdf
   ```

2. **Permission error**
   ```bash
   php artisan db:seed --class=PelanggaranSantriPermissionSeeder
   ```

3. **Clear cache**
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

---

## ✅ Testing Checklist

- [x] Tombol muncul untuk santri ≥75 pelanggaran
- [x] Tombol TIDAK muncul untuk santri <75 pelanggaran
- [x] PDF ter-download dengan benar
- [x] Kop surat tampil lengkap
- [x] Data santri akurat
- [x] Riwayat pelanggaran muncul
- [x] Nomor surat ter-generate
- [x] Tanggal format Indonesia
- [x] Filename dinamis
- [x] Alert muncul di index page
- [x] Permission check berfungsi
- [x] Validation threshold berfungsi

---

## 🎉 SELESAI!

Fitur **Surat Peringatan Otomatis** telah **100% siap** digunakan!

### Quick Access:
- Laporan: `/pelanggaran-santri/laporan/index`
- List: `/pelanggaran-santri`
- Surat: Klik tombol merah/hitam dengan icon 📄

---

**Version:** 1.1.0  
**Date:** 8 November 2025  
**Feature:** Surat Peringatan PDF  
**Status:** Production Ready ✅
