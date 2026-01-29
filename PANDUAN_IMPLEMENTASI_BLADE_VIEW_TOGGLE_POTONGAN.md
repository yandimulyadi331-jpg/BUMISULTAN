# 🎨 PANDUAN IMPLEMENTASI BLADE VIEW: Toggle Potongan Pinjaman

**Created**: 29 Januari 2026  
**For**: Frontend developers

---

## 📌 OVERVIEW

File yang perlu diupdate:  
`resources/views/keuangan-tukang/pinjaman/detail.blade.php`

**Changes**:
1. Update section "Status Potongan Otomatis"
2. Tambah tabel "Riwayat Potongan Pinjaman (Per Minggu)"
3. Update JavaScript function toggleAutoPotong()

---

## 🔄 STEP-BY-STEP IMPLEMENTATION

### STEP 1: Cek File Current

```php
// FILE: resources/views/keuangan-tukang/pinjaman/detail.blade.php
// CARI SECTION: "Status Auto Potong"
```

File sudah ada toggle, kita hanya perlu update logic-nya.

---

### STEP 2: Update Controller untuk Pass Riwayat Data

**File**: `app/Http/Controllers/KeuanganTukangController.php`

**Cari Method**: Mungkin ada method untuk show detail pinjaman (e.g., `show()` atau `detail()`)

**Tambahkan Code** (jika belum ada):
```php
public function show($id) // atau method name yang sesuai
{
    $pinjaman = PinjamanTukang::findOrFail($id);
    
    // [existing code...]
    
    // NEW: Load riwayat potongan minggu
    $riwayatPotonganMinggu = $pinjaman->riwayatPotonganMinggu()
                                      ->orderBy('minggu', 'desc')
                                      ->limit(12)
                                      ->get();
    
    return view('keuangan-tukang.pinjaman.detail', [
        'pinjaman' => $pinjaman,
        'riwayatPotonganMinggu' => $riwayatPotonganMinggu,
        // ... other variables
    ]);
}
```

---

### STEP 3: Update Blade View

**Location**: `resources/views/keuangan-tukang/pinjaman/detail.blade.php`

#### Part 1: Update Section "Status Potongan Otomatis"

**Find**: Bagian dengan card "Status Potongan Otomatis" (kurang lebih line 100-140)

**Replace** section toggle dengan:

```blade
<!-- Status Auto Potong (UPDATED) -->
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
                            onchange="toggleAutoPotong()"
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
```

#### Part 2: Tambah Tabel Riwayat Potongan SEBELUM "Riwayat Pembayaran"

**Find**: Section "<!-- Riwayat Pembayaran -->"

**Insert SEBELUM section itu**, tambahkan:

```blade
<!-- Riwayat Potongan Pinjaman Per Minggu (NEW) -->
<div class="card mb-4">
   <div class="card-header">
      <h6 class="mb-0">
         <i class="ti ti-history me-2"></i>Riwayat Potongan Pinjaman (Per Minggu)
      </h6>
   </div>
   <div class="card-body">
      @if($riwayatPotonganMinggu && $riwayatPotonganMinggu->count() > 0)
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
                  <td>
                     {{ \Carbon\Carbon::parse($riwayat->tanggal_mulai)->format('d/m/Y') }} - 
                     {{ \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('d/m/Y') }}
                  </td>
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
                  <td colspan="7" class="text-center text-muted">Belum ada riwayat potongan</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
      @else
      <div class="alert alert-info">
         <i class="ti ti-info-circle me-2"></i>
         Belum ada riwayat potongan. Riwayat akan tercatat saat toggle diubah.
      </div>
      @endif
   </div>
</div>
```

#### Part 3: Update JavaScript Function

**Find**: Function `toggleAutoPotong()` (mungkin sudah ada di file, di bagian `<script>`)

**Replace dengan code ini**:

```javascript
<script>
async function toggleAutoPotong() {
   const toggle = document.getElementById('toggleAutoPotong');
   const isChecked = toggle.checked;
   const tukangId = {{ $pinjaman->tukang->id }};
   const namaTukang = '{{ $pinjaman->tukang->nama_tukang }}';
   const cicilan = {{ $pinjaman->cicilan_per_minggu }};
   
   try {
      const response = await fetch(`{{ url('keuangan-tukang/toggle-potongan-pinjaman') }}/${tukangId}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
         },
         body: JSON.stringify({
            alasan_tidak_potong: isChecked ? null : prompt('Alasan tidak dipotong (opsional):')
         })
      });
      
      const data = await response.json();
      
      if (data.success) {
         // Build HTML untuk SweetAlert
         let htmlNotif = `
            <div class="text-start">
               <h6 class="mb-3">✅ Status Berhasil Diubah</h6>
               <table class="table table-sm table-borderless">
                  <tr>
                     <td><strong>Tukang:</strong></td>
                     <td>${namaTukang}</td>
                  </tr>
                  <tr>
                     <td><strong>Status:</strong></td>
                     <td>${isChecked ? '<span class="badge bg-success">AKTIF - Cicilan akan dipotong</span>' : '<span class="badge bg-warning text-dark">NONAKTIF - Cicilan tidak dipotong</span>'}</td>
                  </tr>
                  <tr>
                     <td><strong>Nominal:</strong></td>
                     <td>Rp ${isChecked ? new Intl.NumberFormat('id-ID').format(${cicilan}) : '0'}</td>
                  </tr>
                  <tr>
                     <td><strong>Minggu:</strong></td>
                     <td>${data.minggu.minggu}/${data.minggu.tahun}</td>
                  </tr>
               </table>
               <div class="alert alert-info mt-3 mb-0">
                  <i class="ti ti-info-circle me-2"></i>
                  Riwayat potongan akan diperbarui. Data di laporan gaji juga akan otomatis terupdate.
               </div>
            </div>
         `;
         
         Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            html: htmlNotif,
            showConfirmButton: true,
            confirmButtonText: 'Refresh Halaman',
            timer: 5000
         }).then(() => {
            location.reload();
         });
      } else {
         throw new Error(data.message || 'Gagal mengubah status');
      }
   } catch (error) {
      console.error('Error:', error);
      toggle.checked = !isChecked; // Revert toggle
      
      Swal.fire({
         icon: 'error',
         title: 'Gagal!',
         text: error.message || 'Terjadi kesalahan saat mengubah status auto potong',
         showConfirmButton: true
      });
   }
}
</script>
```

---

## ✅ CHECKLIST IMPLEMENTASI BLADE

- [ ] Update section "Status Potongan Otomatis"
- [ ] Tambah tabel "Riwayat Potongan Pinjaman (Per Minggu)"
- [ ] Update JavaScript function toggleAutoPotong()
- [ ] Test toggle functionality
- [ ] Verify riwayat table menampilkan data
- [ ] Verify SweetAlert notification muncul
- [ ] Verify halaman reload setelah sukses
- [ ] Test dengan berbagai scenario

---

## 🧪 TESTING CHECKLIST

### Test Scenario 1: Toggle OFF (tidak dipotong)
```
1. Buka detail pinjaman
2. Lihat status: AKTIF
3. Klik toggle OFF
4. Muncul prompt "Alasan tidak dipotong"
5. Input: "Tukang sakit"
6. Klik OK
7. SweetAlert muncul: "Status Berhasil Diubah"
8. Halaman reload
9. Check: Badge = NONAKTIF, Tabel +1 row baru
10. Verify database: status_potong = 'TIDAK_DIPOTONG'
```

### Test Scenario 2: Toggle ON (dipotong)
```
1. Dari state OFF (sebelumnya)
2. Klik toggle ON
3. SweetAlert muncul
4. Halaman reload
5. Check: Badge = AKTIF, Tabel +1 row baru
6. Verify database: status_potong = 'DIPOTONG'
```

### Test Scenario 3: Laporan Terupdate
```
1. Buka Laporan Gaji (Kamis PDF)
2. Cari tukang yang togglenya OFF
3. Kolom "Potongan" seharusnya TIDAK ada cicilan
4. Cari tukang yang togglenya ON
5. Kolom "Potongan" seharusnya ADA cicilan
```

---

## 🐛 TROUBLESHOOTING BLADE

| Problem | Solusi |
|---------|--------|
| Riwayat table kosong | Pastikan data sudah tercatat di DB. Run: `php artisan migrate` |
| Toggle tidak merespons | Clear cache: `php artisan cache:clear`. Check browser console untuk JS error |
| SweetAlert tidak muncul | Pastikan SweetAlert2 sudah loaded di layout. Check di `resources/views/layouts/app.blade.php` |
| Data tidak reload | Reload manual halaman, atau check network tab untuk POST response |
| Styling berantakan | Verify Bootstrap classes dan icon library (ti icons) sudah loaded |

---

## 📝 NOTES

### Important!
1. **Pass data from controller**: Controller harus pass `$riwayatPotonganMinggu` ke view
2. **Handle null**: Cek `$riwayatPotonganMinggu` dengan `@if` dan `@forelse`
3. **Date format**: Use `\Carbon\Carbon::parse()` untuk parse tanggal
4. **Number format**: Use `number_format()` untuk format Rupiah
5. **Status badge**: Use conditional color (green/yellow) berdasarkan status

### CSS/JS Dependencies
- Bootstrap 5 (sudah ada, untuk layout & badge)
- Tabler Icons (sudah ada, untuk icon)
- SweetAlert2 (pastikan sudah included di layout)
- jQuery/Vanilla JS (untuk AJAX POST)

### Backend Dependencies
- Model `PotonganPinjamanPayrollDetail` harus sudah ada
- Method `riwayatPotonganMinggu()` di `PinjamanTukang` model
- Method `togglePotonganPinjaman()` di Controller sudah updated
- Migration sudah run: `php artisan migrate`

---

## 🔗 CROSS-REFERENCE

**Related Files**:
- Controller method: `KeuanganTukangController->togglePotonganPinjaman()`
- Model relasi: `PinjamanTukang->riwayatPotonganMinggu()`
- Documentation: `DOKUMENTASI_IMPLEMENTASI_TOGGLE_POTONGAN_MINGGUAN.md`

**Test Data Query**:
```sql
-- Check riwayat tercatat
SELECT * FROM potongan_pinjaman_payroll_detail 
WHERE tukang_id = [ID]
ORDER BY minggu DESC
LIMIT 10;

-- Check status minggu tertentu
SELECT status_potong, nominal_cicilan 
FROM potongan_pinjaman_payroll_detail 
WHERE tukang_id = [ID] AND tahun = 2026 AND minggu = 5;
```

---

## ✅ FINAL CHECKLIST

- [ ] File sudah dibaca
- [ ] Controller method sudah pass `$riwayatPotonganMinggu`
- [ ] Blade view sudah diupdate dengan template baru
- [ ] JavaScript function sudah diganti
- [ ] Bootstrap & icons sudah loaded
- [ ] SweetAlert2 sudah included
- [ ] Tested toggle ON/OFF
- [ ] Tested laporan terupdate
- [ ] Tested database record tersimpan
- [ ] Ready to go live ✅

---

**Questions?** Lihat dokumentasi lengkap atau check code examples di file.

**Ready?** Let's implement! 🚀

