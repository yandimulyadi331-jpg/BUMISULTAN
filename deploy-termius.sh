#!/bin/bash

# ==========================================
# DEPLOY TO TERMIUS (REMOTE SERVER)
# ==========================================
# Script Bash untuk deploy otomatis ke Termius (jalankan di server)

APP_PATH="/home/bumisultan/public_html"

echo "================================"
echo "🚀 DEPLOYMENT SCRIPT - TERMIUS"
echo "================================"
echo ""

# Step 1: Pull dari Git
echo "[1/5] 📥 Pulling dari repository..."
cd $APP_PATH
git pull origin main
if [ $? -ne 0 ]; then
    echo "❌ Git pull gagal!"
    exit 1
fi

# Step 2: Clear Cache
echo "[2/5] 🧹 Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
if [ $? -ne 0 ]; then
    echo "❌ Cache clear gagal!"
    exit 1
fi

# Step 3: Run Migration (jika ada)
echo "[3/5] 🗄️ Running migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "⚠️ Migration ada error (bisa normal jika tidak ada migration baru)"
fi

# Step 4: Optimize
echo "[4/5] ⚡ Optimizing application..."
php artisan optimize
php artisan optimize:clear

# Step 5: Restart Queue/Cache
echo "[5/5] 🔄 Restarting services..."
php artisan queue:restart

echo ""
echo "================================"
echo "✅ DEPLOYMENT BERHASIL!"
echo "================================"
echo ""
echo "🌐 Akses aplikasi: https://bumisultan.com/keuangan-tukang/pinjaman"
