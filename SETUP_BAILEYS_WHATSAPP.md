# 🚀 SETUP BAILEYS SERVER - PANDUAN LENGKAP

## ✅ YANG SUDAH SELESAI DI LARAVEL

1. ✅ **Database**: 10 tabel WhatsApp sudah dibuat
2. ✅ **Models**: WaDevice, WaContact, WaGroup, WaMessage, WaBroadcast, dll
3. ✅ **Controller**: WhatsAppController dengan semua method
4. ✅ **Routes**: `/whatsapp/*` sudah terdaftar (super admin only)
5. ✅ **Views**: Dashboard, Devices, Broadcasts, Contacts, Templates
6. ✅ **Menu**: Sidebar "WhatsApp" setelah "KPI Crew"

## 📋 NEXT STEP: SETUP BAILEYS SERVER

### **OPTION A: Local Development (Windows)**

#### 1. Install Node.js
```powershell
# Download Node.js 18+ dari: https://nodejs.org/
# Verifikasi instalasi:
node --version
npm --version
```

#### 2. Buat Folder Baileys Server
```powershell
cd C:\Users\user\Desktop\bumisultansuperapp_v2
mkdir baileys-server
cd baileys-server
```

#### 3. Install Baileys & Dependencies
```powershell
npm init -y
npm install @whiskeysockets/baileys
npm install express cors
npm install qrcode-terminal
npm install pino
```

#### 4. Buat File `server.js`
```javascript
const { default: makeWASocket, useMultiFileAuthState, DisconnectReason } = require('@whiskeysockets/baileys');
const express = require('express');
const cors = require('cors');
const qrcode = require('qrcode-terminal');
const P = require('pino');

const app = express();
app.use(cors());
app.use(express.json());

let sock = null;
let qrCodeData = null;

// Logger
const logger = P({ level: 'silent' });

// Connect WhatsApp
async function connectWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('auth_info');
    
    sock = makeWASocket({
        auth: state,
        printQRInTerminal: true,
        logger
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;
        
        if (qr) {
            qrCodeData = qr;
            console.log('QR Code generated!');
        }
        
        if (connection === 'close') {
            const shouldReconnect = lastDisconnect?.error?.output?.statusCode !== DisconnectReason.loggedOut;
            console.log('Connection closed, reconnecting:', shouldReconnect);
            if (shouldReconnect) {
                connectWhatsApp();
            }
        } else if (connection === 'open') {
            console.log('WhatsApp connected!');
            qrCodeData = null;
        }
    });

    sock.ev.on('messages.upsert', async (m) => {
        console.log('New message:', m.messages);
        // TODO: Forward to Laravel webhook
    });
}

// API Endpoints
app.get('/status', (req, res) => {
    res.json({
        status: sock ? 'connected' : 'disconnected',
        hasQR: qrCodeData !== null
    });
});

app.get('/qr', (req, res) => {
    if (qrCodeData) {
        res.json({ qr: qrCodeData });
    } else {
        res.status(404).json({ error: 'No QR code available' });
    }
});

app.post('/send-message', async (req, res) => {
    const { number, message } = req.body;
    
    if (!sock) {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    
    try {
        const jid = number + '@s.whatsapp.net';
        await sock.sendMessage(jid, { text: message });
        res.json({ success: true, message: 'Message sent' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.post('/send-to-group', async (req, res) => {
    const { groupJid, message } = req.body;
    
    if (!sock) {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    
    try {
        await sock.sendMessage(groupJid, { text: message });
        res.json({ success: true, message: 'Message sent to group' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.post('/fetch-groups', async (req, res) => {
    if (!sock) {
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

app.post('/broadcast-to-groups', async (req, res) => {
    const { group_jids, message } = req.body;
    
    if (!sock) {
        return res.status(400).json({ error: 'WhatsApp not connected' });
    }
    
    const results = [];
    
    for (const groupJid of group_jids) {
        try {
            // Delay 5 detik per grup (avoid spam)
            await new Promise(resolve => setTimeout(resolve, 5000));
            
            await sock.sendMessage(groupJid, { text: message });
            results.push({ groupJid, status: 'sent', sentAt: new Date() });
        } catch (error) {
            results.push({ groupJid, status: 'failed', error: error.message });
        }
    }
    
    res.json(results);
});

// Start server
const PORT = 3000;
app.listen(PORT, async () => {
    console.log(`Baileys server running on http://localhost:${PORT}`);
    await connectWhatsApp();
});
```

#### 5. Jalankan Server
```powershell
node server.js
```

#### 6. Scan QR Code
- Buka WhatsApp di HP
- Klik titik tiga > Linked Devices
- Scan QR code yang muncul di terminal

#### 7. Update Laravel `.env`
```env
BAILEYS_API_URL=http://localhost:3000
```

---

### **OPTION B: VPS Production (Ubuntu)**

#### 1. Login ke VPS & Install Node.js
```bash
ssh root@your-vps-ip
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

#### 2. Install PM2 (Process Manager)
```bash
npm install -g pm2
```

#### 3. Clone/Upload Baileys Server
```bash
cd /var/www/
mkdir baileys-server
cd baileys-server
# Upload file server.js + package.json
npm install
```

#### 4. Jalankan dengan PM2
```bash
pm2 start server.js --name baileys
pm2 save
pm2 startup
```

#### 5. Setup Nginx Reverse Proxy (Optional)
```nginx
server {
    listen 80;
    server_name baileys.yourdomain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

#### 6. Update Laravel `.env`
```env
BAILEYS_API_URL=http://baileys.yourdomain.com
# Atau
BAILEYS_API_URL=http://your-vps-ip:3000
```

---

## 🧪 TESTING

### 1. Test Status API
```powershell
curl http://localhost:3000/status
```

### 2. Test Send Message dari Laravel
Di Laravel Tinker:
```php
php artisan tinker

use Illuminate\Support\Facades\Http;

$response = Http::post('http://localhost:3000/send-message', [
    'number' => '628123456789',
    'message' => 'Test dari Laravel!'
]);

dd($response->json());
```

### 3. Test Broadcast ke Grup
```php
$response = Http::post('http://localhost:3000/broadcast-to-groups', [
    'group_jids' => ['62812xxx-1234567890@g.us'],
    'message' => 'Halo grup!'
]);
```

---

## 🔧 TROUBLESHOOTING

### Error: "Cannot find module @whiskeysockets/baileys"
```powershell
npm install @whiskeysockets/baileys
```

### Error: Port 3000 sudah digunakan
Ganti port di `server.js`:
```javascript
const PORT = 3001; // Atau port lain
```

### QR Code tidak muncul
```powershell
# Install ulang qrcode-terminal
npm install qrcode-terminal
```

### WhatsApp disconnected terus
- Pastikan internet stabil
- Jangan logout dari WhatsApp di HP
- Gunakan PM2 di production untuk auto-restart

---

## 📊 MONITORING

### Cek Logs (PM2)
```bash
pm2 logs baileys
```

### Restart Service
```bash
pm2 restart baileys
```

### Stop Service
```bash
pm2 stop baileys
```

---

## 🎯 NEXT ACTIONS

1. ✅ Setup Baileys server (pilih Option A atau B)
2. ✅ Scan QR code & connect WhatsApp
3. ✅ Test kirim pesan via Postman/Curl
4. ✅ Akses dashboard: http://localhost:8000/whatsapp
5. ✅ Tambah device dari UI
6. ✅ Sync groups dari WhatsApp
7. ✅ Buat broadcast pertama!

---

## 💡 TIPS

- Gunakan nomor WhatsApp khusus (bukan personal)
- Backup folder `auth_info` secara berkala
- Monitor logs untuk detect error
- Test dengan 1-2 penerima dulu sebelum broadcast besar
- Jaga agar tidak spam (max 500 pesan/hari per device)

---

**Selamat! Sistem WhatsApp Anda siap digunakan!** 🎉

*Butuh bantuan? Hubungi developer*
