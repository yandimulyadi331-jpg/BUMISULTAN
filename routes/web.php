<?php

use App\Http\Controllers\BpjskesehatanController;
use App\Http\Controllers\BpjstenagakerjaController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\FacerecognitionController;
use App\Http\Controllers\GajipokokController;
use App\Http\Controllers\GeneralsettingController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\HariliburController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzindinasController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JamkerjabydeptController;
use App\Http\Controllers\JamkerjaController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\TrackingKunjunganController;
use App\Http\Controllers\JenistunjanganController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\PengajuanizinController;
use App\Http\Controllers\PenyesuaiangajiController;
use App\Http\Controllers\PotonganPinjamanPayrollController;
use App\Http\Controllers\Permission_groupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiistirahatController;
use App\Http\Controllers\YayasanPresensiController;
use App\Http\Controllers\YayasanLaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SlipgajiController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WagatewayController;
use App\Http\Controllers\FacerecognitionpresensiController;
use App\Http\Controllers\IconGeneratorController;
use App\Http\Controllers\BersihkanfotoController;
use App\Http\Controllers\TrackingPresensiController;
use App\Http\Controllers\AktivitasKaryawanController;
use App\Http\Controllers\KpiCrewController;
use App\Http\Controllers\YayasanMasarController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KendaraanKaryawanController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SignupControllerImproved;
use App\Http\Controllers\AktivitasKendaraanController;
use App\Http\Controllers\PeminjamanKendaraanController;
use App\Http\Controllers\TransaksiKeuanganController;
use App\Http\Controllers\DanaOperasionalController;
use App\Http\Controllers\JamaahMajlisTaklimController;
use App\Http\Controllers\HadiahMajlisTaklimController;
use App\Http\Controllers\JamaahMasarController;
use App\Http\Controllers\HadiahMasarController;
use App\Http\Controllers\DistribusiHadiahMasarController;
use App\Http\Controllers\UndianUmrohController;
use App\Http\Controllers\KeuanganSantriController;
use App\Http\Controllers\ServiceKendaraanController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PengunjungKaryawanController;
use App\Http\Controllers\LiveTrackingController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanInventarisController;
use App\Http\Controllers\PengembalianInventarisController;
use App\Http\Controllers\HistoryInventarisController;
use App\Http\Controllers\AdministrasiController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PeminjamanPeralatanController;
use App\Http\Controllers\PelanggaranSantriController;
use App\Http\Controllers\KhidmatController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\KehadiranTukangController;
use App\Http\Controllers\KeuanganTukangController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanKeuanganKaryawanController;
use App\Http\Controllers\ManajemenPerawatanController;
use App\Http\Controllers\PerawatanKaryawanController;
use App\Http\Controllers\InventarisPeralatanKaryawanController;
use App\Http\Controllers\TemuanController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.loginuser');
    })->name('loginuser');
    
    // Signup Routes - IMPROVED VERSION dengan foto profil + 5 foto wajah terpisah
    Route::get('/signup', [SignupControllerImproved::class, 'index'])->name('signup');
    Route::post('/signup', [SignupControllerImproved::class, 'store'])->name('signup.store');
});

// Face Recognition Presensi Routes (Public - No Login Required)
Route::controller(FacerecognitionpresensiController::class)->group(function () {
    Route::get('/facerecognition-presensi', 'index')->name('facerecognition-presensi.index');
    Route::get('/facerecognition-presensi/scan/{nik}', 'scan')->name('facerecognition-presensi.scan');
    Route::get('/facerecognition-presensi/scanall', 'scanAny')->name('facerecognition-presensi.scan_any');
    Route::post('/facerecognition-presensi/store', 'store')->name('facerecognition-presensi.store');
    Route::get('/facerecognition-presensi/generate/{nik}', 'getKaryawan')->name('facerecognition-presensi.generate');
    Route::get('/facerecognition/getallwajah', 'getAllWajah')->name('facerecognition.getallwajah');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Setings
    //Role

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard.index');
        Route::post('/dashboard/kirim-ucapan-birthday', 'kirimUcapanBirthday')->name('dashboard.kirim.ucapan.birthday');
    });
    
    // Tugas Luar Routes
    Route::prefix('tugas-luar')->controller(App\Http\Controllers\TugasLuarController::class)->group(function () {
        Route::get('/', 'index')->name('tugas-luar.index');
        Route::get('/create', 'create')->name('tugas-luar.create');
        Route::post('/store', 'store')->name('tugas-luar.store');
        Route::post('/{id}/kembali', 'kembali')->name('tugas-luar.kembali');
        Route::post('/{id}/approve', 'approve')->name('tugas-luar.approve');
        Route::post('/{id}/reject', 'reject')->name('tugas-luar.reject');
        Route::delete('/{id}', 'destroy')->name('tugas-luar.destroy');
    });
    
    // ==================== KENDARAAN KARYAWAN ROUTES ====================
    // Semua karyawan bisa akses untuk operasional kendaraan
    
    // Kendaraan Karyawan - View Only untuk Master Data
    Route::prefix('kendaraan-karyawan')->controller(KendaraanKaryawanController::class)->group(function () {
        Route::get('/', 'index')->name('kendaraan.karyawan.index');
        Route::get('/new', 'indexNew')->name('kendaraan.karyawan.index.new');
        Route::get('/{id}/detail', 'show')->name('kendaraan.karyawan.detail');
        
        // Submit actions from karyawan page
        Route::post('/submit-keluar', 'submitKeluarKendaraan')->name('kendaraan.karyawan.submit.keluar');
        Route::post('/submit-return', 'submitReturnKendaraan')->name('kendaraan.karyawan.submit.return');
        Route::post('/submit-pinjam', 'submitPeminjamanKendaraan')->name('kendaraan.karyawan.submit.pinjam');
        Route::post('/submit-return-pinjam', 'submitReturnPeminjaman')->name('kendaraan.karyawan.submit.return.pinjam');
        Route::post('/submit-service', 'submitServiceRequest')->name('kendaraan.karyawan.submit.service');
    });
    
    // Aktivitas Kendaraan - FULL ACCESS untuk Karyawan
    Route::controller(AktivitasKendaraanController::class)->group(function () {
        Route::get('/kendaraan/{kendaraan_id}/aktivitas/keluar', 'formKeluar')->name('kendaraan.aktivitas.keluar');
        Route::post('/kendaraan/{kendaraan_id}/aktivitas/keluar', 'prosesKeluar')->name('kendaraan.aktivitas.prosesKeluar');
        Route::get('/aktivitas/{id}/kembali', 'formKembali')->name('kendaraan.aktivitas.kembali');
        Route::post('/aktivitas/{id}/kembali', 'prosesKembali')->name('kendaraan.aktivitas.prosesKembali');
        Route::get('/aktivitas/{id}/tracking', 'tracking')->name('kendaraan.aktivitas.tracking');
        Route::get('/aktivitas/{id}/tracking/data', 'getTrackingData')->name('kendaraan.aktivitas.tracking.data');
        Route::delete('/aktivitas/{id}', 'destroy')->name('kendaraan.aktivitas.delete');
    });
    
    // Peminjaman Kendaraan - FULL ACCESS untuk Karyawan
    Route::controller(PeminjamanKendaraanController::class)->group(function () {
        Route::get('/kendaraan/{kendaraan_id}/peminjaman/pinjam', 'formPinjam')->name('kendaraan.peminjaman.pinjam');
        Route::post('/kendaraan/{kendaraan_id}/peminjaman/pinjam', 'prosesPinjam')->name('kendaraan.peminjaman.prosesPinjam');
        Route::get('/peminjaman/{id}/surat-jalan', 'suratJalan')->name('kendaraan.peminjaman.surat');
        Route::get('/peminjaman/{id}/pdf-transportasi', 'downloadSuratTransportasi')->name('kendaraan.peminjaman.pdf.transportasi');
        Route::get('/peminjaman/{id}/pdf-peminjam', 'downloadSuratPeminjam')->name('kendaraan.peminjaman.pdf.peminjam');
        Route::get('/peminjaman/{id}/kembali', 'formKembali')->name('kendaraan.peminjaman.kembali');
        Route::post('/peminjaman/{id}/kembali', 'prosesKembali')->name('kendaraan.peminjaman.prosesKembali');
        Route::get('/peminjaman/{id}/tracking', 'tracking')->name('kendaraan.peminjaman.tracking');
        Route::get('/peminjaman/{id}/tracking/data', 'getTrackingData')->name('kendaraan.peminjaman.tracking.data');
    });
    
    // Service Kendaraan - FULL ACCESS untuk Karyawan
    Route::controller(ServiceKendaraanController::class)->group(function () {
        Route::get('/kendaraan/{kendaraan_id}/service/form', 'formService')->name('service.form');
        Route::post('/kendaraan/{kendaraan_id}/service', 'prosesService')->name('service.proses');
        Route::get('/service/{id}/selesai', 'formSelesai')->name('service.selesai');
        Route::post('/service/{id}/selesai', 'prosesSelesai')->name('service.prosesSelesai');
    });
    
    // Live Tracking untuk Karyawan
    Route::controller(LiveTrackingController::class)->group(function () {
        Route::get('/admin/live-tracking/{aktivitas_id}', 'adminLiveTracking')->name('admin.livetracking');
        Route::get('/api/gps/latest/{aktivitas_id}', 'getLatestGpsData')->name('api.gps.latest');
        Route::get('/driver/tracking/{aktivitas_id}', 'driverTracking')->name('driver.tracking');
        Route::post('/api/gps/store/{aktivitas_id}', 'storeGpsData')->name('api.gps.store');
    });
    
    // API untuk encrypt ID (untuk swipe navigation)
    Route::get('/api/encrypt-id/{id}', [KendaraanKaryawanController::class, 'encryptId']);
    
    // API untuk get kendaraan info
    Route::get('/api/kendaraan/{id}', function($id) {
        return App\Models\Kendaraan::findOrFail($id);
    });
    
    // ==================== END KENDARAAN KARYAWAN ROUTES ====================
    
    Route::middleware('role:super admin')->controller(RoleController::class)->group(function () {
        Route::get('/roles', 'index')->name('roles.index');
        Route::get('/roles/create', 'create')->name('roles.create');
        Route::post('/roles', 'store')->name('roles.store');
        Route::get('/roles/{id}/edit', 'edit')->name('roles.edit');
        Route::put('/roles/{id}/update', 'update')->name('roles.update');
        Route::delete('/roles/{id}/delete', 'destroy')->name('roles.delete');
        Route::get('/roles/{id}/createrolepermission', 'createrolepermission')->name('roles.createrolepermission');
        Route::post('/roles/{id}/storerolepermission', 'storerolepermission')->name('roles.storerolepermission');
    });


    Route::middleware('role:super admin')->controller(Permission_groupController::class)->group(function () {
        Route::get('/permissiongroups', 'index')->name('permissiongroups.index');
        Route::get('/permissiongroups/create', 'create')->name('permissiongroups.create');
        Route::post('/permissiongroups', 'store')->name('permissiongroups.store');
        Route::get('/permissiongroups/{id}/edit', 'edit')->name('permissiongroups.edit');
        Route::put('/permissiongroups/{id}/update', 'update')->name('permissiongroups.update');
        Route::delete('/permissiongroups/{id}/delete', 'destroy')->name('permissiongroups.delete');
    });


    Route::middleware('role:super admin')->controller(PermissionController::class)->group(function () {
        Route::get('/permissions', 'index')->name('permissions.index');
        Route::get('/permissions/create', 'create')->name('permissions.create');
        Route::post('/permissions', 'store')->name('permissions.store');
        Route::get('/permissions/{id}/edit', 'edit')->name('permissions.edit');
        Route::put('/permissions/{id}/update', 'update')->name('permissions.update');
        Route::delete('/permissions/{id}/delete', 'destroy')->name('permissions.delete');
    });

    Route::middleware('role:super admin')->controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/create', 'create')->name('users.create');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{id}/edit', 'edit')->name('users.edit');
        Route::put('/users/{id}/update', 'update')->name('users.update');
        Route::delete('/users/{id}/delete', 'destroy')->name('users.delete');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users/{id}/editpassword', 'editpassword')->name('users.editpassword');
        Route::put('/users/{id}/updatepassword', 'updatepassword')->name('users.updatepassword');
    });

    //Data Master
    //Dat Karyawan
    Route::controller(KaryawanController::class)->group(function () {
        Route::get('/karyawan', 'index')->name('karyawan.index')->can('karyawan.index');
        Route::get('/karyawan/create', 'create')->name('karyawan.create')->can('karyawan.create');
        Route::post('/karyawan', 'store')->name('karyawan.store')->can('karyawan.create');
        Route::get('/karyawan/import', 'import')->name('karyawan.import')->can('karyawan.create');
        Route::get('/karyawan/download-template', 'download_template')->name('karyawan.download_template')->can('karyawan.create');
        Route::post('/karyawan/import', 'import_proses')->name('karyawan.import_proses')->can('karyawan.create');
        Route::get('/karyawan/{nik}/edit', 'edit')->name('karyawan.edit')->can('karyawan.edit');
        Route::put('/karyawan/{nik}', 'update')->name('karyawan.update')->can('karyawan.edit');
        Route::delete('/karyawan/{nik}', 'destroy')->name('karyawan.delete')->can('karyawan.delete');
        Route::get('/karyawan/{nik}/show', 'show')->name('karyawan.show')->can('karyawan.show');
        Route::get('/karyawan/{nik}/setjamkerja', 'setjamkerja')->name('karyawan.setjamkerja')->can('karyawan.setjamkerja');
        Route::post('/karyawan/{nik}/storejamkerjabyday', 'storejamkerjabyday')->name('karyawan.storejamkerjabyday')->can('karyawan.setjamkerja');
        Route::get('/karyawan/{nik}/setcabang', 'setcabang')->name('karyawan.setcabang')->can('karyawan.setcabang');
        Route::post('/karyawan/{nik}/storecabang', 'storecabang')->name('karyawan.storecabang')->can('karyawan.setcabang');
        Route::post('/karyawan/storejamkerjabydate', 'storejamkerjabydate')->name('karyawan.storejamkerjabydate')->can('karyawan.setjamkerja');

        Route::post('/karyawan/getjamkerjabydate', 'getjamkerjabydate')->name('karyawan.getjamkerjabydate')->can('karyawan.setjamkerja');
        Route::post('/karyawan/deletejamkerjabydate', 'deletejamkerjabydate')->name('karyawan.deletejamkerjabydate')->can('karyawan.setjamkerja');

        Route::get('/karyawan/{nik}/createuser', 'createuser')->name('karyawan.createuser')->can('users.create');
        Route::get('/karyawan/{nik}/deleteuser', 'deleteuser')->name('karyawan.deleteuser')->can('users.create');
        Route::get('/karyawan/{nik}/lockunlocklocation', 'lockunlocklocation')->name('karyawan.lockunlocklocation')->can('karyawan.edit');
        Route::get('/karyawan/{nik}/lockunlockjamkerja', 'lockunlockjamkerja')->name('karyawan.lockunlockjamkerja')->can('karyawan.edit');
        Route::get('/karyawan/{nik}/idcard', 'idcard')->name('karyawan.idcard');

        Route::get('/karyawan/getkaryawan', 'getkaryawan')->name('karyawan.getkaryawan');
    });

    Route::controller(DepartemenController::class)->group(function () {
        Route::get('/departemen', 'index')->name('departemen.index')->can('departemen.index');
        Route::get('/departemen/create', 'create')->name('departemen.create')->can('departemen.create');
        Route::post('/departemen', 'store')->name('departemen.store')->can('departemen.create');
        Route::get('/departemen/{nik}', 'edit')->name('departemen.edit')->can('departemen.edit');
        Route::put('/departemen/{nik}', 'update')->name('departemen.update')->can('departemen.edit');
        Route::delete('/departemen/{nik}/delete', 'destroy')->name('departemen.delete')->can('departemen.delete');
    });

    Route::controller(GrupController::class)->group(function () {
        Route::get('/grup', 'index')->name('grup.index')->can('grup.index');
        Route::get('/grup/create', 'create')->name('grup.create')->can('grup.create');
        Route::post('/grup', 'store')->name('grup.store')->can('grup.create');

        // Route pencarian karyawan di grup (letakkan sebelum route parameter)
        Route::get('/grup/search-karyawan', 'searchKaryawan')->name('grup.searchKaryawan');
        // Form karyawan baru di grup (hindari tertangkap oleh {kode_grup})
        Route::get('/grup/{kode_grup}/create-karyawan-form', 'createKaryawanForm')->name('grup.createKaryawanForm')->can('grup.detail');
        // Get anggota grup untuk AJAX update
        Route::get('/grup/{kode_grup}/get-anggota', 'getAnggotaGrup')->name('grup.getAnggotaGrup');
        // Set jam kerja grup
        Route::get('/grup/{kode_grup}/set-jam-kerja', 'setJamKerja')->name('grup.setJamKerja')->can('grup.setJamKerja');
        Route::match(['PUT', 'POST'], '/grup/{kode_grup}/update-jam-kerja', 'updateJamKerja')->name('grup.updateJamKerja')->can('grup.setJamKerja');
        Route::delete('/grup/delete-jam-kerja-bydate', 'deleteJamKerjaBydate')->name('grup.deleteJamKerjaBydate')->can('grup.setJamKerja');
        Route::post('/grup/{kode_grup}/get-jam-kerja-bydate', 'getJamKerjaBydate')->name('grup.getJamKerjaBydate')->can('grup.setJamKerja');
        // Detail grup (letakkan sebelum {kode_grup})
        Route::get('/grup/{kode_grup}/detail', 'detail')->name('grup.detail')->can('grup.detail');
        // Tambah karyawan ke grup (hindari tertangkap oleh {kode_grup})
        Route::post('/grup/add-karyawan', 'addKaryawan')->name('grup.addKaryawan')->can('grup.detail');
        // Hapus karyawan dari grup (hindari tertangkap oleh {kode_grup})
        Route::delete('/grup/remove-karyawan', 'removeKaryawan')->name('grup.removeKaryawan')->can('grup.detail');

        // Route manipulasi data grup (setelah route spesifik di atas)
        Route::get('/grup/{kode_grup}', 'edit')->name('grup.edit')->can('grup.edit');
        Route::delete('/grup/{kode_grup}/delete', 'delete')->name('grup.delete')->can('grup.delete');
        Route::put('/grup/{kode_grup}', 'update')->name('grup.update')->can('grup.edit');
    });

    Route::controller(JabatanController::class)->group(function () {
        Route::get('/jabatan', 'index')->name('jabatan.index')->can('jabatan.index');
        Route::get('/jabatan/create', 'create')->name('jabatan.create')->can('jabatan.create');
        Route::post('/jabatan', 'store')->name('jabatan.store')->can('jabatan.create');
        Route::get('/jabatan/{kode_jabatan}', 'edit')->name('jabatan.edit')->can('jabatan.edit');
        Route::put('/jabatan/{kode_jabatan}', 'update')->name('jabatan.update')->can('jabatan.edit');
        Route::delete('/jabatan/{kode_jabatan}/delete', 'destroy')->name('jabatan.delete')->can('jabatan.delete');
    });


    Route::controller(CabangController::class)->group(function () {
        Route::get('/cabang', 'index')->name('cabang.index')->can('cabang.index');
        Route::get('/cabang/create', 'create')->name('cabang.create')->can('cabang.create');
        Route::post('/cabang', 'store')->name('cabang.store')->can('cabang.create');
        Route::get('/cabang/{kode_cabang}', 'edit')->name('cabang.edit')->can('cabang.edit');
        Route::put('/cabang/{kode_cabang}', 'update')->name('cabang.update')->can('cabang.edit');
        Route::delete('/cabang/{kode_cabang}/delete', 'destroy')->name('cabang.delete')->can('cabang.delete');
    });

    Route::controller(CutiController::class)->group(function () {
        Route::get('/cuti', 'index')->name('cuti.index')->can('cuti.index');
        Route::get('/cuti/create', 'create')->name('cuti.create')->can('cuti.create');
        Route::post('/cuti', 'store')->name('cuti.store')->can('cuti.create');
        Route::get('/cuti/{kode_cuti}', 'edit')->name('cuti.edit')->can('cuti.edit');
        Route::put('/cuti/{kode_cuti}', 'update')->name('cuti.update')->can('cuti.edit');
        Route::delete('/cuti/{kode_cuti}/delete', 'destroy')->name('cuti.delete')->can('cuti.delete');
    });

    Route::controller(JamkerjaController::class)->group(function () {
        Route::get('/jamkerja', 'index')->name('jamkerja.index')->can('jamkerja.index');
        Route::get('/jamkerja/create', 'create')->name('jamkerja.create')->can('jamkerja.create');
        Route::post('/jamkerja', 'store')->name('jamkerja.store')->can('jamkerja.create');
        Route::get('/jamkerja/{kode_jam_kerja}/edit', 'edit')->name('jamkerja.edit')->can('jamkerja.edit');
        Route::put('/jamkerja/{kode_jam_kerja}/update', 'update')->name('jamkerja.update')->can('jamkerja.edit');
        Route::delete('/jamkerja/{kode_jam_kerja}/delete', 'destroy')->name('jamkerja.delete')->can('jamkerja.delete');
    });


    Route::controller(GajipokokController::class)->group(function () {
        Route::get('/gajipokok', 'index')->name('gajipokok.index')->can('gajipokok.index');
        Route::get('/gajipokok/create', 'create')->name('gajipokok.create')->can('gajipokok.create');
        Route::post('/gajipokok', 'store')->name('gajipokok.store')->can('gajipokok.create');
        Route::get('/gajipokok/{kode_gaji}/edit', 'edit')->name('gajipokok.edit')->can('gajipokok.edit');
        Route::put('/gajipokok/{kode_gaji}/update', 'update')->name('gajipokok.update')->can('gajipokok.edit');
        Route::delete('/gajipokok/{kode_gaji}/delete', 'destroy')->name('gajipokok.delete')->can('gajipokok.delete');
    });

    Route::controller(JenistunjanganController::class)->group(function () {
        Route::get('/jenistunjangan', 'index')->name('jenistunjangan.index')->can('jenistunjangan.index');
        Route::get('/jenistunjangan/create', 'create')->name('jenistunjangan.create')->can('jenistunjangan.create');
        Route::post('/jenistunjangan', 'store')->name('jenistunjangan.store')->can('jenistunjangan.create');
        Route::get('/jenistunjangan/{kode_jenis_tunjangan}/edit', 'edit')->name('jenistunjangan.edit')->can('jenistunjangan.edit');
        Route::put('/jenistunjangan/{kode_jenis_tunjangan}/update', 'update')->name('jenistunjangan.update')->can('jenistunjangan.edit');
        Route::delete('/jenistunjangan/{kode_jenis_tunjangan}/delete', 'destroy')->name('jenistunjangan.delete')->can('jenistunjangan.delete');
    });


    Route::controller(TunjanganController::class)->group(function () {
        Route::get('/tunjangan', 'index')->name('tunjangan.index')->can('tunjangan.index');
        Route::get('/tunjangan/create', 'create')->name('tunjangan.create')->can('tunjangan.create');
        Route::post('/tunjangan', 'store')->name('tunjangan.store')->can('tunjangan.create');
        Route::get('/tunjangan/{kode_tunjangan}/edit', 'edit')->name('tunjangan.edit')->can('tunjangan.edit');
        Route::put('/tunjangan/{kode_tunjangan}/update', 'update')->name('tunjangan.update')->can('tunjangan.edit');
        Route::delete('/tunjangan/{kode_tunjangan}/delete', 'destroy')->name('tunjangan.delete')->can('tunjangan.delete');
    });


    Route::controller(BpjskesehatanController::class)->group(function () {
        Route::get('/bpjskesehatan', 'index')->name('bpjskesehatan.index')->can('bpjskesehatan.index');
        Route::get('/bpjskesehatan/create', 'create')->name('bpjskesehatan.create')->can('bpjskesehatan.create');
        Route::post('/bpjskesehatan', 'store')->name('bpjskesehatan.store')->can('bpjskesehatan.create');
        Route::get('/bpjskesehatan/{kode_bpjs_kesehatan}/edit', 'edit')->name('bpjskesehatan.edit')->can('bpjskesehatan.edit');
        Route::put('/bpjskesehatan/{kode_bpjs_kesehatan}/update', 'update')->name('bpjskesehatan.update')->can('bpjskesehatan.edit');
        Route::delete('/bpjskesehatan/{kode_bpjs_kesehatan}/delete', 'destroy')->name('bpjskesehatan.delete')->can('bpjskesehatan.delete');
    });

    Route::controller(BpjstenagakerjaController::class)->group(function () {
        Route::get('/bpjstenagakerja', 'index')->name('bpjstenagakerja.index')->can('bpjstenagakerja.index');
        Route::get('/bpjstenagakerja/create', 'create')->name('bpjstenagakerja.create')->can('bpjstenagakerja.create');
        Route::post('/bpjstenagakerja', 'store')->name('bpjstenagakerja.store')->can('bpjstenagakerja.create');
        Route::get('/bpjstenagakerja/{kode_bpjs_tk}/edit', 'edit')->name('bpjstenagakerja.edit')->can('bpjstenagakerja.edit');
        Route::put('/bpjstenagakerja/{kode_bpjs_tk}/update', 'update')->name('bpjstenagakerja.update')->can('bpjstenagakerja.edit');
        Route::delete('/bpjstenagakerja/{kode_bpjs_tk}/delete', 'destroy')->name('bpjstenagakerja.delete')->can('bpjstenagakerja.delete');
    });


    Route::controller(PenyesuaiangajiController::class)->group(function () {
        Route::get('/penyesuaiangaji', 'index')->name('penyesuaiangaji.index')->can('penyesuaiangaji.index');
        Route::get('/penyesuaiangaji/create', 'create')->name('penyesuaiangaji.create')->can('penyesuaiangaji.create');
        Route::post('/penyesuaiangaji', 'store')->name('penyesuaiangaji.store')->can('penyesuaiangaji.create');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/edit', 'edit')->name('penyesuaiangaji.edit')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/setkaryawan', 'setkaryawan')->name('penyesuaiangaji.setkaryawan')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/addkaryawan', 'addkaryawan')->name('penyesuaiangaji.addkaryawan')->can('penyesuaiangaji.edit');
        Route::post('/penyesuaiangaji/{kode_penyesuaian_gaji}/storekaryawan', 'storekaryawan')->name('penyesuaiangaji.storekaryawan')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/{nik}/editkaryawan', 'editkaryawan')->name('penyesuaiangaji.editkaryawan')->can('penyesuaiangaji.edit');
        Route::put('/penyesuaiangaji/{kode_penyesuaian_gaji}/{nik}/updatekaryawan', 'updatekaryawan')->name('penyesuaiangaji.updatekaryawan')->can('penyesuaiangaji.edit');
        Route::put('/penyesuaiangaji/{kode_penyesuaian_gaji}/update', 'update')->name('penyesuaiangaji.update')->can('penyesuaiangaji.edit');
        Route::delete('/penyesuaiangaji/{kode_penyesuaian_gaji}/delete', 'destroy')->name('penyesuaiangaji.delete')->can('penyesuaiangaji.delete');
        Route::delete('/penyesuaiangaji/{kode_penyesuaian_gaji}/{nik}/deletekaryawan', 'destroykaryawan')->name('penyesuaiangaji.deletekaryawan')->can('penyesuaiangaji.delete');
        Route::post('/penyesuaiangaji/{kode_penyesuaian_gaji}/generate-potongan-pinjaman', 'generatePotonganPinjaman')->name('penyesuaiangaji.generatePotonganPinjaman')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/summary-pinjaman', 'summaryPotonganPinjaman')->name('penyesuaiangaji.summaryPinjaman')->can('penyesuaiangaji.edit');
    });


    Route::controller(PotonganPinjamanPayrollController::class)->group(function () {
        Route::get('/potongan-pinjaman', 'index')->name('potongan_pinjaman.index')->can('potongan_pinjaman.index');
        Route::post('/potongan-pinjaman/generate', 'generate')->name('potongan_pinjaman.generate')->can('potongan_pinjaman.generate');
        Route::post('/potongan-pinjaman/proses', 'proses')->name('potongan_pinjaman.proses')->can('potongan_pinjaman.proses');
        Route::delete('/potongan-pinjaman/delete-periode', 'deleteByPeriode')->name('potongan_pinjaman.deletePeriode')->can('potongan_pinjaman.delete');
        Route::get('/potongan-pinjaman/get-by-nik/{nik}', 'getPotonganByNik')->name('potongan_pinjaman.getByNik')->can('slipgaji.index');
    });

    Route::controller(SlipgajiController::class)->group(function () {
        Route::get('/slipgaji', 'index')->name('slipgaji.index')->can('slipgaji.index');
        Route::get('/slipgaji/create', 'create')->name('slipgaji.create')->can('slipgaji.create');
        Route::post('/slipgaji/store', 'store')->name('slipgaji.store')->can('slipgaji.create');
        Route::get('/slipgaji/{kode_slip}/show', 'show')->name('slipgaji.show')->can('slipgaji.index');
        Route::get('/slipgaji/{kode_slip}/edit', 'edit')->name('slipgaji.edit')->can('slipgaji.edit');
        Route::put('/slipgaji/{kode_slip}/update', 'update')->name('slipgaji.update')->can('slipgaji.edit');
        Route::delete('/slipgaji/{kode_slip}/delete', 'destroy')->name('slipgaji.delete')->can('slipgaji.delete');
        Route::get('/slipgaji/{nik}/{bulan}/{tahun}/cetakslip', 'cetakslipgaji')->name('slipgaji.cetakslip')->can('slipgaji.index');
        Route::get('/slipgaji/{nik}/{bulan}/{tahun}/cetak-public', 'cetakslipgajiPublic')->name('slipgaji.cetakslipPublic');
        Route::post('/slipgaji/send-email', 'sendSlipGajiEmail')->name('slipgaji.sendEmail')->can('slipgaji.index');
        Route::post('/slipgaji/send-email-single', 'sendSlipGajiEmailSingle')->name('slipgaji.sendEmailSingle')->can('slipgaji.index');
        Route::get('/slipgaji/select-karyawan', 'selectKaryawan')->name('slipgaji.selectKaryawan')->can('slipgaji.index');
        Route::post('/slipgaji/send-email-selected', 'sendSlipGajiEmailSelected')->name('slipgaji.sendEmailSelected')->can('slipgaji.index');
    });

    Route::controller(HariliburController::class)->group(function () {
        Route::get('/harilibur', 'index')->name('harilibur.index')->can('harilibur.index');
        Route::get('/harilibur/create', 'create')->name('harilibur.create')->can('harilibur.create');
        Route::post('/harilibur', 'store')->name('harilibur.store')->can('harilibur.create');
        Route::get('/harilibur/{kode_libur}/edit', 'edit')->name('harilibur.edit')->can('harilibur.edit');
        Route::put('/harilibur/{kode_libur}', 'update')->name('harilibur.update')->can('harilibur.edit');
        Route::delete('/harilibur/{kode_libur}/delete', 'destroy')->name('harilibur.delete')->can('harilibur.delete');
        Route::get('/harilibur/{kode_libur}/aturharilibur', 'aturharilibur')->name('harilibur.aturharilibur')->can('harilibur.setharilibur');
        Route::get('/harilibur/{kode_libur}/getkaryawanlibur', 'getkaryawanlibur')->name('harilibur.getkaryawanlibur');
        Route::get('/harilibur/{kode_libur}/aturkaryawan', 'aturkaryawan')->name('harilibur.aturkaryawan');
        Route::post('/harilibur/getkaryawan', 'getkaryawan')->name('harilibur.getkaryawan');
        Route::post('/harilibur/updateliburkaryawan', 'updateliburkaryawan')->name('harilibur.updateliburkaryawan');
        Route::post('/harilibur/deletekaryawanlibur', 'deletekaryawanlibur')->name('harilibur.deletekaryawanlibur');
        Route::post('/harilibur/tambahkansemua', 'tambahkansemua')->name('harilibur.tambahkansemua');
        Route::post('/harilibur/batalkansemua', 'batalkansemua')->name('harilibur.batalkansemua');
    });

    Route::controller(PresensiController::class)->group(function () {
        Route::get('/presensi', 'index')->name('presensi.index')->can('presensi.index');
        Route::get('/presensi/histori', 'histori')->name('presensi.histori')->can('presensi.index');
        Route::get('/presensi/create', 'create')->name('presensi.create')->can('presensi.create');
        Route::post('/presensi', 'store')->name('presensi.store')->can('presensi.create');
        Route::post('/presensi/edit', 'edit')->name('presensi.edit')->can('presensi.edit');
        Route::post('/presensi/update', 'update')->name('presensi.update')->can('presensi.edit');
        Route::delete('/presensi/{id}/delete', 'destroy')->name('presensi.delete')->can('presensi.delete');
        Route::get('/presensi/{id}/{status}/show', 'show')->name('presensi.show');

        Route::post('/presensi/getdatamesin', 'getdatamesin')->name('presensi.getdatamesin');
        Route::post('/presensi/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')->name('presensi.updatefrommachine');
    });

    // Yayasan Presensi Routes
    Route::controller(YayasanPresensiController::class)->group(function () {
        Route::get('/yayasan-presensi', 'index')->name('yayasan-presensi.index')->can('yayasan_masar.index');
        Route::get('/yayasan-presensi/histori', 'histori')->name('yayasan-presensi.histori')->can('yayasan_masar.index');
        Route::get('/yayasan-presensi/create', 'create')->name('yayasan-presensi.create')->can('yayasan_masar.index');
        Route::post('/yayasan-presensi', 'store')->name('yayasan-presensi.store')->can('yayasan_masar.index');
        Route::post('/yayasan-presensi/edit', 'edit')->name('yayasan-presensi.edit')->can('yayasan_masar.index');
        Route::post('/yayasan-presensi/update', 'update')->name('yayasan-presensi.update')->can('yayasan_masar.index');
        Route::delete('/yayasan-presensi/{id}/delete', 'destroy')->name('yayasan-presensi.delete')->can('yayasan_masar.index');
        Route::get('/yayasan-presensi/{id}/{status}/show', 'show')->name('yayasan-presensi.show');

        Route::post('/yayasan-presensi/getdatamesin', 'getdatamesin')->name('yayasan-presensi.getdatamesin');
        Route::post('/yayasan-presensi/getdatamesinall', 'getdatamesinall')->name('yayasan-presensi.getdatamesinall');
        Route::post('/yayasan-presensi/submitallmasuk', 'submitallmasuk')->name('yayasan-presensi.submitallmasuk');
        Route::post('/yayasan-presensi/submitallpulang', 'submitallpulang')->name('yayasan-presensi.submitallpulang');
        Route::post('/yayasan-presensi/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')->name('yayasan-presensi.updatefrommachine');
    });

    Route::controller(JamkerjabydeptController::class)->group(function () {
        Route::get('/jamkerjabydept', 'index')->name('jamkerjabydept.index')->can('jamkerjabydept.index');
        Route::get('/jamkerjabydept/create', 'create')->name('jamkerjabydept.create')->can('jamkerjabydept.create');
        Route::post('/jamkerjabydept', 'store')->name('jamkerjabydept.store')->can('jamkerjabydept.create');
        Route::get('/jamkerjabydept/{kode_jk_dept}/edit', 'edit')->name('jamkerjabydept.edit')->can('jamkerjabydept.edit');
        Route::put('/jamkerjabydept/{kode_jk_dept}', 'update')->name('jamkerjabydept.update')->can('jamkerjabydept.edit');
        Route::delete('/jamkerjabydept/{kode_jk_dept}/delete', 'destroy')->name('jamkerjabydept.delete')->can('jamkerjabydept.delete');
    });

    Route::controller(IzinabsenController::class)->group(function () {
        Route::get('/izinabsen', 'index')->name('izinabsen.index')->can('izinabsen.index');
        Route::get('/izinabsen/create', 'create')->name('izinabsen.create')->can('izinabsen.create');
        Route::post('/izinabsen', 'store')->name('izinabsen.store')->can('izinabsen.create');
        Route::get('/izinabsen/{kode_izin}/approve', 'approve')->name('izinabsen.approve')->can('izinabsen.approve');
        Route::delete('/izinabsen/{kode_izin}/cancelapprove', 'cancelapprove')->name('izinabsen.cancelapprove')->can('izinabsen.approve');
        Route::post('/izinabsen/{kode_izin}/storeapprove', 'storeapprove')->name('izinabsen.storeapprove')->can('izinabsen.approve');
        Route::get('/izinabsen/{id}/edit', 'edit')->name('izinabsen.edit')->can('izinabsen.edit');
        Route::put('/izinabsen/{id}', 'update')->name('izinabsen.update')->can('izinabsen.edit');
        Route::get('/izinabsen/{kode_izin}/show', 'show')->name('izinabsen.show')->can('izinabsen.index');
        Route::delete('/izinabsen/{id}/delete', 'destroy')->name('izinabsen.delete')->can('izinabsen.delete');
    });

    Route::controller(IzinsakitController::class)->group(function () {
        Route::get('/izinsakit', 'index')->name('izinsakit.index')->can('izinsakit.index');
        Route::get('/izinsakit/create', 'create')->name('izinsakit.create')->can('izinsakit.create');
        Route::post('/izinsakit', 'store')->name('izinsakit.store')->can('izinsakit.create');
        Route::get('/izinsakit/{kode_izin_sakit}/edit', 'edit')->name('izinsakit.edit')->can('izinsakit.edit');
        Route::put('/izinsakit/{kode_izin_sakit}', 'update')->name('izinsakit.update')->can('izinsakit.edit');
        Route::get('/izinsakit/{kode_izin_sakit}/show', 'show')->name('izinsakit.show')->can('izinsakit.index');
        Route::delete('/izinsakit/{kode_izin_sakit}/delete', 'destroy')->name('izinsakit.delete')->can('izinsakit.delete');

        Route::get('/izinsakit/{kode_izin_sakit}/approve', 'approve')->name('izinsakit.approve')->can('izinsakit.approve');
        Route::delete('/izinsakit/{kode_izin_sakit}/cancelapprove', 'cancelapprove')->name('izinsakit.cancelapprove')->can('izinsakit.approve');
        Route::post('/izinsakit/{kode_izin_sakit}/storeapprove', 'storeapprove')->name('izinsakit.storeapprove')->can('izinsakit.approve');
    });


    Route::controller(IzincutiController::class)->group(function () {
        Route::get('/izincuti', 'index')->name('izincuti.index')->can('izincuti.index');
        Route::get('/izincuti/create', 'create')->name('izincuti.create')->can('izincuti.create');
        Route::post('/izincuti', 'store')->name('izincuti.store')->can('izincuti.create');
        Route::get('/izincuti/{kode_izin_cuti}/edit', 'edit')->name('izincuti.edit')->can('izincuti.edit');
        Route::put('/izincuti/{kode_izin_cuti}', 'update')->name('izincuti.update')->can('izincuti.edit');
        Route::get('/izincuti/{kode_izin_cuti}/show', 'show')->name('izincuti.show')->can('izincuti.index');
        Route::delete('/izincuti/{kode_izin_cuti}/delete', 'destroy')->name('izincuti.delete')->can('izincuti.delete');

        Route::get('/izincuti/{kode_izin_cuti}/approve', 'approve')->name('izincuti.approve')->can('izincuti.approve');
        Route::delete('/izincuti/{kode_izin_cuti}/cancelapprove', 'cancelapprove')->name('izincuti.cancelapprove')->can('izincuti.approve');
        Route::post('/izincuti/{kode_izin_cuti}/storeapprove', 'storeapprove')->name('izincuti.storeapprove')->can('izincuti.approve');
        Route::get('/izincuti/getsisaharicuti', 'getsisaharicuti')->name('izincuti.getsisaharicuti');
    });

    Route::controller(IzindinasController::class)->group(function () {
        Route::get('/izindinas', 'index')->name('izindinas.index')->can('izindinas.index');
        Route::get('/izindinas/create', 'create')->name('izindinas.create')->can('izindinas.create');
        Route::post('/izindinas', 'store')->name('izindinas.store')->can('izindinas.create');
        Route::get('/izindinas/{kode_izin_cuti}/edit', 'edit')->name('izindinas.edit')->can('izindinas.edit');
        Route::put('/izindinas/{kode_izin_cuti}', 'update')->name('izindinas.update')->can('izindinas.edit');
        Route::get('/izindinas/{kode_izin_cuti}/show', 'show')->name('izindinas.show')->can('izindinas.index');
        Route::delete('/izindinas/{kode_izin_cuti}/delete', 'destroy')->name('izindinas.delete')->can('izindinas.delete');

        Route::get('/izindinas/{kode_izin_cuti}/approve', 'approve')->name('izindinas.approve')->can('izindinas.approve');
        Route::delete('/izindinas/{kode_izin_cuti}/cancelapprove', 'cancelapprove')->name('izindinas.cancelapprove')->can('izindinas.approve');
        Route::post('/izindinas/{kode_izin_cuti}/storeapprove', 'storeapprove')->name('izindinas.storeapprove')->can('izindinas.approve');
    });

    Route::controller(LemburController::class)->group(function () {
        Route::get('/lembur', 'index')->name('lembur.index')->can('lembur.index');
        Route::get('/lembur/create', 'create')->name('lembur.create')->can('lembur.create');
        Route::post('/lembur', 'store')->name('lembur.store')->can('lembur.create');
        Route::get('/lembur/{id}/edit', 'edit')->name('lembur.edit')->can('lembur.edit');
        Route::put('/lembur/{id}', 'update')->name('lembur.update')->can('lembur.edit');
        Route::delete('/lembur/{id}/delete', 'destroy')->name('lembur.delete')->can('lembur.delete');
        Route::get('/lembur/{id}/approve', 'approve')->name('lembur.approve')->can('lembur.approve');
        Route::get('/lembur/{id}/show', 'show')->name('lembur.show')->can('lembur.index');
        Route::delete('/lembur/{id}/cancelapprove', 'cancelapprove')->name('lembur.cancelapprove')->can('lembur.approve');
        Route::post('/lembur/{id}/storeapprove', 'storeapprove')->name('lembur.storeapprove')->can('lembur.approve');
        Route::get('/lembur/{id}/createpresensi', 'createpresensi')->name('lembur.createpresensi');
        Route::post('/lembur/storepresensi', 'storepresensi')->name('lembur.storepresensi');
    });

    Route::controller(PengajuanizinController::class)->group(function () {
        Route::get('/pengajuanizin', 'index')->name('pengajuanizin.index');
    });

    // Route untuk pengajuan tugas luar dari mobile
    Route::prefix('tugasluar')->controller(App\Http\Controllers\TugasLuarMobileController::class)->group(function () {
        Route::get('/create', 'create')->name('tugasluar.create');
        Route::post('/store', 'store')->name('tugasluar.store');
        Route::post('/kembali/{kode}', 'kembali')->name('tugasluar.kembali');
    });

    Route::controller(PresensiistirahatController::class)->group(function () {
        Route::get('/presensiistirahat/create', 'create')->name('presensiistirahat.create');
        Route::post('/presensiistirahat', 'store')->name('presensiistirahat.store');
    });


    Route::controller(GeneralsettingController::class)->group(function () {
        Route::get('/generalsetting', 'index')->name('generalsetting.index')->can('generalsetting.index');
        Route::put('/generalsetting/{id}', 'update')->name('generalsetting.update')->can('generalsetting.edit');
    });

    // PWA Icon Generator Routes
    Route::controller(IconGeneratorController::class)->group(function () {
        Route::post('/generate-pwa-icons', 'generate')->name('pwa.generate-icons');
        Route::get('/preview-pwa-icons', 'preview')->name('pwa.preview-icons');
        Route::delete('/clear-pwa-icons', 'clear')->name('pwa.clear-icons');
    });

    Route::controller(DendaController::class)->group(function () {
        Route::get('/denda', 'index')->name('denda.index')->can('generalsetting.index');
        Route::get('/denda/create', 'create')->name('denda.create')->can('generalsetting.index');
        Route::post('/denda', 'store')->name('denda.store')->can('generalsetting.index');
        Route::get('/denda/{id}/edit', 'edit')->name('denda.edit')->can('generalsetting.index');
        Route::put('/denda/{id}', 'update')->name('denda.update')->can('generalsetting.index');
        Route::delete('/denda/{id}/delete', 'destroy')->name('denda.delete')->can('generalsetting.index');
    });

    Route::controller(LaporanController::class)->group(function () {
        Route::get('/laporan/presensi', 'presensi')->name('laporan.presensi')->can('laporan.presensi');
        Route::post('/laporan/cetakpresensi', 'cetakpresensi')->name('laporan.cetakpresensi')->can('laporan.presensi');
        Route::get('/laporan/cetakslipgaji', 'cetakpresensi');
    });

    Route::controller(YayasanLaporanController::class)->group(function () {
        Route::get('/laporan/presensi-yayasan', 'laporanPresensi')->name('yayasan-laporan.presensi')->can('yayasan_masar.index');
        Route::post('/laporan/cetakpresensi-yayasan', 'cetakPresensiyayasan')->name('yayasan-laporan.cetakpresensi')->can('yayasan_masar.index');
    });

    Route::controller(FacerecognitionController::class)->group(function () {
        Route::post('/facerecognition/hapus-semua/{nik}', 'destroyAll')->name('facerecognition.destroyAll')->can('karyawan.edit');
        Route::get('/facerecognition/{nik}/create', 'create')->name('facerecognition.create');
        Route::post('/facerecognition/store', 'store')->name('facerecognition.store');
        Route::delete('/facerecognition/{id}/delete', 'destroy')->name('facerecognition.delete');

        Route::get('/facerecognition/getwajah', 'getWajah')->name('facerecognition.getwajah');
    });

    Route::middleware('role:super admin')->controller(WagatewayController::class)->group(function () {
        Route::get('/wagateway', 'index')->name('wagateway.index');
        Route::get('/wagateway/messages', 'messages')->name('wagateway.messages');
        Route::post('/wagateway/add-device', 'addDevice')->name('wagateway.add-device');
        Route::post('/wagateway/toggle-device-status/{id}', 'toggleDeviceStatus')->name('wagateway.toggle-device-status');
        Route::post('/wagateway/generate-qr', 'generateQR')->name('wagateway.generate-qr');
        Route::post('/wagateway/check-device-status', 'checkDeviceStatus')->name('wagateway.check-device-status');
        Route::post('/wagateway/test-send-message', 'testSendMessage')->name('wagateway.test-send-message');
        Route::post('/wagateway/disconnect-device', 'disconnectDevice')->name('wagateway.disconnect-device');
        Route::post('/wagateway/fetch-groups', 'fetchGroups')->name('wagateway.fetch-groups');
        Route::delete('/wagateway/delete-device/{id}', 'deleteDevice')->name('wagateway.delete-device');
    });

    // ============ MANAJEMEN ADMINISTRASI ROUTES (KARYAWAN) ============
    // PENTING: Routes ini HARUS di luar middleware super admin dan SEBELUM routes admin
    // agar tidak tertangkap oleh route resource administrasi yang menganggap 'karyawan' sebagai ID
    Route::middleware('auth')->prefix('administrasi/karyawan')->name('administrasi.karyawan.')->group(function () {
        Route::get('/', [AdministrasiController::class, 'indexKaryawan'])->name('index');
        Route::get('/create', [AdministrasiController::class, 'createKaryawan'])->name('create');
        Route::post('/', [AdministrasiController::class, 'storeKaryawan'])->name('store');
        Route::get('/{id}', [AdministrasiController::class, 'showKaryawan'])->name('show');
        Route::get('/{id}/tindak-lanjut/create', [AdministrasiController::class, 'createTindakLanjutKaryawan'])->name('tindak-lanjut.create');
        Route::post('/{id}/tindak-lanjut', [AdministrasiController::class, 'storeTindakLanjutKaryawan'])->name('tindak-lanjut.store');
        Route::get('/{administrasi}/download', [AdministrasiController::class, 'downloadDokumen'])->name('download');
        Route::get('/{administrasi}/export-pdf', [AdministrasiController::class, 'exportPdf'])->name('export-pdf');
    });

    // Fasilitas & Asset Routes
    Route::middleware('role:super admin')->group(function () {
        // Gedung Routes
        Route::controller(GedungController::class)->group(function () {
            Route::get('/gedung', 'index')->name('gedung.index');
            Route::get('/gedung/create', 'create')->name('gedung.create');
            Route::post('/gedung', 'store')->name('gedung.store');
            Route::get('/gedung/{id}/edit', 'edit')->name('gedung.edit');
            Route::put('/gedung/{id}/update', 'update')->name('gedung.update');
            Route::delete('/gedung/{id}/delete', 'destroy')->name('gedung.delete');
            Route::get('/gedung/{id}/show', 'show')->name('gedung.show');
            Route::get('/gedung/export-pdf', 'exportPDF')->name('gedung.exportPDF');
        });

        // Ruangan Routes
        Route::controller(RuanganController::class)->group(function () {
            Route::get('/gedung/{gedung_id}/ruangan', 'index')->name('ruangan.index');
            Route::get('/gedung/{gedung_id}/ruangan/create', 'create')->name('ruangan.create');
            Route::post('/gedung/{gedung_id}/ruangan', 'store')->name('ruangan.store');
            Route::get('/gedung/{gedung_id}/ruangan/{id}/edit', 'edit')->name('ruangan.edit');
            Route::put('/gedung/{gedung_id}/ruangan/{id}/update', 'update')->name('ruangan.update');
            Route::delete('/gedung/{gedung_id}/ruangan/{id}/delete', 'destroy')->name('ruangan.delete');
        });

        // Barang Routes
        Route::controller(BarangController::class)->group(function () {
            Route::get('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang', 'index')->name('barang.index');
            Route::get('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/create', 'create')->name('barang.create');
            Route::post('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang', 'store')->name('barang.store');
            Route::get('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/edit', 'edit')->name('barang.edit');
            Route::put('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/update', 'update')->name('barang.update');
            Route::delete('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/delete', 'destroy')->name('barang.delete');
            Route::get('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/transfer', 'transfer')->name('barang.transfer');
            Route::post('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/proses-transfer', 'prosesTransfer')->name('barang.prosesTransfer');
            Route::get('/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/riwayat', 'riwayatTransfer')->name('barang.riwayat');
        });

        // Kendaraan Routes
        Route::controller(KendaraanController::class)->group(function () {
            Route::get('/kendaraan', 'index')->name('kendaraan.index');
            Route::get('/kendaraan/create', 'create')->name('kendaraan.create');
            Route::post('/kendaraan', 'store')->name('kendaraan.store');
            Route::get('/kendaraan/{id}/edit', 'edit')->name('kendaraan.edit');
            Route::put('/kendaraan/{id}/update', 'update')->name('kendaraan.update');
            Route::delete('/kendaraan/{id}/delete', 'destroy')->name('kendaraan.delete');
            Route::get('/kendaraan/{id}/show', 'show')->name('kendaraan.show');
            Route::get('/kendaraan/{id}/detail', 'show')->name('kendaraan.detail');
        });

        // Peminjaman Kendaraan Routes (Admin Only - Edit/Delete)
        Route::controller(PeminjamanKendaraanController::class)->group(function () {
            Route::get('/kendaraan/{kendaraan_id}/peminjaman', 'index')->name('kendaraan.peminjaman.index');
            Route::get('/peminjaman/{id}/edit', 'edit')->name('kendaraan.peminjaman.edit');
            Route::put('/peminjaman/{id}/update', 'update')->name('kendaraan.peminjaman.update');
            Route::delete('/peminjaman/{id}/delete', 'delete')->name('kendaraan.peminjaman.delete');
        });

        // Service Kendaraan Routes (Admin Only - Edit/Delete/Jadwal)
        Route::controller(ServiceKendaraanController::class)->group(function () {
            Route::get('/kendaraan/{kendaraan_id}/service', 'index')->name('service.index');
            Route::get('/service/{id}/edit', 'editService')->name('service.edit');
            Route::put('/service/{id}', 'updateService')->name('service.update');
            Route::delete('/service/{id}', 'deleteService')->name('service.delete');
            Route::get('/kendaraan/{kendaraan_id}/service/jadwal', 'jadwalService')->name('service.jadwal');
            Route::post('/kendaraan/{kendaraan_id}/service/jadwal', 'storeJadwal')->name('service.storeJadwal');
            Route::put('/service/jadwal/{id}', 'updateJadwal')->name('service.updateJadwal');
            Route::delete('/service/jadwal/{id}', 'deleteJadwal')->name('service.deleteJadwal');
        });

        // Pengunjung Routes
        Route::controller(PengunjungController::class)->group(function () {
            Route::get('/pengunjung', 'index')->name('pengunjung.index');
            Route::post('/pengunjung/checkin', 'checkin')->name('pengunjung.checkin');
            Route::post('/pengunjung/{id}/checkout', 'checkout')->name('pengunjung.checkout');
            Route::get('/pengunjung/{id}/show', 'show')->name('pengunjung.show');
            Route::get('/pengunjung/{id}/edit', 'edit')->name('pengunjung.edit');
            Route::put('/pengunjung/{id}/update', 'update')->name('pengunjung.update');
            Route::delete('/pengunjung/{id}/delete', 'destroy')->name('pengunjung.destroy');
            
            // QR Code
            Route::get('/pengunjung/qrcode', 'showQrCode')->name('pengunjung.qrcode');
            Route::get('/pengunjung/scan', 'scanQrCode')->name('pengunjung.scan');
            
            // Jadwal Pengunjung
            Route::get('/pengunjung/jadwal', 'jadwalIndex')->name('pengunjung.jadwal.index');
            Route::post('/pengunjung/jadwal', 'jadwalStore')->name('pengunjung.jadwal.store');
            Route::put('/pengunjung/jadwal/{id}', 'jadwalUpdate')->name('pengunjung.jadwal.update');
            Route::delete('/pengunjung/jadwal/{id}', 'jadwalDestroy')->name('pengunjung.jadwal.destroy');
            Route::get('/pengunjung/jadwal/{id}/checkin', 'jadwalCheckin')->name('pengunjung.jadwal.checkin');
            
            // Export PDF
            Route::get('/pengunjung/export-pdf', 'exportPDF')->name('pengunjung.exportPDF');
        });

        // ============ MANAJEMEN INVENTARIS ROUTES ============
        
        // Dashboard Manajemen Inventaris (Menu Utama)
        Route::get('manajemen-inventaris', [InventarisController::class, 'dashboard'])->name('manajemen-inventaris.dashboard');
        
        // Master Data Inventaris - Custom routes BEFORE resource
        Route::prefix('inventaris')->name('inventaris.')->group(function () {
            Route::get('/history', [InventarisController::class, 'history'])->name('history');
            Route::get('/history/{id}', [InventarisController::class, 'historyDetail'])->name('history-detail');
            Route::post('/import-barang', [InventarisController::class, 'importFromBarang'])->name('import-barang');
            Route::get('/barangs-for-import', [InventarisController::class, 'getBarangsForImport'])->name('barangs-import');
            Route::get('/export-pdf', [InventarisController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export-aktivitas-pdf', [InventarisController::class, 'exportAktivitasPdf'])->name('export-aktivitas-pdf');
            
            // Detail view with tabs (NEW)
            Route::get('/{id}/detail', [InventarisController::class, 'showDetail'])->name('detail');
        });
        
        Route::resource('inventaris', InventarisController::class);
        
        // Detail Units Management Routes (NEW)
        Route::prefix('inventaris/{inventaris}/units')->name('inventaris.units.')->group(function () {
            Route::get('/', [\App\Http\Controllers\InventarisDetailUnitController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\InventarisDetailUnitController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\InventarisDetailUnitController::class, 'store'])->name('store');
            Route::get('/{unit}/edit', [\App\Http\Controllers\InventarisDetailUnitController::class, 'edit'])->name('edit');
            Route::put('/{unit}', [\App\Http\Controllers\InventarisDetailUnitController::class, 'update'])->name('update');
            Route::delete('/{unit}', [\App\Http\Controllers\InventarisDetailUnitController::class, 'destroy'])->name('destroy');
            Route::get('/{unit}/history', [\App\Http\Controllers\InventarisDetailUnitController::class, 'showHistory'])->name('history');
            Route::get('/{unit}/history/pdf', [\App\Http\Controllers\InventarisDetailUnitController::class, 'historyPdf'])->name('history.pdf');
            Route::get('/{unit}/peminjaman', [\App\Http\Controllers\InventarisDetailUnitController::class, 'showPeminjamanForm'])->name('peminjaman');
            Route::post('/{unit}/kembalikan', [\App\Http\Controllers\InventarisDetailUnitController::class, 'kembalikanUnit'])->name('kembalikan');
            Route::get('/tersedia', [\App\Http\Controllers\InventarisDetailUnitController::class, 'getUnitsTersedia'])->name('tersedia');
            Route::post('/bulk-update-status', [\App\Http\Controllers\InventarisDetailUnitController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        });
        
        // Peminjaman Inventaris
        Route::resource('peminjaman-inventaris', PeminjamanInventarisController::class);
        Route::prefix('peminjaman-inventaris')->name('peminjaman-inventaris.')->group(function () {
            Route::post('/{peminjamanInventaris}/setujui', [PeminjamanInventarisController::class, 'setujui'])->name('setujui');
            Route::post('/{peminjamanInventaris}/tolak', [PeminjamanInventarisController::class, 'tolak'])->name('tolak');
            Route::get('/check-ketersediaan/{inventarisId}', [PeminjamanInventarisController::class, 'checkKetersediaan'])->name('check-ketersediaan');
            Route::get('/export-pdf', [PeminjamanInventarisController::class, 'exportPdf'])->name('export-pdf');
        });
        
        // Pengembalian Inventaris
        Route::prefix('pengembalian-inventaris')->name('pengembalian-inventaris.')->group(function () {
            Route::get('/', [PengembalianInventarisController::class, 'index'])->name('index');
            Route::get('/create', [PengembalianInventarisController::class, 'create'])->name('create'); // Support query string
            Route::post('/', [PengembalianInventarisController::class, 'store'])->name('store');
            Route::get('/select/peminjaman-aktif', [PengembalianInventarisController::class, 'getPeminjamanAktif'])->name('peminjaman-aktif');
            Route::get('/export-pdf', [PengembalianInventarisController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{pengembalianInventaris}', [PengembalianInventarisController::class, 'show'])->name('show');
        });
        
        // ============ PERALATAN BS (Bumi Sultan) ============
        
        // Master Data Peralatan
        Route::prefix('peralatan')->name('peralatan.')->group(function () {
            Route::get('/export-pdf', [PeralatanController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/laporan-stok', [PeralatanController::class, 'laporanStok'])->name('laporan-stok');
            Route::get('/export-laporan-stok', [PeralatanController::class, 'exportLaporanStok'])->name('export-laporan-stok');
            Route::get('/laporan-peminjaman', [PeralatanController::class, 'laporanPeminjaman'])->name('laporan-peminjaman');
            Route::get('/export-laporan-peminjaman', [PeralatanController::class, 'exportLaporanPeminjaman'])->name('export-laporan-peminjaman');
            Route::post('/{peralatan}/update-stok', [PeralatanController::class, 'updateStok'])->name('update-stok');
        });
        Route::resource('peralatan', PeralatanController::class);
        
        // Peminjaman Peralatan
        Route::prefix('peminjaman-peralatan')->name('peminjaman-peralatan.')->group(function () {
            Route::get('/export-pdf', [PeminjamanPeralatanController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{peminjamanPeralatan}/form-pengembalian', [PeminjamanPeralatanController::class, 'formPengembalian'])->name('form-pengembalian');
            Route::post('/{peminjamanPeralatan}/pengembalian', [PeminjamanPeralatanController::class, 'pengembalian'])->name('pengembalian');
            Route::get('/stok-tersedia/{peralatan}', [PeminjamanPeralatanController::class, 'getStokTersedia'])->name('stok-tersedia');
        });
        Route::resource('peminjaman-peralatan', PeminjamanPeralatanController::class);
        
        // ============ MANAJEMEN SAUNG SANTRI ============
        
        // Data Santri
        Route::prefix('santri')->name('santri.')->group(function () {
            Route::get('/export-pdf', [\App\Http\Controllers\SantriController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/download-formulir', [\App\Http\Controllers\SantriController::class, 'downloadFormulir'])->name('download-formulir');
            Route::get('/{santri}/cetak-qr', [\App\Http\Controllers\SantriController::class, 'cetakQr'])->name('cetak-qr');
        });
        Route::resource('santri', \App\Http\Controllers\SantriController::class);
        
        // Jadwal Santri & Absensi Santri
        Route::prefix('jadwal-santri')->name('jadwal-santri.')->group(function () {
            Route::get('/', [\App\Http\Controllers\JadwalSantriController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\JadwalSantriController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\JadwalSantriController::class, 'store'])->name('store');
            Route::get('/{jadwalSantri}', [\App\Http\Controllers\JadwalSantriController::class, 'show'])->name('show');
            Route::get('/{jadwalSantri}/edit', [\App\Http\Controllers\JadwalSantriController::class, 'edit'])->name('edit');
            Route::put('/{jadwalSantri}', [\App\Http\Controllers\JadwalSantriController::class, 'update'])->name('update');
            Route::delete('/{jadwalSantri}', [\App\Http\Controllers\JadwalSantriController::class, 'destroy'])->name('destroy');
        });

        // Absensi Santri
        Route::prefix('absensi-santri')->name('absensi-santri.')->group(function () {
            Route::get('/laporan', [\App\Http\Controllers\AbsensiSantriController::class, 'laporan'])->name('laporan');
            Route::get('/export-pdf', [\App\Http\Controllers\AbsensiSantriController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{jadwalId}/check-date', [\App\Http\Controllers\AbsensiSantriController::class, 'checkDate'])->name('check-date');
            Route::get('/{jadwalId}/create', [\App\Http\Controllers\AbsensiSantriController::class, 'create'])->name('create');
            Route::post('/{jadwalId}', [\App\Http\Controllers\AbsensiSantriController::class, 'store'])->name('store');
            Route::get('/{jadwalId}/show', [\App\Http\Controllers\AbsensiSantriController::class, 'show'])->name('show');
            Route::get('/{jadwalId}/edit/{tanggal}', [\App\Http\Controllers\AbsensiSantriController::class, 'edit'])->name('edit');
            Route::put('/{jadwalId}/update/{tanggal}', [\App\Http\Controllers\AbsensiSantriController::class, 'update'])->name('update');
            Route::put('/{id}/update-single', [\App\Http\Controllers\AbsensiSantriController::class, 'updateSingle'])->name('update-single');
            Route::delete('/{jadwalId}/delete/{tanggal}', [\App\Http\Controllers\AbsensiSantriController::class, 'destroyByDate'])->name('destroy-by-date');
            Route::delete('/{id}', [\App\Http\Controllers\AbsensiSantriController::class, 'destroy'])->name('destroy');
        });

        // Ijin Santri
        Route::prefix('ijin-santri')->name('ijin-santri.')->group(function () {
            Route::get('/', [\App\Http\Controllers\IjinSantriController::class, 'index'])->name('index');
            Route::get('/export-pdf', [\App\Http\Controllers\IjinSantriController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/create', [\App\Http\Controllers\IjinSantriController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\IjinSantriController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\IjinSantriController::class, 'show'])->name('show');
            Route::get('/{id}/download-pdf', [\App\Http\Controllers\IjinSantriController::class, 'downloadPdf'])->name('download-pdf');
            Route::post('/{id}/verifikasi-ttd-ustadz', [\App\Http\Controllers\IjinSantriController::class, 'verifikasiTtdUstadz'])->name('verifikasi-ttd-ustadz');
            Route::post('/{id}/verifikasi-kepulangan', [\App\Http\Controllers\IjinSantriController::class, 'verifikasiKepulangan'])->name('verifikasi-kepulangan');
            Route::post('/{id}/verifikasi-kembali', [\App\Http\Controllers\IjinSantriController::class, 'verifikasiKembali'])->name('verifikasi-kembali');
            Route::delete('/{id}', [\App\Http\Controllers\IjinSantriController::class, 'destroy'])->name('destroy');
        });
        
        // History Inventaris
        Route::prefix('history-inventaris')->name('history-inventaris.')->group(function () {
            Route::get('/', [HistoryInventarisController::class, 'index'])->name('index');
            Route::get('/dashboard', [HistoryInventarisController::class, 'dashboard'])->name('dashboard');
            Route::get('/{historyInventaris}/edit', [HistoryInventarisController::class, 'edit'])->name('edit');
            Route::put('/{historyInventaris}', [HistoryInventarisController::class, 'update'])->name('update');
            Route::delete('/{historyInventaris}', [HistoryInventarisController::class, 'destroy'])->name('destroy');
            Route::get('/{historyInventaris}', [HistoryInventarisController::class, 'show'])->name('show');
            Route::get('/inventaris/{inventaris}', [HistoryInventarisController::class, 'byInventaris'])->name('by-inventaris');
            Route::get('/karyawan/{karyawan}', [HistoryInventarisController::class, 'byKaryawan'])->name('by-karyawan');
            Route::get('/export/pdf', [HistoryInventarisController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export/excel', [HistoryInventarisController::class, 'exportExcel'])->name('export-excel');
        });

        // ============ MANAJEMEN ADMINISTRASI ROUTES (ADMIN) ============
        
        // Export All PDF (must be before resource route)
        Route::get('administrasi/export-all-pdf', [AdministrasiController::class, 'exportAllPdf'])->name('administrasi.exportAllPdf');
        
        // PENTING: Routes karyawan HARUS sebelum route resource
        // Karena route resource akan menangkap /administrasi/{id}
        // Note: Routes karyawan ada di luar middleware super admin (setelah penutup group)
        
        // Master Data Administrasi (Admin Only)
        Route::resource('administrasi', AdministrasiController::class);
        Route::prefix('administrasi')->name('administrasi.')->group(function () {
            Route::get('/{administrasi}/download', [AdministrasiController::class, 'downloadDokumen'])->name('download');
            Route::get('/{administrasi}/export-pdf', [AdministrasiController::class, 'exportPdf'])->name('export-pdf');
            
            // Tindak Lanjut Routes
            Route::get('/{administrasi}/tindak-lanjut/create', [AdministrasiController::class, 'showTindakLanjut'])->name('tindak-lanjut.create');
            Route::post('/{administrasi}/tindak-lanjut', [AdministrasiController::class, 'storeTindakLanjut'])->name('tindak-lanjut.store');
            Route::get('/{administrasi}/tindak-lanjut/{tindakLanjut}/edit', [AdministrasiController::class, 'editTindakLanjut'])->name('tindak-lanjut.edit');
            Route::put('/{administrasi}/tindak-lanjut/{tindakLanjut}', [AdministrasiController::class, 'updateTindakLanjut'])->name('tindak-lanjut.update');
            Route::delete('/{administrasi}/tindak-lanjut/{tindakLanjut}', [AdministrasiController::class, 'destroyTindakLanjut'])->name('tindak-lanjut.destroy');
        });
    }); // Penutup Middleware Super Admin untuk Fasilitas, Dokumen & Administrasi

    // ============ MANAJEMEN DOKUMEN ROUTES (ACCESSIBLE BY AUTHENTICATED USERS) ============
    // Routes ini bisa diakses oleh user yang terautentikasi
    // Kontrol Create/Edit/Delete ada di controller (hanya super admin)
    Route::middleware('auth')->group(function () {
        // Routes khusus yang harus sebelum resource
        Route::prefix('dokumen')->name('dokumen.')->group(function () {
            Route::get('/search-by-code', [DokumenController::class, 'searchByCode'])->name('search-by-code');
            Route::get('/by-loker/{nomorLoker}', [DokumenController::class, 'getByLoker'])->name('by-loker');
            Route::get('/export-pdf', [DokumenController::class, 'exportPdf'])->name('export-pdf');
        });
        
        // Resource routes - Create/Edit/Delete protected di controller
        Route::resource('dokumen', DokumenController::class);
        
        // Routes tambahan untuk download, preview
        Route::prefix('dokumen')->name('dokumen.')->group(function () {
            Route::get('/{id}/download', [DokumenController::class, 'download'])->name('download');
            Route::get('/{id}/preview', [DokumenController::class, 'preview'])->name('preview');
        });
    });

    // ============ PUSAT INFORMASI ROUTES (ADMIN ONLY) ============
    Route::middleware(['auth', 'role:super admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('informasi', \App\Http\Controllers\Admin\InformasiController::class);
        Route::patch('informasi/{informasi}/toggle-active', [\App\Http\Controllers\Admin\InformasiController::class, 'toggleActive'])->name('informasi.toggleActive');
    });

    // ============ KPI CREW ROUTES (SUPER ADMIN ONLY) ============
    Route::middleware(['auth', 'role:super admin'])->prefix('kpicrew')->name('kpicrew.')->controller(KpiCrewController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{nik}', 'show')->name('show');
        Route::post('/recalculate', 'recalculate')->name('recalculate');
    });

    // ============ YAYASAN MASAR ROUTES (DATA MASTER) ============
    Route::controller(YayasanMasarController::class)->group(function () {
        Route::get('/yayasan-masar', 'index')->name('yayasan_masar.index')->can('yayasan_masar.index');
        Route::get('/yayasan-masar/create', 'create')->name('yayasan_masar.create')->can('yayasan_masar.create');
        Route::post('/yayasan-masar', 'store')->name('yayasan_masar.store')->can('yayasan_masar.create');
        Route::get('/yayasan-masar/{kode_yayasan}/edit', 'edit')->name('yayasan_masar.edit')->can('yayasan_masar.edit');
        Route::put('/yayasan-masar/{kode_yayasan}', 'update')->name('yayasan_masar.update')->can('yayasan_masar.edit');
        Route::delete('/yayasan-masar/{kode_yayasan}', 'destroy')->name('yayasan_masar.destroy')->can('yayasan_masar.delete');
        Route::get('/yayasan-masar/{kode_yayasan}/show', 'show')->name('yayasan_masar.show')->can('yayasan_masar.show');
        Route::get('/yayasan-masar/{kode_yayasan}/setjamkerja', 'setjamkerja')->name('yayasan_masar.setjamkerja')->can('yayasan_masar.setjamkerja');
        Route::post('/yayasan-masar/{kode_yayasan}/storejamkerjabyday', 'storejamkerjabyday')->name('yayasan_masar.storejamkerjabyday')->can('yayasan_masar.setjamkerja');
        Route::get('/yayasan-masar/{kode_yayasan}/setcabang', 'setcabang')->name('yayasan_masar.setcabang')->can('yayasan_masar.setcabang');
        Route::post('/yayasan-masar/{kode_yayasan}/storecabang', 'storecabang')->name('yayasan_masar.storecabang')->can('yayasan_masar.setcabang');
        Route::post('/yayasan-masar/storejamkerjabydate', 'storejamkerjabydate')->name('yayasan_masar.storejamkerjabydate')->can('yayasan_masar.setjamkerja');
        Route::post('/yayasan-masar/getjamkerjabydate', 'getjamkerjabydate')->name('yayasan_masar.getjamkerjabydate')->can('yayasan_masar.setjamkerja');
        Route::post('/yayasan-masar/deletejamkerjabydate', 'deletejamkerjabydate')->name('yayasan_masar.deletejamkerjabydate')->can('yayasan_masar.setjamkerja');
        Route::get('/yayasan-masar/{kode_yayasan}/createuser', 'createuser')->name('yayasan_masar.createuser')->can('users.create');
        Route::get('/yayasan-masar/{kode_yayasan}/deleteuser', 'deleteuser')->name('yayasan_masar.deleteuser')->can('users.create');
        Route::get('/yayasan-masar/{kode_yayasan}/lockunlocklocation', 'lockunlocklocation')->name('yayasan_masar.lockunlocklocation')->can('yayasan_masar.edit');
        Route::get('/yayasan-masar/{kode_yayasan}/lockunlockjamkerja', 'lockunlockjamkerja')->name('yayasan_masar.lockunlockjamkerja')->can('yayasan_masar.edit');
        Route::get('/yayasan-masar/{kode_yayasan}/idcard', 'idcard')->name('yayasan_masar.idcard');
        Route::get('/yayasan-masar/{kode_yayasan}/generate-idcard', 'generateIdCard')->name('yayasan_masar.generateIdCard');
        Route::get('/yayasan-masar/{id}/download-idcard', 'downloadIdCard')->name('yayasan_masar.downloadIdCard');
        Route::get('/yayasan-masar/getyayasan_masar', 'getyayasan_masar')->name('yayasan_masar.getyayasan_masar');
        Route::get('/yayasan-masar/download-template', 'downloadTemplate')->name('yayasan_masar.downloadTemplate')->can('yayasan_masar.index');
        Route::get('/yayasan-masar/import-form', 'importForm')->name('yayasan_masar.importForm')->can('yayasan_masar.create');
        Route::post('/yayasan-masar/import', 'importExcel')->name('yayasan_masar.importExcel')->can('yayasan_masar.create');
        Route::get('/yayasan-masar/export-excel', 'exportExcel')->name('yayasan_masar.exportExcel')->can('yayasan_masar.index');
    });

    // ============ INFORMASI API ROUTES (KARYAWAN) ============
    Route::middleware('auth')->prefix('api/informasi')->group(function () {
        Route::get('/unread', [\App\Http\Controllers\Api\InformasiApiController::class, 'getUnreadInformasi']);
        Route::get('/all', [\App\Http\Controllers\Api\InformasiApiController::class, 'getAllInformasi']);
        Route::post('/{id}/mark-read', [\App\Http\Controllers\Api\InformasiApiController::class, 'markAsRead']);
    });

    // Bersihkan Foto Routes
    Route::middleware('role:super admin')->controller(BersihkanfotoController::class)->group(function () {
        Route::get('/bersihkanfoto', 'index')->name('bersihkanfoto.index')->can('bersihkanfoto.index');
        Route::post('/bersihkanfoto', 'destroy')->name('bersihkanfoto.destroy')->can('bersihkanfoto.delete');
    });

    // Tracking Presensi Routes
    Route::middleware('role:super admin')->controller(TrackingPresensiController::class)->group(function () {
        Route::get('/trackingpresensi', 'index')->name('trackingpresensi.index');
        Route::get('/trackingpresensi/getData', 'getData')->name('trackingpresensi.getData');
    });

    // Aktivitas Karyawan Routes
    Route::controller(AktivitasKaryawanController::class)->group(function () {
        Route::get('/aktivitaskaryawan', 'index')->name('aktivitaskaryawan.index')->can('aktivitaskaryawan.index');
        Route::get('/aktivitaskaryawan/create', 'create')->name('aktivitaskaryawan.create')->can('aktivitaskaryawan.create');
        Route::post('/aktivitaskaryawan', 'store')->name('aktivitaskaryawan.store')->can('aktivitaskaryawan.create');
        Route::get('/aktivitaskaryawan/{aktivitaskaryawan}', 'show')->name('aktivitaskaryawan.show')->can('aktivitaskaryawan.index');
        Route::get('/aktivitaskaryawan/{aktivitaskaryawan}/edit', 'edit')->name('aktivitaskaryawan.edit')->can('aktivitaskaryawan.edit');
        Route::put('/aktivitaskaryawan/{aktivitaskaryawan}', 'update')->name('aktivitaskaryawan.update')->can('aktivitaskaryawan.edit');
        Route::delete('/aktivitaskaryawan/{aktivitaskaryawan}', 'destroy')->name('aktivitaskaryawan.destroy')->can('aktivitaskaryawan.delete');
        Route::get('/aktivitaskaryawan/export/pdf', 'exportPdf')->name('aktivitaskaryawan.export.pdf')->can('aktivitaskaryawan.index');
    });

    // Kunjungan Routes
    Route::controller(KunjunganController::class)->group(function () {
        Route::get('/kunjungan', 'index')->name('kunjungan.index')->can('kunjungan.index');
        Route::get('/kunjungan/create', 'create')->name('kunjungan.create')->can('kunjungan.create');
        Route::post('/kunjungan', 'store')->name('kunjungan.store')->can('kunjungan.create');
        Route::get('/kunjungan/{kunjungan}', 'show')->name('kunjungan.show')->can('kunjungan.index');
        Route::get('/kunjungan/{kunjungan}/edit', 'edit')->name('kunjungan.edit')->can('kunjungan.edit');
        Route::put('/kunjungan/{kunjungan}', 'update')->name('kunjungan.update')->can('kunjungan.edit');
        Route::delete('/kunjungan/{kunjungan}', 'destroy')->name('kunjungan.destroy')->can('kunjungan.delete');
        Route::get('/kunjungan/export/pdf', 'exportPdf')->name('kunjungan.export.pdf')->can('kunjungan.index');
    });

    // Tracking Kunjungan Routes
    Route::controller(TrackingKunjunganController::class)->group(function () {
        Route::get('/tracking-kunjungan', 'index')->name('tracking-kunjungan.index')->can('kunjungan.index');
    });

    // Fasilitas & Asset Routes untuk Karyawan (READ ONLY)
    Route::controller(GedungController::class)->group(function () {
        Route::get('/fasilitas/dashboard-karyawan', 'dashboardKaryawan')->name('fasilitas.dashboard.karyawan');
        Route::get('/fasilitas/gedung-karyawan', 'indexKaryawan')->name('gedung.karyawan');
    });

    Route::controller(RuanganController::class)->group(function () {
        Route::get('/fasilitas/gedung/{gedung_id}/ruangan-karyawan', 'indexKaryawan')->name('ruangan.karyawan');
    });

    Route::controller(BarangController::class)->group(function () {
        Route::get('/fasilitas/gedung/{gedung_id}/ruangan/{ruangan_id}/barang-karyawan', 'indexKaryawan')->name('barang.karyawan');
        Route::get('/fasilitas/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/transfer-karyawan', 'transferKaryawan')->name('barang.transferKaryawan');
        Route::post('/fasilitas/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/proses-transfer-karyawan', 'prosesTransferKaryawan')->name('barang.prosesTransferKaryawan');
        Route::get('/fasilitas/gedung/{gedung_id}/ruangan/{ruangan_id}/barang/{id}/riwayat-karyawan', 'riwayatTransferKaryawan')->name('barang.riwayatKaryawan');
    });

    // Manajemen Pengunjung untuk Karyawan (Check-in & Check-out only)
    Route::controller(PengunjungKaryawanController::class)->group(function () {
        Route::get('/fasilitas/pengunjung-karyawan', 'index')->name('pengunjung.karyawan.index');
        Route::post('/fasilitas/pengunjung-karyawan/checkin', 'checkin')->name('pengunjung.karyawan.checkin');
        Route::post('/fasilitas/pengunjung-karyawan/{id}/checkout', 'checkout')->name('pengunjung.karyawan.checkout');
        Route::get('/fasilitas/pengunjung-karyawan/{id}/show', 'show')->name('pengunjung.karyawan.show');
    });

    // Manajemen Dokumen untuk Karyawan (READ ONLY)
    Route::controller(DokumenController::class)->group(function () {
        Route::get('/fasilitas/dokumen-karyawan', 'indexKaryawan')->name('dokumen.karyawan.index');
        Route::get('/fasilitas/dokumen-karyawan/{id}/preview', 'previewKaryawan')->name('dokumen.karyawan.preview');
        Route::get('/fasilitas/dokumen-karyawan/{id}/show', 'showKaryawan')->name('dokumen.karyawan.show');
        Route::get('/fasilitas/dokumen-karyawan/{id}/download', 'downloadKaryawan')->name('dokumen.karyawan.download');
    });

    // Inventaris dan Peralatan untuk Karyawan (READ ONLY)
    Route::controller(InventarisPeralatanKaryawanController::class)->group(function () {
        Route::get('/fasilitas/inventaris-peralatan-karyawan', 'index')->name('inventaris-peralatan.karyawan.index');
        Route::get('/fasilitas/inventaris-peralatan-karyawan/inventaris/{id}', 'showInventaris')->name('inventaris-peralatan.karyawan.inventaris.show');
        Route::get('/fasilitas/inventaris-peralatan-karyawan/peralatan/{id}', 'showPeralatan')->name('inventaris-peralatan.karyawan.peralatan.show');
    });

    // Saung Santri untuk Karyawan (READ ONLY)
    Route::controller(\App\Http\Controllers\SantriController::class)->group(function () {
        Route::get('/saungsantri/dashboard-karyawan', 'dashboardKaryawan')->name('saungsantri.dashboard.karyawan');
        Route::get('/saungsantri/santri-karyawan', 'indexKaryawan')->name('santri.karyawan.index');
        Route::get('/saungsantri/santri-karyawan/{id}', 'showKaryawan')->name('santri.karyawan.show');
    });

    // Ijin Santri untuk Karyawan (READ ONLY)
    Route::prefix('ijin-santri-karyawan')->name('ijin-santri.karyawan.')->controller(\App\Http\Controllers\IjinSantriController::class)->group(function () {
        Route::get('/', 'indexKaryawan')->name('index');
        Route::get('/{id}', 'showKaryawan')->name('show');
        Route::get('/{id}/download-pdf', 'downloadPdfKaryawan')->name('download-pdf');
    });

    // Jadwal Santri untuk Karyawan (READ ONLY)
    Route::prefix('jadwal-santri-karyawan')->name('jadwal-santri.karyawan.')->controller(\App\Http\Controllers\JadwalSantriController::class)->group(function () {
        Route::get('/', 'indexKaryawan')->name('index');
        Route::get('/{jadwalSantri}', 'showKaryawan')->name('show');
    });

    // Absensi Santri untuk Karyawan (READ ONLY)
    Route::prefix('absensi-santri-karyawan')->name('absensi-santri.karyawan.')->controller(\App\Http\Controllers\AbsensiSantriController::class)->group(function () {
        Route::get('/laporan', 'laporanKaryawan')->name('laporan');
        Route::get('/{jadwalId}/show', 'showKaryawan')->name('show');
        Route::get('/{jadwalId}/create', 'createKaryawan')->name('create');
        Route::post('/{jadwalId}', 'storeKaryawan')->name('store');
    });

    // Keuangan Santri untuk Karyawan (READ ONLY)
    Route::prefix('keuangan-santri-karyawan')->name('keuangan-santri.karyawan.')->controller(\App\Http\Controllers\KeuanganSantriController::class)->group(function () {
        Route::get('/', 'indexKaryawan')->name('index');
        Route::get('/{id}', 'showKaryawan')->name('show');
        Route::get('/{id}/download-resi', 'downloadResiKaryawan')->name('download-resi');
    });

    // Pelanggaran Santri untuk Karyawan (READ & INPUT)
    Route::prefix('pelanggaran-santri-karyawan')->name('pelanggaran-santri.karyawan.')->controller(PelanggaranSantriController::class)->group(function () {
        Route::get('/', 'indexKaryawan')->name('index');
        Route::post('/store', 'storeKaryawan')->name('store');
        Route::get('/{id}', 'showKaryawan')->name('show');
    });

    // Manajemen Tukang untuk Karyawan (READ ONLY)
    Route::prefix('manajemen-tukang-karyawan')->name('tukang.karyawan.')->controller(\App\Http\Controllers\TukangController::class)->group(function () {
        Route::get('/', 'indexKaryawan')->name('index');
        Route::get('/{id}', 'showKaryawan')->name('show');
    });

    // ========================================
    // ROUTES MANAJEMEN YAYASAN - KARYAWAN MODE
    // ========================================
    
    // Dashboard Manajemen Yayasan untuk Karyawan - Langsung ke Majlis Ta'lim
    Route::get('/manajemen-yayasan-karyawan', function () {
        return redirect()->route('majlistaklim.karyawan.index');
    })->name('manajemen-yayasan.karyawan.dashboard');

    // Majlis Ta'lim Al-Ikhlas untuk Karyawan
    Route::prefix('majlistaklim-karyawan')->name('majlistaklim.karyawan.')->group(function () {
        
        // Dashboard Majlis Ta'lim
        Route::get('/', function() {
            return view('majlistaklim.karyawan.index');
        })->name('index');
        
        // Jamaah Routes (VIEW ONLY)
        Route::controller(JamaahMajlisTaklimController::class)->group(function () {
            Route::get('/jamaah', 'indexKaryawan')->name('jamaah.index');
            Route::get('/jamaah/{id}', 'showKaryawan')->name('jamaah.show');
        });

        // Hadiah Routes (CAN INPUT)
        Route::controller(HadiahMajlisTaklimController::class)->group(function () {
            Route::get('/hadiah', 'indexKaryawan')->name('hadiah.index');
            Route::get('/hadiah/create', 'createKaryawan')->name('hadiah.create');
            Route::post('/hadiah', 'storeKaryawan')->name('hadiah.store');
        });

        // Distribusi Hadiah Routes (VIEW ONLY)
        Route::controller(HadiahMajlisTaklimController::class)->group(function () {
            Route::get('/distribusi', 'distribusiKaryawan')->name('distribusi.index');
            Route::post('/distribusi', 'storeDistribusiKaryawan')->name('distribusi.store');
            Route::get('/distribusi/{id}', 'showDistribusiKaryawan')->name('distribusi.show');
        });

        // Laporan Routes (VIEW ONLY)
        Route::controller(HadiahMajlisTaklimController::class)->group(function () {
            Route::get('/laporan', 'laporanIndexKaryawan')->name('laporan.index');
            Route::get('/laporan/stok-ukuran', 'laporanStokUkuranKaryawan')->name('laporan.stokUkuran');
            Route::get('/laporan/rekap-distribusi', 'laporanRekapDistribusiKaryawan')->name('laporan.rekapDistribusi');
        });
    });

    // MASAR untuk Karyawan
    Route::prefix('masar-karyawan')->name('masar.karyawan.')->group(function () {
        
        // Dashboard MASAR
        Route::get('/', function() {
            return view('masar.karyawan.index');
        })->name('index');
        
        // Jamaah Routes (VIEW ONLY)
        Route::controller(JamaahMasarController::class)->group(function () {
            Route::get('/jamaah', 'indexKaryawan')->name('jamaah.index');
            Route::get('/jamaah/{id}', 'showKaryawan')->name('jamaah.show');
        });

        // Hadiah Routes (CAN INPUT)
        Route::controller(HadiahMasarController::class)->group(function () {
            Route::get('/hadiah', 'indexKaryawan')->name('hadiah.index');
            Route::get('/hadiah/create', 'createKaryawan')->name('hadiah.create');
            Route::post('/hadiah', 'storeKaryawan')->name('hadiah.store');
        });

        // Distribusi Hadiah Routes (VIEW ONLY)
        Route::controller(DistribusiHadiahMasarController::class)->group(function () {
            Route::get('/distribusi', 'distribusiKaryawan')->name('distribusi.index');
            Route::post('/distribusi', 'storeDistribusiKaryawan')->name('distribusi.store');
            Route::get('/distribusi/{id}', 'showDistribusiKaryawan')->name('distribusi.show');
        });

        // Laporan Routes (VIEW ONLY)
        Route::controller(HadiahMasarController::class)->group(function () {
            Route::get('/laporan', 'laporanIndexKaryawan')->name('laporan.index');
            Route::get('/laporan/stok-ukuran', 'laporanStokUkuranKaryawan')->name('laporan.stokUkuran');
            Route::get('/laporan/rekap-distribusi', 'laporanRekapDistribusiKaryawan')->name('laporan.rekapDistribusi');
        });
    });

    // ========================================
    // END ROUTES MANAJEMEN YAYASAN - KARYAWAN MODE
    // ========================================

    // Keuangan Santri Routes
    Route::prefix('keuangan-santri')->controller(KeuanganSantriController::class)->group(function () {
        Route::get('/', 'index')->name('keuangan-santri.index');
        Route::get('/create', 'create')->name('keuangan-santri.create');
        Route::post('/', 'store')->name('keuangan-santri.store');
        Route::get('/{id}', 'show')->name('keuangan-santri.show');
        Route::get('/{id}/edit', 'edit')->name('keuangan-santri.edit');
        Route::put('/{id}', 'update')->name('keuangan-santri.update');
        Route::delete('/{id}', 'destroy')->name('keuangan-santri.destroy');
        
        // Laporan
        Route::get('/laporan/index', 'laporan')->name('keuangan-santri.laporan');
        Route::get('/laporan/export-pdf', 'exportPdf')->name('keuangan-santri.export.pdf');
        Route::get('/laporan/export-excel', 'exportExcel')->name('keuangan-santri.export.excel');
        
        // Import
        Route::get('/import/form', 'importForm')->name('keuangan-santri.import.form');
        Route::post('/import', 'import')->name('keuangan-santri.import');
        Route::get('/import/download-template', 'downloadTemplate')->name('keuangan-santri.download-template');
        
        // Verifikasi
        Route::post('/{id}/verify', 'verify')->name('keuangan-santri.verify');
        
        // API
        Route::get('/api/detect-category', 'detectCategory')->name('keuangan-santri.detect-category');
    });

    // Pelanggaran Santri Routes
    Route::prefix('pelanggaran-santri')->controller(PelanggaranSantriController::class)->group(function () {
        Route::get('/', 'index')->name('pelanggaran-santri.index');
        Route::get('/create', 'create')->name('pelanggaran-santri.create');
        Route::post('/', 'store')->name('pelanggaran-santri.store');
        Route::get('/{pelanggaranSantri}', 'show')->name('pelanggaran-santri.show');
        Route::get('/{pelanggaranSantri}/edit', 'edit')->name('pelanggaran-santri.edit');
        Route::put('/{pelanggaranSantri}', 'update')->name('pelanggaran-santri.update');
        Route::delete('/{pelanggaranSantri}', 'destroy')->name('pelanggaran-santri.destroy');
        
        // Laporan
        Route::get('/laporan/index', 'laporan')->name('pelanggaran-santri.laporan');
        Route::get('/laporan/export-pdf', 'exportPdf')->name('pelanggaran-santri.export.pdf');
        Route::get('/laporan/export-excel', 'exportExcel')->name('pelanggaran-santri.export.excel');
        
        // Surat Peringatan (untuk santri dengan pelanggaran >= 75)
        Route::get('/surat-peringatan/{userId}', 'suratPeringatan')->name('pelanggaran-santri.surat-peringatan');
        
        // API untuk get total pelanggaran
        Route::get('/api/total/{userId}', 'getTotalPelanggaran')->name('pelanggaran-santri.get-total');
    });

    // Khidmat Routes (Keuangan Belanja Masak Santri)
    Route::prefix('khidmat')->controller(KhidmatController::class)->group(function () {
        Route::get('/', 'index')->name('khidmat.index');
        Route::get('/search', 'search')->name('khidmat.search'); // AJAX Search
        Route::get('/create', 'create')->name('khidmat.create');
        Route::post('/', 'store')->name('khidmat.store');
        Route::get('/{id}', 'show')->name('khidmat.show');
        Route::get('/{id}/edit', 'edit')->name('khidmat.edit');
        Route::put('/{id}', 'update')->name('khidmat.update');
        Route::delete('/{id}', 'destroy')->name('khidmat.destroy');
        
        // Kebersihan
        Route::post('/{id}/kebersihan', 'updateKebersihan')->name('khidmat.update-kebersihan');
        
        // Status Selesai
        Route::post('/{id}/toggle-selesai', 'toggleSelesai')->name('khidmat.toggle-selesai');
        
        // Laporan Keuangan Belanja
        Route::get('/{id}/laporan', 'laporan')->name('khidmat.laporan');
        Route::post('/{id}/belanja', 'storeBelanja')->name('khidmat.store-belanja');
        
        // Template & Import Excel
        Route::get('/{id}/template', 'downloadTemplate')->name('khidmat.download-template');
        Route::post('/{id}/import', 'importBelanja')->name('khidmat.import-belanja');
        
        // Upload Foto Belanja
        Route::post('/{id}/foto', 'uploadFoto')->name('khidmat.upload-foto');
        Route::delete('/foto/{id}', 'deleteFoto')->name('khidmat.delete-foto');
        
        // Export PDF
        Route::get('/pdf/download-all', 'downloadPDF')->name('khidmat.download-pdf');
        Route::get('/pdf/download/{id}', 'downloadSinglePDF')->name('khidmat.download-single-pdf');
    });

    // Khidmat untuk Karyawan (READ ONLY)
    Route::prefix('khidmat-karyawan')->name('khidmat.karyawan.')->controller(KhidmatController::class)->group(function () {
        Route::get('/', 'indexKaryawan')->name('index');
        Route::get('/{id}', 'showKaryawan')->name('show');
    });

    // ========================================
    // ROUTES MANAJEMEN YAYASAN - MAJLIS TA'LIM AL-IKHLAS
    // ========================================
    Route::prefix('majlistaklim')->name('majlistaklim.')->group(function () {
        
        // Redirect to Jamaah (removed index page - navigation now available on all pages)
        Route::get('/', function() {
            return redirect()->route('majlistaklim.jamaah.index');
        })->name('index');
        
        // Jamaah Routes
        Route::controller(JamaahMajlisTaklimController::class)->group(function () {
            Route::get('/jamaah', 'index')->name('jamaah.index');
            Route::get('/jamaah/create', 'create')->name('jamaah.create');
            Route::post('/jamaah', 'store')->name('jamaah.store');
            Route::get('/jamaah/{id}', 'show')->name('jamaah.show');
            Route::get('/jamaah/{id}/edit', 'edit')->name('jamaah.edit');
            Route::put('/jamaah/{id}', 'update')->name('jamaah.update');
            Route::delete('/jamaah/{id}', 'destroy')->name('jamaah.destroy');
            
            // ID Card
            Route::get('/jamaah/{id}/id-card', 'downloadIdCard')->name('jamaah.downloadIdCard');
            
            // Toggle Umroh
            Route::post('/jamaah/{id}/toggle-umroh', 'toggleUmroh')->name('jamaah.toggleUmroh');
            
            // Import Export
            Route::post('/jamaah/import', 'import')->name('jamaah.import');
            Route::get('/jamaah/export/excel', 'export')->name('jamaah.export');
            Route::get('/jamaah/download/template', 'downloadTemplate')->name('jamaah.downloadTemplate');
            
            // Import Kehadiran
            Route::get('/jamaah/kehadiran/template', 'downloadTemplateKehadiran')->name('jamaah.kehadiran.template');
            Route::post('/jamaah/kehadiran/import', 'importKehadiran')->name('jamaah.kehadiran.import');
            
            // Fingerspot Cloud API Integration
            Route::post('/jamaah/getdatamesin', 'getdatamesin')->name('jamaah.getdatamesin');
            Route::post('/jamaah/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')->name('jamaah.updatefrommachine');
        });

        // Hadiah Routes
        Route::controller(HadiahMajlisTaklimController::class)->group(function () {
            Route::get('/hadiah', 'index')->name('hadiah.index');
            Route::get('/hadiah/create', 'create')->name('hadiah.create');
            Route::post('/hadiah', 'store')->name('hadiah.store');
            Route::get('/hadiah/{id}/edit', 'edit')->name('hadiah.edit');
            Route::put('/hadiah/{id}', 'update')->name('hadiah.update');
            Route::delete('/hadiah/{id}', 'destroy')->name('hadiah.destroy');
        });

        // Distribusi Hadiah Routes
        Route::controller(HadiahMajlisTaklimController::class)->group(function () {
            Route::get('/distribusi', 'distribusi')->name('distribusi.index');
            Route::post('/distribusi', 'storeDistribusi')->name('distribusi.store');
            Route::get('/distribusi/{id}', 'showDistribusi')->name('distribusi.show');
            Route::get('/distribusi/{id}/edit', 'editDistribusi')->name('distribusi.edit');
            Route::put('/distribusi/{id}', 'updateDistribusi')->name('distribusi.update');
            Route::delete('/distribusi/{id}', 'destroyDistribusi')->name('distribusi.destroy');
        });

        // Laporan Routes
        Route::controller(HadiahMajlisTaklimController::class)->group(function () {
            Route::get('/laporan/stok-ukuran', 'laporanStokUkuran')->name('laporan.stokUkuran');
            Route::get('/laporan/rekap-distribusi', 'laporanRekapDistribusi')->name('laporan.rekapDistribusi');
        });
    });

    // ========================================
    // ROUTES MANAJEMEN YAYASAN - MASAR (Majelis Saung Ar-Rohmah)
    // DISABLED: Sistem Jamaah Masar dihilangkan agar tidak bentur dengan Yayasan Masar (karyawan)
    // Hanya tersisa mode Karyawan (masar-karyawan) yang menggunakan tabel yayasan_masar
    // ========================================
    
    Route::prefix('masar')->name('masar.')->group(function () {
        
        // Redirect to Jamaah (removed index page - navigation now available on all pages)
        Route::get('/', function() {
            return redirect()->route('masar.jamaah.index');
        })->name('index');
        
        // Jamaah Routes
        Route::controller(JamaahMasarController::class)->group(function () {
            Route::get('/jamaah', 'index')->name('jamaah.index');
            Route::get('/jamaah/create', 'create')->name('jamaah.create');
            Route::post('/jamaah', 'store')->name('jamaah.store');
            Route::get('/jamaah/{id}', 'show')->name('jamaah.show');
            Route::get('/jamaah/{id}/edit', 'edit')->name('jamaah.edit');
            Route::put('/jamaah/{id}', 'update')->name('jamaah.update');
            Route::delete('/jamaah/{id}', 'destroy')->name('jamaah.destroy');
            
            // ID Card
            Route::get('/jamaah/{id}/id-card', 'downloadIdCard')->name('jamaah.downloadIdCard');
            
            // Toggle Umroh
            Route::post('/jamaah/{id}/toggle-umroh', 'toggleUmroh')->name('jamaah.toggleUmroh');
            
            // Import Export
            Route::post('/jamaah/import', 'import')->name('jamaah.import');
            Route::get('/jamaah/export/excel', 'export')->name('jamaah.export');
            Route::get('/jamaah/download/template', 'downloadTemplate')->name('jamaah.downloadTemplate');
            
            // Import Kehadiran
            Route::get('/jamaah/kehadiran/template', 'downloadTemplateKehadiran')->name('jamaah.kehadiran.template');
            Route::post('/jamaah/kehadiran/import', 'importKehadiran')->name('jamaah.kehadiran.import');
            
            // Fingerprint Integration - Get Data Mesin (Mirip Presensi Karyawan)
            Route::post('/jamaah/getdatamesin', 'getdatamesin')->name('jamaah.getdatamesin');
            Route::post('/jamaah/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')->name('jamaah.updatefrommachine');
        });

        // Hadiah Routes
        Route::controller(HadiahMasarController::class)->group(function () {
            Route::get('/hadiah', 'index')->name('hadiah.index');
            Route::get('/hadiah/create', 'create')->name('hadiah.create');
            Route::post('/hadiah', 'store')->name('hadiah.store');
            Route::get('/hadiah/{id}/edit', 'edit')->name('hadiah.edit');
            Route::put('/hadiah/{id}', 'update')->name('hadiah.update');
            Route::delete('/hadiah/{id}', 'destroy')->name('hadiah.destroy');
        });

        // Distribusi Hadiah Routes
        Route::controller(DistribusiHadiahMasarController::class)->group(function () {
            Route::get('/distribusi', 'index')->name('distribusi.index');
            Route::get('/distribusi/create', 'create')->name('distribusi.create');
            Route::post('/distribusi', 'store')->name('distribusi.store');
            Route::get('/distribusi/{id}', 'show')->name('distribusi.show');
            Route::get('/distribusi/{id}/edit', 'edit')->name('distribusi.edit');
            Route::put('/distribusi/{id}', 'update')->name('distribusi.update');
            Route::delete('/distribusi/{id}', 'destroy')->name('distribusi.destroy');
            Route::get('/distribusi/export/pdf', 'exportPDF')->name('distribusi.exportPDF');
            Route::get('/distribusi/statistik/get', 'getStatistik')->name('distribusi.statistik');
        });

        // Laporan Routes
        Route::controller(HadiahMasarController::class)->group(function () {
            Route::get('/laporan/stok-ukuran', 'laporanStokUkuran')->name('laporan.stokUkuran');
            Route::get('/laporan/rekap-distribusi', 'laporanRekapDistribusi')->name('laporan.rekapDistribusi');
        });
    });

    // ========================================
    // ROUTES MANAJEMEN TUKANG
    // ========================================
    Route::prefix('tukang')->name('tukang.')->group(function () {
        Route::controller(TukangController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('delete');
        });
    });
    
    // Routes Kehadiran Tukang
    Route::prefix('kehadiran-tukang')->name('kehadiran-tukang.')->group(function () {
        Route::controller(KehadiranTukangController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/toggle-status', 'toggleStatus')->name('toggle-status');
            Route::post('/toggle-lembur', 'toggleLembur')->name('toggle-lembur');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/rekap', 'rekap')->name('rekap');
            Route::get('/rekap/export-pdf', 'exportPdf')->name('export-pdf');
            Route::get('/detail/{tukang_id}', 'detail')->name('detail');
        });
    });
    
    // Routes Keuangan Tukang
    Route::prefix('keuangan-tukang')->name('keuangan-tukang.')->group(function () {
        Route::controller(KeuanganTukangController::class)->group(function () {
            // Dashboard & Overview
            Route::get('/', 'index')->name('index');
            Route::get('/detail/{tukang_id}', 'detail')->name('detail');
            
            // Lembur Cash (dipindahkan dari kehadiran-tukang)
            Route::get('/lembur-cash', 'lemburCash')->name('lembur-cash');
            Route::post('/lembur-cash/toggle', 'toggleLemburCash')->name('toggle-lembur-cash');
            
            // Pinjaman Tukang
            Route::get('/pinjaman', 'pinjaman')->name('pinjaman');
            Route::post('/pinjaman', 'storePinjaman')->name('pinjaman.store');
            Route::get('/pinjaman/download-formulir-kosong', 'downloadFormulirKosong')->name('pinjaman.download-formulir-kosong');
            Route::get('/pinjaman/{id}', 'detailPinjaman')->name('pinjaman.detail');
            Route::get('/pinjaman/{id}/download-formulir', 'downloadFormulirPinjaman')->name('pinjaman.download-formulir');
            Route::post('/pinjaman/{id}/bayar', 'bayarCicilan')->name('pinjaman.bayar');
            
            // Toggle Potongan Pinjaman
            Route::post('/toggle-potongan-pinjaman/{tukang_id}', 'togglePotonganPinjaman')->name('toggle-potongan-pinjaman');
            
            // Status Pembayaran (untuk dashboard)
            Route::get('/status-pembayaran/{tukang_id}', 'statusPembayaran')->name('status-pembayaran');
            Route::get('/download-slip/{tukang_id}', 'downloadSlipMingguIni')->name('download-slip');
            
            // Laporan Keuangan
            Route::get('/laporan', 'laporan')->name('laporan');
            Route::get('/laporan/export-pdf', 'exportPdf')->name('export-pdf');
            
            // Pembagian Gaji Kamis (TTD Digital)
            Route::get('/pembagian-gaji-kamis', 'pembagianGajiKamis')->name('pembagian-gaji-kamis');
            Route::get('/detail-gaji/{tukang_id}', 'detailGajiTukang')->name('detail-gaji');
            Route::post('/simpan-pembayaran-gaji', 'simpanPembayaranGaji')->name('simpan-pembayaran-gaji');
            Route::get('/download-slip-gaji/{id}', 'downloadSlipGaji')->name('download-slip-gaji');
            Route::get('/download-laporan-gaji-kamis', 'downloadLaporanGajiKamis')->name('download-laporan-gaji-kamis');
        });
    });
    
    // DEPRECATED: Route lama cash-lembur redirect ke keuangan-tukang
    Route::prefix('cash-lembur')->name('cash-lembur.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('keuangan-tukang.lembur-cash');
        })->name('index');
        Route::post('/toggle', function () {
            return redirect()->route('keuangan-tukang.toggle-lembur-cash');
        })->name('toggle');
    });

    // Routes Transaksi Keuangan (General)
    Route::prefix('transaksi-keuangan')->name('transaksi-keuangan.')->group(function () {
        Route::controller(TransaksiKeuanganController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export-pdf', 'exportPdf')->name('export-pdf');
        });
    });
}); // END Route::middleware('auth')->group

Route::get('/createrolepermission', function () {

    try {
        Role::create(['name' => 'super admin']);
        // Permission::create(['name' => 'view-karyawan']);
        // Permission::create(['name' => 'view-departemen']);
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});

// ===================================
// DANA OPERASIONAL HARIAN
// ===================================
Route::middleware('role:super admin')->prefix('dana-operasional')->name('dana-operasional.')->controller(DanaOperasionalController::class)->group(function () {
    // Dashboard
    Route::get('/', 'index')->name('index');
    
    // Laporan
    Route::get('/laporan/harian/{tanggal?}', 'laporanHarian')->name('laporan-harian');
    
    // Import/Export Excel
    Route::get('/export-excel', 'exportExcel')->name('export-excel');
    Route::get('/export-pdf', 'exportPdf')->name('export-pdf');
    Route::post('/send-email', 'sendEmail')->name('send-email');
    Route::get('/download-template', 'downloadTemplate')->name('download-template');
    Route::post('/import-excel', 'importExcel')->name('import-excel');
    Route::post('/import-excel-preview', 'importExcelPreview')->name('import-excel-preview'); // Bank-grade import with preview
    
    // CRUD Transaksi
    Route::post('/store', 'store')->name('store');
    Route::post('/create', 'create')->name('create');
    Route::get('/{id}/detail', 'detail')->name('detail');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::post('/{id}/update', 'update')->name('update');
    Route::post('/{id}/update-kategori', 'updateKategori')->name('update-kategori'); // Quick update kategori
    Route::delete('/{id}', 'destroy')->name('destroy'); // Direct delete
    Route::post('/{id}/upload-foto', 'uploadFoto')->name('upload-foto');
    Route::get('/{id}/download-resi', 'downloadResi')->name('download-resi');
    
    // CRUD Saldo Awal
    Route::put('/saldo-awal/{id}', 'updateSaldoAwal')->name('update-saldo-awal');
    Route::delete('/saldo-awal/{id}/delete', 'destroySaldoAwal')->name('delete-saldo-awal');
});

// ===================================
// LAPORAN KEUANGAN PROFESIONAL (Annual Report)
// ===================================
Route::middleware('role:super admin')->prefix('laporan-keuangan')->name('laporan-keuangan.')->group(function () {
    Route::get('/', [\App\Http\Controllers\LaporanKeuanganController::class, 'index'])->name('index');
    Route::get('/download-annual-report', [\App\Http\Controllers\LaporanKeuanganController::class, 'downloadAnnualReport'])->name('download-annual-report');
    Route::get('/download-excel', [\App\Http\Controllers\LaporanKeuanganController::class, 'downloadExcel'])->name('download-excel');
    Route::get('/preview', [\App\Http\Controllers\LaporanKeuanganController::class, 'preview'])->name('preview');
    
    // Publish/Unpublish untuk karyawan
    Route::post('/{id}/toggle-publish', [\App\Http\Controllers\LaporanKeuanganController::class, 'togglePublish'])->name('toggle-publish');
    Route::post('/{id}/publish', [\App\Http\Controllers\LaporanKeuanganController::class, 'publishLaporan'])->name('publish');
    Route::post('/{id}/unpublish', [\App\Http\Controllers\LaporanKeuanganController::class, 'unpublishLaporan'])->name('unpublish');
});

// ===================================
// LAPORAN KEUANGAN UNTUK KARYAWAN (Read-Only)
// ===================================
Route::middleware('role:karyawan')->prefix('laporan-keuangan-karyawan')->name('laporan-keuangan-karyawan.')->group(function () {
    Route::get('/', [\App\Http\Controllers\LaporanKeuanganKaryawanController::class, 'index'])->name('index');
    Route::get('/{id}', [\App\Http\Controllers\LaporanKeuanganKaryawanController::class, 'show'])->name('show');
    Route::get('/{id}/download-pdf', [\App\Http\Controllers\LaporanKeuanganKaryawanController::class, 'downloadPDF'])->name('download-pdf');
    Route::get('/{id}/download-excel', [\App\Http\Controllers\LaporanKeuanganKaryawanController::class, 'downloadExcel'])->name('download-excel');
});


// ===================================
// PINJAMAN (Crew & Non-Crew)
// ===================================
Route::middleware('role:super admin')->prefix('pinjaman')->name('pinjaman.')->controller(PinjamanController::class)->group(function () {
    // List & Dashboard
    Route::get('/', 'index')->name('index');
    
    // Download Formulir Kosong (Global)
    Route::get('/download-formulir-blank', 'downloadFormulirBlank')->name('download-formulir-blank');
    
    // CRUD Pinjaman
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{pinjaman}', 'show')->name('show');
    Route::get('/{pinjaman}/edit', 'edit')->name('edit');
    Route::put('/{pinjaman}', 'update')->name('update');
    Route::delete('/{pinjaman}', 'destroy')->name('destroy');
    
    // Download Formulir Terisi
    Route::get('/{pinjaman}/download-formulir', 'downloadFormulir')->name('download-formulir');
    
    // Workflow Approval
    Route::post('/{pinjaman}/review', 'review')->name('review');
    Route::post('/{pinjaman}/approve', 'approve')->name('approve');
    Route::post('/{pinjaman}/cairkan', 'cairkan')->name('cairkan');
    
    // Pembayaran Cicilan
    Route::post('/cicilan/{cicilan}/bayar', 'bayarCicilan')->name('cicilan.bayar');
    
    // Tunda Cicilan
    Route::post('/cicilan/{cicilan}/tunda', 'tundaCicilan')->name('cicilan.tunda');
    
    // Tambah Pinjaman (ke pinjaman yang sudah ada)
    Route::post('/{pinjaman}/tambah-pinjaman', 'tambahPinjaman')->name('tambah-pinjaman');
    
    // Kirim Email Notifikasi Manual
    Route::post('/{pinjaman}/kirim-email', 'kirimEmailManual')->name('kirim-email');
    
    // Laporan
    Route::get('/laporan/index', 'laporan')->name('laporan');
});

// ===================================
// MANAJEMEN PERAWATAN GEDUNG
// ===================================
Route::middleware('role:super admin')->prefix('perawatan')->name('perawatan.')->controller(ManajemenPerawatanController::class)->group(function () {
    // Dashboard Perawatan
    Route::get('/', 'index')->name('index');
    
    // Master Checklist (CRUD)
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/', 'masterIndex')->name('index');
        Route::get('/create', 'masterCreate')->name('create');
        Route::post('/store', 'masterStore')->name('store');
        Route::get('/{id}/edit', 'masterEdit')->name('edit');
        Route::put('/{id}', 'masterUpdate')->name('update');
        Route::delete('/{id}', 'masterDestroy')->name('destroy');
    });
    
    // Eksekusi Checklist
    Route::prefix('checklist')->name('checklist.')->group(function () {
        Route::get('/harian', 'checklistHarian')->name('harian');
        Route::get('/mingguan', 'checklistMingguan')->name('mingguan');
        Route::get('/bulanan', 'checklistBulanan')->name('bulanan');
        Route::get('/tahunan', 'checklistTahunan')->name('tahunan');
        
        Route::post('/execute', 'executeChecklist')->name('execute');
        Route::post('/uncheck', 'uncheckChecklist')->name('uncheck');
    });
    
    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', 'laporanIndex')->name('index');
        Route::post('/generate', 'generateLaporan')->name('generate');
        Route::get('/{id}/download', 'downloadLaporan')->name('download');
    });
}); // END Route::middleware('role:super admin')->prefix('perawatan')

// ===================================
// PERAWATAN GEDUNG - UNTUK KARYAWAN
// ===================================
Route::middleware('auth')->prefix('perawatan/karyawan')->name('perawatan.karyawan.')->controller(PerawatanKaryawanController::class)->group(function () {
    // Dashboard Perawatan Karyawan
    Route::get('/', 'index')->name('index');
    
    // Checklist berdasarkan tipe (harian, mingguan, bulanan, tahunan)
    Route::get('/checklist/{tipe}', 'checklist')->name('checklist');
    
    // Execute Checklist (centang sebagai selesai)
    Route::post('/execute', 'executeChecklist')->name('execute');
    
    // Uncheck Checklist (batalkan centang)
    Route::post('/uncheck', 'uncheckChecklist')->name('uncheck');
    
    // History Aktivitas
    Route::get('/history', 'history')->name('history');
}); // END Route Perawatan Karyawan

// ===================================
// TEMUAN - UNTUK ADMIN
// ===================================
Route::middleware('role:super admin')->prefix('temuan')->name('temuan.')->controller(TemuanController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::put('/{id}/status', 'updateStatus')->name('updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
    Route::get('/api/summary', 'apiSummary')->name('apiSummary');
    Route::get('/export/pdf', 'exportPdf')->name('exportPdf');
});

// ===================================
// TEMUAN - UNTUK KARYAWAN
// ===================================
Route::middleware('auth')->prefix('temuan/karyawan')->name('temuan.karyawan.')->controller(TemuanController::class)->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/list', 'karyawanList')->name('list');
    Route::get('/{id}', 'karyawanShow')->name('show');
    Route::delete('/{id}', 'karyawanDestroy')->name('destroy');
});

// Route::get('/storage/{path}', function ($path) {
//     return response()->file(storage_path('app/public/' . $path));
// })->where('path', '.*');

require __DIR__ . '/auth.php';
