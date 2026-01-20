<?php

namespace App\Console\Commands;

use App\Models\Pinjaman;
use Illuminate\Console\Command;

class CheckPinjaman extends Command
{
    protected $signature = 'pinjaman:check {nomor}';
    protected $description = 'Check detail pinjaman';

    public function handle()
    {
        $nomor = $this->argument('nomor');
        $pinjaman = Pinjaman::where('nomor_pinjaman', $nomor)->first();
        
        if (!$pinjaman) {
            $this->error("Pinjaman {$nomor} tidak ditemukan");
            return 1;
        }
        
        $this->info("Pinjaman: " . $pinjaman->nomor_pinjaman);
        $this->line("Jumlah Pengajuan: Rp " . number_format($pinjaman->jumlah_pengajuan, 0, ',', '.'));
        $this->line("Jumlah Disetujui: Rp " . number_format($pinjaman->jumlah_disetujui, 0, ',', '.'));
        $this->line("Total Pinjaman DB: Rp " . number_format($pinjaman->total_pinjaman, 0, ',', '.'));
        $this->line("Total Terbayar: Rp " . number_format($pinjaman->total_terbayar, 0, ',', '.'));
        $this->line("Sisa Pinjaman: Rp " . number_format($pinjaman->sisa_pinjaman, 0, ',', '.'));
        $this->line("Status: " . $pinjaman->status);
        $this->line("Tenor: " . $pinjaman->tenor_bulan . " bulan");
        $this->line("Cicilan Per Bulan: Rp " . number_format($pinjaman->cicilan_per_bulan, 0, ',', '.'));
        $this->line("");
        $this->line("Cicilan Count: " . $pinjaman->cicilan()->count());
        $pinjaman->cicilan()->get()->each(function($c) {
            $this->line("  Cicilan " . $c->cicilan_ke . ": Rp " . number_format($c->jumlah_cicilan, 0, ',', '.') . " (" . $c->status . ")");
        });
        
        return 0;
    }
}
