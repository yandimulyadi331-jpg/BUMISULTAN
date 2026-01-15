# 🔧 TECHNICAL IMPLEMENTATION GUIDE

**Fitur**: Menu Perawatan - Aplikasi Mobile Karyawan  
**Date**: 15 Januari 2026  
**Version**: 1.0  

---

## 📁 File Structure

```
bumisultanAPP/
├── app/Http/Controllers/
│   ├── PerawatanKaryawanController.php        ✏️ MODIFIED
│   └── PresensiController.php                 ✏️ MODIFIED (added method)
├── resources/views/
│   └── perawatan/karyawan/
│       └── checklist.blade.php                ✏️ MODIFIED
├── routes/
│   └── web.php                                ✏️ MODIFIED
└── docs/
    ├── IMPLEMENTASI_FITUR_PERAWATAN_KARYAWAN.md      📝 NEW
    ├── SUMMARY_PERUBAHAN_PERAWATAN.md                📝 NEW
    └── DEPLOYMENT_CHECKLIST.md                       📝 NEW
```

---

## 🔄 Data Flow Diagram

### Upload Foto Perawatan
```
┌─────────────────┐
│ Karyawan Upload │
│   Foto > 2MB    │
└────────┬────────┘
         │
         ▼
┌────────────────────────────────────┐
│ executeChecklist() method          │
│ - Validate: image format only      │
│ - NO max:2048 validation          │
│ - Store file to storage/perawatan  │
└────────┬───────────────────────────┘
         │
         ▼
┌────────────────────────────────────┐
│ Success Response                   │
│ - File stored                      │
│ - Checklist updated                │
│ - No size error                    │
└────────────────────────────────────┘
```

### Absen Pulang Flow
```
┌──────────────────────────┐
│ Karyawan Ingin Pulang    │
│ (dari dashboard/menu)     │
└────────┬─────────────────┘
         │
         ▼
┌──────────────────────────────────────┐
│ Check Checklist Status               │
│ - Is mandatory? No → Skip modal      │
│ - Is completed? Yes → Skip modal     │
│ - Is optional? Yes → Skip modal      │
└────────┬─────────────────────────────┘
         │ (Checklist wajib & belum selesai)
         ▼
┌──────────────────────────────────────┐
│ Show Modal Checkout Confirmation     │
│ - Tombol "Batal"                     │
│ - Tombol "Kerjakan" → Navigate       │
│ - Tombol "Pulang" → AJAX             │
└─┬───────────────────────────────────┬┘
  │                                   │
  ▼ (Kerjakan)                    ▼ (Pulang)
┌──────────────────┐         ┌──────────────────┐
│ Navigate to      │         │ AJAX POST/PUT    │
│ Checklist Page   │         │ /presensi/...    │
└──────────────────┘         └────┬─────────────┘
                                  │
                          ┌───────▼────────┐
                          │ updateAbsenPulang()
                          │ - Get user NIK
                          │ - Check duplicate
                          │ - Update jam_out
                          │ - Send notification
                          └───────┬────────┘
                                  │
                          ┌───────▼────────┐
                          │ Success Response
                          │ - Return JSON
                          │ - Navigate to
                          │   perawatan index
                          └────────────────┘
```

---

## 💾 Database Schema

### Presensi Table (Existing)
```sql
CREATE TABLE presensi (
    id BIGINT PRIMARY KEY,
    nik VARCHAR(20),
    tanggal DATE,
    kode_jam_kerja VARCHAR(20),
    jam_in TIME,
    jam_out TIME,              -- ← Updated by updateAbsenPulang()
    lokasi_in VARCHAR(255),
    lokasi_out VARCHAR(255),
    foto_in VARCHAR(255),
    foto_out VARCHAR(255),
    status ENUM('h', 'i', 'c'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (nik) REFERENCES karyawan(nik)
);
```

### PerawatanLog Table (Existing)
```sql
CREATE TABLE perawatan_log (
    id BIGINT PRIMARY KEY,
    master_perawatan_id BIGINT,
    user_id BIGINT,
    tanggal_eksekusi DATE,
    waktu_eksekusi TIME,
    status ENUM('completed'),
    catatan TEXT,
    foto_bukti VARCHAR(255),    -- ← No size limit now
    periode_key VARCHAR(50),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (master_perawatan_id) REFERENCES master_perawatan(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 🔌 API Endpoints

### 1. Execute Checklist (Existing - Modified Validation)
**Endpoint**: `POST /perawatan/karyawan/execute`  
**Controller**: `PerawatanKaryawanController@executeChecklist()`

**Request**:
```json
{
    "master_perawatan_id": 123,
    "periode_key": "harian_2026-01-15",
    "catatan": "Sudah membersihkan area",
    "foto_bukti": [File Object]
}
```

**Validation Rules** (Updated):
```php
'master_perawatan_id' => 'required|exists:master_perawatan,id',
'periode_key' => 'required|string',
'catatan' => 'nullable|string|max:500',
'foto_bukti' => 'required|image'  // ← No max:2048
```

**Response**:
```json
{
    "success": true,
    "message": "Berhasil!"
}
```

---

### 2. Update Absen Pulang (NEW)
**Endpoint**: `PUT /presensi/update-absen-pulang`  
**Controller**: `PresensiController@updateAbsenPulang()`  
**Middleware**: `auth` (Authenticated users only)

**Request**:
```json
{
    "periode_tipe": "harian",           // Optional
    "periode_key": "harian_2026-01-15", // Optional
    "skip_checklist": true              // Optional
}
```

**Logic Flow**:
1. Get authenticated user → Get user NIK via `UserKaryawan`
2. Find karyawan by NIK
3. Get today's date and current time
4. Find existing presensi record for today
5. Check if already absen pulang (jam_out != null)
6. Update or create presensi with jam_out
7. Send NotificationService
8. Send WhatsApp if enabled
9. Return JSON response

**Response Success** (200):
```json
{
    "success": true,
    "status": true,
    "message": "Berhasil Absen Pulang"
}
```

**Response Error** (400/403/404/500):
```json
{
    "success": false,
    "status": false,
    "message": "Error message here"
}
```

**Error Cases**:
- User not found → 403
- Karyawan not found → 404
- Already clocked out → 400
- Exception → 500

---

## 📱 Frontend Components

### Modal Checkout Confirmation
**ID**: `modalCheckoutConfirm`  
**Trigger**: Via JavaScript function `showCheckoutConfirmation(message)`

**HTML Structure**:
```html
<div class="modal fade" id="modalCheckoutConfirm">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Konfirmasi Absen Pulang</h5>
                <button class="btn-close"></button>
            </div>
            <div class="modal-body">
                <p>Checklist belum selesai. Apa yang ingin Anda lakukan?</p>
                <div id="checkoutMessage">
                    <!-- Dynamic message here -->
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnBatal" data-bs-dismiss="modal">Batal</button>
                <button id="btnKerjakan">Kerjakan</button>
                <button id="btnPulang">Pulang</button>
            </div>
        </div>
    </div>
</div>
```

### JavaScript Event Handlers

#### Button Kerjakan
```javascript
$('#btnKerjakan').on('click', function() {
    $('#modalCheckoutConfirm').modal('hide');
    window.location.href = '{{ route("perawatan.karyawan.checklist", $tipe) }}';
});
```

#### Button Pulang
```javascript
$('#btnPulang').on('click', function() {
    const $btn = $(this);
    $btn.prop('disabled', true).html('<spinner/> Memproses...');
    
    $.ajax({
        url: '{{ route("presensi.updateAbsenPulang") }}',
        type: 'POST',
        data: { 
            '_method': 'PUT',
            'periode_tipe': '{{ $tipe }}',
            'periode_key': '{{ $periodeKey }}',
            'skip_checklist': true
        },
        headers: { 'X-CSRF-TOKEN': token },
        success: function(response) {
            // Show success message
            // Navigate to perawatan index
        },
        error: function(xhr) {
            // Show error message
            // Re-enable button
        }
    });
});
```

---

## 🔐 Security Considerations

### 1. Authentication
- All endpoints require `auth` middleware
- Uses `Auth::user()` to get current user
- Prevents unauthorized access

### 2. Authorization
- Users can only update their own presensi
- Via `UserKaryawan` relationship validation
- Cannot modify other users' records

### 3. CSRF Protection
- All forms have `@csrf` token
- AJAX requests include `X-CSRF-TOKEN` header
- Prevents cross-site attacks

### 4. Input Validation
```php
// For file uploads
'foto_bukti' => 'required|image'  // Validates MIME type

// For request params
'master_perawatan_id' => 'required|exists:master_perawatan,id'  // Exists validation
'periode_key' => 'required|string'

// For text inputs
'catatan' => 'nullable|string|max:500'  // Max length
```

### 5. File Storage
- Files stored in `storage/perawatan/`
- Public accessible via Laravel's storage symlink
- Filename includes timestamp & user ID for uniqueness

---

## 🧪 Unit Test Examples

```php
// Tests/Feature/PerawatanKaryawanControllerTest.php

public function test_upload_foto_without_size_limit()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->image('perawatan.jpg')->size(5000); // 5MB
    
    $response = $this->post('/perawatan/karyawan/execute', [
        'master_perawatan_id' => 1,
        'periode_key' => 'harian_2026-01-15',
        'foto_bukti' => $file
    ]);
    
    $response->assertSuccessful();
    Storage::disk('public')->assertExists('perawatan/perawatan_' . $user->id . '*.jpg');
}

public function test_absen_pulang_success()
{
    $user = User::factory()->create();
    $karyawan = Karyawan::factory()->create(['nik' => 'TEST123']);
    UserKaryawan::create(['user_id' => $user->id, 'nik' => 'TEST123']);
    Presensi::create([
        'nik' => 'TEST123',
        'tanggal' => now()->date,
        'jam_in' => now()->format('H:i:s'),
        'jam_out' => null,
        'status' => 'h'
    ]);
    
    $this->actingAs($user);
    
    $response = $this->put('/presensi/update-absen-pulang', [
        'skip_checklist' => true
    ]);
    
    $response->assertSuccessful();
    $response->assertJson(['success' => true]);
    
    $presensi = Presensi::where('nik', 'TEST123')->first();
    $this->assertNotNull($presensi->jam_out);
}
```

---

## 📊 Performance Considerations

### 1. Query Optimization
```php
// Load relationships efficiently
$presensi = Presensi::with(['user', 'jamkerja'])->find($id);

// Use select() to limit columns
$presensi = Presensi::select('id', 'nik', 'jam_out')->first();

// Use exists() for existence check
if (Presensi::where('nik', $nik)->where('jam_out', '!=', null)->exists()) {
    // Already clocked out
}
```

### 2. File Upload Optimization
- Laravel auto-compresses images via middleware (if configured)
- Can add image optimization in future
- Consider CDN for large files

### 3. Notification Optimization
- WhatsApp notification sent async (if using queue)
- Real-time notification via WebSocket (if implemented)
- No blocking operations in main request

---

## 🚨 Error Handling

### Exception Types
1. **ValidationException** → 422
2. **AuthenticationException** → 401
3. **AuthorizationException** → 403
4. **ModelNotFoundException** → 404
5. **Exception** → 500 (generic)

### Error Responses
```json
{
    "success": false,
    "message": "Human-readable error message",
    "errors": {
        "field_name": ["Error detail 1", "Error detail 2"]
    }
}
```

---

## 📚 Related Documentation

- See `IMPLEMENTASI_FITUR_PERAWATAN_KARYAWAN.md` for feature overview
- See `SUMMARY_PERUBAHAN_PERAWATAN.md` for change summary
- See `DEPLOYMENT_CHECKLIST.md` for deployment procedures

---

## 🤝 Code Review Checklist

- [x] All changes follow Laravel conventions
- [x] Proper exception handling
- [x] Input validation on all endpoints
- [x] No hardcoded values
- [x] Comments on complex logic
- [x] Consistent code formatting
- [x] No SQL injection vulnerabilities
- [x] CSRF protection enabled
- [x] Proper HTTP status codes
- [x] No sensitive data in logs

---

**Version**: 1.0  
**Last Updated**: 15 Januari 2026  
**Status**: ✅ Ready for Development
