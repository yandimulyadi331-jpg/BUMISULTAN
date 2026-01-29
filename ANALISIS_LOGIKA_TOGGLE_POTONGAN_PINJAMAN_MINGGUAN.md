# 📋 ANALISIS LENGKAP: TOGGLE POTONGAN PINJAMAN TUKANG (MINGGUAN)

**Tanggal**: 29 Januari 2026  
**Status**: 🔄 **PLANNING & ANALYSIS**

---

## 🎯 REQUIREMENT YANG DIMINTA

**Logika Toggle Potongan Pinjaman Tukang (Per-Minggu):**

> Potongan pinjaman tukang adalah **wajib setiap minggunya**. Namun, jika ada tukang yang memiliki **keperluan khusus** sehingga pada minggu tertentu **tidak boleh ada potongan**, maka:
> 
> 1. **Admin/User** bisa **non-aktifkan toggle** di minggu itu
> 2. Sistem **otomatis mencatat riwayat** bahwa potongan **TIDAK dilakukan** di minggu itu
> 3. **Nominal terarah** (nominal cicilan tetap jelas di laporan)
> 4. **Terintegrasi dengan laporan gaji** (laporan menampilkan riwayat: potong/tidak potong)
> 5. **Di halaman detail keuangan tukang** (tabel "Bayar Pinjaman"), ada riwayat potongan dan tidak potongan
> 6. **Di halaman detail pinjaman** (tabel "Riwayat Pembayaran Cicilan"), tampilkan status potongan minggu itu

---

## 🔍 ANALISIS SISTEM SAAT INI

### 1. **Struktur Data Existing**

#### Toggle Potongan (Field: `auto_potong_pinjaman`)
- **Model**: `Tukang`
- **Type**: Boolean (1 = ON, 0 = OFF)
- **Fungsi**: Global flag - apakah tukang tersebut bisa di-potong dari gaji atau tidak
- **Problem**: ⚠️ **Saat ini GLOBAL/PERMANEN**, bukan per-minggu
  - Jika toggle OFF untuk tukang X, maka **SELURUH pinjaman** tidak dipotong
  - Tidak ada rekam jejak per-minggu

#### Data Pinjaman Tukang
- **Model**: `PinjamanTukang`
- **Fields**: tukang_id, status, cicilan_per_minggu, sisa_pinjaman, jumlah_terbayar
- **Problem**: ⚠️ Tidak ada field untuk "status potongan minggu ini"

#### Riwayat Pembayaran
- **Model**: `KeuanganTukang` (atau `PinjamanCicilan`)
- **Fields**: tukang_id, jumlah, tanggal, keterangan, dicatat_oleh
- **Problem**: ⚠️ Tidak ada field untuk "apakah minggu ini ada potongan atau tidak"

---

## 🚀 SOLUSI YANG AKAN DIIMPLEMENTASIKAN

### 1. **Database Schema Baru: `potongan_pinjaman_payroll_detail`**

**Tujuan**: Mencatat riwayat potongan pinjaman **per-minggu per-tukang**

```sql
CREATE TABLE potongan_pinjaman_payroll_detail (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tukang_id BIGINT NOT NULL,                    -- FK ke tabel tukangs
    pinjaman_tukang_id BIGINT,                    -- FK ke tabel pinjaman_tukangs (opsional)
    tahun INT NOT NULL,                           -- Tahun (2026, 2025, etc)
    minggu INT NOT NULL,                          -- Minggu (1-52)
    tanggal_mulai DATE NOT NULL,                  -- Hari Senin minggu itu
    tanggal_selesai DATE NOT NULL,                -- Hari Minggu minggu itu
    status_potong ENUM('DIPOTONG', 'TIDAK_DIPOTONG') NOT NULL, -- Status
    nominal_cicilan DECIMAL(12,2) NOT NULL DEFAULT 0,          -- Cicilan per minggu
    alasan_tidak_potong VARCHAR(255),                           -- Jika tidak dipotong, alasan apa
    toggle_by VARCHAR(100),                       -- Siapa yang mengubah toggle
    toggle_at TIMESTAMP,                          -- Kapan toggle diubah
    catatan TEXT,                                 -- Catatan tambahan
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    FOREIGN KEY (tukang_id) REFERENCES tukangs(id),
    FOREIGN KEY (pinjaman_tukang_id) REFERENCES pinjaman_tukangs(id),
    KEY idx_tukang_tahun_minggu (tukang_id, tahun, minggu),
    UNIQUE KEY uk_tukang_minggu (tukang_id, tahun, minggu)
);
```

**Contoh Data:**
```
| id | tukang_id | pinjaman_id | tahun | minggu | status_potong  | nominal_cicilan | alasan | toggle_by | toggle_at |
|----|-----------|-------------|-------|--------|---|---|---|---|
| 1  | 3         | 5           | 2026  | 4      | DIPOTONG       | 150000         | -     | admin     | 2026-01-20 08:00 |
| 2  | 3         | 5           | 2026  | 5      | TIDAK_DIPOTONG | 150000         | Sakit | admin     | 2026-01-23 09:30 |
| 3  | 3         | 5           | 2026  | 6      | DIPOTONG       | 150000         | -     | system    | 2026-01-30 (auto-record) |
```

---

### 2. **Model Baru: `PotonganPinjamanPayrollDetail`**

```php
<?php
namespace App\Models;

class PotonganPinjamanPayrollDetail extends Model
{
    protected $table = 'potongan_pinjaman_payroll_detail';
    
    protected $fillable = [
        'tukang_id',
        'pinjaman_tukang_id',
        'tahun',
        'minggu',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_potong',
        'nominal_cicilan',
        'alasan_tidak_potong',
        'toggle_by',
        'toggle_at',
        'catatan'
    ];
    
    // Relasi
    public function tukang() {
        return $this->belongsTo(Tukang::class);
    }
    
    public function pinjaman() {
        return $this->belongsTo(PinjamanTukang::class, 'pinjaman_tukang_id');
    }
    
    // Scope
    public function scopeMingguan($query, $tukang_id, $tahun, $minggu) {
        return $query->where('tukang_id', $tukang_id)
                     ->where('tahun', $tahun)
                     ->where('minggu', $minggu);
    }
}
```

---

### 3. **Update Model `Tukang`**

```php
class Tukang extends Model
{
    // ... existing code ...
    
    /**
     * Relasi ke riwayat potongan pinjaman
     */
    public function riwayatPotonganPinjaman()
    {
        return $this->hasMany(PotonganPinjamanPayrollDetail::class, 'tukang_id');
    }
    
    /**
     * Method: Dapatkan status potongan untuk minggu tertentu
     */
    public function getStatusPotonganMinggu($tahun, $minggu)
    {
        $record = $this->riwayatPotonganPinjaman()
                       ->where('tahun', $tahun)
                       ->where('minggu', $minggu)
                       ->first();
        
        return $record ? $record->status_potong : 'TIDAK_TERCATAT';
    }
    
    /**
     * Method: Dapatkan nominal cicilan untuk minggu tertentu
     */
    public function getNominalCicilanMinggu($tahun, $minggu)
    {
        $record = $this->riwayatPotonganPinjaman()
                       ->where('tahun', $tahun)
                       ->where('minggu', $minggu)
                       ->first();
        
        if (!$record) return 0;
        if ($record->status_potong == 'TIDAK_DIPOTONG') return 0;
        return $record->nominal_cicilan ?? 0;
    }
}
```

---

### 4. **Update Model `PinjamanTukang`**

```php
class PinjamanTukang extends Model
{
    // ... existing code ...
    
    /**
     * Relasi ke riwayat potongan per-minggu
     */
    public function riwayatPotonganMinggu()
    {
        return $this->hasMany(PotonganPinjamanPayrollDetail::class, 'pinjaman_tukang_id');
    }
    
    /**
     * Method: Record riwayat potongan saat toggle di-ubah
     */
    public function recordPotonganHistory($tahun, $minggu, $status, $toggleBy, $alasan = null)
    {
        // Hitung range tanggal untuk minggu tersebut
        $year = $tahun;
        $week = $minggu;
        $dateTime = new \DateTime();
        $dateTime->setISODate($year, $week, 1); // 1 = Senin
        $tanggal_mulai = $dateTime->format('Y-m-d');
        
        $dateTime->modify('+6 days'); // Minggu
        $tanggal_selesai = $dateTime->format('Y-m-d');
        
        return PotonganPinjamanPayrollDetail::updateOrCreate(
            [
                'tukang_id' => $this->tukang_id,
                'pinjaman_tukang_id' => $this->id,
                'tahun' => $tahun,
                'minggu' => $minggu,
            ],
            [
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'status_potong' => $status,
                'nominal_cicilan' => $this->cicilan_per_minggu,
                'alasan_tidak_potong' => $alasan,
                'toggle_by' => $toggleBy,
                'toggle_at' => now(),
            ]
        );
    }
}
```

---

### 5. **Update Controller: `KeuanganTukangController`**

#### Method: `togglePotonganPinjaman($tukang_id)` (REVISED)

```php
public function togglePotonganPinjaman(Request $request, $tukang_id)
{
    try {
        $tukang = Tukang::findOrFail($tukang_id);
        $pinjamanAktif = PinjamanTukang::where('tukang_id', $tukang_id)
                                       ->where('status', 'aktif')
                                       ->first();
        
        // Jika tidak ada pinjaman aktif, return error
        if (!$pinjamanAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pinjaman aktif untuk tukang ini'
            ], 400);
        }
        
        // Toggle status potongan
        $statusBaru = !$tukang->auto_potong_pinjaman;
        
        // Dapatkan minggu-tahun saat ini
        $mingguTahun = $this->getMingguTahun(now());
        
        // Record history potongan
        $pinjamanAktif->recordPotonganHistory(
            tahun: $mingguTahun['tahun'],
            minggu: $mingguTahun['minggu'],
            status: $statusBaru ? 'DIPOTONG' : 'TIDAK_DIPOTONG',
            toggleBy: auth()->user()->name,
            alasan: $request->input('alasan_tidak_potong', null)
        );
        
        // Update field auto_potong_pinjaman di model Tukang
        $tukang->auto_potong_pinjaman = $statusBaru;
        $tukang->save();
        
        // Recalculate data tukang untuk minggu ini
        $dataBaru = $this->recalculateTukangData($tukang_id, $mingguTahun['tahun'], $mingguTahun['minggu']);
        
        return response()->json([
            'success' => true,
            'message' => 'Status potongan berhasil diubah',
            'status' => $statusBaru,
            'data' => $dataBaru,
            'minggu' => $mingguTahun
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Helper: Dapatkan minggu-tahun saat ini (ISO 8601)
 */
private function getMingguTahun($date)
{
    $carbon = \Carbon\Carbon::parse($date);
    return [
        'tahun' => $carbon->isoFormat('Y'),
        'minggu' => $carbon->isoFormat('W'),
    ];
}

/**
 * Helper: Recalculate data tukang untuk minggu tertentu
 */
private function recalculateTukangData($tukang_id, $tahun, $minggu)
{
    $tukang = Tukang::findOrFail($tukang_id);
    
    // Hitung range tanggal
    $dateTime = new \DateTime();
    $dateTime->setISODate($tahun, $minggu, 1);
    $tanggal_mulai = $dateTime->format('Y-m-d');
    
    $dateTime->modify('+6 days');
    $tanggal_selesai = $dateTime->format('Y-m-d');
    
    // Ambil data gaji untuk minggu ini
    // (dari tabel pembayaran_gaji atau transaksi keuangan)
    
    $statusPotongan = $tukang->getStatusPotonganMinggu($tahun, $minggu);
    $nominalCicilan = $tukang->getNominalCicilanMinggu($tahun, $minggu);
    
    return [
        'tukang_id' => $tukang_id,
        'tahun' => $tahun,
        'minggu' => $minggu,
        'status_potongan' => $statusPotongan,
        'nominal_cicilan' => $nominalCicilan,
        'tanggal_mulai' => $tanggal_mulai,
        'tanggal_selesai' => $tanggal_selesai,
    ];
}
```

---

### 6. **Frontend: Blade View - Detail Pinjaman (`detail.blade.php`)**

#### Update Toggle dengan Modal Input Alasan

```php
<!-- Status Auto Potong (REVISED) -->
@if($pinjaman->status == 'aktif')
<div class="row mb-4">
   <div class="col-12">
      <div class="card border-{{ $pinjaman->tukang->auto_potong_pinjaman ? 'success' : 'secondary' }}">
         <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h6 class="card-title mb-2">
                     <i class="ti ti-settings me-2"></i>Status Potongan Otomatis (Minggu Ini)
                  </h6>
                  <p class="mb-0">
                     @if($pinjaman->tukang->auto_potong_pinjaman)
                        <span class="badge bg-success" style="font-size: 14px; padding: 8px 16px;">
                           <i class="ti ti-check"></i> AKTIF - Cicilan AKAN dipotong minggu ini
                        </span>
                     @else
                        <span class="badge bg-secondary" style="font-size: 14px; padding: 8px 16px;">
                           <i class="ti ti-x"></i> NONAKTIF - Cicilan TIDAK dipotong minggu ini
                        </span>
                     @endif
                  </p>
               </div>
               <div>
                  <div class="form-check form-switch">
                     <input class="form-check-input" 
                            type="checkbox" 
                            role="switch" 
                            id="toggleAutoPotong" 
                            {{ $pinjaman->tukang->auto_potong_pinjaman ? 'checked' : '' }}
                            onchange="showToggleModal()"
                            style="cursor: pointer; width: 3rem; height: 1.5rem;">
                     <label class="form-check-label" for="toggleAutoPotong">
                        <strong>Toggle Auto Potong</strong>
                     </label>
                  </div>
               </div>
            </div>
            <hr>
            <small class="text-muted">
               <i class="ti ti-info-circle me-1"></i>
               Cicilan <strong>Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}</strong> untuk minggu ini.
               Jika toggle diaktifkan, cicilan akan otomatis dipotong dari gaji tukang.
            </small>
         </div>
      </div>
   </div>
</div>
@endif

<!-- Tabel Riwayat Potongan (REVISED) -->
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
                     <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('d/m/Y') }}</td>
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

<!-- Modal Toggle Potongan -->
<div class="modal fade" id="modalTogglePotongan" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Ubah Status Potongan Minggu Ini</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <div class="modal-body">
            <div class="alert alert-info">
               <strong>Tukang:</strong> {{ $pinjaman->tukang->nama_tukang }}<br>
               <strong>Cicilan:</strong> Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}/minggu
            </div>
            <div class="mb-3">
               <label class="form-label">Status Potongan Baru</label>
               <select id="statusPotonganBaru" class="form-select">
                  <option value="">-- Pilih Status --</option>
                  <option value="DIPOTONG">✅ DIPOTONG (Cicilan otomatis dipotong)</option>
                  <option value="TIDAK_DIPOTONG">❌ TIDAK DIPOTONG (Cicilan dibayar manual/nanti)</option>
               </select>
            </div>
            <div class="mb-3">
               <label class="form-label">Alasan (jika tidak dipotong)</label>
               <textarea id="alasanTidakPotong" class="form-control" rows="3" placeholder="Contoh: Tukang sakit, ada kebutuhan mendadak, dll"></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" onclick="confirmTogglePotongan()">
               <i class="ti ti-check me-1"></i> Ubah Status
            </button>
         </div>
      </div>
   </div>
</div>

<script>
function showToggleModal() {
    const modal = new bootstrap.Modal(document.getElementById('modalTogglePotongan'));
    modal.show();
    document.getElementById('toggleAutoPotong').checked = !document.getElementById('toggleAutoPotong').checked; // Kembalikan ke state sebelumnya
}

async function confirmTogglePotongan() {
    const status = document.getElementById('statusPotonganBaru').value;
    const alasan = document.getElementById('alasanTidakPotong').value;
    
    if (!status) {
        Swal.fire('Error', 'Pilih status potongan terlebih dahulu', 'error');
        return;
    }
    
    try {
        const response = await fetch('{{ route("keuangan-tukang.toggle-potongan-pinjaman", $pinjaman->tukang->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                alasan_tidak_potong: alasan
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            Swal.fire('Sukses', 'Status potongan berhasil diubah', 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', result.message, 'error');
        }
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
}
</script>
```

---

### 7. **Update Tabel Detail Keuangan Tukang**

```php
<!-- Di halaman resources/views/keuangan-tukang/detail.blade.php -->

<!-- Tabel Riwayat Pembayaran Pinjaman (REVISED) -->
<div class="card mt-4">
   <div class="card-header">
      <h6 class="mb-0">
         <i class="ti ti-cash me-2"></i> Riwayat Pembayaran Pinjaman
      </h6>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover table-bordered table-sm">
            <thead class="table-dark">
               <tr>
                  <th width="5%">No</th>
                  <th width="12%">Tanggal</th>
                  <th width="15%">Pinjaman</th>
                  <th width="15%">Jumlah Bayar</th>
                  <th width="15%">Sisa Pinjaman</th>
                  <th width="12%">Status Potong</th>
                  <th>Keterangan</th>
               </tr>
            </thead>
            <tbody>
               @forelse($riwayatPinjamanDetail as $index => $bayar)
                  <tr>
                     <td class="text-center">{{ $index + 1 }}</td>
                     <td>{{ \Carbon\Carbon::parse($bayar->tanggal)->format('d/m/Y') }}</td>
                     <td>{{ $bayar->pinjaman->keterangan ?? '-' }}</td>
                     <td class="text-end text-success fw-bold">
                        Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                     </td>
                     <td class="text-end text-{{ $bayar->saldo > 0 ? 'danger' : 'success' }}">
                        Rp {{ number_format($bayar->saldo ?? 0, 0, ',', '.') }}
                     </td>
                     <td>
                        @if(stripos($bayar->keterangan, 'potongan gaji') !== false || stripos($bayar->keterangan, 'auto') !== false)
                           <span class="badge bg-info"><i class="ti ti-robot"></i> OTOMATIS</span>
                        @else
                           <span class="badge bg-secondary"><i class="ti ti-cash-banknote"></i> MANUAL</span>
                        @endif
                     </td>
                     <td>
                        {{ $bayar->keterangan }}
                        @if($bayar->statusPotonganHistory)
                           <br><small class="text-muted">
                              Status: 
                              @if($bayar->statusPotonganHistory->status_potong == 'TIDAK_DIPOTONG')
                                 ❌ Tidak dipotong
                              @else
                                 ✅ Dipotong
                              @endif
                           </small>
                        @endif
                     </td>
                  </tr>
               @empty
                  <tr>
                     <td colspan="7" class="text-center">Belum ada pembayaran pinjaman</td>
                  </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
```

---

## 📊 FLOW DIAGRAM LENGKAP

### Skenario 1: Admin Non-Aktifkan Toggle (Minggu Itu Tidak Dipotong)

```
┌─────────────────────────────────────────────────────────┐
│ 1. Admin buka Detail Pinjaman Tukang (Gambar 2)        │
├─────────────────────────────────────────────────────────┤
│ 2. Lihat "Status Potongan Otomatis (Minggu Ini)"      │
│    - Saat ini: AKTIF (Cicilan akan dipotong)          │
├─────────────────────────────────────────────────────────┤
│ 3. Klik Toggle → OFF                                   │
│    - Muncul Modal: "Ubah Status Potongan"              │
│    - Pilih: "TIDAK DIPOTONG"                          │
│    - Input Alasan: "Tukang sakit"                     │
├─────────────────────────────────────────────────────────┤
│ 4. Klik "Ubah Status" → POST /toggle-potongan-pinjaman │
├─────────────────────────────────────────────────────────┤
│ 5. Controller: togglePotonganPinjaman()                 │
│    a. Dapatkan minggu-tahun saat ini (ISO 8601)       │
│    b. Set status_potong = 'TIDAK_DIPOTONG'            │
│    c. Record ke tabel: potongan_pinjaman_payroll_detail│
│    d. Update auto_potong_pinjaman = false              │
│    e. Return JSON response + data baru                │
├─────────────────────────────────────────────────────────┤
│ 6. Frontend: Reload halaman detail pinjaman            │
│    - Badge berubah: "AKTIF" → "NONAKTIF"              │
│    - Tabel riwayat: +1 baris baru "TIDAK DIPOTONG"    │
├─────────────────────────────────────────────────────────┤
│ 7. Laporan Gaji (Gambar 1) Terupdate Otomatis:        │
│    - Kolom "Potongan" untuk tukang itu:                │
│      * Jika toggle ON  → include cicilan (150.000)    │
│      * Jika toggle OFF → tanpa cicilan (0)            │
│    - Tabel riwayat potongan menampilkan status        │
├─────────────────────────────────────────────────────────┤
│ 8. Halaman Detail Keuangan Tukang Terupdate:          │
│    - Tabel "Riwayat Pembayaran Pinjaman":             │
│      * Muncul column "Status Potong"                  │
│      * Minggu ini: "TIDAK DIPOTONG"                   │
└─────────────────────────────────────────────────────────┘
```

### Skenario 2: Admin Aktifkan Kembali (Minggu Depan Dipotong Lagi)

```
┌─────────────────────────────────────────────────────────┐
│ 1. Minggu depan, Admin buka Detail Pinjaman Tukang     │
├─────────────────────────────────────────────────────────┤
│ 2. Lihat Toggle → masih OFF dari minggu lalu           │
├─────────────────────────────────────────────────────────┤
│ 3. Klik Toggle → ON (Alasan: "Sudah pulih")           │
├─────────────────────────────────────────────────────────┤
│ 4. POST /toggle-potongan-pinjaman                       │
│    - Minggu (baru): DIPOTONG                           │
│    - Record ke database: status_potong = 'DIPOTONG'   │
├─────────────────────────────────────────────────────────┤
│ 5. Laporan Gaji Terupdate:                             │
│    - Minggu baru: cicilan muncul lagi (150.000)       │
│    - Minggu lalu: cicilan tidak ada (0) - tercatat    │
└─────────────────────────────────────────────────────────┘
```

---

## 🎯 INTEGRASI DENGAN 3 KOMPONEN UTAMA

### Komponen 1: Halaman List Pinjaman (`pinjaman/index.blade.php`)

```php
<!-- Kolom "Status Minggu Ini" di tabel -->
@foreach($pinjamanAktif as $pinjaman)
   <tr>
      <td>{{ $pinjaman->tukang->nama_tukang }}</td>
      <td>Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}</td>
      <td>
         @php
            $mingguTahun = now()->isoFormat('Y-W');
            list($tahun, $minggu) = explode('-', $mingguTahun);
            $statusMingguIni = $pinjaman->tukang->getStatusPotonganMinggu($tahun, $minggu);
         @endphp
         
         @if($statusMingguIni == 'DIPOTONG')
            <span class="badge bg-success">✅ DIPOTONG (Minggu Ini)</span>
         @elseif($statusMingguIni == 'TIDAK_DIPOTONG')
            <span class="badge bg-warning text-dark">❌ TIDAK DIPOTONG (Minggu Ini)</span>
         @else
            <span class="badge bg-secondary">Belum tercatat</span>
         @endif
      </td>
      <td>
         <a href="{{ route('keuangan-tukang.pinjaman.detail', $pinjaman->id) }}" class="btn btn-sm btn-info">
            Detail
         </a>
      </td>
   </tr>
@endforeach
```

### Komponen 2: Detail Pinjaman (`pinjaman/detail.blade.php`)

- ✅ Toggle potongan minggu ini (sudah dianalisis di atas)
- ✅ Tabel riwayat potongan per-minggu
- ✅ Modal dengan input alasan

### Komponen 3: Detail Keuangan Tukang (`detail.blade.php`)

- ✅ Tabel "Riwayat Pembayaran Pinjaman" dengan kolom "Status Potong"
- ✅ Menampilkan apakah pembayaran otomatis atau manual

---

## 🔄 DATABASE MIGRATION

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('potongan_pinjaman_payroll_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tukang_id')->constrained('tukangs');
            $table->foreignId('pinjaman_tukang_id')->nullable()->constrained('pinjaman_tukangs');
            $table->integer('tahun');
            $table->integer('minggu');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status_potong', ['DIPOTONG', 'TIDAK_DIPOTONG'])->default('DIPOTONG');
            $table->decimal('nominal_cicilan', 12, 2)->default(0);
            $table->string('alasan_tidak_potong')->nullable();
            $table->string('toggle_by')->nullable();
            $table->timestamp('toggle_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['tukang_id', 'tahun', 'minggu']);
            $table->unique(['tukang_id', 'tahun', 'minggu']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('potongan_pinjaman_payroll_detail');
    }
};
```

---

## 📝 CHECKLIST IMPLEMENTASI

- [ ] **Database**: Create migration dan table `potongan_pinjaman_payroll_detail`
- [ ] **Model**: Create `PotonganPinjamanPayrollDetail` model
- [ ] **Model**: Update `Tukang` dengan relasi dan methods
- [ ] **Model**: Update `PinjamanTukang` dengan recordPotonganHistory()
- [ ] **Controller**: Update `togglePotonganPinjaman()` method
- [ ] **View**: Update `pinjaman/detail.blade.php` dengan toggle dan riwayat
- [ ] **View**: Update `detail.blade.php` dengan tabel riwayat pembayaran
- [ ] **View**: Update `pinjaman/index.blade.php` dengan status minggu ini
- [ ] **Laporan**: Update PDF laporan gaji dengan riwayat potongan
- [ ] **Testing**: Test toggle on/off dengan berbagai skenario
- [ ] **Dokumentasi**: Final documentation

---

## 🚀 NEXT STEP

Lanjut ke implementasi tahap demi tahap sesuai checklist di atas.

