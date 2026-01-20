<?php
// TEST: Debug issue sisa_pinjaman menjadi negatif

echo "=== DEBUG ISSUE SISA PINJAMAN NEGATIF ===\n\n";

// Skenario dari user:
// - Pembayaran 1: 2 juta
// - Pembayaran 2: 3 juta
// - Terlihat: Total pinjaman 4juta, sisa -1juta

$totalPinjaman = 5000000; // Asumsi: pinjaman awal 5juta
$totalTerbayar = 0;
$pembayaran = [2000000, 3000000];

echo "SEBELUM PEMBAYARAN:\n";
echo "  Total Pinjaman: Rp " . number_format($totalPinjaman, 0, ',', '.') . "\n";
echo "  Total Terbayar: Rp " . number_format($totalTerbayar, 0, ',', '.') . "\n";
echo "  Sisa Pinjaman: Rp " . number_format($totalPinjaman - $totalTerbayar, 0, ',', '.') . "\n\n";

foreach ($pembayaran as $index => $jumlahBayar) {
    $totalTerbayar += $jumlahBayar;
    $sisaPinjaman = $totalPinjaman - $totalTerbayar;
    
    echo "PEMBAYARAN " . ($index + 1) . ": Rp " . number_format($jumlahBayar, 0, ',', '.') . "\n";
    echo "  Total Terbayar (kumulatif): Rp " . number_format($totalTerbayar, 0, ',', '.') . "\n";
    echo "  Sisa Pinjaman: Rp " . number_format($sisaPinjaman, 0, ',', '.') . "\n";
    
    if ($sisaPinjaman < 0) {
        echo "  ⚠️  WARNING: Sisa pinjaman NEGATIF!\n";
        echo "     Ini berarti user membayar LEBIH dari total pinjaman\n";
        echo "     Kelebihan bayar (kembalian): Rp " . number_format(abs($sisaPinjaman), 0, ',', '.') . "\n";
    } elseif ($sisaPinjaman == 0) {
        echo "  ✅ Status: LUNAS (total pembayaran = total pinjaman)\n";
    } else {
        echo "  Status: BERJALAN (masih ada sisa)\n";
    }
    echo "\n";
}

echo "ISSUE ANALYSIS:\n";
echo "Masalah kemungkinan di:\n";
echo "1. Total pinjaman tidak ter-set dengan benar saat create\n";
echo "2. Ada pembayaran yang tidak ter-record\n";
echo "3. Ada cicilan yang di-edit/di-update\n";
echo "4. sisa_pinjaman calculated error\n";
echo "\nSOLUSI:\n";
echo "- Verifikasi total_pinjaman di database\n";
echo "- Verifikasi total_terbayar (sum dari semua pembayaran)\n";
echo "- Formula: sisa = total_pinjaman - total_terbayar\n";
echo "- Handle kasus overpayment (sisa < 0)\n";
?>
