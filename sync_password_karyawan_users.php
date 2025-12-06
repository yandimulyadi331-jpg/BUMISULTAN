<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║  SYNC PASSWORD: KARYAWAN → USERS                             ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "Script ini akan menyamakan password di tabel users dengan password\n";
echo "di tabel karyawan, sehingga karyawan bisa login dengan password\n";
echo "yang mereka input saat signup.\n\n";

echo "⚠️  PERHATIAN: Ini hanya untuk karyawan yang SUDAH PUNYA user account!\n";
echo "   Untuk karyawan baru yang signup setelah update, akan otomatis benar.\n\n";

$confirm = readline("Lanjutkan sync password? (yes/no): ");

if (strtolower($confirm) !== 'yes') {
    echo "\n❌ Dibatalkan.\n\n";
    exit;
}

echo "\n";

// Ambil semua karyawan yang punya user account
$karyawan_list = DB::table('karyawan')
    ->join('users', 'users.username', '=', 'karyawan.nik')
    ->select('karyawan.nik', 'karyawan.nama_karyawan', 'karyawan.password as karyawan_password', 'users.id as user_id', 'users.password as user_password')
    ->get();

echo "📊 Total karyawan dengan user account: " . $karyawan_list->count() . "\n\n";

$updated = 0;
$skipped = 0;

foreach ($karyawan_list as $k) {
    echo "Processing: {$k->nik} - {$k->nama_karyawan}\n";
    
    if ($k->karyawan_password === $k->user_password) {
        echo "   ✅ Password sudah match, skip\n\n";
        $skipped++;
        continue;
    }
    
    // Update password di users dengan password dari karyawan
    DB::table('users')
        ->where('id', $k->user_id)
        ->update([
            'password' => $k->karyawan_password,
            'updated_at' => now()
        ]);
    
    echo "   ✅ Password di-update!\n";
    echo "      User sekarang bisa login dengan password signup\n\n";
    $updated++;
}

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  SUMMARY                                                     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "✅ Berhasil update: $updated user\n";
echo "⏭️  Di-skip (sudah match): $skipped user\n";
echo "\n";
echo "💡 Sekarang karyawan bisa login dengan password yang mereka\n";
echo "   input saat signup!\n\n";
