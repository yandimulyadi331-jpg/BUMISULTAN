<!DOCTYPE html>
<html>
<head>
    <title>Test Image Loading</title>
</head>
<body>
    <h1>Test Akses Foto Wajah</h1>
    
    <?php
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    use App\Models\Facerecognition;
    
    $data = Facerecognition::where('nik', '251100001')->get();
    
    foreach ($data as $d) {
        $url = asset('storage/facerecognition/' . $d->wajah);
        $publicPath = public_path('storage/facerecognition/' . $d->wajah);
        
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
        echo "<h3>NIK: {$d->nik} - {$d->wajah}</h3>";
        echo "<p><strong>URL dari asset():</strong> $url</p>";
        echo "<p><strong>File path:</strong> $publicPath</p>";
        echo "<p><strong>File exists:</strong> " . (file_exists($publicPath) ? '✅ YES' : '❌ NO') . "</p>";
        
        if (file_exists($publicPath)) {
            echo "<p><strong>File size:</strong> " . filesize($publicPath) . " bytes</p>";
            echo "<img src='$url' style='max-width:200px; border:2px solid green;' onerror='this.style.border=\"2px solid red\"; this.nextSibling.innerHTML=\"❌ IMAGE FAILED TO LOAD!\"'>";
            echo "<p style='color:green; font-weight:bold;'></p>";
        }
        echo "</div>";
    }
    ?>
</body>
</html>
