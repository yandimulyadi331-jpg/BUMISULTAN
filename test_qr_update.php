<?php
// Test if we can successfully update barang with QR code columns
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Barang;

try {
    echo "=== Testing Barang QR Code Update ===\n\n";
    
    // Get first barang
    $barang = Barang::first();
    
    if (!$barang) {
        echo "❌ No barang found in database\n";
        exit(1);
    }
    
    echo "Testing barang ID: {$barang->id}\n";
    echo "Current values:\n";
    echo "  - qr_code_hash: " . ($barang->qr_code_hash ?? 'NULL') . "\n";
    echo "  - qr_code_path: " . ($barang->qr_code_path ?? 'NULL') . "\n";
    echo "  - qr_code_data: " . (strlen($barang->qr_code_data ?? '') > 50 ? substr($barang->qr_code_data, 0, 50) . '...' : ($barang->qr_code_data ?? 'NULL')) . "\n";
    echo "\n";
    
    // Try to update QR code columns
    echo "Attempting to update QR code columns...\n";
    $barang->qr_code_hash = 'TEST_HASH_' . time();
    $barang->qr_code_path = 'qr_codes/test_' . time() . '.svg';
    $barang->qr_code_data = '<svg>test</svg>';
    
    $barang->save();
    
    echo "✅ Successfully saved barang with QR code columns!\n";
    echo "\nUpdated values:\n";
    echo "  - qr_code_hash: {$barang->qr_code_hash}\n";
    echo "  - qr_code_path: {$barang->qr_code_path}\n";
    echo "  - qr_code_data: " . substr($barang->qr_code_data, 0, 50) . "...\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nFull trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
