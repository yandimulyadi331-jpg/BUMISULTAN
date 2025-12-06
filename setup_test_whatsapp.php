<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SETUP WHATSAPP UNTUK TES KIRIM PESAN ===\n\n";

$apiKey = 'cwZaUTAdLmxw32hjy4bb';
$deviceId = 1;

// 1. Sync Groups dari WhatsApp
echo "1. Sync Groups dari WhatsApp Anda...\n";
try {
    $fonteService = app(\App\Services\FonteService::class);
    $groupsResult = $fonteService->getGroups($apiKey);
    
    if ($groupsResult['success']) {
        $groups = $groupsResult['data'];
        echo "   Ditemukan " . count($groups) . " grup\n\n";
        
        $syncedCount = 0;
        foreach ($groups as $group) {
            DB::table('wa_groups')->updateOrInsert(
                ['group_jid' => $group['id']],
                [
                    'group_name' => $group['name'],
                    'description' => $group['description'] ?? '',
                    'total_members' => $group['total_members'] ?? 0,
                    'device_id' => $deviceId,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
            $syncedCount++;
            echo "   ✓ {$group['name']} ({$group['total_members']} members)\n";
        }
        
        echo "\n   ✅ {$syncedCount} grup berhasil di-sync!\n\n";
    } else {
        echo "   ⚠️ Tidak ada grup atau gagal fetch: " . ($groupsResult['message'] ?? 'Unknown error') . "\n\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error sync groups: " . $e->getMessage() . "\n\n";
}

// 2. Sync Contacts Karyawan dari database
echo "2. Sync Contacts Karyawan...\n";
try {
    $karyawan = DB::table('karyawan')
        ->whereNotNull('no_hp')
        ->where('no_hp', '!=', '')
        ->where('status_aktif_karyawan', '1')
        ->select('nik', 'nama_karyawan', 'no_hp', 'kode_dept', 'kode_jabatan')
        ->get();
    
    echo "   Ditemukan " . $karyawan->count() . " karyawan dengan WhatsApp\n\n";
    
    $syncedCount = 0;
    foreach ($karyawan->take(20) as $k) { // Ambil 20 pertama
        // Format nomor WhatsApp
        $phoneNumber = preg_replace('/[^0-9]/', '', $k->no_hp);
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }
        
        DB::table('wa_contacts')->updateOrInsert(
            ['phone_number' => $phoneNumber],
            [
                'name' => $k->nama_karyawan,
                'type' => 'karyawan',
                'karyawan_nik' => $k->nik,
                'is_blacklisted' => false,
                'updated_at' => now(),
                'created_at' => now()
            ]
        );
        $syncedCount++;
        echo "   ✓ {$k->nama_karyawan} - {$phoneNumber}\n";
    }
    
    echo "\n   ✅ {$syncedCount} contacts berhasil di-sync!\n\n";
} catch (\Exception $e) {
    echo "   ❌ Error sync contacts: " . $e->getMessage() . "\n\n";
}

// 3. Tampilkan Summary
echo "3. Summary:\n";
$totalGroups = DB::table('wa_groups')->where('device_id', $deviceId)->count();
$totalContacts = DB::table('wa_contacts')->where('type', 'karyawan')->count();

echo "   📱 Device: WhatsApp HRD (085715375490)\n";
echo "   👥 Groups: {$totalGroups} grup tersedia\n";
echo "   📞 Contacts: {$totalContacts} karyawan tersedia\n\n";

// 4. Test kirim pesan
echo "=== TES KIRIM PESAN ===\n\n";
echo "Pilih opsi:\n";
echo "1. Kirim ke 1 Karyawan (Test)\n";
echo "2. Kirim ke Grup (Test)\n";
echo "3. Skip Test\n\n";

$choice = readline("Pilih (1/2/3): ");

if ($choice == '1') {
    // Test kirim ke 1 karyawan
    $contact = DB::table('wa_contacts')->where('type', 'karyawan')->first();
    if ($contact) {
        echo "\nMengirim pesan test ke: {$contact->name} ({$contact->phone_number})\n";
        
        $message = "Halo {$contact->name},\n\nIni adalah pesan test dari sistem WhatsApp Bumi Sultan Super App.\n\nTerima kasih! 🙏";
        
        $result = $fonteService->sendMessage($apiKey, $contact->phone_number, $message);
        
        if (isset($result['status']) && $result['status']) {
            echo "✅ Pesan berhasil dikirim!\n";
            
            // Save ke database
            DB::table('wa_messages')->insert([
                'device_id' => $deviceId,
                'recipient_number' => $contact->phone_number,
                'recipient_name' => $contact->name,
                'message_text' => $message,
                'status' => 'sent',
                'sent_at' => now(),
                'created_at' => now()
            ]);
        } else {
            echo "❌ Gagal kirim: " . ($result['reason'] ?? 'Unknown error') . "\n";
        }
    } else {
        echo "⚠️ Tidak ada contact tersedia\n";
    }
    
} elseif ($choice == '2') {
    // Test kirim ke grup
    $group = DB::table('wa_groups')->where('device_id', $deviceId)->first();
    if ($group) {
        echo "\nMengirim pesan test ke grup: {$group->group_name}\n";
        
        $message = "🔔 *Pengumuman Test*\n\nIni adalah pesan test dari sistem WhatsApp Bumi Sultan Super App.\n\nSistem berhasil terhubung! ✅\n\nTerima kasih! 🙏";
        
        $result = $fonteService->sendToGroup($apiKey, $group->group_jid, $message);
        
        if (isset($result['status']) && $result['status']) {
            echo "✅ Pesan berhasil dikirim ke grup!\n";
            
            // Save ke database
            DB::table('wa_messages')->insert([
                'device_id' => $deviceId,
                'recipient_number' => $group->group_jid,
                'recipient_name' => $group->group_name,
                'message_text' => $message,
                'status' => 'sent',
                'sent_at' => now(),
                'created_at' => now()
            ]);
        } else {
            echo "❌ Gagal kirim: " . ($result['reason'] ?? 'Unknown error') . "\n";
        }
    } else {
        echo "⚠️ Tidak ada grup tersedia\n";
    }
} else {
    echo "Skip test kirim pesan.\n";
}

echo "\n✅ SETUP SELESAI!\n\n";
echo "📝 Langkah selanjutnya:\n";
echo "1. Buka menu WhatsApp → Broadcast Baru\n";
echo "2. Isi form broadcast:\n";
echo "   - Judul: Contoh 'Info Gaji Bulan Ini'\n";
echo "   - Pesan: Ketik pesan yang ingin dikirim\n";
echo "   - Target: Pilih (All Karyawan / Departemen / Jabatan / Grup)\n";
echo "3. Klik 'Kirim Broadcast'\n";
echo "4. Pesan akan dikirim dengan delay 5 detik antar pesan\n\n";
echo "🎉 Selamat! Sistem WhatsApp Anda sudah siap digunakan!\n";
