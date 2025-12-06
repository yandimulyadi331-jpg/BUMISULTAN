<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Menghapus data laporan lama...\n";
DB::table('laporan_keuangan')->truncate();
echo "✅ Selesai! Silakan download laporan baru dari halaman admin.\n";
