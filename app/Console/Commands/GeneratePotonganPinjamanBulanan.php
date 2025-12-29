<?php

namespace App\Console\Commands;

use App\Models\PotonganPinjamanMaster;
use App\Models\PotonganPinjamanDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneratePotonganPinjamanBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'potongan-pinjaman:generate {bulan?} {tahun?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate detail potongan pinjaman bulanan dari master yang aktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bulan = $this->argument('bulan') ?? date('n');
        $tahun = $this->argument('tahun') ?? date('Y');

        $this->info("=== Generate Potongan Pinjaman Bulanan ===");
        $this->info("Periode: " . Carbon::create($tahun, $bulan, 1)->format('F Y'));
        $this->line("");

        try {
            DB::beginTransaction();

            // Ambil master yang aktif di periode ini
            $masters = PotonganPinjamanMaster::with('karyawan')
                ->byStatus('aktif')
                ->activePeriode($bulan, $tahun)
                ->get();

            if ($masters->count() == 0) {
                $this->warn('Tidak ada potongan pinjaman aktif untuk periode ini.');
                return 0;
            }

            $this->info("Found {$masters->count()} active master records");
            $this->line("");

            $generated = 0;
            $skipped = 0;
            $errors = 0;

            $progressBar = $this->output->createProgressBar($masters->count());
            $progressBar->start();

            foreach ($masters as $master) {
                try {
                    // Check apakah detail untuk periode ini sudah ada
                    $exists = PotonganPinjamanDetail::where('master_id', $master->id)
                        ->where('bulan', $bulan)
                        ->where('tahun', $tahun)
                        ->exists();

                    if ($exists) {
                        $skipped++;
                        $progressBar->advance();
                        continue;
                    }

                    // Hitung cicilan ke berapa
                    $cicilanKe = $master->details()
                        ->where(function($q) use ($bulan, $tahun) {
                            $q->where('tahun', '<', $tahun)
                              ->orWhere(function($q2) use ($bulan, $tahun) {
                                  $q2->where('tahun', '=', $tahun)
                                     ->where('bulan', '<', $bulan);
                              });
                        })
                        ->count() + 1;

                    // Cek apakah sudah melebihi jumlah bulan
                    if ($cicilanKe > $master->jumlah_bulan) {
                        // Sudah selesai, update status master
                        $master->update(['status' => 'selesai', 'tanggal_selesai' => now()]);
                        $skipped++;
                        $progressBar->advance();
                        continue;
                    }

                    // Tentukan jumlah potongan (bisa beda di cicilan terakhir)
                    $jumlahPotongan = $master->cicilan_per_bulan;
                    if ($cicilanKe == $master->jumlah_bulan) {
                        // Cicilan terakhir, gunakan sisa
                        $jumlahPotongan = $master->sisa_pinjaman;
                    } elseif ($jumlahPotongan > $master->sisa_pinjaman) {
                        $jumlahPotongan = $master->sisa_pinjaman;
                    }

                    // Create detail
                    PotonganPinjamanDetail::create([
                        'master_id' => $master->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'jumlah_potongan' => $jumlahPotongan,
                        'cicilan_ke' => $cicilanKe,
                        'status' => 'pending',
                        'keterangan' => "Auto-generated: Cicilan ke-{$cicilanKe} dari {$master->jumlah_bulan} untuk {$master->karyawan->nama_karyawan}",
                    ]);

                    $generated++;

                } catch (\Exception $e) {
                    $this->error("Error processing master {$master->kode_potongan}: " . $e->getMessage());
                    $errors++;
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->line("\n");

            DB::commit();

            // Summary
            $this->line("");
            $this->info("=== Summary ===");
            $this->line("Generated: {$generated} records");
            $this->line("Skipped: {$skipped} records");
            if ($errors > 0) {
                $this->error("Errors: {$errors} records");
            }
            $this->line("");

            $this->info("✓ Generate potongan pinjaman selesai!");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to generate: " . $e->getMessage());
            return 1;
        }
    }
}
