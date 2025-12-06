<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

use Illuminate\Http\Request;

echo "=== CEK SESSION FLASH MESSAGES ===\n\n";

// Simulate request
$request = Request::create('http://127.0.0.1:8000/kendaraan/test', 'GET');
$kernel->handle($request);

// Get session
$session = $app['session']->driver();

echo "Session driver: " . get_class($session) . "\n";
echo "Session ID: " . $session->getId() . "\n\n";

// Check all session data
echo "--- All Session Data ---\n";
$allData = $session->all();
foreach ($allData as $key => $value) {
    echo "$key => ";
    if (is_array($value)) {
        echo json_encode($value, JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo $value . "\n";
    }
}

// Check flash data
echo "\n--- Flash Data (errors/messages) ---\n";

$flashKeys = ['errors', 'error', 'message', 'success', 'warning', 'info', 'gagal'];
foreach ($flashKeys as $key) {
    if ($session->has($key)) {
        echo "$key: ";
        $val = $session->get($key);
        if (is_object($val)) {
            echo get_class($val) . " => " . json_encode($val, JSON_UNESCAPED_UNICODE) . "\n";
        } else {
            echo json_encode($val, JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
}

echo "\n✅ Check selesai\n";
