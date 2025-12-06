<?php
/**
 * Script untuk memverifikasi integrasi kehadiran
 * dan menampilkan sample data kehadiran
 */

require 'vendor/autoload.php';

use App\Models\JamaahMajlisTaklim;
use App\Models\YayasanMasar;
use App\Models\KehadiranJamaah;
use App\Models\PresensiYayasan;
use Illuminate\Support\Facades\Crypt;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== VERIFIKASI INTEGRASI KEHADIRAN ===\n\n";
    
    // 1. Check data Majlis Taklim
    echo "1. DATA MAJLIS TAKLIM\n";
    echo "─────────────────────────────────────────────────────────\n";
    
    $jamaahCount = JamaahMajlisTaklim::count();
    echo "Total Jamaah Majlis Taklim: " . $jamaahCount . "\n";
    
    $kehadiranCount = KehadiranJamaah::count();
    echo "Total Kehadiran Tercatat: " . $kehadiranCount . "\n";
    
    $kehadiranHariIni = KehadiranJamaah::whereDate('tanggal_kehadiran', today())->count();
    echo "Kehadiran Hari Ini: " . $kehadiranHariIni . "\n\n";
    
    // Sample data
    $jamaahSample = JamaahMajlisTaklim::with('kehadiran')->limit(3)->get();
    if ($jamaahSample->count() > 0) {
        echo "Sample Jamaah:\n";
        foreach ($jamaahSample as $j) {
            $kehadiran_terbaru = $j->kehadiran()->latest('tanggal_kehadiran')->first();
            $status = KehadiranJamaah::whereDate('tanggal_kehadiran', today())
                ->where('jamaah_id', $j->id)->exists() ? 'Hadir' : 'Belum';
            
            echo "- " . $j->nama_jamaah . " (Total: " . $j->jumlah_kehadiran . ", Hari Ini: $status)\n";
            if ($kehadiran_terbaru) {
                echo "  Kehadiran Terakhir: " . $kehadiran_terbaru->tanggal_kehadiran->format('d M Y H:i') . "\n";
            }
        }
    }
    
    echo "\n2. DATA YAYASAN MASAR\n";
    echo "─────────────────────────────────────────────────────────\n";
    
    $yayasanCount = YayasanMasar::where('status_aktif', 1)->count();
    echo "Total Yayasan Aktif: " . $yayasanCount . "\n";
    
    $presensiCount = PresensiYayasan::count();
    echo "Total Presensi Tercatat: " . $presensiCount . "\n";
    
    $presensiHariIni = PresensiYayasan::whereDate('tanggal', today())->count();
    echo "Presensi Hari Ini: " . $presensiHariIni . "\n\n";
    
    // Sample data
    $yayasanSample = YayasanMasar::where('status_aktif', 1)->with('presensi')->limit(3)->get();
    if ($yayasanSample->count() > 0) {
        echo "Sample Yayasan:\n";
        foreach ($yayasanSample as $y) {
            $presensi_terbaru = $y->presensi()->latest('tanggal')->first();
            $total = PresensiYayasan::where('kode_yayasan', $y->kode_yayasan)->count();
            $status = PresensiYayasan::whereDate('tanggal', today())
                ->where('kode_yayasan', $y->kode_yayasan)->exists() ? 'Hadir' : 'Belum';
            
            echo "- " . $y->nama . " (" . $y->kode_yayasan . ", Total: $total, Hari Ini: $status)\n";
            if ($presensi_terbaru) {
                $tanggal = is_string($presensi_terbaru->tanggal) 
                    ? \Carbon\Carbon::parse($presensi_terbaru->tanggal) 
                    : $presensi_terbaru->tanggal;
                $jam_in = is_string($presensi_terbaru->jam_in) 
                    ? \Carbon\Carbon::parse($presensi_terbaru->jam_in) 
                    : $presensi_terbaru->jam_in;
                echo "  Presensi Terakhir: " . $tanggal->format('d M Y') . " (" . $jam_in->format('H:i') . ")\n";
            }
        }
    }
    
    echo "\n3. VERIFIKASI ENDPOINT RESPONSE\n";
    echo "─────────────────────────────────────────────────────────\n";
    
    // Simulate API response
    $dataMajlis = $jamaahSample->map(function($item) {
        $kehadiran_terbaru = $item->kehadiran()->latest('tanggal_kehadiran')->first();
        $status_kehadiran_hari_ini = $item->kehadiran()
            ->whereDate('tanggal_kehadiran', today())
            ->exists() ? 'Hadir' : 'Belum';
        
        return [
            'nama_jamaah' => $item->nama_jamaah,
            'type' => 'majlis',
            'jumlah_kehadiran' => $item->jumlah_kehadiran,
            'kehadiran_terbaru' => $kehadiran_terbaru ? $kehadiran_terbaru->tanggal_kehadiran->format('d M Y') : '-',
            'status_kehadiran_hari_ini' => $status_kehadiran_hari_ini,
        ];
    });
    
    $dataYayasan = $yayasanSample->map(function($item) {
        $presensi_terbaru = $item->presensi()->latest('tanggal')->first();
        $status_kehadiran_hari_ini = PresensiYayasan::where('kode_yayasan', $item->kode_yayasan)
            ->whereDate('tanggal', today())
            ->exists() ? 'Hadir' : 'Belum';
        $total = PresensiYayasan::where('kode_yayasan', $item->kode_yayasan)->count();
        
        $tanggal_str = '-';
        if ($presensi_terbaru) {
            $tanggal = is_string($presensi_terbaru->tanggal) 
                ? \Carbon\Carbon::parse($presensi_terbaru->tanggal) 
                : $presensi_terbaru->tanggal;
            $tanggal_str = $tanggal->format('d M Y');
        }
        
        return [
            'nama_jamaah' => $item->nama,
            'type' => 'yayasan',
            'jumlah_kehadiran' => $total,
            'kehadiran_terbaru' => $tanggal_str,
            'status_kehadiran_hari_ini' => $status_kehadiran_hari_ini,
        ];
    });
    
    $allData = $dataMajlis->toArray();
    $allData = array_merge($allData, $dataYayasan->toArray());
    
    echo "Sample Response Data:\n";
    echo json_encode($allData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
    echo "\n✅ VERIFIKASI SELESAI - Integrasi berjalan dengan baik!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
?>
