# QUICK SETUP - Keuangan Santri

## 🚀 Langkah Setup Cepat

### 1. Jalankan Migration (Sudah Selesai ✅)
```bash
php artisan migrate
php artisan db:seed --class=KeuanganSantriCategorySeeder
```

### 2. Tambahkan Permissions ke Database

**Jalankan query SQL ini:**

```sql
-- Insert Permissions
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('keuangan-santri.index', 'web', NOW(), NOW()),
('keuangan-santri.create', 'web', NOW(), NOW()),
('keuangan-santri.edit', 'web', NOW(), NOW()),
('keuangan-santri.delete', 'web', NOW(), NOW()),
('keuangan-santri.laporan', 'web', NOW(), NOW()),
('keuangan-santri.import', 'web', NOW(), NOW()),
('keuangan-santri.verify', 'web', NOW(), NOW());
```

**ATAU jalankan di Tinker:**

```bash
php artisan tinker
```

```php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Create permissions
$permissions = [
    'keuangan-santri.index',
    'keuangan-santri.create',
    'keuangan-santri.edit',
    'keuangan-santri.delete',
    'keuangan-santri.laporan',
    'keuangan-santri.import',
    'keuangan-santri.verify',
];

foreach ($permissions as $permission) {
    Permission::create(['name' => $permission, 'guard_name' => 'web']);
}

// Assign to super admin
$role = Role::findByName('super admin');
$role->givePermissionTo($permissions);

echo "Permissions created and assigned to super admin!\n";
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### 4. Test Access
1. Login sebagai Super Admin
2. Klik menu **Manajemen Saung Santri**
3. Klik **Keuangan Santri**
4. Seharusnya bisa akses dashboard

---

## 📋 Checklist Akhir

- [x] Migration dijalankan
- [x] Seeder kategori dijalankan
- [x] Menu navigasi muncul
- [ ] Permissions ditambahkan ke database
- [ ] Super admin bisa akses
- [ ] Test input transaksi
- [ ] Test auto-kategorisasi
- [ ] Test export PDF
- [ ] Test import Excel

---

## 🔧 Troubleshooting

### Menu tidak muncul?
Pastikan login sebagai **super admin** (cek role di `model_has_roles`)

### Permission denied?
Jalankan langkah 2 di atas untuk insert permissions

### Auto-detect tidak jalan?
1. Cek network tab di browser (harus ada AJAX call)
2. Pastikan route `/keuangan-santri/api/detect-category` accessible
3. Cek console browser untuk error JavaScript

### PDF tidak download?
Pastikan package `barryvdh/laravel-dompdf` sudah terinstall:
```bash
composer require barryvdh/laravel-dompdf
```

### Import Excel error?
Pastikan package `maatwebsite/excel` sudah terinstall:
```bash
composer require maatwebsite/excel
```

---

## 🎯 Quick Test Data

### Tambah Transaksi Manual:
1. Santri: Pilih salah satu
2. Jenis: Pengeluaran
3. Deskripsi: **"Beli sabun dan shampo"**
4. Jumlah: 25000
5. Submit → Lihat auto-detect ke **Kebersihan & Kesehatan**

### Test dengan berbagai deskripsi:
- "Makan nasi goreng" → **Makanan & Minuman**
- "Beli buku dan pulpen" → **Pendidikan & Alat Tulis**
- "Pulsa internet" → **Komunikasi & Pulsa**
- "Ongkos angkot" → **Transportasi**
- "Laundry baju" → **Pakaian & Laundry**

---

## 📞 Contact

Jika ada issue, cek:
1. **DOKUMENTASI_KEUANGAN_SANTRI.md** → Dokumentasi lengkap
2. Comment di code Controller & Service
3. Log Laravel: `storage/logs/laravel.log`

**Happy Coding! 🎉**
