<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChecklistPeriodeConfig;
use Illuminate\Support\Facades\DB;

class ChecklistPeriodeConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table untuk clean insert
        DB::table('checklist_periode_config')->truncate();

        // Get superadmin user ID (biasanya ID 1)
        $adminId = DB::table('users')->where('email', 'admin@bumisultan.com')->value('id') ?? 1;

        $configs = [
            [
                'tipe_periode' => 'harian',
                'is_enabled' => true,
                'is_mandatory' => true,
                'keterangan' => 'Checklist harian wajib dilengkapi sebelum absen pulang. Pastikan semua item perawatan gedung harian telah dicek.',
                'dibuat_oleh' => $adminId,
                'diubah_oleh' => $adminId,
            ],
            [
                'tipe_periode' => 'mingguan',
                'is_enabled' => true,
                'is_mandatory' => false,
                'keterangan' => 'Checklist mingguan opsional. Silakan lengkapi jika ada perawatan gedung mingguan yang perlu dicek.',
                'dibuat_oleh' => $adminId,
                'diubah_oleh' => $adminId,
            ],
            [
                'tipe_periode' => 'bulanan',
                'is_enabled' => true,
                'is_mandatory' => false,
                'keterangan' => 'Checklist bulanan opsional. Lakukan pengecekan gedung bulanan sesuai jadwal yang ditentukan.',
                'dibuat_oleh' => $adminId,
                'diubah_oleh' => $adminId,
            ],
            [
                'tipe_periode' => 'tahunan',
                'is_enabled' => false,
                'is_mandatory' => false,
                'keterangan' => 'Checklist tahunan saat ini nonaktif. Akan diaktifkan saat periode evaluasi tahunan.',
                'dibuat_oleh' => $adminId,
                'diubah_oleh' => $adminId,
            ],
        ];

        foreach ($configs as $config) {
            ChecklistPeriodeConfig::create($config);
        }

        $this->command->info('✅ Default checklist periode config berhasil dibuat!');
        $this->command->info('   - Harian: AKTIF & WAJIB');
        $this->command->info('   - Mingguan: AKTIF & OPSIONAL');
        $this->command->info('   - Bulanan: AKTIF & OPSIONAL');
        $this->command->info('   - Tahunan: NONAKTIF');
    }
}
