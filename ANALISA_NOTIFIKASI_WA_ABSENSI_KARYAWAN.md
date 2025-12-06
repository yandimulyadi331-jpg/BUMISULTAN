# 📱 ANALISA LENGKAP - NOTIFIKASI WA ABSENSI KARYAWAN

**Tanggal Analisa:** 28 November 2025  
**Sistem:** WA Gateway Notification System  
**Scope:** Notifikasi otomatis saat karyawan absensi masuk/pulang

---

## 🎯 EXECUTIVE SUMMARY

Sistem **SUDAH MEMILIKI** fitur notifikasi WhatsApp otomatis yang terintegrasi penuh dengan sistem absensi. Setiap kali karyawan melakukan absensi (masuk/pulang), sistem secara otomatis mengirim notifikasi ke:
1. **Grup WhatsApp kantor** (jika dikonfigurasi)
2. **WhatsApp pribadi karyawan** (jika dikonfigurasi)

---

## 📊 FLOW SISTEM NOTIFIKASI

```
Karyawan Absen (Web/Mobile)
         ↓
PresensiController::store()
         ↓
Simpan data presensi ke database
         ↓
Cek setting: notifikasi_wa == 1 ?
         ↓
    ┌────┴────┐
    ↓         ↓
 Grup WA   WA Pribadi
    ↓         ↓
SendWaMessage Job (Queue)
    ↓
Kirim ke WA Gateway API
    ↓
Notifikasi Terkirim ✅
```

---

## 🔧 KOMPONEN SISTEM

### 1. **Backend Controller**
**File:** `app/Http/Controllers/PresensiController.php`

#### A. Absensi Masuk (Line ~423)
```php
// Kirim Notifikasi Ke WA setelah absen masuk
if ($generalsetting->notifikasi_wa == 1) {
    if ($generalsetting->tujuan_notifikasi_wa == 1) {
        // Kirim ke WA pribadi karyawan
        if ($karyawan->no_hp != "") {
            $message = "Selamat pagi, Hari ini " . $karyawan->nama_karyawan . 
                       " sudah absen masuk pada " . $jam_presensi . 
                       ". Semangat Bekerja";
            $this->sendwa($karyawan->no_hp, $message);
        }
    } else {
        // Kirim ke grup WhatsApp kantor
        if (!empty($generalsetting->id_group_wa)) {
            $message = "Selamat pagi, Hari ini " . $karyawan->nama_karyawan . 
                       " sudah absen masuk pada " . $jam_presensi . 
                       ". Semangat Bekerja";
            $this->sendwa($generalsetting->id_group_wa, $message);
        }
    }
}
```

#### B. Absensi Pulang (Line ~535)
```php
// Kirim Notifikasi Ke WA setelah absen pulang
if ($generalsetting->notifikasi_wa == 1) {
    if ($generalsetting->tujuan_notifikasi_wa == 1) {
        // Kirim ke WA pribadi karyawan
        if ($karyawan->no_hp != "") {
            $message = "Terimakasih, Hari ini " . $karyawan->nama_karyawan . 
                       " absen Pulang pada " . $jam_presensi . 
                       "Hati Hati di Jalan";
            $this->sendwa($karyawan->no_hp, $message);
        }
    } else {
        // Kirim ke grup WhatsApp
        if (!empty($generalsetting->id_group_wa)) {
            $message = "Terimakasih, Hari ini " . $karyawan->nama_karyawan . 
                       " absen Pulang pada " . $jam_presensi . 
                       "Hati Hati di Jalan";
            $this->sendwa($generalsetting->id_group_wa, $message);
        }
    }
}
```

#### C. Helper Function
```php
function sendwa($no_hp, $message)
{
    // Safety: Validasi nomor HP tidak null/kosong sebelum dispatch job
    if (!empty($no_hp) && !empty($message)) {
        dispatch(new SendWaMessage($no_hp, $message));
    }
}
```

---

### 2. **Job Queue System**
**File:** `app/Jobs/SendWaMessage.php`

**Fitur Utama:**
- ✅ **Asynchronous**: Tidak memblokir proses absensi
- ✅ **Retry Mechanism**: 3x percobaan jika gagal
- ✅ **Dual Provider Support**: Fonnte & Custom Gateway
- ✅ **Logging**: Semua request/response dicatat

```php
class SendWaMessage implements ShouldQueue
{
    public int $tries = 3; // Retry 3x jika gagal
    
    protected string $phoneNumber;
    protected string $message;
    protected bool $birthday;
    
    public function handle(): void
    {
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        
        // Tentukan penerima: Grup WA atau WA Pribadi
        if ($this->birthday) {
            $penerima = $this->phoneNumber;
        } else {
            $penerima = $tujuanNotifikasi == 1 
                ? $generalsetting->id_group_wa 
                : $this->phoneNumber;
        }
        
        // Provider: Fonnte atau Custom Gateway
        if ($providerWa === 'fe') {
            // FONNTE API
            // ... kode implementasi Fonnte
        } else {
            // CUSTOM WA GATEWAY
            $url = rtrim($domain, '/') . '/send-message';
            $sender = Device::where('status', 1)->first();
            
            $payload = [
                'api_key' => $apiKey,
                'sender' => $sender->number,
                'number' => $penerima,
                'message' => $this->message,
            ];
            
            $response = Http::timeout(30)->asForm()->post($url, $payload);
        }
        
        // Logging lengkap untuk debugging
        Log::info('SendWaMessage: Gateway response', [
            'http' => $response->status(),
            'response' => $response->body(),
            'penerima' => $penerima,
            'message' => $this->message,
        ]);
    }
}
```

---

### 3. **WA Gateway Controller**
**File:** `app/Http/Controllers/WagatewayController.php`

**Fungsi Utama:**
1. **Manage Devices** - Tambah/hapus device WhatsApp
2. **Generate QR Code** - Untuk autentikasi WhatsApp
3. **Send Message** - Test kirim pesan
4. **Disconnect Device** - Logout device
5. **Fetch Groups** - Ambil daftar grup WhatsApp

**Key Features:**
```php
// Test Send Message
public function testSendMessage(Request $request)
{
    // Validasi & kirim pesan
    $response = Http::timeout(30)->post($apiUrl, $apiData);
    
    // Simpan log ke database
    Message::create([
        'pengirim' => $request->sender,
        'penerima' => $request->number,
        'pesan' => $request->message,
        'status' => $response->successful() ? 'success' : 'failed',
        'error_message' => ...
    ]);
}

// Fetch Groups dari WhatsApp
public function fetchGroups(Request $request)
{
    $apiUrl = $domain . '/api-fetch-groups';
    $response = Http::timeout(30)->post($apiUrl, $apiData);
    // Return list grup WhatsApp
}
```

---

## ⚙️ KONFIGURASI SISTEM

### Database: Tabel `pengaturan_umum`

| Field | Tipe | Deskripsi | Contoh |
|-------|------|-----------|--------|
| `notifikasi_wa` | `tinyint` | Enable/Disable notifikasi | `1` = ON, `0` = OFF |
| `tujuan_notifikasi_wa` | `tinyint` | Tujuan notifikasi | `1` = Grup WA, `0` = WA Pribadi |
| `id_group_wa` | `varchar` | ID Grup WhatsApp kantor | `628123456789-1234567890` |
| `domain_wa_gateway` | `varchar` | URL WA Gateway API | `https://md.fonnte.com/` |
| `wa_api_key` | `varchar` | API Key untuk gateway | `abc123...y4bb` |
| `provider_wa` | `varchar` | Provider WA Gateway | `fe` (Fonnte) atau `custom` |

### Database: Tabel `karyawan`

| Field | Deskripsi |
|-------|-----------|
| `no_hp` | Nomor WhatsApp karyawan (format: 628xxx) |
| `nama_karyawan` | Nama lengkap karyawan |

### Database: Tabel `devices`

| Field | Deskripsi |
|-------|-----------|
| `number` | Nomor WA yang digunakan sebagai sender |
| `status` | `1` = Aktif, `0` = Nonaktif (hanya 1 device aktif) |

### Database: Tabel `messages` (Log)

| Field | Deskripsi |
|-------|-----------|
| `pengirim` | Nomor WA sender |
| `penerima` | Nomor WA penerima atau ID grup |
| `pesan` | Isi pesan |
| `status` | `success` atau `failed` |
| `message_id` | ID pesan dari API (jika berhasil) |
| `error_message` | Error message (jika gagal) |
| `created_at` | Timestamp pengiriman |

---

## 🎛️ CARA KONFIGURASI

### 1. **Aktifkan Notifikasi WA**

**Menu:** Pengaturan → General Setting

```
☑ Enable Notifikasi WhatsApp
```

Set field `notifikasi_wa = 1` di database

---

### 2. **Pilih Tujuan Notifikasi**

**Opsi A: Kirim ke Grup WhatsApp Kantor**
```
◉ Kirim ke Grup WhatsApp
   ID Grup: [628xxx-123456789]
```
- Set `tujuan_notifikasi_wa = 0`
- Isi `id_group_wa` dengan ID grup WhatsApp

**Opsi B: Kirim ke WA Pribadi Karyawan**
```
◉ Kirim ke WhatsApp Pribadi Karyawan
```
- Set `tujuan_notifikasi_wa = 1`
- Pastikan field `no_hp` di tabel `karyawan` sudah terisi

---

### 3. **Setup WA Gateway**

**Menu:** WA Gateway (`/wagateway`)

#### A. Konfigurasi API
```
Domain WA Gateway: https://md.fonnte.com/
WA API Key: [your-api-key]
Provider: Fonnte / Custom
```

#### B. Tambah Device
1. Klik "Tambah Device"
2. Masukkan nomor WA sender (format: 628xxx)
3. Generate QR Code
4. Scan QR dengan WhatsApp
5. Device terhubung ✅

#### C. Test Notifikasi
```
Sender: 6281234567890
Penerima: 6289876543210
Pesan: Test notifikasi absensi
```

---

## 📱 CONTOH NOTIFIKASI

### Notifikasi Absen Masuk
```
Selamat pagi, Hari ini Ahmad Fauzi sudah absen masuk pada 08:15:32. Semangat Bekerja
```

### Notifikasi Absen Pulang
```
Terimakasih, Hari ini Ahmad Fauzi absen Pulang pada 17:30:15 Hati Hati di Jalan
```

---

## 🔍 FITUR TAMBAHAN YANG ADA

### 1. **Notifikasi Birthday** ✅
```php
SendWaMessage::dispatch($phoneNumber, $message, true);
```
- Kirim langsung ke WA pribadi karyawan
- Tidak terpengaruh setting `tujuan_notifikasi_wa`

### 2. **Real-time Notification** ✅
**File:** `app/Services/NotificationService.php`
```php
NotificationService::presensiNotification($presensi, 'masuk');
NotificationService::presensiNotification($presensi, 'pulang');
```
- Push notification ke dashboard real-time
- Independent dari WA notification

### 3. **Message History** ✅
**Menu:** WA Gateway → Messages
- View semua pesan yang terkirim
- Filter by status (success/failed)
- View error message jika gagal

### 4. **Device Management** ✅
- Multiple devices support
- Auto-select active device untuk kirim pesan
- QR Code generation untuk autentikasi
- Disconnect/logout device

### 5. **Group Management** ✅
```php
// Fetch semua grup WhatsApp
public function fetchGroups(Request $request)
```
- Ambil daftar grup dari WhatsApp
- Untuk memudahkan copy ID grup

---

## 🚀 FLOW LENGKAP PENGGUNAAN

### Setup Awal (One Time)

```
1. Setup WA Gateway
   ├── Masukkan domain & API key
   ├── Tambah device WhatsApp
   ├── Generate & scan QR code
   └── Device status: Connected ✅

2. Konfigurasi General Setting
   ├── Enable notifikasi_wa = 1
   ├── Pilih tujuan notifikasi
   │   ├── Grup WA: Isi id_group_wa
   │   └── WA Pribadi: Pastikan no_hp karyawan terisi
   └── Save ✅

3. Test Notifikasi
   ├── Gunakan fitur "Test Send Message"
   ├── Cek apakah pesan masuk
   └── Cek log di menu Messages
```

---

### Operasional Harian (Automatic)

```
Karyawan Absen Masuk (08:00)
    ↓
✅ Data tersimpan di database
    ↓
✅ Job queue: SendWaMessage
    ↓
✅ Notifikasi terkirim ke WA
    ↓
✅ Log tersimpan di tabel messages
    ↓
Manager/HRD terima notifikasi di grup: 
"Selamat pagi, Hari ini Ahmad Fauzi sudah absen masuk pada 08:00:15. Semangat Bekerja"
```

---

## 🛡️ SECURITY & VALIDATION

### 1. **Input Validation**
```php
// Validasi nomor HP tidak null/kosong
if (!empty($no_hp) && !empty($message)) {
    dispatch(new SendWaMessage($no_hp, $message));
}

// Validasi ID grup tidak null
if (!empty($generalsetting->id_group_wa)) {
    $this->sendwa($generalsetting->id_group_wa, $message);
}
```

### 2. **Error Handling**
```php
try {
    // Kirim pesan
} catch (\Exception $e) {
    Log::warning('SendWaMessage gagal', [
        'error' => $e->getMessage()
    ]);
    // Retry 3x otomatis
}
```

### 3. **Security Fix** (Sudah Diterapkan)
- ❌ Tidak tampilkan HTML error dari API external
- ✅ Generic error messages untuk user
- ✅ Detailed logging untuk admin/developer
- ✅ HTTPS untuk komunikasi dengan API

---

## 📊 MONITORING & LOGGING

### 1. **Application Log**
**File:** `storage/logs/laravel.log`

```
[2025-11-28 08:15:32] SendWaMessage: Gateway response
{
    "http": 200,
    "response": "{\"status\":\"success\",\"message_id\":\"xxxx\"}",
    "penerima": "628123456789-1234567890",
    "message": "Selamat pagi, Hari ini Ahmad Fauzi sudah absen masuk..."
}
```

### 2. **Database Log**
**Tabel:** `messages`

Query contoh:
```sql
-- Lihat pesan hari ini
SELECT * FROM messages 
WHERE DATE(created_at) = CURDATE() 
ORDER BY created_at DESC;

-- Lihat pesan gagal
SELECT * FROM messages 
WHERE status = 'failed' 
ORDER BY created_at DESC;

-- Statistik pengiriman
SELECT 
    DATE(created_at) as tanggal,
    status,
    COUNT(*) as jumlah
FROM messages
GROUP BY DATE(created_at), status;
```

---

## ⚠️ TROUBLESHOOTING

### Problem 1: Notifikasi Tidak Terkirim

**Checklist:**
1. ✅ `notifikasi_wa = 1` di general setting?
2. ✅ Device WhatsApp status connected?
3. ✅ `id_group_wa` atau `no_hp` sudah terisi?
4. ✅ WA Gateway API running?
5. ✅ API key valid?
6. ✅ Quota Fonnte masih ada?

**Debug:**
```bash
# Cek log Laravel
tail -f storage/logs/laravel.log

# Cek database messages
SELECT * FROM messages ORDER BY created_at DESC LIMIT 10;

# Test manual dari menu WA Gateway
```

---

### Problem 2: Device Disconnected

**Solusi:**
1. Buka menu WA Gateway
2. Pilih device yang disconnect
3. Klik "Generate QR Code"
4. Scan ulang dengan WhatsApp
5. Device reconnected ✅

---

### Problem 3: Pesan Terkirim tapi Format Salah

**Penyebab:** Template pesan di code

**Solusi:** Edit file `PresensiController.php`
```php
// Line ~423 & ~535
$message = "Custom template pesan Anda...";
```

---

## 📈 BEST PRACTICES

### 1. **Monitoring Rutin**
```
Daily:
- Cek status device (connected/disconnected)
- Review failed messages

Weekly:
- Analisa statistik pengiriman
- Cek quota API (jika pakai Fonnte)

Monthly:
- Backup log messages
- Review & optimize template pesan
```

### 2. **Template Pesan**
```
✅ Singkat & jelas
✅ Include: Nama, Jam, Status (masuk/pulang)
✅ Profesional & sopan
❌ Jangan terlalu panjang
❌ Hindari emoji berlebihan
```

### 3. **Queue Management**
```php
// Monitor queue
php artisan queue:work --queue=default

// Restart queue after deploy
php artisan queue:restart
```

---

## 🔄 ALTERNATIF PROVIDER

### Saat Ini Support:

#### 1. **Fonnte** (Provider `fe`)
- ✅ Mudah setup
- ✅ Stabil & cepat
- ✅ Support grup & broadcast
- ⚠️ Berbayar (quota system)

#### 2. **Custom Gateway** (Provider `custom`)
- ✅ Full control
- ✅ No quota limit
- ✅ Bisa self-hosted
- ⚠️ Butuh setup server sendiri

---

## 📝 KESIMPULAN

### ✅ FITUR YANG SUDAH ADA:

1. **Notifikasi Otomatis Absensi Masuk** ✅
   - Real-time saat karyawan absen masuk
   - Template: "Selamat pagi, ... sudah absen masuk..."

2. **Notifikasi Otomatis Absensi Pulang** ✅
   - Real-time saat karyawan absen pulang
   - Template: "Terimakasih, ... absen pulang..."

3. **Dual Target Support** ✅
   - Bisa kirim ke grup WhatsApp kantor
   - Bisa kirim ke WA pribadi karyawan
   - Configurable via admin panel

4. **Job Queue System** ✅
   - Asynchronous (tidak block UI)
   - Auto-retry 3x jika gagal
   - Complete error logging

5. **Device Management** ✅
   - Multiple devices support
   - QR Code authentication
   - Status monitoring

6. **Message History** ✅
   - Log semua pesan terkirim
   - Track success/failed status
   - Error message detail

7. **Dual Provider Support** ✅
   - Fonnte API
   - Custom WA Gateway

---

### 🎯 CARA AKTIVASI:

```
Step 1: Setup WA Gateway
  → Menu: /wagateway
  → Add device & scan QR code

Step 2: Konfigurasi General Setting
  → Enable notifikasi_wa
  → Pilih tujuan: Grup WA / WA Pribadi
  → Isi id_group_wa atau pastikan no_hp karyawan terisi

Step 3: Test & Monitor
  → Test send message
  → Lakukan absensi test
  → Cek messages log

✅ DONE! Notifikasi otomatis sudah jalan
```

---

### 📞 SUPPORT

**Dokumentasi Terkait:**
- `ANALISA_FINAL_WAGATEWAY.md` - Analisa WA Gateway
- `SETUP_BAILEYS_WHATSAPP.md` - Setup custom gateway

**Troubleshooting:**
- Check Laravel logs: `storage/logs/laravel.log`
- Check messages table: Database → messages
- Test manual: Menu WA Gateway → Test Send Message

---

**Dibuat oleh:** GitHub Copilot  
**Tanggal:** 28 November 2025  
**Version:** 1.0

---
