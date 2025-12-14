<?php
// Quick script to fix missing QR code columns
require 'vendor/autoload.php';

try {
    $dbHost = '127.0.0.1';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'bumisultansuperapp_v2';

    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connected to database: {$dbName}\n\n";

    // Get current table structure
    $stmt = $pdo->query("DESCRIBE barangs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $existingColumns = array_column($columns, 'Field');

    echo "Current columns in barangs table:\n";
    print_r($existingColumns);
    echo "\n";

    // Check which columns are missing
    $requiredColumns = ['qr_code_data', 'qr_code_hash', 'qr_code_path', 'status_barang'];
    $missingColumns = array_diff($requiredColumns, $existingColumns);

    if (empty($missingColumns)) {
        echo "✅ All required QR code columns exist!\n";
    } else {
        echo "❌ Missing columns: " . implode(', ', $missingColumns) . "\n\n";
        echo "Adding missing columns...\n\n";

        // Add missing columns
        if (in_array('qr_code_data', $missingColumns)) {
            $pdo->exec("ALTER TABLE barangs ADD COLUMN qr_code_data LONGTEXT NULL COMMENT 'Data URL untuk QR Code PNG' AFTER foto");
            echo "✅ Added qr_code_data column\n";
        }

        if (in_array('qr_code_hash', $missingColumns)) {
            $pdo->exec("ALTER TABLE barangs ADD COLUMN qr_code_hash VARCHAR(64) NULL UNIQUE COMMENT 'Hash unik untuk URL publik' AFTER qr_code_data");
            echo "✅ Added qr_code_hash column\n";
        }

        if (in_array('qr_code_path', $missingColumns)) {
            $pdo->exec("ALTER TABLE barangs ADD COLUMN qr_code_path VARCHAR(255) NULL COMMENT 'Path file QR Code PNG' AFTER qr_code_hash");
            echo "✅ Added qr_code_path column\n";
        }

        if (in_array('status_barang', $missingColumns)) {
            $pdo->exec("ALTER TABLE barangs ADD COLUMN status_barang ENUM('Aktif', 'Rusak Total', 'Hilang') NOT NULL DEFAULT 'Aktif' COMMENT 'Status barang untuk kontrol inventaris' AFTER keterangan");
            echo "✅ Added status_barang column\n";
        }

        // Add index for qr_code_hash
        $indexCheck = $pdo->query("SHOW INDEX FROM barangs WHERE Key_name = 'qr_code_hash'")->fetch();
        if (!$indexCheck) {
            $pdo->exec("CREATE UNIQUE INDEX qr_code_hash ON barangs(qr_code_hash)");
            echo "✅ Created index on qr_code_hash\n";
        }

        echo "\n✅ All columns added successfully!\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
