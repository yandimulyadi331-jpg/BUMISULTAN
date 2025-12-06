<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tukang;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║       DEMO SISTEM LEMBUR CASH - DIBAYAR HARI ITU JUGA         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$tukang = Tukang::first();
if (!$tukang) {
    echo "❌ Belum ada data tukang\n";
    exit;
}

$tarif = $tukang->tarif_harian;
$nama = $tukang->nama_tukang;

echo "Tukang: {$nama}\n";
echo "Tarif Harian: Rp " . number_format($tarif, 0, ',', '.') . "\n";
echo str_repeat("─", 68) . "\n\n";

echo "🎯 5 PILIHAN LEMBUR (Cycle dengan tombol):\n\n";

$scenarios = [
    [
        'no' => '1️⃣',
        'name' => 'TIDAK LEMBUR',
        'color' => '⚪ Abu-abu',
        'upah_harian' => $tarif,
        'upah_lembur' => 0,
        'total' => $tarif,
        'bayar' => '-',
        'icon' => '➖'
    ],
    [
        'no' => '2️⃣',
        'name' => 'LEMBUR FULL (Kamis)',
        'color' => '🔴 Merah',
        'upah_harian' => $tarif,
        'upah_lembur' => $tarif,
        'total' => $tarif * 2,
        'bayar' => 'Kamis minggu ini',
        'icon' => '🕐'
    ],
    [
        'no' => '3️⃣',
        'name' => 'LEMBUR SETENGAH (Kamis)',
        'color' => '🟠 Orange',
        'upah_harian' => $tarif,
        'upah_lembur' => $tarif * 0.5,
        'total' => $tarif * 1.5,
        'bayar' => 'Kamis minggu ini',
        'icon' => '🕒'
    ],
    [
        'no' => '4️⃣',
        'name' => 'LEMBUR FULL CASH',
        'color' => '🟢 Hijau (Bold)',
        'upah_harian' => $tarif,
        'upah_lembur' => $tarif,
        'total' => $tarif * 2,
        'bayar' => '💰 HARI INI (CASH)',
        'icon' => '💵',
        'special' => true
    ],
    [
        'no' => '5️⃣',
        'name' => 'LEMBUR SETENGAH CASH',
        'color' => '🔵 Biru (Bold)',
        'upah_harian' => $tarif,
        'upah_lembur' => $tarif * 0.5,
        'total' => $tarif * 1.5,
        'bayar' => '💰 HARI INI (CASH)',
        'icon' => '💵',
        'special' => true
    ]
];

foreach ($scenarios as $s) {
    echo "{$s['no']} {$s['icon']} {$s['name']}\n";
    echo "   Warna Tombol: {$s['color']}\n";
    echo "   Upah Harian: Rp " . number_format($s['upah_harian'], 0, ',', '.') . "\n";
    if ($s['upah_lembur'] > 0) {
        echo "   Bonus Lembur: Rp " . number_format($s['upah_lembur'], 0, ',', '.') . "\n";
    }
    echo "   TOTAL: Rp " . number_format($s['total'], 0, ',', '.') . "\n";
    echo "   Dibayar: {$s['bayar']}\n";
    if (isset($s['special'])) {
        echo "   ⭐ KHUSUS: Lembur dibayar CASH hari ini!\n";
    }
    echo "\n";
}

echo str_repeat("─", 68) . "\n\n";

echo "📊 PERBEDAAN UTAMA:\n\n";

echo "┌─────────────────────────────────────────────────────────────────┐\n";
echo "│ LEMBUR NORMAL (Merah/Orange)                                   │\n";
echo "│ • Dibayar hari KAMIS bersamaan gaji mingguan                   │\n";
echo "│ • Untuk lembur terencana                                       │\n";
echo "│ • Termasuk dalam payroll regular                               │\n";
echo "└─────────────────────────────────────────────────────────────────┘\n\n";

echo "┌─────────────────────────────────────────────────────────────────┐\n";
echo "│ LEMBUR CASH (Hijau/Biru) 💰                                    │\n";
echo "│ • Dibayar HARI ITU JUGA (cash langsung)                        │\n";
echo "│ • Untuk lembur mendesak/urgent                                 │\n";
echo "│ • Tukang terima uang langsung setelah kerja                    │\n";
echo "│ • Motivasi extra untuk deadline ketat                          │\n";
echo "└─────────────────────────────────────────────────────────────────┘\n\n";

echo "💡 CARA PAKAI:\n";
echo "1. Buka halaman 'Kehadiran Tukang'\n";
echo "2. Klik tombol status sampai HIJAU (Hadir)\n";
echo "3. Klik tombol LEMBUR untuk cycle melalui 5 pilihan\n";
echo "4. Pilih warna sesuai kebutuhan:\n";
echo "   - Hijau 💰 = Butuh cash full hari ini\n";
echo "   - Biru 💰 = Butuh cash setengah hari ini\n";
echo "   - Merah = Lembur full bayar Kamis\n";
echo "   - Orange = Lembur setengah bayar Kamis\n";
echo "   - Abu-abu = Tidak lembur\n\n";

echo "✅ FITUR SIAP DIGUNAKAN!\n";
echo "📱 Refresh halaman dan coba sekarang!\n\n";
