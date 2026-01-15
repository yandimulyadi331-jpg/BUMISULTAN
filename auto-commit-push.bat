@echo off
REM ============================================
REM AUTO COMMIT & PUSH - BUMISULTAN APP
REM ============================================
REM Tinggal double-click file ini, semua otomatis!
REM ============================================

echo.
echo ========================================
echo  AUTO COMMIT & PUSH
echo ========================================
echo.

REM Navigate ke project folder
cd /d D:\bumisultanAPP\bumisultanAPP

REM Check if in git repo
if not exist .git (
    echo ERROR: Tidak ada folder .git!
    echo Pastikan Anda di folder project yang benar.
    pause
    exit /b 1
)

echo [1/4] Checking git status...
git status
echo.

echo [2/4] Adding files...
git add .
echo ✓ Files added
echo.

echo [3/4] Committing changes...
REM Get current date and time
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set mydate=%%c-%%a-%%b)
for /f "tokens=1-2 delims=/:" %%a in ('time /t') do (set mytime=%%a%%b)

git commit -m "feat: Update popup checklist - %mydate% %mytime%"
echo ✓ Changes committed
echo.

echo [4/4] Pushing to repository...
git push origin main
echo ✓ Pushed successfully
echo.

echo.
echo ========================================
echo  ✓ SUKSES! Perubahan sudah ter-push!
echo ========================================
echo.
echo Tunggu sebentar sebelum deploy ke hosting...
echo.

pause
