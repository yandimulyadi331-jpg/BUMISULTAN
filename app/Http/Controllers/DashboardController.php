<?php

namespace App\Http\Controllers;

use App\Charts\JeniskelaminkaryawanChart;
use App\Charts\PendidikankaryawanChart;
use App\Charts\StatusKaryawanChart;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Userkaryawan;
use App\Models\KpiCrew;
use App\Jobs\SendWaMessage;
use App\Models\AktivitasKendaraan;
use App\Models\KendaraanPeminjaman;
use App\Models\TugasLuar;
use App\Models\Pinjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use PDF;

class DashboardController extends Controller
{
    public function index(StatusKaryawanChart $chart, JeniskelaminkaryawanChart $jkchart, PendidikankaryawanChart $pddchart, Request $request)
    {
        $agent = new Agent();
        $user = User::where('id', auth()->user()->id)->first();
        $hari_ini = date("Y-m-d");
        if ($user && $user->hasRole('karyawan')) {
            $userkaryawan = Userkaryawan::where('id_user', auth()->user()->id)->first();
            
            // Check if userkaryawan exists
            if (!$userkaryawan) {
                return redirect()->back()->with('error', 'Data karyawan tidak ditemukan');
            }
            
            $data['karyawan'] = Karyawan::where('nik', $userkaryawan->nik)
                ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->first();

            $data['presensi'] = Presensi::where('presensi.nik', $userkaryawan->nik)->where('presensi.tanggal', $hari_ini)->first();
            $data['datapresensi'] = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('presensi.nik', $userkaryawan->nik)
                ->leftJoin('presensi_izinabsen_approve', 'presensi.id', '=', 'presensi_izinabsen_approve.id_presensi')
                ->leftJoin('presensi_izinabsen', 'presensi_izinabsen_approve.kode_izin', '=', 'presensi_izinabsen.kode_izin')

                ->leftJoin('presensi_izinsakit_approve', 'presensi.id', '=', 'presensi_izinsakit_approve.id_presensi')
                ->leftJoin('presensi_izinsakit', 'presensi_izinsakit_approve.kode_izin_sakit', '=', 'presensi_izinsakit.kode_izin_sakit')

                ->leftJoin('presensi_izincuti_approve', 'presensi.id', '=', 'presensi_izincuti_approve.id_presensi')
                ->leftJoin('presensi_izincuti', 'presensi_izincuti_approve.kode_izin_cuti', '=', 'presensi_izincuti.kode_izin_cuti')
                ->select(
                    'presensi.*',
                    'presensi_jamkerja.nama_jam_kerja',
                    'presensi_jamkerja.jam_masuk',
                    'presensi_jamkerja.jam_pulang',
                    'presensi_jamkerja.total_jam',
                    'presensi_jamkerja.lintashari',
                    'presensi_izinabsen.keterangan as keterangan_izin',
                    'presensi_izinsakit.keterangan as keterangan_izin_sakit',
                    'presensi_izincuti.keterangan as keterangan_izin_cuti',
                )
                ->orderBy('tanggal', 'desc')
                ->limit(30)
                ->get();
            $data['rekappresensi'] = Presensi::select(
                DB::raw("SUM(IF(status='h',1,0)) as hadir"),
                DB::raw("SUM(IF(status='i',1,0)) as izin"),
                DB::raw("SUM(IF(status='s',1,0)) as sakit"),
                DB::raw("SUM(IF(status='a',1,0)) as alpa"),
                DB::raw("SUM(IF(status='c',1,0)) as cuti")
            )
                ->groupBy('presensi.nik')
                ->whereRaw('MONTH(presensi.tanggal) = MONTH(?)', [$hari_ini])
                ->whereRaw('YEAR(presensi.tanggal) = YEAR(?)', [$hari_ini])
                ->where('presensi.nik', $userkaryawan->nik)
                ->first();

            $data['lembur'] = Lembur::where('nik', $userkaryawan->nik)->where('status', 1)
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
            $data['notiflembur'] = Lembur::where('nik', $userkaryawan->nik)
                ->where('status', 1)
                ->where('lembur_in', null)
                ->orWhere('lembur_out', null)
                ->where('status', 1)
                ->count();
            
            // Ambil data KPI bulan ini untuk ditampilkan di dashboard karyawan
            $bulan = date('n');
            $tahun = date('Y');
            $data['kpiData'] = KpiCrew::with(['karyawan' => function($query) {
                    $query->select('nik', 'nama_karyawan', 'kode_dept', 'kode_cabang');
                }])
                ->whereHas('karyawan')
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->orderBy('ranking', 'asc')
                ->get();
            
            return view('dashboard.karyawan', $data);
        } else {

            //Dashboard Admin
            $sk = new Karyawan();
            $data['status_karyawan'] = $sk->getRekapstatuskaryawan($request);
            $data['chart'] = $chart->build($request);
            $data['jkchart'] = $jkchart->build($request);
            $data['pddchart'] = $pddchart->build($request);

            $queryPresensi = Presensi::query();
            $queryPresensi->join('karyawan', 'presensi.nik', '=', 'karyawan.nik');
            $queryPresensi->select(
                DB::raw("SUM(IF(status='h',1,0)) as hadir"),
                DB::raw("SUM(IF(status='i',1,0)) as izin"),
                DB::raw("SUM(IF(status='s',1,0)) as sakit"),
                DB::raw("SUM(IF(status='a',1,0)) as alpa"),
                DB::raw("SUM(IF(status='c',1,0)) as cuti")
            );
            if (!empty($request->tanggal)) {
                $queryPresensi->where('tanggal', $request->tanggal);
            } else {
                $queryPresensi->where('tanggal', date('Y-m-d'));
            }

            if (!empty($request->kode_cabang)) {
                $queryPresensi->where('karyawan.kode_cabang', $request->kode_cabang);
            }

            if (!empty($request->kode_dept)) {
                $queryPresensi->where('karyawan.kode_dept', $request->kode_dept);
            }
            $data['rekappresensi'] = $queryPresensi->first();
            
            // Query untuk mengambil nama karyawan berdasarkan status
            $tanggal = !empty($request->tanggal) ? $request->tanggal : date('Y-m-d');
            
            // Karyawan Hadir
            $karyawanHadirQuery = Presensi::join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->where('presensi.tanggal', $tanggal)
                ->where('presensi.status', 'h');
            if (!empty($request->kode_cabang)) {
                $karyawanHadirQuery->where('karyawan.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $karyawanHadirQuery->where('karyawan.kode_dept', $request->kode_dept);
            }
            $data['nama_karyawan_hadir'] = $karyawanHadirQuery->pluck('karyawan.nama_karyawan')->toArray();
            
            // Karyawan Izin
            $karyawanIzinQuery = Presensi::join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->where('presensi.tanggal', $tanggal)
                ->where('presensi.status', 'i');
            if (!empty($request->kode_cabang)) {
                $karyawanIzinQuery->where('karyawan.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $karyawanIzinQuery->where('karyawan.kode_dept', $request->kode_dept);
            }
            $data['nama_karyawan_izin'] = $karyawanIzinQuery->pluck('karyawan.nama_karyawan')->toArray();
            
            // Karyawan Sakit
            $karyawanSakitQuery = Presensi::join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->where('presensi.tanggal', $tanggal)
                ->where('presensi.status', 's');
            if (!empty($request->kode_cabang)) {
                $karyawanSakitQuery->where('karyawan.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $karyawanSakitQuery->where('karyawan.kode_dept', $request->kode_dept);
            }
            $data['nama_karyawan_sakit'] = $karyawanSakitQuery->pluck('karyawan.nama_karyawan')->toArray();
            
            // Karyawan Cuti
            $karyawanCutiQuery = Presensi::join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
                ->where('presensi.tanggal', $tanggal)
                ->where('presensi.status', 'c');
            if (!empty($request->kode_cabang)) {
                $karyawanCutiQuery->where('karyawan.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $karyawanCutiQuery->where('karyawan.kode_dept', $request->kode_dept);
            }
            $data['nama_karyawan_cuti'] = $karyawanCutiQuery->pluck('karyawan.nama_karyawan')->toArray();
            
            // Query untuk mengambil data karyawan yang hadir hari ini
            $queryKaryawanHadir = Presensi::query();
            $queryKaryawanHadir->join('karyawan', 'presensi.nik', '=', 'karyawan.nik');
            $queryKaryawanHadir->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
            $queryKaryawanHadir->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
            $queryKaryawanHadir->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');
            $queryKaryawanHadir->leftJoin('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja');
            
            $queryKaryawanHadir->select(
                'presensi.*',
                'karyawan.nama_karyawan',
                'karyawan.foto',
                'jabatan.nama_jabatan',
                'departemen.nama_dept',
                'cabang.nama_cabang',
                'presensi_jamkerja.nama_jam_kerja',
                'presensi_jamkerja.jam_masuk as jam_kerja_masuk',
                'presensi_jamkerja.jam_pulang as jam_kerja_pulang'
            );
            
            if (!empty($request->tanggal)) {
                $queryKaryawanHadir->where('presensi.tanggal', $request->tanggal);
            } else {
                $queryKaryawanHadir->where('presensi.tanggal', date('Y-m-d'));
            }

            if (!empty($request->kode_cabang)) {
                $queryKaryawanHadir->where('karyawan.kode_cabang', $request->kode_cabang);
            }

            if (!empty($request->kode_dept)) {
                $queryKaryawanHadir->where('karyawan.kode_dept', $request->kode_dept);
            }
            
            $queryKaryawanHadir->where('presensi.status', 'h'); // h = hadir
            $queryKaryawanHadir->orderBy('presensi.jam_in', 'asc');
            
            $data['karyawan_hadir'] = $queryKaryawanHadir->get();
            
            // Query untuk kendaraan yang sedang keluar (aktivitas yang belum kembali)
            $kendaraanKeluarData = AktivitasKendaraan::with(['kendaraan'])
                ->where('status', 'keluar')
                ->whereNull('waktu_kembali')
                ->orderBy('waktu_keluar', 'desc')
                ->get();
            
            $data['kendaraan_keluar'] = $kendaraanKeluarData;
            $data['jumlah_kendaraan_keluar'] = $kendaraanKeluarData->count();
            
            // Query untuk kendaraan yang sedang dipinjam (status disetujui dan belum dikembalikan)
            $kendaraanDipinjamData = KendaraanPeminjaman::with(['kendaraan', 'karyawan'])
                ->where('status_pengajuan', 'Disetujui')
                ->whereNotNull('waktu_ambil')
                ->whereNull('waktu_kembali_actual')
                ->orderBy('tanggal_pinjam', 'desc')
                ->get();
            
            $data['kendaraan_dipinjam'] = $kendaraanDipinjamData;
            $data['jumlah_kendaraan_dipinjam'] = $kendaraanDipinjamData->count();
            
            // Query untuk karyawan tugas luar hari ini
            $tugasLuarData = TugasLuar::whereDate('tanggal', date('Y-m-d'))
                ->where('status', 'keluar')
                ->orderBy('waktu_keluar', 'desc')
                ->get();
            
            $data['tugas_luar'] = $tugasLuarData;
            $data['jumlah_tugas_luar'] = $tugasLuarData->count();
            
            $data['departemen'] = Departemen::orderBy('kode_dept')->get();
            $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
            $data['birthday'] = Karyawan::whereMonth('tanggal_lahir', date('m'))->whereDay('tanggal_lahir', date('d'))
                ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->select(
                    'karyawan.*',
                    'jabatan.nama_jabatan',
                    'departemen.nama_dept',
                    'cabang.nama_cabang',
                    'karyawan.status_karyawan'
                )
                ->when($request->kode_cabang, function ($query) use ($request) {
                    $query->where('karyawan.kode_cabang', $request->kode_cabang);
                })
                ->when($request->kode_dept, function ($query) use ($request) {
                    $query->where('karyawan.kode_dept', $request->kode_dept);
                })
                ->orderBy('tanggal_lahir', 'asc')->get();
            
            // Get Top 10 KPI Crew bulan ini
            $data['topKpiCrew'] = \App\Models\KpiCrew::with(['karyawan' => function($query) {
                    $query->select('nik', 'nama_karyawan', 'kode_jabatan')
                        ->with(['jabatan' => function($q) {
                            $q->select('kode_jabatan', 'nama_jabatan');
                        }]);
                }])
                ->where('bulan', date('n')) // bulan saat ini (1-12)
                ->where('tahun', date('Y'))
                ->orderBy('total_point', 'desc')
                ->limit(10)
                ->get();
            
            // Ambil pinjaman yang sudah jatuh tempo
            $data['pinjamanJatuhTempo'] = Pinjaman::jatuhTempo()
                ->with(['karyawan', 'cicilan'])
                ->orderBy('tanggal_pencairan', 'asc')
                ->get()
                ->map(function($pinjaman) {
                    $cicilanJatuhTempo = $pinjaman->getCicilanPertamaJatuhTempo();
                    return [
                        'id' => $pinjaman->id,
                        'nomor_pinjaman' => $pinjaman->nomor_pinjaman,
                        'nama_peminjam' => $pinjaman->nama_peminjam_lengkap,
                        'no_hp' => $pinjaman->no_telp_peminjam,
                        'karyawan_id' => $pinjaman->karyawan_id,
                        'kategori_peminjam' => $pinjaman->kategori_peminjam,
                        'total_pinjaman' => $pinjaman->total_pinjaman,
                        'sisa_pinjaman' => $pinjaman->sisa_pinjaman,
                        'persentase_pembayaran' => $pinjaman->persentase_pembayaran,
                        'cicilan_ke' => $cicilanJatuhTempo?->cicilan_ke,
                        'tanggal_jatuh_tempo' => $cicilanJatuhTempo?->tanggal_jatuh_tempo,
                        'jumlah_cicilan' => $cicilanJatuhTempo?->jumlah_cicilan,
                        'hari_tertunda' => $pinjaman->hari_tertunda,
                        'model' => $pinjaman,
                    ];
                });
            
            // dd($data['rekappresensi']);
            return view('dashboard.dashboard', $data);
        }
    }

    /**
     * Kirim penagihan pinjaman jatuh tempo via WhatsApp
     */
    public function kirimPenangihanPinjaman(Request $request)
    {
        try {
            $pinjamanId = $request->input('pinjaman_id');
            $pinjaman = Pinjaman::findOrFail($pinjamanId);

            // Validasi pinjaman yang akan dikirim notifikasi
            if (!$pinjaman->isJatuhTempo()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman ini tidak jatuh tempo atau sudah lunas.'
                ], 400);
            }

            // Validasi nomor HP
            if (empty($pinjaman->no_telp_peminjam)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon peminjam tidak tersedia.'
                ], 400);
            }

            // Ambil data cicilan pertama yang jatuh tempo
            $cicilanJatuhTempo = $pinjaman->getCicilanPertamaJatuhTempo();
            $hariTertunda = $pinjaman->hari_tertunda;

            // Format nomor HP
            $phoneNumber = $pinjaman->no_telp_peminjam;
            $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
            if (!str_starts_with($phoneNumber, '62')) {
                $phoneNumber = '62' . $phoneNumber;
            }

            // Buat template pesan penagihan profesional sesuai konteks Bumisultan
            $namaPeminjam = $pinjaman->nama_peminjam_lengkap;
            $nomorPinjaman = $pinjaman->nomor_pinjaman;
            $nominalCicilan = number_format($cicilanJatuhTempo->jumlah_cicilan, 0, ',', '.');
            $sisaPinjaman = number_format($pinjaman->sisa_pinjaman, 0, ',', '.');
            $tglJatuhTempo = \Carbon\Carbon::parse($cicilanJatuhTempo->tanggal_jatuh_tempo)->format('d-m-Y');
            
            $pesan = "💼 *NOTIFIKASI PENAGIHAN PINJAMAN* 💼\n";
            $pesan .= "_Dari: Manajemen Keuangan Bumi Sultan Properti_\n\n";
            
            $pesan .= "👤 *DATA PEMINJAM:*\n";
            $pesan .= "Nama: *{$namaPeminjam}*\n";
            $pesan .= "No. Pinjaman: *{$nomorPinjaman}*\n\n";
            
            $pesan .= "📊 *DETAIL CICILAN:*\n";
            $pesan .= "Cicilan Ke: *{$cicilanJatuhTempo->cicilan_ke}*\n";
            $pesan .= "Nominal Cicilan: *Rp {$nominalCicilan}*\n";
            $pesan .= "Tgl Jatuh Tempo: *{$tglJatuhTempo}*\n";
            $pesan .= "Status: *⚠️ TERTUNDA {$hariTertunda} HARI*\n\n";
            
            $pesan .= "💰 *RINGKASAN PINJAMAN:*\n";
            $pesan .= "Total Pinjaman: *Rp " . number_format($pinjaman->total_pinjaman, 0, ',', '.') . "*\n";
            $pesan .= "Sisa Pinjaman: *Rp {$sisaPinjaman}*\n";
            $pesan .= "Terbayar: " . $pinjaman->persentase_pembayaran . "% ✅\n\n";
            
            $pesan .= "⚠️ *TINDAKAN YANG DIPERLUKAN:*\n";
            $pesan .= "Kami dengan hormat meminta Bapak/Ibu segera melakukan pembayaran cicilan di atas sesuai jadwal yang telah disepakati.\n\n";
            
            $pesan .= "📝 *INFORMASI PEMBAYARAN:*\n";
            $pesan .= "• Pembayaran dapat dilakukan melalui transfer bank ke rekening yang terdaftar\n";
            $pesan .= "• Detail rekening tujuan tersedia dalam dokumen pinjaman (file PDF terlampir)\n";
            $pesan .= "• Jika ada kendala pembayaran, silakan hubungi bagian keuangan\n";
            $pesan .= "• Ketertundaan pembayaran dapat mempengaruhi catatan kredit Anda\n\n";
            
            $pesan .= "📎 *LAMPIRAN DOKUMEN:*\n";
            $pesan .= "File PDF detail pinjaman sudah kami sertakan untuk referensi Anda.\n\n";
            
            $pesan .= "Terima kasih atas perhatian dan kerjasama Anda.\n\n";
            $pesan .= "*Regards,*\n";
            $pesan .= "Tim Manajemen Keuangan\n";
            $pesan .= "🏢 Bumi Sultan Properti";

            // Generate PDF pinjaman untuk dikirim sebagai attachment
            try {
                $pinjaman->load(['karyawan', 'pengaju', 'penyetuju', 'cicilan']);
                $pdf = PDF::loadView('pinjaman.formulir-pdf', compact('pinjaman'))
                    ->setPaper('a4', 'portrait');
                
                // Simpan PDF ke temporary folder
                $pdfPath = storage_path('app/temp/' . 'Pinjaman_' . $pinjaman->nomor_pinjaman . '_' . time() . '.pdf');
                @mkdir(dirname($pdfPath), 0755, true);
                $pdf->save($pdfPath);

                // TODO: Kirim pesan dengan PDF attachment jika WA gateway support
                // Untuk sekarang, kirim pesan terlebih dahulu
                SendWaMessage::dispatch($phoneNumber, $pesan, false);

                // Cleanup PDF file setelah beberapa detik
                if (file_exists($pdfPath)) {
                    @unlink($pdfPath);
                }
            } catch (\Exception $e) {
                // Jika PDF gagal di-generate, tetap kirim pesan WhatsApp
                \Illuminate\Support\Facades\Log::warning('Gagal generate PDF untuk pinjaman: ' . $e->getMessage());
                SendWaMessage::dispatch($phoneNumber, $pesan, false);
            }

            // Log history pengiriman notifikasi
            if (!empty($pinjaman->karyawan_id)) {
                $pinjaman->logHistory(
                    'kirim_notifikasi_wa_tertunda',
                    'notifikasi_pending',
                    'notifikasi_terkirim',
                    "Penagihan WhatsApp terkirim untuk cicilan ke-{$cicilanJatuhTempo->cicilan_ke}",
                    json_encode([
                        'no_telp' => $phoneNumber,
                        'cicilan_ke' => $cicilanJatuhTempo->cicilan_ke,
                        'nominal' => $cicilanJatuhTempo->jumlah_cicilan,
                        'hari_tertunda' => $hariTertunda,
                        'dengan_pdf' => true,
                    ])
                );
            }

            return response()->json([
                'success' => true,
                'message' => "Pesan penagihan berhasil dikirim ke {$namaPeminjam}."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil template pesan penagihan untuk dibuka via WhatsApp Web
     * Endpoint ini hanya mengembalikan pesan tanpa mengirim
     */
    public function getPesanPenagihan(Request $request)
    {
        try {
            $pinjamanId = $request->input('pinjaman_id');
            $pinjaman = Pinjaman::findOrFail($pinjamanId);

            // Validasi pinjaman yang akan dikirim notifikasi
            if (!$pinjaman->isJatuhTempo()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman ini tidak jatuh tempo atau sudah lunas.'
                ], 400);
            }

            // Validasi nomor HP
            if (empty($pinjaman->no_telp_peminjam)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon peminjam tidak tersedia.'
                ], 400);
            }

            // Ambil data cicilan pertama yang jatuh tempo
            $cicilanJatuhTempo = $pinjaman->getCicilanPertamaJatuhTempo();
            $hariTertunda = $pinjaman->hari_tertunda;

            // Format nomor HP
            $phoneNumber = $pinjaman->no_telp_peminjam;
            $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
            if (!str_starts_with($phoneNumber, '62')) {
                $phoneNumber = '62' . $phoneNumber;
            }

            // Buat template pesan penagihan profesional format surat tagihan (clean & simple)
            $namaPeminjam = $pinjaman->nama_peminjam_lengkap;
            $nomorPinjaman = $pinjaman->nomor_pinjaman;
            $nomorSurat = 'BS/KEU/' . str_pad(date('m'), 2, '0', STR_PAD_LEFT) . '/' . date('Y');
            $nominalCicilan = number_format($cicilanJatuhTempo->jumlah_cicilan, 0, ',', '.');
            $sisaPinjaman = number_format($pinjaman->sisa_pinjaman, 0, ',', '.');
            $totalPinjaman = number_format($pinjaman->total_pinjaman, 0, ',', '.');
            $cicilanPerBulan = number_format($pinjaman->cicilan_per_bulan, 0, ',', '.');
            $tglJatuhTempo = \Carbon\Carbon::parse($cicilanJatuhTempo->tanggal_jatuh_tempo)->format('d-m-Y');
            $tglPencairan = \Carbon\Carbon::parse($pinjaman->tanggal_pencairan)->format('d-m-Y');
            $hariTertunda = $pinjaman->hari_tertunda;
            
            $pesan = "*SURAT TAGIHAN KEUANGAN*\n";
            $pesan .= "*BUMI SULTAN*\n\n";
            
            $pesan .= "*INFORMASI SURAT*\n";
            $pesan .= "Nomor Surat : {$nomorSurat}\n";
            $pesan .= "Perihal : Pemberitahuan & Penagihan Kewajiban Pembayaran\n";
            $pesan .= "Tanggal : " . date('d-m-Y') . "\n\n";
            
            $pesan .= "Kepada Yth,\n\n";
            
            $pesan .= "*DATA PEMINJAM*\n";
            $pesan .= "Nama : {$namaPeminjam}\n";
            $pesan .= "No. Identitas : {$pinjaman->nik_peminjam}\n";
            $pesan .= "No. Telp/WA : {$pinjaman->no_telp_peminjam}\n";
            $pesan .= "Alamat : {$pinjaman->alamat_peminjam}\n\n";
            
            $pesan .= "*Dengan hormat,*\n\n";
            
            $pesan .= "Berdasarkan data administrasi keuangan BUMI SULTAN, bersama ini kami sampaikan bahwa Saudara/i memiliki KEWAJIBAN PEMBAYARAN PINJAMAN dengan rincian sebagai berikut:\n\n";
            
            $pesan .= "*DATA PINJAMAN*\n";
            $pesan .= "Nomor Pinjaman : {$nomorPinjaman}\n";
            $pesan .= "Tanggal Pencairan : {$tglPencairan}\n";
            $pesan .= "Jumlah Pinjaman Pokok : Rp {$totalPinjaman}\n";
            $pesan .= "Tenor Pinjaman : {$pinjaman->tenor_bulan} Bulan\n";
            $pesan .= "Cicilan per Bulan : Rp {$cicilanPerBulan}\n";
            $pesan .= "Sisa Pokok Pinjaman : Rp {$sisaPinjaman}\n";
            $pesan .= "Terbayar : {$pinjaman->persentase_pembayaran}%\n\n";
            
            $pesan .= "*STATUS PEMBAYARAN*\n";
            $pesan .= "Cicilan Ke : {$cicilanJatuhTempo->cicilan_ke}\n";
            $pesan .= "Tanggal Jatuh Tempo : {$tglJatuhTempo}\n";
            $pesan .= "Status : *TERTUNGGAK* ({$hariTertunda} hari)\n";
            $pesan .= "Nominal Cicilan : Rp {$nominalCicilan}\n\n";
            
            $pesan .= "Sehubungan dengan hal tersebut, kami mohon agar Saudara/i *SEGERA MELAKUKAN PEMBAYARAN* sesuai kewajiban yang telah disepakati, paling lambat pada tanggal jatuh tempo tersebut.\n\n";
            
            $pesan .= "*METODE PEMBAYARAN*\n";
            $pesan .= "Pembayaran dapat dilakukan melalui Transfer Bank:\n\n";
            $pesan .= "*BANK BCA*\n";
            $pesan .= "Atas Nama : YANDI MULYADI\n";
            $pesan .= "No. Rekening : 4061932571\n\n";
            
            $pesan .= "Mohon setelah melakukan pembayaran agar mengirimkan bukti transfer kepada bagian keuangan BUMI SULTAN.\n\n";
            
            $pesan .= "*PENTING*\n";
            $pesan .= "Ketertundaan pembayaran dapat mempengaruhi catatan kredit Anda.\n\n";
            
            $pesan .= "*PENUTUP*\n\n";
            $pesan .= "Demikian surat pemberitahuan dan penagihan ini kami sampaikan. Atas perhatian dan kerja sama Saudara/i, kami ucapkan terima kasih.\n\n";
            
            $pesan .= "*Hormat kami,*\n\n";
            $pesan .= "BUMI SULTAN\n";
            $pesan .= "Bagian Keuangan\n\n";
            
            $pesan .= "Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol,\n";
            $pesan .= "Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830\n";
            
            return response()->json([
                'success' => true,
                'pesan' => $pesan,
                'no_telp' => $phoneNumber,
                'nama_peminjam' => $namaPeminjam,
                'nomor_pinjaman' => $nomorPinjaman,
                'cicilan_ke' => $cicilanJatuhTempo->cicilan_ke,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kirimUcapanBirthday(Request $request)
    {
        try {
            // Ambil karyawan yang ulang tahun hari ini
            $birthday = Karyawan::whereMonth('tanggal_lahir', date('m'))
                ->whereDay('tanggal_lahir', date('d'))
                ->when($request->kode_cabang, function ($query) use ($request) {
                    $query->where('kode_cabang', $request->kode_cabang);
                })
                ->when($request->kode_dept, function ($query) use ($request) {
                    $query->where('kode_dept', $request->kode_dept);
                })
                ->whereNotNull('no_hp')
                ->where('no_hp', '!=', '')
                ->get();

            if ($birthday->count() == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada karyawan yang ulang tahun hari ini atau tidak ada nomor HP yang tersedia.'
                ], 400);
            }

            $count = 0;
            foreach ($birthday as $karyawan) {
                // Hitung umur
                $umur = Carbon::parse($karyawan->tanggal_lahir)->age;

                // Format pesan ucapan ulang tahun
                $message = "🎉 *Selamat Ulang Tahun!* 🎂\n\n";
                $message .= "Halo *{$karyawan->nama_karyawan}*,\n\n";
                $message .= "Di hari yang istimewa ini, kami ingin mengucapkan:\n\n";
                $message .= "🎂 *Selamat Ulang Tahun yang ke-{$umur}!* 🎂\n\n";
                $message .= "Semoga di hari ulang tahunmu ini:\n";
                $message .= "✨ Panjang umur\n";
                $message .= "✨ Sehat selalu\n";
                $message .= "✨ Bahagia selalu\n";
                $message .= "✨ Sukses dalam karir\n";
                $message .= "✨ Diberkahi rezeki yang berlimpah\n\n";
                $message .= "Terima kasih atas dedikasi dan kontribusinya selama ini. Semoga hubungan kerja kita terus berjalan dengan baik!\n\n";
                $message .= "*Salam Hangat,*\nTim HR";

                // Format nomor HP (hapus 0 di depan jika ada, pastikan format 62xxx)
                $phoneNumber = $karyawan->no_hp;
                $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
                if (!str_starts_with($phoneNumber, '62')) {
                    $phoneNumber = '62' . $phoneNumber;
                }

                // Dispatch job untuk mengirim WhatsApp
                SendWaMessage::dispatch($phoneNumber, $message, true);
                $count++;
            }

            return response()->json([
                'success' => true,
                'message' => "Ucapan ulang tahun sedang dikirim ke {$count} karyawan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
