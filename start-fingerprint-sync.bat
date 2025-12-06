@echo off
echo ========================================
echo   REAL-TIME FINGERPRINT SYNC - AL-IKHLAS
echo ========================================
echo.
echo Starting auto-sync loop...
echo Command akan dijalankan setiap 1 menit
echo Press Ctrl+C to stop
echo.
echo ========================================
echo.

:loop
echo [%date% %time%] Running fingerprint sync...
php artisan fingerprint:sync-realtime
echo.
echo Waiting 60 seconds for next sync...
echo.
timeout /t 60 /nobreak
goto loop
