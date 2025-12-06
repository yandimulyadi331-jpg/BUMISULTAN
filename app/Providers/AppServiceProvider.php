<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Observers\GlobalActivityObserver;

// Import semua model yang ingin diobserve
use App\Models\Presensi;
use App\Models\AktivitasKendaraan;
use App\Models\Pinjaman;
use App\Models\Izinabsen;
use App\Models\Izinsakit;
use App\Models\Izincuti;
use App\Models\Lembur;
use App\Models\AktivitasKaryawan;
use App\Models\PeminjamanInventaris;
use App\Models\PengembalianInventaris;
use App\Models\TransferBarang;
use App\Models\PeminjamanKendaraan;
use App\Models\ServiceKendaraan;
use App\Models\AbsensiSantri;
use App\Models\PelanggaranSantri;
use App\Models\KeuanganSantriTransaction;
use App\Models\KehadiranTukang;
use App\Models\PinjamanTukang;
use App\Models\KehadiranJamaah;
use App\Models\KehadiranJamaahMasar;
use App\Models\DistribusiHadiah;
use App\Models\DistribusiHadiahMasar;
use App\Models\Administrasi;
use App\Models\TindakLanjutAdministrasi;
use App\Models\TransaksiKeuangan;
use App\Models\RealisasiDanaOperasional;
use App\Models\PengajuanDanaOperasional;

// Model tambahan yang perlu diobserve
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Barang;
use App\Models\Inventaris;
use App\Models\Kendaraan;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Santri;
use App\Models\Tukang;
use App\Models\JamaahMajlisTaklim;
use App\Models\JamaahMasar;
use App\Models\Document;
use App\Models\KpiCrew;
use App\Models\Kunjungan;
use App\Models\TugasLuar;
use App\Models\PresensiYayasan;
use App\Observers\PresensiYayasanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        // Daftarkan Global Activity Observer untuk semua model
        $modelsToObserve = [
            // Model utama aplikasi
            Presensi::class,
            AktivitasKendaraan::class,
            Pinjaman::class,
            Izinabsen::class,
            Izinsakit::class,
            Izincuti::class,
            Lembur::class,
            AktivitasKaryawan::class,
            PeminjamanInventaris::class,
            PengembalianInventaris::class,
            TransferBarang::class,
            PeminjamanKendaraan::class,
            ServiceKendaraan::class,
            
            // Model santri & tukang
            AbsensiSantri::class,
            PelanggaranSantri::class,
            KeuanganSantriTransaction::class,
            KehadiranTukang::class,
            PinjamanTukang::class,
            Santri::class,
            Tukang::class,
            
            // Model jamaah
            KehadiranJamaah::class,
            KehadiranJamaahMasar::class,
            DistribusiHadiah::class,
            DistribusiHadiahMasar::class,
            JamaahMajlisTaklim::class,
            JamaahMasar::class,
            PresensiYayasan::class,
            
            // Model administrasi & dokumentasi
            Administrasi::class,
            TindakLanjutAdministrasi::class,
            Document::class,
            
            // Model keuangan
            TransaksiKeuangan::class,
            RealisasiDanaOperasional::class,
            PengajuanDanaOperasional::class,
            
            // Model inventaris & fasilitas
            Inventaris::class,
            Barang::class,
            Kendaraan::class,
            Gedung::class,
            Ruangan::class,
            
            // Model karyawan & HR
            Karyawan::class,
            User::class,
            KpiCrew::class,
            Kunjungan::class,
            TugasLuar::class,
        ];
        
        foreach ($modelsToObserve as $model) {
            if (class_exists($model)) {
                $model::observe(GlobalActivityObserver::class);
            }
        }
        
        // Observer khusus untuk auto-increment kehadiran
        PresensiYayasan::observe(PresensiYayasanObserver::class);
    }
}
