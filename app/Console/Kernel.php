<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan worker queue tiap menit untuk memproses job antrian
        $schedule->command('queue:work --queue=default --sleep=3 --tries=3 --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping();

        // Kirim notifikasi email pinjaman jatuh tempo setiap hari jam 8 pagi
        $schedule->command('pinjaman:send-jatuh-tempo-notifications')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->onSuccess(function () {
                \Log::info('Notifikasi pinjaman jatuh tempo berhasil dikirim');
            })
            ->onFailure(function () {
                \Log::error('Gagal mengirim notifikasi pinjaman jatuh tempo');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
