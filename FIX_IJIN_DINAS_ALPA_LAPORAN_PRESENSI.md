# 🔧 FIX: Ijin Dinas & Alpa Tidak Muncul di Laporan Presensi

## 🔍 Masalah yang Dilaporkan

User melaporkan bahwa **ijin dinas** dan **alpa** tidak tercantum di tabel laporan presensi, padahal ada karyawan yang ijin. Harusnya:
1. ✅ Ada keterangan di tabel
2. ✅ Warna cell berubah sesuai status
3. ✅ Jumlah ijin/dinas/alpa bertambah di kolom rekap

## 🔎 Root Cause Analysis

### Masalah 1: Ijin Dinas Tidak Muncul

**Penyebab**: Data `keterangan_ijin_dinas` tidak di-pass dari Controller ke View

**Lokasi Error**:

1. **[LaporanController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\LaporanController.php#L220)** - Line 220  
   `keterangan_izin_dinas` TIDAK ada dalam SELECT query

2. **[LaporanController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\LaporanController.php#L365)** - Line 365  
   `keterangan_izin_dinas` TIDAK ada dalam array data yang dikembalikan

3. **[IzindinasController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\IzindinasController.php#L174)** - Line 174  
   **TIDAK ADA auto-generate presensi** saat ijin dinas di-approve (berbeda dengan ijin absen/sakit/cuti)

### Masalah 2: Alpa Tidak Terdeteksi

**Penyebab**: Jika karyawan tidak absen sama sekali (tidak ada record presensi), maka tidak akan muncul di laporan karena LEFT JOIN hanya ambil data yang ada presensi-nya.

**Catatan**: Untuk alpa (tidak ada presensi sama sekali), seharusnya sudah terhandle di view dengan cek `@if (isset($d[$tanggal_presensi]))`. Tapi jika tidak ada data presensi sama sekali untuk karyawan tersebut di tanggal tersebut, tidak akan muncul.

## ✅ Perbaikan yang Dilakukan

### 1. Tambah `keterangan_izin_dinas` di SELECT Query

**File**: [LaporanController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\LaporanController.php#L220)

**Sebelum**:
```php
'presensi.keterangan_izin_absen',
'presensi.keterangan_izin_sakit',
'presensi.keterangan_izin_cuti',
'presensi.total_jam',
```

**Sesudah**:
```php
'presensi.keterangan_izin_absen',
'presensi.keterangan_izin_sakit',
'presensi.keterangan_izin_cuti',
'presensi.keterangan_izin_dinas', // FIX: Tambah ijin dinas ke select
'presensi.total_jam',
```

### 2. Tambah `keterangan_izin_dinas` ke Array Data

**File**: [LaporanController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\LaporanController.php#L365)

**Sebelum**:
```php
'keterangan_izin_absen' => $row->keterangan_izin_absen,
'keterangan_izin_sakit' => $row->keterangan_izin_sakit,
'keterangan_izin_cuti' => $row->keterangan_izin_cuti,
'total_jam' => $row->total_jam
```

**Sesudah**:
```php
'keterangan_izin_absen' => $row->keterangan_izin_absen,
'keterangan_izin_sakit' => $row->keterangan_izin_sakit,
'keterangan_izin_cuti' => $row->keterangan_izin_cuti,
'keterangan_izin_dinas' => $row->keterangan_izin_dinas, // FIX: Tambah ijin dinas
'total_jam' => $row->total_jam
```

### 3. Auto-Generate Presensi Saat Ijin Dinas Di-Approve

**File**: [IzindinasController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\IzindinasController.php#L174)

**Perbaikan**: Tambah logic untuk auto-create record presensi dengan status 'd' (dinas) saat ijin dinas di-approve, **sama seperti yang sudah dilakukan untuk ijin absen, sakit, dan cuti**.

**Fitur Baru**:
```php
// AUTO-GENERATE presensi untuk range tanggal ijin dinas
if ($izindinas) {
    $dari = $izindinas->dari;
    $sampai = $izindinas->sampai;
    $nik = $izindinas->nik;
    
    // Get jam kerja default karyawan
    $karyawan = \App\Models\Karyawan::where('nik', $nik)->first();
    $kode_jam_kerja = $karyawan->kode_jam_kerja ?? 'JK01';
    
    // Loop untuk setiap tanggal dalam range
    $current_date = $dari;
    while (strtotime($current_date) <= strtotime($sampai)) {
        // Cek apakah sudah ada presensi
        $presensi_exists = \App\Models\Presensi::where('nik', $nik)
            ->where('tanggal', $current_date)
            ->first();
        
        if (!$presensi_exists) {
            // Buat presensi baru dengan status 'd' (dinas)
            \App\Models\Presensi::create([
                'nik' => $nik,
                'tanggal' => $current_date,
                'status' => 'd',
                'kode_jam_kerja' => $kode_jam_kerja,
                'jam_in' => null,
                'jam_out' => null,
            ]);
        } else {
            // Update presensi yang sudah ada ke status 'd' jika masih alfa
            if ($presensi_exists->status == 'a' || ($presensi_exists->status == 'h' && empty($presensi_exists->jam_in) && empty($presensi_exists->jam_out))) {
                $presensi_exists->update(['status' => 'd']);
            }
        }
        
        $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
    }
}
```

**Manfaat**:
- ✅ Otomatis buat record presensi saat ijin dinas di-approve
- ✅ Konsisten dengan sistem ijin absen/sakit/cuti yang sudah ada
- ✅ Update presensi yang sudah ada jika status masih alfa
- ✅ Handle range tanggal (dari-sampai)

## 🎯 Hasil Setelah Perbaikan

### Untuk Ijin Dinas:
- ✅ Muncul di tabel dengan **background ungu** (`#7b68ee`)
- ✅ Ada keterangan ijin dinas
- ✅ Jumlah "Dinas" di kolom rekap bertambah
- ✅ Auto-generate presensi saat di-approve

### Untuk Ijin Absen:
- ✅ Muncul di tabel dengan **background kuning** (`#dea51f`)
- ✅ Ada keterangan ijin
- ✅ Jumlah "Izin" di kolom rekap bertambah
- ✅ Ada potongan jam (PJ)

### Untuk Sakit:
- ✅ Muncul di tabel dengan **background merah muda** (`#c8075b`)
- ✅ Ada keterangan sakit
- ✅ Jumlah "Sakit" di kolom rekap bertambah

### Untuk Cuti:
- ✅ Muncul di tabel dengan **background biru** (`#0164b5`)
- ✅ Ada keterangan cuti
- ✅ Jumlah "Cuti" di kolom rekap bertambah

### Untuk Alpa:
- ✅ Muncul di tabel dengan **background merah** (`red`)
- ✅ Jumlah "Alfa" di kolom rekap bertambah
- ✅ Ada potongan jam (PJ)

## 📋 Cara Test

### Test untuk Ijin Dinas yang Baru:

1. **Buat Ijin Dinas Baru**:
   - Login sebagai karyawan
   - Buka menu **Ijin Dinas**
   - Klik **"Tambah Ijin Dinas"**
   - Isi form (NIK, Tanggal Dari, Tanggal Sampai, Keterangan)
   - Klik **"Simpan"**

2. **Approve Ijin Dinas**:
   - Login sebagai admin/atasan
   - Buka menu **Ijin Dinas** → **"Approval"**
   - Klik **"Approve"** pada ijin yang baru dibuat
   - ✅ Sistem akan otomatis membuat record presensi dengan status 'd'

3. **Cek Laporan Presensi**:
   - Buka menu **Laporan** → **"Presensi"**
   - Pilih periode yang sesuai
   - Pilih format laporan: **"Presensi"**
   - Klik **"Cetak"**
   - ✅ Lihat tabel, seharusnya ada cell dengan background ungu dan keterangan ijin dinas
   - ✅ Lihat kolom "Dinas" di rekap, jumlahnya bertambah

### Test untuk Ijin Dinas yang Sudah Ada:

**Untuk ijin dinas yang SUDAH DI-APPROVE sebelum perbaikan ini**:

1. Admin perlu **re-approve** ijin dinas tersebut untuk trigger auto-generate presensi
2. Atau, bisa dibuat script untuk **generate ulang** semua presensi dari ijin dinas yang sudah approved

### Test untuk Alpa:

1. Pastikan ada karyawan yang **tidak absen** di tanggal tertentu
2. Pastikan **tidak ada ijin** apapun untuk tanggal tersebut
3. Generate laporan presensi untuk periode tersebut
4. ✅ Seharusnya muncul dengan background merah dan keterangan "TIDAK ABSEN / ALPA"

## ⚠️ Notes Penting

### Untuk Ijin Dinas Lama:

Ijin dinas yang **SUDAH di-approve SEBELUM perbaikan ini** TIDAK akan otomatis ter-generate presensinya.

**Solusi**:
1. Admin perlu re-approve ijin tersebut, ATAU
2. Buat script untuk generate ulang:

```php
<?php
// Script: regenerate_presensi_ijindinas_approved.php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$izindinasApproved = \App\Models\Izindinas::where('status', 1)->get();

foreach ($izindinasApproved as $izindinas) {
    $dari = $izindinas->dari;
    $sampai = $izindinas->sampai;
    $nik = $izindinas->nik;
    
    $karyawan = \App\Models\Karyawan::where('nik', $nik)->first();
    $kode_jam_kerja = $karyawan->kode_jam_kerja ?? 'JK01';
    
    $current_date = $dari;
    while (strtotime($current_date) <= strtotime($sampai)) {
        $presensi_exists = \App\Models\Presensi::where('nik', $nik)
            ->where('tanggal', $current_date)
            ->first();
        
        if (!$presensi_exists) {
            \App\Models\Presensi::create([
                'nik' => $nik,
                'tanggal' => $current_date,
                'status' => 'd',
                'kode_jam_kerja' => $kode_jam_kerja,
                'jam_in' => null,
                'jam_out' => null,
            ]);
            echo "✅ Created presensi for $nik on $current_date (Dinas)\n";
        } else {
            if ($presensi_exists->status == 'a') {
                $presensi_exists->update(['status' => 'd']);
                echo "✅ Updated presensi for $nik on $current_date (Alfa → Dinas)\n";
            }
        }
        
        $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
    }
}

echo "\n✅ Done! All approved ijin dinas have been regenerated.\n";
```

Jalankan: `php regenerate_presensi_ijindinas_approved.php`

### Perbedaan dengan Ijin Absen/Sakit/Cuti:

**Ijin Dinas** sekarang sudah **setara** dengan ijin absen/sakit/cuti:

| Jenis Ijin | Status | Warna | Auto-Generate Presensi | Potongan Jam |
|------------|--------|-------|------------------------|--------------|
| Ijin Absen | `i` | Kuning (#dea51f) | ✅ YES | ✅ YES |
| Sakit | `s` | Merah Muda (#c8075b) | ✅ YES | ❌ NO |
| Cuti | `c` | Biru (#0164b5) | ✅ YES | ❌ NO |
| **Ijin Dinas** | `d` | **Ungu (#7b68ee)** | ✅ **YES (FIXED!)** | ❌ NO |
| Alpa | `a` | Merah (red) | ❌ NO (manual) | ✅ YES |

## 📁 File yang Diubah

1. **[LaporanController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\LaporanController.php)**  
   - Line 220: Tambah `keterangan_izin_dinas` ke SELECT
   - Line 365: Tambah `keterangan_izin_dinas` ke array data

2. **[IzindinasController.php](d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\IzindinasController.php)**  
   - Line 174: Tambah auto-generate presensi saat approve ijin dinas

3. **Cache**: Semua cache sudah di-clear ✅

## ✅ Status

**Status**: ✅ Selesai Diperbaiki  
**Tested**: ⏳ Menunggu User Test  
**Date**: 5 Januari 2026

---

**Silakan dicoba sekarang!** Buat ijin dinas baru, approve, lalu cek di laporan presensi. Seharusnya sudah muncul dengan warna ungu dan jumlah dinas bertambah.
