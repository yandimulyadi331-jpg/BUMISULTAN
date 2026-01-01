# VISUAL GUIDE: Logika Validasi Ijin Dinas

## 📊 DIAGRAM OVERLAP DETECTION

### ✅ LOGIKA YANG BENAR

```
Formula: (dari_baru <= sampai_lama) AND (sampai_baru >= dari_lama)
```

### 🎯 CONTOH VISUAL

```
Legenda:
[====] = Range existing (sudah ada di database)
{----} = Range input baru
  ✅   = Non-overlap (BERHASIL)
  ❌   = Overlap (DITOLAK)
```

---

## CASE 1: Overlap di Awal ❌

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:        [============]
                 3            7

Input:     {==========}
           1          5

Result: ❌ OVERLAP TERDETEKSI
Reason: Input mulai sebelum existing selesai
```

---

## CASE 2: Overlap di Akhir ❌

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:  [============]
           2            6

Input:                 {==========}
                       5          9

Result: ❌ OVERLAP TERDETEKSI
Reason: Input selesai setelah existing mulai
```

---

## CASE 3: Input di Dalam Range Existing ❌

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:  [========================]
           2                        9

Input:            {========}
                  4        6

Result: ❌ OVERLAP TERDETEKSI
Reason: Input sepenuhnya di dalam range existing
```

---

## CASE 4: Existing di Dalam Range Input ❌

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:        [========]
                 4        6

Input:     {========================}
           2                        9

Result: ❌ OVERLAP TERDETEKSI
Reason: Existing sepenuhnya di dalam range input
```

---

## CASE 5: Tepat Bersentuhan (Edge Case) ❌

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:  [========]
           2        5

Input:              [========]
                    5        8

Result: ❌ OVERLAP TERDETEKSI
Reason: Tanggal 5 dipakai di kedua range (overlap 1 hari)
```

---

## CASE 6: Tidak Overlap (Sebelum) ✅

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:                    [========]
                             6        9

Input:     {========}
           1        3

Result: ✅ NON-OVERLAP
Reason: Input selesai sebelum existing mulai
```

---

## CASE 7: Tidak Overlap (Sesudah) ✅

```
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|

Existing:  [========]
           2        5

Input:                        {========}
                              7        10

Result: ✅ NON-OVERLAP
Reason: Input mulai setelah existing selesai
```

---

## 🔍 DETAIL VALIDASI PER KARYAWAN

### Skenario Multi-Karyawan

```
KARYAWAN A:
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|
Range:           [============]
                 3            6

KARYAWAN B:
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|
Range:           [============]
                 3            6

KARYAWAN C:
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|
Range:           [============]
                 3            6

Result: ✅ SEMUA BERHASIL
Reason: Karyawan BERBEDA, validasi per karyawan
```

### Skenario Duplikasi Karyawan yang Sama

```
KARYAWAN A - REQUEST #1:
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|
Range:           [============]
                 3            6
Status: ✅ BERHASIL (First request)

KARYAWAN A - REQUEST #2:
Timeline: |--1--|--2--|--3--|--4--|--5--|--6--|--7--|--8--|--9--|--10--|
Existing:        [============]
                 3            6
Input:                {============}
                      5            8
Status: ❌ DITOLAK (Overlap detected)
```

---

## 📋 TABEL KEPUTUSAN

| dari_input | sampai_input | dari_existing | sampai_existing | Overlap? | Result |
|------------|--------------|---------------|-----------------|----------|---------|
| 1 | 3 | 6 | 9 | ❌ No | ✅ Berhasil |
| 1 | 5 | 3 | 7 | ✅ Yes | ❌ Ditolak |
| 3 | 7 | 1 | 5 | ✅ Yes | ❌ Ditolak |
| 4 | 6 | 2 | 9 | ✅ Yes | ❌ Ditolak |
| 2 | 9 | 4 | 6 | ✅ Yes | ❌ Ditolak |
| 2 | 5 | 5 | 8 | ✅ Yes | ❌ Ditolak |
| 7 | 10 | 2 | 5 | ❌ No | ✅ Berhasil |

---

## 💡 FORMULA BREAKDOWN

### Step-by-Step Validation:

```php
// Input baru
$dari_baru = $request->dari;      // Contoh: 2026-01-03
$sampai_baru = $request->sampai;  // Contoh: 2026-01-07

// Existing di database (untuk karyawan yang sama)
$dari_lama = $existing->dari;      // Contoh: 2026-01-05
$sampai_lama = $existing->sampai;  // Contoh: 2026-01-09

// Check overlap
$overlap = ($dari_baru <= $sampai_lama) && ($sampai_baru >= $dari_lama);

if ($overlap) {
    // ❌ DITOLAK
    return "Anda Sudah Mengajukan Ijin Dinas Pada Rentang Tanggal Tersebut!";
} else {
    // ✅ BERHASIL
    // Lanjutkan proses insert
}
```

### Truth Table:

| Condition 1 | Condition 2 | Result |
|-------------|-------------|---------|
| dari_baru <= sampai_lama | sampai_baru >= dari_lama | OVERLAP |
| TRUE | TRUE | ✅ YES (Ditolak) |
| TRUE | FALSE | ❌ NO (Berhasil) |
| FALSE | TRUE | ❌ NO (Berhasil) |
| FALSE | FALSE | ❌ NO (Berhasil) |

---

## 🎯 IMPLEMENTASI QUERY

### Query Laravel:

```php
$cek_izin_dinas = Izindinas::where('nik', $nik)
    ->where(function($query) use ($request) {
        $query->where('dari', '<=', $request->sampai)
              ->where('sampai', '>=', $request->dari);
    })
    ->first();
```

### Generated SQL:

```sql
SELECT * FROM presensi_izindinas
WHERE nik = 'K001'
  AND (
    dari <= '2026-01-07'
    AND sampai >= '2026-01-03'
  )
LIMIT 1;
```

### SQL Explanation:

```
1. Filter by NIK → Only check same employee
2. Check overlap:
   - dari <= sampai_input (existing starts before input ends)
   - sampai >= dari_input (existing ends after input starts)
3. If found (not null) → Overlap detected → Reject
4. If not found (null) → No overlap → Accept
```

---

## 📊 FLOWCHART

```
START
  ↓
Input: NIK, dari, sampai
  ↓
Validasi 3 hari?
  ↓
YES → Continue | NO → Error "Tidak Boleh Lebih dari 3 Hari"
  ↓
Query: Cek overlap untuk NIK ini
  ↓
Found existing with overlap?
  ↓
YES → Error "Sudah Ada Ijin" | NO → Continue
  ↓
Generate kode_ijin_dinas
  ↓
Insert to database
  ↓
SUCCESS
  ↓
END
```

---

## 🧪 TESTING MATRIX

| Test ID | Input | Expected Overlap | Expected Result |
|---------|-------|------------------|-----------------|
| T1 | dari=1, sampai=3, existing=6-9 | NO | ✅ Pass |
| T2 | dari=1, sampai=5, existing=3-7 | YES | ❌ Reject |
| T3 | dari=3, sampai=7, existing=1-5 | YES | ❌ Reject |
| T4 | dari=4, sampai=6, existing=2-9 | YES | ❌ Reject |
| T5 | dari=2, sampai=9, existing=4-6 | YES | ❌ Reject |
| T6 | dari=7, sampai=10, existing=2-5 | NO | ✅ Pass |
| T7 | dari=3, sampai=6, existing=3-6 | YES | ❌ Reject |

---

## 🎓 KEY TAKEAWAYS

1. **Overlap Formula:**
   ```
   (dari_baru <= sampai_lama) AND (sampai_baru >= dari_lama)
   ```

2. **Validation Scope:**
   - ✅ Check per NIK (same employee)
   - ✅ Detect ALL overlap cases
   - ✅ Allow multiple employees on same dates

3. **Edge Cases Handled:**
   - ✅ Partial overlap (start)
   - ✅ Partial overlap (end)
   - ✅ New inside existing
   - ✅ Existing inside new
   - ✅ Exact match

4. **What's NOT Validated:**
   - ❌ Different employees (allowed)
   - ❌ Non-overlapping dates (allowed)

---

**Reference:**
- [ANALISA_IJIN_DINAS_MULTIPLE_KARYAWAN.md](ANALISA_IJIN_DINAS_MULTIPLE_KARYAWAN.md)
- [QUICK_FIX_IJIN_DINAS_MULTIPLE.md](QUICK_FIX_IJIN_DINAS_MULTIPLE.md)
- [IMPLEMENTASI_SUMMARY_IJIN_DINAS_MULTIPLE.md](IMPLEMENTASI_SUMMARY_IJIN_DINAS_MULTIPLE.md)
