<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

echo "=== ASSIGN ROLE DEPARTEMEN KEBERSIHAN ===\n\n";

try {
    // Clear permission cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
    // Cari user yang login (kemungkinan Adam Adifa)
    echo "Mencari user...\n";
    
    // Coba cari user dengan nama "Adam"
    $users = User::where('name', 'LIKE', '%Adam%')->get();
    
    if ($users->isEmpty()) {
        echo "❌ User dengan nama 'Adam' tidak ditemukan\n";
        echo "Menampilkan semua user:\n\n";
        $allUsers = User::all();
        foreach ($allUsers as $u) {
            $roles = $u->roles->pluck('name')->implode(', ');
            echo "ID: {$u->id} | Email: {$u->email} | Nama: {$u->name} | Role: " . ($roles ?: 'TIDAK ADA') . "\n";
        }
        exit(1);
    }
    
    echo "✅ Ditemukan " . $users->count() . " user:\n";
    foreach ($users as $u) {
        $roles = $u->roles->pluck('name')->implode(', ');
        echo "  {$u->id}. {$u->name} ({$u->email}) - Role: " . ($roles ?: 'TIDAK ADA') . "\n";
    }
    
    // Ambil user pertama
    $user = $users->first();
    echo "\n✅ Assign role 'departemen kebersihan' ke: {$user->name}\n";
    
    // Cek apakah role exists
    $role = Role::where('name', 'departemen kebersihan')->first();
    if (!$role) {
        echo "❌ Role 'departemen kebersihan' belum ada!\n";
        echo "Jalankan: php artisan db:seed --class=DepartemenRoleSeeder\n";
        exit(1);
    }
    
    // Remove old roles (opsional)
    echo "Menghapus role lama...\n";
    $user->syncRoles([]); // Remove all roles
    
    // Assign new role
    $user->assignRole('departemen kebersihan');
    
    echo "✅ BERHASIL!\n\n";
    echo "User: {$user->name}\n";
    echo "Role: departemen kebersihan\n";
    echo "Permissions:\n";
    
    $permissions = $user->getAllPermissions();
    foreach ($permissions as $perm) {
        echo "  ✓ {$perm->name}\n";
    }
    
    echo "\n🎉 Silakan logout dan login kembali untuk melihat perubahan!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
