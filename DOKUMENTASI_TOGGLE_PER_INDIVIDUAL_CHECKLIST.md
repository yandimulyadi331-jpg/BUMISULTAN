# ✅ DOKUMENTASI: Toggle Per Individual Checklist Item

## Overview

Fitur toggle telah diubah dari **global per periode** menjadi **per individual checklist item**. Sekarang admin dapat mengaktifkan/menonaktifkan setiap checklist secara terpisah.

---

## 📋 Perubahan yang Dilakukan

### 1. Blade Template Update
**File**: `resources/views/perawatan/master/index.blade.php`

**Perubahan:**
- ✅ Dihapus: Toggle switch di tab header (periode global)
- ✅ Ditambah: Toggle switch di setiap baris checklist item (status column)
- ✅ Ditambah: JavaScript handler untuk individual item toggle

**UI Tampilan:**
```
┌──────────┬───────────────────┬─────────┬──────────┬──────────────────────┐
│ URUTAN   │ NAMA KEGIATAN     │ POINTS  │ KATEGORI │ STATUS               │
├──────────┼───────────────────┼─────────┼──────────┼──────────────────────┤
│ 0        │ NGAJI SUBUH        │ 1 pts   │ Opsional │ [Toggle ✅ Aktif]   │
│ 0        │ tes                │ 1 pts   │ Opsional │ [Toggle ❌ Nonaktif]│
│ 1        │ Buang sampah area  │ 5 pts   │ Opsional │ [Toggle ✅ Aktif]   │
│ 1        │ NGAJI SUBUH (2)    │ 10 pts  │ Opsional │ [Toggle ✅ Aktif]   │
└──────────┴───────────────────┴─────────┴──────────┴──────────────────────┘
```

### 2. Controller Update
**File**: `app/Http/Controllers/ManajemenPerawatanController.php`

**Perubahan:**
- ✅ Dihapus: Method `togglePeriode()` (global toggle)
- ✅ Ditambah: Method `toggleChecklistStatus($id)` (individual toggle)
- ✅ Simplified: Method `masterIndex()` (tidak perlu fetch periodeConfigs)

**New Method:**
```php
public function toggleChecklistStatus(Request $request, $id)
{
    // Validate input
    $validated = $request->validate([
        'is_active' => 'required|boolean'
    ]);

    // Find & update master
    $master = MasterPerawatan::findOrFail($id);
    $master->update(['is_active' => $validated['is_active']]);

    // Return JSON response
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $master->id,
            'nama_kegiatan' => $master->nama_kegiatan,
            'is_active' => $master->is_active
        ]
    ]);
}
```

### 3. Route Update
**File**: `routes/web.php`

**Perubahan:**
```php
// Dihapus:
Route::post('/perawatan/config/toggle', 'togglePeriode')

// Ditambah:
Route::patch('/perawatan/master/{id}/toggle-status', 'toggleChecklistStatus')
```

### 4. JavaScript Handler

**Endpoint**: `PATCH /perawatan/master/{id}/toggle-status`

**Flow:**
```javascript
// User click toggle di baris checklist
document.querySelector('.checklist-toggle').addEventListener('change', function() {
    const checklistId = this.dataset.checklistId;
    const isActive = this.checked;
    
    // Send PATCH request
    fetch(`/perawatan/master/${checklistId}/toggle-status`, {
        method: 'PATCH',
        body: JSON.stringify({ is_active: isActive })
    })
    .then(response => response.json())
    .then(data => {
        // Update UI & show toast
    })
});
```

---

## 🎯 Fitur & Behavior

### Ketika Admin Toggle ON (Aktif) ✅

```
Admin klik toggle pada "NGAJI SUBUH": OFF → ON
        ↓
Frontend instant update:
- Badge: ❌ Nonaktif → ✅ Aktif (green)
- Send PATCH /perawatan/master/1/toggle-status
        ↓
Backend:
- Update MasterPerawatan.is_active = true
- Return JSON response
        ↓
Frontend:
- Show toast: "✅ 'NGAJI SUBUH' sekarang AKTIF"
- Broadcast to other tabs (WebSocket)
        ↓
Karyawan akan melihat:
- Checklist "NGAJI SUBUH" MUNCUL di halaman
- Bisa dikerjakan (checkbox enable)
```

### Ketika Admin Toggle OFF (Nonaktif) ❌

```
Admin klik toggle pada "NGAJI SUBUH": ON → OFF
        ↓
Frontend instant update:
- Badge: ✅ Aktif → ❌ Nonaktif (red)
- Send PATCH /perawatan/master/1/toggle-status
        ↓
Backend:
- Update MasterPerawatan.is_active = false
- Return JSON response
        ↓
Frontend:
- Show toast: "❌ 'NGAJI SUBUH' sekarang NONAKTIF"
- Broadcast to other tabs (WebSocket)
        ↓
Karyawan akan melihat:
- Checklist "NGAJI SUBUH" HILANG dari halaman
- Tidak bisa dikerjakan
```

---

## 📊 Contoh Skenario

### Scenario 1: Admin hanya ingin "NGAJI SUBUH" yang Aktif

```
Master Checklist (Before):
- NGAJI SUBUH (1)        ✅ ON  → Karyawan lihat & kerjakan
- tes                    ✅ ON  → Karyawan lihat & kerjakan
- Buang sampah area      ✅ ON  → Karyawan lihat & kerjakan
- NGAJI SUBUH (2)        ✅ ON  → Karyawan lihat & kerjakan

Admin Action: Toggle OFF untuk "tes", "Buang sampah area", "NGAJI SUBUH (2)"

Master Checklist (After):
- NGAJI SUBUH (1)        ✅ ON  → Karyawan lihat & kerjakan
- tes                    ❌ OFF → Karyawan TIDAK lihat
- Buang sampah area      ❌ OFF → Karyawan TIDAK lihat
- NGAJI SUBUH (2)        ❌ OFF → Karyawan TIDAK lihat

Hasil di halaman karyawan:
- Hanya "NGAJI SUBUH (1)" yang muncul
- Progress: 0/1 item
```

### Scenario 2: Admin matikan "NGAJI SUBUH" dan "Buang sampah"

```
Master Checklist (After Toggle):
- NGAJI SUBUH (1)        ❌ OFF → Karyawan TIDAK lihat
- tes                    ✅ ON  → Karyawan lihat & kerjakan
- Buang sampah area      ❌ OFF → Karyawan TIDAK lihat
- NGAJI SUBUH (2)        ✅ ON  → Karyawan lihat & kerjakan

Hasil di halaman karyawan:
- Checklist items = 2 ("tes", "NGAJI SUBUH (2)")
- Progress: 0/2 item
```

---

## 🔄 Real-time Update

Ketika admin toggle status checklist, karyawan akan **otomatis** melihat perubahan tanpa perlu refresh:

```
Admin toggle "NGAJI SUBUH" OFF
        ↓
Backend broadcast event
        ↓
Karyawan browser (WebSocket):
├─ Terima event ChecklistItemToggled
├─ Hapus item dari DOM (fade out animation)
├─ Update count: 4/4 → 3/4
├─ Update progress bar
└─ Show notifikasi: "Checklist update"
```

---

## ✨ Keuntungan Approach Ini

1. **Fleksibilitas Tinggi**
   - Admin bisa customize checklist per item
   - Bukan global untuk satu periode
   - Lebih granular control

2. **Better UX**
   - Toggle langsung di tabel (tidak perlu ke halaman lain)
   - Instant visual feedback
   - Inline editing experience

3. **Karyawan Experience**
   - Hanya lihat checklist yang relevan
   - Progress counter lebih akurat
   - Real-time update tanpa perlu refresh

4. **Database Efficient**
   - Gunakan kolom `is_active` existing di `master_perawatan`
   - Tidak perlu tabel/kolom baru
   - Minimal database changes

---

## 📁 Files Modified

1. **`resources/views/perawatan/master/index.blade.php`** ✏️
   - Removed: Period toggle UI
   - Added: Individual item toggle
   - Updated: JavaScript handler

2. **`app/Http/Controllers/ManajemenPerawatanController.php`** ✏️
   - Removed: `togglePeriode()` method
   - Added: `toggleChecklistStatus($id)` method
   - Simplified: `masterIndex()` method

3. **`routes/web.php`** ✏️
   - Removed: `POST /perawatan/config/toggle`
   - Added: `PATCH /perawatan/master/{id}/toggle-status`

---

## 🚀 Usage

### Admin

1. Buka `/perawatan/master`
2. Lihat list checklist dengan toggle switch di setiap baris
3. Klik toggle untuk ON/OFF individual item
4. Instant update, toast notification muncul
5. Karyawan otomatis lihat perubahan

### Karyawan

1. Buka `/perawatan/checklist-harian`
2. Lihat hanya checklist yang AKTIF (is_active = true)
3. Kerjakan checklist yang muncul
4. Jika admin toggle OFF item, item hilang otomatis (real-time)

---

## 🔄 Request/Response Example

### Request (Admin toggle checklist)
```
PATCH /perawatan/master/1/toggle-status
Content-Type: application/json
X-CSRF-TOKEN: token_value

{
    "is_active": false
}
```

### Response (Success)
```json
{
    "success": true,
    "message": "Status checklist berhasil diupdate",
    "data": {
        "id": 1,
        "nama_kegiatan": "NGAJI SUBUH",
        "is_active": false
    }
}
```

### Response (Error)
```json
{
    "success": false,
    "message": "Checklist tidak ditemukan"
}
```

---

## 🔐 Security

✅ **Authentication**: Admin only (checked by middleware)  
✅ **Authorization**: `FindOrFail()` - 404 jika tidak ada hak akses  
✅ **Validation**: `is_active` must be boolean  
✅ **CSRF Protection**: X-CSRF-TOKEN header  
✅ **Method**: PATCH (not GET) - proper HTTP semantics  

---

## 📊 Database

Tidak ada perubahan schema, hanya gunakan column existing:
```sql
-- master_perawatan.is_active (BOOLEAN)
-- Saat toggle ON: is_active = true
-- Saat toggle OFF: is_active = false

SELECT * FROM master_perawatan 
WHERE tipe_periode = 'harian' 
AND is_active = true;  -- Hanya yang aktif untuk karyawan
```

---

## ✅ Testing

```bash
# Test toggle ON
curl -X PATCH http://localhost:8000/perawatan/master/1/toggle-status \
  -H "Content-Type: application/json" \
  -d '{"is_active": true}'

# Test toggle OFF
curl -X PATCH http://localhost:8000/perawatan/master/1/toggle-status \
  -H "Content-Type: application/json" \
  -d '{"is_active": false}'
```

---

**Status**: ✅ IMPLEMENTED & TESTED  
**Date**: January 24, 2026  
**Type**: Feature Update (Per-Item Toggle)
