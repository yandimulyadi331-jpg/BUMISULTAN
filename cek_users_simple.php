<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║  CEK STRUKTUR TABEL USERS                                    ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$users = DB::table('users')->limit(3)->get();

echo "📊 SAMPLE DATA DI TABEL USERS:\n";
echo "   Total users: " . DB::table('users')->count() . "\n\n";

foreach ($users as $user) {
    echo "═══════════════════════════════════════\n";
    foreach ((array)$user as $key => $value) {
        if ($key == 'password') {
            echo "   $key: " . substr($value, 0, 30) . "...\n";
        } else {
            echo "   $key: $value\n";
        }
    }
    echo "\n";
}

echo "\n💡 KESIMPULAN:\n";
echo "   Dari struktur di atas, kita bisa lihat kolom apa yang\n";
echo "   menghubungkan users dengan karyawan.\n";
