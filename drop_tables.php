<?php
// Quick script to drop tables
$host = 'localhost';
$db = 'bumisultansuperapp_v2';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

try {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    $pdo->exec('DROP TABLE IF EXISTS distribusi_hadiah_yayasan_masar');
    $pdo->exec('DROP TABLE IF EXISTS hadiah_yayasan_masar');
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    echo "✓ Tables dropped successfully!\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
