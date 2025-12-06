<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KeuanganSantriCategory;

class KeuanganSantriCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // KATEGORI PENGELUARAN
            [
                'nama_kategori' => 'Kebersihan & Kesehatan',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'sabun', 'shampo', 'pasta gigi', 'sikat gigi', 'detergen', 
                    'tissue', 'handuk', 'obat', 'vitamin', 'paracetamol', 
                    'batuk', 'flu', 'demam', 'sakit', 'antiseptik', 'plaster',
                    'minyak kayu putih', 'balsem', 'masker', 'hand sanitizer'
                ],
                'icon' => 'fa-solid fa-hands-bubbles',
                'color' => '#10B981',
                'deskripsi' => 'Pengeluaran untuk kebersihan diri dan kesehatan',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Makanan & Minuman',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'makan', 'nasi', 'lauk', 'sayur', 'buah', 'jajan', 
                    'snack', 'minum', 'air', 'teh', 'kopi', 'susu',
                    'roti', 'bakso', 'mie', 'soto', 'ayam', 'ikan',
                    'telur', 'tempe', 'tahu', 'warung', 'kantin', 'menu'
                ],
                'icon' => 'fa-solid fa-utensils',
                'color' => '#F59E0B',
                'deskripsi' => 'Pengeluaran untuk makanan dan minuman',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Pendidikan & Alat Tulis',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'buku', 'pulpen', 'pensil', 'penghapus', 'penggaris',
                    'spidol', 'kertas', 'map', 'binder', 'bolpoin',
                    'catatan', 'tulis', 'les', 'kursus', 'modul',
                    'fotocopy', 'print', 'kitab', 'alquran', 'hafalan'
                ],
                'icon' => 'fa-solid fa-book',
                'color' => '#3B82F6',
                'deskripsi' => 'Pengeluaran untuk keperluan pendidikan',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Transportasi',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'transport', 'ojek', 'angkot', 'bus', 'travel',
                    'bensin', 'parkir', 'tol', 'pulang', 'berangkat',
                    'perjalanan', 'jalan', 'antar', 'jemput'
                ],
                'icon' => 'fa-solid fa-car',
                'color' => '#6366F1',
                'deskripsi' => 'Pengeluaran untuk transportasi',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Komunikasi & Pulsa',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'pulsa', 'kuota', 'internet', 'paket data', 'wifi',
                    'telpon', 'sms', 'whatsapp', 'wa', 'komunikasi'
                ],
                'icon' => 'fa-solid fa-mobile-screen-button',
                'color' => '#8B5CF6',
                'deskripsi' => 'Pengeluaran untuk komunikasi',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Pakaian & Laundry',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'baju', 'celana', 'kaos', 'kemeja', 'koko', 'sarung',
                    'sepatu', 'sandal', 'laundry', 'cuci', 'setrika',
                    'jahit', 'peci', 'gamis', 'jilbab'
                ],
                'icon' => 'fa-solid fa-shirt',
                'color' => '#EC4899',
                'deskripsi' => 'Pengeluaran untuk pakaian dan laundry',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Ibadah & Keagamaan',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'sedekah', 'infaq', 'zakat', 'shodaqoh', 'masjid',
                    'tahlil', 'yasin', 'doa', 'sumbangan', 'amal'
                ],
                'icon' => 'fa-solid fa-mosque',
                'color' => '#14B8A6',
                'deskripsi' => 'Pengeluaran untuk kegiatan ibadah',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Hiburan & Rekreasi',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'hiburan', 'nonton', 'film', 'game', 'main',
                    'jalan-jalan', 'wisata', 'rekreasi', 'liburan'
                ],
                'icon' => 'fa-solid fa-gamepad',
                'color' => '#F97316',
                'deskripsi' => 'Pengeluaran untuk hiburan',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Lain-lain',
                'jenis' => 'pengeluaran',
                'keywords' => [
                    'lain', 'lainnya', 'dll', 'etc', 'miscellaneous'
                ],
                'icon' => 'fa-solid fa-ellipsis',
                'color' => '#6B7280',
                'deskripsi' => 'Pengeluaran lain-lain',
                'is_active' => true
            ],

            // KATEGORI PEMASUKAN
            [
                'nama_kategori' => 'Uang Saku Orang Tua',
                'jenis' => 'pemasukan',
                'keywords' => [
                    'uang saku', 'kiriman', 'transfer', 'orangtua', 'orang tua',
                    'bapak', 'ibu', 'ayah', 'mama', 'papa', 'keluarga'
                ],
                'icon' => 'fa-solid fa-hand-holding-dollar',
                'color' => '#10B981',
                'deskripsi' => 'Pemasukan dari orang tua',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Beasiswa',
                'jenis' => 'pemasukan',
                'keywords' => [
                    'beasiswa', 'bantuan', 'scholarship', 'yayasan',
                    'donasi', 'bantuan pendidikan'
                ],
                'icon' => 'fa-solid fa-graduation-cap',
                'color' => '#3B82F6',
                'deskripsi' => 'Pemasukan dari beasiswa',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Hadiah & Bonus',
                'jenis' => 'pemasukan',
                'keywords' => [
                    'hadiah', 'bonus', 'lomba', 'juara', 'penghargaan',
                    'prestasi', 'reward', 'gift'
                ],
                'icon' => 'fa-solid fa-gift',
                'color' => '#F59E0B',
                'deskripsi' => 'Pemasukan dari hadiah atau bonus',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Pekerjaan Sampingan',
                'jenis' => 'pemasukan',
                'keywords' => [
                    'kerja', 'gaji', 'upah', 'honor', 'freelance',
                    'paruh waktu', 'part time', 'penghasilan'
                ],
                'icon' => 'fa-solid fa-briefcase',
                'color' => '#8B5CF6',
                'deskripsi' => 'Pemasukan dari pekerjaan',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Saldo Awal',
                'jenis' => 'pemasukan',
                'keywords' => [
                    'saldo awal', 'modal awal', 'deposit', 'initial',
                    'pembukaan', 'registrasi'
                ],
                'icon' => 'fa-solid fa-wallet',
                'color' => '#14B8A6',
                'deskripsi' => 'Saldo awal santri',
                'is_active' => true
            ],
            [
                'nama_kategori' => 'Lain-lain',
                'jenis' => 'pemasukan',
                'keywords' => [
                    'lain', 'lainnya', 'dll', 'etc', 'miscellaneous'
                ],
                'icon' => 'fa-solid fa-ellipsis',
                'color' => '#6B7280',
                'deskripsi' => 'Pemasukan lain-lain',
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            KeuanganSantriCategory::create($category);
        }
    }
}
