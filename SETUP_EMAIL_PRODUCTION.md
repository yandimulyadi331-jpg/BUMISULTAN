# 🚀 Setup Email untuk Production/Hosting

## 📋 Perbedaan Development vs Production

### **Development (Sekarang):**
```env
APP_URL=http://localhost
APP_NAME=Laravel
MAIL_FROM_ADDRESS=manajemenbumisultan@gmail.com
```

### **Production (Setelah Hosting):**
```env
APP_URL=https://sistem.bumisultan.com
APP_NAME="PT Bumi Sultan"
MAIL_FROM_ADDRESS=noreply@bumisultan.com
```

---

## 🎨 Tampilan Email di Production

### **1. Subjek Email akan lebih profesional:**
```
Development: 🔔 Pinjaman Jatuh Tempo HARI INI
Production:  🔔 PT Bumi Sultan - Pinjaman Jatuh Tempo HARI INI
```

### **2. Sender/Pengirim:**
```
Development: manajemenbumisultan@gmail.com
Production:  noreply@bumisultan.com (atau info@bumisultan.com)
```

### **3. Link Button "Login ke Sistem":**
```
Development: http://localhost:8000
Production:  https://sistem.bumisultan.com
```

### **4. Footer Email:**
```
Development: http://localhost
Production:  https://sistem.bumisultan.com
```

---

## 🌐 Cara Setup di Production

### **Step 1: Update .env di Server**

```env
# ============================================
# PRODUCTION CONFIGURATION
# ============================================

APP_NAME="PT Bumi Sultan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sistem.bumisultan.com

# ============================================
# EMAIL CONFIGURATION (PRODUCTION)
# ============================================

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@bumisultan.com
MAIL_PASSWORD=your-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bumisultan.com
MAIL_FROM_NAME="PT Bumi Sultan - Sistem Pinjaman"
```

---

### **Step 2: (RECOMMENDED) Gunakan Email Domain Sendiri**

#### **Keuntungan pakai domain sendiri:**
✅ Lebih profesional: `noreply@bumisultan.com`  
✅ Tidak kena limit Gmail (500 email/hari)  
✅ Tidak masuk SPAM  
✅ Branding perusahaan lebih kuat  

#### **Setup Email Domain:**

**a. Pakai cPanel/Hosting:**
```env
MAIL_HOST=mail.bumisultan.com
MAIL_PORT=587
MAIL_USERNAME=noreply@bumisultan.com
MAIL_PASSWORD=password-cpanel-email
MAIL_ENCRYPTION=tls
```

**b. Pakai Google Workspace (Recommended):**
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@bumisultan.com  # Email Google Workspace
MAIL_PASSWORD=app-password-google
MAIL_ENCRYPTION=tls
```

**c. Pakai SMTP Service (Mailgun, SendGrid, AWS SES):**
```env
# Mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@mg.bumisultan.com
MAIL_PASSWORD=mailgun-password

# SendGrid
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=sendgrid-api-key

# AWS SES
MAIL_HOST=email-smtp.us-east-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=aws-smtp-username
MAIL_PASSWORD=aws-smtp-password
```

---

## 🎨 Contoh Tampilan Email Production

### **Subject:**
```
🔔 PT Bumi Sultan - Pinjaman Jatuh Tempo HARI INI
```

### **From:**
```
PT Bumi Sultan - Sistem Pinjaman <noreply@bumisultan.com>
```

### **Body (Header):**
```
┌─────────────────────────────────────────┐
│  🏢 PT BUMI SULTAN                      │
│  Sistem Manajemen Pinjaman              │
└─────────────────────────────────────────┘

Pengingat Pembayaran Cicilan Pinjaman

HARI INI adalah tanggal jatuh tempo cicilan pinjaman Anda.
```

### **Body (Detail):**
```
┌─────────────────────┬────────────────────┐
│ Nomor Pinjaman      │ PNJ-202511-0001    │
│ Nama Peminjam       │ Budi Santoso       │
│ Cicilan Per Bulan   │ Rp 1.000.000       │
│ Total Pinjaman      │ Rp 12.000.000      │
│ Total Terbayar      │ Rp 5.000.000       │
│ Sisa Pinjaman       │ Rp 7.000.000       │
│ Tanggal Jatuh Tempo │ 25 November 2025   │
└─────────────────────┴────────────────────┘
```

### **Body (Footer):**
```
💳 Cara Pembayaran

📞 Telepon: 0857-1537-5490
📧 Email: manajemenbumisultan@gmail.com
🏢 Kantor: Senin-Jumat, 08:00-17:00 WIB

[Login ke Sistem] ← Button menuju https://sistem.bumisultan.com

───────────────────────────────────────────
ℹ️ Email ini dikirim otomatis oleh sistem.
───────────────────────────────────────────

Hormat kami,
Tim Keuangan PT Bumi Sultan
https://sistem.bumisultan.com
```

---

## 🎨 Kustomisasi Tampilan (Optional)

### **1. Tambah Logo Perusahaan**

Edit file `resources/views/emails/pinjaman/jatuh-tempo.blade.php`:

```blade
@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ config('app.url') }}/images/logo-bumisultan.png" 
         alt="PT Bumi Sultan" 
         style="max-width: 200px;">
</div>

# Pengingat Pembayaran Cicilan Pinjaman
...
```

### **2. Ubah Warna Brand**

Edit file `config/mail.php` atau buat custom theme:

```php
// config/mail.php
'theme' => 'bumisultan',  // Custom theme
```

Buat file `resources/views/vendor/mail/html/themes/bumisultan.css`:

```css
/* Primary color - sesuaikan dengan brand */
.button-primary {
    background-color: #1e3a8a !important;  /* Biru Bumi Sultan */
}

.panel {
    border-left-color: #1e3a8a !important;
}
```

---

## 📊 Monitoring di Production

### **1. Cek Log Email**

```bash
# Di server
tail -f storage/logs/laravel.log
```

### **2. Dashboard Statistik**

```sql
-- Total email terkirim hari ini
SELECT COUNT(*) FROM pinjaman_email_notifications 
WHERE DATE(sent_at) = CURDATE() AND status = 'sent';

-- Email gagal terkirim
SELECT * FROM pinjaman_email_notifications 
WHERE status = 'failed' 
ORDER BY created_at DESC;
```

### **3. Setup Cron Job di Server**

```bash
# Edit crontab
crontab -e

# Tambahkan (ganti path sesuai server):
* * * * * cd /var/www/bumisultan && php artisan schedule:run >> /dev/null 2>&1
```

---

## ⚡ Performance Tips

### **1. Gunakan Queue**

```env
QUEUE_CONNECTION=database  # atau redis
```

```bash
# Jalankan queue worker
php artisan queue:work --tries=3
```

### **2. Rate Limiting**

Batasi jumlah email per menit di command untuk avoid spam detection.

### **3. Monitoring Service**

- **Mailgun:** Dashboard analytics
- **SendGrid:** Email tracking & analytics
- **AWS SES:** CloudWatch monitoring

---

## 🔒 Security Checklist

- [ ] ✅ APP_DEBUG=false di production
- [ ] ✅ APP_ENV=production
- [ ] ✅ HTTPS aktif (SSL certificate)
- [ ] ✅ Email password tidak hardcode
- [ ] ✅ SPF & DKIM record setup (jika pakai domain sendiri)
- [ ] ✅ DMARC policy setup
- [ ] ✅ Firewall rules untuk SMTP
- [ ] ✅ Backup konfigurasi email

---

## 📞 Support

Jika butuh bantuan setup production:
1. Hubungi tim DevOps
2. Cek dokumentasi hosting provider
3. Konsultasi dengan IT support

---

## ✅ Summary

| Aspek | Development | Production |
|-------|-------------|------------|
| **Domain** | localhost | bumisultan.com |
| **Email Sender** | Gmail personal | Domain company |
| **URL Button** | http://localhost | https://sistem.bumisultan.com |
| **Branding** | Generic | PT Bumi Sultan |
| **Security** | Basic | SSL + SPF + DKIM |
| **Monitoring** | Manual | Dashboard + Logging |

---

**Tampilan email di production akan jauh lebih profesional dengan logo, warna brand, dan domain sendiri! 🚀**
