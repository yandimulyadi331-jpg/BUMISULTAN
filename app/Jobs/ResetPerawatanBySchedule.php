<?php

namespace App\Jobs;

use App\Models\JadwalPiket;
use App\Models\JadwalPiketKaryawan;
use App\Models\PerawatanLog;
use App\Services\JadwalPiketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ResetPerawatanBySchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jadwalPiketService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->jadwalPiketService = new JadwalPiketService();
    }

    /**
     * Execute the job.
     * 
     * Job ini menjalankan setiap 1 menit untuk:
     * 1. Cek apakah shift/jadwal piket sudah selesai
     * 2. Reset checklist untuk shift yang sudah selesai (mark as expired)
     * 3. Prepare checklist untuk shift berikutnya
     */
    public function handle(): void
    {
        Log::info('ResetPerawatanBySchedule started');

        try {
            $now = now();
            $todayDate = $now->format('Y-m-d');

            // Get semua active jadwal piket
            $activeJadwalPikets = JadwalPiket::active()->get();

            foreach ($activeJadwalPikets as $jadwalPiket) {
                // Check apakah jadwal piket sudah selesai hari ini
                if (!$this->jadwalPiketService->shouldResetSchedule($jadwalPiket)) {
                    continue; // Shift belum selesai, skip
                }

                // Get semua karyawan yang assigned ke jadwal piket ini
                $karyawansWithSchedule = JadwalPiketKaryawan::activeOnDate(null, $now->format('Y-m-d'))
                    ->where('jadwal_piket_id', $jadwalPiket->id)
                    ->distinct('nik')
                    ->pluck('nik');

                foreach ($karyawansWithSchedule as $nik) {
                    // Tentukan status checklist yang belum selesai
                    $periodeKey = 'piket_' . $jadwalPiket->id . '_' . $todayDate;

                    // Update semua pending checklist untuk jadwal piket ini menjadi expired
                    PerawatanLog::whereHas('user', function ($q) use ($nik) {
                        $q->whereHas('userkaryawan', function ($q2) use ($nik) {
                            $q2->where('nik', $nik);
                        });
                    })
                    ->where('periode_key', $periodeKey)
                    ->where('status', 'pending')
                    ->update([
                        'status_validity' => 'expired',
                        'last_reset_at' => $now
                    ]);

                    Log::info("Reset perawatan for $nik on jadwal piket {$jadwalPiket->id}", [
                        'periode_key' => $periodeKey
                    ]);
                }
            }

            Log::info('ResetPerawatanBySchedule completed successfully');
        } catch (\Exception $e) {
            Log::error('ResetPerawatanBySchedule error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
