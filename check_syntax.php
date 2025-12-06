<?php
/**
 * Quick Syntax Check
 */

echo "🔍 CHECKING DASHBOARD SYNTAX\n";
echo "============================\n\n";

$dashboardFile = __DIR__ . '/resources/views/dashboard/dashboard.blade.php';

if (!file_exists($dashboardFile)) {
    echo "❌ File not found: $dashboardFile\n";
    exit(1);
}

$content = file_get_contents($dashboardFile);

echo "✅ File found and readable\n";
echo "📊 File size: " . strlen($content) . " bytes\n";
echo "📋 Line count: " . substr_count($content, "\n") . " lines\n\n";

// Check for common JavaScript syntax errors
$errors = [];

// Check for unclosed functions
$functionCount = preg_match_all('/function\s+\w+\s*\(/', $content);
echo "🔧 Functions found: $functionCount\n";

// Check for try-catch blocks
$tryCount = substr_count($content, 'try {');
$catchCount = substr_count($content, 'catch');
echo "⚠️ Try blocks: $tryCount\n";
echo "⚠️ Catch blocks: $catchCount\n";

if ($tryCount !== $catchCount) {
    echo "❌ WARNING: Unmatched try-catch blocks!\n";
    $errors[] = "Unmatched try-catch blocks";
}

// Check for common issues
if (strpos($content, 'karyawanHTML += `</small><br><span class="badge bg-label-info">${karyawanList.length} orang</span>`;                content') !== false) {
    echo "❌ ERROR: Found concatenated line without proper spacing!\n";
    $errors[] = "Concatenated code without proper line break";
}

// Check balanced braces in script tags
preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $content, $scripts);
foreach ($scripts[1] as $index => $script) {
    $openBraces = substr_count($script, '{');
    $closeBraces = substr_count($script, '}');
    
    if ($openBraces !== $closeBraces) {
        echo "❌ WARNING: Unbalanced braces in script block " . ($index + 1) . "!\n";
        echo "   Open: $openBraces, Close: $closeBraces\n";
        $errors[] = "Unbalanced braces in script block " . ($index + 1);
    } else {
        echo "✅ Script block " . ($index + 1) . ": Braces balanced ($openBraces pairs)\n";
    }
}

echo "\n";

if (empty($errors)) {
    echo "✅ NO SYNTAX ERRORS DETECTED!\n";
    echo "Dashboard should be working properly now.\n";
} else {
    echo "❌ ERRORS FOUND:\n";
    foreach ($errors as $error) {
        echo "   • $error\n";
    }
}

echo "\n🎯 Ready to test dashboard!\n";