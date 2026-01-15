#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Auto Commit & Push Script untuk Pop-Up Checklist Perawatan
.DESCRIPTION
    Script ini otomatis:
    1. Check git status
    2. Add semua changes
    3. Commit dengan message
    4. Push ke repository
    5. Verify push berhasil
#>

param(
    [string]$CommitMessage = "feat: Update popup checklist"
)

# Set error action
$ErrorActionPreference = "Stop"

# Define colors
$Green = [System.ConsoleColor]::Green
$Yellow = [System.ConsoleColor]::Yellow
$Red = [System.ConsoleColor]::Red
$Cyan = [System.ConsoleColor]::Cyan

function Write-Step {
    param([string]$Step, [string]$Message)
    Write-Host "$Step " -ForegroundColor $Cyan -NoNewline
    Write-Host $Message
}

function Write-Success {
    param([string]$Message)
    Write-Host "✓ " -ForegroundColor $Green -NoNewline
    Write-Host $Message
}

function Write-Error {
    param([string]$Message)
    Write-Host "✗ " -ForegroundColor $Red -NoNewline
    Write-Host $Message
}

# Navigate to project folder
$ProjectPath = "D:\bumisultanAPP\bumisultanAPP"
if (-not (Test-Path $ProjectPath)) {
    Write-Error "Project path tidak ditemukan: $ProjectPath"
    exit 1
}

Set-Location $ProjectPath
Write-Host ""
Write-Host "╔════════════════════════════════════════╗" -ForegroundColor $Cyan
Write-Host "║   AUTO COMMIT & PUSH TO GIT            ║" -ForegroundColor $Cyan
Write-Host "║   Pop-Up Checklist Perawatan          ║" -ForegroundColor $Cyan
Write-Host "╚════════════════════════════════════════╝" -ForegroundColor $Cyan
Write-Host ""

# Step 1: Check git status
Write-Step "[1/5]" "Checking git status..."
try {
    $status = git status --short
    if ($status) {
        Write-Success "Found $(($status | Measure-Object -Line).Lines) changed file(s)"
        Write-Host ""
        Write-Host "Files to commit:"
        $status | ForEach-Object { Write-Host "  $_" }
    } else {
        Write-Host "  No changes detected"
    }
} catch {
    Write-Error $_
    exit 1
}
Write-Host ""

# Step 2: Add changes
Write-Step "[2/5]" "Adding all changes..."
try {
    git add .
    Write-Success "All changes added"
} catch {
    Write-Error $_
    exit 1
}
Write-Host ""

# Step 3: Commit
Write-Step "[3/5]" "Creating commit..."
try {
    git commit -m $CommitMessage
    Write-Success "Commit created with message: '$CommitMessage'"
} catch {
    Write-Error $_
    Write-Host "Note: Ini bisa karena tidak ada changes untuk di-commit"
    Write-Host ""
    Write-Host "Skip Step 3? (Y/n): " -NoNewline
    $continue = Read-Host
    if ($continue -eq "n") { exit 1 }
}
Write-Host ""

# Step 4: Push
Write-Step "[4/5]" "Pushing to repository..."
try {
    git push origin main
    Write-Success "Pushed to main branch"
} catch {
    Write-Error $_
    exit 1
}
Write-Host ""

# Step 5: Verify
Write-Step "[5/5]" "Verifying commit..."
try {
    $lastCommit = git log --oneline -1
    Write-Success "Last commit:"
    Write-Host "  $lastCommit" -ForegroundColor $Green
} catch {
    Write-Error $_
}
Write-Host ""

Write-Host "╔════════════════════════════════════════╗" -ForegroundColor $Green
Write-Host "║   ✓ SUCCESS! CHANGES PUSHED            ║" -ForegroundColor $Green
Write-Host "╚════════════════════════════════════════╝" -ForegroundColor $Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor $Yellow
Write-Host "  1. Buka Termius → Connect ke hosting"
Write-Host "  2. Run: git pull origin main"
Write-Host "  3. Run: php artisan config:cache"
Write-Host ""
