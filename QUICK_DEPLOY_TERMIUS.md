# ⚡ QUICK DEPLOY TERMIUS - LANGSUNG ACTION

## 🚀 5 MENIT DEPLOYMENT

Ikuti langkah-langkah ini dengan cepat:

---

## STEP 1: Commit Local (2 menit)

**Di VS Code Terminal / Command Prompt lokal:**

```bash
cd d:\bumisultanAPP\bumisultanAPP

git add .
git commit -m "feat: Add checklist notification modal"
git push origin main
```

**Tunggu hingga selesai** ✓

---

## STEP 2: Open Termius (1 menit)

1. **Buka Termius aplikasi**
2. **Pilih SSH host Anda** (dari saved hosts)
3. **Click "Connect"**

Atau jika belum saved:
- Title: BumiSultan
- Hostname: [domain/IP hosting Anda]
- Username: [username]
- Password: [password]

---

## STEP 3: Navigate & Pull (1 menit)

**Di Termius (setelah SSH connect):**

```bash
# Masuk ke folder project
cd /home/username/public_html/bumisultanAPP

# ATAU sesuaikan path hosting Anda

# Pull kode terbaru
git pull origin main
```

**Tunggu git pull selesai** ✓

---

## STEP 4: Cache & Verify (1 menit)

```bash
# Cache laravel
php artisan config:cache

# Verify route terdaftar
php artisan route:list | grep checklist
```

**Harus ada output:**
```
POST   api/checklist/status   api.checklist.status
```

---

## STEP 5: Test (bonus, optional)

Buka browser:
```
https://domain-anda.com/dashboard
```

Lihat apakah modal muncul saat ada checklist belum selesai ✓

---

## ✅ DONE!

Perubahan sudah live di hosting! 🎉

---

## 🆘 JIKA ERROR

### Git Pull Error
```bash
git status
# Jika ada conflict, contact dev
```

### Route Tidak Muncul
```bash
php artisan config:clear
php artisan config:cache
php artisan route:list | grep checklist
```

### API Error 500
```bash
tail -20 storage/logs/laravel.log
# Lihat pesan error di log
```

---

## 💡 TIPS

- **Save SSH host di Termius** → Next time tinggal connect
- **Buat 2 terminal** → Satu untuk command, satu untuk logs
- **Tail logs real-time** → `tail -f storage/logs/laravel.log`

---

**Pertanyaan? Baca:** `PANDUAN_DEPLOY_HOSTING_TERMIUS.md` (versi lengkap)
