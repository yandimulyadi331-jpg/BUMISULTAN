# 🛠️ STEP-BY-STEP IMPLEMENTATION GUIDE

## Overview
Panduan lengkap untuk mengimplementasikan Toggle Checklist feature dari awal.

---

## Phase 1: Database Setup

### Step 1.1: Create Migration
```bash
php artisan make:migration create_checklist_periode_config_table
```

### Step 1.2: Write Migration Code
File: `database/migrations/XXXX_XX_XX_XXXXXX_create_checklist_periode_config_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_periode_config', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_periode', ['harian', 'mingguan', 'bulanan', 'tahunan'])->unique();
            $table->boolean('is_enabled')->default(true)->index();
            $table->boolean('is_mandatory')->default(false);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->unsignedBigInteger('diubah_oleh')->nullable();
            $table->timestamps();
            
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('set null');
            $table->foreign('diubah_oleh')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['tipe_periode', 'is_enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_periode_config');
    }
};
```

### Step 1.3: Run Migration
```bash
php artisan migrate
```

### Step 1.4: Seed Initial Data
Buka `php artisan tinker`:
```bash
php artisan tinker
```

Jalankan:
```php
foreach(['harian', 'mingguan', 'bulanan', 'tahunan'] as $tipe) {
    \App\Models\ChecklistPeriodeConfig::updateOrCreate(
        ['tipe_periode' => $tipe],
        ['is_enabled' => true, 'is_mandatory' => false]
    );
}

\App\Models\ChecklistPeriodeConfig::all();
```

---

## Phase 2: Model Creation/Verification

### Step 2.1: Create/Verify Model
File: `app/Models/ChecklistPeriodeConfig.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistPeriodeConfig extends Model
{
    use HasFactory;

    protected $table = 'checklist_periode_config';

    protected $fillable = [
        'tipe_periode',
        'is_enabled',
        'is_mandatory',
        'keterangan',
        'dibuat_oleh',
        'diubah_oleh',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_mandatory' => 'boolean',
    ];

    public function pembuatRelasi()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function pengubahRelasi()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_periode', $tipe);
    }
}
```

### Step 2.2: Verify Model Import
Pastikan model sudah di-import di controller:
```php
use App\Models\ChecklistPeriodeConfig;
```

---

## Phase 3: Controller Update

### Step 3.1: Update masterIndex() Method
File: `app/Http/Controllers/ManajemenPerawatanController.php`

**Find:**
```php
public function masterIndex()
{
    $masters = MasterPerawatan::with('ruangan')->withCount(['logs' => function($q) {
        $q->whereDate('tanggal_eksekusi', '>=', now()->subDays(30));
    }])->ordered()->get();
    
    return view('perawatan.master.index', compact('masters'));
}
```

**Replace with:**
```php
public function masterIndex()
{
    $masters = MasterPerawatan::with('ruangan')->withCount(['logs' => function($q) {
        $q->whereDate('tanggal_eksekusi', '>=', now()->subDays(30));
    }])->ordered()->get();
    
    // ⭐ NEW: Get periode configs
    $periodeConfigs = [];
    foreach(['harian', 'mingguan', 'bulanan', 'tahunan'] as $tipe) {
        $config = ChecklistPeriodeConfig::byTipe($tipe)->first();
        $periodeConfigs[$tipe] = $config ? $config->is_enabled : true;
    }
    
    return view('perawatan.master.index', compact('masters', 'periodeConfigs'));
}
```

### Step 3.2: Add togglePeriode() Method
**Add after masterDestroy() method:**

```php
public function togglePeriode(Request $request)
{
    // ✅ Validate input
    $validated = $request->validate([
        'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
        'is_enabled' => 'required|boolean'
    ]);

    // ✅ Get or create config
    $config = ChecklistPeriodeConfig::byTipe($validated['tipe_periode'])->first()
        ?? new ChecklistPeriodeConfig(['tipe_periode' => $validated['tipe_periode']]);

    // ✅ Update config
    $config->update([
        'is_enabled' => $validated['is_enabled'],
        'diubah_oleh' => Auth::id()
    ]);

    // ✅ Calculate total checklist
    $totalChecklist = 0;
    if ($validated['is_enabled']) {
        $totalChecklist = MasterPerawatan::where('tipe_periode', $validated['tipe_periode'])
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->count();
    }

    // ✅ Broadcast event (optional, if WebSocket configured)
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

### Step 3.3: Verify Imports
Check top of controller file has:
```php
use App\Models\ChecklistPeriodeConfig;
use Illuminate\Support\Facades\Auth;
```

---

## Phase 4: Route Configuration

### Step 4.1: Add Route
File: `routes/web.php`

**Find:**
```php
// Checklist Periode Config (NEW - Toggle Feature)
Route::prefix('config')->name('config.')->group(function () {
    Route::get('/', 'showConfig')->name('index');
    Route::post('/update', 'updateConfig')->name('update');
    Route::get('/status/{tipe}', 'getStatusChecklist')->name('status');
});
```

**Update to:**
```php
// Checklist Periode Config (NEW - Toggle Feature)
Route::prefix('config')->name('config.')->group(function () {
    Route::get('/', 'showConfig')->name('index');
    Route::post('/update', 'updateConfig')->name('update');
    Route::post('/toggle', 'togglePeriode')->name('toggle');  // ⭐ NEW
    Route::get('/status/{tipe}', 'getStatusChecklist')->name('status');
});
```

### Step 4.2: Verify Route
```bash
php artisan route:list | grep toggle
```

Output should show:
```
POST   /perawatan/config/toggle   ManajemenPerawatanController@togglePeriode
```

---

## Phase 5: Blade Template Update

### Step 5.1: Update Tab Navigation
File: `resources/views/perawatan/master/index.blade.php`

**Find:**
```blade
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="#harian" class="nav-link active" data-bs-toggle="tab" role="tab">
            <svg ...></svg>
            Harian ({{ $masters->where('tipe_periode', 'harian')->count() }})
        </a>
    </li>
    <!-- ... other tabs ... -->
</ul>
```

**Replace with:**
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
            @if($tipePeriode === 'harian')
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /></svg>
            @elseif($tipePeriode === 'mingguan')
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><path d="M7 14h.01" /><path d="M11 14h.01" /></svg>
            @elseif($tipePeriode === 'bulanan')
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><rect x="8" y="15" width="2" height="2" /></svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
            @endif
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

### Step 5.2: Add JavaScript Handler
**Add at end of file, before `@endsection`:**

```blade
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle toggle change
    document.querySelectorAll('.period-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const periode = this.dataset.periode;
            const isEnabled = this.checked;
            
            // Update status badge
            const statusBadge = document.querySelector(`#status-${periode}`);
            if (isEnabled) {
                statusBadge.textContent = '✅ Aktif';
                statusBadge.className = 'badge bg-success';
            } else {
                statusBadge.textContent = '❌ Nonaktif';
                statusBadge.className = 'badge bg-danger';
            }
            
            // Send request to backend
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
                    // Update count
                    document.querySelector(`#count-${periode}`).textContent = data.data.total_checklist;
                    
                    // Show notification
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
                    
                    // Broadcast (if WebSocket available)
                    if (window.Echo !== undefined) {
                        window.Echo.channel('checklist-updates')
                            .whisper('ChecklistToggled', {
                                tipe_periode: periode,
                                is_enabled: isEnabled,
                                total_checklist: data.data.total_checklist
                            });
                    }
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Gagal mengupdate toggle',
                        icon: 'error'
                    });
                    this.checked = !isEnabled;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mengupdate',
                    icon: 'error'
                });
                this.checked = !isEnabled;
            });
        });
    });
    
    // Listen for updates from other users
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

<style>
.nav-tabs .nav-item {
    display: flex;
    align-items: center;
    position: relative;
    flex-wrap: wrap;
}

.nav-tabs .nav-item .form-check {
    margin-left: auto;
}

@media (max-width: 768px) {
    .nav-tabs {
        flex-wrap: wrap;
    }
    
    .nav-tabs .nav-item {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .nav-tabs .nav-item .nav-link {
        flex: 1;
    }
}
</style>
@endsection
```

---

## Phase 6: Testing

### Step 6.1: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Step 6.2: Test Manually
1. Open `/perawatan/master` as super admin
2. Click toggle Harian: OFF → ON
3. Check:
   - [ ] Badge changes to ✅ Aktif (green)
   - [ ] Count updates: (X) items shown
   - [ ] Toast notification appears
   - [ ] Database updated

4. Click toggle Harian: ON → OFF
5. Check:
   - [ ] Badge changes to ❌ Nonaktif (red)
   - [ ] Count updates: (0) items
   - [ ] Toast notification appears
   - [ ] Database updated

### Step 6.3: Verify Database
```bash
php artisan tinker
> ChecklistPeriodeConfig::all();
```

Should show 4 records with correct is_enabled values.

---

## Phase 7: Deployment

### Step 7.1: Git Commit
```bash
git add .
git commit -m "feat: implement toggle checklist feature"
git push origin feature/toggle-checklist
```

### Step 7.2: Code Review
- [ ] All tests pass
- [ ] Code follows PSR-12
- [ ] Comments are clear
- [ ] No debug code left

### Step 7.3: Deploy to Production
```bash
# SSH to server
ssh user@server

# Pull changes
cd /path/to/app
git pull origin feature/toggle-checklist

# Run migrations (if needed)
php artisan migrate --force

# Clear cache
php artisan cache:clear
php artisan view:clear

# Restart queue (if using)
php artisan queue:restart
```

---

## Troubleshooting

### Issue 1: Toggle tidak muncul
```
Solution:
- Clear browser cache (Ctrl+Shift+Delete)
- php artisan view:clear
- Check browser console for JS errors
- Verify ChecklistPeriodeConfig table exists
```

### Issue 2: AJAX request 404
```
Solution:
- Verify route: php artisan route:list | grep toggle
- Check CSRF token in meta tag
- Verify Controller method exists
```

### Issue 3: Toggle not saving
```
Solution:
- Check DB permissions
- Verify foreign key constraints
- Check Laravel logs
- Verify user is authenticated
```

### Issue 4: Real-time sync not working
```
Solution:
- Check WebSocket connection
- Verify Laravel Echo installed
- Check Pusher/WebSocket server status
- Fallback to manual refresh
```

---

**Implementation Complete! ✅**

Now you have:
- ✅ Database table created
- ✅ Model implemented
- ✅ Controller method added
- ✅ Route configured
- ✅ Blade template updated
- ✅ JavaScript handler added
- ✅ Fully tested & ready for production

Happy coding! 🚀
