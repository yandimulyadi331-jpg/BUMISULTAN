# Modul Fasilitas & Asset - Manajemen Gedung, Ruangan, dan Barang

## Deskripsi
Modul ini mengelola Fasilitas & Asset perusahaan dengan hierarki:
**Gedung → Ruangan → Barang**

Semua modul saling terintegrasi dengan fitur transfer barang antar ruangan/gedung.

## Struktur Database

### Tabel: gedungs
- id (PK)
- kode_gedung (unique)
- nama_gedung
- alamat
- kode_cabang (FK ke cabang)
- jumlah_lantai
- keterangan
- timestamps

### Tabel: ruangans
- id (PK)
- kode_ruangan (unique)
- gedung_id (FK ke gedungs, cascade delete)
- nama_ruangan
- lantai
- luas (m²)
- kapasitas
- keterangan
- timestamps

### Tabel: barangs
- id (PK)
- kode_barang (unique)
- ruangan_id (FK ke ruangans, cascade delete)
- nama_barang
- kategori
- merk
- jumlah
- satuan
- kondisi (Baik, Rusak Ringan, Rusak Berat)
- tanggal_perolehan
- harga_perolehan
- keterangan
- timestamps

### Tabel: transfer_barangs
- id (PK)
- kode_transfer (unique, auto-generated)
- barang_id (FK ke barangs)
- ruangan_asal_id (FK ke ruangans)
- ruangan_tujuan_id (FK ke ruangans)
- jumlah_transfer
- tanggal_transfer
- petugas
- keterangan
- timestamps

## Fitur Utama

### 1. Manajemen Gedung
- **CRUD Gedung**: Create, Read, Update, Delete gedung
- **Filter**: Pencarian berdasarkan nama gedung dan cabang
- **Info**: Menampilkan total ruangan dan total barang per gedung
- **Akses**: Klik icon 🚪 untuk melihat ruangan di gedung tersebut

**URL**: `/gedung`

### 2. Manajemen Ruangan
- **CRUD Ruangan**: Create, Read, Update, Delete ruangan per gedung
- **Data**: Kode, nama, lantai, luas, kapasitas
- **Info**: Menampilkan total barang per ruangan
- **Akses**: Klik icon 📦 untuk melihat barang di ruangan tersebut
- **Validasi**: Tidak bisa hapus ruangan yang masih ada barang

**URL**: `/gedung/{gedung_id}/ruangan`

### 3. Manajemen Barang
- **CRUD Barang**: Create, Read, Update, Delete barang per ruangan
- **Data**: Kode, nama, kategori, merk, jumlah, satuan, kondisi, harga
- **Status Kondisi**: Baik (hijau), Rusak Ringan (kuning), Rusak Berat (merah)
- **Transfer**: Fitur untuk memindahkan barang ke ruangan lain

**URL**: `/gedung/{gedung_id}/ruangan/{ruangan_id}/barang`

### 4. Transfer Barang
- **Proses Transfer**: Memindahkan barang dari satu ruangan ke ruangan lain
- **Validasi**: Cek stok tersedia sebelum transfer
- **Auto-Generate**: Kode transfer otomatis (format: TRF-YYYYMMDD-XXXX)
- **Logic**:
  - Mengurangi stok di ruangan asal
  - Menambah stok di ruangan tujuan (buat baru jika belum ada)
  - Hapus barang jika stok di ruangan asal menjadi 0
- **Riwayat**: Mencatat semua transfer yang dilakukan

**URL Transfer**: `/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/transfer`

## Alur Penggunaan

### Untuk Admin:
1. Login sebagai Super Admin
2. Buka menu **Fasilitas & Asset** → **Manajemen Gedung**
3. Tambah gedung baru atau pilih gedung yang ada
4. Dari daftar gedung, klik icon 🚪 untuk akses ruangan
5. Tambah ruangan di gedung tersebut
6. Dari daftar ruangan, klik icon 📦 untuk akses barang
7. Tambah barang di ruangan tersebut
8. Untuk transfer barang, klik icon ↔️ di daftar barang
9. Pilih ruangan tujuan dan jumlah yang akan ditransfer
10. Submit untuk proses transfer

## Routes
Semua routes dibatasi untuk role **Super Admin** saja.

```php
// Gedung
GET    /gedung                                 -> index
GET    /gedung/create                         -> create
POST   /gedung                                -> store
GET    /gedung/{id}/edit                      -> edit
PUT    /gedung/{id}/update                    -> update
DELETE /gedung/{id}/delete                    -> destroy

// Ruangan
GET    /gedung/{gedung_id}/ruangan            -> index
GET    /gedung/{gedung_id}/ruangan/create     -> create
POST   /gedung/{gedung_id}/ruangan            -> store
GET    /gedung/{gedung_id}/ruangan/{id}/edit  -> edit
PUT    /gedung/{gedung_id}/ruangan/{id}/update -> update
DELETE /gedung/{gedung_id}/ruangan/{id}/delete -> destroy

// Barang
GET    /gedung/{gedung_id}/ruangan/{ruangan_id}/barang              -> index
GET    /gedung/{gedung_id}/ruangan/{ruangan_id}/barang/create       -> create
POST   /gedung/{gedung_id}/ruangan/{ruangan_id}/barang              -> store
GET    /gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/edit    -> edit
PUT    /gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/update  -> update
DELETE /gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/delete  -> destroy
GET    /gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/transfer -> transfer form
POST   /gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/proses-transfer -> process transfer
```

## Models & Relationships

### Gedung Model
```php
- belongsTo: Cabang
- hasMany: Ruangan
```

### Ruangan Model
```php
- belongsTo: Gedung
- hasMany: Barang
- hasMany: TransferBarang (as asal & tujuan)
```

### Barang Model
```php
- belongsTo: Ruangan
- hasMany: TransferBarang
```

### TransferBarang Model
```php
- belongsTo: Barang
- belongsTo: Ruangan (ruangan_asal_id)
- belongsTo: Ruangan (ruangan_tujuan_id)
```

## Controllers

1. **GedungController**: Mengelola CRUD gedung
2. **RuanganController**: Mengelola CRUD ruangan per gedung
3. **BarangController**: Mengelola CRUD barang dan transfer

## Views Structure

```
resources/views/fasilitas/
├── gedung/
│   ├── index.blade.php   (List gedung)
│   ├── create.blade.php  (Form tambah gedung)
│   └── edit.blade.php    (Form edit gedung)
├── ruangan/
│   ├── index.blade.php   (List ruangan per gedung)
│   ├── create.blade.php  (Form tambah ruangan)
│   └── edit.blade.php    (Form edit ruangan)
└── barang/
    ├── index.blade.php   (List barang per ruangan)
    ├── create.blade.php  (Form tambah barang)
    ├── edit.blade.php    (Form edit barang)
    └── transfer.blade.php (Form transfer barang)
```

## Security
- Semua ID di-encrypt menggunakan Laravel Crypt
- Validasi cascade delete untuk mencegah data orphan
- Middleware role: hanya Super Admin yang bisa akses
- Validasi stok sebelum transfer

## Catatan Penting
1. **Cascade Delete**: 
   - Hapus gedung → hapus semua ruangan dan barang di dalamnya
   - Hapus ruangan → hapus semua barang di dalamnya
2. **Validasi Delete**:
   - Gedung tidak bisa dihapus jika masih ada ruangan
   - Ruangan tidak bisa dihapus jika masih ada barang
3. **Transfer Barang**:
   - Jika barang sudah ada di tujuan (kode sama), jumlah akan ditambahkan
   - Jika barang belum ada di tujuan, akan dibuat record baru
   - Jika stok di asal habis (0), record barang akan dihapus otomatis

## Migration Commands
```bash
# Menjalankan migration
php artisan migrate

# Rollback jika perlu
php artisan migrate:rollback --step=4
```

## Testing
Setelah implementasi:
1. ✅ Buat gedung baru
2. ✅ Buat ruangan di gedung
3. ✅ Buat barang di ruangan
4. ✅ Transfer barang ke ruangan lain
5. ✅ Validasi cascade delete
6. ✅ Cek integrasi antar modul

## Support
Untuk pertanyaan atau bug report, hubungi tim development.
