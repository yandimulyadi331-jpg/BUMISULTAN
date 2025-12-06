<?php
/**
 * Simulate exact user request to find error
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a test request
$request = Illuminate\Http\Request::create('/kendaraan/' . Illuminate\Support\Facades\Crypt::encryptString(6) . '?tab=peminjaman', 'GET');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== SIMULATING ACTUAL HTTP REQUEST ===\n\n";
echo "URL: /kendaraan/{encrypted-id}?tab=peminjaman\n";
echo "Method: GET\n\n";

try {
    $response = $kernel->handle($request);
    
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Type: " . get_class($response) . "\n";
    
    if ($response->getStatusCode() == 200) {
        echo "\n✓ Request successful!\n";
        echo "Content length: " . strlen($response->getContent()) . " bytes\n";
    } else {
        echo "\n✗ Request failed!\n";
        echo "Response content:\n";
        echo $response->getContent();
    }
    
} catch (\Exception $e) {
    echo "\n✗ EXCEPTION CAUGHT!\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

$kernel->terminate($request, $response ?? null);

echo "\n\n=== END SIMULATION ===\n";
