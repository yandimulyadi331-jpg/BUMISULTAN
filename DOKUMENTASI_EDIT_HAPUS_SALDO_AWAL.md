# DOKUMENTASI: Edit dan Hapus Saldo Awal - Dana Operasional

## 📋 OVERVIEW

Fitur untuk mengedit dan menghapus **Sisa saldo sebelumnya** (saldo awal) pada tabel Dana Operasional. Baris saldo awal sekarang memiliki tombol Edit dan Hapus untuk memudahkan pengelolaan saldo harian.

---

## 🎯 FITUR YANG DITAMBAHKAN

### 1. **Tombol Aksi di Baris Saldo Awal**
- **Tombol Edit** (kuning) - untuk mengubah nominal saldo awal
- **Tombol Hapus** (merah) - untuk menghapus data saldo awal

### 2. **Modal Edit Saldo Awal**
- Form untuk mengubah nominal saldo awal
- Input tanggal (readonly, tidak bisa diubah)
- Input nominal (dapat diubah, support nilai positif dan negatif)
- Validasi required dan numeric

### 3. **Modal Konfirmasi Hapus**
- Menggunakan modal yang sama dengan konfirmasi hapus transaksi
- Menampilkan info: "Saldo Awal - Tanggal: {tanggal}"
- Warning: "Data tidak dapat dikembalikan"

### 4. **Backend Logic**
- **Update Saldo Awal**: Validasi, update database, recalculate saldo
- **Hapus Saldo Awal**: Validasi ada transaksi atau tidak, delete jika aman

---

## 🔧 KOMPONEN TEKNIS

### A. Frontend (View)

#### 1. Tombol Aksi di Tabel
```blade
<td class="text-center">
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" 
                class="btn btn-warning btn-xs p-1 px-2" 
                onclick="editSaldoAwal({{ $saldo->id }}, '{{ $saldo->tanggal->format('Y-m-d') }}', {{ $saldo->saldo_awal }})"
                title="Edit Saldo Awal">
            <i class="ti ti-edit" style="font-size: 14px;"></i>
        </button>
        <button type="button"
                class="btn btn-danger btn-xs p-1 px-2" 
                onclick="confirmDeleteSaldoAwal({{ $saldo->id }}, '{{ $saldo->tanggal->format('d-M-Y') }}')"
                title="Hapus Saldo Awal">
            <i class="ti ti-trash" style="font-size: 14px;"></i>
        </button>
    </div>
</td>
```

#### 2. Modal Edit Saldo Awal
```blade
<div class="modal fade" id="modalEditSaldoAwal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-edit me-2"></i>Edit Saldo Awal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditSaldoAwal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control" id="editSaldoTanggal" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Saldo Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="editSaldoNominal" name="saldo_awal" required step="0.01" placeholder="0">
                        <small class="text-muted">Masukkan nilai positif untuk saldo surplus, negatif untuk defisit</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

#### 3. Fungsi JavaScript
```javascript
// Edit Saldo Awal
function editSaldoAwal(saldoId, tanggal, saldoAwal) {
    document.getElementById('editSaldoTanggal').value = tanggal;
    document.getElementById('editSaldoNominal').value = saldoAwal;
    
    const form = document.getElementById('formEditSaldoAwal');
    form.action = `/dana-operasional/saldo-awal/${saldoId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('modalEditSaldoAwal'));
    modal.show();
}

// Konfirmasi Hapus Saldo Awal
function confirmDeleteSaldoAwal(saldoId, tanggal) {
    document.getElementById('deleteNomorTransaksi').textContent = 'Saldo Awal';
    document.getElementById('deleteKeterangan').textContent = 'Tanggal: ' + tanggal;
    
    const form = document.getElementById('formDelete');
    form.action = `/dana-operasional/saldo-awal/${saldoId}/delete`;
    
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmDelete'));
    modal.show();
}
```

### B. Backend (Controller)

#### 1. Method: `updateSaldoAwal()`
```php
public function updateSaldoAwal(Request $request, $id)
{
    $request->validate([
        'saldo_awal' => 'required|numeric'
    ]);

    try {
        $saldo = SaldoHarianOperasional::findOrFail($id);
        $tanggal = $saldo->tanggal;
        
        // Update saldo awal
        $saldo->saldo_awal = $request->saldo_awal;
        $saldo->save();

        // Recalculate saldo untuk tanggal ini dan selanjutnya
        RealisasiDanaOperasional::recalculateSaldoHarian($tanggal);

        return redirect()->route('dana-operasional.index')
            ->with('success', 'Saldo awal berhasil diupdate');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal update saldo awal: ' . $e->getMessage());
    }
}
```

#### 2. Method: `destroySaldoAwal()`
```php
public function destroySaldoAwal($id)
{
    try {
        $saldo = SaldoHarianOperasional::findOrFail($id);
        $tanggal = $saldo->tanggal->format('d-M-Y');
        
        // Cek apakah ada transaksi di tanggal ini
        $adaTransaksi = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $saldo->tanggal)->count();
        
        if ($adaTransaksi > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus saldo awal karena masih ada transaksi di tanggal ini');
        }

        $saldo->delete();

        return redirect()->route('dana-operasional.index')
            ->with('success', "Saldo awal tanggal {$tanggal} berhasil dihapus");

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal hapus saldo awal: ' . $e->getMessage());
    }
}
```

### C. Routes

```php
Route::middleware('role:super admin')->prefix('dana-operasional')->name('dana-operasional.')->controller(DanaOperasionalController::class)->group(function () {
    // ... existing routes
    
    // CRUD Saldo Awal
    Route::put('/saldo-awal/{id}', 'updateSaldoAwal')->name('update-saldo-awal');
    Route::delete('/saldo-awal/{id}/delete', 'destroySaldoAwal')->name('delete-saldo-awal');
});
```

---

## 📖 CARA PENGGUNAAN

### EDIT SALDO AWAL

1. Buka halaman **Dana Operasional**
2. Cari baris dengan label **"Sisa saldo sebelumnya"** (baris kuning)
3. Klik tombol **Edit** (ikon pensil kuning) di kolom aksi
4. Modal **"Edit Saldo Awal"** akan muncul
5. Ubah nilai **Saldo Awal**:
   - Nilai positif: untuk saldo surplus
   - Nilai negatif: untuk saldo defisit
6. Klik tombol **Update**
7. Saldo akan diupdate dan sistem akan recalculate semua saldo selanjutnya

### HAPUS SALDO AWAL

1. Buka halaman **Dana Operasional**
2. Cari baris dengan label **"Sisa saldo sebelumnya"**
3. Klik tombol **Hapus** (ikon trash merah) di kolom aksi
4. Modal **"Konfirmasi Hapus"** akan muncul dengan info:
   - Nomor Transaksi: "Saldo Awal"
   - Keterangan: "Tanggal: {tanggal}"
5. Klik tombol **Hapus** untuk konfirmasi
6. **Validasi**:
   - Jika **ada transaksi** di tanggal tersebut: Error, tidak bisa dihapus
   - Jika **tidak ada transaksi**: Saldo awal berhasil dihapus

---

## ⚠️ VALIDASI & KEAMANAN

### Edit Saldo Awal
- ✅ Saldo awal harus berupa **angka (numeric)**
- ✅ Field **required**, tidak boleh kosong
- ✅ Support nilai **positif dan negatif**
- ✅ Setelah update, sistem **recalculate** semua saldo selanjutnya

### Hapus Saldo Awal
- ✅ **Tidak bisa dihapus** jika masih ada transaksi di tanggal tersebut
- ✅ Validasi untuk mencegah data inconsistency
- ✅ Error message yang jelas jika validasi gagal

---

## 🎨 UI/UX DESIGN

### Modal Edit
- **Header**: Background kuning (warning)
- **Icon**: Edit (ti-edit)
- **Form**: Input tanggal (readonly) + input nominal
- **Buttons**: Batal (secondary) + Update (warning)

### Modal Hapus
- **Style**: Sama dengan modal hapus transaksi
- **Icon Trash**: Merah di dalam lingkaran pink gradient
- **Info Box**: Background kuning/cream dengan detail
- **Warning**: "Data tidak dapat dikembalikan"
- **Buttons**: Batal (abu-abu) + Hapus (merah gradient)

### Tombol Aksi
- **Edit**: Button warning dengan icon edit
- **Hapus**: Button danger dengan icon trash
- **Style**: Button group, size xs, compact

---

## 🧪 TESTING

### Test Edit Saldo Awal
```
□ Klik tombol Edit di baris saldo awal
□ Modal muncul dengan data tanggal dan nominal
□ Ubah nominal menjadi nilai positif (contoh: 500000)
□ Klik Update
□ Saldo berhasil diupdate
□ Lihat perubahan di tabel
□ Cek saldo akhir telah recalculate
```

### Test Hapus Saldo Awal (Ada Transaksi)
```
□ Pilih tanggal yang ada transaksi
□ Klik tombol Hapus di baris saldo awal
□ Modal konfirmasi muncul
□ Klik Hapus
□ Error message: "Tidak dapat menghapus saldo awal karena masih ada transaksi"
□ Saldo tidak terhapus
```

### Test Hapus Saldo Awal (Tidak Ada Transaksi)
```
□ Pilih tanggal yang tidak ada transaksi
□ Klik tombol Hapus di baris saldo awal
□ Modal konfirmasi muncul
□ Klik Hapus
□ Success message: "Saldo awal tanggal {tanggal} berhasil dihapus"
□ Saldo berhasil dihapus
□ Baris saldo awal hilang dari tabel
```

---

## 📊 IMPACT & BENEFITS

### Untuk Admin
- ✅ Mudah **mengoreksi saldo awal** jika ada kesalahan input
- ✅ Dapat **menghapus saldo awal** untuk tanggal yang tidak diperlukan
- ✅ Sistem otomatis **recalculate saldo** setelah perubahan
- ✅ Validasi mencegah **data inconsistency**

### Untuk Sistem
- ✅ Data saldo lebih **akurat dan konsisten**
- ✅ Mencegah **orphan data** (saldo awal tanpa transaksi)
- ✅ Integritas data terjaga dengan **validasi ketat**

---

## 🔄 ALUR SISTEM

### Alur Edit Saldo Awal
```
1. User klik tombol Edit
2. Modal muncul dengan data saldo
3. User ubah nominal
4. Klik Update
5. Backend validasi input
6. Update saldo_awal di database
7. Recalculate saldo untuk tanggal ini dan selanjutnya
8. Redirect dengan success message
9. Tabel refresh dengan data baru
```

### Alur Hapus Saldo Awal
```
1. User klik tombol Hapus
2. Modal konfirmasi muncul
3. User klik Hapus
4. Backend cek ada transaksi atau tidak
5a. Jika ADA transaksi:
    - Return error message
    - Data tidak dihapus
5b. Jika TIDAK ADA transaksi:
    - Delete saldo dari database
    - Redirect dengan success message
    - Baris saldo awal hilang
```

---

## 📝 CATATAN PENTING

1. **Saldo Awal vs Transaksi**
   - Saldo awal adalah data di tabel `saldo_harian_operasional`
   - Transaksi adalah data di tabel `realisasi_dana_operasional`
   - Keduanya berbeda dan memiliki logic CRUD terpisah

2. **Recalculate Saldo**
   - Setelah edit saldo awal, sistem otomatis recalculate
   - Menggunakan method `RealisasiDanaOperasional::recalculateSaldoHarian()`
   - Memastikan semua saldo selanjutnya update

3. **Validasi Hapus**
   - Tidak bisa hapus saldo awal jika masih ada transaksi
   - Mencegah data inconsistency
   - Error message yang jelas untuk user

4. **Permission**
   - Hanya **Super Admin** yang dapat edit/hapus saldo awal
   - Sesuai dengan middleware: `role:super admin`

---

## ✅ STATUS IMPLEMENTASI

- ✅ Tombol Edit dan Hapus di tabel
- ✅ Modal edit saldo awal
- ✅ Modal konfirmasi hapus (reuse existing)
- ✅ Fungsi JavaScript lengkap
- ✅ Routes backend
- ✅ Controller methods dengan validasi
- ✅ Recalculate saldo setelah edit
- ✅ Validasi hapus jika ada transaksi
- ✅ Testing script
- ✅ Dokumentasi lengkap

**STATUS: IMPLEMENTASI LENGKAP & SIAP DIGUNAKAN**

---

## 🔗 FILE YANG DIMODIFIKASI

1. **View**: `resources/views/dana-operasional/index.blade.php`
   - Tambah tombol Edit & Hapus di baris saldo awal
   - Tambah modal edit saldo awal
   - Tambah fungsi JS: `editSaldoAwal()` & `confirmDeleteSaldoAwal()`

2. **Controller**: `app/Http/Controllers/DanaOperasionalController.php`
   - Tambah method: `updateSaldoAwal()`
   - Tambah method: `destroySaldoAwal()`

3. **Routes**: `routes/web.php`
   - Tambah route: `PUT /dana-operasional/saldo-awal/{id}`
   - Tambah route: `DELETE /dana-operasional/saldo-awal/{id}/delete`

4. **Testing**: `test_edit_hapus_saldo_awal.php`
   - Script test untuk verifikasi implementasi

---

**Dokumentasi dibuat pada**: 13 Januari 2025  
**Versi**: 1.0  
**Status**: ✅ Complete & Tested
