<?php
/**
 * Check and clean data distribusi_hadiah sebelum migration
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
    
    echo "=== CHECK & CLEAN DATA DISTRIBUSI HADIAH ===\n\n";
    
    // 1. Cek data yayasan_masar yang ada
    echo "1. Data YAYASAN_MASAR yang ada:\n";
    $result = $pdo->query("SELECT id, kode_yayasan, nama FROM yayasan_masar ORDER BY id");
    $yayasanIds = [];
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $yayasanIds[] = $row['id'];
        echo "   ID: {$row['id']}, Kode: {$row['kode_yayasan']}, Nama: {$row['nama']}\n";
    }
    
    // 2. Cek data distribusi_hadiah
    echo "\n2. Data DISTRIBUSI_HADIAH yang ada:\n";
    $result = $pdo->query("SELECT id, nomor_distribusi, jamaah_id, penerima FROM distribusi_hadiah");
    $distribusiRows = $result->fetchAll(PDO::FETCH_ASSOC);
    echo "   Total records: " . count($distribusiRows) . "\n";
    
    $invalidIds = [];
    foreach ($distribusiRows as $row) {
        if (!in_array($row['jamaah_id'], $yayasanIds) && $row['jamaah_id'] !== null) {
            $invalidIds[] = $row['jamaah_id'];
            echo "   ⚠️  ID: {$row['id']}, jamaah_id: {$row['jamaah_id']} (INVALID!)\n";
        }
    }
    
    if (empty($invalidIds)) {
        echo "   ✅ Semua jamaah_id valid atau NULL\n";
    } else {
        echo "\n3. SET INVALID jamaah_id TO NULL:\n";
        $uniqueInvalidIds = array_unique($invalidIds);
        foreach ($uniqueInvalidIds as $invalidId) {
            $pdo->exec("UPDATE distribusi_hadiah SET jamaah_id = NULL WHERE jamaah_id = {$invalidId}");
            echo "   ✅ Updated: jamaah_id {$invalidId} → NULL\n";
        }
    }
    
    echo "\n=== VERIFIKASI SETELAH CLEAN ===\n";
    $result = $pdo->query("SELECT COUNT(*) as valid, SUM(CASE WHEN jamaah_id IS NULL THEN 1 ELSE 0 END) as null_count FROM distribusi_hadiah");
    $data = $result->fetch(PDO::FETCH_ASSOC);
    echo "Total records: {$data['valid']}\n";
    echo "Records dengan jamaah_id NULL: {$data['null_count']}\n";
    echo "✅ Data siap untuk migration!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
