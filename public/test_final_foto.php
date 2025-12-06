<!DOCTYPE html>
<html>
<head>
    <title>Test Final - Foto Wajah</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-card { border: 2px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        img { max-width: 200px; border: 3px solid green; border-radius: 5px; }
        img.error { border-color: red; }
        .info { color: #666; font-size: 0.9em; margin: 5px 0; }
    </style>
</head>
<body>
    <h1>🔍 Test Final - Foto Wajah Absensi</h1>
    
    <?php
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    use App\Models\Facerecognition;
    use App\Models\Karyawan;
    
    $nik = '251100001';
    $karyawan = Karyawan::where('nik', $nik)->first();
    
    if (!$karyawan) {
        echo "<p style='color:red;'>❌ Karyawan tidak ditemukan!</p>";
        exit;
    }
    
    $nama_depan = strtolower(explode(' ', trim($karyawan->nama_karyawan))[0]);
    $folder = $nik . '-' . $nama_depan;
    
    echo "<div class='test-card'>";
    echo "<h2>📋 Info Karyawan</h2>";
    echo "<p><strong>NIK:</strong> $nik</p>";
    echo "<p><strong>Nama:</strong> {$karyawan->nama_karyawan}</p>";
    echo "<p><strong>Folder:</strong> $folder</p>";
    echo "</div>";
    
    $foto_list = Facerecognition::where('nik', $nik)->get();
    
    echo "<h2>📷 Foto Wajah ({$foto_list->count()} foto)</h2>";
    
    foreach ($foto_list as $d) {
        $url = url('/storage/uploads/facerecognition/' . $folder . '/' . $d->wajah);
        $publicPath = public_path('storage/uploads/facerecognition/' . $folder . '/' . $d->wajah);
        $exists = file_exists($publicPath);
        
        $cardClass = $exists ? 'success' : 'error';
        $statusIcon = $exists ? '✅' : '❌';
        
        echo "<div class='test-card $cardClass'>";
        echo "<h3>$statusIcon {$d->wajah}</h3>";
        echo "<div class='info'><strong>URL:</strong> $url</div>";
        echo "<div class='info'><strong>Path:</strong> $publicPath</div>";
        echo "<div class='info'><strong>File Exists:</strong> " . ($exists ? 'YES' : 'NO') . "</div>";
        
        if ($exists) {
            echo "<div class='info'><strong>Size:</strong> " . filesize($publicPath) . " bytes</div>";
            echo "<div style='margin-top:10px;'>";
            echo "<img src='$url' alt='{$d->wajah}' onerror=\"this.className='error'; this.alt='GAGAL LOAD!'\">";
            echo "</div>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>⚠️ FILE TIDAK DITEMUKAN!</p>";
        }
        
        echo "</div>";
    }
    ?>
    
    <div class="test-card">
        <h2>🎯 Kesimpulan</h2>
        <p>Jika semua foto tampil dengan border hijau, berarti SUKSES! ✅</p>
        <p>Sekarang coba refresh halaman detail karyawan di admin panel.</p>
    </div>
</body>
</html>
