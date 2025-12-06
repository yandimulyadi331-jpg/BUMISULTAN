<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Kendaraan;

$kendaraan = Kendaraan::with('peminjamanAktif')->first();

if ($kendaraan->peminjamanAktif && $kendaraan->status !== 'dipinjam') {
    echo "Fixing status...\n";
    $kendaraan->status = 'dipinjam';
    $kendaraan->save();
    echo "✅ Status updated to 'dipinjam'\n";
} else {
    echo "Status sudah benar atau tidak ada peminjaman aktif\n";
}
