<?php
// Simple test to check data in database
$conn = mysqli_connect("localhost", "root", "", "bumisultansuperapp_v2");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Count data
$yayasan = mysqli_query($conn, "SELECT COUNT(*) as total FROM yayasan_masar WHERE status_aktif = '1'");
$yayasan_row = mysqli_fetch_assoc($yayasan);

$majlis = mysqli_query($conn, "SELECT COUNT(*) as total FROM jamaah_majlis_taklim");
$majlis_row = mysqli_fetch_assoc($majlis);

echo "=== DATA CHECK ===\n";
echo "Yayasan Masar (aktif): " . $yayasan_row['total'] . "\n";
echo "Majlis Taklim: " . $majlis_row['total'] . "\n";

// Sample data
echo "\n=== SAMPLE YAYASAN DATA ===\n";
$sample = mysqli_query($conn, "SELECT kode_yayasan, nama, jenis_kelamin, no_identitas FROM yayasan_masar WHERE status_aktif = '1' LIMIT 3");
while ($row = mysqli_fetch_assoc($sample)) {
    echo $row['kode_yayasan'] . " | " . $row['nama'] . " | " . $row['jenis_kelamin'] . " | " . $row['no_identitas'] . "\n";
}

echo "\n=== SAMPLE MAJLIS DATA ===\n";
$sample2 = mysqli_query($conn, "SELECT id, nama_jamaah, jenis_kelamin, nik FROM jamaah_majlis_taklim LIMIT 3");
while ($row = mysqli_fetch_assoc($sample2)) {
    echo $row['id'] . " | " . $row['nama_jamaah'] . " | " . $row['jenis_kelamin'] . " | " . $row['nik'] . "\n";
}

mysqli_close($conn);
?>
