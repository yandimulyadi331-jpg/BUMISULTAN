# 📋 ANALISA: Sistem Checklist Real-Time Sesuai Jadwal Piket

**Status:** Analisis Komprehensif  
**Tanggal:** 22 Januari 2026  
**Requester:** Admin  
**Tujuan:** Membuat sistem checklist terstruktur dan terarah berdasarkan jadwal piket karyawan

---

## 🎯 PERINTAH ANDA - BREAKDOWN DETAIL

### **Kebutuhan Utama:**
Sistem checklist harus **real-time sesuai jam jadwal piket karyawan**, sehingga:

#### 1. **Visibilitas Checklist Terbatas**
```
CONTOH:
├─ Karyawan: Doni (NIK: 123456)
├─ Jadwal Piket: NON SHIFT (08:00 - 17:00)
│
├─ YANG TAMPIL:
│  ✅ Checklist 08:00 - "Bersihkan Ruang Kerja"
│  ✅ Checklist 12:00 - "Cek Keamanan"
│  ✅ Checklist 14:00 - "Buang Sampah"
│  ✅ Checklist 17:00 - "Absen Pulang"
│
└─ YANG TIDAK TAMPIL:
   ❌ Checklist 18:00 - "Monitor Malam" (hidden, bukan shift Doni)
   ❌ Checklist 21:00 - "Kunci Gudang" (hidden, bukan shift Doni)
```

#### 2. **Reset Checklist Per Jadwal Shift**
```
Jam 08:00 → Mulai Shift Doni
  ↓
  Reset checklist (jika ada dari shift sebelumnya)
  ↓
  Tampilkan checklist 08:00 - 17:00 SAJA
  
Jam 17:00 → Akhir Shift Doni
  ↓
  Checklist 17:00 menjadi "locked" (tidak bisa edit)
  ↓
  Checklist 18:00+ tidak boleh dikerjakan Doni
  
Jam 20:00 → Mulai Shift Lain (e.g., SHIFT 2)
  ↓
  Karyawan SHIFT 2 mulai lihat checklist 20:00 - 08:00
```

#### 3. **Logika Absen Pulang**
```
SKENARIO A - Checklist Selesai:
  Doni (NON SHIFT 08:00-17:00)
  ├─ Jam 15:00: Selesaikan semua checklist jam 08:00-17:00
  ├─ Jam 15:30: Klik "Absen Pulang"
  └─ ✅ BERHASIL - Bypass checklist requirement, tidak perlu tunggu jam 17:00

SKENARIO B - Checklist Belum Selesai:
  Doni
  ├─ Jam 16:00: Belum selesaikan checklist
  ├─ Jam 16:30: Klik "Absen Pulang"
  └─ ⚠️ NOTIFIKASI: "Ada 3 checklist yang belum selesai"
     ├─ Tombol "Selesaikan" → Redirect ke halaman checklist
     └─ Tombol "Pulang" → Force pulang (bypass checklist)

SKENARIO C - Di Luar Jadwal Piket:
  Doni (NON SHIFT 08:00-17:00)
  ├─ Jam 18:00: Coba buka checklist
  └─ ❌ BLOCKED - "Checklist hanya tersedia jam 08:00-17:00"
```

#### 4. **Pencegahan Penyalahgunaan**
```
GOAL: Tidak ada karyawan yang bisa mengerjakan checklist di luar jadwal mereka

MASALAH YANG DICEGAH:
  ❌ Karyawan NON SHIFT mengerjakan checklist SHIFT 2 (18:00-21:00)
  ❌ Karyawan SHIFT 1 mengerjakan checklist NON SHIFT (08:00-17:00)
  ❌ Checklist lama masih muncul di hari/shift berikutnya
  ❌ Karyawan absen pulang saat masih ada checklist harinya

SOLUSI:
  ✅ Validasi kode_jam_kerja saat load checklist
  ✅ Kode_jam_kerja disimpan di perawatan_log
  ✅ Timestamp validasi jam mulai-selesai shift
  ✅ API response berisi time-boundary checking
```

---

## 🏗️ ARSITEKTUR SAAT INI vs YANG DIBUTUHKAN

### **Status Implementasi Saat Ini:**

#### ✅ SUDAH IMPLEMENT:
1. **Database Schema**
   - `master_perawatan.kode_jam_kerja` (nullable, FK to presensi_jamkerja)
   - `perawatan_log.kode_jam_kerja` (untuk audit trail)
   - `jadwal_piket_karyawans` (relasi jam kerja per karyawan)

2. **Filter Checklist Berdasarkan Jam Kerja**
   ```php
   // ChecklistController.php
   $masterChecklists = MasterPerawatan::active()
       ->byTipe('harian')
       ->where(function ($query) use ($kodeJamKerja) {
           $query->whereNull('kode_jam_kerja')  // Untuk semua shift
               ->orWhere('kode_jam_kerja', $kodeJamKerja); // Shift spesifik
       })
       ->ordered()
       ->get();
   ```

3. **UI Form Create/Edit**
   - Dropdown pilih jam kerja di perawatan.master.create
   - Label "Jadwal Piket (Jam Kerja)"

4. **Force Pulang Feature**
   - Endpoint `POST /api/checklist/force-pulang`
   - Modal dengan tombol "Selesaikan" | "Pulang"

#### ⚠️ YANG MASIH PERLU DIPERKUAT:

1. **Time-Window Validation**
   - ❌ Tidak ada validasi jam masuk-pulang saat checklist dikerjakan
   - ❌ API tidak check apakah karyawan dalam rentang jam kerja saat ini
   - ❌ Karyawan bisa akses checklist meski sudah diluar jam kerjanya

2. **Reset Checklist Per Shift**
   - ❌ Tidak ada mekanisme "reset" otomatis saat shift berganti
   - ❌ Tidak ada "cleanup" checklist lama dari shift sebelumnya
   - ❌ periode_key hanya berdasarkan DATE, tidak mempertimbangkan SHIFT

3. **Validasi Timestamp Akurat**
   - ❌ API tidak check jam_masuk-jam_pulang dari presensi
   - ❌ Tidak ada pengecekan apakah ini di dalam window waktu shift
   - ❌ Timezone handling tidak jelas

4. **UX/UI Improvements**
   - ⚠️ Modal notifikasi "force pulang" perlu penjelasan lebih detail
   - ⚠️ Tidak ada visual indicator shift mana yang aktif sekarang
   - ⚠️ Tidak ada warning saat user mencoba akses checklist diluar jam

5. **Audit & Monitoring**
   - ❌ Tidak ada log untuk "invalid attempts" (akses checklist diluar jam)
   - ❌ Tidak ada dashboard untuk monitoring compliance
   - ❌ KPI Points tidak ter-reset jika checklist dilakukan diluar jam

---

## 🔧 IMPLEMENTASI YANG DIREKOMENDASIKAN

### **Phase 1: Core Logic Enhancement** (Priority: HIGH)

#### 1.1 Update ChecklistController - Add Time-Window Validation
```php
public function checkStatus(Request $request)
{
    $user = Auth::user();
    $date = $request->input('date', now()->format('Y-m-d'));
    $userkaryawan = $user->userkaryawan;
    
    if (!$userkaryawan) {
        return response()->json(['hasIncompleteChecklist' => false]);
    }

    $nik = $userkaryawan->nik;
    
    // Get today's presensi
    $presensiToday = Presensi::where('nik', $nik)
        ->where('tanggal', $date)
        ->first();

    if (!$presensiToday) {
        return response()->json(['hasIncompleteChecklist' => false]);
    }

    // ⭐ NEW: Check time-window validation
    $now = now()->format('H:i:00');
    $jamMasuk = $presensiToday->jam_in ?? '00:00:00';
    $jamKeluar = $presensiToday->jam_out;
    
    // Jika sudah absen pulang, tidak perlu cek checklist
    if ($jamKeluar) {
        return response()->json(['hasIncompleteChecklist' => false]);
    }

    // ⭐ NEW: Check apakah dalam jam kerja yang valid
    if (!$this->isInWorkHours($now, $jamMasuk, $presensiToday->jam_pulang_expected)) {
        return response()->json([
            'hasIncompleteChecklist' => false,
            'isOutsideWorkHours' => true,
            'message' => 'Checklist hanya tersedia dalam jam kerja Anda'
        ]);
    }

    $kodeJamKerja = $presensiToday->kode_jam_kerja;
    
    // Get master checklist with time validation
    $masterChecklists = MasterPerawatan::active()
        ->byTipe('harian')
        ->where(function ($query) use ($kodeJamKerja) {
            $query->whereNull('kode_jam_kerja')
                ->orWhere('kode_jam_kerja', $kodeJamKerja);
        })
        ->ordered()
        ->get();

    // ... rest of logic
}

// ⭐ NEW Helper Method
private function isInWorkHours($now, $jamMasuk, $jamPulangExpected)
{
    // Convert to comparable format
    $nowTime = strtotime($now);
    $masukTime = strtotime($jamMasuk);
    $pulangTime = strtotime($jamPulangExpected);

    // Handle midnight crossing (e.g., SHIFT 2: 20:00 - 08:00)
    if ($pulangTime < $masukTime) {
        // Midnight shift
        return $nowTime >= $masukTime || $nowTime <= $pulangTime;
    }
    
    return $nowTime >= $masukTime && $nowTime <= $pulangTime;
}
```

#### 1.2 Create Checklist Period Service (New File)
```php
// app/Services/ChecklistPeriodService.php
namespace App\Services;

use Carbon\Carbon;

class ChecklistPeriodService
{
    /**
     * Generate periode key yang unik per shift
     * Format: "harian_{date}_{kode_jam_kerja}"
     * 
     * Contoh:
     * - Karyawan NON SHIFT jam 8-17: "harian_2026-01-22_NONS"
     * - Karyawan SHIFT 2 jam 20-08: "harian_2026-01-22_SFT2"
     */
    public function generatePeriodeKey($date, $kodeJamKerja, $tipe = 'harian')
    {
        if ($tipe === 'harian') {
            // Untuk shift yang cross-midnight, gunakan tanggal start shift
            return "harian_{$date}_{$kodeJamKerja}";
        }
        
        return "{$tipe}_{$date}";
    }

    /**
     * Check apakah periode shift sudah berakhir
     * Return: boolean - true jika shift sudah selesai
     */
    public function isShiftEnded($kodeJamKerja, $jamPulang)
    {
        $now = now();
        $jamPulangTime = Carbon::createFromFormat('H:i:s', $jamPulang);
        
        return $now->greaterThan($jamPulangTime);
    }

    /**
     * Get periode key untuk reset checklist
     * Digunakan untuk cleanup checklist lama
     */
    public function getExpiredPeriodes($days = 7)
    {
        $expiredDate = now()->subDays($days)->format('Y-m-d');
        // Return periode keys yang expired
    }
}
```

#### 1.3 Add Migration untuk Detail Timestamp
```sql
-- database/migrations/2026_01_22_add_timestamp_validation_perawatan_log.php

ALTER TABLE `perawatan_log` ADD COLUMN `jam_mulai_valid` TIME NULLABLE;
ALTER TABLE `perawatan_log` ADD COLUMN `jam_selesai_valid` TIME NULLABLE;
ALTER TABLE `perawatan_log` ADD COLUMN `outside_work_hours` TINYINT DEFAULT 0;

-- Untuk audit: track jika checklist dikerjakan diluar jam kerja
ALTER TABLE `perawatan_log` ADD COLUMN `timestamp_validation_error` VARCHAR(255) NULLABLE;
```

---

### **Phase 2: UI/UX Enhancements** (Priority: MEDIUM)

#### 2.1 Add Shift Time Display di Dashboard
```blade
<!-- resources/views/dashboard/karyawan.blade.php -->
<div class="shift-info-card">
    <div class="shift-badge">
        <span class="shift-name">{{ auth()->user()->userkaryawan->jamKerjaSaat->nama_jam_kerja ?? 'NO SHIFT' }}</span>
        <span class="shift-time">
            {{ auth()->user()->userkaryawan->jamKerjaSaat->jam_masuk ?? '—' }} 
            - 
            {{ auth()->user()->userkaryawan->jamKerjaSaat->jam_pulang ?? '—' }}
        </span>
    </div>
    <div class="time-remaining">
        Sisa waktu shift: <strong id="timeRemaining">— menit</strong>
    </div>
</div>
```

#### 2.2 Update Modal Notifikasi Checklist
```blade
<!-- More descriptive modal -->
<div class="modal-body">
    <p><strong>Jam Kerja Anda:</strong> {{ $shiftTime }}</p>
    <p><strong>Checklist yang harus diselesaikan:</strong></p>
    <ul>
        @foreach($incompleteChecklists as $item)
            <li>
                {{ $item->nama_kegiatan }}
                <span class="badge">Jam: {{ $item->jam_mulai ?? 'Fleksibel' }}</span>
            </li>
        @endforeach
    </ul>
    <p class="warning">
        ⚠️ Setelah jam {{ $jamPulang }}, Anda tidak bisa lagi mengerjakan checklist hari ini.
    </p>
</div>
```

#### 2.3 Add Real-time Countdown Timer
```javascript
// resources/js/checklist-timer.js

function initChecklistTimer() {
    const shiftEndTime = window.shiftEndTime; // e.g., "17:00"
    
    setInterval(() => {
        const now = moment();
        const endTime = moment(shiftEndTime, 'HH:mm');
        const duration = moment.duration(endTime.diff(now));
        
        if (duration.asSeconds() <= 0) {
            // Shift sudah berakhir
            disableAllChecklists();
            showWarning('Shift Anda telah berakhir');
        } else if (duration.asMinutes() <= 30) {
            // 30 menit sebelum akhir shift
            highlightChecklistUrgent();
        }
        
        document.getElementById('timeRemaining').textContent = 
            Math.floor(duration.asMinutes()) + ' menit';
    }, 60000); // Update setiap menit
}
```

---

### **Phase 3: Advanced Features** (Priority: LOW)

#### 3.1 Compliance Monitoring Dashboard (Untuk Admin)
```
Path: /admin/checklist/compliance
Tampilkan:
├─ Per Shift Performance
│  ├─ NON SHIFT: 92% checklist on-time
│  ├─ SHIFT 1: 85% checklist on-time
│  └─ SHIFT 2: 78% checklist on-time (perlu improvement)
│
├─ Karyawan dengan Violation
│  ├─ Doni: Checklist diluar jam kerja (3x bulan ini)
│  └─ Rina: Force pulang tanpa selesai checklist (5x bulan ini)
│
└─ KPI Impact
   └─ Checklist done in-time vs out-of-time points calculation
```

#### 3.2 Auto-Reset Mechanism
```php
// Add Command/Job untuk auto-cleanup
php artisan schedule:add

// app/Console/Commands/ResetChecklistOutsideWorkHours.php
- Jalankan setiap jam
- Find: perawatan_log yang dibuat diluar jam kerja
- Action: Mark as "invalid" dan reset user's KPI points
```

---

## 📊 COMPARISON: SEBELUM vs SESUDAH

| Aspek | Sebelumnya | Sesudah Implementasi |
|-------|-----------|---------------------|
| **Filter Shift** | Cukup ada logic di controller | Validated di setiap layer (API, controller, job) |
| **Time Validation** | Hanya check jam_in, tidak check shift window | Validated jam masuk-pulang per shift |
| **Periode Key** | `harian_{date}` | `harian_{date}_{kodeJamKerja}` |
| **Reset Checklist** | Manual | Auto-reset saat shift berakhir |
| **Abuse Prevention** | Limited | Strong - validated di API, controller, & DB |
| **UX Clarity** | Modal basic | Shift info + countdown timer + detailed warnings |
| **Audit Trail** | Minimal | Complete - track jika diluar jam |
| **KPI Accuracy** | Tidak ada penalty untuk out-of-time | Points berbeda untuk in-time vs out-of-time |

---

## 🎯 IMPLEMENTATION CHECKLIST

### **Phase 1 (Priority: HIGH)**
- [ ] Add time-window validation di ChecklistController::checkStatus()
- [ ] Create ChecklistPeriodService
- [ ] Update periode_key generation
- [ ] Add migration untuk timestamp validation fields
- [ ] Update PerawatanLog model dengan new timestamps

### **Phase 2 (Priority: MEDIUM)**
- [ ] Add shift-time display di dashboard
- [ ] Update modal notifikasi dengan shift info
- [ ] Implement real-time countdown timer
- [ ] Add warning saat mendekati end-of-shift

### **Phase 3 (Priority: LOW)**
- [ ] Create compliance monitoring dashboard
- [ ] Implement auto-reset command
- [ ] Add violation tracking & alerting
- [ ] KPI points calculation refinement

---

## 🚀 NEXT STEPS

Setelah analisis ini disetujui, kami akan:

1. **Update Database Schema** (Migration files)
2. **Enhance ChecklistController** (Time-window logic)
3. **Create ChecklistPeriodService** (Periode management)
4. **Update Frontend** (Shift display + timer)
5. **Add Audit Logging** (Track violations)
6. **Testing & QA** (Scenario testing)
7. **Deployment** (Production rollout)

---

## 📝 CATATAN TAMBAHAN

### **Edge Cases yang Perlu Dihandle:**

1. **Shift Berganti di Tengah Malam**
   - SHIFT 2: 20:00 - 08:00 (cross-midnight)
   - Bagaimana handle tanggal? Gunakan start-of-shift date

2. **Karyawan Lembur**
   - Jika normal 08:00-17:00 tapi lembur sampai 20:00
   - Apakah checklist SHIFT 1 tetap berlaku?
   - **Rekomendasi:** Check extended hours di lembur table

3. **Karyawan Cuti/Libur**
   - Jika tidak ada presensi, tidak perlu tampilkan checklist
   - **Handled:** Existing logic check presensi first

4. **Maintenance Window**
   - Bagaimana jika sistem down pada jam shift tertentu?
   - **Rekomendasi:** Add grace period (e.g., +1 jam after shift end)

---

**Status Analisis:** ✅ COMPLETE  
**Siap untuk:** Diskusi & Approval  
**Waktu Estimasi Implementasi:** 3-4 hari (1 dev)
