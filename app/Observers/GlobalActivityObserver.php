<?php

namespace App\Observers;

use App\Services\NotificationService;
use App\Models\RealTimeNotification;

class GlobalActivityObserver
{
    /**
     * Handle model created event untuk semua model
     */
    public function created($model)
    {
        $this->createNotificationFromModel($model, 'created');
    }

    /**
     * Handle model updated event untuk semua model
     */
    public function updated($model)
    {
        $this->createNotificationFromModel($model, 'updated');
    }

    /**
     * Membuat notifikasi berdasarkan model dan action
     */
    private function createNotificationFromModel($model, $action)
    {
        $modelClass = get_class($model);
        $modelName = class_basename($modelClass);
        
        // Mapping model ke notifikasi yang sesuai
        switch ($modelName) {
            case 'Presensi':
                if ($action === 'created' || $action === 'updated') {
                    $type = 'masuk';
                    if ($model->jam_out && $action === 'updated') {
                        $type = 'pulang';
                    }
                    NotificationService::presensiNotification($model, $type);
                }
                break;

            case 'AktivitasKendaraan':
                if ($action === 'created') {
                    $type = isset($model->waktu_keluar) ? 'keluar' : 'masuk';
                    NotificationService::kendaraanNotification($model, $type);
                }
                break;

            case 'Pinjaman':
                if ($action === 'created') {
                    NotificationService::pinjamanNotification($model, 'pengajuan');
                } elseif ($action === 'updated' && $model->isDirty('status')) {
                    $status = $model->status;
                    $type = $status === 'disetujui' ? 'approve' : 
                           ($status === 'ditolak' ? 'reject' : 
                           ($status === 'dicairkan' ? 'cairkan' : 'update'));
                    NotificationService::pinjamanNotification($model, $type);
                }
                break;

            case 'Izinabsen':
            case 'Izinsakit':
            case 'Izincuti':
                if ($action === 'created') {
                    $modelType = strtolower(str_replace('Izin', 'izin_', $modelName));
                    NotificationService::izinNotification($model, 'pengajuan', $modelType);
                }
                break;

            case 'Lembur':
                if ($action === 'created') {
                    NotificationService::lemburNotification($model, 'pengajuan');
                } elseif ($action === 'updated') {
                    if ($model->isDirty('status') && $model->status == 1) {
                        NotificationService::lemburNotification($model, 'approve');
                    } elseif ($model->isDirty('lembur_in')) {
                        NotificationService::lemburNotification($model, 'checkin');
                    } elseif ($model->isDirty('lembur_out')) {
                        NotificationService::lemburNotification($model, 'checkout');
                    }
                }
                break;

            case 'AktivitasKaryawan':
                if ($action === 'created') {
                    $karyawan = $model->karyawan ?? null;
                    $nama_karyawan = $karyawan ? $karyawan->nama_karyawan : 'Unknown';
                    
                    $data = [
                        'message' => "Aktivitas baru: {$nama_karyawan} - {$model->deskripsi_aktivitas} di {$model->lokasi}",
                        'nama_karyawan' => $nama_karyawan,
                        'nik' => $model->nik,
                        'lokasi' => $model->lokasi,
                        'deskripsi' => $model->deskripsi_aktivitas,
                        'waktu' => $model->created_at->format('H:i:s'),
                        'reference_id' => $model->id,
                        'reference_table' => 'aktivitas_karyawan'
                    ];
                    
                    NotificationService::trackingPresensiNotification($data, 'aktivitas_baru');
                } elseif ($action === 'updated') {
                    if ($model->isDirty('lokasi')) {
                        $karyawan = $model->karyawan ?? null;
                        $nama_karyawan = $karyawan ? $karyawan->nama_karyawan : 'Unknown';
                        
                        $data = [
                            'message' => "Update lokasi: {$nama_karyawan} pindah ke {$model->lokasi}",
                            'nama_karyawan' => $nama_karyawan,
                            'lokasi_baru' => $model->lokasi,
                            'lokasi_lama' => $model->getOriginal('lokasi'),
                            'reference_id' => $model->id,
                            'reference_table' => 'aktivitas_karyawan'
                        ];
                        
                        NotificationService::trackingPresensiNotification($data, 'lokasi_update');
                    }
                }
                break;

            case 'PeminjamanInventaris':
                if ($action === 'created') {
                    $data = [
                        'nama_barang' => $model->inventaris->nama_inventaris ?? 'Inventaris',
                        'nama_peminjam' => $model->nama_peminjam ?? 'Unknown',
                        'reference_id' => $model->id,
                        'reference_table' => 'peminjaman_inventaris'
                    ];
                    NotificationService::inventarisNotification($data, 'peminjaman');
                }
                break;

            case 'PengembalianInventaris':
                if ($action === 'created') {
                    $peminjaman = $model->peminjamanInventaris;
                    $data = [
                        'nama_barang' => $peminjaman->inventaris->nama_inventaris ?? 'Inventaris',
                        'nama_peminjam' => $peminjaman->nama_peminjam ?? 'Unknown',
                        'reference_id' => $model->id,
                        'reference_table' => 'pengembalian_inventaris'
                    ];
                    NotificationService::inventarisNotification($data, 'pengembalian');
                }
                break;

            case 'TransferBarang':
                if ($action === 'created') {
                    $data = [
                        'nama_barang' => $model->barang->nama_barang ?? 'Barang',
                        'nama_peminjam' => 'System Transfer',
                        'reference_id' => $model->id,
                        'reference_table' => 'transfer_barangs'
                    ];
                    NotificationService::inventarisNotification($data, 'transfer');
                }
                break;

            case 'PeminjamanKendaraan':
                if ($action === 'created') {
                    $data = [
                        'nomor_polisi' => $model->kendaraan->nomor_polisi ?? 'Kendaraan',
                        'peminjam' => $model->nama_peminjam ?? 'Unknown',
                        'tujuan' => $model->tujuan,
                        'waktu' => $model->created_at
                    ];
                    NotificationService::kendaraanNotification($data, 'peminjaman');
                }
                break;

            case 'ServiceKendaraan':
                if ($action === 'created') {
                    $data = [
                        'nomor_polisi' => $model->kendaraan->nomor_polisi ?? 'Kendaraan',
                        'jenis_service' => $model->jenis_service,
                        'bengkel' => $model->nama_bengkel,
                        'waktu' => $model->created_at
                    ];
                    NotificationService::kendaraanNotification($data, 'service');
                }
                break;

            case 'AbsensiSantri':
                if ($action === 'created') {
                    $santri = $model->santri ?? null;
                    $nama_santri = $santri ? $santri->nama_santri : 'Unknown';
                    
                    if ($model->status == 'hadir') {
                        $data = [
                            'message' => "Santri hadir: {$nama_santri} - {$model->mata_pelajaran} pada {$model->tanggal} {$model->jam_masuk}",
                            'nama_santri' => $nama_santri,
                            'mata_pelajaran' => $model->mata_pelajaran ?? 'Umum',
                            'tanggal' => $model->tanggal,
                            'jam_masuk' => $model->jam_masuk ?? '-',
                            'kelas' => $model->kelas ?? '-',
                            'reference_id' => $model->id,
                            'reference_table' => 'absensi_santri'
                        ];
                        NotificationService::santriDetailNotification($data, 'absensi_hadir');
                    } else {
                        $data = [
                            'message' => "Santri tidak hadir: {$nama_santri} - {$model->mata_pelajaran} ({$model->keterangan})",
                            'nama_santri' => $nama_santri,
                            'mata_pelajaran' => $model->mata_pelajaran ?? 'Umum',
                            'tanggal' => $model->tanggal,
                            'alasan' => $model->keterangan ?? 'Tanpa keterangan',
                            'reference_id' => $model->id,
                            'reference_table' => 'absensi_santri'
                        ];
                        NotificationService::santriDetailNotification($data, 'absensi_tidak_hadir');
                    }
                }
                break;

            case 'PelanggaranSantri':
                if ($action === 'created') {
                    $santri = $model->santri ?? null;
                    $nama_santri = $santri ? $santri->nama_santri : 'Unknown';
                    
                    $data = [
                        'message' => "Pelanggaran baru: {$nama_santri} - {$model->jenis_pelanggaran} pada {$model->tanggal}",
                        'nama_santri' => $nama_santri,
                        'jenis_pelanggaran' => $model->jenis_pelanggaran,
                        'deskripsi' => $model->deskripsi_pelanggaran ?? '-',
                        'tanggal' => $model->tanggal,
                        'tingkat_pelanggaran' => $model->tingkat_pelanggaran ?? 'Ringan',
                        'pelapor' => $model->pelapor ?? 'System',
                        'reference_id' => $model->id,
                        'reference_table' => 'pelanggaran_santri'
                    ];
                    NotificationService::santriDetailNotification($data, 'pelanggaran_baru');
                } elseif ($action === 'updated' && $model->isDirty('sanksi')) {
                    $santri = $model->santri ?? null;
                    $nama_santri = $santri ? $santri->nama_santri : 'Unknown';
                    
                    $data = [
                        'message' => "Sanksi diberikan: {$nama_santri} - {$model->sanksi}",
                        'nama_santri' => $nama_santri,
                        'jenis_sanksi' => $model->sanksi,
                        'jenis_pelanggaran' => $model->jenis_pelanggaran,
                        'reference_id' => $model->id,
                        'reference_table' => 'pelanggaran_santri'
                    ];
                    NotificationService::santriDetailNotification($data, 'sanksi_diberikan');
                }
                break;

            case 'KeuanganSantriTransaction':
                if ($action === 'created') {
                    $santri = $model->santri ?? null;
                    $nama_santri = $santri ? $santri->nama_santri : 'Unknown';
                    
                    $data = [
                        'message' => "{$model->jenis_transaksi}: {$nama_santri} - Rp " . number_format($model->nominal, 0, ',', '.') . " ({$model->keterangan})",
                        'nama_santri' => $nama_santri,
                        'jenis_transaksi' => $model->jenis_transaksi,
                        'nominal' => number_format($model->nominal, 0, ',', '.'),
                        'keterangan' => $model->keterangan ?? 'Transaksi umum',
                        'tanggal' => $model->tanggal ?? $model->created_at->format('Y-m-d'),
                        'saldo_setelah' => $model->saldo_akhir ?? 0,
                        'reference_id' => $model->id,
                        'reference_table' => 'keuangan_santri_transactions'
                    ];
                    NotificationService::santriDetailNotification($data, 'transaksi_keuangan');
                }
                break;

            case 'KehadiranTukang':
                if ($action === 'created') {
                    $tukang = $model->tukang ?? null;
                    $nama_tukang = $tukang ? $tukang->nama_tukang : 'Unknown';
                    
                    if ($model->jam_masuk && !$model->jam_keluar) {
                        $data = [
                            'message' => "Tukang masuk: {$nama_tukang} - {$model->jam_masuk} ({$model->status})",
                            'nama_tukang' => $nama_tukang,
                            'jam_masuk' => $model->jam_masuk,
                            'status' => $model->status ?? 'Hadir',
                            'tanggal' => $model->tanggal,
                            'keahlian' => $tukang ? $tukang->keahlian : '-',
                            'reference_id' => $model->id,
                            'reference_table' => 'kehadiran_tukangs'
                        ];
                        NotificationService::tukangDetailNotification($data, 'kehadiran_masuk');
                    } elseif ($model->isDirty('jam_keluar') && $model->jam_keluar) {
                        $data = [
                            'message' => "Tukang pulang: {$nama_tukang} - {$model->jam_keluar}",
                            'nama_tukang' => $nama_tukang,
                            'jam_pulang' => $model->jam_keluar,
                            'durasi_kerja' => $model->total_jam ?? '-',
                            'reference_id' => $model->id,
                            'reference_table' => 'kehadiran_tukangs'
                        ];
                        NotificationService::tukangDetailNotification($data, 'kehadiran_pulang');
                    }
                } elseif ($action === 'updated') {
                    if ($model->isDirty('jam_keluar') && $model->jam_keluar) {
                        $tukang = $model->tukang ?? null;
                        $nama_tukang = $tukang ? $tukang->nama_tukang : 'Unknown';
                        
                        $data = [
                            'message' => "Tukang pulang: {$nama_tukang} - {$model->jam_keluar}",
                            'nama_tukang' => $nama_tukang,
                            'jam_pulang' => $model->jam_keluar,
                            'durasi_kerja' => $model->total_jam ?? '-',
                            'reference_id' => $model->id,
                            'reference_table' => 'kehadiran_tukangs'
                        ];
                        NotificationService::tukangDetailNotification($data, 'kehadiran_pulang');
                    }
                }
                break;

            case 'PinjamanTukang':
                if ($action === 'created') {
                    $data = [
                        'message' => "Tukang {$model->tukang->nama_tukang} mengajukan pinjaman Rp " . number_format($model->jumlah_pinjaman),
                        'reference_id' => $model->id,
                        'reference_table' => 'pinjaman_tukangs'
                    ];
                    NotificationService::tukangNotification($data, 'pinjaman');
                }
                break;

            case 'KehadiranJamaah':
                if ($action === 'created') {
                    $jamaah = $model->jamaah ?? null;
                    $nama_jamaah = $jamaah ? $jamaah->nama_jamaah : 'Unknown';
                    
                    $data = [
                        'message' => "Kehadiran Majlis Ta'lim: {$nama_jamaah} - {$model->jenis_kegiatan} pada {$model->tanggal}",
                        'nama_jamaah' => $nama_jamaah,
                        'jenis_kegiatan' => $model->jenis_kegiatan ?? 'Kegiatan Rutin',
                        'tanggal' => $model->tanggal,
                        'waktu' => $model->waktu ?? '-',
                        'lokasi' => $model->lokasi ?? 'Masjid',
                        'keterangan' => $model->keterangan ?? '-',
                        'organisasi' => 'majlistaklim',
                        'reference_id' => $model->id,
                        'reference_table' => 'kehadiran_jamaah'
                    ];
                    NotificationService::yayasanDetailNotification($data, 'kehadiran_jamaah', 'majlistaklim');
                }
                break;

            case 'KehadiranJamaahMasar':
                if ($action === 'created') {
                    $jamaahMasar = $model->jamaahMasar ?? null;
                    $nama_jamaah = $jamaahMasar ? $jamaahMasar->nama_jamaah : 'Unknown';
                    
                    $data = [
                        'message' => "Kehadiran MASAR: {$nama_jamaah} - {$model->jenis_kegiatan} pada {$model->tanggal}",
                        'nama_jamaah' => $nama_jamaah,
                        'jenis_kegiatan' => $model->jenis_kegiatan ?? 'Kegiatan Rutin',
                        'tanggal' => $model->tanggal,
                        'waktu' => $model->waktu ?? '-',
                        'lokasi' => $model->lokasi ?? 'Masjid',
                        'keterangan' => $model->keterangan ?? '-',
                        'organisasi' => 'masar',
                        'reference_id' => $model->id,
                        'reference_table' => 'kehadiran_jamaah_masar'
                    ];
                    NotificationService::yayasanDetailNotification($data, 'kehadiran_jamaah', 'masar');
                }
                break;

            case 'DistribusiHadiah':
            case 'DistribusiHadiahMasar':
                if ($action === 'created') {
                    $isMasar = str_contains($modelName, 'Masar');
                    $organisasi = $isMasar ? 'masar' : 'majlistaklim';
                    $organisasi_name = $isMasar ? 'MASAR' : 'Majlis Ta\'lim Al-Ikhlas';
                    
                    $jamaah = null;
                    $nama_jamaah = 'Unknown';
                    
                    if ($isMasar && $model->jamaahMasar) {
                        $jamaah = $model->jamaahMasar;
                        $nama_jamaah = $jamaah->nama_jamaah;
                    } elseif (!$isMasar && $model->jamaah) {
                        $jamaah = $model->jamaah;
                        $nama_jamaah = $jamaah->nama_jamaah;
                    }
                    
                    $data = [
                        'message' => "Distribusi hadiah {$organisasi_name}: {$nama_jamaah} - {$model->jenis_hadiah} (Rp " . number_format($model->nominal ?? 0, 0, ',', '.') . ")",
                        'nama_jamaah' => $nama_jamaah,
                        'nama_hadiah' => $model->jenis_hadiah ?? 'Hadiah Umum',
                        'nominal' => number_format($model->nominal ?? 0, 0, ',', '.'),
                        'tanggal_distribusi' => $model->tanggal_distribusi ?? $model->created_at->format('Y-m-d'),
                        'periode' => $model->periode ?? date('Y-m'),
                        'keterangan' => $model->keterangan ?? '-',
                        'organisasi' => $organisasi,
                        'reference_id' => $model->id,
                        'reference_table' => $model->getTable()
                    ];
                    NotificationService::yayasanDetailNotification($data, 'distribusi_hadiah', $organisasi);
                }
                break;

            case 'Administrasi':
                if ($action === 'created') {
                    $data = [
                        'message' => "Dokumen administrasi baru: {$model->judul}",
                        'reference_id' => $model->id,
                        'reference_table' => 'administrasi'
                    ];
                    NotificationService::administrasiNotification($data, 'dokumen_baru');
                }
                break;

            case 'TindakLanjutAdministrasi':
                if ($action === 'created') {
                    $data = [
                        'message' => "Tindak lanjut administrasi: {$model->catatan}",
                        'reference_id' => $model->id,
                        'reference_table' => 'tindak_lanjut_administrasi'
                    ];
                    NotificationService::administrasiNotification($data, 'tindak_lanjut');
                }
                break;

            case 'TransaksiKeuangan':
            case 'RealisasiDanaOperasional':
            case 'PengajuanDanaOperasional':
                if ($action === 'created') {
                    $data = [
                        'message' => $this->getKeuanganMessage($model, $modelName),
                        'reference_id' => $model->id,
                        'reference_table' => $model->getTable()
                    ];
                    NotificationService::keuanganNotification($data, 'transaksi');
                }
                break;

            case 'Karyawan':
                if ($action === 'created') {
                    NotificationService::customNotification(
                        'Karyawan Baru',
                        "Karyawan baru ditambahkan: {$model->nama_karyawan}",
                        'karyawan',
                        [
                            'icon' => 'ti ti-user-plus',
                            'color' => 'success',
                            'data' => [
                                'nik' => $model->nik,
                                'nama_karyawan' => $model->nama_karyawan,
                                'kode_dept' => $model->kode_dept
                            ]
                        ]
                    );
                }
                break;

            case 'User':
                if ($action === 'created') {
                    NotificationService::customNotification(
                        'User Baru',
                        "User baru terdaftar: {$model->name}",
                        'user',
                        [
                            'icon' => 'ti ti-user-check',
                            'color' => 'info',
                            'data' => [
                                'name' => $model->name,
                                'email' => $model->email
                            ]
                        ]
                    );
                }
                break;

            case 'Barang':
                if ($action === 'created') {
                    NotificationService::inventarisNotification([
                        'nama_barang' => $model->nama_barang,
                        'nama_peminjam' => 'Admin',
                        'reference_id' => $model->id,
                        'reference_table' => 'barangs'
                    ], 'barang_baru');
                } elseif ($action === 'updated') {
                    NotificationService::inventarisNotification([
                        'nama_barang' => $model->nama_barang,
                        'nama_peminjam' => 'Admin',
                        'reference_id' => $model->id,
                        'reference_table' => 'barangs'
                    ], 'barang_update');
                }
                break;

            case 'Inventaris':
                if ($action === 'created') {
                    NotificationService::inventarisNotification([
                        'nama_barang' => $model->nama_inventaris,
                        'nama_peminjam' => 'Admin',
                        'reference_id' => $model->id,
                        'reference_table' => 'inventaris'
                    ], 'inventaris_baru');
                }
                break;

            case 'Kendaraan':
                if ($action === 'created') {
                    NotificationService::customNotification(
                        'Kendaraan Baru',
                        "Kendaraan baru ditambahkan: {$model->nomor_polisi}",
                        'kendaraan',
                        [
                            'icon' => 'ti ti-car-crane',
                            'color' => 'info',
                            'data' => [
                                'nomor_polisi' => $model->nomor_polisi,
                                'merk' => $model->merk,
                                'model' => $model->model
                            ]
                        ]
                    );
                }
                break;

            case 'Gedung':
                if ($action === 'created') {
                    NotificationService::customNotification(
                        'Gedung Baru',
                        "Gedung baru ditambahkan: {$model->nama_gedung}",
                        'fasilitas',
                        [
                            'icon' => 'ti ti-building',
                            'color' => 'secondary'
                        ]
                    );
                }
                break;

            case 'Ruangan':
                if ($action === 'created') {
                    NotificationService::customNotification(
                        'Ruangan Baru',
                        "Ruangan baru ditambahkan: {$model->nama_ruangan}",
                        'fasilitas',
                        [
                            'icon' => 'ti ti-door',
                            'color' => 'secondary'
                        ]
                    );
                }
                break;

            case 'Santri':
                if ($action === 'created') {
                    $data = [
                        'message' => "Santri baru terdaftar: {$model->nama_santri}",
                        'reference_id' => $model->id,
                        'reference_table' => 'santri'
                    ];
                    NotificationService::santriNotification($data, 'registrasi');
                }
                break;

            case 'Tukang':
                if ($action === 'created') {
                    $data = [
                        'message' => "Tukang baru terdaftar: {$model->nama_tukang}",
                        'reference_id' => $model->id,
                        'reference_table' => 'tukangs'
                    ];
                    NotificationService::tukangNotification($data, 'registrasi');
                }
                break;

            case 'JamaahMajlisTaklim':
                if ($action === 'created') {
                    $data = [
                        'message' => "Jamaah baru terdaftar: {$model->nama_jamaah}",
                        'reference_id' => $model->id,
                        'reference_table' => 'jamaah_majlis_taklim'
                    ];
                    NotificationService::jamaahNotification($data, 'registrasi', 'majlis_taklim');
                }
                break;

            case 'JamaahMasar':
                if ($action === 'created') {
                    $data = [
                        'message' => "Jamaah Masar baru terdaftar: {$model->nama_jamaah}",
                        'reference_id' => $model->id,
                        'reference_table' => 'jamaah_masar'
                    ];
                    NotificationService::jamaahNotification($data, 'registrasi', 'masar');
                }
                break;

            case 'Document':
                if ($action === 'created') {
                    $data = [
                        'message' => "Dokumen baru diupload: {$model->nama_dokumen}",
                        'reference_id' => $model->id,
                        'reference_table' => 'documents'
                    ];
                    NotificationService::administrasiNotification($data, 'dokumen_baru');
                }
                break;

            case 'KpiCrew':
                if ($action === 'created') {
                    $karyawan = $model->karyawan;
                    $namaKaryawan = $karyawan ? $karyawan->nama_karyawan : 'Unknown';
                    NotificationService::customNotification(
                        'KPI Crew Baru',
                        "KPI crew " . $namaKaryawan . " telah dihitung",
                        'kpi',
                        [
                            'icon' => 'ti ti-chart-line',
                            'color' => 'warning',
                            'data' => [
                                'nik' => $model->nik,
                                'total_point' => $model->total_point,
                                'ranking' => $model->ranking
                            ]
                        ]
                    );
                }
                break;

            case 'Kunjungan':
                if ($action === 'created') {
                    $data = [
                        'message' => "Kunjungan baru: {$model->nama_pengunjung} ke {$model->tujuan_kunjungan} pada {$model->waktu_kunjungan}",
                        'nama_pengunjung' => $model->nama_pengunjung,
                        'tujuan' => $model->tujuan_kunjungan,
                        'waktu_kunjungan' => $model->waktu_kunjungan,
                        'keperluan' => $model->keperluan,
                        'asal_instansi' => $model->asal_instansi ?? 'Individu',
                        'reference_id' => $model->id,
                        'reference_table' => 'kunjungan'
                    ];
                    NotificationService::kunjunganDetailNotification($data, 'kunjungan_baru');
                } elseif ($action === 'updated') {
                    if ($model->isDirty('status') && $model->status == 'selesai') {
                        $data = [
                            'message' => "Kunjungan selesai: {$model->nama_pengunjung} - durasi {$model->durasi_kunjungan}",
                            'nama_pengunjung' => $model->nama_pengunjung,
                            'durasi' => $model->durasi_kunjungan,
                            'reference_id' => $model->id,
                            'reference_table' => 'kunjungan'
                        ];
                        NotificationService::kunjunganDetailNotification($data, 'kunjungan_selesai');
                    }
                }
                break;

            case 'Gedung':
                if ($action === 'created') {
                    $data = [
                        'message' => "Gedung baru ditambahkan: '{$model->nama_gedung}' - {$model->alamat}",
                        'nama_gedung' => $model->nama_gedung,
                        'alamat' => $model->alamat,
                        'jumlah_lantai' => $model->jumlah_lantai ?? 0,
                        'luas_bangunan' => $model->luas_bangunan ?? 0,
                        'reference_id' => $model->id,
                        'reference_table' => 'gedung'
                    ];
                    NotificationService::gedungDetailNotification($data, 'gedung_baru');
                }
                break;

            case 'Ruangan':
                if ($action === 'created') {
                    $gedung = $model->gedung ?? null;
                    $nama_gedung = $gedung ? $gedung->nama_gedung : 'Unknown';
                    
                    $data = [
                        'message' => "Ruangan baru: '{$model->nama_ruangan}' di {$nama_gedung} - kapasitas {$model->kapasitas} orang",
                        'nama_ruangan' => $model->nama_ruangan,
                        'nama_gedung' => $nama_gedung,
                        'kapasitas' => $model->kapasitas ?? 0,
                        'fasilitas' => $model->fasilitas ?? 'Standar',
                        'reference_id' => $model->id,
                        'reference_table' => 'ruangan'
                    ];
                    NotificationService::gedungDetailNotification($data, 'ruangan_baru');
                }
                break;

            case 'TugasLuar':
                if ($action === 'created') {
                    $karyawan = $model->karyawan;
                    $namaKaryawan = $karyawan ? $karyawan->nama_karyawan : 'Unknown';
                    NotificationService::customNotification(
                        'Tugas Luar Baru',
                        "Tugas luar: " . $namaKaryawan . " ke " . $model->lokasi_tugas,
                        'tugas_luar',
                        [
                            'icon' => 'ti ti-map-pin',
                            'color' => 'primary',
                            'data' => [
                                'nik' => $model->nik,
                                'lokasi_tugas' => $model->lokasi_tugas,
                                'keperluan' => $model->keperluan
                            ]
                        ]
                    );
                }
                break;

            default:
                // Notifikasi umum untuk model yang belum ada mapping spesifik
                if (in_array($action, ['created', 'updated'])) {
                    $title = ucfirst($action) . ' ' . $modelName;
                    $message = $modelName . ' ' . ($action === 'created' ? 'baru dibuat' : 'diperbarui');
                    
                    NotificationService::customNotification($title, $message, 'system', [
                        'icon' => 'ti ti-activity',
                        'color' => 'info',
                        'data' => [
                            'model' => $modelName,
                            'action' => $action,
                            'id' => $model->id ?? null
                        ],
                        'reference_id' => $model->id ?? null,
                        'reference_table' => $model->getTable() ?? null
                    ]);
                }
                break;
        }
    }

    /**
     * Generate pesan untuk transaksi keuangan
     */
    private function getKeuanganMessage($model, $modelName)
    {
        switch ($modelName) {
            case 'TransaksiKeuangan':
                return "Transaksi keuangan: {$model->deskripsi} - Rp " . number_format($model->nominal);
            case 'RealisasiDanaOperasional':
                return "Realisasi dana operasional: {$model->keterangan} - Rp " . number_format($model->nominal);
            case 'PengajuanDanaOperasional':
                return "Pengajuan dana operasional: {$model->keperluan} - Rp " . number_format($model->nominal_pengajuan);
            default:
                return "Aktivitas keuangan baru";
        }
    }
}