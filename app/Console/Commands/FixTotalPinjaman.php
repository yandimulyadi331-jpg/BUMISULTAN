<?php

namespace App\Console\Commands;

use App\Models\PinjamanIbu;
use Illuminate\Console\Command;

class FixTotalPinjaman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman:fix-total-pinjaman';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Fix total_pinjaman dari jumlah_disetujui untuk existing data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔧 Fix total_pinjaman untuk semua pinjaman...');
        $this->info('');
        
        $pinjaman = PinjamanIbu::where('status', '!=', 'ditolak')
            ->where('status', '!=', 'pengajuan')
            ->get();
        
        $diperbaiki = 0;
        
        foreach ($pinjaman as $p) {
            // Total pinjaman harus sama dengan jumlah_disetujui (atau jumlah_pengajuan jika belum disetujui)
            $totalBenar = $p->jumlah_disetujui ?? $p->jumlah_pengajuan;
            
            if ($p->total_pinjaman != $totalBenar) {
                $this->line("📝 {$p->nomor_pinjaman}: Rp " . number_format($p->total_pinjaman, 0, ',', '.') . ' → Rp ' . number_format($totalBenar, 0, ',', '.'));
                $p->update(['total_pinjaman' => $totalBenar]);
                $diperbaiki++;
            }
        }
        
        $this->info("✅ Selesai! {$diperbaiki} pinjaman sudah di-fix total_pinjaman.");
        
        // Regenerate cicilan
        $this->call('pinjaman:regenerate-cicilan');
        
        return 0;
    }
}
