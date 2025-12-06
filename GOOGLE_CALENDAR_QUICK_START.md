# 🚀 QUICK START - Google Calendar Integration

## Setup dalam 5 Menit!

### 1️⃣ Buka Setup Helper
Akses URL: **http://localhost:8000/google-calendar/setup**

Halaman ini akan memandu Anda step-by-step.

---

### 2️⃣ Di Google Cloud Console

1. Klik tombol **"Configure consent screen"** yang muncul di screenshot Anda
2. Pilih:
   - **User Type**: `External` (atau Internal jika untuk organisasi Google Workspace)
   - Klik **Create**

3. Isi OAuth consent screen:
   - **App name**: `Bumi Sultan SuperApp`
   - **User support email**: Email admin Anda
   - **Developer contact**: Email admin Anda
   - Klik **Save and Continue**

4. Di halaman Scopes:
   - Klik **Add or Remove Scopes**
   - Cari dan centang: `Google Calendar API` → `.../auth/calendar`
   - Klik **Update** → **Save and Continue**

5. Di halaman Test users (jika External):
   - Klik **Add Users**
   - Masukkan email yang akan digunakan untuk testing
   - Klik **Save and Continue**

6. Review summary dan klik **Back to Dashboard**

---

### 3️⃣ Buat OAuth Client ID

1. Kembali ke **Credentials** → **Create Credentials** → **OAuth client ID**
2. Isi:
   - **Application type**: `Web application`
   - **Name**: `Bumi Sultan Calendar Integration`
   - **Authorized redirect URIs**: Klik **Add URI** dan masukkan:
     ```
     http://localhost:8000/google-calendar/callback
     http://127.0.0.1:8000/google-calendar/callback
     ```
3. Klik **Create**
4. Akan muncul popup dengan Client ID dan Client Secret
5. Klik **Download JSON** atau **OK**

---

### 4️⃣ Simpan Credentials

1. Download file JSON (biasanya bernama seperti `client_secret_xxx.json`)
2. Rename file menjadi `credentials.json`
3. Copy ke folder project:
   ```powershell
   copy "C:\Downloads\credentials.json" "C:\Users\user\Desktop\bumisultansuperapp_v2\presensigpsv2-main\storage\app\google-calendar\credentials.json"
   ```

---

### 5️⃣ Authorize di Browser

1. Buka: **http://localhost:8000/google-calendar/setup**
2. Klik tombol **"🔐 Authorize Google Calendar"**
3. Login dengan akun Google Anda
4. Akan muncul warning "Google hasn't verified this app" → Klik **Advanced** → **Go to Bumi Sultan SuperApp (unsafe)**
5. Klik **Allow** untuk semua permissions
6. Anda akan redirect kembali ke sistem dengan pesan sukses ✅

---

### 6️⃣ Test Fitur

1. Buka **Manajemen Administrasi** → **Tambah Administrasi**
2. Pilih jenis: **Undangan Masuk** atau **Undangan Keluar**
3. Isi detail acara:
   - Nama Acara
   - Tanggal & Waktu
   - Lokasi
4. **Centang** ✅ **"Sync ke Google Calendar"**
5. Isi **Email untuk Notifikasi**
6. Klik **Simpan**
7. Check Google Calendar Anda - Event sudah tercatat! 📅

---

## ✅ Selesai!

Sistem sekarang akan:
- ✅ Otomatis sync undangan ke Google Calendar
- ✅ Kirim email reminder H-3, H-2, H-1, dan Hari H
- ✅ Tracking status notifikasi

---

## 🔧 Troubleshooting

### Error: "credentials file not found"
- Pastikan file `credentials.json` ada di `storage/app/google-calendar/`
- Cek nama file (harus persis `credentials.json`, bukan `client_secret_xxx.json`)

### Error: "Redirect URI mismatch"
- Pastikan URI di Google Console sama persis: `http://localhost:8000/google-calendar/callback`
- Jangan ada trailing slash atau perbedaan port

### Error: "Access denied"
- Pastikan sudah add email sebagai test user di OAuth consent screen
- Coba logout dan login ulang di Google

---

**Need Help?** Check dokumentasi lengkap di `GOOGLE_CALENDAR_INTEGRATION_GUIDE.md`
