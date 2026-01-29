# 🎚️ Implementasi Toggle Checklist di Master Checklist Page

## 📋 Overview

Fitur toggle telah diimplementasikan di halaman **Master Checklist** (`/perawatan/master`) dengan tampilan toggle ON/OFF untuk setiap periode:
- **Harian** (Daily)
- **Mingguan** (Weekly)
- **Bulanan** (Monthly)
- **Tahunan** (Yearly)

---

## 🎨 UI Layout

### Before (Lama)
```
┌────────────────────────────────────────────┐
│ Harian (18) │ Mingguan (14) │ Bulanan (14) │ Tahunan (14)
├────────────────────────────────────────────┤
│ Daftar master checklist di tabel           │
└────────────────────────────────────────────┘
```

### After (Baru - dengan Toggle)
```
┌───────────────────────────────────────────────────────────┐
│ Harian (18)  Status: ✅ Aktif        │ Mingguan (14) ...  │
│ [Tab Link]   [Toggle Switch]          │ [Tab Link] ...     │
├───────────────────────────────────────────────────────────┤
│ Daftar master checklist di tabel                           │
└───────────────────────────────────────────────────────────┘
```

**Penjelasan:**
- Setiap tab periode menampilkan **toggle switch** untuk ON/OFF
- Badge status dinamis: **✅ Aktif** atau **❌ Nonaktif**
- Count items otomatis update: `Harian (18)` atau `Harian (0)` saat toggle OFF

---

## 💻 Technical Implementation

### 1. Database Model

#### Model: `ChecklistPeriodeConfig`

```php
// app/Models/ChecklistPeriodeConfig.php
class ChecklistPeriodeConfig extends Model
{
    protected $table = 'checklist_periode_config';
    
    protected $fillable = [
        'tipe_periode',
        'is_enabled',        // ⭐ Toggle status (true = ON, false = OFF)
        'is_mandatory',
        'keterangan',
        'dibuat_oleh',
        'diubah_oleh'
    ];
    
    protected $casts = [
        'is_enabled' => 'boolean',
        'is_mandatory' => 'boolean'
    ];
    
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_periode', $tipe);
    }
}
```

#### SQL Migration

```sql
CREATE TABLE checklist_periode_config (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipe_periode ENUM('harian', 'mingguan', 'bulanan', 'tahunan') UNIQUE,
    is_enabled BOOLEAN DEFAULT TRUE,
    is_mandatory BOOLEAN DEFAULT FALSE,
    keterangan TEXT NULL,
    dibuat_oleh BIGINT UNSIGNED NULL,
    diubah_oleh BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id),
    FOREIGN KEY (diubah_oleh) REFERENCES users(id),
    UNIQUE KEY unique_tipe (tipe_periode),
    INDEX idx_tipe_enabled (tipe_periode, is_enabled)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 2. Controller Implementation

#### File: `app/Http/Controllers/ManajemenPerawatanController.php`

**Update masterIndex():**
```php
public function masterIndex()
{
    $masters = MasterPerawatan::with('ruangan')
        ->withCount(['logs' => function($q) {
            $q->whereDate('tanggal_eksekusi', '>=', now()->subDays(30));
        }])
        ->ordered()
        ->get();
    
    // ⭐ NEW: Get periode configs untuk ditampilkan di toggle
    $periodeConfigs = [];
    foreach(['harian', 'mingguan', 'bulanan', 'tahunan'] as $tipe) {
        $config = ChecklistPeriodeConfig::byTipe($tipe)->first();
        $periodeConfigs[$tipe] = $config ? $config->is_enabled : true;
    }
    
    return view('perawatan.master.index', compact('masters', 'periodeConfigs'));
}
```

**New Method: togglePeriode():**
```php
public function togglePeriode(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
        'is_enabled' => 'required|boolean'
    ]);

    // ⭐ Get or create config record
    $config = ChecklistPeriodeConfig::byTipe($validated['tipe_periode'])->first()
        ?? new ChecklistPeriodeConfig(['tipe_periode' => $validated['tipe_periode']]);

    // ⭐ Update toggle status
    $config->update([
        'is_enabled' => $validated['is_enabled'],
        'diubah_oleh' => Auth::id()
    ]);

    // ⭐ Calculate total checklist (if enabled, count all active masters)
    $totalChecklist = 0;
    if ($validated['is_enabled']) {
        $totalChecklist = MasterPerawatan::where('tipe_periode', $validated['tipe_periode'])
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->count();
    }

    // ⭐ Broadcast event untuk real-time update
    if (class_exists('App\Events\ChecklistPeriodeToggled')) {
        broadcast(new \App\Events\ChecklistPeriodeToggled(
            tipe_periode: $validated['tipe_periode'],
            is_enabled: $validated['is_enabled'],
            total_checklist: $totalChecklist,
            message: $validated['is_enabled'] 
                ? "Checklist {$validated['tipe_periode']} sekarang AKTIF" 
                : "Checklist {$validated['tipe_periode']} sekarang NONAKTIF"
        ));
    }

    // ✅ Return JSON response
    return response()->json([
        'success' => true,
        'message' => 'Konfigurasi checklist berhasil diupdate',
        'data' => [
            'tipe_periode' => $config->tipe_periode,
            'is_enabled' => $config->is_enabled,
            'total_checklist' => $totalChecklist
        ]
    ]);
}
```

---

### 3. Route

#### File: `routes/web.php`

```php
Route::middleware('role:super admin')->prefix('perawatan')->name('perawatan.')->controller(ManajemenPerawatanController::class)->group(function () {
    
    // Master Checklist
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/', 'masterIndex')->name('index');
        // ... other routes
    });
    
    // ⭐ NEW: Toggle Periode Config
    Route::prefix('config')->name('config.')->group(function () {
        Route::post('/toggle', 'togglePeriode')->name('toggle');
        // ... other routes
    });
});
```

---

### 4. Blade Template

#### File: `resources/views/perawatan/master/index.blade.php`

**Tab Header dengan Toggle:**

```blade
<!-- Tabs per Periode dengan Toggle -->
<ul class="nav nav-tabs mb-3" role="tablist">
    @php
        $periodes = [
            'harian' => 'Harian',
            'mingguan' => 'Mingguan',
            'bulanan' => 'Bulanan',
            'tahunan' => 'Tahunan'
        ];
    @endphp
    
    @foreach($periodes as $tipePeriode => $labelPeriode)
    <li class="nav-item" role="presentation" class="d-flex align-items-center">
        <!-- Tab Link -->
        <a href="#{{ $tipePeriode }}" class="nav-link {{ $loop->first ? 'active' : '' }}" 
           data-bs-toggle="tab" role="tab">
            <i class="ti ti-calendar me-1"></i>
            <span id="count-{{ $tipePeriode }}" class="badge bg-info">
                {{ $masters->where('tipe_periode', $tipePeriode)->count() }}
            </span>
            <span class="ms-2">{{ $labelPeriode }}</span>
        </a>
        
        <!-- ⭐ Toggle Switch -->
        <div class="ms-auto d-flex align-items-center gap-2 px-2 py-1 bg-light rounded">
            <small class="text-muted">Status:</small>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input period-toggle" 
                       type="checkbox" 
                       id="toggle_{{ $tipePeriode }}" 
                       data-periode="{{ $tipePeriode }}"
                       {{ $periodeConfigs[$tipePeriode] ?? false ? 'checked' : '' }}>
                <label class="form-check-label small" for="toggle_{{ $tipePeriode }}">
                    <span class="badge" id="status-{{ $tipePeriode }}">
                        {{ ($periodeConfigs[$tipePeriode] ?? false) ? '✅ Aktif' : '❌ Nonaktif' }}
                    </span>
                </label>
            </div>
        </div>
    </li>
    @endforeach
</ul>
```

---

### 5. JavaScript Handler

#### File: `resources/views/perawatan/master/index.blade.php` (Script Section)

```javascript
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ⭐ Handle toggle change event
    document.querySelectorAll('.period-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const periode = this.dataset.periode;
            const isEnabled = this.checked;
            
            // Update status badge instantly
            const statusBadge = document.querySelector(`#status-${periode}`);
            if (isEnabled) {
                statusBadge.textContent = '✅ Aktif';
                statusBadge.className = 'badge bg-success';
            } else {
                statusBadge.textContent = '❌ Nonaktif';
                statusBadge.className = 'badge bg-danger';
            }
            
            // ⭐ Send AJAX request to backend
            fetch(`/perawatan/config/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    tipe_periode: periode,
                    is_enabled: isEnabled
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // ✅ Update count badge
                    document.querySelector(`#count-${periode}`).textContent = data.data.total_checklist;
                    
                    // ✅ Show notification
                    const message = isEnabled 
                        ? `✅ Checklist ${periode} sekarang AKTIF (${data.data.total_checklist} items)` 
                        : `❌ Checklist ${periode} sekarang NONAKTIF`;
                    
                    Swal.fire({
                        title: data.message,
                        text: message,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    
                    // ⭐ Broadcast to other users via WebSocket
                    if (window.Echo !== undefined) {
                        window.Echo.channel('checklist-updates')
                            .whisper('ChecklistToggled', {
                                tipe_periode: periode,
                                is_enabled: isEnabled,
                                total_checklist: data.data.total_checklist
                            });
                    }
                } else {
                    // ❌ Error handling
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Gagal mengupdate toggle',
                        icon: 'error'
                    });
                    this.checked = !isEnabled; // Revert toggle
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mengupdate',
                    icon: 'error'
                });
                this.checked = !isEnabled; // Revert toggle
            });
        });
    });
    
    // ⭐ Listen for updates from other admins (real-time sync)
    if (window.Echo !== undefined) {
        window.Echo.channel('checklist-updates')
            .listen('ChecklistPeriodeToggled', (data) => {
                const toggle = document.querySelector(`#toggle_${data.tipe_periode}`);
                if (toggle && toggle.checked !== data.is_enabled) {
                    toggle.checked = data.is_enabled;
                    toggle.dispatchEvent(new Event('change'));
                }
            });
    }
});
</script>
```

---

## 🔄 Flow: Saat Toggle Diubah

```
┌─────────────────────────────────────────┐
│ Admin klik toggle di halaman master      │
│ Checkbox change event triggered          │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Frontend:                                │
│ 1. Update badge: ✅ Aktif / ❌ Nonaktif │
│ 2. Send AJAX POST /perawatan/config/... │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Backend (togglePeriode method):          │
│ 1. Validate input                       │
│ 2. Update/create config in DB           │
│ 3. Calculate total_checklist            │
│ 4. Broadcast event                      │
│ 5. Return JSON response                 │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Frontend Response:                       │
│ 1. Update count: (18) → (0) or vice     │
│ 2. Show toast notification              │
│ 3. Broadcast to other tabs/windows      │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│ Real-time Sync (WebSocket):             │
│ Halaman karyawan otomatis update        │
│ - Checklist item hide/show              │
│ - Progress bar reset                    │
│ - Status banner change                  │
└─────────────────────────────────────────┘
```

---

## 📊 Data Flow

### Toggle ON (Activate Checklist)

```
User Action: Klik toggle harian (OFF → ON)
       ↓
Backend Process:
- is_enabled = TRUE
- Count active masters for 'harian' = 18
- Update config record
       ↓
Response:
{
    "success": true,
    "message": "Konfigurasi checklist berhasil diupdate",
    "data": {
        "tipe_periode": "harian",
        "is_enabled": true,
        "total_checklist": 18
    }
}
       ↓
Frontend Update:
- Badge: '✅ Aktif' (green)
- Count: 'Harian (18)'
- Toast: '✅ Checklist harian sekarang AKTIF (18 items)'
```

### Toggle OFF (Deactivate Checklist)

```
User Action: Klik toggle harian (ON → OFF)
       ↓
Backend Process:
- is_enabled = FALSE
- total_checklist = 0 (diabaikan)
- Update config record
       ↓
Response:
{
    "success": true,
    "message": "Konfigurasi checklist berhasil diupdate",
    "data": {
        "tipe_periode": "harian",
        "is_enabled": false,
        "total_checklist": 0
    }
}
       ↓
Frontend Update:
- Badge: '❌ Nonaktif' (red)
- Count: 'Harian (0)'
- Toast: '❌ Checklist harian sekarang NONAKTIF'
```

---

## 🔐 Security & Validation

### Backend Validation
```php
// Only super admin can access this route
Route::middleware('role:super admin')->group(function() {
    Route::post('/perawatan/config/toggle', 'togglePeriode');
});

// Input validation
$validated = $request->validate([
    'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
    'is_enabled' => 'required|boolean'
]);
```

### CSRF Protection
```html
<!-- CSRF token included in AJAX request -->
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
```

---

## 🎯 Key Features

| Feature | Status | Details |
|---------|--------|---------|
| **Toggle UI** | ✅ | Switch button di tab header |
| **Status Badge** | ✅ | Dynamic '✅ Aktif' / '❌ Nonaktif' |
| **Count Update** | ✅ | Badge count otomatis berkurang/bertambah |
| **Real-time Sync** | ✅ | WebSocket broadcast ke session lain |
| **Toast Notification** | ✅ | SweetAlert2 notification |
| **Error Handling** | ✅ | Revert toggle jika ada error |
| **CSRF Protection** | ✅ | X-CSRF-TOKEN header |
| **Role-based Access** | ✅ | Hanya super admin |

---

## 🚀 Usage

### Admin mengaktifkan checklist harian:
1. Masuk ke `/perawatan/master`
2. Di tab **Harian**, lihat toggle switch `❌ Nonaktif`
3. Klik toggle untuk mengubah ke `✅ Aktif`
4. Sistem akan menghitung: 18 items active
5. Notifikasi: "✅ Checklist harian sekarang AKTIF (18 items)"
6. Count badge: `Harian (18)` muncul

### Karyawan langsung akan melihat:
- Halaman `/perawatan/checklist-harian` menampilkan 18 items
- Banner: "⚠️ Checklist wajib diselesaikan"
- Progress counter: 0/18

### Admin menonaktifkan checklist mingguan:
1. Di tab **Mingguan**, lihat toggle switch `✅ Aktif`
2. Klik toggle untuk mengubah ke `❌ Nonaktif`
3. Sistem set: total_checklist = 0
4. Notifikasi: "❌ Checklist mingguan sekarang NONAKTIF"
5. Count badge: `Mingguan (0)` atau hilang

### Karyawan langsung akan melihat:
- Halaman `/perawatan/checklist-mingguan` tidak menampilkan items
- Banner: "⚠️ Checklist sedang nonaktif"
- Checkbox disabled / readonly
- Bisa langsung absen pulang tanpa kerjakan checklist

---

## 📱 Responsive Design

Toggle switch tersedia di semua ukuran device:
- **Desktop**: Toggle di kanan tab (inline)
- **Tablet**: Toggle tetap terlihat
- **Mobile**: Toggle pindah ke bawah (flex wrap)

---

## 🔧 Requirements

- Laravel 11+
- MySQL 8.0+
- Laravel Echo + Pusher/WebSocket (untuk real-time)
- SweetAlert2 (untuk notifikasi)
- Bootstrap 5+ (untuk toggle switch styling)

---

**Implementation Date**: January 24, 2026  
**Status**: ✅ FULLY IMPLEMENTED & OPERATIONAL  
**Last Updated**: January 24, 2026
