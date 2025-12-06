<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TAMBAH SAMPLE CONTACTS UNTUK TESTING ===\n\n";

use App\Models\WaContact;

$samples = [
    ['name' => 'Admin Testing', 'phone' => '628123456789', 'type' => 'external'],
    ['name' => 'Manager IT', 'phone' => '628234567890', 'type' => 'external'],
    ['name' => 'Staff HRD', 'phone' => '628345678901', 'type' => 'external'],
];

foreach($samples as $sample) {
    WaContact::updateOrCreate(
        ['phone_number' => $sample['phone']],
        [
            'name' => $sample['name'],
            'type' => $sample['type']
        ]
    );
    echo "✅ {$sample['name']} - {$sample['phone']}\n";
}

echo "\n=================================\n";
echo "✅ SAMPLE DATA BERHASIL DITAMBAHKAN!\n";
echo "=================================\n";
echo "Total: 3 kontak sample\n\n";
echo "Silakan buka: http://127.0.0.1:8000/whatsapp/contacts\n";
