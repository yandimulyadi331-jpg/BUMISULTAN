# ==========================================
# DEPLOY TO TERMIUS (REMOTE SERVER)
# ==========================================
# Script PowerShell untuk deploy otomatis ke Termius

$serverIp = "103.168.172.50"  # IP Termius Anda
$serverUser = "root"
$appPath = "/home/bumisultan/public_html"  # Path aplikasi di server

Write-Host "================================" -ForegroundColor Cyan
Write-Host "🚀 DEPLOYMENT SCRIPT - TERMIUS" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Pull dari Git
Write-Host "[1/5] 📥 Pulling dari repository..." -ForegroundColor Yellow
ssh "${serverUser}@${serverIp}" "cd ${appPath} && git pull origin main"
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Git pull gagal!" -ForegroundColor Red
    exit 1
}

# Step 2: Clear Cache
Write-Host "[2/5] 🧹 Clearing cache..." -ForegroundColor Yellow
ssh "${serverUser}@${serverIp}" "cd ${appPath} && php artisan cache:clear && php artisan config:clear && php artisan route:clear"
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Cache clear gagal!" -ForegroundColor Red
    exit 1
}

# Step 3: Run Migration (jika ada)
Write-Host "[3/5] 🗄️ Running migrations..." -ForegroundColor Yellow
ssh "${serverUser}@${serverIp}" "cd ${appPath} && php artisan migrate --force"
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️ Migration ada error (bisa normal jika tidak ada migration baru)" -ForegroundColor Yellow
}

# Step 4: Optimize
Write-Host "[4/5] ⚡ Optimizing application..." -ForegroundColor Yellow
ssh "${serverUser}@${serverIp}" "cd ${appPath} && php artisan optimize && php artisan optimize:clear"

# Step 5: Restart Queue/Cache
Write-Host "[5/5] 🔄 Restarting services..." -ForegroundColor Yellow
ssh "${serverUser}@${serverIp}" "cd ${appPath} && php artisan queue:restart"

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "✅ DEPLOYMENT BERHASIL!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "🌐 Akses aplikasi: https://bumisultan.com/keuangan-tukang/pinjaman" -ForegroundColor Cyan
