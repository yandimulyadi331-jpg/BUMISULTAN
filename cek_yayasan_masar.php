<?php
/**
 * Cek struktur tabel yayasan_masar dan mapping dengan jamaah
 */

try {
    $envPath = __DIR__ . '/.env';
    $envFile = file_get_contents($envPath);
    
    $env = [];
    foreach (explode("\n", $envFile) as $line) {
        if (strpos($line, '=') === false || strpos($line, '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
    
    $pdo = new PDO(
        'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_DATABASE'],
        $env['DB_USERNAME'],
        $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "=== CEK TABEL yayasan_masar ===\n\n";
    
    // 1. Struktur tabel
    echo "1. STRUKTUR TABEL yayasan_masar:\n";
    $result = $pdo->query("DESCRIBE yayasan_masar");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "Columns: ";
    echo implode(", ", array_column($columns, 'Field')) . "\n\n";
    
    // 2. Data dengan kode_yayasan 251200009
    echo "2. CEK DATA DENGAN KODE_YAYASAN 251200009:\n";
    $result = $pdo->query("SELECT * FROM yayasan_masar WHERE kode_yayasan = '251200009'");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    
    if ($data) {
        echo "Ditemukan:\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        
        // 3. Check apakah ada jamaah_majlis_taklim yang sesuai
        echo "3. CEK JAMAAH TERKAIT:\n";
        
        // Check berdasarkan nama
        $nama = $data['nama'] ?? $data['nama_yayasan'] ?? '';
        if (!empty($nama)) {
            echo "Mencari jamaah dengan nama: {$nama}\n";
            $result = $pdo->query("SELECT id, nama_jamaah FROM jamaah_majlis_taklim WHERE nama_jamaah LIKE '%" . addslashes($nama) . "%'");
            $jamaahList = $result->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($jamaahList) > 0) {
                echo "Ditemukan jamaah:\n";
                foreach ($jamaahList as $jamaah) {
                    echo "- ID: {$jamaah['id']}, Nama: {$jamaah['nama_jamaah']}\n";
                }
            } else {
                echo "Tidak ada jamaah dengan nama serupa\n";
            }
        }
        
    } else {
        echo "❌ Tidak ditemukan kode_yayasan 251200009\n";
        
        // List semua yayasan
        echo "\nSemua yayasan_masar:\n";
        $result = $pdo->query("SELECT id, kode_yayasan, nama FROM yayasan_masar LIMIT 20");
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo "- ID: {$row['id']}, Kode: {$row['kode_yayasan']}, Nama: {$row['nama']}\n";
        }
    }
    
    // 4. Cek apakah ada foreign key dari yayasan_masar ke jamaah_majlis_taklim
    echo "\n4. CEK FOREIGN KEY DARI yayasan_masar:\n";
    $result = $pdo->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                           FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                           WHERE TABLE_NAME = 'yayasan_masar' AND COLUMN_NAME != 'PRIMARY'");
    $fks = $result->fetchAll(PDO::FETCH_ASSOC);
    if (count($fks) > 0) {
        foreach ($fks as $fk) {
            echo "- {$fk['COLUMN_NAME']} → {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
        }
    } else {
        echo "Tidak ada foreign key\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
