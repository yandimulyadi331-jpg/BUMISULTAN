<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

echo "========================================\n";
echo "ASSIGN ROLE KE USER (AMAN - TIDAK HAPUS DATA!)\n";
echo "========================================\n\n";

// Cek apakah role 'super admin' ada
$superAdminRole = Role::where('name', 'super admin')->first();
if (!$superAdminRole) {
    echo "❌ Role 'super admin' tidak ditemukan!\n";
    echo "Membuat role 'super admin'...\n";
    $superAdminRole = Role::create(['name' => 'super admin']);
    echo "✅ Role 'super admin' berhasil dibuat!\n\n";
}

// Tanyakan user mana yang mau di-assign
echo "Daftar User:\n";
echo str_repeat("-", 80) . "\n";

$users = User::all();
foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->join(', ') ?: '(tidak ada role)';
    echo "[{$user->id}] {$user->email} - Role: {$roles}\n";
}

echo str_repeat("-", 80) . "\n";
echo "\nMasukkan ID user yang ingin diberi role 'super admin': ";
$userId = trim(fgets(STDIN));

if (empty($userId) || !is_numeric($userId)) {
    echo "❌ ID tidak valid!\n";
    exit(1);
}

$user = User::find($userId);
if (!$user) {
    echo "❌ User dengan ID {$userId} tidak ditemukan!\n";
    exit(1);
}

// Cek apakah user sudah punya role ini
if ($user->hasRole('super admin')) {
    echo "⚠️  User {$user->email} SUDAH memiliki role 'super admin'!\n";
    exit(0);
}

// Assign role (AMAN - hanya menambahkan, tidak menghapus)
$user->assignRole('super admin');

echo "✅ BERHASIL! Role 'super admin' telah di-assign ke:\n";
echo "   Email: {$user->email}\n";
echo "   ID: {$user->id}\n";
echo "\n📌 Sekarang login dengan email ini untuk akses /wagateway\n";
echo "📌 DATA LAIN TETAP AMAN, TIDAK ADA YANG DIHAPUS!\n";
