<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== HAPUS SESSION LAMA ===\n";

// Hapus semua session
DB::table('sessions')->truncate();
echo "✓ Semua session telah dihapus\n";

// Hapus cache
DB::table('cache')->truncate();
echo "✓ Cache telah dihapus\n";
