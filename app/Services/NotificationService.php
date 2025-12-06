<?php

namespace App\Services;

use App\Models\RealTimeNotification;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\AktivitasKendaraan;
use App\Models\Pinjaman;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Membuat notifikasi untuk presensi masuk/keluar
     */
    public static function presensiNotification($presensi, $type = 'masuk')
    {
        $karyawan = Karyawan::where('nik', $presensi->nik)->first();
        
        if ($type === 'masuk') {
            $title = 'Presensi Masuk';
            $message = "{$karyawan->nama_karyawan} telah melakukan presensi masuk pada " . Carbon::parse($presensi->jam_in)->format('H:i');
        } else {
            $title = 'Presensi Pulang';
            $message = "{$karyawan->nama_karyawan} telah melakukan presensi pulang pada " . Carbon::parse($presensi->jam_out)->format('H:i');
        }

        return RealTimeNotification::createNotification($title, $message, 'presensi', [
            'icon' => 'ti ti-clock',
            'color' => 'success',
            'data' => [
                'nik' => $presensi->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jam' => $type === 'masuk' ? $presensi->jam_in : $presensi->jam_out,
                'type' => $type
            ],
            'reference_id' => $presensi->id,
            'reference_table' => 'presensi'
        ]);
    }

    /**
     * Membuat notifikasi untuk aktivitas kendaraan
     */
    public static function kendaraanNotification($aktivitas, $type = 'keluar')
    {
        // Handle both array and object input
        $kendaraan = null;
        $driver = null;
        $waktu = null;
        $tujuan = null;
        $kode_aktivitas = null;
        $reference_id = null;
        
        if (is_array($aktivitas)) {
            // If aktivitas is array
            $kendaraan = $aktivitas['kendaraan'] ?? null;
            $driver = $aktivitas['driver'] ?? $aktivitas[1] ?? 'Unknown';
            $tujuan = $aktivitas['tujuan'] ?? null;
            $kode_aktivitas = $aktivitas['kode_aktivitas'] ?? null;
            $reference_id = $aktivitas['id'] ?? null;
            
            if ($type === 'keluar') {
                $waktu = $aktivitas['waktu_keluar'] ?? $aktivitas[2] ?? null;
            } else {
                $waktu = $aktivitas['waktu_kembali'] ?? null;
            }
        } else {
            // If aktivitas is object
            $kendaraan = $aktivitas->kendaraan ?? null;
            $driver = $aktivitas->driver ?? 'Unknown';
            $tujuan = $aktivitas->tujuan ?? null;
            $kode_aktivitas = $aktivitas->kode_aktivitas ?? null;
            $reference_id = $aktivitas->id ?? null;
            
            if ($type === 'keluar') {
                $waktu = $aktivitas->waktu_keluar ?? null;
            } else {
                $waktu = $aktivitas->waktu_kembali ?? null;
            }
        }
        
        $nomor_polisi = 'Unknown';
        if (is_array($kendaraan) && isset($kendaraan['nomor_polisi'])) {
            $nomor_polisi = $kendaraan['nomor_polisi'];
        } elseif (is_object($kendaraan) && isset($kendaraan->nomor_polisi)) {
            $nomor_polisi = $kendaraan->nomor_polisi;
        } elseif (is_string($kendaraan)) {
            $nomor_polisi = $kendaraan;
        }
        
        $title = $type === 'keluar' ? 'Kendaraan Keluar' : 'Kendaraan Masuk';
        $message = "Kendaraan {$nomor_polisi} {$type}";
        
        if ($waktu) {
            try {
                $message .= " pada " . Carbon::parse($waktu)->format('H:i');
            } catch (\Exception $e) {
                // Ignore datetime parse error
            }
        }
        
        $message .= " - Driver: {$driver}";

        return RealTimeNotification::createNotification($title, $message, 'kendaraan', [
            'icon' => 'ti ti-car',
            'color' => $type === 'keluar' ? 'info' : 'success',
            'data' => [
                'nomor_polisi' => $nomor_polisi,
                'driver' => $driver,
                'tujuan' => $tujuan,
                'waktu' => $waktu,
                'type' => $type,
                'kode_aktivitas' => $kode_aktivitas
            ],
            'reference_id' => $reference_id,
            'reference_table' => 'aktivitas_kendaraans'
        ]);
    }

    /**
     * Membuat notifikasi untuk pinjaman
     */
    public static function pinjamanNotification($pinjaman, $type = 'pengajuan')
    {
        $karyawan = Karyawan::where('nik', $pinjaman->nik)->first();
        
        $titles = [
            'pengajuan' => 'Pengajuan Pinjaman Baru',
            'approve' => 'Pinjaman Disetujui',
            'reject' => 'Pinjaman Ditolak',
            'cicilan' => 'Pembayaran Cicilan',
            'lunas' => 'Pinjaman Lunas'
        ];

        $colors = [
            'pengajuan' => 'warning',
            'approve' => 'success',
            'reject' => 'danger',
            'cicilan' => 'info',
            'lunas' => 'success'
        ];

        $title = $titles[$type] ?? 'Aktivitas Pinjaman';
        $message = "{$karyawan->nama_karyawan} - {$title} sebesar " . number_format($pinjaman->jumlah_pinjaman, 0, ',', '.');

        return RealTimeNotification::createNotification($title, $message, 'pinjaman', [
            'icon' => 'ti ti-credit-card',
            'color' => $colors[$type] ?? 'primary',
            'data' => [
                'nik' => $pinjaman->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jumlah' => $pinjaman->jumlah_pinjaman,
                'type' => $type
            ],
            'reference_id' => $pinjaman->id,
            'reference_table' => 'pinjaman'
        ]);
    }

    /**
     * Membuat notifikasi untuk undangan deadline
     */
    public static function undanganDeadlineNotification($undangan)
    {
        $title = 'Deadline Undangan';
        $message = "Undangan '{$undangan->judul}' telah mencapai deadline pada " . Carbon::parse($undangan->tanggal_deadline)->format('d M Y H:i');

        return RealTimeNotification::createNotification($title, $message, 'undangan', [
            'icon' => 'ti ti-calendar-event',
            'color' => 'danger',
            'data' => [
                'judul' => $undangan->judul,
                'deadline' => $undangan->tanggal_deadline,
                'type' => 'deadline'
            ],
            'reference_id' => $undangan->id,
            'reference_table' => 'administrasi'
        ]);
    }

    /**
     * Membuat notifikasi untuk inventaris
     */
    public static function inventarisNotification($data, $type = 'peminjaman')
    {
        $titles = [
            'peminjaman' => 'Peminjaman Inventaris',
            'pengembalian' => 'Pengembalian Inventaris',
            'transfer' => 'Transfer Barang'
        ];

        $colors = [
            'peminjaman' => 'warning',
            'pengembalian' => 'success',
            'transfer' => 'info'
        ];

        $title = $titles[$type] ?? 'Aktivitas Inventaris';
        $message = "{$data['nama_barang']} - {$title} oleh {$data['nama_peminjam']}";

        return RealTimeNotification::createNotification($title, $message, 'inventaris', [
            'icon' => 'ti ti-package',
            'color' => $colors[$type] ?? 'secondary',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Membuat notifikasi untuk administrasi
     */
    public static function administrasiNotification($data, $type = 'dokumen_baru')
    {
        $titles = [
            'dokumen_baru' => 'Dokumen Administrasi Baru',
            'tindak_lanjut' => 'Tindak Lanjut Administrasi',
            'approval' => 'Persetujuan Administrasi'
        ];

        $title = $titles[$type] ?? 'Aktivitas Administrasi';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'administrasi', [
            'icon' => 'ti ti-file-text',
            'color' => 'dark',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? 'administrasi'
        ]);
    }

    /**
     * Membuat notifikasi untuk keuangan
     */
    public static function keuanganNotification($data, $type = 'transaksi')
    {
        $titles = [
            'transaksi' => 'Transaksi Keuangan',
            'laporan' => 'Laporan Keuangan',
            'pengajuan_dana' => 'Pengajuan Dana Operasional'
        ];

        $title = $titles[$type] ?? 'Aktivitas Keuangan';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'keuangan', [
            'icon' => 'ti ti-coins',
            'color' => 'success',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Membuat notifikasi umum/custom
     */
    public static function customNotification($title, $message, $type = 'system', $options = [])
    {
        return RealTimeNotification::createNotification($title, $message, $type, $options);
    }

    /**
     * Membersihkan notifikasi hari sebelumnya (untuk cron job)
     */
    public static function cleanupOldNotifications()
    {
        return RealTimeNotification::where('tanggal', '<', Carbon::today())->delete();
    }

    /**
     * Get notifikasi hari ini untuk dashboard
     */
    public static function getTodayNotifications($limit = 50)
    {
        return RealTimeNotification::today()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Membuat notifikasi untuk izin absen
     */
    public static function izinNotification($izin, $type = 'pengajuan', $model_type = 'izin_absen')
    {
        $karyawan = Karyawan::where('nik', $izin->nik)->first();
        
        $titles = [
            'pengajuan' => 'Pengajuan Izin',
            'approve' => 'Izin Disetujui',
            'reject' => 'Izin Ditolak'
        ];

        $colors = [
            'pengajuan' => 'warning',
            'approve' => 'success',
            'reject' => 'danger'
        ];

        $title = $titles[$type] ?? 'Aktivitas Izin';
        $message = "{$karyawan->nama_karyawan} - {$title}";
        
        if ($model_type == 'izin_absen') {
            $message .= " pada " . \Carbon\Carbon::parse($izin->tanggal_izin_dari)->format('d M Y');
        } elseif ($model_type == 'izin_sakit') {
            $message .= " sakit dari " . \Carbon\Carbon::parse($izin->tanggal_dari)->format('d M') . 
                       " sampai " . \Carbon\Carbon::parse($izin->tanggal_sampai)->format('d M Y');
        } elseif ($model_type == 'izin_cuti') {
            $message .= " cuti " . $izin->jml_hari . " hari";
        }

        return RealTimeNotification::createNotification($title, $message, 'izin', [
            'icon' => 'ti ti-calendar-off',
            'color' => $colors[$type] ?? 'primary',
            'data' => [
                'nik' => $izin->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'model_type' => $model_type,
                'type' => $type
            ],
            'reference_id' => $izin->id,
            'reference_table' => $model_type == 'izin_absen' ? 'presensi_izinabsen' : 
                               ($model_type == 'izin_sakit' ? 'presensi_izinsakit' : 'presensi_izincuti')
        ]);
    }

    /**
     * Membuat notifikasi untuk lembur
     */
    public static function lemburNotification($lembur, $type = 'pengajuan')
    {
        $karyawan = Karyawan::where('nik', $lembur->nik)->first();
        
        $titles = [
            'pengajuan' => 'Pengajuan Lembur',
            'approve' => 'Lembur Disetujui',
            'reject' => 'Lembur Ditolak',
            'checkin' => 'Check-in Lembur',
            'checkout' => 'Check-out Lembur'
        ];

        $colors = [
            'pengajuan' => 'warning',
            'approve' => 'success',
            'reject' => 'danger',
            'checkin' => 'info',
            'checkout' => 'primary'
        ];

        $title = $titles[$type] ?? 'Aktivitas Lembur';
        $message = "{$karyawan->nama_karyawan} - {$title} pada " . 
                  \Carbon\Carbon::parse($lembur->tanggal_lembur)->format('d M Y');

        return RealTimeNotification::createNotification($title, $message, 'lembur', [
            'icon' => 'ti ti-clock-plus',
            'color' => $colors[$type] ?? 'primary',
            'data' => [
                'nik' => $lembur->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'tanggal_lembur' => $lembur->tanggal_lembur,
                'type' => $type
            ],
            'reference_id' => $lembur->id,
            'reference_table' => 'lembur'
        ]);
    }

    /**
     * Membuat notifikasi untuk aktivitas karyawan
     */
    public static function aktivitasKaryawanNotification($aktivitas, $type = 'baru')
    {
        $karyawan = Karyawan::where('nik', $aktivitas->nik)->first();
        
        $title = 'Aktivitas Karyawan Baru';
        $message = "{$karyawan->nama_karyawan} menambahkan aktivitas: {$aktivitas->deskripsi}";

        return RealTimeNotification::createNotification($title, $message, 'aktivitas_karyawan', [
            'icon' => 'ti ti-list-check',
            'color' => 'info',
            'data' => [
                'nik' => $aktivitas->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'deskripsi' => $aktivitas->deskripsi,
                'type' => $type
            ],
            'reference_id' => $aktivitas->id,
            'reference_table' => 'aktivitas_karyawan'
        ]);
    }

    /**
     * Membuat notifikasi untuk kegiatan santri
     */
    public static function santriNotification($data, $type = 'kehadiran')
    {
        $titles = [
            'kehadiran' => 'Kehadiran Santri',
            'pelanggaran' => 'Pelanggaran Santri',
            'ijin' => 'Ijin Santri',
            'keuangan' => 'Transaksi Keuangan Santri'
        ];

        $icons = [
            'kehadiran' => 'ti ti-user-check',
            'pelanggaran' => 'ti ti-alert-triangle',
            'ijin' => 'ti ti-calendar-off',
            'keuangan' => 'ti ti-coins'
        ];

        $colors = [
            'kehadiran' => 'success',
            'pelanggaran' => 'danger',
            'ijin' => 'warning',
            'keuangan' => 'info'
        ];

        $title = $titles[$type] ?? 'Aktivitas Santri';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'santri', [
            'icon' => $icons[$type] ?? 'ti ti-users',
            'color' => $colors[$type] ?? 'primary',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Membuat notifikasi untuk tukang
     */
    public static function tukangNotification($data, $type = 'kehadiran')
    {
        $titles = [
            'kehadiran' => 'Kehadiran Tukang',
            'gaji' => 'Pembayaran Gaji Tukang',
            'pinjaman' => 'Pinjaman Tukang'
        ];

        $icons = [
            'kehadiran' => 'ti ti-hammer',
            'gaji' => 'ti ti-coins',
            'pinjaman' => 'ti ti-credit-card'
        ];

        $title = $titles[$type] ?? 'Aktivitas Tukang';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'tukang', [
            'icon' => $icons[$type] ?? 'ti ti-hammer',
            'color' => 'warning',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Membuat notifikasi untuk jamaah
     */
    public static function jamaahNotification($data, $type = 'kehadiran', $jamaah_type = 'majlis_taklim')
    {
        $titles = [
            'kehadiran' => 'Kehadiran Jamaah',
            'distribusi_hadiah' => 'Distribusi Hadiah',
            'undian_umroh' => 'Undian Umroh'
        ];

        $title = $titles[$type] ?? 'Aktivitas Jamaah';
        $subtitle = $jamaah_type == 'majlis_taklim' ? 'Majlis Taklim' : 'Masar';
        
        return RealTimeNotification::createNotification($title . ' ' . $subtitle, $data['message'], 'jamaah', [
            'icon' => 'ti ti-users',
            'color' => 'success',
            'data' => array_merge($data, ['jamaah_type' => $jamaah_type]),
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Get statistik notifikasi hari ini
     */
    public static function getTodayStats()
    {
        $today = RealTimeNotification::today();
        
        return [
            'total' => $today->count(),
            'by_type' => $today->groupBy('type')
                ->selectRaw('type, count(*) as count')
                ->pluck('count', 'type')
                ->toArray(),
            'latest' => $today->latest()->first()
        ];
    }

    // === DETAILED TRACKING & MONITORING NOTIFICATIONS ===
    
    /**
     * Notifikasi untuk tracking presensi dengan detail lokasi dan waktu
     */
    public static function trackingPresensiNotification($data, $type = 'tracking')
    {
        $titles = [
            'lokasi_update' => 'Update Lokasi Presensi',
            'tracking_aktif' => 'Tracking Aktif',
            'area_masuk' => 'Masuk Area Kerja',
            'area_keluar' => 'Keluar Area Kerja',
            'lokasi_tidak_valid' => 'Lokasi Tidak Valid'
        ];

        $icons = [
            'lokasi_update' => 'ti ti-map-pin-check',
            'tracking_aktif' => 'ti ti-radar',
            'area_masuk' => 'ti ti-login',
            'area_keluar' => 'ti ti-logout',
            'lokasi_tidak_valid' => 'ti ti-map-pin-x'
        ];

        $colors = [
            'lokasi_update' => 'primary',
            'tracking_aktif' => 'success',
            'area_masuk' => 'info',
            'area_keluar' => 'warning',
            'lokasi_tidak_valid' => 'danger'
        ];

        $title = $titles[$type] ?? 'Aktivitas Tracking';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'tracking', [
            'icon' => $icons[$type] ?? 'ti ti-map-pin',
            'color' => $colors[$type] ?? 'primary',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Notifikasi untuk kunjungan dengan detail pengunjung dan tujuan
     */
    public static function kunjunganDetailNotification($data, $type = 'kunjungan')
    {
        $titles = [
            'kunjungan_baru' => 'Kunjungan Baru',
            'kunjungan_selesai' => 'Kunjungan Selesai',
            'tracking_kunjungan' => 'Tracking Kunjungan',
            'checkin' => 'Check-in Pengunjung',
            'checkout' => 'Check-out Pengunjung',
            'perpanjangan' => 'Perpanjangan Kunjungan'
        ];

        $icons = [
            'kunjungan_baru' => 'ti ti-user-plus',
            'kunjungan_selesai' => 'ti ti-user-check',
            'tracking_kunjungan' => 'ti ti-map-2',
            'checkin' => 'ti ti-login',
            'checkout' => 'ti ti-logout',
            'perpanjangan' => 'ti ti-clock-plus'
        ];

        $colors = [
            'kunjungan_baru' => 'success',
            'kunjungan_selesai' => 'primary',
            'tracking_kunjungan' => 'warning',
            'checkin' => 'info',
            'checkout' => 'secondary',
            'perpanjangan' => 'warning'
        ];

        $title = $titles[$type] ?? 'Aktivitas Kunjungan';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'kunjungan', [
            'icon' => $icons[$type] ?? 'ti ti-users',
            'color' => $colors[$type] ?? 'info',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    // === DETAILED FACILITY MANAGEMENT NOTIFICATIONS ===
    
    /**
     * Notifikasi untuk manajemen gedung dengan detail lokasi dan pemeliharaan
     */
    public static function gedungDetailNotification($data, $type = 'gedung')
    {
        $titles = [
            'gedung_baru' => 'Gedung Baru Ditambahkan',
            'ruangan_baru' => 'Ruangan Baru Ditambahkan',
            'pemeliharaan_terjadwal' => 'Pemeliharaan Terjadwal',
            'pemeliharaan_darurat' => 'Pemeliharaan Darurat',
            'pemeliharaan_selesai' => 'Pemeliharaan Selesai',
            'inspeksi_rutin' => 'Inspeksi Rutin Gedung',
            'kerusakan_dilaporkan' => 'Kerusakan Dilaporkan'
        ];

        $title = $titles[$type] ?? 'Aktivitas Gedung';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'gedung', [
            'icon' => 'ti ti-building',
            'color' => $type == 'pemeliharaan_darurat' ? 'danger' : ($type == 'gedung_baru' ? 'success' : 'info'),
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Notifikasi untuk pengunjung dengan detail waktu dan tujuan
     */
    public static function pengunjungDetailNotification($data, $type = 'pengunjung')
    {
        $titles = [
            'registrasi_baru' => 'Registrasi Pengunjung Baru',
            'checkin' => 'Check-in Pengunjung', 
            'checkout' => 'Check-out Pengunjung',
            'kunjungan_berulang' => 'Kunjungan Berulang',
            'waktu_habis' => 'Waktu Kunjungan Habis',
            'area_terlarang' => 'Akses Area Terlarang'
        ];

        $title = $titles[$type] ?? 'Aktivitas Pengunjung';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'pengunjung', [
            'icon' => 'ti ti-user-circle',
            'color' => $type == 'area_terlarang' ? 'danger' : ($type == 'registrasi_baru' ? 'success' : 'info'),
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    /**
     * Notifikasi untuk peralatan BS dengan detail stok dan peminjaman
     */
    public static function peralatanDetailNotification($data, $type = 'peralatan')
    {
        $titles = [
            'peralatan_baru' => 'Peralatan Baru Ditambahkan',
            'peminjaman_baru' => 'Peminjaman Peralatan',
            'pengembalian' => 'Pengembalian Peralatan',
            'stok_habis' => 'Stok Peralatan Habis',
            'stok_minimum' => 'Stok Mencapai Batas Minimum',
            'peralatan_rusak' => 'Peralatan Rusak',
            'maintenance_peralatan' => 'Maintenance Peralatan'
        ];

        $title = $titles[$type] ?? 'Aktivitas Peralatan';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'peralatan', [
            'icon' => 'ti ti-tools',
            'color' => $type == 'stok_habis' ? 'danger' : ($type == 'peminjaman_baru' ? 'warning' : 'success'),
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    // === DETAILED SANTRI MANAGEMENT NOTIFICATIONS ===
    
    /**
     * Notifikasi untuk santri dengan detail absensi, ijin, keuangan dan pelanggaran
     */
    public static function santriDetailNotification($data, $type = 'santri')
    {
        $titles = [
            'absensi_hadir' => 'Absensi Santri Hadir',
            'absensi_tidak_hadir' => 'Santri Tidak Hadir',
            'ijin_baru' => 'Ijin Santri Baru',
            'ijin_disetujui' => 'Ijin Santri Disetujui',
            'ijin_ditolak' => 'Ijin Santri Ditolak',
            'transaksi_keuangan' => 'Transaksi Keuangan Santri',
            'pelanggaran_baru' => 'Pelanggaran Santri',
            'sanksi_diberikan' => 'Sanksi Pelanggaran',
            'khidmat_baru' => 'Khidmat Belanja Masak',
            'jadwal_update' => 'Update Jadwal Santri'
        ];

        $icons = [
            'absensi_hadir' => 'ti ti-user-check',
            'absensi_tidak_hadir' => 'ti ti-user-x',
            'ijin_baru' => 'ti ti-file-description',
            'ijin_disetujui' => 'ti ti-check',
            'ijin_ditolak' => 'ti ti-x',
            'transaksi_keuangan' => 'ti ti-currency-dollar',
            'pelanggaran_baru' => 'ti ti-alert-circle',
            'sanksi_diberikan' => 'ti ti-gavel',
            'khidmat_baru' => 'ti ti-shopping-cart',
            'jadwal_update' => 'ti ti-calendar'
        ];

        $colors = [
            'absensi_hadir' => 'success',
            'absensi_tidak_hadir' => 'danger',
            'ijin_baru' => 'warning',
            'ijin_disetujui' => 'success',
            'ijin_ditolak' => 'danger',
            'transaksi_keuangan' => 'info',
            'pelanggaran_baru' => 'danger',
            'sanksi_diberikan' => 'warning',
            'khidmat_baru' => 'primary',
            'jadwal_update' => 'info'
        ];

        $title = $titles[$type] ?? 'Aktivitas Santri';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'santri', [
            'icon' => $icons[$type] ?? 'ti ti-school',
            'color' => $colors[$type] ?? 'info',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    // === DETAILED YAYASAN NOTIFICATIONS ===
    
    /**
     * Notifikasi untuk yayasan dengan detail distribusi hadiah dan kehadiran
     */
    public static function yayasanDetailNotification($data, $type = 'yayasan', $organisasi = 'majlistaklim')
    {
        $titles = [
            'distribusi_hadiah' => 'Distribusi Hadiah',
            'kehadiran_jamaah' => 'Kehadiran Jamaah',
            'jamaah_baru' => 'Jamaah Baru',
            'undian_umroh' => 'Undian Umroh',
            'kegiatan_rutin' => 'Kegiatan Rutin',
            'donasi_masuk' => 'Donasi Masuk',
            'laporan_keuangan' => 'Laporan Keuangan'
        ];

        $organisasi_name = $organisasi == 'majlistaklim' ? 'Majlis Ta\'lim Al-Ikhlas' : 'MASAR';
        $title = $titles[$type] ?? 'Aktivitas Yayasan';
        $full_title = $title . ' ' . $organisasi_name;
        
        return RealTimeNotification::createNotification($full_title, $data['message'], 'yayasan', [
            'icon' => 'ti ti-building-arch',
            'color' => $type == 'distribusi_hadiah' ? 'success' : 'primary',
            'data' => array_merge($data, ['organisasi' => $organisasi]),
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    // === DETAILED TUKANG NOTIFICATIONS ===
    
    /**
     * Notifikasi untuk tukang dengan detail kehadiran dan pembayaran
     */
    public static function tukangDetailNotification($data, $type = 'tukang')
    {
        $titles = [
            'kehadiran_masuk' => 'Tukang Masuk Kerja',
            'kehadiran_pulang' => 'Tukang Pulang Kerja',
            'lembur_mulai' => 'Tukang Mulai Lembur',
            'lembur_selesai' => 'Lembur Tukang Selesai',
            'pembayaran_upah' => 'Pembayaran Upah Tukang',
            'cash_lembur' => 'Cash Lembur Tukang',
            'tukang_baru' => 'Tukang Baru Terdaftar',
            'pinjaman_tukang' => 'Pinjaman Tukang',
            'alpha' => 'Tukang Alpha',
            'cuti' => 'Tukang Cuti'
        ];

        $icons = [
            'kehadiran_masuk' => 'ti ti-login',
            'kehadiran_pulang' => 'ti ti-logout',
            'lembur_mulai' => 'ti ti-clock-plus',
            'lembur_selesai' => 'ti ti-clock-check',
            'pembayaran_upah' => 'ti ti-cash',
            'cash_lembur' => 'ti ti-coins',
            'tukang_baru' => 'ti ti-user-plus',
            'pinjaman_tukang' => 'ti ti-credit-card',
            'alpha' => 'ti ti-user-x',
            'cuti' => 'ti ti-calendar-off'
        ];

        $colors = [
            'kehadiran_masuk' => 'success',
            'kehadiran_pulang' => 'primary',
            'lembur_mulai' => 'warning',
            'lembur_selesai' => 'success',
            'pembayaran_upah' => 'success',
            'cash_lembur' => 'warning',
            'tukang_baru' => 'success',
            'pinjaman_tukang' => 'info',
            'alpha' => 'danger',
            'cuti' => 'secondary'
        ];

        $title = $titles[$type] ?? 'Aktivitas Tukang';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'tukang', [
            'icon' => $icons[$type] ?? 'ti ti-tool',
            'color' => $colors[$type] ?? 'info',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }

    // === DETAILED MAINTENANCE NOTIFICATIONS ===
    
    /**
     * Notifikasi untuk perawatan dengan detail biaya dan status
     */
    public static function perawatanDetailNotification($data, $type = 'perawatan')
    {
        $titles = [
            'perawatan_terjadwal' => 'Perawatan Terjadwal',
            'perawatan_darurat' => 'Perawatan Darurat',
            'perawatan_selesai' => 'Perawatan Selesai',
            'permintaan_perawatan' => 'Permintaan Perawatan',
            'persetujuan_perawatan' => 'Persetujuan Perawatan',
            'penolakan_perawatan' => 'Penolakan Perawatan',
            'monitoring_perawatan' => 'Monitoring Perawatan',
            'laporan_perawatan' => 'Laporan Perawatan'
        ];

        $icons = [
            'perawatan_terjadwal' => 'ti ti-calendar-event',
            'perawatan_darurat' => 'ti ti-alert-triangle',
            'perawatan_selesai' => 'ti ti-check',
            'permintaan_perawatan' => 'ti ti-file-plus',
            'persetujuan_perawatan' => 'ti ti-check-circle',
            'penolakan_perawatan' => 'ti ti-x-circle',
            'monitoring_perawatan' => 'ti ti-eye',
            'laporan_perawatan' => 'ti ti-report'
        ];

        $colors = [
            'perawatan_terjadwal' => 'warning',
            'perawatan_darurat' => 'danger',
            'perawatan_selesai' => 'success',
            'permintaan_perawatan' => 'info',
            'persetujuan_perawatan' => 'success',
            'penolakan_perawatan' => 'danger',
            'monitoring_perawatan' => 'primary',
            'laporan_perawatan' => 'secondary'
        ];

        $title = $titles[$type] ?? 'Aktivitas Perawatan';
        
        return RealTimeNotification::createNotification($title, $data['message'], 'perawatan', [
            'icon' => $icons[$type] ?? 'ti ti-building-warehouse',
            'color' => $colors[$type] ?? 'info',
            'data' => $data,
            'reference_id' => $data['reference_id'] ?? null,
            'reference_table' => $data['reference_table'] ?? null
        ]);
    }
}