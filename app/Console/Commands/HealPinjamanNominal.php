<?php

namespace App\Console\Commands;

use App\Models\Pinjaman;
use Illuminate\Console\Command;

class HealPinjamanNominal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman:heal-nominal';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Perbaiki nominal pinjaman dari cicilan (self-heal akurasi)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔧 Memulai perbaikan akurasi nominal pinjaman...');
        $this->info('');
        
        $pinjamanList = Pinjaman::with('cicilan')->get();
        $diperbaiki = 0;
        
        foreach ($pinjamanList as $pinjaman) {
            $nominalAsli = $pinjaman->total_pinjaman;
            $sisaAsli = $pinjaman->sisa_pinjaman;
            $cicilanCount = $pinjaman->cicilan()->count();
            $cicilanSum = $pinjaman->cicilan()->sum('jumlah_cicilan');
            
            // Show detail
            $this->line("📋 {$pinjaman->nomor_pinjaman}");
            $this->line("   - Tenor: {$pinjaman->tenor_bulan} bulan, Cicilan/Bulan: Rp " . number_format($pinjaman->cicilan_per_bulan, 0, ',', '.'));
            $this->line("   - DB Total: Rp " . number_format($nominalAsli, 0, ',', '.') . " | Cicilan Count: {$cicilanCount} | Sum: Rp " . number_format($cicilanSum, 0, ',', '.'));
            
            $pinjaman->healNominalAkurasi();
            
            if ($nominalAsli != $pinjaman->total_pinjaman || $sisaAsli != $pinjaman->sisa_pinjaman) {
                $diperbaiki++;
                $this->line("   ✅ FIXED: Rp " . number_format($nominalAsli, 0, ',', '.') . ' → ' . number_format($pinjaman->total_pinjaman, 0, ',', '.') . " | Sisa: Rp " . number_format($pinjaman->sisa_pinjaman, 0, ',', '.'));
            } else {
                $this->line("   ✓ OK");
            }
            $this->line("");
        }
        
        $this->info("✅ Selesai! {$diperbaiki} pinjaman berhasil diperbaiki.");
        return 0;
    }
}
