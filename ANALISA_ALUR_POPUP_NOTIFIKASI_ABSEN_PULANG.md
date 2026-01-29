# 📋 ANALISA ALUR POPUP NOTIFIKASI ABSEN PULANG

## 🎯 RINGKASAN PERMINTAAN

User ingin:
1. **Popup notifikasi** muncul SEBELUM karyawan absen pulang
2. **Tidak ada ubah struktur** - hanya modifikasi popup notifikasi
3. Saat klik tombol **"Pulang"** → Bisa absen dengan scan wajah + lokasi (seperti biasa)
4. Saat klik **"Selesaikan"** di popup → Diarahkan ke halaman **Checklist Karyawan**

---

## 🏗️ STRUKTUR SAAT INI (CURRENT FLOW)

### 1. **Halaman Utama Absensi Karyawan**
- **File**: `resources/views/presensiistirahat/create.blade.php`
- **Fitur Utama**:
  - Webcam untuk scan wajah (Face Recognition)
  - Tombol "Mulai Istirahat" dan "Selesai Istirahat"
  - Daftar perawatan/checklist jika ada
  - Dropdown lokasi kantor/cabang

### 2. **Alur Absen Pulang Saat Ini**

```
┌─────────────────────────────────┐
│   Karyawan di Halaman Presensi   │
│  (resources/views/presensi...)   │
└────────────┬────────────────────┘
             │
             ▼
   ┌─────────────────────────┐
   │ Klik Tombol "Pulang"    │
   │ (#takeabsenakhiri)      │
   └────────┬────────────────┘
            │
            ▼
   ┌─────────────────────────┐
   │ Cek Face Recognition?   │
   │ (faceRecognitionDetected)│
   └────────┬────────────────┘
            │
            ├─ TIDAK terdeteksi → Error Alert
            │
            └─ Terdeteksi ✓
                   │
                   ▼
        ┌──────────────────────┐
        │ Snap Photo + Ambil   │
        │ Koordinat Lokasi     │
        │ Webcam.snap()        │
        └────────┬─────────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ AJAX POST ke server  │
        │ /presensiistirahat   │
        │ /store               │
        └────────┬─────────────┘
                 │
                 ├─ Success
                 │   ├─ Swal Alert ✓
                 │   └─ Redirect ke Dashboard
                 │
                 └─ Error
                     ├─ Swal Alert ✗
                     └─ Tetap di halaman presensi
```

---

## 🔍 ANALISA DETAIL FILE TERKAIT

### **A. File Halaman Presensi**
```
📁 resources/views/presensiistirahat/create.blade.php
```

**Line 666-671**: Audio untuk notifikasi
```blade
<audio id="notifikasi_sudahabsenpulang">
    <source src="{{ asset('assets/sound/sudahabsenpulang.mp3') }}" type="audio/mpeg">
</audio>
<audio id="notifikasi_absenpulang">
    <source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg">
</audio>
```

**Line 1520-1650**: Tombol "Selesai Istirahat" Handler
```javascript
$("#takeabsenakhiri").click(function() {
    // Disable tombol
    // Ubah text menjadi "Loading..."
    // Snap foto dari webcam
    // Cek face recognition
    // AJAX POST ke /presensiistirahat/store
    // Sukses → Redirect ke /dashboard
    // Error → Tampil error alert
});
```

### **B. Halaman Checklist Perawatan**
```
📁 resources/views/perawatan/checklist.blade.php
```

**Fitur**:
- Menampilkan checklist wajib/opsional
- Progress bar
- Tombol "Kembali" ke perawatan.index
- Tidak ada "Checkout" explicit di halaman ini

### **C. Controller yang Handle Absensi**
```
📁 app/Http/Controllers/PresensiController.php
```

**Endpoint**: `/presensiistirahat/store`
- Receive POST data dari halaman presensi
- Proses face recognition validation
- Simpan data presensi
- Return JSON response

---

## 📍 ANALISA ALUR YANG DIINGINKAN

### **Alur Ideal (Yang User Mau)**

```
┌────────────────────────────────┐
│  Karyawan di Halaman Presensi  │
│     (bersama Checklist)         │
└─────────┬──────────────────────┘
          │
          ▼
┌────────────────────────────────┐
│  Klik Tombol "PULANG"          │
│  (Baru - dengan notifikasi)    │
└─────────┬──────────────────────┘
          │
          ▼
┌────────────────────────────────┐
│   POPUP NOTIFIKASI MUNCUL      │
│                                │
│  Pesan: "Siap untuk absen      │
│   pulang?"                     │
│                                │
│  ┌─────────────┐ ┌───────────┐│
│  │  [Batal]    │ │[Lanjutkan]││
│  └─────────────┘ └───────────┘│
└─────────┬──────────────────────┘
          │
          ├─ Klik "BATAL" → Tutup popup, kembali
          │
          └─ Klik "LANJUTKAN"
                   │
                   ▼
          ┌────────────────────────┐
          │ Buka Modal Presensi    │
          │ (Face + Lokasi Scan)   │
          │                        │
          │ - Kamera webcam        │
          │ - Scan wajah           │
          │ - Ambil lokasi         │
          │ - Pilih cabang (opt)   │
          │                        │
          │ [Selesaikan]           │
          └────────┬───────────────┘
                   │
                   ├─ Success
                   │   └─ REDIRECT ke /perawatan/checklist
                   │       (Halaman Checklist Karyawan)
                   │
                   └─ Error → Alert & tetap di modal
```

---

## 💡 SOLUSI & IMPLEMENTASI

### **OPSI 1: Popup Modal Sederhana (RECOMMENDED)**

#### **Struktur HTML**
Tambahkan di [presensiistirahat/create.blade.php](presensiistirahat/create.blade.php) sebelum closing section:

```html
<!-- Modal Notifikasi Pulang -->
<div class="modal fade" id="modalNotifikasiPulang" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning border-0">
                <h5 class="modal-title" id="modalTitle">
                    <i class="ti ti-alert-circle me-2"></i>Konfirmasi Absen Pulang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-3">
                    <strong>Anda siap untuk absen pulang?</strong>
                </p>
                <div class="alert alert-info" role="alert">
                    <i class="ti ti-info-circle me-2"></i>
                    Proses ini akan melakukan scan wajah dan lokasi Anda untuk verifikasi.
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnLanjutkanPulang">
                    <i class="ti ti-check me-2"></i>Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>
```

#### **Modifikasi JavaScript Handler**

**Dari**:
```javascript
$("#takeabsenakhiri").click(function() {
    // langsung proses AJAX
});
```

**Menjadi**:
```javascript
$("#takeabsenakhiri").click(function() {
    // Tampilkan modal notifikasi
    const modalPulang = new bootstrap.Modal(document.getElementById('modalNotifikasiPulang'));
    modalPulang.show();
});

// Handler tombol "Lanjutkan" di modal
$("#btnLanjutkanPulang").click(function() {
    // Tutup modal
    bootstrap.Modal.getInstance(document.getElementById('modalNotifikasiPulang')).hide();
    
    // Proses absen (scan wajah + lokasi)
    processAbsenPulang();
});

function processAbsenPulang() {
    $("#takeabsenakhiri").prop('disabled', true);
    $("#takeabsenakhiri").html(
        '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading...</span></div> Loading...'
    );
    
    let status = '2';
    Webcam.snap(function(uri) {
        image = uri;
    });
    
    if (faceRecognitionDetected == 0 && faceRecognition == 1) {
        swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Wajah tidak terdeteksi',
            didClose: function() {
                $("#takeabsenakhiri").prop('disabled', false);
                $("#takeabsenakhiri").html(
                    '<ion-icon name="finger-print-outline" style="font-size: 20px; margin-right: 8px;"></ion-icon>Pulang'
                );
            }
        });
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url: "{{ route('presensiistirahat.store') }}",
        data: {
            _token: "{{ csrf_token() }}",
            image: image,
            status: status,
            lokasi: lokasi,
            lokasi_cabang: lokasi_cabang,
            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
        },
        success: function(data) {
            if (data.status == true) {
                swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 4000
                }).then(function() {
                    // ✅ UBAH REDIRECT KE HALAMAN CHECKLIST
                    window.location.href = '/perawatan/checklist';
                });
            }
        },
        error: function(xhr) {
            swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseJSON.message,
                didClose: function() {
                    $("#takeabsenakhiri").prop('disabled', false);
                    $("#takeabsenakhiri").html(
                        '<ion-icon name="finger-print-outline" style="font-size: 20px; margin-right: 8px;"></ion-icon>Pulang'
                    );
                }
            });
        }
    });
}
```

---

### **OPSI 2: SweetAlert2 Custom Dialog (MODERN)**

Lebih simple, hanya gunakan SweetAlert:

```javascript
$("#takeabsenakhiri").click(function() {
    swal.fire({
        title: 'Konfirmasi Absen Pulang',
        html: `
            <p>Anda siap untuk absen pulang?</p>
            <div class="alert alert-info mt-3">
                <small>Proses ini akan melakukan scan wajah dan lokasi untuk verifikasi.</small>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            processAbsenPulang();
        }
    });
});
```

---

## 📊 PERBANDINGAN OPSI

| Aspek | Opsi 1 (Modal) | Opsi 2 (SweetAlert) |
|-------|----------------|-------------------|
| **UI/UX** | Profesional, Bootstrap native | Modern, Eye-catching |
| **Kompleksitas** | Medium | Simple |
| **Customization** | Tinggi | Cukup tinggi |
| **Consistency** | Sesuai design system | Berbeda dari design |
| **Recommended** | ✅ Lebih baik | ✓ Cukup baik |

---

## ✅ CHECKLIST IMPLEMENTASI

### **Phase 1: Popup Notifikasi**
- [ ] Tambahkan modal HTML di presensi view
- [ ] Modifikasi handler #takeabsenakhiri
- [ ] Extract logic absen ke function `processAbsenPulang()`
- [ ] Test popup muncul saat klik "Pulang"
- [ ] Test bisa close popup dengan "Batal"

### **Phase 2: Integrasi Checklist**
- [ ] Ubah redirect dari `/dashboard` → `/perawatan/checklist`
- [ ] Pastikan route `/perawatan/checklist` sudah ada dan punya parameter periode
- [ ] Test redirect ke halaman checklist setelah absen pulang sukses

### **Phase 3: Testing**
- [ ] Cek popup notifikasi muncul
- [ ] Cek proses absen (wajah + lokasi) masih berfungsi
- [ ] Cek redirect ke checklist berhasil
- [ ] Cek error handling tetap bekerja
- [ ] Cek di mobile dan desktop

---

## 🔗 FILE YANG PERLU DIMODIFIKASI

1. **[presensiistirahat/create.blade.php](resources/views/presensiistirahat/create.blade.php)**
   - Tambah modal HTML
   - Modifikasi JavaScript handler

2. **Kemungkinan**: [PresensiController.php](app/Http/Controllers/PresensiController.php)
   - Jika ingin ubah redirect response ke `/perawatan/checklist`

---

## 🎨 MOCKUP POPUP NOTIFIKASI

```
╔════════════════════════════════════╗
║ ⚠️  Konfirmasi Absen Pulang        ║
╠════════════════════════════════════╣
║                                    ║
║  Anda siap untuk absen pulang?    ║
║                                    ║
║  ℹ️  Proses ini akan melakukan    ║
║      scan wajah dan lokasi untuk   ║
║      verifikasi.                   ║
║                                    ║
╠════════════════════════════════════╣
║  [Batal]          [Lanjutkan ✓]   ║
╚════════════════════════════════════╝
```

---

## 🚀 NEXT STEPS

1. **Pilih Opsi** (Modal Bootstrap atau SweetAlert2)
2. **Implementasi** modifikasi kode
3. **Testing** di environment lokal
4. **Deploy** ke production

---

**Last Updated**: 2025-01-24
**Status**: Analysis Complete - Ready for Implementation
