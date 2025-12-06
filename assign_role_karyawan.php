<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

try {
    // Cek apakah role 'karyawan' sudah ada
    $role = Role::firstOrCreate(['name' => 'karyawan']);
    echo "✓ Role 'karyawan' sudah ada atau berhasil dibuat\n\n";
    
    // Tampilkan semua user
    $users = User::all();
    echo "=== DAFTAR USER ===\n";
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->implode(', ');
        echo "ID: {$user->id} | Email: {$user->email} | Nama: {$user->nama_lengkap} | Role: " . ($roles ?: 'TIDAK ADA ROLE') . "\n";
    }
    
    echo "\n=== SOLUSI ===\n";
    echo "Pilih user yang ingin diberi akses kendaraan:\n";
    echo "1. Login ke aplikasi sebagai super admin\n";
    echo "2. Atau jalankan command: php artisan tinker\n";
    echo "3. Ketik: User::find(ID_USER)->assignRole('karyawan');\n";
    echo "4. Atau assign role 'super admin' untuk akses penuh\n\n";
    
    // Auto assign role karyawan ke semua user yang belum punya role
    echo "=== AUTO FIX (OPSIONAL) ===\n";
    echo "Apakah ingin assign role 'karyawan' ke semua user yang belum punya role? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if(trim($line) == 'y'){
        $fixed = 0;
        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                $user->assignRole('karyawan');
                echo "✓ Role 'karyawan' di-assign ke: {$user->email}\n";
                $fixed++;
            }
        }
        echo "\nTotal {$fixed} user diperbaiki!\n";
    }
    fclose($handle);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
