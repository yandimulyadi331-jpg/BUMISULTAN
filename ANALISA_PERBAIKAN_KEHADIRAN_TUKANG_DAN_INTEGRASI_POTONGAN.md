# Analisa & Perbaikan: Kehadiran Tukang + Integrasi Potongan Nominal Gaji Kamis

**Tanggal:** 15 Januari 2026  
**Status:** SELESAI

## 🎯 Masalah yang Dilaporkan

1. **Pencarian Tanggal Terbatas**
   - Menu Kehadiran Tukang hanya bisa mencari dengan 1 tanggal saja
   - User ingin bisa search dengan range tanggal (dari - sampai)

2. **Integrasi Potongan Tidak Konsisten**
   - Saat TTD Kamis, potongan gaji tidak terupdate dengan nominal yang sesuai
   - Ada kemungkinan potongan ada di satu menu tapi tidak terpotong di menu lain

## 📋 Analisis Mendalam

### A. Struktur Data

#### Tabel Kehadiran Tukang (`kehadiran_tukangs`)
- `tukang_id` - ID Tukang
- `tanggal` - Tanggal kehadiran
- `status` - hadir/tidak_hadir/setengah_hari
- `lembur` - tidak/full/setengah_hari
- `lembur_dibayar_cash` - boolean
- `upah_harian` - upah per hari berdasarkan status
- `upah_lembur` - upah lembur
- `total_upah` - total yang dibayar (harian + lembur, atau hanya harian jika cash)

#### Tabel Potongan Tukang (`potongan_tukangs`)
- `tukang_id` - ID Tukang
- `tanggal` - tanggal potongan
- `jenis_potongan` - keterlambatan/tidak_hadir/kerusakan_alat/pinjaman/denda/lain_lain
- `jumlah` - nominal potongan
- `keterangan` - deskripsi

#### Tabel Keuangan Tukang (`keuangan_tukangs`)
- `jenis_transaksi` - upah_harian/lembur_full/lembur_setengah/lembur_cash/potongan/pinjaman/dll
- `jumlah` - nominal
- `tipe` - debit (uang masuk) atau kredit (uang keluar)

#### Tabel Pembayaran Gaji (`pembayaran_gaji_tukangs`)
- `total_kotor` - upah harian + lembur (minus cash)
- `total_potongan` - sum dari semua potongan
- `total_nett` - total_kotor - total_potongan

### B. Flow Pembayaran Gaji Kamis

```
1. Menu Kehadiran Tukang (single/range)
   ↓
   Update Status Kehadiran → auto sync ke KeuanganTukang

2. Menu Keuangan Tukang > Potongan
   ↓
   Tambah Potongan → auto sync ke KeuanganTukang

3. Menu Keuangan Tukang > TTD Kamis
   ↓
   GET Detail Gaji (modal)
   ├─ Hitung upah dari KehadiranTukang
   ├─ Hitung potongan dari:
   │  ├─ PinjamanTukang (jika auto_potong_pinjaman = true)
   │  └─ PotonganTukang (selalu)
   └─ Kalkulasi: total_nett = total_kotor - total_potongan
   
   POST Simpan Pembayaran + TTD
   ├─ Simpan ke PembayaranGajiTukang
   ├─ Update KeuanganTukang (history bayar cicilan)
   └─ Cetak Slip Gaji
```

### C. Masalah Potongan yang Ditemukan

#### ❌ ISSUE 1: Potongan Pinjaman Tidak Konsisten

**Ditemukan di:** Controller `KeuanganTukangController`

**Method `detailGajiTukang()` (OLD)**
```php
// LAMA: Potongan pinjaman SELALU ditampilkan tanpa cek auto_potong_pinjaman
foreach ($pinjamanAktif as $p) {
    $rincianPotongan[] = [
        'jenis' => 'Cicilan Pinjaman',
        'jumlah' => $p->cicilan_per_minggu  // ← SELALU
    ];
    $totalPotongan += $p->cicilan_per_minggu;
}
```

**Method `downloadLaporanPengajuanGaji()` (CORRECT)**
```php
// BENAR: Hanya potong jika auto_potong_pinjaman AKTIF
if ($tukang->auto_potong_pinjaman) {
    $totalPotonganPinjaman = $pinjamanAktif->sum('cicilan_per_minggu');
}
```

**Penyebab:** Logika di `detailGajiTukang()` tidak mengecek `auto_potong_pinjaman`, 
sehingga saat TTD modal menampilkan potongan yang berbeda dengan laporan pengajuan gaji.

#### ✅ SOLUSI 1: Seragamkan Logika Potongan Pinjaman

File: `app/Http/Controllers/KeuanganTukangController.php`

```php
// ✅ PERBAIKAN: Hanya potong pinjaman jika auto_potong_pinjaman = true
if ($tukang->auto_potong_pinjaman) {
    foreach ($pinjamanAktif as $p) {
        $rincianPotongan[] = [
            'jenis' => 'Cicilan Pinjaman',
            'keterangan' => 'Sisa: Rp ' . number_format($p->sisa_pinjaman, 0, ',', '.'),
            'jumlah' => $p->cicilan_per_minggu
        ];
        $totalPotongan += $p->cicilan_per_minggu;
    }
}

// ✅ Potongan lain SELALU ditampilkan
foreach ($potonganLain as $p) {
    $rincianPotongan[] = [
        'jenis' => ucwords(str_replace('_', ' ', $p->jenis_potongan)),
        'keterangan' => $p->keterangan,
        'jumlah' => $p->jumlah
    ];
    $totalPotongan += $p->jumlah;
}
```

#### ✅ PERBAIKAN RESPONSE JSON

Tambahkan flag ke response untuk validasi di client:
```php
return response()->json([
    ...
    'auto_potong_pinjaman' => $tukang->auto_potong_pinjaman
]);
```

---

## 🔄 Fitur Baru: Pencarian Range Tanggal

### A. Perubahan Controller

**File:** `app/Http/Controllers/KehadiranTukangController.php`

**Method `index()`** - Support 2 mode:

```php
public function index(Request $request)
{
    // ✅ MODE RANGE TANGGAL (NEW)
    if ($request->has('tanggal_mulai') && $request->has('tanggal_akhir')) {
        $tanggalMulai = Carbon::parse($request->input('tanggal_mulai'));
        $tanggalAkhir = Carbon::parse($request->input('tanggal_akhir'));
        
        // Load kehadiran dalam range untuk setiap tukang
        foreach ($tukangs as $tukang) {
            $tukang->kehadiran_list = KehadiranTukang::where('tukang_id', $tukang->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
                ->orderBy('tanggal')
                ->get();
        }
        
        return view(..., ['mode' => 'range', 'tanggal_mulai' => ..., 'tanggal_akhir' => ...]);
    }
    
    // ✅ MODE SINGLE TANGGAL (ORIGINAL)
    else {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        // ... original logic ...
        return view(..., ['mode' => 'single', 'tanggal' => ...]);
    }
}
```

### B. Perubahan View

**File:** `resources/views/manajemen-tukang/kehadiran/index.blade.php`

#### 1. Form Pencarian (NEW)

```blade
@if($mode == 'single')
   <form action="{{ route('kehadiran-tukang.index') }}" method="GET" class="d-flex align-items-center">
      <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
   </form>
@else
   <!-- BARU: Range Tanggal -->
   <form action="{{ route('kehadiran-tukang.index') }}" method="GET" class="d-flex align-items-center gap-2">
      <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggal_mulai }}" style="width: 150px;">
      <span class="text-muted">s/d</span>
      <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir }}" style="width: 150px;">
      <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-search"></i> Cari</button>
      <a href="{{ route('kehadiran-tukang.index') }}" class="btn btn-secondary btn-sm"><i class="ti ti-refresh"></i> Reset</a>
   </form>
@endif
```

#### 2. Tabel Mode Range (NEW)

```blade
@if($mode == 'range')
   <!-- Tampilan Summary: Hadir, Setengah, Lembur, Total Upah -->
   <table class="table table-sm">
      <thead class="table-dark">
         <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Hadir</th>
            <th>Setengah</th>
            <th>Tidak Hadir</th>
            <th>Lembur</th>
            <th>Total Upah</th>
         </tr>
      </thead>
      <tbody>
         @foreach($tukangs as $tukang)
         <tr>
            <td>{{ $tukang->kode_tukang }}</td>
            <td>{{ $tukang->nama_tukang }}</td>
            <td><span class="badge bg-success">{{ $tukang->kehadiran_list->where('status', 'hadir')->count() }}</span></td>
            <td><span class="badge bg-warning">{{ $tukang->kehadiran_list->where('status', 'setengah_hari')->count() }}</span></td>
            <td><span class="badge bg-danger">{{ $tukang->kehadiran_list->where('status', 'tidak_hadir')->count() }}</span></td>
            <td><span class="badge bg-info">{{ $tukang->kehadiran_list->whereIn('lembur', ['full', 'setengah_hari'])->count() }}</span></td>
            <td class="text-end"><strong>Rp {{ number_format($tukang->kehadiran_list->sum('total_upah'), 0, ',', '.') }}</strong></td>
         </tr>
         @endforeach
      </tbody>
   </table>
@endif
```

---

## 📊 Checklist Perbaikan

### ✅ Fitur Pencarian Tanggal (Range)
- [x] Modifikasi form input di view (dari - sampai)
- [x] Update controller untuk support 2 mode (single/range)
- [x] Buat tampilan tabel berbeda untuk mode range
- [x] Tambah button Reset
- [x] Validasi input tanggal

### ✅ Integrasi Potongan Konsisten
- [x] Perbaiki logika `detailGajiTukang()` - seragamkan dengan `downloadLaporanPengajuanGaji()`
- [x] Tambahkan validasi `auto_potong_pinjaman` di modal TTD
- [x] Ensure potongan lain (denda, kerusakan) selalu ditampilkan
- [x] Response JSON include flag `auto_potong_pinjaman`

### ✅ Testing Checklist
- [x] Test pencarian single tanggal (backward compatibility)
- [x] Test pencarian range tanggal
- [x] Test TTD Kamis dengan potongan pinjaman AKTIF
- [x] Test TTD Kamis dengan potongan pinjaman NONAKTIF
- [x] Test potongan lain (denda, kerusakan) terpotong di TTD
- [x] Validasi laporan pengajuan gaji = nominal di TTD

---

## 🔐 Database Consistency Check

### Consistency Rules

```sql
-- RULE 1: Setiap kehadiran yang upah_harian > 0 harus ada di keuangan_tukangs
SELECT COUNT(*) FROM kehadiran_tukangs k
WHERE upah_harian > 0 
AND NOT EXISTS (
   SELECT 1 FROM keuangan_tukangs 
   WHERE kehadiran_tukang_id = k.id 
   AND jenis_transaksi = 'upah_harian'
);

-- RULE 2: Setiap potongan harus ada di keuangan_tukangs
SELECT COUNT(*) FROM potongan_tukangs p
WHERE NOT EXISTS (
   SELECT 1 FROM keuangan_tukangs 
   WHERE potongan_tukang_id = p.id
);

-- RULE 3: Total potongan di pembayaran_gaji = sum potongan dalam periode
SELECT p.id, p.total_potongan, 
       (SELECT SUM(jumlah) FROM keuangan_tukangs 
        WHERE tipe='kredit' 
        AND tanggal BETWEEN p.periode_mulai AND p.periode_akhir
        AND tukang_id = p.tukang_id) as actual_total
FROM pembayaran_gaji_tukangs p
WHERE p.total_potongan != actual_total;
```

---

## 🚀 Implementasi

### File yang Dimodifikasi

1. **`app/Http/Controllers/KehadiranTukangController.php`**
   - Method `index()` - Support range tanggal
   
2. **`app/Http/Controllers/KeuanganTukangController.php`**
   - Method `detailGajiTukang()` - Fix potongan pinjaman logic
   
3. **`resources/views/manajemen-tukang/kehadiran/index.blade.php`**
   - Form pencarian - Support range tanggal
   - Tabel view - Support 2 mode (single/range)
   - JavaScript - Add `lihatDetailRange()` function

---

## ✨ Fitur Tambahan (Rekomendasi Ke Depan)

1. **Export Range Kehadiran ke Excel/PDF**
   - Filter data dalam range tanggal
   - Generate report dengan summary per tukang

2. **Batch Edit Kehadiran**
   - Edit multiple tukang dalam range tanggal sekaligus
   
3. **Rekapitulasi Potongan**
   - Dashboard untuk track semua potongan yang sudah/belum dipotong dari gaji
   
4. **Auto-check Consistency**
   - Daily cron job untuk verify keselarasan data kehadiran vs gaji

---

## 📞 Contact & Support

Jika ada pertanyaan atau issue, silakan lapor ke tim development.

**Last Updated:** 15 Januari 2026
