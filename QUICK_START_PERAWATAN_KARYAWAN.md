# 🚀 QUICK START - Menu Perawatan Karyawan

## Instalasi Cepat

### 1. Persiapan Storage
```bash
# Di terminal/command prompt, jalankan:
php artisan storage:link
```

### 2. Buat Folder untuk Foto
```bash
# Pastikan folder ini ada dan writable
mkdir storage/app/public/perawatan
```

### 3. Isi Data Master (Untuk Admin)
Login sebagai admin, lalu:
- Masuk ke menu **"Manajemen Perawatan"**
- Klik **"Master Checklist"**
- Klik **"Tambah Checklist"**

Contoh data checklist harian:
```
Nama Kegiatan: Membersihkan lantai ruang kantor
Deskripsi: Sapu dan pel seluruh area lantai kantor
Tipe Periode: Harian
Kategori: Kebersihan
Urutan: 1
Status: Aktif
```

## 🎯 Cara Pakai untuk Karyawan

### Step 1: Akses Menu
1. Login sebagai karyawan
2. Di dashboard, scroll ke bawah
3. Klik menu **"Perawatan"** (icon jam/clock)

### Step 2: Pilih Jenis Checklist
Pilih salah satu:
- **Checklist Harian** (untuk tugas setiap hari)
- **Checklist Mingguan** (untuk tugas setiap minggu)
- **Checklist Bulanan** (untuk tugas setiap bulan)
- **Checklist Tahunan** (untuk tugas setiap tahun)

### Step 3: Kerjakan Checklist
1. Klik **checkbox** di sebelah kiri tugas
2. Modal akan muncul:
   - **Catatan**: Isi jika ada catatan (opsional)
   - **Foto Bukti**: Upload foto hasil kerja (opsional)
3. Klik tombol **"Selesai"**
4. ✅ Checklist berubah hijau dan tercentang!

### Step 4: Lihat History (Opsional)
- Klik **"History Aktivitas"**
- Lihat semua checklist yang sudah dikerjakan
- Klik foto untuk memperbesar

## 📱 Screenshot Alur

```
Dashboard Karyawan
    ↓ (Klik "Perawatan")
Dashboard Perawatan
    ↓ (Pilih "Checklist Harian")
Halaman Checklist
    ↓ (Klik checkbox)
Modal Input
    ↓ (Isi catatan & foto)
Checklist Selesai ✅
```

## ⚠️ Yang Perlu Diingat

1. **Satu kali centang per periode**
   - Sekali checklist dicentang untuk hari ini, tidak bisa dicentang lagi
   - Bisa dibatalkan dengan tombol "Batalkan"

2. **Foto maksimal 2MB**
   - Format: JPG, PNG, JPEG
   - Ukuran max: 2MB

3. **Reset Periode**
   - Harian: Reset setiap hari jam 00:00
   - Mingguan: Reset setiap Senin
   - Bulanan: Reset setiap tanggal 1
   - Tahunan: Reset setiap 1 Januari

## 🔧 Troubleshooting Cepat

### Foto tidak bisa diupload?
```bash
# Jalankan ini:
php artisan storage:link
chmod -R 775 storage/app/public/perawatan
```

### Menu tidak muncul di dashboard?
- Cek apakah sudah login sebagai karyawan
- Refresh halaman (Ctrl + F5)
- Clear cache browser

### Checklist kosong?
- Minta admin untuk membuat master checklist
- Cek status checklist harus "Aktif"

## 📞 Butuh Bantuan?
Hubungi admin atau tim IT untuk bantuan lebih lanjut.

---

**Happy Checklist! ✅**
