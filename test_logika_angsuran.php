<?php
// Test logika angsuran baru user

$testCases = [
    [
        'nama' => 'Case 1: Pinjaman 5M, cicilan 2M/bulan',
        'totalPinjaman' => 5000000,
        'cicilanPerBulan' => 2000000,
    ],
    [
        'nama' => 'Case 2: Pinjaman 3.5M, cicilan 1M/bulan',
        'totalPinjaman' => 3500000,
        'cicilanPerBulan' => 1000000,
    ],
    [
        'nama' => 'Case 3: Pinjaman 10M, cicilan 3M/bulan',
        'totalPinjaman' => 10000000,
        'cicilanPerBulan' => 3000000,
    ],
];

foreach ($testCases as $case) {
    echo "\n=== {$case['nama']} ===\n";
    echo str_repeat("=", 50) . "\n";
    
    $totalPinjaman = $case['totalPinjaman'];
    $cicilanPerBulan = $case['cicilanPerBulan'];
    
    // Hitung tenor otomatis
    $tenor = ceil($totalPinjaman / $cicilanPerBulan);
    
    // Hitung cicilan terakhir
    $cicilanTerakhir = $totalPinjaman - ($cicilanPerBulan * ($tenor - 1));
    
    echo "Input User:\n";
    echo "  Pinjaman: Rp " . number_format($totalPinjaman, 0, ',', '.') . "\n";
    echo "  Cicilan per bulan: Rp " . number_format($cicilanPerBulan, 0, ',', '.') . "\n";
    echo "\nHasil Perhitungan Sistem:\n";
    echo "  Tenor (otomatis): $tenor bulan\n";
    echo "\nJadwal Cicilan:\n";
    
    $totalCicilan = 0;
    for ($i = 1; $i <= $tenor; $i++) {
        if ($i < $tenor) {
            $nominal = $cicilanPerBulan;
        } else {
            $nominal = $cicilanTerakhir;
        }
        
        $totalCicilan += $nominal;
        $tipeStr = ($i < $tenor) ? "" : "(TERAKHIR - SISA)";
        echo "  Bulan $i: Rp " . number_format($nominal, 0, ',', '.') . " $tipeStr\n";
    }
    
    echo "\nVerifikasi Akurasi:\n";
    echo "  Total Cicilan: Rp " . number_format($totalCicilan, 0, ',', '.') . "\n";
    echo "  Total Pinjaman: Rp " . number_format($totalPinjaman, 0, ',', '.') . "\n";
    echo "  Status: " . ($totalCicilan == $totalPinjaman ? "✅ AKURAT" : "❌ TIDAK AKURAT") . "\n";
}
?>
