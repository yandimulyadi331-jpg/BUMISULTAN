# DOKUMENTASI FILTER ADVANCED - DANA OPERASIONAL

## 📋 Overview
Sistem filter lanjutan untuk laporan Dana Operasional yang memungkinkan pengguna memfilter transaksi berdasarkan periode waktu yang fleksibel.

---

## 🎯 Fitur Utama

### 1. **4 Tipe Filter**
- ✅ **Per Bulan** - Filter transaksi dalam 1 bulan tertentu
- ✅ **Per Tahun** - Filter transaksi dalam 1 tahun penuh
- ✅ **Per Minggu** - Filter transaksi dalam 1 minggu (Senin-Minggu)
- ✅ **Range Tanggal** - Filter transaksi antara 2 tanggal custom

### 2. **Dynamic Input Fields**
Input fields otomatis muncul/hilang sesuai tipe filter yang dipilih:
- Bulan → Month picker
- Tahun → Number input (2020-2099)
- Minggu → Week picker
- Range → 2 date pickers (Start & End)

### 3. **Periode Label**
Tampilan header card menunjukkan periode yang sedang aktif:
- "November 2025" (bulan)
- "Tahun 2025" (tahun)
- "Minggu 10 Nov - 16 Nov 2025" (minggu)
- "01 Nov 2025 - 03 Nov 2025" (range)

### 4. **PDF Export dengan Filter**
Button PDF di filter section untuk download laporan sesuai filter aktif.

---

## 🔧 Technical Implementation

### **1. Controller: DanaOperasionalController.php**

#### Method: `index(Request $request)`
```php
public function index(Request $request)
{
    $filterType = $request->get('filter_type', 'bulan');
    
    // Calculate date range based on filter type
    switch ($filterType) {
        case 'tahun':
            $tahun = $request->get('tahun', date('Y'));
            $tanggalAwal = Carbon::create($tahun, 1, 1)->startOfYear();
            $tanggalAkhir = Carbon::create($tahun, 12, 31)->endOfYear();
            $periodeLabel = "Tahun $tahun";
            break;
            
        case 'minggu':
            if ($request->has('minggu')) {
                list($tahun, $minggu) = explode('-W', $request->minggu);
                $tanggalAwal = Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
                $tanggalAkhir = Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();
            } else {
                $tanggalAwal = Carbon::now()->startOfWeek();
                $tanggalAkhir = Carbon::now()->endOfWeek();
            }
            $periodeLabel = "Minggu " . $tanggalAwal->format('d M') . " - " . $tanggalAkhir->format('d M Y');
            break;
            
        case 'range':
            if ($request->has('start_date') && $request->has('end_date')) {
                $tanggalAwal = Carbon::parse($request->start_date)->startOfDay();
                $tanggalAkhir = Carbon::parse($request->end_date)->endOfDay();
            } else {
                $tanggalAwal = Carbon::now()->startOfMonth();
                $tanggalAkhir = Carbon::now()->endOfMonth();
            }
            $periodeLabel = $tanggalAwal->format('d M Y') . " - " . $tanggalAkhir->format('d M Y');
            break;
            
        default: // 'bulan'
            $bulan = $request->get('bulan', date('Y-m'));
            $tanggalAwal = Carbon::parse($bulan . '-01')->startOfMonth();
            $tanggalAkhir = Carbon::parse($bulan . '-01')->endOfMonth();
            $periodeLabel = $tanggalAwal->locale('id')->isoFormat('MMMM YYYY');
            break;
    }
    
    // Get data with date range filter
    $riwayatSaldo = SaldoHarianOperasional::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
        ->orderBy('tanggal', 'asc')
        ->get();
    
    $realisasiPerTanggal = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
        ->orderBy('tanggal_realisasi', 'asc')
        ->get()
        ->groupBy(function($item) {
            return $item->tanggal_realisasi->format('Y-m-d');
        });

    return view('dana-operasional.index', compact(
        'riwayatSaldo',
        'realisasiPerTanggal',
        'tanggalAwal',
        'tanggalAkhir',
        'filterType',
        'periodeLabel'
    ));
}
```

#### Method: `exportPdf(Request $request)`
```php
public function exportPdf(Request $request)
{
    // Sama persis dengan logic di index()
    // Calculate tanggalDari & tanggalSampai based on filter_type
    
    // ... (filter logic sama seperti index)
    
    $data = [
        'transaksi_detail' => $transaksiDetail,
        'periode_label' => $periodeLabel,
        // ... other data
    ];
    
    $pdf = PDF::loadView('dana-operasional.pdf-simple', $data);
    return $pdf->download('Laporan_Dana_Operasional_' . $periodeLabel . '.pdf');
}
```

---

### **2. View: index.blade.php**

#### Filter Section
```blade
<div class="card-body border-bottom">
    <form method="GET" class="row g-3" id="formFilter">
        <!-- Dropdown Tipe Filter -->
        <div class="col-md-2">
            <label class="form-label">Tipe Filter</label>
            <select class="form-select" name="filter_type" id="filterType" onchange="toggleFilterInputs()">
                <option value="bulan" {{ request('filter_type', 'bulan') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                <option value="tahun" {{ request('filter_type') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                <option value="minggu" {{ request('filter_type') == 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                <option value="range" {{ request('filter_type') == 'range' ? 'selected' : '' }}>Range Tanggal</option>
            </select>
        </div>
        
        <!-- Input Bulan (hidden by default) -->
        <div class="col-md-2" id="inputBulan" style="display: none;">
            <label class="form-label">Bulan</label>
            <input type="month" class="form-control" name="bulan" value="{{ request('bulan', date('Y-m')) }}">
        </div>
        
        <!-- Input Tahun (hidden by default) -->
        <div class="col-md-2" id="inputTahun" style="display: none;">
            <label class="form-label">Tahun</label>
            <input type="number" class="form-control" name="tahun" value="{{ request('tahun', date('Y')) }}" min="2020" max="2099">
        </div>
        
        <!-- Input Minggu (hidden by default) -->
        <div class="col-md-2" id="inputMinggu" style="display: none;">
            <label class="form-label">Minggu</label>
            <input type="week" class="form-control" name="minggu" value="{{ request('minggu') }}">
        </div>
        
        <!-- Input Range Start (hidden by default) -->
        <div class="col-md-2" id="inputRangeStart" style="display: none;">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
        </div>
        
        <!-- Input Range End (hidden by default) -->
        <div class="col-md-2" id="inputRangeEnd" style="display: none;">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
        </div>
        
        <!-- Action Buttons -->
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bx bx-search me-1"></i> Tampilkan
            </button>
            <a href="{{ route('dana-operasional.index') }}" class="btn btn-secondary me-2">
                <i class="bx bx-reset me-1"></i> Reset
            </a>
            <button type="button" class="btn btn-success" onclick="downloadPdfFiltered()">
                <i class="ti ti-file-type-pdf me-1"></i> PDF
            </button>
        </div>
    </form>
</div>
```

#### Header dengan Periode Label
```blade
<div class="card-header d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-0">Riwayat Transaksi</h5>
        <small class="text-muted">Periode: {{ $periodeLabel ?? 'Semua Data' }}</small>
    </div>
    <div class="btn-group">
        <!-- buttons -->
    </div>
</div>
```

---

### **3. JavaScript Functions**

#### Toggle Filter Inputs
```javascript
function toggleFilterInputs() {
    const filterType = document.getElementById('filterType').value;
    
    // Hide all inputs first
    document.getElementById('inputBulan').style.display = 'none';
    document.getElementById('inputTahun').style.display = 'none';
    document.getElementById('inputMinggu').style.display = 'none';
    document.getElementById('inputRangeStart').style.display = 'none';
    document.getElementById('inputRangeEnd').style.display = 'none';
    
    // Show input based on filter type
    if (filterType === 'bulan') {
        document.getElementById('inputBulan').style.display = 'block';
    } else if (filterType === 'tahun') {
        document.getElementById('inputTahun').style.display = 'block';
    } else if (filterType === 'minggu') {
        document.getElementById('inputMinggu').style.display = 'block';
    } else if (filterType === 'range') {
        document.getElementById('inputRangeStart').style.display = 'block';
        document.getElementById('inputRangeEnd').style.display = 'block';
    }
}

// Call on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFilterInputs();
});
```

#### Download PDF with Filter
```javascript
function downloadPdfFiltered() {
    const form = document.getElementById('formFilter');
    const formData = new FormData(form);
    
    // Build query string
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    // Redirect to PDF export with filter params
    window.location.href = '{{ route("dana-operasional.export-pdf") }}?' + params.toString();
}
```

---

## 📊 Test Results

### Data Test (12 transaksi total):
- **Januari 2025**: 2 transaksi
- **November 2025**: 10 transaksi

### Filter Test Results:
| Filter Type | Parameters | Range | Result |
|------------|-----------|-------|---------|
| Per Bulan | `bulan=2025-11` | 01 Nov - 30 Nov 2025 | 10 transaksi ✅ |
| Per Tahun | `tahun=2025` | 01 Jan - 31 Dec 2025 | 12 transaksi ✅ |
| Per Minggu | `minggu=2025-W46` | 10 Nov - 16 Nov 2025 | 10 transaksi ✅ |
| Range | `start=2025-11-01&end=2025-11-03` | 01-03 Nov 2025 | 0 transaksi ✅ |

---

## 🎨 User Interface

### Filter Dropdown
```
┌─────────────────────────────────────────────────┐
│ Tipe Filter: [▼ Per Bulan]                      │
│                                                   │
│ [Input sesuai tipe filter]                       │
│                                                   │
│ [🔍 Tampilkan] [🔄 Reset] [📄 PDF]              │
└─────────────────────────────────────────────────┘
```

### Dynamic Input Examples

**Per Bulan:**
```
Tipe Filter: [Per Bulan ▼]
Bulan: [2025-11] (month picker)
```

**Per Tahun:**
```
Tipe Filter: [Per Tahun ▼]
Tahun: [2025] (number input)
```

**Per Minggu:**
```
Tipe Filter: [Per Minggu ▼]
Minggu: [2025-W46] (week picker)
```

**Range Tanggal:**
```
Tipe Filter: [Range Tanggal ▼]
Dari Tanggal: [2025-11-01] (date picker)
Sampai Tanggal: [2025-11-30] (date picker)
```

---

## 🔗 URL Examples

### View dengan Filter
```
// Per Bulan
/dana-operasional?filter_type=bulan&bulan=2025-11

// Per Tahun
/dana-operasional?filter_type=tahun&tahun=2025

// Per Minggu
/dana-operasional?filter_type=minggu&minggu=2025-W46

// Range Tanggal
/dana-operasional?filter_type=range&start_date=2025-11-01&end_date=2025-11-30
```

### PDF Download dengan Filter
```
// Per Bulan
/dana-operasional/export-pdf?filter_type=bulan&bulan=2025-11

// Per Tahun
/dana-operasional/export-pdf?filter_type=tahun&tahun=2025

// Per Minggu
/dana-operasional/export-pdf?filter_type=minggu&minggu=2025-W46

// Range Tanggal
/dana-operasional/export-pdf?filter_type=range&start_date=2025-11-01&end_date=2025-11-30
```

---

## 📝 How to Use

### **Untuk User:**
1. Buka halaman Dana Operasional
2. Pilih tipe filter dari dropdown (Bulan/Tahun/Minggu/Range)
3. Input akan muncul sesuai tipe yang dipilih
4. Isi nilai filter (bulan, tahun, minggu, atau range tanggal)
5. Klik tombol **Tampilkan** untuk filter data
6. Klik tombol **PDF** untuk download laporan PDF dengan filter aktif
7. Klik tombol **Reset** untuk kembali ke view default (bulan ini)

### **Untuk Developer:**
1. Controller method `index()` dan `exportPdf()` sudah diupdate
2. View sudah ada filter dropdown dan dynamic inputs
3. JavaScript functions sudah ditambahkan
4. Periode label otomatis muncul di header card
5. Semua filter menggunakan Carbon untuk date calculation

---

## ✅ Features Checklist

- [x] Filter Per Bulan dengan month picker
- [x] Filter Per Tahun dengan number input
- [x] Filter Per Minggu dengan week picker
- [x] Filter Range Tanggal dengan 2 date pickers
- [x] Dynamic show/hide input fields
- [x] Periode label di header card
- [x] PDF download dengan filter parameter
- [x] JavaScript toggleFilterInputs()
- [x] JavaScript downloadPdfFiltered()
- [x] Controller index() dengan switch/case
- [x] Controller exportPdf() dengan filter support
- [x] Carbon date calculations untuk semua tipe
- [x] Default value untuk setiap tipe filter
- [x] Reset button untuk clear filter

---

## 🚀 Next Enhancements (Optional)

1. **Quick Filter Buttons**
   - "Hari Ini", "Kemarin", "7 Hari Terakhir", "30 Hari Terakhir"
   
2. **Excel Export dengan Filter**
   - Update `exportExcel()` method untuk support filter_type
   
3. **Filter Presets**
   - Save favorite filters untuk quick access
   
4. **Chart/Graph**
   - Visual representation dari data filtered (pie chart, line chart)
   
5. **Print View**
   - Optimized print layout langsung dari browser

---

## 📌 Related Files

- **Controller**: `app/Http/Controllers/DanaOperasionalController.php`
- **View**: `resources/views/dana-operasional/index.blade.php`
- **Model**: `app/Models/RealisasiDanaOperasional.php`
- **Model**: `app/Models/SaldoHarianOperasional.php`
- **Routes**: `routes/web.php` (line 1161-1186)
- **Test Script**: `test_filter_advanced.php`

---

## 🐛 Troubleshooting

### Issue: Input tidak muncul saat pilih filter
**Solution:** 
- Check JavaScript console untuk errors
- Verify `toggleFilterInputs()` function terpanggil
- Verify element IDs match: `inputBulan`, `inputTahun`, `inputMinggu`, `inputRangeStart`, `inputRangeEnd`

### Issue: Periode label tidak muncul
**Solution:**
- Verify controller mengirim variable `$periodeLabel` ke view
- Check Blade syntax: `{{ $periodeLabel ?? 'Semua Data' }}`

### Issue: PDF download kosong
**Solution:**
- Verify route `dana-operasional.export-pdf` exists
- Check controller `exportPdf()` method sudah diupdate dengan filter logic
- Verify parameter dikirim via URL query string

---

**Dokumentasi dibuat**: 13 November 2025  
**Last Updated**: 13 November 2025  
**Version**: 1.0  
**Status**: ✅ COMPLETED & TESTED
