<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking Jamaah PIN Data...\n\n";

$jamaah = DB::table('jamaah_majlis_taklim')
    ->whereNotNull('pin_fingerprint')
    ->where('status_aktif', 1)
    ->select('id', 'nama_jamaah', 'nomor_jamaah', 'pin_fingerprint')
    ->limit(20)
    ->get();

if ($jamaah->isEmpty()) {
    echo "⚠️  Tidak ada jamaah dengan PIN!\n";
    exit(1);
}

echo "📊 Ditemukan {$jamaah->count()} jamaah dengan PIN:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Group by PIN to check duplicates
$pinGroups = [];
foreach ($jamaah as $j) {
    if (!isset($pinGroups[$j->pin_fingerprint])) {
        $pinGroups[$j->pin_fingerprint] = [];
    }
    $pinGroups[$j->pin_fingerprint][] = $j;
}

foreach ($pinGroups as $pin => $users) {
    if (count($users) > 1) {
        echo "❌ PIN {$pin} DUPLIKAT (" . count($users) . " orang):\n";
        foreach ($users as $u) {
            echo "   - {$u->nama_jamaah} (#{$u->id}) - {$u->nomor_jamaah}\n";
        }
    } else {
        $u = $users[0];
        echo "✅ PIN {$pin}: {$u->nama_jamaah} - {$u->nomor_jamaah}\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "💡 Saran: Hapus PIN duplikat atau set unique PIN untuk setiap jamaah\n";
