# 🏢 PANDUAN PENGGUNAAN SISTEM ROLE DEPARTEMEN

## ✅ INSTALASI SELESAI!

Role untuk setiap departemen sudah berhasil dibuat dengan permission masing-masing.

---

## 📋 DAFTAR ROLE YANG SUDAH DIBUAT

### 1. **Departemen HRD** 👥
**Akses:**
- ✅ Karyawan (Full CRUD)
- ✅ Presensi (View, Approve, Export)
- ✅ Slip Gaji (View, Create)
- ✅ Izin/Cuti (Approve)
- ✅ Lembur (Approve)
- ✅ Laporan Karyawan & Presensi

**Total: 11 permissions aktif**

---

### 2. **Departemen Keuangan** 💰
**Akses:**
- ✅ Dana Operasional (Full CRUD + Approve + Laporan)
- ✅ Pinjaman (View, Approve, Export)
- ✅ Laporan Keuangan (View, Export)
- ✅ Transaksi Keuangan (Create, View)
- ✅ Keuangan Santri (View)

**Total: 17 permissions aktif**

---

### 3. **Departemen Operasional** ⚙️
**Akses:**
- ✅ Inventaris (Full CRUD)
- ✅ Peminjaman Inventaris (Create, Approve)
- ✅ Kendaraan (View)
- ✅ Peminjaman Kendaraan (Create, Approve)

**Total: 13 permissions aktif**

---

### 4. **Departemen Kebersihan** 🧹
**Akses:**
- ✅ Perawatan (Full CRUD)
- ✅ Inventaris (View Only)
- ✅ Checklist Perawatan (Create, Edit)

**Total: 7 permissions aktif**

---

### 5. **Divisi Keagamaan** 🕌
**Akses:**
- ✅ Saung Santri (Full CRUD + Export)
- ✅ Keuangan Santri (Full CRUD)
- ✅ Jamaah (Full CRUD)

**Total: 4 permissions aktif** *(beberapa menu belum ada permission di database)*

---

### 6. **Departemen Maintenance** 🔧
**Akses:**
- ✅ Perawatan (Full CRUD)
- ✅ Service Kendaraan (Full CRUD)
- ✅ Inventaris (View)
- ✅ Tukang (Full CRUD)

**Total: 15 permissions aktif**

---

### 7. **Admin Santri** 📚
**Akses:**
- ✅ Saung Santri (Full CRUD)
- ✅ Keuangan Santri (Full CRUD)
- ✅ Jamaah (Full CRUD)

**Total: 5 permissions aktif** *(beberapa menu belum ada permission di database)*

---

### 8. **Karyawan** (Default Role) 👤
**Akses:**
- ✅ Profile (View, Edit own profile)
- ✅ Presensi (View, Create own)
- ✅ Izin/Cuti (Create own)
- ✅ Slip Gaji (View own)
- ✅ Pinjaman (View, Create own)

**Total: 3 permissions aktif** *(limited access - hanya data sendiri)*

---

## 🚀 CARA MENGGUNAKAN

### **1. Assign Role ke User**

Ada 2 cara:

#### **Cara A: Via Tinker (Command Line)**
```bash
php artisan tinker

# Cari user by email
$user = User::where('email', 'user@email.com')->first();

# Assign role
$user->assignRole('departemen hrd');
# atau
$user->assignRole('departemen keuangan');
# atau
$user->assignRole('departemen kebersihan');

# Check role user
$user->getRoleNames();
```

#### **Cara B: Via Halaman Admin (Jika sudah ada)**
1. Login sebagai **Super Admin**
2. Buka menu **Settings → Users**
3. Edit user yang ingin di-assign role
4. Pilih role dari dropdown
5. Save

---

### **2. Edit Permission Role**

Jika Anda ingin **mengubah permission** suatu role:

1. Login sebagai **Super Admin**
2. Buka menu **Settings → Roles**
3. Klik tombol **Edit Permissions** pada role yang ingin diubah
4. Centang/uncentang permission sesuai kebutuhan
5. Klik **Update Permissions**

**URL langsung:**
```
http://bumisultan.site/manajemen/roles/{role_id}/edit-permissions
```

---

### **3. Cek Permission User**

```bash
php artisan tinker

# Cari user
$user = User::find(1);

# Lihat semua permission user
$user->getAllPermissions();

# Cek apakah user punya permission tertentu
$user->can('perawatan.create'); // true/false
```

---

## 🔐 PROTEKSI ROUTE

Untuk melindungi route agar hanya user dengan permission tertentu yang bisa akses:

### **Di routes/web.php:**

**SEBELUM:**
```php
Route::get('/perawatan', [PerawatanController::class, 'index'])
    ->name('perawatan.index');
```

**SESUDAH:**
```php
Route::get('/perawatan', [PerawatanController::class, 'index'])
    ->middleware('permission:perawatan.index')
    ->name('perawatan.index');
```

**Contoh untuk semua CRUD:**
```php
Route::middleware(['auth', 'permission:perawatan.index'])->group(function () {
    Route::get('/perawatan', [PerawatanController::class, 'index'])->name('perawatan.index');
    Route::get('/perawatan/create', [PerawatanController::class, 'create'])
        ->middleware('permission:perawatan.create');
    Route::post('/perawatan', [PerawatanController::class, 'store'])
        ->middleware('permission:perawatan.create');
    Route::get('/perawatan/{id}/edit', [PerawatanController::class, 'edit'])
        ->middleware('permission:perawatan.edit');
    Route::put('/perawatan/{id}', [PerawatanController::class, 'update'])
        ->middleware('permission:perawatan.edit');
    Route::delete('/perawatan/{id}', [PerawatanController::class, 'destroy'])
        ->middleware('permission:perawatan.delete');
});
```

---

## 👁️ HIDE/SHOW MENU BERDASARKAN PERMISSION

### **Di Sidebar/Navbar (Blade Template):**

**SEBELUM:**
```blade
<li>
    <a href="{{ route('perawatan.index') }}">
        <i class="ti ti-tools"></i>
        <span>Perawatan</span>
    </a>
</li>
```

**SESUDAH:**
```blade
@can('perawatan.index')
<li>
    <a href="{{ route('perawatan.index') }}">
        <i class="ti ti-tools"></i>
        <span>Perawatan</span>
    </a>
</li>
@endcan
```

**Untuk submenu:**
```blade
@can('perawatan.index')
<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon ti ti-tools"></i>
        <div>Perawatan</div>
    </a>
    <ul class="menu-sub">
        @can('perawatan.index')
        <li class="menu-item">
            <a href="{{ route('perawatan.index') }}" class="menu-link">
                <div>Daftar Perawatan</div>
            </a>
        </li>
        @endcan
        
        @can('perawatan.create')
        <li class="menu-item">
            <a href="{{ route('perawatan.create') }}" class="menu-link">
                <div>Tambah Perawatan</div>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan
```

---

## 🔧 TROUBLESHOOTING

### **1. Permission tidak muncul setelah assign**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan permission:cache-reset

# Logout dan login lagi
```

### **2. User masih bisa akses halaman padahal tidak punya permission**
- Pastikan route sudah di-protect dengan `middleware('permission:...')`
- Clear cache: `php artisan cache:clear`
- Cek apakah user punya role **Super Admin** (super admin bypass semua permission)

### **3. Menu tidak hilang padahal user tidak punya permission**
- Pastikan blade menggunakan `@can('permission.name')`
- Clear view cache: `php artisan view:clear`
- Hard refresh browser: **Ctrl + Shift + R**

### **4. Error "Permission does not exist"**
```bash
# Cek permission yang ada
php artisan tinker
Permission::pluck('name');

# Jika permission tidak ada, buat dulu di database
# atau jalankan seeder permission
php artisan db:seed --class=PermissionSystemMasterSeeder
```

---

## 📊 MONITORING & AUDIT

### **Cek User dengan Role Tertentu:**
```bash
php artisan tinker

# Semua user dengan role "Departemen HRD"
$users = User::role('departemen hrd')->get();
foreach($users as $u) {
    echo "{$u->name} - {$u->email}\n";
}
```

### **Cek Permission yang Belum Ter-assign:**
```bash
php artisan tinker

$role = Role::findByName('departemen kebersihan');
$allPermissions = Permission::count();
$assignedPermissions = $role->permissions->count();
echo "Assigned: {$assignedPermissions} / {$allPermissions}\n";
```

---

## ⚠️ CATATAN PENTING

1. **Super Admin** memiliki akses ke SEMUA permission (bypass semua proteksi)
2. **Karyawan** (role default) hanya bisa akses data milik sendiri
3. Jika permission tidak ditemukan saat seeder, artinya permission belum dibuat di database
4. Setiap kali ubah permission, **logout dan login lagi** atau clear cache
5. Permission bersifat **additive** - satu user bisa punya multiple roles

---

## 🚀 NEXT STEPS

Untuk melengkapi sistem:

1. ✅ **Update semua routes** dengan middleware permission
2. ✅ **Update sidebar/navbar** dengan `@can()`
3. ✅ **Buat permission untuk menu yang belum ada**
4. ✅ **Testing akses per departemen**
5. ✅ **Deploy ke server**

---

## 📞 BANTUAN

Jika ada masalah atau pertanyaan, hubungi administrator sistem atau cek dokumentasi lengkap di:
- [ANALISIS_SISTEM_ROLE_DEPARTEMEN.md](ANALISIS_SISTEM_ROLE_DEPARTEMEN.md)

---

**Dibuat:** {{ date('d M Y') }}
**Sistem:** Bumisultan Management System
