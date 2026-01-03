# 📸 Dokumentasi Face Verification & Map Visualization

## 📋 Ringkasan

Sistem absensi jamaah telah ditingkatkan dengan 2 fitur keamanan baru:

1. **Face Verification** - Verifikasi wajah menggunakan AI untuk memastikan jamaah yang absen sesuai dengan foto di database
2. **Map Visualization** - Tampilan peta interaktif dengan radius merah dan marker lokasi jamaah

---

## 🎯 Fitur Face Verification

### Cara Kerja

1. **Deteksi Foto Database**
   - Sistem mengecek apakah jamaah memiliki foto di database (kolom `foto` di tabel `yayasan_masar`)
   - Jika ada foto, verifikasi wajah akan diaktifkan
   - Jika tidak ada foto, sistem langsung menerima foto selfie tanpa verifikasi

2. **Load AI Models**
   - Menggunakan **face-api.js** library (TensorFlow.js based)
   - 3 model yang diload:
     - `tinyFaceDetector` - Deteksi wajah di foto
     - `faceLandmark68Net` - Deteksi 68 titik landmark wajah
     - `faceRecognitionNet` - Ekstrak descriptor wajah untuk perbandingan

3. **Proses Verifikasi**
   ```
   Ambil Foto Selfie
        ↓
   Deteksi Wajah di Selfie
        ↓
   Deteksi Wajah di Foto Database
        ↓
   Hitung Euclidean Distance
        ↓
   Compare dengan Threshold (0.6)
        ↓
   Distance < 0.6 = MATCH ✅
   Distance >= 0.6 = REJECT ❌
   ```

4. **Hasil Verifikasi**
   - **Match**: Wajah cocok → Similarity ditampilkan (misal: 82%) → Lanjut ke GPS
   - **Tidak Match**: Wajah tidak cocok → SweetAlert error → Harus ambil foto ulang
   - **Wajah Tidak Terdeteksi**: Pencahayaan buruk → SweetAlert error → Ambil foto ulang
   - **Error**: Jika verifikasi gagal (network, dll) → Absensi tetap dilanjutkan dengan warning

### Teknologi yang Digunakan

- **Library**: face-api.js v0.22.2
- **CDN**: `https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js`
- **Models CDN**: `https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/`
- **Algorithm**: Euclidean Distance untuk face descriptor comparison
- **Threshold**: 0.6 (standard threshold, semakin kecil = semakin strict)

### Kelebihan

✅ **Keamanan Tinggi** - Mencegah absensi palsu dengan wajah orang lain  
✅ **AI-Powered** - Menggunakan deep learning untuk akurasi tinggi  
✅ **Real-time** - Verifikasi dilakukan di browser (tidak perlu kirim ke server)  
✅ **User Friendly** - Menampilkan persentase similarity  
✅ **Fallback** - Jika jamaah belum punya foto, tetap bisa absen  

### Keterbatasan

⚠️ **Membutuhkan Pencahayaan Baik** - Wajah harus terlihat jelas  
⚠️ **Load Time** - Model AI butuh waktu ~3-5 detik untuk load  
⚠️ **Browser Compatibility** - Butuh browser modern (Chrome, Firefox, Safari, Edge)  
⚠️ **Threshold Fixed** - Saat ini threshold 0.6 tidak bisa diubah dari UI  

---

## 🗺️ Fitur Map Visualization

### Cara Kerja

1. **Trigger Display**
   - Peta otomatis muncul setelah GPS koordinat berhasil didapat
   - Menampilkan lokasi venue (event) dan lokasi jamaah

2. **Elemen Peta**
   - **Marker Venue** (ungu): Titik pusat event
   - **Lingkaran Merah**: Radius yang diizinkan (misal: 100 meter)
   - **Marker Jamaah** (hijau/kuning): 
     - Hijau = Dalam radius (valid)
     - Kuning = Di luar radius (invalid)

3. **Auto Zoom**
   - Peta otomatis zoom untuk menampilkan kedua marker (venue & jamaah)
   - Padding 50px untuk tampilan lebih nyaman

4. **Interactive**
   - Klik marker untuk melihat popup info
   - Zoom in/out dengan scroll mouse atau pinch gesture
   - Pan/drag untuk melihat area lain

### Teknologi yang Digunakan

- **Library**: Leaflet.js v1.9.4
- **CSS CDN**: `https://unpkg.com/leaflet@1.9.4/dist/leaflet.css`
- **JS CDN**: `https://unpkg.com/leaflet@1.9.4/dist/leaflet.js`
- **Tiles**: OpenStreetMap (gratis, no API key needed)
- **Tiles URL**: `https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`

### Visual Elements

```
┌─────────────────────────────────┐
│  🗺️ Peta Lokasi               │
│                                 │
│      🔵 Venue                  │
│      ⭕ (Radius Merah)         │
│                                 │
│              🟢 Jamaah         │
│                                 │
│  Info: Jarak 45 meter          │
│  Status: ✓ Dalam radius        │
└─────────────────────────────────┘
```

### Kelebihan

✅ **Visual Jelas** - Langsung terlihat posisi relatif jamaah terhadap venue  
✅ **No API Key** - Menggunakan OpenStreetMap yang gratis  
✅ **Responsive** - Auto-resize di mobile & desktop  
✅ **Interactive** - Bisa zoom dan pan  
✅ **Color Coded** - Hijau/kuning untuk status valid/invalid  

### Keterbatasan

⚠️ **Membutuhkan Internet** - Tiles diload dari server OpenStreetMap  
⚠️ **Accuracy GPS** - Akurasi tergantung device (biasanya 5-50 meter)  
⚠️ **Loading Time** - Tiles butuh waktu load (biasanya 1-2 detik)  

---

## 📊 Flow Absensi Lengkap (Updated)

```
1. Input PIN
     ↓
2. Pilih Jamaah (jika tutup modal)
     ↓
3. Halaman Absensi Terbuka
     ↓
4. FACE VERIFICATION
   ├─ Klik "Aktifkan Kamera"
   ├─ Load Face-API Models (jika ada foto DB)
   ├─ Ambil Foto Selfie
   ├─ Verifikasi Wajah dengan Database
   ├─ Match? → ✅ Lanjut
   └─ Tidak Match? → ❌ Ambil Ulang
     ↓
5. GPS LOCATION
   ├─ Klik "Dapatkan Lokasi"
   ├─ Get Coordinates
   ├─ Hitung Jarak ke Venue
   ├─ Tampilkan PETA (Leaflet.js)
   │   ├─ Marker Venue (ungu)
   │   ├─ Lingkaran Radius (merah)
   │   └─ Marker Jamaah (hijau/kuning)
   ├─ Dalam Radius? → ✅ Valid
   └─ Di Luar Radius? → ⚠️ Invalid (bisa tetap submit)
     ↓
6. Submit Absensi
   ├─ Foto disimpan: storage/app/public/uploads/absensi_jamaah/
   ├─ Data disimpan: presensi_yayasan
   ├─ Counter increment: yayasan_masar.jumlah_kehadiran
   └─ Redirect ke Success Page
```

---

## 🔧 Konfigurasi

### Threshold Face Matching

Edit file: `resources/views/qr-attendance/jamaah-attendance.blade.php`

```javascript
const threshold = 0.6; // Line ~605

// Ubah nilai threshold:
// 0.4 = Very Strict (wajah harus 60%+ similar)
// 0.5 = Strict (50%+ similar)
// 0.6 = Standard (40%+ similar) ← Default
// 0.7 = Loose (30%+ similar)
```

### Tiles Peta (jika mau ganti)

```javascript
// OpenStreetMap (default, gratis)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Alternatif: CartoDB (lebih clean)
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '© CartoDB'
}).addTo(map);

// Alternatif: Esri Satellite
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: '© Esri'
}).addTo(map);
```

---

## 🧪 Testing

### Test Face Verification

1. **Upload Foto Jamaah**
   ```sql
   -- Update foto jamaah di database
   UPDATE yayasan_masar 
   SET foto = 'nama_file.jpg'
   WHERE kode_yayasan = '251200002';
   ```

2. **Upload File Foto**
   - Copy foto ke: `public/storage/yayasan_masar/nama_file.jpg`
   - Atau: `public/storage/jamaah/nama_file.jpg`

3. **Test Scenarios**
   - ✅ Foto wajah sendiri → Harus MATCH
   - ❌ Foto wajah orang lain → Harus REJECT
   - ⚠️ Foto tanpa wajah → Deteksi error
   - ⚠️ Foto dengan pencahayaan buruk → Mungkin REJECT

### Test Map Visualization

1. **Dalam Radius**
   - Buka absensi di lokasi event
   - GPS harus menunjukkan jarak < radius
   - Marker harus hijau
   - Status: "Lokasi valid"

2. **Luar Radius**
   - Buka absensi jauh dari event
   - GPS harus menunjukkan jarak > radius
   - Marker harus kuning
   - Status: "Di luar radius"

3. **Peta Interaktif**
   - Klik marker venue → Popup muncul
   - Klik marker jamaah → Popup muncul dengan jarak
   - Zoom in/out → Harus smooth
   - Pan/drag → Harus responsive

---

## 🚀 Performance

### Load Time Analysis

| Component | Size | Load Time |
|-----------|------|-----------|
| face-api.js | ~500 KB | ~1-2 detik |
| Face Models | ~5 MB | ~3-5 detik |
| Leaflet.js | ~150 KB | <1 detik |
| Map Tiles | ~50 KB/tile | ~1-2 detik |
| **Total First Load** | **~6 MB** | **~5-8 detik** |

### Optimization Tips

1. **Cache Face Models**
   - Browser otomatis cache models setelah pertama kali load
   - Load berikutnya lebih cepat (~1-2 detik)

2. **Lazy Load**
   - Face models hanya diload jika jamaah punya foto
   - Map hanya dirender saat GPS didapat

3. **CDN**
   - Semua library dari CDN (caching global)
   - Tile maps di-cache browser

---

## 📱 Browser Support

| Browser | Face-API | Leaflet | Status |
|---------|----------|---------|--------|
| Chrome 90+ | ✅ | ✅ | Full Support |
| Firefox 88+ | ✅ | ✅ | Full Support |
| Safari 14+ | ✅ | ✅ | Full Support |
| Edge 90+ | ✅ | ✅ | Full Support |
| Opera 76+ | ✅ | ✅ | Full Support |
| Mobile Chrome | ✅ | ✅ | Full Support |
| Mobile Safari | ✅ | ✅ | Full Support |
| IE 11 | ❌ | ⚠️ | Not Supported |

---

## 🐛 Troubleshooting

### Face Verification Gagal

**Problem**: "Failed to load Face-API models"

**Solution**:
1. Cek koneksi internet
2. Cek console browser (F12)
3. Pastikan CDN tidak diblokir firewall
4. Coba refresh page (Ctrl+F5)

---

**Problem**: "Wajah tidak terdeteksi"

**Solution**:
1. Pastikan pencahayaan cukup
2. Wajah harus frontal (tidak miring)
3. Jarak kamera tidak terlalu dekat/jauh
4. Tidak ada penghalang (masker, kacamata gelap)

---

**Problem**: "Wajah tidak cocok" padahal orangnya benar

**Solution**:
1. Cek foto di database apakah jelas
2. Update foto database dengan foto lebih baru
3. Turunkan threshold (0.6 → 0.7)
4. Pastikan foto database adalah foto wajah frontal

---

### Map Tidak Muncul

**Problem**: Map container kosong/blank

**Solution**:
1. Cek koneksi internet
2. Pastikan Leaflet.css & Leaflet.js ter-load
3. Buka console (F12), cek error
4. Pastikan koordinat venue valid (tidak null)

---

**Problem**: Tiles tidak muncul di peta

**Solution**:
1. Cek koneksi internet
2. OpenStreetMap bisa down, coba lagi nanti
3. Ganti tiles provider (lihat bagian Konfigurasi)

---

## 📚 Resources

### Face-API.js
- Dokumentasi: https://github.com/justadudewhohacks/face-api.js
- Live Demo: https://justadudewhohacks.github.io/face-api.js/docs/index.html
- Models: https://github.com/vladmandic/face-api

### Leaflet.js
- Dokumentasi: https://leafletjs.com/reference.html
- Tutorials: https://leafletjs.com/examples.html
- Plugins: https://leafletjs.com/plugins.html

### OpenStreetMap
- Website: https://www.openstreetmap.org
- Tiles Usage Policy: https://operations.osmfoundation.org/policies/tiles/
- Alternative Providers: https://leaflet-extras.github.io/leaflet-providers/preview/

---

## ✅ Checklist Update

- [x] Face-API.js CDN added
- [x] Leaflet.js CDN added
- [x] Face verification logic implemented
- [x] Map visualization implemented
- [x] Error handling untuk face detection
- [x] Fallback jika jamaah tidak punya foto
- [x] Auto-zoom map untuk optimal view
- [x] Color-coded markers (hijau/kuning)
- [x] Interactive popups di markers
- [x] Responsive design (mobile & desktop)
- [x] Loading indicators
- [x] SweetAlert notifications

---

**Update**: 3 Januari 2026  
**Author**: GitHub Copilot  
**Version**: 2.0 (dengan Face Verification & Map)
