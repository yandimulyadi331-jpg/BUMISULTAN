# 🧪 CARA TES WA GATEWAY LOKAL (Tanpa Hosting)

## 📋 OVERVIEW

Dokumen ini menjelaskan cara **test fitur WhatsApp Gateway** di environment **localhost** tanpa perlu hosting/deploy ke VPS. 

Ada **2 pilihan metode**:
1. **Metode A (Rekomendasi)**: Menggunakan **Fonnte API** (mudah, cepat, tanpa setup server)
2. **Metode B**: Menggunakan **Baileys Server + Ngrok** (gratis, kontrol penuh, perlu setup)

---

## ✅ METODE A: FONNTE API (REKOMENDASI - PALING MUDAH)

### Kenapa Pilih Metode Ini?
- ✅ **Tidak perlu setup server** WhatsApp lokal
- ✅ **Tidak perlu ngrok** atau tunneling
- ✅ **Langsung bisa test** dalam 5 menit
- ✅ **Support group message** otomatis
- ✅ **Free trial** tersedia

### Langkah-Langkah:

#### 1. Daftar Akun Fonnte
1. Buka: https://fonnte.com
2. Klik **Register** / **Daftar**
3. Isi form pendaftaran
4. Verifikasi email
5. Login ke dashboard

#### 2. Dapatkan API Token
1. Setelah login, masuk ke menu **Settings** atau **API**
2. Copy **API Token** Anda (contoh: `abc123xyz+yourtoken`)
3. Simpan token ini

#### 3. Connect WhatsApp ke Fonnte
1. Di dashboard Fonnte, klik menu **Device** atau **Perangkat**
2. Klik **Add Device** / **Tambah Perangkat**
3. Scan QR Code yang muncul dengan WhatsApp di HP:
   - Buka WhatsApp di HP
   - Klik titik tiga (⋮) > **Linked Devices**
   - Klik **Link a Device**
   - Scan QR code di dashboard Fonnte

#### 4. Update Database Laravel
Buka database Anda (phpMyAdmin/TablePlus/HeidiSQL), jalankan query:

```sql
-- Update pengaturan umum
UPDATE pengaturan_umum 
SET 
    wagateway_url = 'https://api.fonnte.com',
    wagateway_key = 'abc123xyz+yourtoken'  -- GANTI dengan token Anda
WHERE id = 1;

-- Cek hasil
SELECT wagateway_url, wagateway_key FROM pengaturan_umum;
```

#### 5. Test Kirim Pesan Manual

Buka terminal PowerShell di folder project:

```powershell
php artisan tinker
```

Jalankan code berikut:

```php
use App\Jobs\SendWaMessage;

// Test kirim ke nomor pribadi (GANTI dengan nomor Anda)
SendWaMessage::dispatch('628123456789', 'Test WA Gateway dari Localhost!');

// Cek queue
exit

php artisan queue:work --once
```

**Cek WhatsApp Anda**, seharusnya pesan masuk! 🎉

#### 6. Test Notifikasi Absensi Otomatis

1. **Pastikan konfigurasi notif aktif**:
```sql
UPDATE pengaturan_umum 
SET 
    wa_notif_absen_aktif = 1,
    wa_notif_absen_tujuan = '628123456789@s.whatsapp.net'  -- GANTI nomor
WHERE id = 1;
```

2. **Jalankan Laravel server**:
```powershell
php artisan serve
```

3. **Buka browser**: http://localhost:8000

4. **Login** sebagai karyawan

5. **Klik tombol Absen Masuk** di dashboard

6. **Cek WhatsApp** - notifikasi harusnya masuk otomatis!

#### 7. Test Kirim ke Group WhatsApp

**Option 1: Via Tinker (Manual)**
```powershell
php artisan tinker
```

```php
use App\Jobs\SendWaMessage;

// Group JID format: 628123456789-1234567890@g.us
SendWaMessage::dispatch('628123456789-1234567890@g.us', 'Test ke grup!');

exit
```

**Option 2: Via Dashboard WhatsApp Gateway**

1. Buka: http://localhost:8000/whatsapp
2. Login sebagai super admin
3. Klik **Tambah Device** (isi nama device, contoh: "WA Testing")
4. Klik **Sync Groups** untuk ambil data grup dari WhatsApp
5. Pilih grup, ketik pesan, klik **Kirim**

---

## 🔧 METODE B: BAILEYS SERVER + NGROK (GRATIS, TANPA QUOTA)

### Kenapa Pilih Metode Ini?
- ✅ **Gratis 100%** (tidak ada biaya API)
- ✅ **Kontrol penuh** atas sistem
- ✅ **Tidak ada quota** limit
- ⚠️ Perlu setup lebih kompleks
- ⚠️ Perlu install Node.js + ngrok

### Persiapan Tools:

#### 1. Install Node.js
1. Download: https://nodejs.org/ (pilih versi LTS)
2. Jalankan installer
3. Restart terminal
4. Cek instalasi:
```powershell
node --version
npm --version
```

#### 2. Install Ngrok (Untuk Tunneling)
1. Download: https://ngrok.com/download
2. Extract file ZIP
3. Pindahkan `ngrok.exe` ke folder: `C:\Tools\ngrok\`
4. Daftar akun di https://dashboard.ngrok.com/signup
5. Copy **Authtoken** dari dashboard
6. Jalankan di PowerShell:
```powershell
C:\Tools\ngrok\ngrok.exe config add-authtoken YOUR_AUTH_TOKEN_HERE
```

### Langkah Setup:

#### 1. Setup Baileys WhatsApp Server

**Buat folder baru untuk server WA:**
```powershell
cd c:\Users\user\Desktop\
mkdir baileys-whatsapp-server
cd baileys-whatsapp-server
```

**Install dependencies:**
```powershell
npm init -y
npm install @whiskeysockets/baileys express qrcode-terminal cors
```

**Buat file `server.js`:**

```javascript
const { default: makeWASocket, DisconnectReason, useMultiFileAuthState } = require('@whiskeysockets/baileys');
const express = require('express');
const qrcode = require('qrcode-terminal');
const cors = require('cors');

const app = express();
app.use(express.json());
app.use(cors());

let sock;
let qrCodeData = null;
let isConnected = false;

async function connectWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('auth_info');
    
    sock = makeWASocket({
        auth: state,
        printQRInTerminal: false
    });
    
    sock.ev.on('creds.update', saveCreds);
    
    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;
        
        if (qr) {
            qrCodeData = qr;
            qrcode.generate(qr, { small: true });
            console.log('\n✅ QR Code generated! Scan dengan WhatsApp di HP');
        }
        
        if (connection === 'close') {
            const shouldReconnect = lastDisconnect?.error?.output?.statusCode !== DisconnectReason.loggedOut;
            console.log('⚠️ Connection closed. Reconnecting:', shouldReconnect);
            isConnected = false;
            if (shouldReconnect) {
                connectWhatsApp();
            }
        } else if (connection === 'open') {
            console.log('✅ WhatsApp connected!');
            isConnected = true;
            qrCodeData = null;
        }
    });
}

// API Status
app.get('/status', (req, res) => {
    res.json({ 
        connected: isConnected,
        hasQR: qrCodeData !== null
    });
});

// API Get QR
app.get('/qr', (req, res) => {
    if (qrCodeData) {
        res.json({ qr: qrCodeData });
    } else {
        res.status(404).json({ error: 'No QR code available' });
    }
});

// API Send Message
app.post('/send-message', async (req, res) => {
    const { number, message } = req.body;
    
    if (!sock || !isConnected) {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    
    try {
        const jid = number.includes('@') ? number : number + '@s.whatsapp.net';
        await sock.sendMessage(jid, { text: message });
        res.json({ success: true, message: 'Message sent' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// API Send to Group
app.post('/send-to-group', async (req, res) => {
    const { groupJid, message } = req.body;
    
    if (!sock || !isConnected) {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    
    try {
        await sock.sendMessage(groupJid, { text: message });
        res.json({ success: true, message: 'Message sent to group' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// API Fetch Groups
app.post('/fetch-groups', async (req, res) => {
    if (!sock || !isConnected) {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    
    try {
        const groups = await sock.groupFetchAllParticipating();
        const groupList = Object.values(groups).map(group => ({
            groupJid: group.id,
            groupName: group.subject,
            description: group.desc,
            totalMembers: group.participants.length
        }));
        
        res.json({ success: true, groups: groupList });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Start server
const PORT = 3000;
app.listen(PORT, async () => {
    console.log(`🚀 Baileys WhatsApp Server running on http://localhost:${PORT}`);
    console.log(`📱 Scan QR code dengan WhatsApp di HP`);
    await connectWhatsApp();
});
```

**Jalankan server:**
```powershell
node server.js
```

**Scan QR Code:**
- Buka WhatsApp di HP
- Klik titik tiga (⋮) > **Linked Devices**
- Klik **Link a Device**
- Scan QR code yang muncul di terminal

#### 2. Setup Ngrok Tunnel

**Buka terminal BARU (jangan tutup terminal server.js!)**

```powershell
# Jalankan ngrok untuk expose port 3000
C:\Tools\ngrok\ngrok.exe http 3000
```

**Copy URL ngrok** yang muncul (contoh: `https://abc123.ngrok-free.app`)

#### 3. Update Database Laravel

Jalankan query di database:

```sql
UPDATE pengaturan_umum 
SET 
    wagateway_url = 'https://abc123.ngrok-free.app',  -- GANTI dengan URL ngrok Anda
    wagateway_key = 'baileys-local'
WHERE id = 1;
```

#### 4. Update Laravel Code (Support Baileys)

Edit file `app/Jobs/SendWaMessage.php`, cari method `handle()`:

**Tambahkan kondisi untuk Baileys:**

```php
public function handle()
{
    try {
        $pengaturan = DB::table('pengaturan_umum')->first();
        
        if (!$pengaturan) {
            Log::error('[SendWaMessage] Pengaturan umum tidak ditemukan');
            return;
        }

        $url = $pengaturan->wagateway_url;
        $token = $pengaturan->wagateway_key;
        
        // Deteksi jika menggunakan Baileys (local server)
        $isBaileys = strpos($url, 'ngrok') !== false || strpos($token, 'baileys') !== false;
        
        if ($isBaileys) {
            // Mode Baileys
            $endpoint = rtrim($url, '/') . '/send-message';
            
            $response = Http::timeout(30)->post($endpoint, [
                'number' => $this->target,
                'message' => $this->message,
            ]);
            
            if ($response->successful()) {
                Log::info('[SendWaMessage] Berhasil kirim via Baileys', [
                    'target' => $this->target,
                    'response' => $response->json()
                ]);
            } else {
                throw new \Exception('Baileys error: ' . $response->body());
            }
        } else {
            // Mode Fonnte (existing code)
            $response = Http::timeout(30)->post('https://api.fonnte.com/send', [
                'target' => $this->target,
                'message' => $this->message,
                'token' => $token,
            ]);
            
            // ... rest of Fonnte code
        }
        
    } catch (\Exception $e) {
        Log::error('[SendWaMessage] Error: ' . $e->getMessage());
        throw $e;
    }
}
```

#### 5. Test Kirim Pesan

```powershell
php artisan tinker
```

```php
use App\Jobs\SendWaMessage;

// Test kirim ke nomor pribadi
SendWaMessage::dispatch('628123456789', 'Test WA Gateway Baileys + Ngrok!');

exit

php artisan queue:work --once
```

Cek WhatsApp, pesan harusnya masuk! 🎉

---

## 📊 PERBANDINGAN METODE

| Aspek | Metode A (Fonnte) | Metode B (Baileys + Ngrok) |
|-------|-------------------|----------------------------|
| **Kemudahan Setup** | ⭐⭐⭐⭐⭐ Sangat mudah | ⭐⭐⭐ Sedang |
| **Waktu Setup** | 5 menit | 30 menit |
| **Biaya** | Free trial, lalu berbayar | 100% gratis |
| **Quota Limit** | Ya (500-1000 pesan/hari) | Tidak ada limit |
| **Stabilitas** | ⭐⭐⭐⭐⭐ Sangat stabil | ⭐⭐⭐⭐ Stabil |
| **Maintenance** | Tidak perlu | Perlu restart kadang |
| **Rekomendasi** | ✅ **Testing cepat** | ✅ **Production gratis** |

---

## 🧪 CHECKLIST TESTING

Setelah setup selesai, test semua fitur:

### 1. Test Kirim Pesan Manual
- [ ] Kirim pesan ke nomor pribadi via Tinker
- [ ] Kirim pesan ke nomor lain
- [ ] Kirim pesan ke group WhatsApp

### 2. Test Notifikasi Absensi
- [ ] Absen masuk → cek notif WA masuk
- [ ] Absen pulang → cek notif WA masuk
- [ ] Cek format pesan (nama, waktu, lokasi)

### 3. Test Dashboard WA Gateway
- [ ] Akses: http://localhost:8000/whatsapp
- [ ] Tambah device baru
- [ ] Sync groups dari WhatsApp
- [ ] Kirim broadcast ke 1 grup
- [ ] Kirim broadcast ke multiple grup

### 4. Test Error Handling
- [ ] Matikan WhatsApp di HP → cek log error
- [ ] Kirim ke nomor invalid → cek response
- [ ] Cek retry mechanism (matikan internet, kirim pesan)

---

## 🔧 TROUBLESHOOTING

### Problem: Pesan tidak terkirim

**Solusi:**
1. Cek queue running:
```powershell
php artisan queue:work
```

2. Cek log Laravel:
```powershell
tail -f storage/logs/laravel.log
```

3. Cek konfigurasi database:
```sql
SELECT wagateway_url, wagateway_key FROM pengaturan_umum;
```

### Problem: Ngrok URL expired (Metode B)

**Penyebab**: Ngrok free tier nganti URL baru setiap restart.

**Solusi**:
1. Stop ngrok (Ctrl+C)
2. Jalankan lagi: `ngrok http 3000`
3. Update database dengan URL baru

**Alternatif**: Upgrade ke ngrok paid ($8/month) untuk URL tetap.

### Problem: WhatsApp disconnected (Metode B)

**Solusi:**
1. Stop server: Ctrl+C
2. Hapus folder `auth_info`
3. Jalankan lagi: `node server.js`
4. Scan QR code lagi

### Problem: "Cannot find module @whiskeysockets/baileys"

**Solusi:**
```powershell
npm install @whiskeysockets/baileys
```

---

## 💡 TIPS PRO

### 1. Testing Paralel (Baileys + Fonnte)
Anda bisa setup **KEDUA metode** sekaligus:
- Fonnte untuk production
- Baileys untuk testing/development

Tinggal switch konfigurasi database saja.

### 2. Auto-Start Queue Worker
Agar tidak manual run `queue:work` terus:

**Windows (PowerShell):**
```powershell
# Buat file start-queue.bat
echo "php artisan queue:work --sleep=3 --tries=3" > start-queue.bat

# Double klik file tersebut untuk start queue
```

**Linux (Supervisor):**
```bash
sudo apt install supervisor
# Edit /etc/supervisor/conf.d/laravel-worker.conf
# (lihat dokumentasi Laravel Queue)
```

### 3. Monitor Queue dengan Laravel Horizon
Install Horizon untuk monitoring queue real-time:
```powershell
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

Akses: http://localhost:8000/horizon

---

## 🎯 NEXT STEPS

Setelah berhasil test lokal:

1. ✅ **Pindah ke VPS Production**
   - Setup Baileys server dengan PM2
   - Ganti ngrok dengan domain/IP public
   - Setup SSL certificate (Let's Encrypt)

2. ✅ **Setup Monitoring**
   - Install Laravel Horizon
   - Setup log rotation
   - Alert email jika queue gagal

3. ✅ **Optimasi Performance**
   - Redis untuk queue (lebih cepat dari database)
   - Multiple queue workers
   - Rate limiting untuk avoid spam

---

## 📞 NEED HELP?

**Error yang tidak tercover di dokumentasi ini?**
- Kirim error log lengkap
- Screenshot error message
- Jelaskan step yang sudah dilakukan

**Contact Developer** atau buat issue di GitHub project.

---

**Selamat mencoba! Semoga berhasil test WA Gateway di localhost! 🚀**

*Dibuat: 2024 | Untuk: presensigpsv2-main | Metode: Fonnte API / Baileys + Ngrok*
