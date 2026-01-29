# 🚀 QUICK REFERENCE: Toggle Potongan Pinjaman Tukang Per-Minggu

**Dibuat**: 29 Januari 2026  
**Untuk**: Developer & Admin

---

## 📌 TL;DR (Too Long; Didn't Read)

**Apa?** Sistem toggle potongan pinjaman tukang per-minggu dengan riwayat tercatat.

**Kenapa?** Agar admin bisa non-aktifkan potongan di minggu tertentu tanpa merubah status global, dan sistem otomatis mencatat riwayat serta terintegrasi dengan laporan.

**Gimana?** Toggle ON/OFF di detail pinjaman → sistem record history → laporan terupdate otomatis.

---

## 🗂️ FILES OVERVIEW

| File | Tipe | Fungsi |
|------|------|--------|
| `2026_01_29_000001_create_potongan_pinjaman_payroll_detail_table.php` | Migration | Database table untuk history potongan per-minggu |
| `PotonganPinjamanPayrollDetail.php` | Model | Model untuk table history potongan |
| `Tukang.php` | Model | +8 methods untuk query history |
| `PinjamanTukang.php` | Model | +5 methods untuk record & query |
| `KeuanganTukangController.php` | Controller | Updated `togglePotonganPinjaman()` + helper |
| `detail.blade.php` (pinjaman) | View | Updated toggle + tabel riwayat |
| Dokumentasi files | Docs | 3 files dokumentasi lengkap |

---

## 🔧 INSTALLATION

### 1. Run Migration (WAJIB)
```bash
php artisan migrate
```

### 2. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

### 3. Done! ✅

---

## 🎯 USAGE CONTOH

### A. User Scenario: Klik Toggle di Detail Pinjaman

```
1. Buka: /keuangan-tukang/pinjaman/[id]
2. Lihat: Status Potongan (AKTIF/NONAKTIF)
3. Klik: Toggle OFF
4. Input: Alasan "Tukang sakit" (opsional)
5. Kirim: POST /toggle-potongan-pinjaman/[tukang_id]
6. Result: ✅ History tercatat, tabel riwayat update
```

### B. Developer Scenario: Dapatkan Data Riwayat

```php
$tukang = Tukang::find(123);

// Get status minggu 5 tahun 2026
$status = $tukang->getStatusPotonganMinggu(2026, 5);
// Return: 'DIPOTONG' | 'TIDAK_DIPOTONG' | 'TIDAK_TERCATAT'

// Get nominal cicilan minggu 5 (0 jika tidak dipotong)
$nominal = $tukang->getNominalCicilanMinggu(2026, 5);
// Return: 0 | 150000 (tergantung status)

// Get detail lengkap minggu 5
$detail = $tukang->getDetailPotonganMinggu(2026, 5);
// Return: PotonganPinjamanPayrollDetail object

// Get riwayat Januari 2026
$riwayat = $tukang->getRiwayatPotonganBulan(2026, 1);
// Return: Collection (12 minggu)

// Get total cicilan yang dipotong bulan Januari
$total = $tukang->getTotalCicilanDipotongBulan(2026, 1);
// Return: 450000 (3 minggu × 150000)
```

### C. Admin Scenario: Lihat Laporan

```
Laporan Gaji (Kamis):
- Query: SELECT * FROM pembayaran_gaji_tukangs WHERE ...
- Untuk setiap tukang:
  * Cek: $tukang->getNominalCicilanMinggu($tahun, $minggu)
  * Jika DIPOTONG: tampilkan cicilan di kolom "Potongan"
  * Jika TIDAK_DIPOTONG: cicilan = 0
- Result: ✅ Laporan otomatis terupdate
```

---

## 💾 DATABASE TABLE SCHEMA

```sql
CREATE TABLE potongan_pinjaman_payroll_detail (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tukang_id BIGINT NOT NULL,              -- Foreign key ke tukangs
    pinjaman_tukang_id BIGINT,              -- Foreign key ke pinjaman_tukangs
    tahun INT NOT NULL,                     -- 2026, 2025, dll
    minggu INT NOT NULL,                    -- 1-52 (ISO 8601)
    tanggal_mulai DATE NOT NULL,            -- Senin minggu itu
    tanggal_selesai DATE NOT NULL,          -- Minggu minggu itu
    status_potong ENUM('DIPOTONG', 'TIDAK_DIPOTONG'),
    nominal_cicilan DECIMAL(12,2) NOT NULL DEFAULT 0,
    alasan_tidak_potong VARCHAR(255),       -- Jika tidak dipotong
    toggle_by VARCHAR(100),                 -- Siapa ubah
    toggle_at TIMESTAMP,                    -- Kapan ubah
    catatan TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Indexes
    UNIQUE KEY uk_tukang_minggu (tukang_id, tahun, minggu),
    KEY idx_tukang_tahun_minggu (tukang_id, tahun, minggu),
    KEY idx_status_potong (status_potong)
);
```

---

## 🔌 API ENDPOINTS

### Endpoint: Toggle Potongan

```
POST /keuangan-tukang/toggle-potongan-pinjaman/{tukang_id}

Request Body:
{
  "alasan_tidak_potong": "Tukang sakit" // Opsional
}

Response Success:
{
  "success": true,
  "message": "Status potongan pinjaman untuk [NAMA] sekarang [STATUS] (Minggu 5/2026)",
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

Response Error:
{
  "success": false,
  "message": "Tidak ada pinjaman aktif untuk tukang ini"
}
```

---

## 🧮 LOGIC FLOW

```
┌─────────────────────────────────────────────────┐
│ User Klik Toggle                                │
├─────────────────────────────────────────────────┤
│ ↓                                               │
│ POST /toggle-potongan-pinjaman/{tukang_id}     │
├─────────────────────────────────────────────────┤
│ ↓                                               │
│ Controller: togglePotonganPinjaman()            │
│  • Validasi pinjaman aktif                      │
│  • Toggle status auto_potong_pinjaman           │
│  • Get minggu-tahun saat ini (ISO 8601)        │
│  • Record history ke tabel baru                │
│  • Recalculate gaji                            │
│  • Return JSON                                  │
├─────────────────────────────────────────────────┤
│ ↓                                               │
│ Frontend: Reload halaman                        │
│  • Update badge status                          │
│  • Tabel riwayat +1 row                        │
│  • SweetAlert notification                      │
├─────────────────────────────────────────────────┤
│ ↓                                               │
│ Data Tersimpan:                                 │
│  • Table: potongan_pinjaman_payroll_detail     │
│  • Status: DIPOTONG / TIDAK_DIPOTONG           │
│  • History: Toggle by siapa, kapan, alasan     │
├─────────────────────────────────────────────────┤
│ ↓                                               │
│ Laporan Otomatis Terupdate:                    │
│  • Cek getNominalCicilanMinggu()                │
│  • Kolom Potongan adjust otomatis              │
└─────────────────────────────────────────────────┘
```

---

## 📊 DATA CONTOH

### Riwayat Potongan 3 Minggu Terakhir

| Minggu | Range Tanggal | Status | Nominal | Alasan |
|--------|---------------|--------|---------|--------|
| 6/2026 | 03-09 Feb | DIPOTONG | 150.000 | - |
| 5/2026 | 27 Jan-02 Feb | TIDAK_DIPOTONG | 0 | Tukang sakit |
| 4/2026 | 20-26 Jan | DIPOTONG | 150.000 | - |

### Query Sample

```sql
-- Get riwayat minggu 5 tahun 2026
SELECT * FROM potongan_pinjaman_payroll_detail 
WHERE tukang_id = 123 
  AND tahun = 2026 
  AND minggu = 5;

-- Get riwayat 12 minggu terakhir
SELECT * FROM potongan_pinjaman_payroll_detail 
WHERE tukang_id = 123 
ORDER BY minggu DESC 
LIMIT 12;

-- Get total cicilan dipotong bulan Januari 2026
SELECT SUM(nominal_cicilan) as total 
FROM potongan_pinjaman_payroll_detail 
WHERE tukang_id = 123 
  AND tahun = 2026 
  AND minggu BETWEEN 1 AND 4 
  AND status_potong = 'DIPOTONG';

-- Get minggu tidak dipotong bulan Januari
SELECT COUNT(*) as jumlah 
FROM potongan_pinjaman_payroll_detail 
WHERE tukang_id = 123 
  AND tahun = 2026 
  AND minggu BETWEEN 1 AND 4 
  AND status_potong = 'TIDAK_DIPOTONG';
```

---

## 🎨 BLADE TEMPLATE SNIPPED

### Tabel Riwayat Potongan (di detail pinjaman)

```blade
<div class="table-responsive">
   <table class="table table-sm table-hover table-bordered">
      <thead class="table-dark">
         <tr>
            <th width="8%">Minggu</th>
            <th width="20%">Range</th>
            <th width="12%">Status</th>
            <th width="12%">Nominal</th>
            <th>Alasan</th>
            <th width="12%">Diubah</th>
         </tr>
      </thead>
      <tbody>
         @forelse($riwayatPotonganMinggu as $r)
         <tr>
            <td>{{ $r->minggu }}/{{ $r->tahun }}</td>
            <td>{{ $r->tanggal_range }}</td>
            <td>
               @if($r->status_potong == 'DIPOTONG')
               <span class="badge bg-success">✅ DIPOTONG</span>
               @else
               <span class="badge bg-warning">❌ TIDAK DIPOTONG</span>
               @endif
            </td>
            <td class="text-end">
               @if($r->status_potong == 'DIPOTONG')
               <strong>Rp {{ number_format($r->nominal_cicilan, 0, ',', '.') }}</strong>
               @else
               <span class="text-muted">Rp 0</span>
               @endif
            </td>
            <td>{{ $r->alasan_tidak_potong ?? '-' }}</td>
            <td><small>{{ $r->toggle_by }}</small></td>
         </tr>
         @empty
         <tr><td colspan="6" class="text-center">Belum ada riwayat</td></tr>
         @endforelse
      </tbody>
   </table>
</div>
```

---

## ⚠️ PENTING!

### ✅ DO:
- Run `php artisan migrate` sebelum gunakan
- Clear cache setelah install: `php artisan cache:clear`
- Use `getNominalCicilanMinggu()` di laporan, bukan `auto_potong_pinjaman`
- Record history saat toggle diubah
- Track audit trail (siapa ubah, kapan, alasan)

### ❌ DON'T:
- Jangan delete/modify migration file yang sudah run
- Jangan manual update table potongan_pinjaman_payroll_detail
- Jangan bergantung pada `auto_potong_pinjaman` untuk nominal laporan (gunakan history)
- Jangan lupa clear cache saat update model

---

## 🆘 TROUBLESHOOTING

| Problem | Solusi |
|---------|--------|
| Migration gagal | `php artisan migrate:status` → cek error detail |
| Toggle tidak terupdate | `php artisan cache:clear` |
| Data tidak tercatat | Cek DB connection, run migration |
| Laporan masih salah | Update view gunakan `getNominalCicilanMinggu()` |
| Minggu tidak sesuai | Verifikasi `APP_TIMEZONE=Asia/Jakarta` di .env |
| Model tidak ditemukan | Tambahkan `use App\Models\PotonganPinjamanPayrollDetail;` |

---

## 📚 DOKUMENTASI LENGKAP

- `ANALISIS_LOGIKA_TOGGLE_POTONGAN_PINJAMAN_MINGGUAN.md` - Full analysis & design
- `DOKUMENTASI_IMPLEMENTASI_TOGGLE_POTONGAN_MINGGUAN.md` - Implementation guide
- `RINGKASAN_IMPLEMENTASI_TOGGLE_POTONGAN_MINGGUAN.md` - Implementation summary
- `QUICK_REFERENCE_TOGGLE_POTONGAN_MINGGUAN.md` - This file (quick ref)

---

## 🚀 NEXT STEP

1. Run migration: `php artisan migrate`
2. Update blade view dengan template di atas
3. Test toggle functionality
4. Update laporan untuk pakai `getNominalCicilanMinggu()`
5. Verify semua terupdate otomatis

---

**Questions?** Lihat dokumentasi lengkap di folder yang sesuai.

**Ready?** `php artisan migrate` → Go live! 🎉

