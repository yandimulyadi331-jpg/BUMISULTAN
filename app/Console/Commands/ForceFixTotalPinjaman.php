<?php

namespace App\Console\Commands;

use App\Models\PinjamanIbu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ForceFixTotalPinjaman extends Command
{
    protected $signature = 'pinjaman:force-fix-total';
    protected $description = 'Force fix total_pinjaman = jumlah_disetujui untuk semua pinjaman';

    public function handle()
    {
        $this->info('🔧 Force fix total_pinjaman untuk semua pinjaman...');
        $this->info('');
        
        $pinjaman = PinjamanIbu::where('status', '!=', 'ditolak')
            ->where('status', '!=', 'pengajuan')
            ->get();
        
        $diperbaiki = 0;
        
        foreach ($pinjaman as $p) {
            $totalBenar = $p->jumlah_disetujui ?? $p->jumlah_pengajuan;
            
            if ($p->total_pinjaman != $totalBenar) {
                $this->line("📝 {$p->nomor_pinjaman}: Total Rp " . 
                    number_format($p->total_pinjaman, 0, ',', '.') . 
                    " → Rp " . number_format($totalBenar, 0, ',', '.'));
                
                // Update langsung
                DB::table('pinjaman_ibu')
                    ->where('id', $p->id)
                    ->update(['total_pinjaman' => $totalBenar]);
                
                $diperbaiki++;
            }
        }
        
        $this->info("");
        $this->info("✅ Selesai! {$diperbaiki} pinjaman sudah di-fix total_pinjaman.");
        
        // Regenerate cicilan yang mungkin kurang
        $this->call('pinjaman:regenerate-cicilan');
        
        return 0;
    }
}
