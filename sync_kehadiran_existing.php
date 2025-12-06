<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KehadiranTukang;
use App\Models\KeuanganTukang;
use Illuminate\Support\Facades\DB;

echo "=== SYNC MANUAL KEHADIRAN -> KEUANGAN ===\n\n";

$kehadirans = KehadiranTukang::with('tukang')->get();

echo "Total kehadiran yang akan di-sync: " . $kehadirans->count() . "\n\n";

foreach ($kehadirans as $kehadiran) {
    echo "Syncing: " . $kehadiran->tukang->nama_tukang . " (" . $kehadiran->tanggal . ")\n";
    
    DB::beginTransaction();
    try {
        // 1. Sync Upah Harian
        if (in_array($kehadiran->status, ['hadir', 'setengah_hari']) && $kehadiran->upah_harian > 0) {
            $upahHarian = KeuanganTukang::updateOrCreate(
                [
                    'tukang_id' => $kehadiran->tukang_id,
                    'tanggal' => $kehadiran->tanggal,
                    'jenis_transaksi' => 'upah_harian',
                    'kehadiran_tukang_id' => $kehadiran->id
                ],
                [
                    'jumlah' => $kehadiran->upah_harian,
                    'tipe' => 'debit',
                    'keterangan' => 'Upah harian - ' . ucwords(str_replace('_', ' ', $kehadiran->status)),
                    'dicatat_oleh' => 'System Sync'
                ]
            );
            echo "  ✅ Upah Harian: Rp " . number_format($kehadiran->upah_harian, 0, ',', '.') . "\n";
        }
        
        // 2. Sync Upah Lembur
        if ($kehadiran->lembur != 'tidak' && $kehadiran->upah_lembur > 0) {
            $jenisLembur = $kehadiran->lembur == 'full' ? 'lembur_full' : 'lembur_setengah';
            
            $upahLembur = KeuanganTukang::updateOrCreate(
                [
                    'tukang_id' => $kehadiran->tukang_id,
                    'tanggal' => $kehadiran->tanggal,
                    'jenis_transaksi' => $jenisLembur,
                    'kehadiran_tukang_id' => $kehadiran->id
                ],
                [
                    'jumlah' => $kehadiran->upah_lembur,
                    'tipe' => 'debit',
                    'keterangan' => 'Upah lembur - ' . ($kehadiran->lembur == 'full' ? 'Full' : 'Setengah Hari') . 
                                    ($kehadiran->lembur_dibayar_cash ? ' (Cash)' : ' (Kamis)'),
                    'dicatat_oleh' => 'System Sync'
                ]
            );
            echo "  ✅ Upah Lembur: Rp " . number_format($kehadiran->upah_lembur, 0, ',', '.') . "\n";
        }
        
        DB::commit();
        echo "  ✅ Sync berhasil!\n\n";
    } catch (\Exception $e) {
        DB::rollBack();
        echo "  ❌ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "=== SELESAI ===\n";
echo "Total transaksi keuangan sekarang: " . KeuanganTukang::count() . " record\n";
