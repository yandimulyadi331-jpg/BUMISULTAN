<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK MAPPING JAMAAH ===\n\n";

$jamaah = App\Models\JamaahMajlisTaklim::find(12);

if ($jamaah) {
    echo "✅ Jamaah Ditemukan:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ID Database    : " . $jamaah->id . "\n";
    echo "Nama           : " . $jamaah->nama_jamaah . "\n";
    echo "Nomor Jamaah   : " . ($jamaah->nomor_jamaah ?? '-') . "\n";
    echo "Machine User ID: " . ($jamaah->machine_user_id ?? 'BELUM DI-MAPPING') . "\n";
    echo "Status         : " . ($jamaah->status_aktif ? 'Aktif' : 'Nonaktif') . "\n";
    echo "Jumlah Hadir   : " . ($jamaah->jumlah_kehadiran ?? 0) . "x\n";
    echo "Ditambahkan    : " . $jamaah->created_at->format('d/m/Y H:i:s') . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    if ($jamaah->machine_user_id) {
        echo "✅ STATUS: Sudah di-mapping! Siap untuk presensi.\n\n";
        echo "📝 Next Steps:\n";
        echo "   1. Pastikan jamaah sudah registrasi sidik jari di mesin dengan User ID: {$jamaah->machine_user_id}\n";
        echo "   2. Minta jamaah presensi (tap sidik jari)\n";
        echo "   3. Jalankan: php artisan jamaah:sync-attendance\n";
    } else {
        echo "⚠️  STATUS: Belum di-mapping!\n\n";
        echo "📝 Cara mapping:\n";
        echo "   php artisan jamaah:map-manual --jamaah-id=12 --machine-id=XXXX\n";
    }
} else {
    echo "❌ Jamaah ID 12 tidak ditemukan\n";
}

echo "\n=== STATISTIK MAPPING ===\n\n";
$totalJamaah = App\Models\JamaahMajlisTaklim::count();
$mappedJamaah = App\Models\JamaahMajlisTaklim::whereNotNull('machine_user_id')->count();
$unmappedJamaah = $totalJamaah - $mappedJamaah;

echo "Total Jamaah       : {$totalJamaah}\n";
echo "Sudah Di-mapping   : {$mappedJamaah}\n";
echo "Belum Di-mapping   : {$unmappedJamaah}\n";

if ($unmappedJamaah > 0) {
    echo "\n💡 Lihat daftar unmapped: php artisan jamaah:map-manual --list\n";
}
