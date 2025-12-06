<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║  VERIFIKASI AUTO CREATE USER SAAT SIGNUP                     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$nik = '251100001';

echo "🔍 CEK KARYAWAN NIK: $nik\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$karyawan = Karyawan::where('nik', $nik)->first();

if (!$karyawan) {
    echo "❌ Karyawan tidak ditemukan!\n";
    exit;
}

echo "✅ KARYAWAN DITEMUKAN:\n";
echo "   NIK: {$karyawan->nik}\n";
echo "   Nama: {$karyawan->nama_karyawan}\n";
echo "   Status: " . ($karyawan->status_aktif_karyawan == 1 ? 'Aktif ✅' : 'Non Aktif ⚠️') . "\n";
echo "   Password (hash): " . substr($karyawan->password, 0, 30) . "...\n";
echo "\n";

echo "🔍 CEK USER ACCOUNT:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$user = User::where('username', $nik)->first();

if (!$user) {
    echo "❌ USER TIDAK DITEMUKAN!\n";
    echo "⚠️  Karyawan belum punya akun login!\n";
    echo "💡 Signup belum otomatis create user.\n\n";
    
    echo "📝 INFO:\n";
    echo "   Jika ini karyawan lama (sebelum update):\n";
    echo "   → Admin harus create user manual\n";
    echo "   → Atau update code sudah aktif untuk signup baru\n";
    exit;
}

echo "✅ USER ACCOUNT DITEMUKAN:\n";
echo "   ID: {$user->id}\n";
echo "   Name: {$user->name}\n";
echo "   Username: {$user->username}\n";
echo "   Email: {$user->email}\n";
echo "   Password (hash): " . substr($user->password, 0, 30) . "...\n";
echo "\n";

echo "🔐 CEK PASSWORD CONSISTENCY:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

if ($karyawan->password === $user->password) {
    echo "✅ PASSWORD MATCH!\n";
    echo "   Hash di tabel karyawan = Hash di tabel users\n";
    echo "   User bisa login dengan password yang sama!\n";
} else {
    echo "❌ PASSWORD TIDAK MATCH!\n";
    echo "   Hash berbeda antara karyawan & users\n";
    echo "   Karyawan: " . substr($karyawan->password, 0, 30) . "...\n";
    echo "   Users:    " . substr($user->password, 0, 30) . "...\n";
    echo "\n";
    echo "⚠️  Kemungkinan:\n";
    echo "   • User account dibuat manual oleh admin\n";
    echo "   • Password di-set berbeda\n";
}

echo "\n";

echo "🧪 TEST LOGIN:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

if ($karyawan->status_aktif_karyawan == 1) {
    echo "✅ Status: AKTIF - User bisa login\n";
} else {
    echo "⚠️  Status: NON AKTIF - User belum bisa login\n";
    echo "💡 Admin harus ubah status ke 'Aktif' di halaman edit karyawan\n";
}

echo "\n";

echo "📋 INFO LOGIN:\n";
echo "   • URL: http://127.0.0.1:8000\n";
echo "   • Username: {$user->username}\n";
echo "   • Password: [password yang diinput saat signup]\n";
echo "\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ VERIFIKASI SELESAI!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
