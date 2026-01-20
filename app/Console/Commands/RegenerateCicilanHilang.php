<?php

namespace App\Console\Commands;

use App\Models\Pinjaman;
use Illuminate\Console\Command;

class RegenerateCicilanHilang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman:regenerate-cicilan';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Regenerate cicilan yang hilang untuk pinjaman yang dicairkan';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔧 Regenerate cicilan yang hilang...');
        $this->info('');
        
        $pinjamanList = Pinjaman::where('status', '!=', 'ditolak')
            ->where('status', '!=', 'pengajuan')
            ->where('status', '!=', 'review')
            ->get();
        
        $diregenerasi = 0;
        
        foreach ($pinjamanList as $pinjaman) {
            $cicilanCount = $pinjaman->cicilan()->count();
            $expectedCount = $pinjaman->tenor_bulan;
            
            if ($cicilanCount == 0) {
                // Tidak ada cicilan sama sekali
                $this->line("🔴 {$pinjaman->nomor_pinjaman} - Cicilan KOSONG! Tenor: {$expectedCount}, Cicilan: {$cicilanCount}");
                $pinjaman->generateJadwalCicilan();
                $diregenerasi++;
                $this->line("   ✅ REGENERATED");
                
            } elseif ($cicilanCount < $expectedCount) {
                // Cicilan tidak lengkap (missing cicilan ke-3 dst)
                $this->line("🟡 {$pinjaman->nomor_pinjaman} - Cicilan TIDAK LENGKAP! Tenor: {$expectedCount}, Ada: {$cicilanCount}");
                $pinjaman->cicilan()->delete();
                $pinjaman->generateJadwalCicilan();
                $diregenerasi++;
                $this->line("   ✅ REGENERATED");
            }
            
            $this->line("");
        }
        
        $this->info("✅ Selesai! {$diregenerasi} pinjaman sudah di-regenerate cicilan.");
        
        // Jalankan heal nominal setelah regenerate
        $this->call('pinjaman:heal-nominal');
        
        return 0;
    }
}
