# FIX: Foreign Key Constraint Violation Distribusi Hadiah

## Masalah
Error saat insert ke tabel `distribusi_hadiah`:
```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: 
a foreign key constraint fails (`bumisultansuperapp_v2`.`distribusi_hadiah`, 
CONSTRAINT `distribusi_hadiah_jamaah_id_foreign` FOREIGN KEY (`jamaah_id`) 
REFERENCES `jamaah_majlis_taklim` (`id`) ON DELETE CASCADE)
```

**Gejala tambahan:** Dropdown jamaah di form tidak muncul dengan data yang benar.

## Root Cause
Ada **multiple issues** yang menyebabkan error:

### Issue 1: Validasi & Query Mismatch (Controller)
**File**: `app/Http/Controllers/HadiahMajlisTaklimController.php`

```php
// ❌ SALAH - Validasi mengecek di yayasan_masar, tapi FK referensi ke jamaah_majlis_taklim
$rules['jamaah_id'] = 'required|exists:yayasan_masar,kode_yayasan';

// Data yang di-insert:
$jamaahId = $request->jamaah_id;  // nilai 251200009 dari yayasan_masar.kode_yayasan
DistribusiHadiah::create([
    'jamaah_id' => $jamaahId,  // ❌ Tidak ada di jamaah_majlis_taklim
]);
```

### Issue 2: Dropdown Tidak Menampilkan Data Correct (Controller)
```php
// ❌ SALAH - Query dari YayasanMasar dengan field yang wrong
$jamaahList = YayasanMasar::where('status_aktif', 1)
    ->get(['kode_yayasan', 'nama', 'no_hp']);  // Wrong table
```

### Issue 3: Blade Template Menggunakan Field yang Salah
```blade
<!-- ❌ SALAH -->
<option value="{{ $jamaah->kode_yayasan }}">
    {{ $jamaah->nama_jamaah }}
</option>
```

### Issue 4: Model Relasi yang Salah
```php
// ❌ SALAH di DistribusiHadiah model
public function jamaah()
{
    return $this->belongsTo(YayasanMasar::class, 'jamaah_id');
}
```

## Data yang Dievaluasi
- **yayasan_masar.kode_yayasan** = `251200009` (Nama: DANI, ID: 9)
- **jamaah_majlis_taklim** = Total 14 jamaah, range ID: 2-15
- **Tidak ada jamaah dengan nama DANI di jamaah_majlis_taklim**

## Solusi yang Diimplementasikan

### 1. Update Validasi & Query (3 method)

#### a) Method `distribusiForm()` - Baris 336-339
```php
// SEBELUM:
$jamaahList = YayasanMasar::where('status_aktif', 1)
    ->orderBy('nama')
    ->get(['kode_yayasan', 'nama', 'no_hp']);

// SESUDAH:
$jamaahList = JamaahMajlisTaklim::where('status_aktif', 1)
    ->orderBy('nama_jamaah')
    ->get(['id', 'nomor_jamaah', 'nama_jamaah', 'no_telepon']);
```

#### b) Method `updateDistribusi()` - Baris 562-565
```php
// SEBELUM:
$jamaahList = YayasanMasar::where('status_aktif', 1)
    ->orderBy('nama')
    ->get(['kode_yayasan', 'nama', 'no_hp']);

// SESUDAH:
$jamaahList = JamaahMajlisTaklim::where('status_aktif', 1)
    ->orderBy('nama_jamaah')
    ->get(['id', 'nomor_jamaah', 'nama_jamaah', 'no_telepon']);
```

#### c) Method `storeDistribusiKaryawan()` - Baris 920-923
```php
// SEBELUM:
$jamaahList = YayasanMasar::where('status_aktif', 1)
    ->orderBy('nama')
    ->get(['kode_yayasan', 'nama', 'no_hp']);

// SESUDAH:
$jamaahList = JamaahMajlisTaklim::where('status_aktif', 1)
    ->orderBy('nama_jamaah')
    ->get(['id', 'nomor_jamaah', 'nama_jamaah', 'no_telepon']);
```

### 2. Update Validasi Rules (3 lokasi)

#### a) Baris 376
```php
// SEBELUM:
$rules['jamaah_id'] = 'required|exists:yayasan_masar,kode_yayasan';

// SESUDAH:
$rules['jamaah_id'] = 'required|exists:jamaah_majlis_taklim,id';
```

#### b) Baris 579
```php
// SEBELUM:
'jamaah_id' => 'required|exists:yayasan_masar,kode_yayasan',

// SESUDAH:
'jamaah_id' => 'nullable|exists:jamaah_majlis_taklim,id',
```

#### c) Baris 951
```php
// SEBELUM:
if ($request->tipe_penerima === 'jamaah') {
    $rules['jamaah_id'] = 'required|exists:yayasan_masar,kode_yayasan';
}

// SESUDAH:
if ($request->tipe_penerima === 'jamaah') {
    $rules['jamaah_id'] = 'required|exists:jamaah_majlis_taklim,id';
}
```

### 3. Update Fetch Jamaah untuk storeDistribusi

#### Baris 405-416
```php
// SEBELUM:
if ($request->tipe_penerima === 'jamaah') {
    $jamaahId = $request->jamaah_id;
    $jamaah = YayasanMasar::where('kode_yayasan', $jamaahId)->firstOrFail();
    $keteranganPenerima = "Jamaah: {$jamaah->nama}";
}

// SESUDAH:
if ($request->tipe_penerima === 'jamaah') {
    // Ambil dari jamaah_majlis_taklim
    $jamaahId = $request->jamaah_id;
    $jamaah = JamaahMajlisTaklim::findOrFail($jamaahId);
    $keteranganPenerima = "Jamaah: {$jamaah->nama_jamaah}";
} else {
    // Non-jamaah - set jamaah_id = NULL
    $jamaahId = null;
    $keteranganPenerima = "Non-Jamaah: {$request->penerima_nama_umum}";
    // ...
}
```

### 4. Update Fetch Jamaah untuk storeDistribusiKaryawan

#### Baris 980-992
```php
// SEBELUM:
if ($request->tipe_penerima === 'jamaah') {
    $jamaah = YayasanMasar::where('kode_yayasan', $request->jamaah_id)->firstOrFail();
    $keteranganPenerima = "Jamaah Terdaftar: {$jamaah->nama}";
}

// SESUDAH:
$jamaahIdForRecord = null;

if ($request->tipe_penerima === 'jamaah') {
    $jamaahIdForRecord = $request->jamaah_id;
    $jamaah = JamaahMajlisTaklim::findOrFail($jamaahIdForRecord);
    $keteranganPenerima = "Jamaah Terdaftar: {$jamaah->nama_jamaah}";
} else {
    // Non-jamaah - set jamaah_id = NULL
    $jamaahIdForRecord = null;
    $keteranganPenerima = "Penerima Umum: {$request->penerima_nama_umum}";
    // ...
}
```

### 5. Update Blade Template (3 file)

#### a) `resources/views/majlistaklim/hadiah/distribusi.blade.php` - Baris 40-50
```blade
<!-- SEBELUM: -->
<label class="form-label fw-bold">Pilih Jamaah Yayasan MASAR <span class="text-danger">*</span></label>
<select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror">
    <option value="">-- Cari Jamaah (ketik nama) --</option>
    @foreach($jamaahList as $jamaah)
        <option value="{{ $jamaah->kode_yayasan }}" {{ old('jamaah_id') == $jamaah->kode_yayasan ? 'selected' : '' }}>
            {{ $jamaah->nama_jamaah }}
        </option>
    @endforeach
</select>

<!-- SESUDAH: -->
<label class="form-label fw-bold">Pilih Jamaah Majlis Taklim <span class="text-danger">*</span></label>
<select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror">
    <option value="">-- Cari Jamaah (ketik nama) --</option>
    @foreach($jamaahList as $jamaah)
        <option value="{{ $jamaah->id }}" {{ old('jamaah_id') == $jamaah->id ? 'selected' : '' }}>
            {{ $jamaah->nama_jamaah }} ({{ $jamaah->nomor_jamaah }})
        </option>
    @endforeach
</select>
```

#### b) `resources/views/majlistaklim/hadiah/edit_distribusi.blade.php` - Baris 57-70
```blade
<!-- SEBELUM: -->
<label class="form-label">Pilih Jamaah Yayasan MASAR <span class="text-danger">*</span></label>
<select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror" required>
    <option value="">-- Cari Jamaah --</option>
    @foreach($jamaahList as $jamaah)
        <option value="{{ $jamaah->kode_yayasan }}" {{ old('jamaah_id', $distribusi->jamaah_id) == $jamaah->kode_yayasan ? 'selected' : '' }}>
            {{ $jamaah->nama }}
        </option>
    @endforeach
</select>

<!-- SESUDAH: -->
<label class="form-label">Pilih Jamaah Majlis Taklim <span class="text-danger">*</span></label>
<select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror" required>
    <option value="">-- Cari Jamaah --</option>
    @foreach($jamaahList as $jamaah)
        <option value="{{ $jamaah->id }}" {{ old('jamaah_id', $distribusi->jamaah_id) == $jamaah->id ? 'selected' : '' }}>
            {{ $jamaah->nama_jamaah }} ({{ $jamaah->nomor_jamaah }})
        </option>
    @endforeach
</select>
```

#### c) `resources/views/majlistaklim/karyawan/hadiah/distribusi.blade.php` - Baris 930-942
```blade
<!-- SEBELUM: -->
<p class="input-card-label">Pilih Jamaah Yayasan MASAR <span class="text-danger">*</span></p>
<!-- ... -->
<select name="jamaah_id" id="jamaah_id" class="form-select">
    <option value="">-- Pilih Jamaah Terdaftar --</option>
    @foreach($jamaahList ?? [] as $jamaah)
        <option value="{{ $jamaah->kode_yayasan }}">
            {{ $jamaah->nama }}
        </option>
    @endforeach
</select>

<!-- SESUDAH: -->
<p class="input-card-label">Pilih Jamaah Majlis Taklim <span class="text-danger">*</span></p>
<!-- ... -->
<select name="jamaah_id" id="jamaah_id" class="form-select">
    <option value="">-- Pilih Jamaah Terdaftar --</option>
    @foreach($jamaahList ?? [] as $jamaah)
        <option value="{{ $jamaah->id }}">
            {{ $jamaah->nama_jamaah }} ({{ $jamaah->nomor_jamaah }})
        </option>
    @endforeach
</select>
```

### 6. Update Model Relasi

#### `app/Models/DistribusiHadiah.php` - Baris 57-59
```php
// SEBELUM:
public function jamaah()
{
    return $this->belongsTo(YayasanMasar::class, 'jamaah_id');
}

// SESUDAH:
public function jamaah()
{
    return $this->belongsTo(JamaahMajlisTaklim::class, 'jamaah_id');
}
```

## File yang Diubah
✅ `app/Http/Controllers/HadiahMajlisTaklimController.php`
✅ `app/Models/DistribusiHadiah.php`
✅ `resources/views/majlistaklim/hadiah/distribusi.blade.php`
✅ `resources/views/majlistaklim/hadiah/edit_distribusi.blade.php`
✅ `resources/views/majlistaklim/karyawan/hadiah/distribusi.blade.php`

## Testing
Untuk test fix ini:

1. **Data yang valid sekarang:**
   - Dropdown jamaah menampilkan: ID, Nama Jamaah, No Induk (14 jamaah)
   - Hanya jamaah dengan ID 2-15 yang bisa dipilih
   - Jika ingin input untuk DANI (yayasan_masar), gunakan opsi "Non-Jamaah"
   - Set `jamaah_id = NULL` untuk penerima non-jamaah

2. **Test dengan data DANI:**
   - Halaman: `http://127.0.0.1:8000/majlistaklim/distribusi`
   - Pilih tipe penerima: "Penerima Lain (Non-Jamaah)"
   - Nama penerima: "DANI"
   - Field jamaah_id akan otomatis NULL
   - Insert akan berhasil tanpa foreign key constraint violation ✅

3. **Test dengan data Jamaah Valid:**
   - Pilih tipe penerima: "Jamaah Terdaftar"
   - Pilih salah satu dari dropdown (misal: YANDI MULYADI, h engkus, etc)
   - Insert akan berhasil ✅

## Catatan Penting
- Foreign key constraint di `distribusi_hadiah.jamaah_id` tetap referensi ke `jamaah_majlis_taklim.id`
- Field `jamaah_id` **nullable**, sehingga bisa NULL untuk penerima non-jamaah
- Relasi model sudah diperbarui untuk point ke tabel yang benar
- Cache sudah di-clear untuk memastikan perubahan diterapkan

