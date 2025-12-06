<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPerawatan;

class MasterPerawatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checklists = [
            // ==================== HARIAN ====================
            // Kebersihan
            [
                'nama_kegiatan' => 'Buang Sampah Ruang Tamu',
                'deskripsi' => 'Buang sampah dari tempat sampah ruang tamu ke tempat pembuangan utama',
                'tipe_periode' => 'harian',
                'kategori' => 'kebersihan',
                'urutan' => 1
            ],
            [
                'nama_kegiatan' => 'Buang Sampah Kamar Mandi',
                'deskripsi' => 'Buang sampah dari tempat sampah semua kamar mandi',
                'tipe_periode' => 'harian',
                'kategori' => 'kebersihan',
                'urutan' => 2
            ],
            [
                'nama_kegiatan' => 'Sapu Lantai Ruang Utama',
                'deskripsi' => 'Sapu lantai lobby, koridor, dan ruang pertemuan',
                'tipe_periode' => 'harian',
                'kategori' => 'kebersihan',
                'urutan' => 3
            ],
            [
                'nama_kegiatan' => 'Pel Lantai Toilet',
                'deskripsi' => 'Pel lantai semua toilet dengan disinfektan',
                'tipe_periode' => 'harian',
                'kategori' => 'kebersihan',
                'urutan' => 4
            ],
            [
                'nama_kegiatan' => 'Bersihkan Meja Kerja',
                'deskripsi' => 'Lap dan bersihkan meja kerja dan meja rapat',
                'tipe_periode' => 'harian',
                'kategori' => 'kebersihan',
                'urutan' => 5
            ],
            [
                'nama_kegiatan' => 'Buang Sampah Dapur',
                'deskripsi' => 'Buang sampah dapur dan bersihkan area sekitar',
                'tipe_periode' => 'harian',
                'kategori' => 'kebersihan',
                'urutan' => 6
            ],
            
            // Pengecekan Harian
            [
                'nama_kegiatan' => 'Cek Keran Air',
                'deskripsi' => 'Pastikan semua keran air tidak bocor dan berfungsi normal',
                'tipe_periode' => 'harian',
                'kategori' => 'pengecekan',
                'urutan' => 7
            ],
            [
                'nama_kegiatan' => 'Cek Lampu Gedung',
                'deskripsi' => 'Cek semua lampu gedung, pastikan tidak ada yang mati',
                'tipe_periode' => 'harian',
                'kategori' => 'pengecekan',
                'urutan' => 8
            ],
            [
                'nama_kegiatan' => 'Cek Pintu & Jendela',
                'deskripsi' => 'Pastikan pintu dan jendela terkunci dengan baik',
                'tipe_periode' => 'harian',
                'kategori' => 'pengecekan',
                'urutan' => 9
            ],
            [
                'nama_kegiatan' => 'Cek AC & Kipas',
                'deskripsi' => 'Pastikan AC dan kipas angin berfungsi dengan baik',
                'tipe_periode' => 'harian',
                'kategori' => 'pengecekan',
                'urutan' => 10
            ],

            // ==================== MINGGUAN ====================
            [
                'nama_kegiatan' => 'Pel Lantai Seluruh Gedung',
                'deskripsi' => 'Pel lantai semua ruangan termasuk koridor dengan pembersih lantai',
                'tipe_periode' => 'mingguan',
                'kategori' => 'kebersihan',
                'urutan' => 1
            ],
            [
                'nama_kegiatan' => 'Bersihkan Jendela & Kaca',
                'deskripsi' => 'Bersihkan jendela dan kaca dengan pembersih kaca',
                'tipe_periode' => 'mingguan',
                'kategori' => 'kebersihan',
                'urutan' => 2
            ],
            [
                'nama_kegiatan' => 'Cuci Karpet',
                'deskripsi' => 'Vakum dan cuci karpet di ruang rapat dan musholla',
                'tipe_periode' => 'mingguan',
                'kategori' => 'kebersihan',
                'urutan' => 3
            ],
            [
                'nama_kegiatan' => 'Bersihkan Area Parkir',
                'deskripsi' => 'Sapu dan buang sampah di area parkir',
                'tipe_periode' => 'mingguan',
                'kategori' => 'kebersihan',
                'urutan' => 4
            ],
            [
                'nama_kegiatan' => 'Cek Kebocoran Pipa',
                'deskripsi' => 'Cek seluruh pipa air untuk kemungkinan kebocoran',
                'tipe_periode' => 'mingguan',
                'kategori' => 'pengecekan',
                'urutan' => 5
            ],
            [
                'nama_kegiatan' => 'Cek Sistem Listrik',
                'deskripsi' => 'Cek panel listrik, stop kontak, dan saklar',
                'tipe_periode' => 'mingguan',
                'kategori' => 'pengecekan',
                'urutan' => 6
            ],
            [
                'nama_kegiatan' => 'Test Alarm Kebakaran',
                'deskripsi' => 'Test fungsi alarm kebakaran dan smoke detector',
                'tipe_periode' => 'mingguan',
                'kategori' => 'pengecekan',
                'urutan' => 7
            ],

            // ==================== BULANAN ====================
            [
                'nama_kegiatan' => 'Service AC Semua Ruangan',
                'deskripsi' => 'Bersihkan filter AC dan cek freon semua unit AC',
                'tipe_periode' => 'bulanan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 1
            ],
            [
                'nama_kegiatan' => 'Bersihkan Plafon',
                'deskripsi' => 'Bersihkan plafon dari debu dan sarang laba-laba',
                'tipe_periode' => 'bulanan',
                'kategori' => 'kebersihan',
                'urutan' => 2
            ],
            [
                'nama_kegiatan' => 'Cuci Gorden & Vitrase',
                'deskripsi' => 'Lepas dan cuci semua gorden dan vitrase',
                'tipe_periode' => 'bulanan',
                'kategori' => 'kebersihan',
                'urutan' => 3
            ],
            [
                'nama_kegiatan' => 'Cek Atap Gedung',
                'deskripsi' => 'Cek atap untuk kebocoran dan genteng rusak',
                'tipe_periode' => 'bulanan',
                'kategori' => 'pengecekan',
                'urutan' => 4
            ],
            [
                'nama_kegiatan' => 'Bersihkan Saluran Air',
                'deskripsi' => 'Bersihkan selokan dan saluran air dari sampah',
                'tipe_periode' => 'bulanan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 5
            ],
            [
                'nama_kegiatan' => 'Cek Pompa Air',
                'deskripsi' => 'Cek fungsi pompa air dan tangki air',
                'tipe_periode' => 'bulanan',
                'kategori' => 'pengecekan',
                'urutan' => 6
            ],
            [
                'nama_kegiatan' => 'Cat Ulang Tembok (jika perlu)',
                'deskripsi' => 'Cek dan cat ulang bagian tembok yang terkelupas',
                'tipe_periode' => 'bulanan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 7
            ],

            // ==================== TAHUNAN ====================
            [
                'nama_kegiatan' => 'General Cleaning Gedung',
                'deskripsi' => 'Pembersihan menyeluruh semua area gedung',
                'tipe_periode' => 'tahunan',
                'kategori' => 'kebersihan',
                'urutan' => 1
            ],
            [
                'nama_kegiatan' => 'Cat Ulang Gedung',
                'deskripsi' => 'Pengecatan ulang seluruh gedung interior dan eksterior',
                'tipe_periode' => 'tahunan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 2
            ],
            [
                'nama_kegiatan' => 'Service Genset',
                'deskripsi' => 'Service menyeluruh genset oleh teknisi',
                'tipe_periode' => 'tahunan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 3
            ],
            [
                'nama_kegiatan' => 'Cek Struktur Bangunan',
                'deskripsi' => 'Pemeriksaan struktur bangunan oleh ahli',
                'tipe_periode' => 'tahunan',
                'kategori' => 'pengecekan',
                'urutan' => 4
            ],
            [
                'nama_kegiatan' => 'Service CCTV',
                'deskripsi' => 'Pemeliharaan dan update sistem CCTV',
                'tipe_periode' => 'tahunan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 5
            ],
            [
                'nama_kegiatan' => 'Pest Control',
                'deskripsi' => 'Pembasmian hama dan rayap di seluruh gedung',
                'tipe_periode' => 'tahunan',
                'kategori' => 'perawatan_rutin',
                'urutan' => 6
            ],
            [
                'nama_kegiatan' => 'Audit Keamanan Gedung',
                'deskripsi' => 'Audit menyeluruh sistem keamanan gedung',
                'tipe_periode' => 'tahunan',
                'kategori' => 'pengecekan',
                'urutan' => 7
            ],
        ];

        foreach ($checklists as $checklist) {
            MasterPerawatan::create($checklist);
        }
    }
}
