<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║  ANALISA PASSWORD & LOGIN KARYAWAN                           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$nik = '251100001';
$karyawan = Karyawan::where('nik', $nik)->first();

if (!$karyawan) {
    echo "❌ Karyawan dengan NIK $nik tidak ditemukan!\n";
    exit;
}

echo "📋 DATA KARYAWAN:\n";
echo "   NIK: {$karyawan->nik}\n";
echo "   Nama: {$karyawan->nama_karyawan}\n";
echo "   Status: " . ($karyawan->status_aktif_karyawan == 1 ? 'Aktif' : 'Non Aktif') . "\n";
echo "\n";

echo "🔐 KOLOM AUTH YANG TERSEDIA:\n";
$columns = \DB::getSchemaBuilder()->getColumnListing('karyawan');
$auth_columns = array_filter($columns, function($col) {
    return in_array($col, ['username', 'email', 'password', 'pin', 'nik']);
});

foreach ($auth_columns as $col) {
    $value = $karyawan->$col ?? 'NULL';
    if ($col == 'password') {
        $is_hashed = strlen($value) == 60 && str_starts_with($value, '$2y$');
        echo "   ✓ $col: " . ($is_hashed ? "✅ HASHED (bcrypt)" : "⚠️ Plain text atau format lain") . "\n";
        echo "     Value: " . substr($value, 0, 30) . "...\n";
    } else {
        echo "   ✓ $col: $value\n";
    }
}

echo "\n";

echo "🧪 TEST PASSWORD:\n";
$test_passwords = ['password', '12345678', '251100001', 'yandi123', 'admin'];

foreach ($test_passwords as $test_pass) {
    $match = Hash::check($test_pass, $karyawan->password);
    $icon = $match ? "✅" : "❌";
    echo "   $icon Test password '$test_pass': " . ($match ? "MATCH!" : "No match") . "\n";
}

echo "\n";

echo "📝 INFO AUTH CONFIG:\n";
echo "   • LoginRequest mencari field: 'username' atau 'email'\n";
echo "   • Auth::attempt() menggunakan kolom tersebut + password\n";
echo "   • Password harus di-hash dengan bcrypt (Hash::make)\n";
echo "\n";

echo "⚠️  MASALAH YANG DITEMUKAN:\n";

// Cek apakah ada kolom username atau email
if (!in_array('username', $columns) && !in_array('email', $columns)) {
    echo "   ❌ TIDAK ADA KOLOM 'username' atau 'email' di tabel karyawan!\n";
    echo "   ❌ LoginRequest mencari salah satu kolom ini!\n";
    echo "   ❌ Karyawan tidak bisa login tanpa username/email!\n";
    echo "\n";
    echo "💡 SOLUSI:\n";
    echo "   1. Tambah kolom 'username' di tabel karyawan\n";
    echo "   2. Update SignupController untuk simpan username\n";
    echo "   3. Atau gunakan NIK sebagai username\n";
} else {
    echo "   ℹ️  Kolom auth tersedia, cek apakah signup mengisi kolom tersebut\n";
}

echo "\n";
