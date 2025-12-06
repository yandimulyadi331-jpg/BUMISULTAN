<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "CEK USER DAN ROLE MEREKA\n";
echo "========================================\n\n";

// Cek user dan role
$users = DB::table('users')
    ->leftJoin('model_has_roles', function($join) {
        $join->on('users.id', '=', 'model_has_roles.model_id')
             ->where('model_has_roles.model_type', '=', 'App\Models\User');
    })
    ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
    ->select('users.id', 'users.email', 'users.name', 'roles.name as role_name')
    ->get();

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Nama: {$user->name}\n";
    echo "Role: " . ($user->role_name ?: '❌ TIDAK PUNYA ROLE (INI MASALAHNYA!)') . "\n";
    echo "----------------------------------------\n";
}

echo "\n✅ SCRIPT INI HANYA MEMBACA DATA, TIDAK MENGUBAH APAPUN!\n";
