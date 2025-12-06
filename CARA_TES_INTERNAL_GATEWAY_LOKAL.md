# 🚀 CARA TES INTERNAL GATEWAY LOKAL (Sesuai Video Tutorial)

## 📋 OVERVIEW

Panduan ini khusus untuk test **Internal Gateway** yang sudah Anda setup di screenshot:
- ✅ Provider: **Internal Gateway**
- ✅ Domain: `http://127.0.0.1:8000/`
- ✅ WA API Key: `cwZaUTAdLmxw32hjy4bb`
- ✅ Notifikasi: Aktif, kirim ke Karyawan

**Internal Gateway** = WhatsApp server menggunakan **Baileys** yang berjalan lokal di komputer Anda (seperti video tutorial YouTube).

---

## 🎯 APA YANG PERLU DILAKUKAN?

Karena Anda menggunakan **Internal Gateway**, Anda perlu:

1. ✅ **Jalankan server Baileys WhatsApp** (di terminal terpisah)
2. ✅ **Scan QR Code** dengan HP WhatsApp
3. ✅ **Jalankan Laravel server** (`php artisan serve`)
4. ✅ **Test kirim pesan** dari Laravel

---

## 📦 STEP 1: SETUP BAILEYS WHATSAPP SERVER

### 1.1. Buat Folder Baileys Server

Buka terminal PowerShell **BARU** (jangan di folder project Laravel!):

```powershell
# Buat folder di Desktop
cd C:\Users\user\Desktop\
mkdir baileys-whatsapp
cd baileys-whatsapp
```

### 1.2. Install Dependencies

```powershell
# Init npm project
npm init -y

# Install Baileys & dependencies
npm install @whiskeysockets/baileys express qrcode-terminal cors body-parser
```

**Tunggu sampai selesai** (1-2 menit)

### 1.3. Buat File `server.js`

Buat file baru `server.js` dengan isi:

```javascript
const { default: makeWASocket, DisconnectReason, useMultiFileAuthState } = require('@whiskeysockets/baileys');
const express = require('express');
const qrcode = require('qrcode-terminal');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

let sock;
let qrCodeData = null;
let isConnected = false;

// Function untuk connect WhatsApp
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
            console.log('\n📱 ===================================');
            console.log('   SCAN QR CODE INI DENGAN WHATSAPP');
            console.log('=====================================\n');
            qrcode.generate(qr, { small: true });
            console.log('\n📱 Cara scan:');
            console.log('1. Buka WhatsApp di HP');
            console.log('2. Klik titik tiga (⋮) > Linked Devices');
            console.log('3. Klik "Link a Device"');
            console.log('4. Scan QR code di atas\n');
        }
        
        if (connection === 'close') {
            const shouldReconnect = lastDisconnect?.error?.output?.statusCode !== DisconnectReason.loggedOut;
            console.log('⚠️  Connection closed. Reconnecting:', shouldReconnect);
            isConnected = false;
            if (shouldReconnect) {
                setTimeout(() => connectWhatsApp(), 3000);
            }
        } else if (connection === 'open') {
            console.log('\n✅ =============================');
            console.log('   WHATSAPP BERHASIL CONNECT!');
            console.log('   Server siap menerima pesan');
            console.log('===============================\n');
            isConnected = true;
            qrCodeData = null;
        }
    });
}

// Endpoint: Status connection
app.get('/status', (req, res) => {
    res.json({ 
        connected: isConnected,
        hasQR: qrCodeData !== null,
        timestamp: new Date().toISOString()
    });
});

// Endpoint: Get QR Code
app.get('/qr', (req, res) => {
    if (qrCodeData) {
        res.json({ qr: qrCodeData });
    } else {
        res.status(404).json({ error: 'No QR code available' });
    }
});

// Endpoint: Create Device (untuk kompatibilitas dengan Laravel)
app.post('/create-device', (req, res) => {
    console.log('📝 Request create-device:', req.body);
    
    if (!isConnected) {
        return res.status(400).json({ 
            success: false,
            error: 'WhatsApp not connected yet. Please scan QR code first.' 
        });
    }
    
    res.json({ 
        success: true,
        message: 'Device already connected',
        sender: req.body.sender || 'default'
    });
});

// Endpoint: Send Message (format Laravel)
app.post('/send-message', async (req, res) => {
    console.log('\n📤 Incoming send-message request:', req.body);
    
    const { number, message, sender, api_key } = req.body;
    
    // Validasi API Key
    if (api_key !== 'cwZaUTAdLmxw32hjy4bb') {
        console.log('❌ Invalid API Key:', api_key);
        return res.status(401).json({ 
            success: false,
            error: 'Invalid API Key' 
        });
    }
    
    if (!sock || !isConnected) {
        console.log('❌ WhatsApp not connected');
        return res.status(400).json({ 
            success: false,
            error: 'WhatsApp not connected' 
        });
    }
    
    if (!number || !message) {
        console.log('❌ Missing number or message');
        return res.status(400).json({ 
            success: false,
            error: 'Number and message are required' 
        });
    }
    
    try {
        // Format nomor WhatsApp
        let jid = number;
        
        // Jika sudah ada @g.us (group) atau @s.whatsapp.net (individual), pakai langsung
        if (!jid.includes('@')) {
            // Hapus karakter non-digit
            jid = jid.replace(/\D/g, '');
            
            // Tambahkan @s.whatsapp.net untuk nomor individual
            jid = jid + '@s.whatsapp.net';
        }
        
        console.log('📱 Sending to:', jid);
        console.log('💬 Message:', message);
        
        await sock.sendMessage(jid, { text: message });
        
        console.log('✅ Message sent successfully!\n');
        
        res.json({ 
            success: true, 
            message: 'Message sent successfully',
            recipient: jid
        });
    } catch (error) {
        console.error('❌ Error sending message:', error.message);
        res.status(500).json({ 
            success: false,
            error: error.message 
        });
    }
});

// Endpoint: Send to Group
app.post('/send-to-group', async (req, res) => {
    const { groupJid, message } = req.body;
    
    if (!sock || !isConnected) {
        return res.status(400).json({ 
            success: false,
            error: 'WhatsApp not connected' 
        });
    }
    
    try {
        await sock.sendMessage(groupJid, { text: message });
        console.log('✅ Group message sent to:', groupJid);
        res.json({ success: true, message: 'Message sent to group' });
    } catch (error) {
        console.error('❌ Error sending group message:', error.message);
        res.status(500).json({ 
            success: false,
            error: error.message 
        });
    }
});

// Endpoint: Fetch Groups
app.post('/fetch-groups', async (req, res) => {
    if (!sock || !isConnected) {
        return res.status(400).json({ 
            success: false,
            error: 'WhatsApp not connected' 
        });
    }
    
    try {
        const groups = await sock.groupFetchAllParticipating();
        const groupList = Object.values(groups).map(group => ({
            groupJid: group.id,
            groupName: group.subject,
            description: group.desc,
            totalMembers: group.participants.length
        }));
        
        console.log('✅ Fetched', groupList.length, 'groups');
        res.json({ success: true, groups: groupList });
    } catch (error) {
        console.error('❌ Error fetching groups:', error.message);
        res.status(500).json({ 
            success: false,
            error: error.message 
        });
    }
});

// Start server
const PORT = 8000;
app.listen(PORT, async () => {
    console.log('\n🚀 ================================');
    console.log('   BAILEYS WHATSAPP SERVER');
    console.log('   Running on http://127.0.0.1:' + PORT);
    console.log('==================================\n');
    console.log('⏳ Connecting to WhatsApp...\n');
    await connectWhatsApp();
});
```

**Cara buat file:**
1. Buka Notepad
2. Copy paste code di atas
3. Save As > `server.js` > Save

---

## ▶️ STEP 2: JALANKAN BAILEYS SERVER

Di terminal yang sama (folder `baileys-whatsapp`):

```powershell
node server.js
```

**Output yang muncul:**
```
🚀 ================================
   BAILEYS WHATSAPP SERVER
   Running on http://127.0.0.1:8000
==================================

⏳ Connecting to WhatsApp...

📱 ===================================
   SCAN QR CODE INI DENGAN WHATSAPP
=====================================

█▀▀▀▀▀█ ... (QR CODE)
...

📱 Cara scan:
1. Buka WhatsApp di HP
2. Klik titik tiga (⋮) > Linked Devices
3. Klik "Link a Device"
4. Scan QR code di atas
```

### Scan QR Code:

1. Buka **WhatsApp di HP**
2. Klik **titik tiga (⋮)** > **Linked Devices**
3. Klik **Link a Device**
4. **Scan QR code** yang muncul di terminal

**Setelah scan berhasil, akan muncul:**
```
✅ =============================
   WHATSAPP BERHASIL CONNECT!
   Server siap menerima pesan
===============================
```

**⚠️ JANGAN TUTUP TERMINAL INI!** Server harus tetap running.

---

## 🧪 STEP 3: TEST KIRIM PESAN DARI LARAVEL

### 3.1. Buka Terminal PowerShell BARU

Buka terminal **BARU** untuk Laravel (folder project):

```powershell
cd C:\Users\user\Desktop\bumisultansuperapp_v2\presensigpsv2-main
```

### 3.2. Test Kirim Pesan dengan Tinker

```powershell
php artisan tinker
```

Di tinker, jalankan:

```php
use App\Jobs\SendWaMessage;

// GANTI 628123456789 dengan nomor WhatsApp Anda
SendWaMessage::dispatch('628123456789', 'Test dari Internal Gateway!');

// Keluar dari tinker
exit
```

### 3.3. Jalankan Queue Worker

```powershell
php artisan queue:work --once
```

**Lihat terminal Baileys server**, akan muncul:

```
📤 Incoming send-message request: { ... }
📱 Sending to: 628123456789@s.whatsapp.net
💬 Message: Test dari Internal Gateway!
✅ Message sent successfully!
```

**Cek WhatsApp di HP Anda**, pesan harusnya masuk! 🎉

---

## 🔥 STEP 4: TEST NOTIFIKASI ABSENSI OTOMATIS

### 4.1. Pastikan Konfigurasi Database Benar

Buka database (phpMyAdmin/HeidiSQL/TablePlus), jalankan query:

```sql
-- Cek konfigurasi saat ini
SELECT 
    provider_wa,
    domain_wa_gateway,
    wa_api_key,
    wa_notif_absen_aktif,
    tujuan_notifikasi_wa
FROM pengaturan_umum 
WHERE id = 1;
```

**Hasilnya harus:**
- `provider_wa` = `ig` (Internal Gateway)
- `domain_wa_gateway` = `http://127.0.0.1:8000/`
- `wa_api_key` = `cwZaUTAdLmxw32hjy4bb`
- `wa_notif_absen_aktif` = `1` (Aktif)
- `tujuan_notifikasi_wa` = `2` (Kirim ke Karyawan individual)

**Jika belum sesuai, update:**

```sql
UPDATE pengaturan_umum 
SET 
    provider_wa = 'ig',
    domain_wa_gateway = 'http://127.0.0.1:8000/',
    wa_api_key = 'cwZaUTAdLmxw32hjy4bb',
    wa_notif_absen_aktif = 1,
    tujuan_notifikasi_wa = 2
WHERE id = 1;
```

### 4.2. Pastikan Karyawan Punya Nomor WhatsApp

```sql
-- Cek nomor WA karyawan
SELECT nik, nama, no_wa FROM karyawan WHERE no_wa IS NOT NULL LIMIT 5;

-- Jika no_wa kosong, update dengan nomor Anda untuk testing
UPDATE karyawan 
SET no_wa = '628123456789'  -- GANTI dengan nomor Anda
WHERE nik = '001';  -- GANTI dengan NIK Anda
```

### 4.3. Jalankan Laravel Server

Di terminal Laravel:

```powershell
php artisan serve
```

Buka browser: http://127.0.0.1:8000

### 4.4. Login & Test Absen

1. **Login** dengan akun karyawan yang nomor WA-nya sudah diisi
2. Klik tombol **Absen Masuk**
3. **Lihat terminal Baileys server**, harusnya ada log kirim pesan
4. **Cek WhatsApp**, notifikasi harusnya masuk!

**Format notif:**

```
🟢 ABSEN MASUK
Nama: [Nama Karyawan]
NIK: [NIK]
Waktu: 28-11-2024 08:30:15
Lokasi: Bumi Sultan
```

---

## 📊 MONITORING & TROUBLESHOOTING

### Cek Status Baileys Server

Buka browser baru, akses: http://127.0.0.1:8000/status

Response:
```json
{
  "connected": true,
  "hasQR": false,
  "timestamp": "2024-11-28T..."
}
```

- `connected: true` = WhatsApp tersambung ✅
- `connected: false` = WhatsApp putus, scan QR lagi ❌

### Cek Log Laravel

```powershell
# Di folder project
tail -f storage/logs/laravel.log
```

atau buka file `storage/logs/laravel.log` dengan Notepad.

### Problem: Pesan tidak terkirim

**Cek 1: Baileys server running?**
```powershell
# Cek di terminal Baileys server, harusnya ada tulisan:
✅ WHATSAPP BERHASIL CONNECT!
```

**Cek 2: WhatsApp masih connect?**
```
http://127.0.0.1:8000/status
```

Jika `connected: false`, restart Baileys server:
1. Tekan `Ctrl+C` di terminal Baileys
2. Jalankan lagi: `node server.js`
3. Scan QR code lagi

**Cek 3: Queue worker running?**
```powershell
php artisan queue:work
```

Biarkan running terus, jangan `Ctrl+C`.

**Cek 4: API Key benar?**

Di file `server.js` line 78:
```javascript
if (api_key !== 'cwZaUTAdLmxw32hjy4bb') {
```

Pastikan sama dengan database:
```sql
SELECT wa_api_key FROM pengaturan_umum WHERE id = 1;
```

### Problem: QR Code tidak muncul

**Solusi:**
```powershell
# Stop server: Ctrl+C
# Hapus folder auth
rm -r auth_info
# Jalankan lagi
node server.js
```

### Problem: WhatsApp disconnected terus

**Penyebab:**
- Internet tidak stabil
- WhatsApp di HP logout
- Terlalu banyak pesan dikirim (spam)

**Solusi:**
1. Restart Baileys server
2. Scan QR code lagi
3. Jangan kirim > 10 pesan per menit

---

## 🎯 CHECKLIST TESTING LENGKAP

### ✅ Test Manual (Via Tinker)
- [ ] Kirim pesan ke nomor pribadi
- [ ] Kirim pesan ke nomor lain
- [ ] Kirim pesan ke group WhatsApp (format: `628xxx-1234567890@g.us`)

### ✅ Test Notifikasi Absensi
- [ ] Absen masuk → notif WA masuk
- [ ] Absen pulang → notif WA masuk
- [ ] Format pesan sesuai (nama, NIK, waktu, lokasi)

### ✅ Test Error Handling
- [ ] Matikan Baileys server → error log muncul
- [ ] Kirim ke nomor invalid → error response
- [ ] Matikan WhatsApp di HP → reconnect otomatis

### ✅ Test Dashboard WA Gateway
- [ ] Akses: http://127.0.0.1:8000/wagateway
- [ ] Tambah device (harusnya berhasil karena WhatsApp sudah connect)
- [ ] Test kirim pesan dari dashboard

---

## 💡 TIPS PRO

### 1. Auto-Start Queue Worker (Windows)

Buat file `start-queue.bat`:

```batch
@echo off
:loop
php artisan queue:work --sleep=3 --tries=3
goto loop
```

Double klik file tersebut, queue akan auto-restart jika crash.

### 2. Auto-Start Baileys Server (PM2 Alternative)

Install `pm2` untuk Windows:

```powershell
npm install -g pm2
pm2 start server.js --name baileys-wa
pm2 save
```

Setiap restart Windows, jalankan:
```powershell
pm2 resurrect
```

### 3. Multiple Device (Untuk Testing)

Jika ingin test dengan 2 nomor WhatsApp berbeda:

1. Buat folder `baileys-whatsapp-2`
2. Copy `server.js`
3. Ganti `PORT = 8000` jadi `PORT = 8001`
4. Ganti API Key di code
5. Jalankan di terminal terpisah

### 4. Backup Auth Session

Folder `auth_info` berisi session WhatsApp, **BACKUP** secara berkala:

```powershell
# Backup
cp -r auth_info auth_info_backup_$(Get-Date -Format 'yyyyMMdd')

# Restore (jika WhatsApp logout)
rm -r auth_info
cp -r auth_info_backup_20241128 auth_info
```

---

## 🚀 PRODUCTION DEPLOYMENT (Opsional)

Jika sudah siap hosting:

### Option 1: Deploy ke VPS (Ubuntu)

```bash
# SSH ke VPS
ssh root@your-vps-ip

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install PM2
npm install -g pm2

# Clone/upload server.js
cd /var/www/baileys-whatsapp
npm install

# Jalankan
pm2 start server.js --name baileys
pm2 startup
pm2 save
```

### Option 2: Deploy dengan Ngrok (Untuk Testing Remote)

```powershell
# Download ngrok: https://ngrok.com/download
# Jalankan ngrok
C:\Tools\ngrok\ngrok.exe http 8000

# Copy URL (contoh: https://abc123.ngrok-free.app)
# Update database:
```

```sql
UPDATE pengaturan_umum 
SET domain_wa_gateway = 'https://abc123.ngrok-free.app/'
WHERE id = 1;
```

Sekarang bisa diakses dari internet!

---

## 🔐 SECURITY NOTES

### 1. Ganti API Key Default

Edit `server.js` line 78:

```javascript
if (api_key !== 'YOUR_RANDOM_KEY_HERE') {  // Ganti dengan key random
```

Update database:
```sql
UPDATE pengaturan_umum 
SET wa_api_key = 'YOUR_RANDOM_KEY_HERE'
WHERE id = 1;
```

### 2. Jangan Share Auth Folder

Folder `auth_info` berisi session WhatsApp, **JANGAN** di-upload ke GitHub!

Tambahkan ke `.gitignore`:
```
baileys-whatsapp/auth_info/
```

### 3. Rate Limiting (Avoid Ban)

WhatsApp bisa ban nomor jika spam. **Batasi:**
- Max 10 pesan/menit
- Max 500 pesan/hari
- Delay 3-5 detik antar pesan

---

## 📞 BUTUH BANTUAN?

**Error tidak tercover di dokumentasi?**

1. Lihat log Baileys server (di terminal)
2. Lihat log Laravel (`storage/logs/laravel.log`)
3. Screenshot error
4. Kirim ke developer atau buat issue

**Contact:**
- Email: developer@example.com
- WhatsApp: +62...

---

## 🎉 SELAMAT!

Anda berhasil setup **Internal Gateway** untuk test lokal!

**Next Steps:**
1. ✅ Test semua fitur notifikasi
2. ✅ Deploy ke VPS (production)
3. ✅ Setup monitoring & alert
4. ✅ Backup auth session berkala

**Selamat mencoba! 🚀**

---

*Dibuat: 28 November 2024 | Untuk: presensigpsv2-main | Sesuai: Video Tutorial YouTube*
