<?php
/**
 * Verify Dashboard JavaScript Syntax
 */

echo "🔍 VERIFYING DASHBOARD JAVASCRIPT\n";
echo "===================================\n\n";

$file = __DIR__ . '/resources/views/dashboard/dashboard.blade.php';
$content = file_get_contents($file);

echo "📊 File Analysis:\n";
echo "  Size: " . number_format(strlen($content)) . " bytes\n";
echo "  Lines: " . substr_count($content, "\n") . "\n\n";

// Extract JavaScript sections
preg_match('/<script>(.*?)<\/script>/s', $content, $matches);

if (empty($matches)) {
    echo "❌ No script tag found!\n";
    exit(1);
}

$jsContent = $matches[1];

echo "✅ JavaScript section found\n";
echo "  JS Length: " . strlen($jsContent) . " bytes\n\n";

// Check for critical functions
$functions = [
    'showKaryawanModal',
    'showTugasLuarModal',
    'refreshNotifications',
    'loadNotifications',
    'displayNotifications',
    'markAsRead',
    'markAllAsRead'
];

echo "🔧 Checking Critical Functions:\n";
$allFound = true;
foreach ($functions as $func) {
    $found = strpos($jsContent, "function $func") !== false;
    $status = $found ? '✅' : '❌';
    echo "  $status $func\n";
    if (!$found) $allFound = false;
}

echo "\n📋 Checking Common Errors:\n";
$errors = [];

// Check for undefined event reference
if (preg_match('/event\.target/', $jsContent) && !preg_match('/function \w+\(event\)/', $jsContent)) {
    echo "  ⚠️ Warning: 'event' reference found but might not be passed as parameter\n";
    $errors[] = "event reference";
} else {
    echo "  ✅ Event parameter handling looks good\n";
}

// Check for balanced braces
$openBraces = substr_count($jsContent, '{');
$closeBraces = substr_count($jsContent, '}');
if ($openBraces === $closeBraces) {
    echo "  ✅ Braces balanced ($openBraces pairs)\n";
} else {
    echo "  ❌ Braces NOT balanced! Open: $openBraces, Close: $closeBraces\n";
    $errors[] = "unbalanced braces";
}

// Check for balanced parentheses
$openParen = substr_count($jsContent, '(');
$closeParen = substr_count($jsContent, ')');
if ($openParen === $closeParen) {
    echo "  ✅ Parentheses balanced ($openParen pairs)\n";
} else {
    echo "  ❌ Parentheses NOT balanced! Open: $openParen, Close: $closeParen\n";
    $errors[] = "unbalanced parentheses";
}

echo "\n";

if ($allFound && empty($errors)) {
    echo "✅ ALL CHECKS PASSED!\n";
    echo "Dashboard JavaScript should work properly.\n\n";
    echo "🚀 Next Steps:\n";
    echo "  1. Open dashboard: http://127.0.0.1:8000/dashboard\n";
    echo "  2. Hard refresh: Ctrl + F5\n";
    echo "  3. Check console for errors (F12)\n";
    echo "  4. Test clicking all cards\n";
} else {
    echo "❌ ISSUES FOUND:\n";
    if (!$allFound) {
        echo "  - Some functions are missing\n";
    }
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}

echo "\n✅ Verification complete!\n";