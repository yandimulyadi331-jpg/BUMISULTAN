# Update Fitur Ijin Santri

## 🎉 Fitur Baru yang Ditambahkan

### 1. **Pencarian Ijin Santri**
- 🔍 Cari berdasarkan:
  - Nomor Surat
  - Nama Santri
  - NIS Santri
  - Alasan Ijin

### 2. **Filter Berdasarkan Tanggal**
- 📅 Filter rentang tanggal:
  - Tanggal Dari
  - Tanggal Sampai
- Default menampilkan ijin **HARI INI** saja
- Untuk melihat riwayat, gunakan filter tanggal

### 3. **Filter Berdasarkan Status**
- ✅ Menunggu TTD Ustadz (pending)
- ✅ Sudah TTD Ustadz (ttd_ustadz)
- ✅ Santri Dipulangkan (dipulangkan)
- ✅ Sudah Kembali (kembali)

### 4. **Hapus Ijin Santri**
- 🗑️ Tombol hapus tersedia di setiap data ijin
- Konfirmasi dengan SweetAlert2 yang menampilkan:
  - Nomor Surat
  - Nama Santri
- Loading indicator saat proses hapus

### 5. **Integrasi Status Ijin di Data Santri**
- 📊 Kolom "Status Ijin" di tabel Data Santri
- Badge status:
  - 🟡 **"Pulang"** - Jika santri sedang ijin pulang (status: dipulangkan)
  - 🟢 **"Di Pesantren"** - Jika santri tidak sedang ijin
- Tooltip menampilkan detail:
  - Alasan ijin
  - Tanggal rencana kembali
- Tanggal kembali ditampilkan di bawah badge

### 6. **Export PDF Mengikuti Filter**
- 📄 Download PDF sesuai dengan filter yang diterapkan
- Jika tidak ada filter, akan export ijin hari ini
- Jika ada filter tanggal/pencarian, akan export sesuai filter

## 📋 Cara Penggunaan

### Tampilan Default (Hari Ini)
1. Buka halaman **Ijin Santri**
2. Secara default akan menampilkan ijin **hari ini** saja
3. Jika tidak ada data, tabel akan kosong

### Mencari Riwayat Ijin Sebelumnya
1. Gunakan **Filter Tanggal**:
   - Isi "Tanggal Dari" dan "Tanggal Sampai"
   - Klik tombol "Cari"
2. Atau gunakan **Pencarian**:
   - Ketik nomor surat, nama santri, atau NIS
   - Klik tombol "Cari"
3. Klik "Reset" untuk kembali ke tampilan hari ini

### Menghapus Ijin Santri
1. Klik tombol **"Hapus"** (merah) di kolom Aksi
2. Konfirmasi dengan melihat detail:
   - No. Surat
   - Nama Santri
3. Klik **"Ya, Hapus!"** untuk konfirmasi
4. Data akan terhapus permanen (termasuk foto jika ada)

### Melihat Status Ijin di Data Santri
1. Buka menu **"Data Santri"**
2. Lihat kolom **"Status Ijin"**
3. Santri yang sedang pulang akan memiliki badge **"Pulang"** (kuning)
4. Hover badge untuk melihat detail ijin (tooltip)

## 🔧 Perubahan Teknis

### File yang Dimodifikasi:

1. **Controller**
   - `app/Http/Controllers/IjinSantriController.php`
     - Method `index()`: Tambah filter pencarian, tanggal, dan status
     - Method `exportPdf()`: Mengikuti filter yang sama dengan index
     - Method `destroy()`: Sudah ada (hapus ijin + foto)

   - `app/Http/Controllers/SantriController.php`
     - Method `index()`: Load relasi ijinSantri dengan status "dipulangkan"

2. **Model**
   - `app/Models/Santri.php`
     - Tambah relasi `ijinSantri()`

3. **View**
   - `resources/views/ijin_santri/index.blade.php`
     - Tambah form filter & pencarian
     - Tambah info "Menampilkan ijin hari ini"
     - Tombol hapus dengan SweetAlert2
     - Export PDF dengan query string filter

   - `resources/views/santri/index.blade.php`
     - Tambah kolom "Status Ijin"
     - Badge status pulang/di pesantren
     - Tooltip dengan detail ijin
     - Update colspan empty state

   - `resources/views/ijin_santri/laporan_pdf.blade.php`
     - Header tabel diperbaiki (background solid color)

## 💡 Tips Penggunaan

1. **Monitoring Harian**: 
   - Buka halaman Ijin Santri setiap hari untuk melihat ijin hari ini
   
2. **Laporan Bulanan**:
   - Gunakan filter tanggal untuk bulan tertentu
   - Download PDF sebagai arsip

3. **Pencarian Cepat**:
   - Ketik nama santri untuk melihat semua riwayat ijinnya
   
4. **Integrasi dengan Data Santri**:
   - Cek status real-time santri pulang/tidak di halaman Data Santri

## ⚠️ Catatan Penting

- **Reset Otomatis**: Setiap ganti hari, tampilan akan menampilkan ijin hari baru
- **Riwayat Tetap Ada**: Data lama tidak hilang, gunakan filter untuk mencarinya
- **Hapus Hati-hati**: Data yang dihapus tidak bisa dikembalikan
- **Filter Fleksibel**: Bisa kombinasi pencarian + tanggal + status

## 🚀 Fitur Masa Depan (Opsional)

- Notifikasi saat santri harus kembali
- Export Excel untuk analisis data
- Statistik ijin per santri
- Dashboard grafik ijin bulanan
