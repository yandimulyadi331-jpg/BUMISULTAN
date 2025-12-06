<?php
// Load environment
require __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Direct database connection
try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'] . ";charset=utf8mb4",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query jamaah
    $stmt = $pdo->prepare("SELECT * FROM jamaah_masar WHERE nik = ?");
    $stmt->execute(['3201062404000076']);
    $jamaah = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($jamaah) {
        echo "=== DATA JAMAAH ===\n";
        echo "Nama: " . $jamaah->nama_jamaah . "\n";
        echo "NIK: " . $jamaah->nik . "\n";
        echo "Foto (dari DB): " . ($jamaah->foto ?: 'NULL/KOSONG') . "\n\n";
        
        if ($jamaah->foto) {
            echo "=== CEK FILE FOTO ===\n";
            $fotoClean = ltrim($jamaah->foto, '/');
            
            $basePath = __DIR__;
            $paths = [
                'storage/app/public/' . $fotoClean => $basePath . '/storage/app/public/' . $fotoClean,
                'public/storage/' . $fotoClean => $basePath . '/public/storage/' . $fotoClean,
                'public/' . $fotoClean => $basePath . '/public/' . $fotoClean,
            ];
            
            $found = false;
            foreach ($paths as $label => $fullPath) {
                $exists = file_exists($fullPath);
                echo "$label: " . ($exists ? "✅ FOUND" : "❌ NOT FOUND") . "\n";
                echo "   Full path: $fullPath\n";
                if ($exists && is_file($fullPath)) {
                    $found = true;
                    echo "   File size: " . filesize($fullPath) . " bytes\n";
                    echo "   MIME type: " . mime_content_type($fullPath) . "\n";
                }
                echo "\n";
            }
            
            echo "\n=== URL YANG DIGUNAKAN DI ID CARD ===\n";
            echo "asset('storage/" . $fotoClean . "')\n";
            echo "Which resolves to: http://localhost:8001/storage/" . $fotoClean . "\n\n";
            
            if (!$found) {
                echo "❌ FILE TIDAK DITEMUKAN DI SEMUA LOKASI!\n";
                echo "⚠️  Kemungkinan:\n";
                echo "   1. File belum diupload\n";
                echo "   2. Path di database salah\n";
                echo "   3. Storage link belum dibuat\n";
            }
        } else {
            echo "❌ FOTO FIELD IS NULL OR EMPTY IN DATABASE!\n";
        }
    } else {
        echo "❌ JAMAAH NOT FOUND!\n";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
