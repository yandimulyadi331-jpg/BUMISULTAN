<?php
/**
 * Debug Script - Cek Path Foto Jamaah
 * Jalankan di browser: http://localhost:8001/debug_foto_jamaah.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "<h2>🔍 Debug Foto Jamaah</h2>";
echo "<style>
    body { font-family: Arial; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background: #4CAF50; color: white; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    img { max-width: 100px; max-height: 100px; border-radius: 50%; }
</style>";

// Cek Jamaah Masar
echo "<h3>📋 Jamaah MASAR</h3>";
$jamaahMasar = DB::table('jamaah_masar')
    ->whereNotNull('foto')
    ->where('foto', '!=', '')
    ->limit(10)
    ->get();

echo "<table>";
echo "<tr><th>ID</th><th>Nama</th><th>Path Foto (DB)</th><th>File Exists?</th><th>Preview</th><th>URL</th></tr>";

foreach($jamaahMasar as $jamaah) {
    $fotoClean = ltrim($jamaah->foto, '/');
    $paths = [
        'storage/app/public/' . $fotoClean => storage_path('app/public/' . $fotoClean),
        'public/storage/' . $fotoClean => public_path('storage/' . $fotoClean),
        'storage/app/' . $fotoClean => storage_path('app/' . $fotoClean),
        'public/' . $fotoClean => public_path($fotoClean),
    ];
    
    $fileExists = false;
    $foundPath = '';
    $previewUrl = '';
    
    foreach($paths as $label => $path) {
        if(file_exists($path) && is_file($path)) {
            $fileExists = true;
            $foundPath = $label;
            $previewUrl = asset('storage/' . $fotoClean);
            break;
        }
    }
    
    $statusClass = $fileExists ? 'success' : 'error';
    $statusText = $fileExists ? '✅ Found at: ' . $foundPath : '❌ NOT FOUND';
    
    echo "<tr>";
    echo "<td>{$jamaah->id}</td>";
    echo "<td>{$jamaah->nama_jamaah}</td>";
    echo "<td><code>{$jamaah->foto}</code></td>";
    echo "<td class='{$statusClass}'>{$statusText}</td>";
    echo "<td>";
    if($fileExists) {
        echo "<img src='{$previewUrl}' alt='Preview'>";
    } else {
        echo "N/A";
    }
    echo "</td>";
    echo "<td>";
    if($fileExists) {
        echo "<a href='{$previewUrl}' target='_blank'>View</a>";
    } else {
        echo "N/A";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

// Cek Jamaah Majlis Taklim
echo "<h3>📋 Jamaah MAJLIS TAKLIM</h3>";
$jamaahMajlis = DB::table('jamaah_majlis_taklim')
    ->whereNotNull('foto')
    ->where('foto', '!=', '')
    ->limit(10)
    ->get();

echo "<table>";
echo "<tr><th>ID</th><th>Nama</th><th>Path Foto (DB)</th><th>File Exists?</th><th>Preview</th><th>URL</th></tr>";

foreach($jamaahMajlis as $jamaah) {
    $fotoClean = ltrim($jamaah->foto, '/');
    $paths = [
        'storage/app/public/' . $fotoClean => storage_path('app/public/' . $fotoClean),
        'public/storage/' . $fotoClean => public_path('storage/' . $fotoClean),
        'storage/app/' . $fotoClean => storage_path('app/' . $fotoClean),
        'public/' . $fotoClean => public_path($fotoClean),
    ];
    
    $fileExists = false;
    $foundPath = '';
    $previewUrl = '';
    
    foreach($paths as $label => $path) {
        if(file_exists($path) && is_file($path)) {
            $fileExists = true;
            $foundPath = $label;
            $previewUrl = asset('storage/' . $fotoClean);
            break;
        }
    }
    
    $statusClass = $fileExists ? 'success' : 'error';
    $statusText = $fileExists ? '✅ Found at: ' . $foundPath : '❌ NOT FOUND';
    
    echo "<tr>";
    echo "<td>{$jamaah->id}</td>";
    echo "<td>{$jamaah->nama_jamaah}</td>";
    echo "<td><code>{$jamaah->foto}</code></td>";
    echo "<td class='{$statusClass}'>{$statusText}</td>";
    echo "<td>";
    if($fileExists) {
        echo "<img src='{$previewUrl}' alt='Preview'>";
    } else {
        echo "N/A";
    }
    echo "</td>";
    echo "<td>";
    if($fileExists) {
        echo "<a href='{$previewUrl}' target='_blank'>View</a>";
    } else {
        echo "N/A";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

// Cek Storage Link
echo "<h3>🔗 Storage Link Check</h3>";
$storageLink = public_path('storage');
if(is_link($storageLink)) {
    echo "<p class='success'>✅ Storage link EXISTS</p>";
    echo "<p>Target: " . readlink($storageLink) . "</p>";
} else {
    echo "<p class='error'>❌ Storage link NOT FOUND</p>";
    echo "<p class='warning'>⚠️ Jalankan: <code>php artisan storage:link</code></p>";
}

echo "<hr>";
echo "<h3>📝 Rekomendasi:</h3>";
echo "<ol>";
echo "<li>Pastikan sudah run: <code>php artisan storage:link</code></li>";
echo "<li>Cek file foto ada di: <code>storage/app/public/[path-foto]</code></li>";
echo "<li>Path foto di database harus format: <code>uploads/jamaah/filename.jpg</code></li>";
echo "<li>Atau path relatif lainnya tanpa leading slash</li>";
echo "</ol>";
