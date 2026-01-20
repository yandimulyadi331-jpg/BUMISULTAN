<?php

namespace App\Console\Commands;

use App\Models\PinjamanIbu;
use Illuminate\Console\Command;

class DebugPinjaman extends Command
{
    protected $signature = 'pinjaman:debug {nomor}';
    protected $description = 'Debug pinjaman untuk lihat cicilan generation logic';

    public function handle()
    {
        $nomor = $this->argument('nomor');
        // Coba PinjamanIbu dulu, jika tidak ada coba Pinjaman crew
        $pinjaman = PinjamanIbu::where('nomor_pinjaman', $nomor)->first();
        if (!$pinjaman) {
            $pinjaman = \App\Models\Pinjaman::where('nomor_pinjaman', $nomor)->first();
        }
        
        if (!$pinjaman) {
            $this->error("Pinjaman {$nomor} tidak ditemukan");
            return 1;
        }
        
        $this->info("=== DEBUG PINJAMAN {$pinjaman->nomor_pinjaman} ===");
        $this->line("Total Pinjaman DB: Rp " . number_format($pinjaman->total_pinjaman, 0, ',', '.'));
        $this->line("Jumlah Disetujui: Rp " . number_format($pinjaman->jumlah_disetujui, 0, ',', '.'));
        $this->line("Cicilan Per Bulan: Rp " . number_format($pinjaman->cicilan_per_bulan, 0, ',', '.'));
        $this->line("Tenor: {$pinjaman->tenor_bulan} bulan");
        $this->line("");
        
        // Hitung harusnya
        $cicilanNormal = $pinjaman->cicilan_per_bulan;
        $cicilanTerakhir = $pinjaman->total_pinjaman - ($cicilanNormal * ($pinjaman->tenor_bulan - 1));
        
        $this->line("=== KALKULASI HARUSNYA ===");
        for ($i = 1; $i <= $pinjaman->tenor_bulan; $i++) {
            if ($i < $pinjaman->tenor_bulan) {
                $nominal = $cicilanNormal;
            } else {
                $nominal = $cicilanTerakhir;
            }
            
            $skip = $nominal <= 0 ? " (SKIP)" : "";
            $this->line("Cicilan {$i}: Rp " . number_format($nominal, 0, ',', '.') . $skip);
        }
        
        $this->line("");
        $this->line("=== CICILAN DI DATABASE ===");
        $this->line("Count: " . $pinjaman->cicilan()->count());
        $pinjaman->cicilan()->orderBy('cicilan_ke')->get()->each(function($c) {
            $this->line("Cicilan {$c->cicilan_ke}: Rp " . number_format($c->jumlah_cicilan, 0, ',', '.') . " ({$c->status})");
        });
        
        return 0;
    }
}
