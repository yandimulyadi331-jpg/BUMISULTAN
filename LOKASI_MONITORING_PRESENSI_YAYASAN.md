# 📍 LOKASI MONITORING PRESENSI DI YAYASAN

## 🗂️ STRUKTUR MENU SISTEM

Sistem **ePresensiV2** memiliki beberapa bagian untuk monitoring presensi:

---

## 1️⃣ **MONITORING PRESENSI KARYAWAN**

### 📌 Lokasi: 
```
Menu Utama → Monitoring Presensi
```

**URL:** `http://127.0.0.1:8000/presensi`

**Fitur:**
- ✅ Lihat data presensi karyawan secara real-time
- ✅ Filter berdasarkan tanggal, departemen, cabang
- ✅ Lihat detail jam masuk/keluar
- ✅ Export data presensi
- ✅ Laporan presensi harian

**Akses:** Super Admin & User dengan permission `presensi.index`

---

## 2️⃣ **TRACKING PRESENSI (Real-Time)**

### 📌 Lokasi:
```
Menu Utama → Tracking Presensi
```

**URL:** `http://127.0.0.1:8000/trackingpresensi`

**Fitur:**
- ✅ Tracking lokasi karyawan secara live
- ✅ Peta lokasi (GPS tracking)
- ✅ Riwayat pergerakan
- ✅ Verifikasi kehadiran dengan lokasi

**Akses:** Super Admin & User dengan permission `trackingpresensi.index`

---

## 3️⃣ **FACE RECOGNITION PRESENSI**

### 📌 Lokasi:
```
Menu Utama → (biasanya di bagian khusus atau public access)
```

**URL:** `http://127.0.0.1:8000/facerecognition-presensi`

**Fitur:**
- ✅ Scan wajah untuk presensi
- ✅ Identifikasi otomatis via camera
- ✅ Recording hasil scan

**Akses:** Public (tidak perlu login)

---

## 4️⃣ **LAPORAN PRESENSI & GAJI**

### 📌 Lokasi:
```
Menu Utama → Laporan → Presensi & Gaji
```

**URL:** `http://127.0.0.1:8000/laporan/presensi`

**Fitur:**
- ✅ Laporan presensi per bulan
- ✅ Hitung total jam kerja
- ✅ Perhitungan gaji otomatis
- ✅ Export ke Excel/PDF
- ✅ Analisa kehadiran

**Akses:** Super Admin & User dengan permission `laporan.presensi`

---

## 5️⃣ **MANAJEMEN SANTRI (YAYASAN)**

Sistem ini juga memiliki bagian khusus untuk **Yayasan/Pesantren**:

### 📌 Lokasi:
```
Menu Utama → Manajemen Saung Santri → Jadwal & Absensi Santri
```

**URL:** `http://127.0.0.1:8000/jadwal-santri`

**Fitur:**
- ✅ Kelola jadwal santri
- ✅ Monitor absensi santri
- ✅ Input ijin/sakit santri
- ✅ Laporan absensi santri

**Akses:** Super Admin & User yang authorized

---

## 🎯 UNTUK YAYASAN MASAR - PRESENSI

Anda telah membuat modul **Yayasan Masar** yang sudah siap. Untuk menambahkan fitur **monitoring presensi untuk Yayasan Masar**, ada beberapa opsi:

### **OPSI 1: Gunakan Sistem Existing Santri**
Manfaatkan struktur yang sudah ada untuk Santri, dan adaptasi untuk Yayasan Masar

```
Menu → Manajemen Saung Santri → Jadwal & Absensi Santri
```

### **OPSI 2: Buat Modul Presensi Yayasan Masar Terpisah**
Buat controller & routes khusus untuk presensi Yayasan Masar mirip dengan struktur existing

**File yang perlu dibuat:**
- `PresensiYayasanMasarController.php`
- Routes untuk presensi yayasan masar
- Views untuk monitoring presensi yayasan masar

### **OPSI 3: Gunakan Sistem Presensi Karyawan**
Rekayasa sistem presensi karyawan existing untuk juga mencakup Yayasan Masar

---

## 📊 PERBANDINGAN FITUR PRESENSI

| Fitur | Karyawan | Santri | Yayasan Masar |
|-------|----------|--------|---------------|
| **Monitoring Presensi** | ✅ Ada | ✅ Ada | ❓ Belum ada |
| **Tracking GPS** | ✅ Ada | ❌ Tidak | ❌ Tidak |
| **Face Recognition** | ✅ Ada | ❌ Tidak | ❌ Tidak |
| **Laporan Presensi** | ✅ Ada | ✅ Ada | ❓ Belum ada |
| **Perhitungan Gaji** | ✅ Ada | ❌ Tidak | ❌ Tidak |

---

## 🚀 SARAN UNTUK YAYASAN MASAR

Karena Yayasan Masar adalah duplikasi dari Karyawan, berikut saran implementasi presensi:

### **Langkah 1: Gunakan struktur Presensi Existing**
```php
// Copy PresensiController dan adaptasi untuk Yayasan Masar
App\Http\Controllers\PresensiYayasanMasarController
```

### **Langkah 2: Buat table presensi_yayasan_masar**
```sql
CREATE TABLE presensi_yayasan_masar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_yayasan VARCHAR(20),
    jam_masuk TIME,
    jam_keluar TIME,
    tgl_presensi DATE,
    keterangan TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (kode_yayasan) REFERENCES yayasan_masar(kode_yayasan)
);
```

### **Langkah 3: Tambahkan menu di sidebar**
```blade
@if (auth()->user()->hasRole(['super admin']) || auth()->user()->can('presensi_yayasan_masar.index'))
    <li class="menu-item">
        <a href="{{ route('presensi-yayasan-masar.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-calendar-check"></i>
            <div>Monitoring Presensi Yayasan</div>
        </a>
    </li>
@endif
```

### **Langkah 4: Tambahkan routes**
```php
Route::controller(PresensiYayasanMasarController::class)->group(function () {
    Route::get('/presensi-yayasan-masar', 'index')->name('presensi-yayasan-masar.index');
    Route::post('/presensi-yayasan-masar', 'store')->name('presensi-yayasan-masar.store');
    Route::get('/presensi-yayasan-masar/laporan', 'laporan')->name('presensi-yayasan-masar.laporan');
});
```

---

## 📋 RINGKASAN

**Presensi yang sudah ada di sistem:**
1. ✅ Presensi Karyawan → Menu "Monitoring Presensi"
2. ✅ Presensi Santri → Menu "Jadwal & Absensi Santri"
3. ✅ Tracking GPS → Menu "Tracking Presensi"
4. ✅ Laporan Presensi → Menu "Laporan > Presensi & Gaji"

**Untuk Yayasan Masar:**
- ⚠️ Belum ada modul presensi khusus
- 💡 Bisa menggunakan yang sudah ada atau membuat yang baru
- 📝 Dokumentasi cara membuat ada di atas

---

**Apakah Anda ingin saya membuat modul Presensi Yayasan Masar yang terpisah?**
