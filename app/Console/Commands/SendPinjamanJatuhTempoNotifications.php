<?php

namespace App\Console\Commands;

use App\Models\Pinjaman;
use App\Models\Karyawan;
use App\Mail\PinjamanJatuhTempoMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPinjamanJatuhTempoNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman:send-jatuh-tempo-notifications {--test : Mode testing tanpa kirim email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi email untuk pinjaman yang akan/sudah jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isTestMode = $this->option('test');
        
        if ($isTestMode) {
            $this->info('🧪 MODE TESTING - Email tidak akan benar-benar dikirim');
        }
        
        $this->info('📧 Memulai pengecekan pinjaman jatuh tempo...');
        
        $now = Carbon::now();
        $hariIni = $now->day;
        
        // Ambil semua pinjaman yang masih berjalan
        $pinjamanBerjalan = Pinjaman::where('status', 'berjalan')
            ->with('karyawan')
            ->get();
        
        $this->info("✓ Ditemukan {$pinjamanBerjalan->count()} pinjaman berjalan");
        
        $totalDikirim = 0;
        $totalGagal = 0;
        
        foreach ($pinjamanBerjalan as $pinjaman) {
            $tanggalJatuhTempo = (int) $pinjaman->tanggal_jatuh_tempo_setiap_bulan;
            
            // Hitung selisih hari
            $selisihHari = $tanggalJatuhTempo - $hariIni;
            
            // Jika tanggal jatuh tempo sudah lewat bulan ini, hitung untuk bulan depan
            if ($selisihHari < 0) {
                $selisihHari = Carbon::now()->endOfMonth()->day - $hariIni + $tanggalJatuhTempo;
            }
            
            $tipeNotifikasi = null;
            $hariSebelum = null;
            
            // Tentukan tipe notifikasi berdasarkan selisih hari
            if ($selisihHari == 0) {
                $tipeNotifikasi = 'jatuh_tempo_hari_ini';
                $hariSebelum = 0;
            } elseif ($selisihHari == 1) {
                $tipeNotifikasi = 'jatuh_tempo_besok';
                $hariSebelum = 1;
            } elseif ($selisihHari == 3) {
                $tipeNotifikasi = 'jatuh_tempo_3_hari';
                $hariSebelum = 3;
            } elseif ($selisihHari == 7) {
                $tipeNotifikasi = 'jatuh_tempo_7_hari';
                $hariSebelum = 7;
            } elseif ($selisihHari < 0) {
                // Sudah lewat jatuh tempo bulan ini
                $tipeNotifikasi = 'sudah_lewat_jatuh_tempo';
                $hariSebelum = null;
            }
            
            // Jika ada notifikasi yang perlu dikirim
            if ($tipeNotifikasi) {
                // Cek apakah notifikasi sudah pernah dikirim untuk bulan ini
                $sudahDikirim = DB::table('pinjaman_email_notifications')
                    ->where('pinjaman_id', $pinjaman->id)
                    ->where('tipe_notifikasi', $tipeNotifikasi)
                    ->whereMonth('tanggal_jatuh_tempo', $now->month)
                    ->whereYear('tanggal_jatuh_tempo', $now->year)
                    ->where('status', 'sent')
                    ->exists();
                
                if ($sudahDikirim) {
                    $this->line("⏭️  Notifikasi {$tipeNotifikasi} untuk pinjaman {$pinjaman->nomor_pinjaman} sudah dikirim bulan ini");
                    continue;
                }
                
                // Dapatkan email peminjam
                $email = $this->getEmailPeminjam($pinjaman);
                
                if (!$email) {
                    $this->warn("⚠️  Tidak ada email untuk pinjaman {$pinjaman->nomor_pinjaman}");
                    continue;
                }
                
                // Hitung tanggal jatuh tempo untuk bulan ini
                $tanggalJatuhTempoFull = Carbon::create($now->year, $now->month, $tanggalJatuhTempo);
                if ($tanggalJatuhTempoFull->isPast() && $tipeNotifikasi === 'sudah_lewat_jatuh_tempo') {
                    // Tetap gunakan tanggal bulan ini untuk yang sudah lewat
                } elseif ($tanggalJatuhTempoFull->isPast()) {
                    $tanggalJatuhTempoFull->addMonth();
                }
                
                // Simpan log notifikasi
                $notificationId = DB::table('pinjaman_email_notifications')->insertGetId([
                    'pinjaman_id' => $pinjaman->id,
                    'email_tujuan' => $email,
                    'tipe_notifikasi' => $tipeNotifikasi,
                    'hari_sebelum_jatuh_tempo' => $hariSebelum,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempoFull,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                
                try {
                    if (!$isTestMode) {
                        // Kirim email
                        Mail::to($email)->send(new PinjamanJatuhTempoMail($pinjaman, $tipeNotifikasi, $hariSebelum));
                    }
                    
                    // Update status menjadi sent
                    DB::table('pinjaman_email_notifications')
                        ->where('id', $notificationId)
                        ->update([
                            'status' => 'sent',
                            'sent_at' => $now,
                            'updated_at' => $now,
                        ]);
                    
                    $this->info("✓ Email {$tipeNotifikasi} dikirim ke {$email} untuk pinjaman {$pinjaman->nomor_pinjaman}");
                    $totalDikirim++;
                    
                } catch (\Exception $e) {
                    // Update status menjadi failed
                    DB::table('pinjaman_email_notifications')
                        ->where('id', $notificationId)
                        ->update([
                            'status' => 'failed',
                            'error_message' => $e->getMessage(),
                            'retry_count' => DB::raw('retry_count + 1'),
                            'updated_at' => $now,
                        ]);
                    
                    $this->error("✗ Gagal kirim email untuk pinjaman {$pinjaman->nomor_pinjaman}: {$e->getMessage()}");
                    $totalGagal++;
                }
            }
        }
        
        $this->newLine();
        $this->info("📊 RINGKASAN:");
        $this->info("   ✓ Berhasil dikirim: {$totalDikirim}");
        if ($totalGagal > 0) {
            $this->warn("   ✗ Gagal dikirim: {$totalGagal}");
        }
        $this->info("✅ Selesai!");
        
        return Command::SUCCESS;
    }
    
    /**
     * Dapatkan email peminjam
     */
    private function getEmailPeminjam(Pinjaman $pinjaman): ?string
    {
        if ($pinjaman->kategori_peminjam === 'crew' && $pinjaman->karyawan) {
            return $pinjaman->karyawan->email;
        }
        
        // Untuk non-crew, ambil dari field email_peminjam
        return $pinjaman->email_peminjam;
    }
}
