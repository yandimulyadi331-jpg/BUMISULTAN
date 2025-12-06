<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "==============================================\n";
echo "  CEK KONFIGURASI WA GATEWAY\n";
echo "==============================================\n\n";

$setting = DB::table('pengaturan_umum')->where('id', 1)->first();

echo "Provider WA       : " . ($setting->provider_wa ?? 'tidak ada') . "\n";
echo "Domain WA Gateway : " . ($setting->domain_wa_gateway ?? 'tidak ada') . "\n";
echo "WA API Key        : " . ($setting->wa_api_key ? substr($setting->wa_api_key, 0, 6) . '***' . substr($setting->wa_api_key, -4) : 'tidak ada') . "\n";

echo "\n";
echo "STATUS:\n";
if ($setting->provider_wa === 'fe') {
    echo "✅ Provider: FONNTE (Fonnte.com)\n";
    echo "✅ Tidak perlu server lokal\n";
    echo "✅ Tidak perlu menu 'WhatsApp Gateway' di aplikasi\n";
    echo "✅ Kirim pesan langsung via Fonnte API\n";
} elseif ($setting->provider_wa === 'ig') {
    echo "⚠️  Provider: INTERNAL GATEWAY (Server sendiri)\n";
    echo "⚠️  Perlu server WA Gateway running\n";
    echo "⚠️  Perlu device connected di menu 'WhatsApp Gateway'\n";
} else {
    echo "❌ Provider tidak valid\n";
}

echo "\n";
echo "==============================================\n";
echo "  DEVICE STATUS\n";
echo "==============================================\n\n";

$devices = DB::table('devices')->get();
if ($devices->count() > 0) {
    foreach ($devices as $device) {
        echo "Nomor : " . $device->number . "\n";
        echo "Status: " . ($device->status == 1 ? 'Aktif' : 'Tidak Aktif') . "\n";
        echo "Dibuat: " . $device->created_at . "\n";
        echo "---\n";
    }
} else {
    echo "Tidak ada device terdaftar\n";
}

echo "\n";
echo "==============================================\n\n";

if ($setting->provider_wa === 'fe') {
    echo "CATATAN:\n";
    echo "- Anda menggunakan Fonnte, device di tabel 'devices' TIDAK DIGUNAKAN\n";
    echo "- Device dikelola di dashboard Fonnte (https://fonnte.com)\n";
    echo "- Menu 'WhatsApp Gateway' di aplikasi TIDAK PERLU DIGUNAKAN\n";
    echo "- Langsung test kirim notifikasi dari fitur aplikasi\n";
} elseif ($setting->provider_wa === 'ig') {
    echo "CATATAN:\n";
    echo "- Anda menggunakan Internal Gateway\n";
    echo "- Pastikan server WA Gateway running di: " . ($setting->domain_wa_gateway ?? 'belum diset') . "\n";
    echo "- Pastikan minimal 1 device status Aktif dan Connected\n";
}

echo "\n";
