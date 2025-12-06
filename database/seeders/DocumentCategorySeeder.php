<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'kode_kategori' => 'SK',
                'nama_kategori' => 'Surat Keputusan',
                'deskripsi' => 'Surat Keputusan Perusahaan',
                'warna' => '#007bff',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'PKS',
                'nama_kategori' => 'Perjanjian Kerja Sama',
                'deskripsi' => 'Dokumen Perjanjian Kerja Sama dengan Pihak Ketiga',
                'warna' => '#28a745',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'SOP',
                'nama_kategori' => 'Standard Operating Procedure',
                'deskripsi' => 'Prosedur Operasional Standar',
                'warna' => '#ffc107',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KTK',
                'nama_kategori' => 'Kontrak Karyawan',
                'deskripsi' => 'Kontrak Kerja Karyawan',
                'warna' => '#17a2b8',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'INV',
                'nama_kategori' => 'Invoice',
                'deskripsi' => 'Dokumen Invoice/Tagihan',
                'warna' => '#dc3545',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'LPR',
                'nama_kategori' => 'Laporan',
                'deskripsi' => 'Laporan Kegiatan/Keuangan',
                'warna' => '#6610f2',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'SRT',
                'nama_kategori' => 'Surat Menyurat',
                'deskripsi' => 'Surat Resmi Keluar/Masuk',
                'warna' => '#fd7e14',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'IZN',
                'nama_kategori' => 'Perizinan',
                'deskripsi' => 'Dokumen Perizinan Perusahaan',
                'warna' => '#20c997',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'NDA',
                'nama_kategori' => 'Non-Disclosure Agreement',
                'deskripsi' => 'Perjanjian Kerahasiaan',
                'warna' => '#6c757d',
                'last_number' => 0,
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'MOU',
                'nama_kategori' => 'Memorandum of Understanding',
                'deskripsi' => 'Nota Kesepahaman',
                'warna' => '#e83e8c',
                'last_number' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('document_categories')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
