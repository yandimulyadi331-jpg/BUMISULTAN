<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║  CEK TABEL USERS & RELASI DENGAN KARYAWAN                    ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Cek tabel users
$users_columns = \DB::getSchemaBuilder()->getColumnListing('users');
echo "📋 KOLOM DI TABEL USERS:\n";
foreach ($users_columns as $col) {
    echo "   • $col\n";
}

echo "\n";

// Cek data user untuk NIK 251100001
$user = DB::table('users')
    ->join('karyawan', 'karyawan.nik', '=', 'users.nik')
    ->where('karyawan.nik', '251100001')
    ->select('users.*', 'karyawan.nik', 'karyawan.nama_karyawan')
    ->first();

if ($user) {
    echo "✅ USER DITEMUKAN UNTUK NIK 251100001:\n";
    echo "   ID User: {$user->id}\n";
    echo "   Username: {$user->username}\n";
    echo "   Email: {$user->email}\n";
    echo "   NIK: {$user->nik}\n";
    echo "   Nama: {$user->nama_karyawan}\n";
    echo "   Password (hash): " . substr($user->password, 0, 30) . "...\n";
    echo "\n";
    echo "✅ KARYAWAN SUDAH PUNYA AKUN USER!\n";
} else {
    echo "❌ USER TIDAK DITEMUKAN UNTUK NIK 251100001!\n";
    echo "\n";
    echo "⚠️  KESIMPULAN:\n";
    echo "   • Tabel users terpisah dari tabel karyawan\n";
    echo "   • Login menggunakan tabel users (username + password)\n";
    echo "   • Signup hanya membuat data di tabel karyawan\n";
    echo "   • Perlu CREATE USER untuk karyawan bisa login!\n";
    echo "\n";
    echo "💡 CARA CREATE USER:\n";
    echo "   1. Login sebagai admin\n";
    echo "   2. Data Master > Karyawan\n";
    echo "   3. Klik icon USER PLUS (merah) di kolom aksi\n";
    echo "   4. Set username & password untuk karyawan\n";
}

echo "\n";

// Cari karyawan yang belum punya user
$karyawan_tanpa_user = DB::table('karyawan')
    ->leftJoin('users', 'users.nik', '=', 'karyawan.nik')
    ->whereNull('users.id')
    ->select('karyawan.nik', 'karyawan.nama_karyawan', 'karyawan.status_aktif_karyawan')
    ->get();

echo "📊 KARYAWAN TANPA USER ACCOUNT:\n";
echo "   Total: " . $karyawan_tanpa_user->count() . " karyawan\n\n";

foreach ($karyawan_tanpa_user as $k) {
    $status = $k->status_aktif_karyawan == 1 ? '✅ Aktif' : '❌ Non Aktif';
    echo "   • {$k->nik} - {$k->nama_karyawan} ($status)\n";
}

echo "\n";
