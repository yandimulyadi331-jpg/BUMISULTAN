<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriAkunKeuangan;
use App\Models\AkunKeuangan;

class AkunKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini akan membuat Chart of Accounts (COA) standar
     * berdasarkan praktik akuntansi yang umum digunakan di Indonesia
     */
    public function run(): void
    {
        // 1. KATEGORI AKUN
        $kategoriAset = KategoriAkunKeuangan::create([
            'kode' => '1',
            'nama' => 'ASET',
            'tipe' => 'aset',
            'deskripsi' => 'Harta atau kekayaan yang dimiliki perusahaan',
            'is_active' => true,
        ]);

        $kategoriLiabilitas = KategoriAkunKeuangan::create([
            'kode' => '2',
            'nama' => 'LIABILITAS',
            'tipe' => 'liabilitas',
            'deskripsi' => 'Kewajiban atau utang perusahaan',
            'is_active' => true,
        ]);

        $kategoriEkuitas = KategoriAkunKeuangan::create([
            'kode' => '3',
            'nama' => 'EKUITAS',
            'tipe' => 'ekuitas',
            'deskripsi' => 'Modal atau kekayaan bersih perusahaan',
            'is_active' => true,
        ]);

        $kategoriPendapatan = KategoriAkunKeuangan::create([
            'kode' => '4',
            'nama' => 'PENDAPATAN',
            'tipe' => 'pendapatan',
            'deskripsi' => 'Penghasilan dari kegiatan usaha',
            'is_active' => true,
        ]);

        $kategoriBeban = KategoriAkunKeuangan::create([
            'kode' => '5',
            'nama' => 'BEBAN',
            'tipe' => 'beban',
            'deskripsi' => 'Pengeluaran untuk operasional perusahaan',
            'is_active' => true,
        ]);

        // 2. AKUN ASET (1-XXXX)
        
        // Aset Lancar (1-1XXX)
        $asetLancar = AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-1000',
            'nama_akun' => 'ASET LANCAR',
            'posisi_normal' => 'debit',
            'level' => 1,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-1001',
            'nama_akun' => 'Kas',
            'posisi_normal' => 'debit',
            'parent_id' => $asetLancar->id,
            'level' => 2,
            'is_active' => true,
            'is_kas_bank' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-1002',
            'nama_akun' => 'Bank',
            'posisi_normal' => 'debit',
            'parent_id' => $asetLancar->id,
            'level' => 2,
            'is_active' => true,
            'is_kas_bank' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-1003',
            'nama_akun' => 'Piutang Usaha',
            'posisi_normal' => 'debit',
            'parent_id' => $asetLancar->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-1004',
            'nama_akun' => 'Persediaan',
            'posisi_normal' => 'debit',
            'parent_id' => $asetLancar->id,
            'level' => 2,
            'is_active' => true,
        ]);

        // Aset Tetap (1-2XXX)
        $asetTetap = AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-2000',
            'nama_akun' => 'ASET TETAP',
            'posisi_normal' => 'debit',
            'level' => 1,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-2001',
            'nama_akun' => 'Tanah',
            'posisi_normal' => 'debit',
            'parent_id' => $asetTetap->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-2002',
            'nama_akun' => 'Bangunan',
            'posisi_normal' => 'debit',
            'parent_id' => $asetTetap->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-2003',
            'nama_akun' => 'Kendaraan',
            'posisi_normal' => 'debit',
            'parent_id' => $asetTetap->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriAset->id,
            'kode_akun' => '1-2004',
            'nama_akun' => 'Peralatan',
            'posisi_normal' => 'debit',
            'parent_id' => $asetTetap->id,
            'level' => 2,
            'is_active' => true,
        ]);

        // 3. AKUN LIABILITAS (2-XXXX)
        
        // Liabilitas Jangka Pendek (2-1XXX)
        $liabilitasPendek = AkunKeuangan::create([
            'kategori_id' => $kategoriLiabilitas->id,
            'kode_akun' => '2-1000',
            'nama_akun' => 'LIABILITAS JANGKA PENDEK',
            'posisi_normal' => 'kredit',
            'level' => 1,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriLiabilitas->id,
            'kode_akun' => '2-1001',
            'nama_akun' => 'Utang Usaha',
            'posisi_normal' => 'kredit',
            'parent_id' => $liabilitasPendek->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriLiabilitas->id,
            'kode_akun' => '2-1002',
            'nama_akun' => 'Utang Gaji',
            'posisi_normal' => 'kredit',
            'parent_id' => $liabilitasPendek->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriLiabilitas->id,
            'kode_akun' => '2-1003',
            'nama_akun' => 'Utang Pajak',
            'posisi_normal' => 'kredit',
            'parent_id' => $liabilitasPendek->id,
            'level' => 2,
            'is_active' => true,
        ]);

        // 4. AKUN EKUITAS (3-XXXX)
        $ekuitas = AkunKeuangan::create([
            'kategori_id' => $kategoriEkuitas->id,
            'kode_akun' => '3-1000',
            'nama_akun' => 'MODAL',
            'posisi_normal' => 'kredit',
            'level' => 1,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriEkuitas->id,
            'kode_akun' => '3-1001',
            'nama_akun' => 'Modal Pemilik',
            'posisi_normal' => 'kredit',
            'parent_id' => $ekuitas->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriEkuitas->id,
            'kode_akun' => '3-1002',
            'nama_akun' => 'Laba Ditahan',
            'posisi_normal' => 'kredit',
            'parent_id' => $ekuitas->id,
            'level' => 2,
            'is_active' => true,
        ]);

        // 5. AKUN PENDAPATAN (4-XXXX)
        $pendapatan = AkunKeuangan::create([
            'kategori_id' => $kategoriPendapatan->id,
            'kode_akun' => '4-1000',
            'nama_akun' => 'PENDAPATAN USAHA',
            'posisi_normal' => 'kredit',
            'level' => 1,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriPendapatan->id,
            'kode_akun' => '4-1001',
            'nama_akun' => 'Pendapatan Jasa',
            'posisi_normal' => 'kredit',
            'parent_id' => $pendapatan->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriPendapatan->id,
            'kode_akun' => '4-1002',
            'nama_akun' => 'Pendapatan Penjualan',
            'posisi_normal' => 'kredit',
            'parent_id' => $pendapatan->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriPendapatan->id,
            'kode_akun' => '4-1003',
            'nama_akun' => 'Pendapatan Lain-lain',
            'posisi_normal' => 'kredit',
            'parent_id' => $pendapatan->id,
            'level' => 2,
            'is_active' => true,
        ]);

        // 6. AKUN BEBAN (5-XXXX)
        
        // Beban Operasional (5-1XXX)
        $bebanOperasional = AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1000',
            'nama_akun' => 'BEBAN OPERASIONAL',
            'posisi_normal' => 'debit',
            'level' => 1,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1001',
            'nama_akun' => 'Beban Gaji',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1002',
            'nama_akun' => 'Beban Listrik',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1003',
            'nama_akun' => 'Beban Air',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1004',
            'nama_akun' => 'Beban Telepon & Internet',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1005',
            'nama_akun' => 'Beban Perlengkapan',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1006',
            'nama_akun' => 'Beban Transportasi',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1007',
            'nama_akun' => 'Beban Pemeliharaan',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1008',
            'nama_akun' => 'Beban Sewa',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1009',
            'nama_akun' => 'Beban Penyusutan',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        AkunKeuangan::create([
            'kategori_id' => $kategoriBeban->id,
            'kode_akun' => '5-1010',
            'nama_akun' => 'Beban Lain-lain',
            'posisi_normal' => 'debit',
            'parent_id' => $bebanOperasional->id,
            'level' => 2,
            'is_active' => true,
        ]);

        echo "✓ Berhasil membuat Chart of Accounts standar\n";
        echo "✓ Total Kategori: 5\n";
        echo "✓ Total Akun: " . AkunKeuangan::count() . "\n";
    }
}
