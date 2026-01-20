<?php

namespace App\Console\Commands;

use App\Models\Pinjaman;
use App\Models\PinjamanCicilan;
use Illuminate\Console\Command;

class SyncPinjamanData extends Command
{
    protected $signature = 'pinjaman:sync-data';
    protected $description = 'Sync data pinjaman: total_pinjaman, total_terbayar, sisa_pinjaman dari cicilan';

    public function handle()
    {
        $this->info('🔄 Mensinkronisasi data pinjaman...');
        $this->info('');
        
        $pinjamanList = Pinjaman::with('cicilan')->get();
        $disynced = 0;
        
        foreach ($pinjamanList as $pinjaman) {
            // Hitung dari cicilan yang sebenarnya
            $totalCicilan = $pinjaman->cicilan()->sum('jumlah_cicilan');
            $totalBayar = $pinjaman->cicilan()->sum('jumlah_dibayar');
            $sisaPinjaman = max(0, $totalCicilan - $totalBayar);
            
            // Check apakah perlu update
            if ($pinjaman->total_pinjaman != $totalCicilan ||
                $pinjaman->total_terbayar != $totalBayar ||
                $pinjaman->sisa_pinjaman != $sisaPinjaman) {
                
                $this->line("🔄 {$pinjaman->nomor_pinjaman}:");
                $this->line("   Total: Rp " . number_format($pinjaman->total_pinjaman, 0, ',', '.') . 
                           " → Rp " . number_format($totalCicilan, 0, ',', '.'));
                $this->line("   Bayar: Rp " . number_format($pinjaman->total_terbayar, 0, ',', '.') . 
                           " → Rp " . number_format($totalBayar, 0, ',', '.'));
                $this->line("   Sisa: Rp " . number_format($pinjaman->sisa_pinjaman, 0, ',', '.') . 
                           " → Rp " . number_format($sisaPinjaman, 0, ',', '.'));
                
                $pinjaman->update([
                    'total_pinjaman' => $totalCicilan,
                    'total_terbayar' => $totalBayar,
                    'sisa_pinjaman' => $sisaPinjaman,
                ]);
                
                $disynced++;
                $this->line("   ✅ SYNCED");
                $this->line("");
            }
        }
        
        if ($disynced == 0) {
            $this->info("✅ Semua data sudah sinkron!");
        } else {
            $this->info("✅ Selesai! {$disynced} pinjaman sudah di-sync.");
        }
        
        return 0;
    }
}
