# 🤖 AUTOMASI GIT COMMIT & PUSH - LOKAL

Saya sudah buatkan 2 script untuk otomatis commit & push di lokal. Tinggal double-click!

---

## 📋 Ada 2 Pilihan Script

### ✅ OPTION 1: Batch Script (RECOMMENDED - Paling Simple)
**File**: `auto-push.bat`

**Cara pakai:**
1. Cari file `auto-push.bat` di folder project
2. **Double-click** file tersebut
3. Masukkan commit message (atau Enter untuk default)
4. **Done!** Semua otomatis commit & push

**Output:**
```
========================================
   AUTO COMMIT & PUSH KE GIT
========================================

[1/5] Checking git status...
✓ Found 3 changed file(s)

[2/5] Adding all changes...
✓ All files added

[3/5] Creating commit...
Enter commit message: feat: Update popup checklist
✓ Commit created

[4/5] Pushing to repository...
✓ Pushed to main branch

[5/5] Verifying...
abc1234 feat: Update popup checklist

========================================
✓ SUCCESS! Changes pushed to Git
========================================
```

---

### ✅ OPTION 2: PowerShell Script (Advanced)
**File**: `auto-push.ps1`

**Cara pakai (Windows 10/11):**
1. Buka PowerShell (Win + X → Windows PowerShell)
2. Navigate ke folder: `cd D:\bumisultanAPP\bumisultanAPP`
3. Run command:
```powershell
.\auto-push.ps1 -CommitMessage "feat: Add checklist popup"
```

Atau dengan default message:
```powershell
.\auto-push.ps1
```

**Kelebihan PowerShell:**
- Warna output lebih pretty
- Error handling lebih baik
- Bisa di-schedule untuk auto-run

---

## 🚀 RECOMMENDED WORKFLOW

### Setiap kali ada perubahan di local:

```
1. Double-click: auto-push.bat
   ↓
2. Tunggu selesai (semua otomatis)
   ↓
3. Buka Termius
   ↓
4. Run: git pull origin main
   ↓
5. Run: php artisan config:cache
   ↓
   DONE! Perubahan live di hosting
```

---

## 🎯 AUTOMATION WORKFLOW

### Tanpa Script (MANUAL)
```
1. git add .
2. git commit -m "message"
3. git push origin main
= 3 command manual ketik
```

### Dengan Script (AUTOMATED) ✅
```
1. Double-click auto-push.bat
= 1 action saja, semua otomatis!
```

---

## ⚙️ CUSTOMIZATION

### Ubah Default Commit Message

**Option A: Edit auto-push.bat**
```batch
REM Buka file auto-push.bat
REM Cari baris ini:
set "commit_msg=feat: Update popup checklist"

REM Ganti dengan pesan Anda:
set "commit_msg=feat: Add new feature xyz"
```

**Option B: Edit auto-push.ps1**
```powershell
# Buka file auto-push.ps1
# Cari baris ini di param:
[string]$CommitMessage = "feat: Update popup checklist"

# Ganti dengan:
[string]$CommitMessage = "feat: Add new feature xyz"
```

---

## 🔧 TROUBLESHOOTING

### Problem: "Command not found"
```bash
# Solution: Pastikan sudah di folder project yang benar
cd D:\bumisultanAPP\bumisultanAPP

# Atau double-click auto-push.bat (otomatis correct folder)
```

### Problem: PowerShell script tidak jalan
```powershell
# PowerShell mungkin block script execution
# Solution: Enable script execution (run as admin)

Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Kemudian coba lagi:
.\auto-push.ps1
```

### Problem: Git tidak ditemukan
```bash
# Pastikan Git sudah installed
git --version

# Jika tidak terinstall, download dari: https://git-scm.com/
```

### Problem: "Nothing to commit"
```
Ini normal jika tidak ada changes.
Cukup lanjut ke step berikutnya di Termius.
```

---

## 📱 INTEGRATION WITH WINDOWS TASK SCHEDULER (Optional)

Jika ingin auto-push setiap hari pada jam tertentu:

**Step 1: Buat batch file dengan schedule**
```batch
@echo off
cd D:\bumisultanAPP\bumisultanAPP
git add .
git commit -m "auto: Daily backup"
git push origin main
```

**Step 2: Setup Windows Task Scheduler**
1. Press Win + R
2. Type: `taskschd.msc`
3. Click: "Create Task"
4. Set schedule (contoh: 6 PM setiap hari)
5. Action: Run batch file

---

## 🎨 BASH/SHELL SCRIPT (Mac/Linux Users)

Jika menggunakan Mac atau Linux, buat file `auto-push.sh`:

```bash
#!/bin/bash

cd ~/bumisultanAPP/bumisultanAPP

echo "=============================="
echo "AUTO COMMIT & PUSH"
echo "=============================="
echo ""

echo "[1/5] Checking status..."
git status --short

echo "[2/5] Adding changes..."
git add .

echo "[3/5] Committing..."
read -p "Commit message (default: 'feat: Update'): " msg
msg=${msg:-"feat: Update"}
git commit -m "$msg"

echo "[4/5] Pushing..."
git push origin main

echo "[5/5] Verifying..."
git log --oneline -1

echo ""
echo "✓ Success! Changes pushed."
```

**Cara pakai:**
```bash
chmod +x auto-push.sh
./auto-push.sh
```

---

## 📊 SCRIPT COMPARISON

| Feature | .bat | .ps1 | .sh |
|---------|------|------|-----|
| Platform | Windows ✅ | Windows ✅ | Mac/Linux ✅ |
| Ease | Very Easy ✅ | Easy | Easy |
| Double-click | ✅ Yes | ❌ No | ❌ No |
| Pretty Output | ✅ | ✅✅ | ✅ |
| Custom Message | ✅ | ✅✅ | ✅ |
| Scheduling | ✅ | ✅✅ | ✅ |

---

## 💡 PRO TIPS

### Tip 1: Create Shortcut
```
1. Klik kanan auto-push.bat
2. "Send to" → "Desktop (create shortcut)"
3. Sekarang ada shortcut di desktop
4. Double-click shortcut = instant commit & push
```

### Tip 2: Combine dengan Hosting Deploy
```
# Buat combined script (advanced)
1. auto-push.bat (local)
2. auto-pull.sh (di hosting via Termius)
3. Sekarang one-click deploy lokal + hosting!
```

### Tip 3: Monitor Git Log
```bash
# Lihat semua commit yang di-push:
git log --oneline | head -10

# Buat alias untuk cepat
# Edit .gitconfig atau run:
git config --global alias.last "log --oneline -5"

# Kemudian cukup:
git last
```

---

## ✅ QUICK START

**Sekarang juga:**

1. Lihat folder project Anda
2. Cari file: `auto-push.bat` atau `auto-push.ps1`
3. Double-click `auto-push.bat` (paling simple)
4. Selesai! Commit & push otomatis

**Next:**
- Buka Termius
- `git pull origin main`
- Done!

---

## 📞 REMEMBER

```
Script ini akan:
✅ Add semua changes
✅ Create commit dengan message
✅ Push ke repository

Script ini TIDAK akan:
❌ Create pull request
❌ Deploy ke hosting
❌ Restart web server

Untuk hosting, masih perlu manual:
1. Termius → git pull
2. Termius → php artisan config:cache
```

---

**Enjoy automated deployment! 🚀**
