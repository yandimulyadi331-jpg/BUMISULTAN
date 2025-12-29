# ANALISA REDESIGN SISTEM POTONGAN PINJAMAN PAYROLL

## 📋 EXECUTIVE SUMMARY

Berdasarkan analisa mendalam terhadap sistem yang ada, user meminta perubahan sistem **Potongan Pinjaman Payroll** agar mengikuti alur dan tampilan dari **BPJS Tenaga Kerja** dengan fitur tambahan:

1. **Tampilan mirip BPJS** - Form input per karyawan dengan tabel data yang mudah dimonitor
2. **Input manual per karyawan** - Admin bisa input potongan pinjaman untuk setiap karyawan
3. **Pengaturan jangka waktu** - Menentukan bulan mulai dan berakhir potongan
4. **Auto-generate bulanan** - Sistem otomatis membuat potongan sesuai jangka waktu
5. **Monitoring lebih baik** - Menghindari kesalahan angka dan tracking yang jelas

---

## 🎯 MASALAH SISTEM SAAT INI

### Current System Flow:
```
1. Generate Potongan (Auto) → Ambil dari pinjaman_cicilan yang jatuh tempo
2. Proses Potongan → Update status pending → dipotong
3. Hapus Periode → Reset semua data periode tertentu
```

### Kelemahan:
- ❌ **Tidak bisa input manual** per karyawan
- ❌ **Tidak ada pengaturan jangka waktu** (bulan berlaku)
- ❌ **Sulit monitoring** - data tersebar per cicilan
- ❌ **Rawan kesalahan** - generate otomatis tanpa validasi manual
- ❌ **Tidak fleksibel** - harus ikut jadwal cicilan

---

## 🎨 REFERENCE SYSTEM: BPJS TENAGA KERJA

### Struktur Database BPJS:
```sql
karyawan_bpjstenagakerja:
- kode_bpjs_tk (PK)
- nik (FK)
- jumlah
- tanggal_berlaku
```

### Alur BPJS (Yang Diinginkan):
1. **Input Manual** - Admin tambah BPJS untuk karyawan tertentu
2. **Set Tanggal Berlaku** - Menentukan kapan mulai dipotong
3. **Monitoring Mudah** - Tabel menampilkan semua data dengan jelas
4. **Edit/Hapus Fleksibel** - Bisa edit atau hapus kapan saja

---

## 🏗️ DESAIN SISTEM BARU: POTONGAN PINJAMAN MASTER

### A. STRUKTUR DATABASE BARU

#### Tabel: `potongan_pinjaman_master`
```sql
CREATE TABLE potongan_pinjaman_master (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    kode_potongan VARCHAR(15) UNIQUE,        -- PPM250001, PPM250002
    nik CHAR(9),                             -- FK ke karyawan
    pinjaman_id BIGINT,                      -- FK ke pinjaman (opsional)
    
    -- Data Potongan
    jumlah_pinjaman DECIMAL(15,2),           -- Total pinjaman (misal 5jt)
    cicilan_per_bulan DECIMAL(15,2),         -- Cicilan per bulan (misal 1jt)
    jumlah_bulan INT,                        -- Jumlah bulan cicilan (misal 5 bulan)
    
    -- Periode Berlaku
    bulan_mulai INT,                         -- Bulan mulai (1-12)
    tahun_mulai INT,                         -- Tahun mulai
    bulan_selesai INT,                       -- Bulan selesai (auto-calculated)
    tahun_selesai INT,                       -- Tahun selesai (auto-calculated)
    
    -- Tracking
    jumlah_terbayar DECIMAL(15,2) DEFAULT 0, -- Total yang sudah dipotong
    sisa_pinjaman DECIMAL(15,2),             -- Sisa yang belum dipotong
    bulan_terakhir_dipotong INT NULL,        -- Tracking bulan terakhir dipotong
    tahun_terakhir_dipotong INT NULL,        -- Tracking tahun terakhir dipotong
    
    -- Status
    status ENUM('aktif', 'selesai', 'ditunda', 'dibatalkan') DEFAULT 'aktif',
    tanggal_selesai DATE NULL,               -- Auto-set saat lunas
    
    -- Metadata
    keterangan TEXT NULL,
    dibuat_oleh BIGINT,                      -- FK ke users
    diupdate_oleh BIGINT NULL,               -- FK ke users
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (nik) REFERENCES karyawan(nik) ON DELETE CASCADE,
    FOREIGN KEY (pinjaman_id) REFERENCES pinjaman(id) ON DELETE SET NULL,
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (diupdate_oleh) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_nik (nik),
    INDEX idx_status (status),
    INDEX idx_periode (bulan_mulai, tahun_mulai, bulan_selesai, tahun_selesai)
);
```

#### Tabel: `potongan_pinjaman_detail`
```sql
CREATE TABLE potongan_pinjaman_detail (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    master_id BIGINT,                        -- FK ke potongan_pinjaman_master
    
    -- Periode
    bulan INT,                               -- Bulan potongan (1-12)
    tahun INT,                               -- Tahun potongan
    
    -- Jumlah
    jumlah_potongan DECIMAL(15,2),           -- Jumlah yang dipotong
    cicilan_ke INT,                          -- Cicilan ke berapa
    
    -- Status
    status ENUM('pending', 'dipotong', 'batal') DEFAULT 'pending',
    tanggal_dipotong DATE NULL,              -- Kapan dipotong
    diproses_oleh BIGINT NULL,               -- FK ke users
    
    -- Metadata
    keterangan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (master_id) REFERENCES potongan_pinjaman_master(id) ON DELETE CASCADE,
    FOREIGN KEY (diproses_oleh) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_master (master_id),
    INDEX idx_periode (bulan, tahun),
    INDEX idx_status (status),
    UNIQUE KEY unique_periode (master_id, bulan, tahun)
);
```

---

## 🔄 ALUR SISTEM BARU

### 1. INPUT POTONGAN PINJAMAN (Manual)

**Halaman: Tambah Potongan Pinjaman**

Form Input:
```
- Pilih Karyawan: [Dropdown/Search]
- Referensi Pinjaman: [Dropdown - opsional, dari tabel pinjaman]
- Total Pinjaman: Rp [input] (misal: 5.000.000)
- Cicilan Per Bulan: Rp [input] (misal: 1.000.000)
- Jumlah Bulan: [auto-calculate: 5.000.000 / 1.000.000 = 5 bulan]
- Bulan Mulai: [Dropdown: Januari - Desember]
- Tahun Mulai: [Input: 2025]
- Bulan Selesai: [Auto: Mei] (calculated)
- Tahun Selesai: [Auto: 2025] (calculated)
- Keterangan: [Textarea]
```

**Validasi:**
- Total pinjaman harus > 0
- Cicilan per bulan harus > 0 dan <= total pinjaman
- Bulan mulai harus >= bulan sekarang (untuk data baru)
- Karyawan belum punya potongan aktif di periode yang sama

**Proses Submit:**
1. Insert ke `potongan_pinjaman_master`
2. Auto-generate kode: PPM250001 (Potongan Pinjaman Master + Tahun + Urutan)
3. Calculate periode selesai
4. Status = 'aktif'

---

### 2. AUTO-GENERATE DETAIL BULANAN (Background Job / Manual Trigger)

**Command/Job: GeneratePotonganPinjamanBulanan**

Dijalankan setiap awal bulan atau manual trigger.

**Logic:**
```php
1. Ambil semua master dengan status='aktif'
2. Filter yang periode bulan/tahun sekarang ada di range-nya
3. Cek apakah detail untuk bulan ini sudah ada
4. Jika belum:
   - Insert ke potongan_pinjaman_detail
   - Status = 'pending'
   - Jumlah = cicilan_per_bulan dari master
   - Cicilan ke = hitung berdasarkan bulan berjalan
5. Update bulan_terakhir_dipotong di master (jika status=dipotong)
```

**Example:**
```
Master Data:
- Total: 5.000.000
- Cicilan/bulan: 1.000.000
- Mulai: Januari 2025
- Selesai: Mei 2025

Auto-generate akan buat:
- Detail Januari 2025: 1.000.000 (cicilan ke-1)
- Detail Februari 2025: 1.000.000 (cicilan ke-2)
- Detail Maret 2025: 1.000.000 (cicilan ke-3)
- Detail April 2025: 1.000.000 (cicilan ke-4)
- Detail Mei 2025: 1.000.000 (cicilan ke-5)
```

---

### 3. HALAMAN INDEX (Mirip BPJS)

**URL:** `/payroll/potongan-pinjaman-master`

**Fitur:**
- ✅ Tombol **Tambah Potongan Pinjaman**
- ✅ Filter: Cari Nama Karyawan, Cabang, Departemen, Status
- ✅ Tombol **Cari**

**Tabel Data:**
```
| KODE     | NIK       | NAMA KARYAWAN    | DEPT | CABANG | TOTAL PINJAMAN | CICILAN/BULAN | PERIODE              | PROGRESS      | STATUS | # |
|----------|-----------|------------------|------|--------|----------------|---------------|----------------------|---------------|--------|---|
| PPM250001| 12345678  | Yandi Mulyadi    | CRW  | JGL    | 5.000.000      | 1.000.000     | Jan 2025 - Mei 2025  | 2/5 (40%)     | Aktif  | ✏️🗑️|
| PPM250002| 87654321  | Ahmad Rizki      | MTC  | PST    | 10.000.000     | 2.000.000     | Feb 2025 - Jun 2025  | 1/5 (20%)     | Aktif  | ✏️🗑️|
```

**Kolom Progress:**
- Menampilkan: cicilan_terakhir / total_cicilan (persentase)
- Visual: Progress bar

---

### 4. PROSES POTONGAN BULANAN

**Halaman: Proses Potongan Bulan [Bulan] [Tahun]**

**URL:** `/payroll/potongan-pinjaman-proses`

**Fitur:**
- Filter Bulan & Tahun
- Tombol **Tampilkan**
- Tombol **Generate Detail** (jika detail bulan ini belum ada)
- Tombol **Proses Potongan** (update status pending → dipotong)
- Tombol **Hapus Periode Ini**

**Summary Cards:**
```
┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│  Pending    │  │  Dipotong   │  │    Batal    │  │   Total     │
│     5       │  │      3      │  │      0      │  │  Karyawan   │
│ Rp 5.000.000│  │ Rp 3.000.000│  │  Rp 0       │  │      8      │
└─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘
```

**Tabel Detail:**
```
| NIK      | NAMA KARYAWAN | KODE MASTER | CICILAN KE | JUMLAH      | STATUS   | TANGGAL DIPOTONG |
|----------|---------------|-------------|------------|-------------|----------|------------------|
| 12345678 | Yandi Mulyadi | PPM250001   | 2/5        | 1.000.000   | Pending  | -                |
| 87654321 | Ahmad Rizki   | PPM250002   | 1/5        | 2.000.000   | Dipotong | 01-12-2025       |
```

**Tombol Proses:**
- Update status detail: pending → dipotong
- Update master: jumlah_terbayar, sisa_pinjaman
- Cek jika cicilan terakhir: update status master = 'selesai'

---

### 5. INTEGRASI DENGAN SLIP GAJI

**Function: getPotonganPinjamanByNik($nik, $bulan, $tahun)**

```php
public function getPotonganPinjamanByNik($nik, $bulan, $tahun)
{
    return DB::table('potongan_pinjaman_detail as d')
        ->join('potongan_pinjaman_master as m', 'd.master_id', '=', 'm.id')
        ->join('karyawan as k', 'm.nik', '=', 'k.nik')
        ->where('m.nik', $nik)
        ->where('d.bulan', $bulan)
        ->where('d.tahun', $tahun)
        ->where('d.status', 'dipotong')
        ->select('d.*', 'm.kode_potongan', 'm.keterangan as keterangan_master')
        ->get();
}
```

**Di Slip Gaji:**
```
POTONGAN:
- Potongan Pinjaman (PPM250001) - Cicilan 2/5: Rp 1.000.000
```

---

## 📊 PERBANDINGAN SISTEM

| Aspek                    | Sistem Lama                          | Sistem Baru                                |
|--------------------------|--------------------------------------|--------------------------------------------|
| **Input**                | Auto-generate dari cicilan           | Manual input per karyawan                  |
| **Jangka Waktu**         | Ikut jadwal cicilan                  | Atur sendiri bulan mulai & selesai         |
| **Monitoring**           | Sulit - data per cicilan             | Mudah - master dengan progress tracking    |
| **Fleksibilitas**        | Rendah - terikat pinjaman            | Tinggi - bisa tanpa referensi pinjaman     |
| **Validasi**             | Auto tanpa konfirmasi                | Input manual dengan validasi               |
| **Tracking Progress**    | Tidak ada                            | Ada progress bar & persentase              |
| **Edit/Update**          | Sulit - harus hapus periode          | Mudah - edit master atau detail            |
| **Tampilan**             | Complex - group by karyawan          | Simple - tabel flat seperti BPJS           |

---

## 🛠️ IMPLEMENTATION PLAN

### Phase 1: Database Migration (1-2 hari)
- [ ] Create migration untuk `potongan_pinjaman_master`
- [ ] Create migration untuk `potongan_pinjaman_detail`
- [ ] Seed data sample untuk testing

### Phase 2: Model & Controller (2-3 hari)
- [ ] Create Model `PotonganPinjamanMaster`
- [ ] Create Model `PotonganPinjamanDetail`
- [ ] Create Controller `PotonganPinjamanMasterController`
- [ ] Implement CRUD methods

### Phase 3: Views & UI (3-4 hari)
- [ ] Create view: index (list master) - mirip BPJS
- [ ] Create view: create (form tambah potongan)
- [ ] Create view: edit (form edit potongan)
- [ ] Create view: proses (proses bulanan)
- [ ] Implement search & filter

### Phase 4: Auto-Generate Logic (2-3 hari)
- [ ] Create Command/Job untuk generate detail bulanan
- [ ] Implement logic calculate periode selesai
- [ ] Implement logic update status master saat selesai
- [ ] Testing auto-generate

### Phase 5: Integration (2-3 hari)
- [ ] Integrasi dengan slip gaji
- [ ] Update laporan keuangan
- [ ] Testing end-to-end

### Phase 6: Migration Data Lama (1-2 hari)
- [ ] Script migrasi dari `potongan_pinjaman_payroll` ke sistem baru
- [ ] Validasi data
- [ ] Backup data lama

**Total Estimasi: 11-17 hari (2-3 minggu)**

---

## 🎨 MOCKUP UI

### Halaman Index (List)
```
┌────────────────────────────────────────────────────────────────────────┐
│  Potongan Pinjaman Payroll                           [+ Tambah]        │
├────────────────────────────────────────────────────────────────────────┤
│  [🔍 Cari Nama] [Cabang ▼] [Departemen ▼] [Status ▼] [Cari]          │
├────────────────────────────────────────────────────────────────────────┤
│ KODE  │ NIK │ NAMA │ DEPT │ TOTAL │ CICILAN │ PERIODE │ PROGRESS │ # │
├────────────────────────────────────────────────────────────────────────┤
│ PPM001│ 123 │ Yandi│ CRW  │  5jt  │  1jt    │Jan-Mei  │██▒▒▒ 40% │✏️🗑│
│ PPM002│ 456 │ Ahmad│ MTC  │ 10jt  │  2jt    │Feb-Jun  │█▒▒▒▒ 20% │✏️🗑│
└────────────────────────────────────────────────────────────────────────┘
```

### Halaman Tambah/Edit
```
┌────────────────────────────────────────────────────────────────────────┐
│  Tambah Potongan Pinjaman Payroll                                      │
├────────────────────────────────────────────────────────────────────────┤
│  Karyawan *          [Cari dan pilih karyawan...        ▼]            │
│  Referensi Pinjaman  [Pilih pinjaman (opsional)         ▼]            │
│                                                                         │
│  Total Pinjaman *    Rp [5.000.000                      ]              │
│  Cicilan per Bulan * Rp [1.000.000                      ]              │
│  Jumlah Bulan           [5] bulan (otomatis)                           │
│                                                                         │
│  Bulan Mulai *       [Januari ▼]     Tahun [2025]                     │
│  Bulan Selesai       [Mei] (otomatis) Tahun [2025]                    │
│                                                                         │
│  Keterangan          [Pinjaman untuk renovasi rumah...  ]              │
│                                                                         │
│  [Simpan]  [Batal]                                                     │
└────────────────────────────────────────────────────────────────────────┘
```

### Halaman Proses Bulanan
```
┌────────────────────────────────────────────────────────────────────────┐
│  Proses Potongan Pinjaman - Desember 2025                              │
├────────────────────────────────────────────────────────────────────────┤
│  [Bulan ▼] [Tahun ▼] [Tampilkan] [Generate] [Proses] [Hapus]         │
├────────────────────────────────────────────────────────────────────────┤
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐                     │
│  │ Pending │ │Dipotong │ │  Batal  │ │  Total  │                     │
│  │    5    │ │    3    │ │    0    │ │    8    │                     │
│  │  5.0jt  │ │  3.0jt  │ │    0    │ │Karyawan │                     │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘                     │
├────────────────────────────────────────────────────────────────────────┤
│ NIK │ NAMA  │ KODE   │ CICILAN │ JUMLAH  │ STATUS  │ TGL POTONG  │   │
├────────────────────────────────────────────────────────────────────────┤
│ 123 │ Yandi │PPM001  │  2/5    │ 1.000K  │ Pending │      -      │   │
│ 456 │ Ahmad │PPM002  │  1/5    │ 2.000K  │Dipotong │ 01-12-2025  │   │
└────────────────────────────────────────────────────────────────────────┘
```

---

## ⚠️ CONSIDERATIONS & RISKS

### Considerations:
1. **Backward Compatibility**: Apakah data lama di `potongan_pinjaman_payroll` perlu dimigrasi?
2. **Integration Impact**: Pastikan slip gaji, laporan keuangan terintegrasi
3. **User Training**: Admin perlu training cara input manual
4. **Validation**: Pastikan tidak double potongan di periode yang sama

### Risks:
1. **Data Loss**: Jika migrasi tidak sempurna
2. **Calculation Error**: Jika logic calculate periode salah
3. **Performance**: Jika banyak karyawan dengan potongan aktif

### Mitigation:
- Testing menyeluruh sebelum production
- Backup database sebelum migrasi
- Soft delete untuk data master (tidak langsung hapus permanen)
- Audit log untuk setiap perubahan

---

## 🎯 REKOMENDASI

### Opsi 1: Full Redesign (Recommended)
✅ **Kelebihan:**
- Sistem baru lebih fleksibel dan maintainable
- UI/UX lebih baik (mirip BPJS)
- Monitoring lebih mudah
- Sesuai permintaan user

❌ **Kekurangan:**
- Butuh waktu development 2-3 minggu
- Perlu migrasi data lama
- Perlu training user

### Opsi 2: Hybrid (Quick Fix)
Tetap pakai sistem lama, tambah fitur:
- Form input manual untuk create potongan tanpa cicilan
- Tambah field periode berlaku

✅ **Kelebihan:**
- Lebih cepat (1 minggu)
- Tidak perlu migrasi besar

❌ **Kekurangan:**
- Sistem tetap kompleks
- Tidak sepenuhnya sesuai permintaan user
- Masih sulit monitoring

---

## 📝 KESIMPULAN

Sistem baru dengan **Master-Detail Pattern** seperti BPJS akan memberikan:

✅ **Fleksibilitas** - Input manual per karyawan
✅ **Monitoring** - Progress tracking yang jelas
✅ **Otomatis** - Auto-generate detail bulanan
✅ **User-Friendly** - UI seperti BPJS yang sudah familiar
✅ **Maintainable** - Kode lebih clean dan terstruktur

**REKOMENDASI: Implementasi Opsi 1 (Full Redesign)**

Estimasi: **2-3 minggu development + testing**

---

## 📞 NEXT STEPS

Jika disetujui, saya akan mulai:
1. ✅ Create migration files
2. ✅ Create models
3. ✅ Create controller
4. ✅ Create views
5. ✅ Testing
6. ✅ Migration data lama

**Apakah Anda ingin saya mulai implementasi?**
