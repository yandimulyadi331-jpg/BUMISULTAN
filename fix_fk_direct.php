<?php
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
    
    echo "Cek foreign key constraints:\n";
    $result = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                           WHERE TABLE_NAME = 'distribusi_hadiah' AND COLUMN_NAME = 'jamaah_id' 
                           AND REFERENCED_TABLE_NAME IS NOT NULL");
    $fks = $result->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($fks) > 0) {
        foreach ($fks as $fk) {
            echo "Ada constraint: " . $fk['CONSTRAINT_NAME'] . "\n";
        }
    } else {
        echo "Tidak ada constraint untuk jamaah_id\n";
        
        // Tambah constraint ke yayasan_masar
        echo "\nTambah constraint baru...\n";
        $pdo->exec('ALTER TABLE distribusi_hadiah ADD CONSTRAINT distribusi_hadiah_jamaah_id_foreign 
                    FOREIGN KEY (jamaah_id) REFERENCES yayasan_masar(id) ON DELETE CASCADE');
        echo "✅ Constraint berhasil ditambah\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
