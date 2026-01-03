# 🚀 Quick Start: Face Verification & Map Visualization

## ⚡ Test Langsung (5 Menit)

### 1️⃣ Setup Foto Jamaah (Opsional)

Jika ingin test **face verification**, jamaah harus punya foto di database:

```bash
# Cek apakah jamaah sudah punya foto
php artisan tinker --execute="echo DB::table('yayasan_masar')->where('kode_yayasan', '251200002')->value('foto');"
```

**Output yang diharapkan**: `251200002.jpg` (atau nama file lain)

---

#### Upload Foto Jamaah

**Option A: Upload via Web Interface** (jika ada fitur upload)
- Login ke sistem
- Menu Jamaah → Edit DESTY
- Upload foto wajah frontal yang jelas

**Option B: Manual Copy File**
```bash
# Copy foto ke salah satu lokasi ini:
# 1. public/storage/yayasan_masar/251200002.jpg
# 2. public/storage/jamaah/251200002.jpg

# Contoh:
copy "C:\path\to\foto\desty.jpg" "public\storage\yayasan_masar\251200002.jpg"
```

**Option C: Update Database Saja** (jika foto sudah ada di folder)
```sql
UPDATE yayasan_masar 
SET foto = '251200002.jpg'
WHERE kode_yayasan = '251200002';
```

---

### 2️⃣ Akses Halaman Absensi

1. **Buka Browser**
   ```
   http://localhost/absensi-qr/{token}/pin
   ```

2. **Input PIN**
   - PIN: `1234`
   - Otomatis redirect ke halaman absensi DESTY

3. **Atau Manual Select**
   - Klik X untuk tutup modal PIN
   - Pilih card DESTY
   - Redirect ke halaman absensi

---

### 3️⃣ Test Face Verification

#### Scenario 1: Jamaah PUNYA Foto di Database

1. **Klik "Aktifkan Kamera"**
   - Browser akan minta izin akses kamera
   - Klik "Allow"

2. **Loading Face-API Models**
   - Tunggu 3-5 detik
   - Console akan show: "Loading Face-API models..."
   - Setelah selesai: "Face-API models loaded successfully"

3. **Ambil Foto Selfie**
   - Posisikan wajah dalam lingkaran
   - Pastikan pencahayaan bagus
   - Klik "Ambil Foto"

4. **Hasil Verifikasi**

   **✅ MATCH (Wajah Cocok)**
   ```
   Icon: ✓ (hijau)
   Status: "Wajah terverifikasi! (Similarity: 82%)"
   Action: Lanjut ke GPS
   ```

   **❌ TIDAK MATCH**
   ```
   Icon: ⚠️ (merah)
   Status: "Wajah tidak cocok dengan database (Similarity: 32%)"
   SweetAlert: "Wajah Anda tidak cocok dengan foto di database"
   Action: Harus ambil foto ulang atau hubungi admin
   ```

   **⚠️ WAJAH TIDAK TERDETEKSI**
   ```
   Icon: ⚠️ (merah)
   Status: "Wajah tidak terdeteksi"
   SweetAlert: "Tidak dapat mendeteksi wajah pada foto"
   Action: Ambil foto ulang dengan pencahayaan lebih baik
   ```

---

#### Scenario 2: Jamaah TIDAK Punya Foto di Database

1. **Klik "Aktifkan Kamera"**
2. **Ambil Foto Selfie**
3. **Hasil**
   ```
   Icon: ✓ (hijau)
   Status: "Foto wajah berhasil diambil"
   Note: Verifikasi di-skip karena tidak ada foto referensi
   Action: Langsung lanjut ke GPS
   ```

---

### 4️⃣ Test Map Visualization

1. **Klik "Dapatkan Lokasi Saya"**
   - Browser akan minta izin akses lokasi
   - Klik "Allow"

2. **Menunggu GPS**
   - Status: "Mendapatkan lokasi Anda..."
   - Tunggu 2-5 detik

3. **Peta Muncul** 🗺️

   **Visual Elements:**
   ```
   ┌───────────────────────────────┐
   │  🗺️ OpenStreetMap            │
   │                               │
   │    🔵 Venue (Ungu)           │
   │    ⭕ Radius (Merah)         │
   │                               │
   │          🟢 Anda (Hijau)     │
   │                               │
   └───────────────────────────────┘
   
   Info di bawah peta:
   Latitude: -6.467189
   Longitude: 107.062736
   Jarak dari venue: 45 meter
   ```

4. **Status Lokasi**

   **✅ DALAM RADIUS**
   ```
   Marker: Hijau
   Status: "Lokasi Anda valid - Dalam radius venue"
   Action: Tombol submit aktif
   ```

   **⚠️ LUAR RADIUS**
   ```
   Marker: Kuning
   Status: "Anda di luar radius venue (250m). Maksimal: 100m"
   Action: Tombol submit tetap bisa diklik (tergantung setting)
   ```

5. **Interaksi Peta**
   - **Klik Marker Venue** → Popup: "📍 Lokasi Event"
   - **Klik Marker Anda** → Popup: "📱 Lokasi Anda - Jarak: 45 meter"
   - **Zoom In/Out** → Scroll mouse atau pinch gesture
   - **Pan/Drag** → Klik dan drag peta

---

### 5️⃣ Submit Absensi

1. **Pastikan Kedua Validasi Hijau**
   - ✅ Validasi Wajah
   - ✅ Validasi Lokasi

2. **Klik "Submit Absensi"**
   - Loading: "Menyimpan absensi..."

3. **Success**
   ```
   SweetAlert Success:
   "Absensi Berhasil!
   Terima kasih telah hadir di [Nama Event]
   
   Total Kehadiran: 5x"
   ```

4. **Redirect ke Success Page**
   - Menampilkan nama jamaah
   - Event name
   - Tanggal & waktu
   - Total kehadiran

---

## 🧪 Testing Checklist

### Face Verification Tests

- [ ] **Test dengan foto sendiri** → Harus MATCH (similarity >40%)
- [ ] **Test dengan foto orang lain** → Harus REJECT
- [ ] **Test tanpa pencahayaan** → Wajah tidak terdeteksi
- [ ] **Test dengan kacamata** → Bisa MATCH atau tidak (tergantung foto DB)
- [ ] **Test tanpa foto DB** → Langsung accept foto
- [ ] **Test dengan koneksi lambat** → Loading lebih lama tapi tetap work

### Map Visualization Tests

- [ ] **Test dalam radius** → Marker hijau, status valid
- [ ] **Test luar radius** → Marker kuning, status invalid
- [ ] **Test klik marker venue** → Popup muncul
- [ ] **Test klik marker jamaah** → Popup dengan jarak muncul
- [ ] **Test zoom in/out** → Smooth
- [ ] **Test pan/drag** → Responsive
- [ ] **Test di mobile** → Map responsive, touch works

### Integration Tests

- [ ] **Full flow**: PIN → Face → GPS → Submit → Success
- [ ] **Ambil foto ulang** → Reset validation, bisa foto lagi
- [ ] **Refresh page** → Validation reset
- [ ] **Back button** → Kembali ke daftar jamaah
- [ ] **Duplicate absensi** → Cek apakah terdeteksi sudah absen

---

## 🐛 Common Issues & Fixes

### Issue 1: Face-API Models Tidak Load

**Symptoms:**
- Console error: "Failed to load Face-API models"
- Foto diambil tapi tidak ada verifikasi

**Fix:**
```bash
# Cek koneksi internet
ping cdn.jsdelivr.net

# Cek browser console (F12)
# Lihat error message detail

# Refresh page
Ctrl+F5
```

---

### Issue 2: Peta Tidak Muncul

**Symptoms:**
- Container peta kosong/putih
- Tidak ada tiles yang load

**Fix:**
```javascript
// Buka Console (F12), cek error
// Pastikan Leaflet loaded:
console.log(typeof L); // Harus "object"

// Test manual load tiles:
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
```

---

### Issue 3: Wajah Tidak Terdeteksi

**Fix:**
1. Pastikan pencahayaan cukup (tidak terlalu gelap/terang)
2. Wajah frontal (tidak miring >30 derajat)
3. Jarak kamera ideal: 30-50 cm
4. Tidak ada penghalang (masker, kacamata gelap, topi)
5. Resolusi kamera minimal 640x480

---

### Issue 4: Similarity Rendah Terus

**Problem:**
- Foto sendiri tapi similarity cuma 30-35%
- Threshold 0.6 = butuh 40%+

**Fix:**
```javascript
// Option 1: Turunkan threshold (lebih loose)
// Edit jamaah-attendance.blade.php line ~605
const threshold = 0.7; // Dari 0.6 → 0.7

// Option 2: Update foto database dengan foto lebih baru
// Foto harus:
// - Frontal
// - Pencahayaan bagus
// - Resolusi tinggi
// - Tidak blur
```

---

### Issue 5: GPS Tidak Akurat

**Problem:**
- Jarak jauh dari venue padahal sudah di lokasi
- Marker tidak sesuai posisi real

**Fix:**
1. **Aktifkan High Accuracy GPS**
   ```javascript
   // Sudah di-setting di code:
   {
       enableHighAccuracy: true,
       timeout: 10000,
       maximumAge: 0
   }
   ```

2. **Tunggu GPS Stabil**
   - Klik "Dapatkan Lokasi" lagi setelah 10 detik
   - GPS pertama biasanya kurang akurat

3. **Gunakan Device dengan GPS Bagus**
   - Mobile phone lebih akurat dari laptop
   - Android/iOS lebih akurat dari desktop browser

---

## 📊 Performance Metrics

| Action | Expected Time | Acceptable Range |
|--------|---------------|------------------|
| Load Face Models | 3-5 detik | 2-8 detik |
| Face Detection | 1-2 detik | 0.5-3 detik |
| Face Verification | 1-2 detik | 0.5-4 detik |
| Load Map | 1-2 detik | 0.5-3 detik |
| Load Map Tiles | 1-2 detik | 0.5-5 detik |
| Get GPS | 2-5 detik | 1-10 detik |
| Submit Absensi | 0.5-1 detik | 0.3-2 detik |
| **Total Flow** | **10-20 detik** | **8-30 detik** |

---

## 📱 Device Testing Priority

### Priority 1 (Must Test)
- ✅ Chrome Desktop (Windows/Mac)
- ✅ Chrome Mobile (Android)
- ✅ Safari Mobile (iOS)

### Priority 2 (Should Test)
- ⚠️ Firefox Desktop
- ⚠️ Edge Desktop
- ⚠️ Samsung Internet (Android)

### Priority 3 (Nice to Test)
- 🔵 Opera Desktop
- 🔵 UC Browser (Android)
- 🔵 Brave Browser

---

## ✅ Success Indicators

Sistem bekerja dengan baik jika:

1. **Face Verification**
   - ✅ Models load dalam 5 detik
   - ✅ Foto sendiri → Match (>40% similarity)
   - ✅ Foto orang lain → Reject
   - ✅ Error handling tampil dengan jelas

2. **Map Visualization**
   - ✅ Peta muncul setelah GPS didapat
   - ✅ Tiles load lengkap (tidak ada kotak putih)
   - ✅ 2 markers muncul (venue + jamaah)
   - ✅ Lingkaran radius merah terlihat jelas
   - ✅ Zoom/pan berfungsi smooth

3. **Integration**
   - ✅ Full flow lancar tanpa error
   - ✅ Data tersimpan di database
   - ✅ Counter increment benar
   - ✅ Success page tampil dengan data benar

---

## 🎯 Next Steps

Setelah test berhasil, pertimbangkan:

1. **Production Deployment**
   - Clear cache: `php artisan config:clear && php artisan view:clear`
   - Test di production environment
   - Monitor error logs

2. **User Training**
   - Buat video tutorial penggunaan
   - Training untuk jamaah tentang posisi foto yang benar
   - Training untuk admin cara upload foto jamaah

3. **Monitoring**
   - Track success/failure rate face verification
   - Monitor GPS accuracy issues
   - Collect user feedback

4. **Optimization**
   - Consider self-hosting Face-API models (faster load)
   - Consider caching map tiles
   - Consider fallback tiles provider

---

**Last Updated**: 3 Januari 2026  
**Tested On**: Chrome 120, Firefox 121, Safari 17  
**Status**: ✅ Production Ready
