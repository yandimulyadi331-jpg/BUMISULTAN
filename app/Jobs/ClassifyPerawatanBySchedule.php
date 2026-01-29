<?php

namespace App\Jobs;

use App\Models\JadwalPiket;
use App\Models\JadwalPiketKaryawan;
use App\Models\MasterPerawatan;
use App\Models\PerawatanLog;
use App\Services\JadwalPiketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ClassifyPerawatanBySchedule implements ShouldQueue
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
     * 1. Mengidentifikasi jadwal piket mana yang sedang berlangsung untuk setiap karyawan
     * 2. Membuat record PerawatanLog untuk checklist sesuai jadwal piket
     * 3. Menandai validitas checklist (valid, expired, outside_shift)
     */
    public function handle(): void
    {
        Log::info('ClassifyPerawatanBySchedule started');

        try {
            $now = now();
            $todayDate = $now->format('Y-m-d');

            // Get semua unique jadwal piket yang aktif
            $activeJadwalPikets = JadwalPiket::active()->get();

            if ($activeJadwalPikets->isEmpty()) {
                Log::info('No active jadwal piket found');
                return;
            }

            foreach ($activeJadwalPikets as $jadwalPiket) {
                // Get semua karyawan yang assigned ke jadwal piket ini (untuk hari ini)
                $karyawansWithSchedule = JadwalPiketKaryawan::activeOnDate(null, $now->format('Y-m-d'))
                    ->where('jadwal_piket_id', $jadwalPiket->id)
                    ->distinct('nik')
                    ->pluck('nik');

                foreach ($karyawansWithSchedule as $nik) {
                    // Get user_id dari nik
                    $user = \App\Models\User::whereHas('userkaryawan', function ($q) use ($nik) {
                        $q->where('nik', $nik);
                    })->first();

                    if (!$user) {
                        continue;
                    }

                    // Get master checklist untuk jadwal piket ini
                    $masterChecklists = MasterPerawatan::active()
                        ->where('jadwal_piket_id', $jadwalPiket->id)
                        ->get();

                    foreach ($masterChecklists as $master) {
                        // Generate periode key berdasarkan jadwal piket
                        $periodeKey = 'piket_' . $jadwalPiket->id . '_' . $todayDate;

                        // Check apakah sudah ada record hari ini untuk master ini dan user ini
                        $existingLog = PerawatanLog::where([
                            'master_perawatan_id' => $master->id,
                            'user_id' => $user->id,
                            'tanggal_eksekusi' => $todayDate,
                            'periode_key' => $periodeKey
                        ])->first();

                        if ($existingLog) {
                            // Update validity status
                            $validity = $this->jadwalPiketService->getValidityStatus($jadwalPiket, $now);
                            $existingLog->update([
                                'status_validity' => $validity
                            ]);
                        } else {
                            // Tentukan validity status
                            $validity = $this->jadwalPiketService->getValidityStatus($jadwalPiket, $now);

                            // Buat record baru
                            PerawatanLog::create([
                                'master_perawatan_id' => $master->id,
                                'user_id' => $user->id,
                                'tanggal_eksekusi' => $todayDate,
                                'waktu_eksekusi' => $now->format('H:i:s'),
                                'jam_ceklis' => null, // akan diisi saat user ceklis
                                'nama_karyawan' => $user->userkaryawan->nama_lengkap ?? $user->name,
                                'jadwal_piket_id' => $jadwalPiket->id,
                                'status' => 'pending',
                                'status_validity' => $validity,
                                'catatan' => null,
                                'foto_bukti' => null,
                                'periode_key' => $periodeKey,
                                'points_earned' => 0
                            ]);
                        }
                    }
                }
            }

            Log::info('ClassifyPerawatanBySchedule completed successfully');
        } catch (\Exception $e) {
            Log::error('ClassifyPerawatanBySchedule error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
