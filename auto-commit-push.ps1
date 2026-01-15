#!/usr/bin/env powershell

<#
.SYNOPSIS
    Auto Commit & Push Script untuk BumiSultan App
    
.DESCRIPTION
    Script ini otomatis melakukan:
    1. Git add .
    2. Git commit dengan message
    3. Git push ke origin main
    4. Show status
    
.USAGE
    1. Right-click script ini
    2. Select "Run with PowerShell"
    3. Atau double-click file auto-commit-push.bat
    
.AUTHOR
    BumiSultan Development Team
#>

Write-Host "`n" -ForegroundColor White
Write-Host "╔════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║     AUTO COMMIT & PUSH - BUMISULTAN    ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host "`n"

# Set project path
$projectPath = "D:\bumisultanAPP\bumisultanAPP"

# Navigate to project folder
Write-Host "📁 Navigating to project folder..." -ForegroundColor Yellow
Set-Location $projectPath

# Check if git repo exists
if (-not (Test-Path ".git")) {
    Write-Host "❌ ERROR: Tidak ada folder .git!" -ForegroundColor Red
    Write-Host "Pastikan Anda di folder project yang benar: $projectPath" -ForegroundColor Red
    Write-Host "`nPress any key to continue..." -ForegroundColor Gray
    $null = $host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    exit 1
}

Write-Host "✓ Git repository found" -ForegroundColor Green
Write-Host "`n"

# Step 1: Check status
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "[1/4] CHECKING GIT STATUS" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan

$status = git status --porcelain
if ($status) {
    Write-Host "Files changed:" -ForegroundColor Yellow
    Write-Host $status -ForegroundColor Gray
} else {
    Write-Host "No changes found." -ForegroundColor Yellow
    Write-Host "`nPress any key to continue..." -ForegroundColor Gray
    $null = $host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
    exit 0
}

Write-Host "`n"

# Step 2: Add files
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "[2/4] ADDING FILES" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan

git add .
Write-Host "✓ Files added successfully" -ForegroundColor Green
Write-Host "`n"

# Step 3: Commit
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "[3/4] COMMITTING CHANGES" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan

$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$message = "feat: Update popup checklist - $timestamp"

git commit -m $message

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Changes committed" -ForegroundColor Green
} else {
    Write-Host "⚠ Commit failed or no changes to commit" -ForegroundColor Yellow
}

Write-Host "`n"

# Step 4: Push
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "[4/4] PUSHING TO REPOSITORY" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan

git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Push successful" -ForegroundColor Green
} else {
    Write-Host "❌ Push failed" -ForegroundColor Red
    Write-Host "Please check your git configuration and try again" -ForegroundColor Yellow
}

Write-Host "`n"

# Final status
Write-Host "╔════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║  ✓ OPERASI SELESAI!                  ║" -ForegroundColor Green
Write-Host "║  Perubahan sudah ter-push ke repo    ║" -ForegroundColor Green
Write-Host "║  Siap untuk deploy ke hosting!       ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════╝" -ForegroundColor Green

Write-Host "`n"
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Buka Termius" -ForegroundColor Gray
Write-Host "2. Connect SSH ke hosting" -ForegroundColor Gray
Write-Host "3. Run: git pull origin main" -ForegroundColor Gray
Write-Host "4. Run: php artisan config:cache" -ForegroundColor Gray
Write-Host "`n"

Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
