<?php
// Detailed database structure verification
require 'vendor/autoload.php';

try {
    $dbHost = '127.0.0.1';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'bumisultansuperapp_v2';

    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== DETAILED TABLE STRUCTURE ===\n\n";

    // Get full table structure with more details
    $stmt = $pdo->query("SHOW FULL COLUMNS FROM barangs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Total columns: " . count($columns) . "\n\n";

    foreach ($columns as $col) {
        echo "Name: {$col['Field']}\n";
        echo "  Type: {$col['Type']}\n";
        echo "  Null: {$col['Null']}\n";
        echo "  Key: {$col['Key']}\n";
        echo "  Default: {$col['Default']}\n";
        echo "  Extra: {$col['Extra']}\n";
        echo "  Comment: {$col['Comment']}\n\n";
    }

    // Check if qr_code columns exist
    echo "\n=== QR CODE COLUMNS CHECK ===\n";
    $qrColumns = array_filter($columns, function($col) {
        return strpos($col['Field'], 'qr_code') !== false;
    });

    if (empty($qrColumns)) {
        echo "❌ NO QR CODE COLUMNS FOUND!\n";
    } else {
        echo "✅ Found " . count($qrColumns) . " QR code columns:\n";
        foreach ($qrColumns as $col) {
            echo "  - {$col['Field']} ({$col['Type']})\n";
        }
    }

    // Test update statement
    echo "\n=== TESTING UPDATE STATEMENT ===\n";
    try {
        $testStmt = $pdo->prepare("UPDATE barangs SET qr_code_hash = ? WHERE id = ? LIMIT 1");
        echo "✅ UPDATE statement prepared successfully\n";
    } catch (\Exception $e) {
        echo "❌ UPDATE statement failed: " . $e->getMessage() . "\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
