<?php

namespace App\Listeners;

use App\Events\PinjamanPaymentUpdated;
use App\Models\Pinjaman;
use App\Models\LaporanPinjamanCache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateLaporanPinjaman implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PinjamanPaymentUpdated $event): void
    {
        try {
            // ✅ REAL-TIME UPDATE: Rekonsiliasi akurasi nominal pinjaman
            $this->rekonsiliasi($event->pinjaman);
            
            // ✅ Update cache laporan (untuk tampilan real-time yang cepat)
            $this->updateCacheLaporan($event->pinjaman);
            
            // ✅ Log perubahan untuk audit trail
            $this->logPerubahanRealTime($event);
            
        } catch (\Exception $e) {
            \Log::error('Error updating laporan pinjaman: ' . $e->getMessage(), [
                'pinjaman_id' => $event->pinjaman->id,
                'exception' => $e,
            ]);
        }
    }

    /**
     * Rekonsiliasi akurasi nominal pinjaman
     * Memastikan: total_pinjaman = total_terbayar + sisa_pinjaman (selalu akurat)
     */
    private function rekonsiliasi(Pinjaman $pinjaman): void
    {
        $pinjaman->load('cicilan');
        
        // Hitung ulang total_terbayar dari cicilan yang sudah lunas/sebagian
        $totalTerbayarAktual = $pinjaman->cicilan()
            ->whereIn('status', ['lunas', 'sebagian'])
            ->sum('jumlah_dibayar');
        
        // Hitung ulang sisa dari cicilan belum bayar + sisa sebagian
        $sisaBelumBayar = $pinjaman->cicilan()
            ->whereIn('status', ['belum_bayar', 'sebagian'])
            ->sum('sisa_cicilan');
        
        // Verifikasi akurasi
        $totalHarusBayar = $totalTerbayarAktual + $sisaBelumBayar;
        $selisih = abs($totalHarusBayar - (float)$pinjaman->total_pinjaman);
        
        if ($selisih > 1) {
            // Ada anomali, lakukan rekonsiliasi
            \Log::warning('Anomali nominal pinjaman terdeteksi, melakukan rekonsiliasi', [
                'pinjaman_id' => $pinjaman->id,
                'total_pinjaman' => $pinjaman->total_pinjaman,
                'total_terbayar_aktual' => $totalTerbayarAktual,
                'sisa_belum_bayar' => $sisaBelumBayar,
                'selisih' => $selisih,
            ]);
            
            // Perbaiki dengan mengambil sumber kebenaran tunggal: total_pinjaman
            $sisaBaru = (float)$pinjaman->total_pinjaman - $totalTerbayarAktual;
            
            if ($sisaBaru < 0) {
                // Overpayment - jadikan 0 dan tandai lunas
                $sisaBaru = 0;
                $pinjaman->status = 'lunas';
                $pinjaman->tanggal_lunas = now();
            } else if ($sisaBaru <= 0) {
                $pinjaman->status = 'lunas';
                $pinjaman->tanggal_lunas = now();
            } else {
                $pinjaman->status = 'berjalan';
            }
            
            $pinjaman->total_terbayar = $totalTerbayarAktual;
            $pinjaman->sisa_pinjaman = $sisaBaru;
            $pinjaman->save();
        }
    }

    /**
     * Update cache laporan untuk performa optimal
     */
    private function updateCacheLaporan(Pinjaman $pinjaman): void
    {
        try {
            // Hapus cache lama
            \Cache::forget('laporan_pinjaman_stats');
            \Cache::forget('laporan_pinjaman_detail_' . $pinjaman->id);
            
            // Pre-generate cache baru dengan expire 5 menit (untuk data real-time)
            $laporanStats = $this->generateLaporanStats($pinjaman);
            \Cache::put('laporan_pinjaman_stats', $laporanStats, now()->addMinutes(5));
            
            // Cache detail pinjaman
            $detailPinjaman = [
                'id' => $pinjaman->id,
                'nomor_pinjaman' => $pinjaman->nomor_pinjaman,
                'total_pinjaman' => (float)$pinjaman->total_pinjaman,
                'total_terbayar' => (float)$pinjaman->total_terbayar,
                'sisa_pinjaman' => (float)$pinjaman->sisa_pinjaman,
                'persentase' => (float)$pinjaman->persentase_pembayaran,
                'status' => $pinjaman->status,
                'updated_at' => now(),
            ];
            
            \Cache::put('laporan_pinjaman_detail_' . $pinjaman->id, $detailPinjaman, now()->addMinutes(5));
            
        } catch (\Exception $e) {
            \Log::error('Error updating laporan cache: ' . $e->getMessage());
        }
    }

    /**
     * Generate statistik laporan pinjaman
     */
    private function generateLaporanStats($pinjaman): array
    {
        $query = Pinjaman::query();
        
        return [
            'total_pengajuan' => $query->where('status', 'pengajuan')->count(),
            'total_review' => $query->where('status', 'review')->count(),
            'total_disetujui' => $query->where('status', 'disetujui')->count(),
            'total_dicairkan' => $query->whereIn('status', ['dicairkan', 'berjalan'])->count(),
            'total_lunas' => $query->where('status', 'lunas')->count(),
            'total_nominal_dicairkan' => Pinjaman::whereIn('status', ['dicairkan', 'berjalan', 'lunas'])->sum('jumlah_disetujui'),
            'total_nominal_terbayar' => Pinjaman::whereIn('status', ['berjalan', 'lunas'])->sum('total_terbayar'),
            'total_nominal_sisa' => Pinjaman::whereIn('status', ['dicairkan', 'berjalan'])->sum('sisa_pinjaman'),
            'updated_at' => now(),
        ];
    }

    /**
     * Log perubahan untuk audit trail real-time
     */
    private function logPerubahanRealTime(PinjamanPaymentUpdated $event): void
    {
        try {
            DB::table('pinjaman_real_time_log')->insert([
                'pinjaman_id' => $event->pinjaman->id,
                'cicilan_ke' => $event->cicilan->cicilan_ke,
                'event_type' => 'payment_updated',
                'data_sebelum' => json_encode($event->dataPerubahan['sebelum'] ?? []),
                'data_sesudah' => json_encode($event->dataPerubahan['sesudah'] ?? []),
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error logging real-time change: ' . $e->getMessage());
        }
    }
}
