# 🚀 PANDUAN INSTALASI SISTEM MANAJEMEN INVENTARIS
## Super App v2 - Bumi Sultan

---

## 📋 CHECKLIST INSTALASI

- [ ] 1. Rename Controller Files
- [ ] 2. Run Database Migrations
- [ ] 3. Update Routes
- [ ] 4. Install Dependencies
- [ ] 5. Update Sidebar Menu
- [ ] 6. Run Seeder (Optional)
- [ ] 7. Testing

---

## 🔧 LANGKAH INSTALASI DETAIL

### STEP 1: Rename Controller Files

Controller files sudah dibuat dengan suffix `_full`. Rename semua file:

```bash
cd app/Http/Controllers

# Windows PowerShell
Rename-Item PeminjamanInventarisController_full.php PeminjamanInventarisController.php
Rename-Item PengembalianInventarisController_full.php PengembalianInventarisController.php
Rename-Item InventarisEventController_full.php InventarisEventController.php
Rename-Item HistoryInventarisController_full.php HistoryInventarisController.php

# Atau manual: rename via File Explorer
```

**File yang perlu di-rename:**
- `PeminjamanInventarisController_full.php` → `PeminjamanInventarisController.php`
- `PengembalianInventarisController_full.php` → `PengembalianInventarisController.php`
- `InventarisEventController_full.php` → `InventarisEventController.php`
- `HistoryInventarisController_full.php` → `HistoryInventarisController.php`

**Catatan:** `InventarisController.php` sudah OK, tidak perlu rename.

---

### STEP 2: Run Database Migrations

Jalankan migrations untuk membuat semua tabel:

```bash
php artisan migrate
```

**Tabel yang akan dibuat:**
1. ✅ `inventaris` - Master data inventaris
2. ✅ `peminjaman_inventaris` - Data peminjaman
3. ✅ `pengembalian_inventaris` - Data pengembalian
4. ✅ `inventaris_events` - Event inventaris
5. ✅ `inventaris_event_items` - Pivot table event & inventaris
6. ✅ `history_inventaris` - Tracking history

**Jika ada error:**
- Check foreign key constraints
- Pastikan tabel `users`, `karyawans`, `cabangs`, `barangs` sudah ada
- Run: `php artisan migrate:fresh` (HATI-HATI: akan reset database)

---

### STEP 3: Update Routes

Buka file `routes/web.php` dan tambahkan routes dari file `INVENTARIS_ROUTES.php`:

```php
// Copy semua isi dari INVENTARIS_ROUTES.php
// Paste di routes/web.php (di dalam middleware auth group yang sudah ada)
```

**Atau tambahkan di akhir file:**

```php
// Include Inventaris Routes
require __DIR__.'/inventaris.php';
```

Lalu buat file baru `routes/inventaris.php` dan copy isi dari `INVENTARIS_ROUTES.php`

---

### STEP 4: Install Dependencies

Install DomPDF untuk export PDF:

```bash
composer require barryvdh/laravel-dompdf
```

Publish config:

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

**Optional:** Install Laravel Excel untuk export Excel

```bash
composer require maatwebsite/excel
```

---

### STEP 5: Storage Link

Buat symbolic link untuk storage (jika belum):

```bash
php artisan storage:link
```

**Pastikan folder berikut ada:**
- `storage/app/public/inventaris/`
- `storage/app/public/peminjaman/`
- `storage/app/public/pengembalian/`

---

### STEP 6: Update Sidebar Menu

Edit file sidebar view Anda (biasanya di `resources/views/layouts/sidebar.blade.php` atau `resources/views/components/sidebar.blade.php`)

**Cari menu "Fasilitas & Asset" dan tambahkan submenu:**

```html
<!-- Fasilitas & Asset Menu -->
<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-building"></i>
        <p>
            Fasilitas & Asset
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <!-- Existing submenu -->
        <li class="nav-item">
            <a href="{{ route('gedung.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Gedung</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('barang.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Barang</p>
            </a>
        </li>
        
        <!-- NEW: Submenu Inventaris -->
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                    Manajemen Inventaris
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('inventaris.index') }}" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Master Inventaris</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('peminjaman-inventaris.index') }}" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Peminjaman</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengembalian-inventaris.index') }}" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Pengembalian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('inventaris-event.index') }}" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Inventaris Event</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('history-inventaris.index') }}" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>History & Tracking</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('history-inventaris.dashboard') }}" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Dashboard Analytics</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>
```

**Untuk Tailwind sidebar (jika menggunakan Tailwind):**

```html
<!-- Fasilitas & Asset -->
<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <span class="flex-1 text-left">Fasilitas & Asset</span>
        <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    
    <div x-show="open" class="pl-11 space-y-1">
        <a href="{{ route('gedung.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gedung</a>
        <a href="{{ route('barang.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Barang</a>
        
        <!-- Submenu Inventaris -->
        <div x-data="{ openInv: false }" class="space-y-1">
            <button @click="openInv = !openInv" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <span class="flex-1 text-left">Manajemen Inventaris</span>
                <svg :class="{'rotate-90': openInv}" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <div x-show="openInv" class="pl-4 space-y-1">
                <a href="{{ route('inventaris.index') }}" class="block px-4 py-2 text-xs text-gray-600 hover:bg-gray-100">Master Inventaris</a>
                <a href="{{ route('peminjaman-inventaris.index') }}" class="block px-4 py-2 text-xs text-gray-600 hover:bg-gray-100">Peminjaman</a>
                <a href="{{ route('pengembalian-inventaris.index') }}" class="block px-4 py-2 text-xs text-gray-600 hover:bg-gray-100">Pengembalian</a>
                <a href="{{ route('inventaris-event.index') }}" class="block px-4 py-2 text-xs text-gray-600 hover:bg-gray-100">Inventaris Event</a>
                <a href="{{ route('history-inventaris.index') }}" class="block px-4 py-2 text-xs text-gray-600 hover:bg-gray-100">History & Tracking</a>
                <a href="{{ route('history-inventaris.dashboard') }}" class="block px-4 py-2 text-xs text-gray-600 hover:bg-gray-100">Dashboard Analytics</a>
            </div>
        </div>
    </div>
</div>
```

---

### STEP 7: Create Views (Blade Templates)

Views belum dibuat. Anda perlu membuat blade templates untuk:

**Master Inventaris:**
- `resources/views/inventaris/index.blade.php`
- `resources/views/inventaris/create.blade.php`
- `resources/views/inventaris/edit.blade.php`
- `resources/views/inventaris/show.blade.php`
- `resources/views/inventaris/import-barang.blade.php`
- `resources/views/inventaris/pdf.blade.php`
- `resources/views/inventaris/aktivitas-pdf.blade.php`

**Peminjaman:**
- `resources/views/peminjaman-inventaris/index.blade.php`
- `resources/views/peminjaman-inventaris/create.blade.php`
- `resources/views/peminjaman-inventaris/edit.blade.php`
- `resources/views/peminjaman-inventaris/show.blade.php`
- `resources/views/peminjaman-inventaris/pdf.blade.php`

**Pengembalian:**
- `resources/views/pengembalian-inventaris/index.blade.php`
- `resources/views/pengembalian-inventaris/create.blade.php`
- `resources/views/pengembalian-inventaris/show.blade.php`
- `resources/views/pengembalian-inventaris/select-peminjaman.blade.php`
- `resources/views/pengembalian-inventaris/pdf.blade.php`

**Event:**
- `resources/views/inventaris-event/index.blade.php`
- `resources/views/inventaris-event/create.blade.php`
- `resources/views/inventaris-event/edit.blade.php`
- `resources/views/inventaris-event/show.blade.php`
- `resources/views/inventaris-event/add-inventaris.blade.php`
- `resources/views/inventaris-event/distribusi-karyawan.blade.php`
- `resources/views/inventaris-event/pdf.blade.php`

**History:**
- `resources/views/history-inventaris/index.blade.php`
- `resources/views/history-inventaris/show.blade.php`
- `resources/views/history-inventaris/dashboard.blade.php`
- `resources/views/history-inventaris/by-inventaris.blade.php`
- `resources/views/history-inventaris/by-karyawan.blade.php`
- `resources/views/history-inventaris/pdf.blade.php`

**NOTE:** Views akan dibuat sebagai file terpisah karena cukup banyak.

---

### STEP 8: Seeder (Optional)

Buat seeder untuk data sample:

```bash
php artisan make:seeder InventarisSeeder
```

Edit `database/seeders/InventarisSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventaris;
use App\Models\Cabang;

class InventarisSeeder extends Seeder
{
    public function run()
    {
        $cabang = Cabang::first();
        
        $inventaris = [
            [
                'nama_barang' => 'Laptop Dell Latitude 5420',
                'kategori' => 'Elektronik',
                'merk' => 'Dell',
                'tipe_model' => 'Latitude 5420',
                'nomor_seri' => 'DL-2024-001',
                'jumlah' => 5,
                'satuan' => 'unit',
                'harga_perolehan' => 15000000,
                'kondisi' => 'baik',
                'status' => 'tersedia',
                'lokasi_penyimpanan' => 'Gudang IT',
                'cabang_id' => $cabang->id,
                'created_by' => 1,
            ],
            [
                'nama_barang' => 'Tenda Camping 4 Orang',
                'kategori' => 'Camping & Outdoor',
                'merk' => 'Eiger',
                'jumlah' => 10,
                'satuan' => 'unit',
                'harga_perolehan' => 2500000,
                'kondisi' => 'baik',
                'status' => 'tersedia',
                'lokasi_penyimpanan' => 'Gudang Olahraga',
                'cabang_id' => $cabang->id,
                'created_by' => 1,
            ],
            [
                'nama_barang' => 'Sleeping Bag',
                'kategori' => 'Camping & Outdoor',
                'merk' => 'Consina',
                'jumlah' => 20,
                'satuan' => 'unit',
                'harga_perolehan' => 500000,
                'kondisi' => 'baik',
                'status' => 'tersedia',
                'lokasi_penyimpanan' => 'Gudang Olahraga',
                'cabang_id' => $cabang->id,
                'created_by' => 1,
            ],
            // Add more sample data...
        ];

        foreach ($inventaris as $item) {
            Inventaris::create($item);
        }
    }
}
```

Run seeder:

```bash
php artisan db:seed --class=InventarisSeeder
```

---

### STEP 9: Testing

Test semua fitur:

1. **Master Inventaris:**
   - ✅ Create inventaris baru
   - ✅ Upload foto
   - ✅ Import dari Barang
   - ✅ Edit inventaris
   - ✅ Delete inventaris
   - ✅ Filter & Search
   - ✅ Export PDF

2. **Peminjaman:**
   - ✅ Ajukan peminjaman
   - ✅ TTD digital peminjam
   - ✅ Approval/Reject
   - ✅ Check ketersediaan
   - ✅ Export PDF

3. **Pengembalian:**
   - ✅ Proses pengembalian
   - ✅ TTD digital
   - ✅ Deteksi keterlambatan
   - ✅ Hitung denda
   - ✅ Check kondisi barang
   - ✅ Export PDF

4. **Event:**
   - ✅ Create event
   - ✅ Add inventaris
   - ✅ Cek ketersediaan
   - ✅ Distribusi ke karyawan
   - ✅ Export PDF

5. **History:**
   - ✅ View history
   - ✅ Filter by inventaris/karyawan
   - ✅ Dashboard analytics
   - ✅ Export PDF

---

## ⚠️ TROUBLESHOOTING

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: Foreign key constraint fails
Pastikan tabel parent sudah ada (`users`, `karyawans`, `cabangs`, `barangs`)

### Error: Storage link not found
```bash
php artisan storage:link
```

### Error: PDF generation failed
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Error: Route not found
Check routes dengan:
```bash
php artisan route:list | findstr inventaris
```

---

## 📊 DATABASE SCHEMA

### ER Diagram (Simplified)

```
users ──┬── inventaris ──┬── peminjaman_inventaris ──── pengembalian_inventaris
        │                │
        │                └── inventaris_event_items ──── inventaris_events
        │                │
        │                └── history_inventaris
        │
karyawans ──────────────┼── peminjaman_inventaris
                         │
                         └── history_inventaris

cabangs ────────────────── inventaris

barangs ────────────────── inventaris
```

---

## 🎯 NEXT STEPS

1. ✅ Install & Setup (Sudah lengkap)
2. ⏳ Create Views (Blade Templates) - **TODO**
3. ⏳ Add Permissions & Roles - **Optional**
4. ⏳ Add API Endpoints - **Optional**
5. ⏳ Add Notifications (Email/WhatsApp) - **Optional**
6. ⏳ Add Barcode Scanner - **Optional**
7. ⏳ Add Mobile App - **Optional**

---

## ✅ CHECKLIST FINAL

- [ ] All migrations run successfully
- [ ] All models created
- [ ] All controllers working
- [ ] Routes registered
- [ ] Sidebar menu updated
- [ ] Views created
- [ ] TTD digital working
- [ ] Photo upload working
- [ ] PDF export working
- [ ] Auto calculations working (denda, keterlambatan)
- [ ] History tracking working
- [ ] Event system working
- [ ] Import from Barang working

---

**Status:** Ready for Implementation
**Estimated Time:** 4-6 hours (with views)
**Last Update:** 2025-11-06

---

✨ **HAPPY CODING!** ✨
