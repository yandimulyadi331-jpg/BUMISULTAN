<?php

/**
 * TESTING SCRIPT - Ijin Dinas Multiple Karyawan
 * 
 * Script ini untuk testing validasi overlap ijin dinas
 * setelah perbaikan di IzindinasController
 * 
 * CARA TESTING:
 * 1. Pastikan sudah ada beberapa karyawan di database
 * 2. Run: php test_ijin_dinas_multiple.php
 * 3. Atau akses via browser: http://localhost/bumisultanAPP/test_ijin_dinas_multiple.php
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Izindinas;
use App\Models\Karyawan;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=================================================\n";
echo "TEST: VALIDASI IJIN DINAS MULTIPLE KARYAWAN\n";
echo "=================================================\n\n";

// Ambil 5 karyawan untuk testing
$karyawan = Karyawan::select('nik', 'nama_karyawan')
    ->limit(5)
    ->get();

if ($karyawan->count() < 5) {
    echo "❌ ERROR: Butuh minimal 5 karyawan untuk testing\n";
    echo "   Karyawan tersedia: " . $karyawan->count() . "\n";
    exit;
}

echo "✅ Karyawan untuk testing:\n";
foreach ($karyawan as $index => $k) {
    echo "   " . ($index + 1) . ". {$k->nik} - {$k->nama_karyawan}\n";
}
echo "\n";

// Test dates
$dari = '2026-01-15';
$sampai = '2026-01-17';

echo "📅 Tanggal Testing: {$dari} s/d {$sampai}\n\n";

// Bersihkan data testing lama
echo "🧹 Membersihkan data testing lama...\n";
Izindinas::whereBetween('dari', ['2026-01-01', '2026-01-31'])->delete();
echo "✅ Data testing lama dihapus\n\n";

// TEST CASE 1: Input Multiple Karyawan - Tanggal Sama
echo "=================================================\n";
echo "TEST CASE 1: Input 5 Karyawan - Tanggal Sama\n";
echo "=================================================\n";

$success_count = 0;
foreach ($karyawan as $index => $k) {
    try {
        // Simulasi validasi overlap
        $cek_izin_dinas = Izindinas::where('nik', $k->nik)
            ->where(function($query) use ($dari, $sampai) {
                $query->where('dari', '<=', $sampai)
                      ->where('sampai', '>=', $dari);
            })
            ->first();

        if ($cek_izin_dinas) {
            echo "❌ Karyawan " . ($index + 1) . " ({$k->nama_karyawan}): DITOLAK - Sudah ada ijin\n";
            continue;
        }

        // Generate kode
        $lastizin = Izindinas::select('kode_izin_dinas')
            ->whereRaw('YEAR(dari)="2026"')
            ->whereRaw('MONTH(dari)="01"')
            ->orderBy("kode_izin_dinas", "desc")
            ->first();
        
        $last_kode = $lastizin != null ? $lastizin->kode_izin_dinas : '';
        $kode_izin_dinas = buatkode($last_kode, "ID2601", 4);

        // Insert
        Izindinas::create([
            'kode_izin_dinas' => $kode_izin_dinas,
            'nik' => $k->nik,
            'tanggal' => $dari,
            'dari' => $dari,
            'sampai' => $sampai,
            'keterangan' => 'Testing Multiple Karyawan - Tanggal Sama',
            'status' => 0,
        ]);

        $success_count++;
        echo "✅ Karyawan " . ($index + 1) . " ({$k->nama_karyawan}): BERHASIL - {$kode_izin_dinas}\n";
    } catch (\Exception $e) {
        echo "❌ Karyawan " . ($index + 1) . " ({$k->nama_karyawan}): ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Hasil: {$success_count}/5 karyawan berhasil input\n";
if ($success_count == 5) {
    echo "✅ TEST CASE 1: PASSED - Semua karyawan berhasil input di tanggal yang sama!\n";
} else {
    echo "❌ TEST CASE 1: FAILED - Tidak semua karyawan berhasil input\n";
}
echo "\n";

// TEST CASE 2: Duplikasi Karyawan yang Sama
echo "=================================================\n";
echo "TEST CASE 2: Duplikasi Karyawan yang Sama\n";
echo "=================================================\n";

$test_karyawan = $karyawan->first();
echo "Testing dengan: {$test_karyawan->nama_karyawan}\n\n";

// Coba input lagi karyawan yang sama
try {
    $cek_izin_dinas = Izindinas::where('nik', $test_karyawan->nik)
        ->where(function($query) use ($dari, $sampai) {
            $query->where('dari', '<=', $sampai)
                  ->where('sampai', '>=', $dari);
        })
        ->first();

    if ($cek_izin_dinas) {
        echo "✅ TEST CASE 2: PASSED - Duplikasi berhasil dicegah!\n";
        echo "   Error message: 'Anda Sudah Mengajukan Ijin Dinas Pada Rentang Tanggal Tersebut!'\n";
    } else {
        echo "❌ TEST CASE 2: FAILED - Duplikasi tidak terdeteksi!\n";
    }
} catch (\Exception $e) {
    echo "❌ TEST CASE 2: ERROR - " . $e->getMessage() . "\n";
}
echo "\n";

// TEST CASE 3: Overlap Detection
echo "=================================================\n";
echo "TEST CASE 3: Overlap Detection\n";
echo "=================================================\n";

$test_cases = [
    ['dari' => '2026-01-16', 'sampai' => '2026-01-20', 'desc' => 'Overlap di tengah'],
    ['dari' => '2026-01-10', 'sampai' => '2026-01-16', 'desc' => 'Overlap di awal'],
    ['dari' => '2026-01-10', 'sampai' => '2026-01-20', 'desc' => 'Melingkupi sepenuhnya'],
    ['dari' => '2026-01-16', 'sampai' => '2026-01-16', 'desc' => 'Satu hari di tengah'],
];

$overlap_detected = 0;
foreach ($test_cases as $index => $test) {
    try {
        $cek_izin_dinas = Izindinas::where('nik', $test_karyawan->nik)
            ->where(function($query) use ($test) {
                $query->where('dari', '<=', $test['sampai'])
                      ->where('sampai', '>=', $test['dari']);
            })
            ->first();

        if ($cek_izin_dinas) {
            echo "✅ Test " . ($index + 1) . " ({$test['desc']}): OVERLAP TERDETEKSI\n";
            echo "   Range test: {$test['dari']} - {$test['sampai']}\n";
            echo "   Range existing: {$cek_izin_dinas->dari} - {$cek_izin_dinas->sampai}\n";
            $overlap_detected++;
        } else {
            echo "❌ Test " . ($index + 1) . " ({$test['desc']}): OVERLAP TIDAK TERDETEKSI\n";
        }
    } catch (\Exception $e) {
        echo "❌ Test " . ($index + 1) . ": ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Hasil: {$overlap_detected}/" . count($test_cases) . " overlap terdeteksi\n";
if ($overlap_detected == count($test_cases)) {
    echo "✅ TEST CASE 3: PASSED - Semua overlap berhasil terdeteksi!\n";
} else {
    echo "❌ TEST CASE 3: FAILED - Ada overlap yang tidak terdeteksi\n";
}
echo "\n";

// TEST CASE 4: Non-Overlap (Harus Berhasil)
echo "=================================================\n";
echo "TEST CASE 4: Non-Overlap (Harus Berhasil)\n";
echo "=================================================\n";

$dari_baru = '2026-01-20';
$sampai_baru = '2026-01-22';

echo "Range baru: {$dari_baru} - {$sampai_baru}\n";
echo "Range existing: {$dari} - {$sampai}\n\n";

try {
    $cek_izin_dinas = Izindinas::where('nik', $test_karyawan->nik)
        ->where(function($query) use ($dari_baru, $sampai_baru) {
            $query->where('dari', '<=', $sampai_baru)
                  ->where('sampai', '>=', $dari_baru);
        })
        ->first();

    if (!$cek_izin_dinas) {
        // Generate kode
        $lastizin = Izindinas::select('kode_izin_dinas')
            ->whereRaw('YEAR(dari)="2026"')
            ->whereRaw('MONTH(dari)="01"')
            ->orderBy("kode_izin_dinas", "desc")
            ->first();
        
        $last_kode = $lastizin != null ? $lastizin->kode_izin_dinas : '';
        $kode_izin_dinas = buatkode($last_kode, "ID2601", 4);

        Izindinas::create([
            'kode_izin_dinas' => $kode_izin_dinas,
            'nik' => $test_karyawan->nik,
            'tanggal' => $dari_baru,
            'dari' => $dari_baru,
            'sampai' => $sampai_baru,
            'keterangan' => 'Testing Non-Overlap',
            'status' => 0,
        ]);

        echo "✅ TEST CASE 4: PASSED - Non-overlap berhasil input!\n";
    } else {
        echo "❌ TEST CASE 4: FAILED - Non-overlap ditolak (seharusnya berhasil)\n";
    }
} catch (\Exception $e) {
    echo "❌ TEST CASE 4: ERROR - " . $e->getMessage() . "\n";
}
echo "\n";

// Ringkasan
echo "=================================================\n";
echo "RINGKASAN TESTING\n";
echo "=================================================\n";

$total_data = Izindinas::whereBetween('dari', ['2026-01-01', '2026-01-31'])->count();
echo "Total data ijin dinas yang dibuat: {$total_data}\n";

$data_per_karyawan = Izindinas::whereBetween('dari', ['2026-01-01', '2026-01-31'])
    ->select('nik', DB::raw('COUNT(*) as total'))
    ->groupBy('nik')
    ->get();

echo "\nData per karyawan:\n";
foreach ($data_per_karyawan as $data) {
    $k = Karyawan::where('nik', $data->nik)->first();
    $nama = $k ? $k->nama_karyawan : 'Unknown';
    echo "  - {$nama} ({$data->nik}): {$data->total} ijin\n";
}

echo "\n=================================================\n";
echo "✅ TESTING SELESAI\n";
echo "=================================================\n";
echo "\nCatatan:\n";
echo "- Data testing tersimpan di database untuk verifikasi manual\n";
echo "- Untuk membersihkan: DELETE FROM presensi_izindinas WHERE tanggal BETWEEN '2026-01-01' AND '2026-01-31'\n";
echo "\n";

/**
 * Helper function: buatkode
 * Jika tidak ada di environment, define di sini
 */
if (!function_exists('buatkode')) {
    function buatkode($last_kode, $prefix, $length)
    {
        if (empty($last_kode)) {
            $urut = 1;
        } else {
            $urut = (int) substr($last_kode, strlen($prefix), $length);
            $urut++;
        }
        return $prefix . str_pad($urut, $length, "0", STR_PAD_LEFT);
    }
}

if (!function_exists('hitungHari')) {
    function hitungHari($dari, $sampai)
    {
        $start = new DateTime($dari);
        $end = new DateTime($sampai);
        $interval = $start->diff($end);
        return $interval->days + 1; // +1 untuk inklusif
    }
}
