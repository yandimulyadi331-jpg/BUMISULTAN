<?php
/**
 * Debug Dashboard Issues
 * Mengecek semua komponen dashboard untuk menemukan masalah
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\TugasLuar;
use App\Models\RealTimeNotification;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 DEBUGGING DASHBOARD ISSUES\n";
echo "==============================\n\n";

try {
    echo "1️⃣ Checking Tugas Luar Data:\n";
    echo "----------------------------\n";
    
    $tugasLuarCount = TugasLuar::whereDate('tanggal', date('Y-m-d'))
        ->where('status', 'keluar')
        ->count();
    
    echo "✅ Tugas Luar hari ini: $tugasLuarCount\n";
    
    if ($tugasLuarCount > 0) {
        $tugasLuarSample = TugasLuar::whereDate('tanggal', date('Y-m-d'))
            ->where('status', 'keluar')
            ->first();
        echo "📋 Sample data: {$tugasLuarSample->kode_tugas} - {$tugasLuarSample->tujuan}\n";
    }
    
    echo "\n2️⃣ Checking Notifications Data:\n";
    echo "-------------------------------\n";
    
    $notificationsCount = RealTimeNotification::today()->count();
    echo "✅ Notifications hari ini: $notificationsCount\n";
    
    if ($notificationsCount > 0) {
        $unreadCount = RealTimeNotification::today()->where('is_read', false)->count();
        echo "📬 Unread notifications: $unreadCount\n";
    }
    
    echo "\n3️⃣ Checking JavaScript Console Errors:\n";
    echo "--------------------------------------\n";
    echo "Untuk mengecek JavaScript errors:\n";
    echo "1. Buka dashboard di browser\n";
    echo "2. Tekan F12 untuk buka Developer Tools\n";
    echo "3. Lihat tab Console untuk error messages\n";
    echo "4. Klik card 'Karyawan Tugas Luar' dan lihat console\n\n";
    
    echo "4️⃣ Common Issues to Check:\n";
    echo "--------------------------\n";
    echo "❓ Bootstrap JavaScript loaded? (Check console for bootstrap errors)\n";
    echo "❓ jQuery conflicts? (Check if $ is defined)\n";
    echo "❓ Modal element exists? (Check if modalTugasLuar in DOM)\n";
    echo "❓ Click event bound? (Check onclick in HTML)\n";
    echo "❓ Data passed correctly? (Check dataTugasLuar variable)\n\n";
    
    echo "5️⃣ Quick Debug Steps:\n";
    echo "---------------------\n";
    echo "1. Check browser console for errors\n";
    echo "2. Verify data: console.log(dataTugasLuar) in browser\n";
    echo "3. Check modal HTML: document.getElementById('modalTugasLuar')\n";
    echo "4. Test function directly: showTugasLuarModal() in console\n\n";
    
    echo "🛠️ TROUBLESHOOTING COMPLETED\n";
    echo "Dashboard seharusnya berfungsi normal dengan:\n";
    echo "• $tugasLuarCount tugas luar hari ini\n";
    echo "• $notificationsCount notifikasi real-time\n";
    echo "• Modal popup untuk detail\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎯 NEXT STEPS:\n";
echo "1. Refresh dashboard page\n";
echo "2. Open browser console (F12)\n";
echo "3. Click 'Karyawan Tugas Luar' card\n";
echo "4. Check for any JavaScript errors\n";