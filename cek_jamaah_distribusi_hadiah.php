<?php
/**
 * Script untuk mengecek data jamaah dan foreign key constraint
 * untuk problem distribusi hadiah dengan jamaah_id 251200009
 */

try {
    // Load .env file
    $envPath = __DIR__ . '/.env';
    $envFile = file_get_contents($envPath);
    
    // Parse .env
    $env = [];
    foreach (explode("\n", $envFile) as $line) {
        if (strpos($line, '=') === false || strpos($line, '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
    
    // Setup database connection
    $pdo = new PDO(
        'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_DATABASE'],
        $env['DB_USERNAME'],
        $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "=== CEK DATA JAMAAH DISTRIBUSI HADIAH ===\n";
    echo "Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
    
    // 1. Lihat struktur tabel jamaah_majlis_taklim
    echo "1. STRUKTUR TABEL jamaah_majlis_taklim:\n";
    $result = $pdo->query("DESCRIBE jamaah_majlis_taklim");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "Columns: ";
    echo implode(", ", array_column($columns, 'Field')) . "\n\n";
    
    // 2. Cek apakah jamaah_id 251200009 ada
    echo "2. CEK JAMAAH ID 251200009:\n";
    $result = $pdo->query("SELECT * FROM jamaah_majlis_taklim WHERE id = 251200009");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    
    if ($data) {
        echo "✅ DITEMUKAN:\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "❌ TIDAK DITEMUKAN di jamaah_majlis_taklim\n\n";
    }
    
    // 3. Cek apakah ID ini ada di tabel lain
    echo "3. CEK ID 251200009 DI TABEL LAIN:\n";
    
    // Check yayasan_masar
    $tables = ['yayasan_masar', 'yayasan', 'user', 'users'];
    foreach ($tables as $table) {
        $result = $pdo->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '$table'");
        $check = $result->fetch(PDO::FETCH_ASSOC);
        
        if ($check['cnt'] > 0) {
            $result = $pdo->query("SELECT * FROM $table WHERE id = 251200009");
            $data = $result->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                echo "✅ Ditemukan di $table:\n";
                echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        }
    }
    
    // 4. Lihat total jamaah
    echo "\n4. TOTAL JAMAAH DI jamaah_majlis_taklim:\n";
    $result = $pdo->query("SELECT COUNT(*) as total FROM jamaah_majlis_taklim");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    echo "Total: " . $data['total'] . " jamaah\n";
    
    // 5. Lihat range ID
    echo "\n5. RANGE ID JAMAAH:\n";
    $result = $pdo->query("SELECT MIN(id) as min_id, MAX(id) as max_id FROM jamaah_majlis_taklim");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    echo "Min ID: " . $data['min_id'] . "\n";
    echo "Max ID: " . $data['max_id'] . "\n";
    echo "ID 251200009 dalam range? " . ($data['min_id'] <= 251200009 && $data['max_id'] >= 251200009 ? 'YA' : 'TIDAK') . "\n";
    
    // 6. Lihat contoh jamaah
    echo "\n6. CONTOH JAMAAH (10 PERTAMA):\n";
    $result = $pdo->query("SELECT id, nomor_jamaah, nama_jamaah FROM jamaah_majlis_taklim LIMIT 10");
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "- ID: {$row['id']}, No: {$row['nomor_jamaah']}, Nama: {$row['nama_jamaah']}\n";
    }
    
    // 7. Cek foreign key constraint di distribusi_hadiah
    echo "\n7. FOREIGN KEY CONSTRAINT DI distribusi_hadiah:\n";
    $result = $pdo->query("SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                           FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                           WHERE TABLE_NAME = 'distribusi_hadiah' AND COLUMN_NAME = 'jamaah_id'");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        echo "Constraint: " . $data['CONSTRAINT_NAME'] . "\n";
        echo "Referencing: {$data['TABLE_NAME']}.{$data['COLUMN_NAME']}\n";
        echo "Referenced: {$data['REFERENCED_TABLE_NAME']}.{$data['REFERENCED_COLUMN_NAME']}\n";
    }
    
    // 8. Cari jamaah DANI
    echo "\n8. CARI JAMAAH DENGAN NAMA MENGANDUNG 'DANI':\n";
    $result = $pdo->query("SELECT id, nomor_jamaah, nama_jamaah FROM jamaah_majlis_taklim WHERE nama_jamaah LIKE '%DANI%' LIMIT 10");
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($rows) > 0) {
        echo "Ditemukan:\n";
        foreach ($rows as $row) {
            echo "- ID: {$row['id']}, Nama: {$row['nama_jamaah']}\n";
        }
    } else {
        echo "Tidak ada jamaah dengan nama mengandung 'DANI'\n";
    }
    
    echo "\n=== KESIMPULAN ===\n";
    echo "ID 251200009 tidak ada di tabel jamaah_majlis_taklim\n";
    echo "\nSOLUSI:\n";
    echo "1. Gunakan ID jamaah yang benar dari list di atas\n";
    echo "2. Atau set jamaah_id = NULL jika penerima bukan jamaah terdaftar\n";
    echo "3. Atau tambahkan jamaah baru dengan ID 251200009\n";
    
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
?>
