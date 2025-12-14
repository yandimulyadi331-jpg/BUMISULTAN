<?php
// Check migration status
require 'vendor/autoload.php';

try {
    $dbHost = '127.0.0.1';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'bumisultansuperapp_v2';

    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== MIGRATIONS TABLE ===\n\n";

    $stmt = $pdo->query("SELECT * FROM migrations WHERE migration LIKE '%qr_code%' OR migration LIKE '%barang%'");
    $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($migrations)) {
        echo "❌ No QR code or barang migrations found!\n";
    } else {
        echo "✅ Found " . count($migrations) . " relevant migrations:\n\n";
        foreach ($migrations as $mig) {
            echo "Migration: {$mig['migration']}\n";
            echo "  Batch: {$mig['batch']}\n";
            echo "  Executed at: {$mig['execution_time']}ms\n\n";
        }
    }

    // Look specifically for the QR code migration
    echo "\n=== CHECKING QR CODE MIGRATION SPECIFICALLY ===\n";
    $stmt = $pdo->query("SELECT * FROM migrations WHERE migration = '2025_12_12_000001_add_qr_code_to_barangs_table'");
    $qrMig = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($qrMig) {
        echo "✅ QR Code migration HAS been executed!\n";
        echo "  Batch: {$qrMig['batch']}\n";
        echo "  Time: {$qrMig['execution_time']}ms\n";
    } else {
        echo "❌ QR Code migration NOT FOUND in migrations table!\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
