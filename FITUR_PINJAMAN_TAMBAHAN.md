# Fitur Pinjaman Tambahan / Pinjaman Baru untuk Peminjam yang Sama

## Overview
Fitur ini memungkinkan peminjam yang sudah pernah mengajukan pinjaman untuk mengajukan pinjaman baru/tambahan.

## Fitur Utama

### 1. Pinjaman Tambahan
- Peminjam yang sama dapat mengajukan **pinjaman baru** meskipun masih memiliki pinjaman aktif
- Sistem akan menampilkan **informasi pinjaman aktif** yang masih berjalan
- Setiap pinjaman dicatat sebagai transaksi terpisah dengan nomor pinjaman unik

### 2. Dokumen Administrasi Wajib Diisi Ulang
- **SEMUA DOKUMEN WAJIB DIISI ULANG** untuk setiap pinjaman baru
- Dokumen yang harus dilengkapi:
  - ✅ Foto/Scan KTP
  - ✅ Slip Gaji (untuk crew) atau Bukti Penghasilan
  - ✅ Dokumen Pendukung Lainnya
  - ✅ Data Jaminan (jika ada)
  - ✅ Data Penjamin (jika ada)
- Tujuan: **Keperluan arsip** dan dokumentasi lengkap per transaksi

### 3. Auto-Fill Data Peminjam
- Data identitas peminjam **otomatis terisi** dari pinjaman sebelumnya:
  - Nama lengkap
  - NIK / No. Karyawan
  - Alamat
  - No. Telepon
  - Pekerjaan
- User tinggal melengkapi:
  - Dokumen administrasi (WAJIB)
  - Jumlah pinjaman baru
  - Tenor
  - Tujuan pinjaman

## Cara Menggunakan

### A. Dari Halaman Detail Pinjaman
1. Buka detail pinjaman peminjam
2. Scroll ke bagian **"Pinjaman Tambahan"** (card hijau)
3. Klik tombol **"Buat Pinjaman Baru untuk [Nama Peminjam]"**
4. Sistem akan:
   - Menampilkan informasi pinjaman aktif (jika ada)
   - Auto-fill data peminjam
   - Menampilkan alert: "SEMUA DOKUMEN ADMINISTRASI WAJIB DIISI ULANG"
5. Lengkapi:
   - Upload dokumen KTP ✅
   - Upload slip gaji ✅
   - Upload dokumen pendukung ✅
   - Isi data jaminan (jika ada)
   - Isi jumlah, tenor, tujuan pinjaman
6. Klik **"Ajukan Pinjaman"**

### B. Dari Halaman Daftar Pinjaman
1. Klik **"Tambah Pinjaman"**
2. Isi kategori dan data peminjam secara manual
3. Lengkapi semua dokumen administrasi
4. Submit pengajuan

## Informasi Pinjaman Aktif

Sistem menampilkan informasi lengkap pinjaman aktif peminjam:
```
⚠️ Informasi Pinjaman Aktif:
Peminjam ini masih memiliki 2 pinjaman aktif:
• PNJ-202511-0001 - Status: BERJALAN - Sisa: Rp 4.000.000 (20% terbayar)
• PNJ-202511-0002 - Status: DISETUJUI - Sisa: Rp 10.000.000 (0% terbayar)
```

## Business Rules

### Validasi Pinjaman Baru
- ✅ Peminjam boleh punya **multiple pinjaman aktif**
- ✅ Setiap pinjaman **independent** (terpisah)
- ✅ Pembayaran cicilan per pinjaman **tidak tercampur**
- ✅ PDF resi per pinjaman **terpisah**

### Administrasi
- 🔒 **WAJIB** upload dokumen baru untuk setiap pinjaman
- 🔒 Dokumen pinjaman lama **TIDAK** bisa dipakai ulang
- 📁 Setiap pinjaman punya arsip dokumen lengkap
- 📋 Audit trail lengkap per pinjaman

### Laporan & Monitoring
- Laporan pinjaman menampilkan **semua pinjaman** (per transaksi)
- Filter berdasarkan:
  - Nama peminjam
  - NIK/No. Karyawan
  - Status
  - Tanggal
- Export PDF per pinjaman atau per periode

## Database Schema

### Tabel `pinjaman`
Setiap record = 1 transaksi pinjaman unik
- `id` - Primary key
- `nomor_pinjaman` - Unique (PNJ-YYYYMM-XXXX)
- `karyawan_id` / `nik_peminjam` - Identifier peminjam
- `dokumen_ktp` - Path file KTP (WAJIB)
- `dokumen_slip_gaji` - Path file slip gaji (WAJIB)
- `dokumen_pendukung_lain` - Path dokumen tambahan
- ... (fields lainnya)

### Relasi
- Satu peminjam → Many pinjaman (1:N)
- Satu pinjaman → Many cicilan (1:N)
- Satu pinjaman → Many history (1:N)

## Benefits

### Untuk Admin/Finance
✅ Arsip lengkap per transaksi
✅ Audit trail jelas
✅ Tracking per pinjaman mudah
✅ Dokumen tidak tercecer

### Untuk Peminjam
✅ Bisa pinjam lagi tanpa harus lunas dulu
✅ Data otomatis terisi (tidak repot)
✅ Proses cepat

### Untuk Perusahaan
✅ Compliance dokumentasi terpenuhi
✅ Risk management lebih baik
✅ Historical data lengkap
✅ Pelaporan akurat

## Contoh Use Case

### Skenario 1: Pinjaman Bertahap
- Jan 2025: Budi pinjam Rp 5 juta (12 bulan)
- Mar 2025: Budi bayar 2 cicilan (Rp 833.333 x 2)
- Apr 2025: Budi butuh dana lagi, ajukan pinjaman baru Rp 3 juta
- Hasil:
  - Pinjaman 1: Sisa Rp 3.333.334 (10 cicilan tersisa)
  - Pinjaman 2: Rp 3 juta (12 cicilan baru)
  - Total sisa: Rp 6.333.334

### Skenario 2: Pinjaman Darurat
- Karyawan punya pinjaman aktif Rp 10 juta
- Butuh dana darurat Rp 2 juta
- Bisa ajukan pinjaman baru tanpa harus lunas yang lama
- Kedua pinjaman berjalan paralel

## Technical Implementation

### Controller: `PinjamanController@create`
```php
public function create(Request $request)
{
    // Jika ada parameter ?duplicate_from=ID
    if ($request->has('duplicate_from')) {
        $pinjamanLama = Pinjaman::find($request->duplicate_from);
        
        // Ambil HANYA data peminjam (TIDAK termasuk dokumen)
        $duplicateData = [
            'kategori_peminjam' => $pinjamanLama->kategori_peminjam,
            'nama_peminjam_lengkap' => $pinjamanLama->nama_peminjam_lengkap,
            'nik_peminjam' => $pinjamanLama->nik_peminjam,
            // ... data identitas lainnya
            // TIDAK ada: dokumen_ktp, dokumen_slip_gaji, dll
        ];
        
        // Cek pinjaman aktif
        $pinjamanAktif = Pinjaman::where(...)
            ->whereIn('status', ['pengajuan', 'review', 'disetujui', 'dicairkan', 'berjalan'])
            ->get();
    }
    
    return view('pinjaman.create', compact('duplicateData', 'pinjamanAktif'));
}
```

### View: `create.blade.php`
- Auto-fill: `value="{{ old('nama', $duplicateData['nama'] ?? '') }}"`
- Alert pinjaman aktif ditampilkan
- Alert dokumen wajib diisi ulang
- File input tetap required (tidak ada pre-fill)

## Security & Validation

- ✅ File dokumen validated (mimes, max size)
- ✅ Tidak ada duplicate nomor pinjaman
- ✅ Tidak ada akses unauthorized ke data peminjam lain
- ✅ Soft delete untuk data integrity

## Future Enhancement Ideas

1. **Dashboard Peminjam**: View all pinjaman aktif per peminjam
2. **Payment Gateway**: Integrasi pembayaran online
3. **SMS/Email Notification**: Reminder cicilan
4. **Mobile App**: Aplikasi mobile untuk peminjam
5. **Credit Scoring**: Sistem penilaian kelayakan berdasarkan riwayat

---

**Last Updated**: November 14, 2025
**Version**: 1.0
**Status**: ✅ Production Ready
