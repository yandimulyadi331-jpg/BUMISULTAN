# 📱 Dokumentasi Manajemen Kendaraan Mode Karyawan dengan Swipe Navigation

## 📋 Deskripsi
Fitur manajemen kendaraan khusus untuk karyawan dengan navigasi swipe card yang intuitif. Saat karyawan membuka menu kendaraan, langsung diarahkan ke halaman detail kendaraan dengan card yang bisa digeser untuk berpindah antar kendaraan. Data aktivitas, peminjaman, dan service otomatis berubah sesuai kendaraan yang dipilih.

---

## ✨ Fitur Utama

### 1. **Auto Redirect ke Detail**
- Saat klik menu "Kendaraan" di mode karyawan
- Langsung redirect ke detail kendaraan pertama
- Tidak ada halaman daftar/index yang terpisah

### 2. **Swipeable Card Kendaraan**
- Card kendaraan bisa di-swipe horizontal (kiri/kanan)
- Mendukung touch gesture di mobile
- Button navigasi (prev/next) untuk desktop
- Indicator dots untuk melihat posisi kendaraan

### 3. **Dynamic Content Update**
- Data otomatis berubah saat pindah kendaraan:
  - ✅ Informasi kendaraan
  - ✅ Aktivitas Keluar/Masuk
  - ✅ Peminjaman
  - ✅ Service
  - ✅ Action cards sesuai status kendaraan

### 4. **Read-Only Mode**
- Karyawan hanya bisa VIEW data
- Tidak ada tombol Edit/Delete untuk master data kendaraan
- Tetap bisa melakukan aktivitas (keluar/masuk, peminjaman, service)

---

## 🎯 Alur Penggunaan

### **Akses Halaman**
```
Menu Kendaraan → Auto redirect ke Detail Kendaraan Pertama
```

### **Navigasi Antar Kendaraan**

#### Desktop:
1. Klik tombol **◄** (Previous) untuk kendaraan sebelumnya
2. Klik tombol **►** (Next) untuk kendaraan berikutnya
3. Klik **dots indicator** untuk langsung ke kendaraan tertentu

#### Mobile/Touch:
1. **Swipe kiri** untuk kendaraan berikutnya
2. **Swipe kanan** untuk kendaraan sebelumnya
3. Tap **dots indicator** untuk langsung ke kendaraan tertentu

### **Melihat Detail**
1. Card kendaraan menampilkan:
   - Foto kendaraan
   - Nama & No. Polisi
   - Jenis, Merk, Model
   - Kode Kendaraan
   - Warna, No. Mesin
   - Kapasitas
   - Status (badge berwarna)

2. Tab di bawah card:
   - **Aktivitas Keluar/Masuk**: Histori keluar masuk kendaraan
   - **Peminjaman**: Histori peminjaman
   - **Service**: Histori service & maintenance

---

## 🎨 Tampilan UI

### **Card Kendaraan**
```
┌─────────────────────────────────────┐
│  ◄  [==== CARD KENDARAAN ====]  ►  │
│                                      │
│  ┌────┐  DFGH B 123 NG              │
│  │FOTO│  Mobil - BZXCBV DF          │
│  └────┘                              │
│         Kode: 233                    │
│         Warna: PUTIH                 │
│         No. Mesin: 2345              │
│         Kapasitas: - orang           │
│         Status: [Sedang Keluar]      │
│                                      │
│         ● ○ ○ ○ ○                   │  ← Indicators
└─────────────────────────────────────┘
```

### **Border Aktif**
- Card yang sedang dipilih memiliki **border biru (3px solid #667eea)**
- Card lain tidak memiliki border

### **Indicator Dots**
- **Biru (#667eea)**: Kendaraan aktif
- **Abu-abu (#ddd)**: Kendaraan lain
- Hover effect: Scale 1.3x

---

## 🔧 Implementasi Teknis

### **Controller**

#### `KendaraanKaryawanController.php`

```php
/**
 * Redirect langsung ke detail kendaraan pertama
 */
public function index(Request $request)
{
    $firstKendaraan = Kendaraan::orderBy('kode_kendaraan')->first();
    
    if (!$firstKendaraan) {
        return redirect('/dashboard')->with('error', 'Tidak ada kendaraan tersedia');
    }
    
    return redirect()->route('kendaraan.karyawan.detail', Crypt::encrypt($firstKendaraan->id));
}

/**
 * Detail Kendaraan dengan Swipeable Card Navigation
 */
public function show($id)
{
    $id = Crypt::decrypt($id);
    $kendaraan = Kendaraan::with([
        'cabang', 
        'aktivitas' => function($q) { $q->latest()->limit(10); },
        'peminjaman' => function($q) { $q->latest()->limit(10); },
        'services' => function($q) { $q->latest()->limit(10); },
        'jadwalServices',
        'aktivitasAktif',
        'peminjamanAktif',
        'serviceAktif'
    ])->findOrFail($id);
    
    // Load semua kendaraan untuk swipe navigation
    $allKendaraan = Kendaraan::with('cabang')
        ->orderBy('kode_kendaraan')
        ->get();
    
    // Cari index kendaraan saat ini
    $currentIndex = $allKendaraan->search(function($item) use ($id) {
        return $item->id == $id;
    });
    
    return view('kendaraan.karyawan.detail', compact('kendaraan', 'allKendaraan', 'currentIndex'));
}

/**
 * API untuk encrypt ID (untuk navigasi swipe)
 */
public function encryptId($id)
{
    return response()->json([
        'encrypted_id' => Crypt::encrypt($id)
    ]);
}
```

### **Routes**

```php
// Kendaraan Karyawan Routes
Route::prefix('kendaraan-karyawan')->controller(KendaraanKaryawanController::class)->group(function () {
    Route::get('/', 'index')->name('kendaraan.karyawan.index');
    Route::get('/{id}/detail', 'show')->name('kendaraan.karyawan.detail');
});

// API untuk encrypt ID (untuk swipe navigation)
Route::get('/api/encrypt-id/{id}', [KendaraanKaryawanController::class, 'encryptId']);
```

### **View Structure**

#### `resources/views/kendaraan/karyawan/detail.blade.php`

**Struktur Utama:**
```blade
@extends('layouts.mobile.app')

<!-- Header Section -->
<div id="header-section">
    <h3>Detail Kendaraan</h3>
</div>

<!-- Swipeable Cards Container -->
<div class="position-relative">
    <!-- Navigation Buttons -->
    <button id="prevBtn">◄</button>
    <button id="nextBtn">►</button>
    
    <!-- Cards Container -->
    <div id="kendaraanCardsContainer">
        <div id="kendaraanCards" class="d-flex">
            @foreach($allKendaraan as $index => $k)
                <div class="kendaraan-card" data-index="{{ $index }}" data-id="{{ $k->id }}">
                    <!-- Card Content -->
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Indicator Dots -->
    <div id="carouselIndicators">
        @foreach($allKendaraan as $index => $k)
            <span class="indicator-dot" data-index="{{ $index }}"></span>
        @endforeach
    </div>
</div>

<!-- Tabs: Aktivitas, Peminjaman, Service -->
<ul class="nav nav-tabs">...</ul>
<div class="tab-content">...</div>
```

### **JavaScript Logic**

```javascript
let currentIndex = {{ $currentIndex }};
let totalKendaraan = {{ count($allKendaraan) }};
let allKendaraanData = @json($allKendaraan);

// Button Navigation
$('#prevBtn').click(function() {
    if (currentIndex > 0) {
        currentIndex--;
        navigateToKendaraan();
    }
});

$('#nextBtn').click(function() {
    if (currentIndex < totalKendaraan - 1) {
        currentIndex++;
        navigateToKendaraan();
    }
});

// Touch Swipe Support
let touchStartX = 0;
let touchEndX = 0;

$('#kendaraanCardsContainer').on('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

$('#kendaraanCardsContainer').on('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    if (touchEndX < touchStartX - 50) {
        // Swipe left (next)
        if (currentIndex < totalKendaraan - 1) {
            currentIndex++;
            navigateToKendaraan();
        }
    }
    if (touchEndX > touchStartX + 50) {
        // Swipe right (prev)
        if (currentIndex > 0) {
            currentIndex--;
            navigateToKendaraan();
        }
    }
}

function navigateToKendaraan() {
    const kendaraanId = allKendaraanData[currentIndex].id;
    
    // Show loading
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get encrypted ID via AJAX
    $.ajax({
        url: `/api/encrypt-id/${kendaraanId}`,
        type: 'GET',
        success: function(response) {
            window.location.href = `/kendaraan-karyawan/${response.encrypted_id}/detail`;
        },
        error: function() {
            window.location.reload();
        }
    });
}
```

---

## 🎨 CSS Styling

```css
/* Transition for smooth swipe */
.transition-all {
    transition: transform 0.3s ease;
}

/* Indicator dots hover effect */
.indicator-dot:hover {
    transform: scale(1.3);
}

/* Card hover effect */
.hover-shadow:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Navigation buttons */
#prevBtn, #nextBtn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
```

---

## 📱 Responsive Design

### **Desktop (>768px)**
- Navigation buttons visible
- Card width 100%
- Smooth transition animation
- Click dots untuk navigasi

### **Mobile (<768px)**
- Touch swipe gestures
- Navigation buttons tetap ada untuk fallback
- Optimized touch area
- Smooth swipe animation

---

## 🔐 Security

### **ID Encryption**
- Semua ID kendaraan di-encrypt menggunakan `Crypt::encrypt()`
- Decrypt di controller: `Crypt::decrypt($id)`
- API endpoint `/api/encrypt-id/{id}` untuk AJAX navigation

### **Permission**
- Mode karyawan: READ-ONLY untuk master data
- Tidak bisa Edit/Delete kendaraan
- Tetap bisa melakukan aktivitas operasional

---

## 📊 Data Flow

```
1. User klik menu "Kendaraan"
   ↓
2. Controller index() redirect ke detail kendaraan pertama
   ↓
3. Controller show() load:
   - Data kendaraan aktif (dengan relations)
   - Semua kendaraan (untuk navigation)
   - Current index
   ↓
4. View render:
   - Swipeable cards (semua kendaraan)
   - Detail tabs (kendaraan aktif)
   - JavaScript setup navigation
   ↓
5. User swipe/click navigation
   ↓
6. AJAX call `/api/encrypt-id/{new_id}`
   ↓
7. Get encrypted ID
   ↓
8. Redirect ke `/kendaraan-karyawan/{encrypted_id}/detail`
   ↓
9. Back to step 3 (load new kendaraan)
```

---

## 🧪 Testing

### **Test Scenario**

1. **Akses Menu Kendaraan**
   - Buka: `/kendaraan-karyawan`
   - Expected: Auto redirect ke detail kendaraan pertama
   - Check: URL berubah ke `/kendaraan-karyawan/{encrypted_id}/detail`

2. **Swipe Navigation (Mobile)**
   - Swipe left → Next kendaraan
   - Swipe right → Previous kendaraan
   - Check: Card berubah, data tabs berubah

3. **Button Navigation (Desktop)**
   - Click ◄ → Previous kendaraan
   - Click ► → Next kendaraan
   - Check: Smooth transition, loading indicator

4. **Indicator Dots**
   - Click dot ke-3
   - Expected: Langsung ke kendaraan ke-3
   - Check: Active dot berubah warna

5. **Tab Content**
   - Pindah kendaraan
   - Check tab Aktivitas, Peminjaman, Service
   - Expected: Data berubah sesuai kendaraan

6. **Boundary Cases**
   - Di kendaraan pertama, prevBtn disabled
   - Di kendaraan terakhir, nextBtn disabled
   - Swipe di edge tidak error

---

## 🐛 Troubleshooting

### **Problem**: Card tidak bergeser
**Solution**: 
- Check CSS `transform: translateX()`
- Verify `currentIndex` calculation
- Check console untuk JavaScript errors

### **Problem**: Data tidak berubah setelah swipe
**Solution**:
- Check AJAX call `/api/encrypt-id/{id}`
- Verify route definition
- Check controller `encryptId()` method

### **Problem**: Touch swipe tidak responsive
**Solution**:
- Check `touchstart` dan `touchend` event binding
- Verify swipe threshold (50px)
- Test di real device (bukan browser emulator)

### **Problem**: Indicator dots tidak update
**Solution**:
- Check `updateCardPosition()` function
- Verify CSS selector `.indicator-dot`
- Check data-index attribute

---

## 📈 Performance

### **Optimization**
- Load hanya 10 latest records per tab (aktivitas, peminjaman, service)
- Lazy load dengan `->limit(10)` di Eloquent
- AJAX navigation untuk smooth UX
- CSS transition menggunakan GPU acceleration

### **Caching Strategy**
```php
// Future improvement: Cache all kendaraan list
$allKendaraan = Cache::remember('all_kendaraan_list', 3600, function() {
    return Kendaraan::with('cabang')->orderBy('kode_kendaraan')->get();
});
```

---

## 🚀 Future Enhancements

1. **Search & Filter**
   - Filter by jenis kendaraan
   - Search by nama/no. polisi
   - Quick jump to kendaraan

2. **Infinite Scroll**
   - Load kendaraan dynamically
   - Better performance untuk banyak data

3. **Favorites**
   - Bookmark kendaraan favorit
   - Quick access

4. **Real-time Status**
   - WebSocket untuk update status real-time
   - Notification saat status berubah

5. **Analytics**
   - Track most viewed kendaraan
   - Usage statistics per kendaraan

---

## 📝 Changelog

### **Version 1.0.0** (November 14, 2025)
- ✅ Initial release
- ✅ Swipeable card navigation
- ✅ Auto redirect to detail
- ✅ Touch gesture support
- ✅ Indicator dots
- ✅ Dynamic content update
- ✅ Read-only mode for karyawan
- ✅ Mobile responsive layout

---

## 👥 Credits

**Developer**: AI Assistant  
**Project**: Bumi Sultan Super App v2  
**Module**: Manajemen Kendaraan Karyawan  
**Date**: November 14, 2025  

---

## 📞 Support

Untuk pertanyaan atau issue:
1. Check dokumentasi ini terlebih dahulu
2. Review code di `KendaraanKaryawanController.php`
3. Check JavaScript console untuk errors
4. Test di berbagai devices (desktop, mobile, tablet)

---

**Happy Coding! 🚗💨**
