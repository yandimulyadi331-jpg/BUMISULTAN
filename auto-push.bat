@echo off
REM ===== AUTO COMMIT & PUSH SCRIPT =====
REM Untuk automasi git commit dan push di local
REM Tinggal double-click, semua otomatis!

setlocal enabledelayedexpansion
cd /d "D:\bumisultanAPP\bumisultanAPP"

cls
echo.
echo ========================================
echo   AUTO COMMIT & PUSH KE GIT
echo   Pop-Up Checklist Perawatan
echo ========================================
echo.

REM Check git status
echo [1/5] Checking git status...
git status
echo.

REM Add semua perubahan
echo [2/5] Adding all changes...
git add .
echo ✓ All files added
echo.

REM Commit dengan pesan
echo [3/5] Creating commit...
set /p "commit_msg=Enter commit message (default: 'feat: Update popup checklist'): "
if "!commit_msg!"=="" set "commit_msg=feat: Update popup checklist"

git commit -m "!commit_msg!"
echo ✓ Commit created
echo.

REM Push ke repository
echo [4/5] Pushing to repository...
git push origin main
echo ✓ Pushed to main branch
echo.

REM Verification
echo [5/5] Verifying...
git log --oneline | head -1
echo.

echo ========================================
echo ✓ SUCCESS! Changes pushed to Git
echo.
echo Next steps:
echo 1. Go to hosting (Termius)
echo 2. Run: git pull origin main
echo 3. Run: php artisan config:cache
echo ========================================
echo.

pause
