<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tukang;
use App\Models\KehadiranTukang;
use App\Models\KeuanganTukang;
use App\Models\PinjamanTukang;
use Carbon\Carbon;

echo "============================================\n";
echo "GENERATE DATA MINGGU KAMIS (SABTU-KAMIS)\n";
echo "============================================\n\n";

// Hitung periode minggu ini (Sabtu s/d Kamis)
$today = Carbon::parse('2025-11-13'); // Simulasi hari Kamis gajian
echo "📅 Simulasi Hari: {$today->locale('id')->isoFormat('dddd, D MMMM YYYY')}\n\n";

// Cari Kamis terakhir
$kamis = $today->copy();
while ($kamis->dayOfWeek !== Carbon::THURSDAY) {
    if ($kamis->dayOfWeek < Carbon::THURSDAY) {
        $kamis->subWeek();
    }
    $kamis->subDay();
}

// Sabtu sebelum Kamis (6 hari ke belakang)
$sabtu = $kamis->copy()->subDays(5);

echo "📍 Periode Gajian:\n";
echo "   Sabtu  : {$sabtu->locale('id')->isoFormat('dddd, D MMMM YYYY')}\n";
echo "   Minggu : {$sabtu->copy()->addDay()->locale('id')->isoFormat('dddd, D MMMM YYYY')}\n";
echo "   Senin  : {$sabtu->copy()->addDays(2)->locale('id')->isoFormat('dddd, D MMMM YYYY')}\n";
echo "   Selasa : {$sabtu->copy()->addDays(3)->locale('id')->isoFormat('dddd, D MMMM YYYY')}\n";
echo "   Rabu   : {$sabtu->copy()->addDays(4)->locale('id')->isoFormat('dddd, D MMMM YYYY')}\n";
echo "   Kamis  : {$kamis->locale('id')->isoFormat('dddd, D MMMM YYYY')} ✅ GAJIAN\n\n";

// Ambil 3 tukang pertama untuk simulasi
$tukangs = Tukang::where('status', 'aktif')->take(3)->get();

if ($tukangs->count() == 0) {
    echo "❌ Tidak ada tukang aktif!\n";
    exit;
}

echo "👷 Tukang yang akan di-generate:\n";
foreach ($tukangs as $tukang) {
    // Set tarif default jika belum ada
    if (!$tukang->tarif_harian || $tukang->tarif_harian == 0) {
        $tukang->tarif_harian = 150000;
        $tukang->save();
    }
    echo "   - {$tukang->kode_tukang} | {$tukang->nama_tukang} | Rp " . number_format($tukang->tarif_harian, 0, ',', '.') . "/hari\n";
}
echo "\n";

// Hapus data lama di periode ini
echo "🗑️  Hapus data lama periode ini...\n";
KeuanganTukang::whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
    ->whereIn('tukang_id', $tukangs->pluck('id'))
    ->delete();
    
KehadiranTukang::whereBetween('tanggal', [$sabtu->format('Y-m-d'), $kamis->format('Y-m-d')])
    ->whereIn('tukang_id', $tukangs->pluck('id'))
    ->delete();

echo "✅ Data lama dihapus\n\n";

// Generate data per hari untuk setiap tukang
echo "📝 Generate Data Kehadiran & Keuangan...\n\n";

foreach ($tukangs as $tukang) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "👤 {$tukang->nama_tukang} ({$tukang->kode_tukang})\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $totalUpah = 0;
    $totalLembur = 0;
    $hariMasuk = 0;
    
    // Loop 6 hari (Sabtu-Kamis)
    for ($i = 0; $i <= 5; $i++) {
        $tanggal = $sabtu->copy()->addDays($i);
        $namaHari = $tanggal->locale('id')->isoFormat('dddd');
        
        // Randomize: 90% hadir, 10% tidak hadir
        $hadir = rand(1, 10) <= 9;
        
        if ($hadir) {
            $hariMasuk++;
            
            // Buat kehadiran
            KehadiranTukang::create([
                'tukang_id' => $tukang->id,
                'tanggal' => $tanggal->format('Y-m-d'),
                'jam_masuk' => '08:00:00',
                'jam_keluar' => rand(0, 10) <= 7 ? '17:00:00' : '20:00:00', // 70% normal, 30% lembur
                'status' => 'hadir',
                'keterangan' => 'Hadir normal'
            ]);
            
            // Catat upah harian
            KeuanganTukang::create([
                'tukang_id' => $tukang->id,
                'tanggal' => $tanggal->format('Y-m-d'),
                'jenis_transaksi' => 'upah_harian',
                'tipe' => 'debit',
                'jumlah' => $tukang->tarif_harian,
                'keterangan' => "Upah harian - {$namaHari}"
            ]);
            
            $totalUpah += $tukang->tarif_harian;
            
            // 30% chance lembur
            if (rand(1, 10) <= 3) {
                $lemburCash = rand(50000, 150000);
                KeuanganTukang::create([
                    'tukang_id' => $tukang->id,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'jenis_transaksi' => 'lembur_cash',
                    'tipe' => 'debit',
                    'jumlah' => $lemburCash,
                    'keterangan' => "Lembur cash - {$namaHari}"
                ]);
                $totalLembur += $lemburCash;
                
                echo "   ✅ {$namaHari} ({$tanggal->format('d/m')}): Hadir + Lembur Rp " . number_format($lemburCash, 0, ',', '.') . "\n";
            } else {
                echo "   ✅ {$namaHari} ({$tanggal->format('d/m')}): Hadir\n";
            }
        } else {
            echo "   ❌ {$namaHari} ({$tanggal->format('d/m')}): Tidak Hadir\n";
        }
    }
    
    echo "\n📊 Ringkasan {$tukang->nama_tukang}:\n";
    echo "   - Hari Masuk: {$hariMasuk}/6 hari\n";
    echo "   - Total Upah: Rp " . number_format($totalUpah, 0, ',', '.') . "\n";
    echo "   - Total Lembur: Rp " . number_format($totalLembur, 0, ',', '.') . "\n";
    
    // Cek pinjaman
    $pinjaman = PinjamanTukang::where('tukang_id', $tukang->id)->aktif()->first();
    if ($pinjaman && $tukang->auto_potong_pinjaman) {
        echo "   - Cicilan Pinjaman: Rp " . number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') . " (DIPOTONG)\n";
        echo "   - Gaji Bersih: Rp " . number_format(($totalUpah + $totalLembur - $pinjaman->cicilan_per_minggu), 0, ',', '.') . "\n";
    } else {
        echo "   - Cicilan Pinjaman: Tidak ada\n";
        echo "   - Gaji Bersih: Rp " . number_format(($totalUpah + $totalLembur), 0, ',', '.') . "\n";
    }
    
    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ DATA BERHASIL DI-GENERATE!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "🌐 Silakan buka: http://localhost:8000/keuangan-tukang\n";
echo "📅 Dashboard akan menampilkan periode: {$sabtu->locale('id')->isoFormat('D MMMM')} - {$kamis->locale('id')->isoFormat('D MMMM YYYY')}\n\n";
