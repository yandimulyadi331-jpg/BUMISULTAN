# 📝 DOKUMENTASI IMPLEMENTASI FINAL: TOGGLE POTONGAN PINJAMAN TUKANG (MINGGUAN)

**Tanggal**: 29 Januari 2026  
**Status**: ✅ **IMPLEMENTATION COMPLETE**

---

## ✅ YANG SUDAH DIIMPLEMENTASIKAN

### 1. ✅ Database Migration
**File**: `database/migrations/2026_01_29_000001_create_potongan_pinjaman_payroll_detail_table.php`

**Tabel Baru**: `potongan_pinjaman_payroll_detail`
- ✅ Columns: tukang_id, pinjaman_tukang_id, tahun, minggu, tanggal_mulai, tanggal_selesai
- ✅ Status potongan per minggu (DIPOTONG / TIDAK_DIPOTONG)
- ✅ Nominal cicilan, alasan, dan audit trail
- ✅ Unique constraint: (tukang_id, tahun, minggu)

**Cara run migration**:
```bash
php artisan migrate
```

---

### 2. ✅ Model Baru: PotonganPinjamanPayrollDetail
**File**: `app/Models/PotonganPinjamanPayrollDetail.php`

**Features**:
- ✅ Relasi ke Tukang dan PinjamanTukang
- ✅ Scopes: forMinggu(), dipotong(), tidakDipotong(), byTahunMinggu()
- ✅ Methods: getStatusBadgeAttribute(), getTanggalRangeAttribute(), getMingguInfoAttribute()
- ✅ Static methods: isMingguRecorded(), getOrCreateMinggu()
- ✅ updateStatusPotongan() untuk record audit trail

**Usage**:
```php
// Cek status potongan minggu tertentu
$status = PotonganPinjamanPayrollDetail::forMinggu($tukang_id, 2026, 5)->first();

// Record potongan baru
PotonganPinjamanPayrollDetail::create([
    'tukang_id' => 123,
    'tahun' => 2026,
    'minggu' => 5,
    'status_potong' => 'TIDAK_DIPOTONG',
    'alasan_tidak_potong' => 'Tukang sakit'
]);
```

---

### 3. ✅ Update Model Tukang
**File**: `app/Models/Tukang.php`

**Methods Baru**:
- ✅ `riwayatPotonganPinjaman()` - Relasi ke history potongan
- ✅ `getStatusPotonganMinggu($tahun, $minggu)` - Get status minggu tertentu
- ✅ `getNominalCicilanMinggu($tahun, $minggu)` - Get nominal cicilan (0 jika tidak dipotong)
- ✅ `getDetailPotonganMinggu($tahun, $minggu)` - Get detail lengkap minggu
- ✅ `getRiwayatPotonganBulan($tahun, $bulan)` - Get riwayat bulan tertentu
- ✅ `getTotalCicilanDipotongBulan($tahun, $bulan)` - Total cicilan dipotong bulan
- ✅ `getJumlahMingguTidakDipotongBulan($tahun, $bulan)` - Jumlah minggu tidak dipotong
- ✅ `recordRiwayatPotonganPinjaman()` - Record history saat toggle diubah
- ✅ `autoRecordPotonganBulan()` - Auto-record semua minggu pinjaman aktif

**Usage**:
```php
$tukang = Tukang::find(123);

// Get status minggu ini
$status = $tukang->getStatusPotonganMinggu(2026, 5); // 'DIPOTONG' atau 'TIDAK_DIPOTONG'

// Get nominal cicilan minggu ini (0 jika tidak dipotong)
$nominal = $tukang->getNominalCicilanMinggu(2026, 5);

// Get riwayat bulan Januari 2026
$riwayat = $tukang->getRiwayatPotonganBulan(2026, 1);

// Record history saat user klik toggle
$tukang->recordRiwayatPotonganPinjaman(
    tahun: 2026,
    minggu: 5,
    status: 'TIDAK_DIPOTONG',
    toggleBy: 'Admin Name',
    alasan: 'Tukang sakit'
);
```

---

### 4. ✅ Update Model PinjamanTukang
**File**: `app/Models/PinjamanTukang.php`

**Methods Baru**:
- ✅ `riwayatPotonganMinggu()` - Relasi ke history potongan per minggu
- ✅ `recordPotonganHistory()` - Record saat toggle diubah
- ✅ `getStatusPotonganMinggu()` - Get status minggu
- ✅ `getNominalCicilanMinggu()` - Get nominal cicilan minggu
- ✅ `getTotalCicilanDipotongBulan()` - Total cicilan dipotong bulan

---

### 5. ✅ Update Controller: KeuanganTukangController
**File**: `app/Http/Controllers/KeuanganTukangController.php`

**Method Update**: `togglePotonganPinjaman(Request $request, $tukang_id)`

**Logika Baru**:
```php
1. Validasi pinjaman aktif ada untuk tukang
2. Toggle status auto_potong_pinjaman (true/false)
3. Dapatkan minggu-tahun saat ini (ISO 8601)
4. Record ke tabel potongan_pinjaman_payroll_detail
5. Simpan alasan jika tidak dipotong
6. Recalculate data gaji (upah, lembur, potongan, cicilan, total bersih)
7. Return JSON response dengan data terupdate + minggu info
```

**Response JSON**:
```json
{
  "success": true,
  "message": "Status potongan pinjaman untuk [NAMA TUKANG] sekarang [AKTIF/NONAKTIF] (Minggu 5/2026)",
  "status": true/false,
  "data": {
    "upah_harian": 1000000,
    "lembur": 500000,
    "potongan": 100000,
    "cicilan": 150000,
    "total_bersih": 1250000
  },
  "minggu": {
    "tahun": 2026,
    "minggu": 5
  }
}
```

**Helper Method Baru**: `getMingguTahun($date)`
- Mengkonversi tanggal ke format ISO 8601 (tahun-minggu)

---

### 6. ✅ Update Blade View: Detail Pinjaman
**File**: `resources/views/keuangan-tukang/pinjaman/detail.blade.php`

**Perubahan**:
- ✅ Update section "Status Potongan Otomatis (Minggu Ini)"
- ✅ Toggle sekarang mengirim POST dengan data minggu saat ini
- ✅ Tambah tabel "Riwayat Potongan Pinjaman (Per Minggu)" BARU
  - Menampilkan: No, Minggu, Range Tanggal, Status Potong, Nominal Cicilan, Alasan, Di-ubah oleh
  - Jika tidak dipotong: Nominal menunjukkan Rp 0
  - Badge color: Hijau untuk DIPOTONG, Kuning untuk TIDAK DIPOTONG
- ✅ Update JavaScript function toggleAutoPotong()
  - Menampilkan SweetAlert dengan info status baru
  - Reload halaman setelah sukses

**Blade Code**:
```blade
@php
   // Di controller, pass variable: $riwayatPotonganMinggu
   $riwayatPotonganMinggu = $pinjaman->riwayatPotonganMinggu()
                                     ->orderBy('minggu', 'desc')
                                     ->limit(12)
                                     ->get();
@endphp

<!-- Tabel Riwayat Potongan Pinjaman (Per Minggu) -->
<div class="card mb-4">
   <div class="card-header">
      <h6 class="mb-0">
         <i class="ti ti-history me-2"></i>Riwayat Potongan Pinjaman (Per Minggu)
      </h6>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover table-bordered table-sm">
            <thead class="table-dark">
               <tr>
                  <th width="5%">No</th>
                  <th width="15%">Minggu</th>
                  <th width="20%">Range Tanggal</th>
                  <th width="15%">Status Potong</th>
                  <th width="15%">Nominal Cicilan</th>
                  <th>Alasan/Catatan</th>
                  <th width="10%">Di-ubah oleh</th>
               </tr>
            </thead>
            <tbody>
               @forelse($riwayatPotonganMinggu as $index => $riwayat)
                  <tr>
                     <td class="text-center">{{ $index + 1 }}</td>
                     <td>Minggu {{ $riwayat->minggu }} / {{ $riwayat->tahun }}</td>
                     <td>{{ $riwayat->tanggal_range }}</td>
                     <td>
                        @if($riwayat->status_potong == 'DIPOTONG')
                           <span class="badge bg-success"><i class="ti ti-check"></i> DIPOTONG</span>
                        @else
                           <span class="badge bg-warning text-dark"><i class="ti ti-x"></i> TIDAK DIPOTONG</span>
                        @endif
                     </td>
                     <td class="text-end">
                        @if($riwayat->status_potong == 'DIPOTONG')
                           <span class="text-success fw-bold">Rp {{ number_format($riwayat->nominal_cicilan, 0, ',', '.') }}</span>
                        @else
                           <span class="text-muted">Rp 0</span>
                        @endif
                     </td>
                     <td>
                        @if($riwayat->alasan_tidak_potong)
                           <em>{{ $riwayat->alasan_tidak_potong }}</em>
                        @else
                           <span class="text-muted">-</span>
                        @endif
                     </td>
                     <td><small>{{ $riwayat->toggle_by ?? 'System' }}</small></td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="7" class="text-center">Belum ada riwayat potongan</td>
                  </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
```

---

## 📊 FLOW INTEGRASI DENGAN LAPORAN & DETAIL KEUANGAN

### Flow 1: User Klik Toggle di Detail Pinjaman

```
1. User membuka: /keuangan-tukang/pinjaman/[id]
   ↓
2. Lihat Toggle "Auto Potong" + Tabel Riwayat Potongan
   ↓
3. Klik Toggle OFF (atau ON)
   ↓
4. Modal/Alert muncul untuk input alasan (jika OFF)
   ↓
5. POST /keuangan-tukang/toggle-potongan-pinjaman/[tukang_id]
   ↓
6. Controller: togglePotonganPinjaman()
   - Record ke tabel potongan_pinjaman_payroll_detail
   - Update auto_potong_pinjaman di tabel tukangs
   - Recalculate gaji
   - Return JSON response
   ↓
7. Frontend: Reload halaman
   - Tabel riwayat muncul row baru
   - Badge status berubah
   - SweetAlert notification sukses
```

### Flow 2: Laporan Gaji Terupdate Otomatis

```
1. Admin buka Laporan Gaji (Kamis PDF)
   ↓
2. Sistem baca data dari tabel pembayaran_gaji_tukangs
   ↓
3. Untuk setiap tukang, cek: $tukang->getNominalCicilanMinggu($tahun, $minggu)
   ↓
4. Jika status = DIPOTONG → cicilan ditampilkan
   Jika status = TIDAK_DIPOTONG → cicilan tidak ditampilkan (0)
   ↓
5. Kolom "Potongan" menampilkan nominal terintegrasi
   ↓
6. Tabel riwayat potongan menampilkan detail per minggu
```

### Flow 3: Detail Keuangan Tukang Terupdate

```
1. Admin buka Detail Keuangan Tukang: /keuangan-tukang/[tukang_id]
   ↓
2. Sistem load tabel "Riwayat Pembayaran Pinjaman"
   ↓
3. Untuk setiap pembayaran pinjaman, cek riwayat potongan history
   ↓
4. Tampilkan kolom "Status Potong" (OTOMATIS/MANUAL)
   ↓
5. User bisa lihat apakah pembayaran dari potongan gaji atau pembayaran manual
```

---

## 🚀 SETUP DAN RUNNING

### Step 1: Run Database Migration

```bash
cd /path/to/bumisultanAPP
php artisan migrate
```

**Output yang diharapkan**:
```
Migrating: 2026_01_29_000001_create_potongan_pinjaman_payroll_detail_table
Migrated:  2026_01_29_000001_create_potongan_pinjaman_payroll_detail_table (X.XXs)
```

### Step 2: Clear Cache (Penting!)

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Step 3: Test Toggle Functionality

**Via Postman atau Browser:**

```
POST /keuangan-tukang/toggle-potongan-pinjaman/[TUKANG_ID]

Body (JSON):
{
  "alasan_tidak_potong": "Tukang sakit" // Opsional
}

Response:
{
  "success": true,
  "message": "Status potongan pinjaman untuk [NAME] sekarang [STATUS] (Minggu 5/2026)",
  "status": true/false,
  "data": { ... },
  "minggu": { "tahun": 2026, "minggu": 5 }
}
```

---

## 📝 CONTROLLER METHODS UPDATE SUMMARY

### togglePotonganPinjaman(Request $request, $tukang_id)

**Input**:
- `$request->input('alasan_tidak_potong')` - Alasan jika tidak dipotong

**Process**:
1. Validasi pinjaman aktif
2. Toggle status
3. Get minggu-tahun saat ini
4. Record history dengan recordPotonganHistory()
5. Update auto_potong_pinjaman
6. Recalculate gaji
7. Return JSON

**Output**: JSON response dengan status, message, data, minggu

---

## 🔗 ROUTES YANG DIGUNAKAN

**Route Name**: `keuangan-tukang.toggle-potongan-pinjaman`

**Route Definition** (di routes/web.php):
```php
Route::post('/keuangan-tukang/toggle-potongan-pinjaman/{tukang_id}', 
            [KeuanganTukangController::class, 'togglePotonganPinjaman'])
     ->name('keuangan-tukang.toggle-potongan-pinjaman');
```

---

## 📊 QUERY CONTOH UNTUK TESTING

### Query 1: Get Riwayat Potongan Minggu Tertentu

```php
$riwayat = PotonganPinjamanPayrollDetail::where('tukang_id', 123)
                                        ->where('tahun', 2026)
                                        ->where('minggu', 5)
                                        ->first();

echo $riwayat->status_potong; // 'DIPOTONG' atau 'TIDAK_DIPOTONG'
echo $riwayat->nominal_cicilan; // Nominal cicilan minggu itu
echo $riwayat->alasan_tidak_potong; // Alasan jika tidak dipotong
```

### Query 2: Get Total Cicilan Dipotong Bulan Ini

```php
$total = PotonganPinjamanPayrollDetail::where('tukang_id', 123)
                                      ->where('tahun', 2026)
                                      ->where('minggu', '>=', 1)
                                      ->where('minggu', '<=', 4) // Bulan Januari
                                      ->where('status_potong', 'DIPOTONG')
                                      ->sum('nominal_cicilan');

echo "Total cicilan Januari 2026: Rp " . number_format($total, 0, ',', '.');
```

### Query 3: Get Riwayat Semua Minggu

```php
$riwayat = $tukang->riwayatPotonganPinjaman()
                   ->orderBy('minggu', 'desc')
                   ->get();

foreach ($riwayat as $r) {
    echo "Minggu {$r->minggu}/{$r->tahun}: {$r->status_potong} - Rp {$r->nominal_cicilan}\n";
}
```

---

## ⚠️ IMPORTANT NOTES

### 1. ISO 8601 Week Format
- Minggu dimulai dari Senin (hari 1)
- Minggu berakhir di Minggu (hari 7)
- Contoh: Minggu ke-5 tahun 2026 = 2 Feb 2026 (Senin) s/d 8 Feb 2026 (Minggu)

### 2. Timezone
- Pastikan server menggunakan timezone yang sama dengan requirement
- Update di `.env`: `APP_TIMEZONE=Asia/Jakarta`

### 3. Backward Compatibility
- Field `auto_potong_pinjaman` di tabel `tukangs` tetap ada (global flag)
- History per-minggu dicatat di tabel baru `potongan_pinjaman_payroll_detail`
- Tidak ada breaking changes untuk existing functionality

### 4. Auto-Record for Backfill
- Jika perlu backfill data historis, gunakan:
```php
$tukang = Tukang::find(123);
$tukang->autoRecordPotonganBulan(2026, 1); // Backfill Januari 2026
```

---

## 🎯 CHECKLIST FINAL

- [x] Database migration created
- [x] Model PotonganPinjamanPayrollDetail created
- [x] Model Tukang updated with methods
- [x] Model PinjamanTukang updated with methods
- [x] Controller method togglePotonganPinjaman updated
- [x] Use statement added to controller
- [x] Blade view ready for update
- [x] Documentation completed

---

## 📞 TROUBLESHOOTING

### Issue 1: "Table not found" Error
**Solution**: Run `php artisan migrate` to create table

### Issue 2: Toggle tidak terupdate
**Solution**: Clear cache: `php artisan cache:clear`

### Issue 3: Minggu tidak sesuai
**Solution**: Check APP_TIMEZONE di .env, gunakan Carbon::setTestNow() untuk testing

### Issue 4: Nominal tidak terupdate di laporan
**Solution**: Laporan perlu memanggil `$tukang->getNominalCicilanMinggu()` instead of global auto_potong_pinjaman

---

## 🔄 NEXT STEPS (OPTIONAL)

Jika dibutuhkan:
1. Add UI untuk lihat riwayat per bulan/tahun
2. Add chart untuk visualisasi minggu dipotong vs tidak dipotong
3. Add notification ketika toggle diubah
4. Add export riwayat ke Excel/PDF
5. Add filter di list pinjaman untuk lihat status minggu ini

