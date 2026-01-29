<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterPerawatan;
use App\Models\PerawatanLog;
use App\Models\Presensi;
use App\Models\JadwalPiket;
use App\Models\JadwalPiketKaryawan;
use App\Services\JadwalPiketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChecklistController extends Controller
{
    protected $jadwalPiketService;

    public function __construct()
    {
        $this->jadwalPiketService = new JadwalPiketService();
    }
    /**
     * Check checklist status untuk karyawan hari ini
     */
    public function checkStatus(Request $request)
    {
        $user = Auth::user();
        $date = $request->input('date', now()->format('Y-m-d'));

        // Get NIK dari user melalui relasi userkaryawan
        $userkaryawan = $user->userkaryawan;
        if (!$userkaryawan) {
            return response()->json([
                'hasIncompleteChecklist' => false,
                'shouldShowModal' => false,
                'message' => 'User bukan karyawan'
            ]);
        }

        $nik = $userkaryawan->nik;

        // Get today's presensi to know the shift
        $presensiToday = Presensi::where('nik', $nik)
            ->where('tanggal', $date)
            ->first();

        if (!$presensiToday) {
            // Jika belum ada presensi hari ini, tidak perlu checklist
            return response()->json([
                'hasIncompleteChecklist' => false,
                'shouldShowModal' => false,
                'message' => 'Belum ada presensi hari ini'
            ]);
        }

        // Check jam_out - jika sudah ada jam_out (sudah absen pulang), jangan tampilkan modal
        if ($presensiToday->jam_out != null) {
            return response()->json([
                'hasIncompleteChecklist' => false,
                'shouldShowModal' => false,
                'message' => 'Sudah absen pulang'
            ]);
        }

        // Generate periode key untuk harian (YYYY-MM-DD format)
        $periodeKey = 'harian_' . $date;

        // Get kode_jam_kerja dari presensi
        $kodeJamKerja = $presensiToday->kode_jam_kerja;

        // Get all master checklist harian yang aktif DAN sesuai dengan jam kerja karyawan
        // Filter: kode_jam_kerja NULL (untuk semua) OR sesuai jam kerja karyawan
        $masterChecklists = MasterPerawatan::active()
            ->byTipe('harian')
            ->where(function ($query) use ($kodeJamKerja) {
                $query->whereNull('kode_jam_kerja')
                    ->orWhere('kode_jam_kerja', $kodeJamKerja);
            })
            ->ordered()
            ->get();

        if ($masterChecklists->isEmpty()) {
            // Jika tidak ada master checklist harian, tidak perlu modal
            return response()->json([
                'hasIncompleteChecklist' => false,
                'shouldShowModal' => false,
                'message' => 'Tidak ada checklist harian untuk shift Anda'
            ]);
        }

        // Count completed checklist
        $completedCount = PerawatanLog::where('user_id', $user->id)
            ->where('periode_key', $periodeKey)
            ->where('status', 'completed')
            ->count();

        $totalCount = $masterChecklists->count();
        $remainingCount = $totalCount - $completedCount;
        $percentageRemaining = $remainingCount > 0 ? round(($remainingCount / $totalCount) * 100) : 0;

        // Show modal jika ada checklist yang belum selesai
        $hasIncompleteChecklist = $remainingCount > 0;

        return response()->json([
            'hasIncompleteChecklist' => $hasIncompleteChecklist,
            'shouldShowModal' => $hasIncompleteChecklist,
            'checklistInfo' => [
                'total' => $totalCount,
                'completed' => $completedCount,
                'remaining' => $remainingCount,
                'percentageRemaining' => $percentageRemaining,
                'percentageCompleted' => $completedCount > 0 ? round(($completedCount / $totalCount) * 100) : 0
            ],
            'message' => $hasIncompleteChecklist 
                ? "Masih ada {$remainingCount} checklist yang belum selesai"
                : 'Semua checklist sudah selesai'
        ]);
    }

    /**
     * Force pulang - bypass checklist requirement
     * User klik tombol "Pulang" di notifikasi checklist
     */
    public function forcePulang(Request $request)
    {
        try {
            $user = Auth::user();
            $date = $request->input('date', now()->format('Y-m-d'));

            // Get presensi
            $userkaryawan = $user->userkaryawan;
            if (!$userkaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan karyawan'
                ], 403);
            }

            $nik = $userkaryawan->nik;
            $presensiToday = Presensi::where('nik', $nik)
                ->where('tanggal', $date)
                ->first();

            if (!$presensiToday) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada presensi hari ini'
                ], 404);
            }

            // Store flag bahwa user force pulang
            // Return success, biarkan aplikasi mobile handle absen pulang
            return response()->json([
                'success' => true,
                'forcePulangAllowed' => true,
                'message' => 'Anda dapat melanjutkan absen pulang'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get checklist grouped by jadwal piket dengan status validity
     * NEW METHOD: Untuk aplikasi mobile
     */
    public function getChecklistBySchedule(Request $request)
    {
        try {
            $user = Auth::user();
            $date = $request->input('date', now()->format('Y-m-d'));

            // Get NIK dari user
            $userkaryawan = $user->userkaryawan;
            if (!$userkaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan karyawan'
                ], 403);
            }

            $nik = $userkaryawan->nik;
            $now = now();

            // Get jadwal piket karyawan untuk hari ini
            $jadwalPiketKaryawan = JadwalPiketKaryawan::activeOnDate($nik, $date)->with('jadwalPiket')->get();

            if ($jadwalPiketKaryawan->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'current_shift' => null,
                    'upcoming_shifts' => [],
                    'completed_today' => [],
                    'message' => 'Tidak ada jadwal piket untuk hari ini'
                ]);
            }

            $response = [
                'success' => true,
                'current_shift' => null,
                'upcoming_shifts' => [],
                'completed_today' => []
            ];

            // Group jadwal piket
            foreach ($jadwalPiketKaryawan as $jpk) {
                $jadwalPiket = $jpk->jadwalPiket;
                $isActive = $this->jadwalPiketService->isInSchedule($jadwalPiket, $now);

                // Get checklist untuk jadwal piket ini
                $periodeKey = 'piket_' . $jadwalPiket->id . '_' . $date;
                $checklists = PerawatanLog::where([
                    'user_id' => $user->id,
                    'tanggal_eksekusi' => $date,
                    'periode_key' => $periodeKey
                ])
                ->with('masterPerawatan')
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'master_id' => $log->master_perawatan_id,
                        'nama' => $log->masterPerawatan->nama_kegiatan,
                        'deskripsi' => $log->masterPerawatan->deskripsi,
                        'status' => $log->status,
                        'is_valid' => $log->status_validity === 'valid',
                        'status_validity' => $log->status_validity,
                        'kategori' => $log->masterPerawatan->kategori,
                        'points' => $log->masterPerawatan->points ?? 0,
                        'completed_at' => $log->status === 'completed' ? $log->updated_at : null,
                        'created_at' => $log->created_at
                    ];
                });

                $jadwalInfo = [
                    'id' => $jadwalPiket->id,
                    'nama' => $jadwalPiket->nama_piket,
                    'jam_mulai' => $jadwalPiket->jam_mulai->format('H:i'),
                    'jam_selesai' => $jadwalPiket->jam_selesai->format('H:i'),
                    'is_active' => $isActive,
                    'status' => $isActive ? 'AKTIF' : ($this->jadwalPiketService->getMinutesUntilShiftStart($jadwalPiket, $now) === null ? 'TERTUTUP (SELESAI)' : 'TERTUTUP (BELUM MULAI)'),
                    'waktu_tersisa_menit' => $this->jadwalPiketService->getMinutesUntilShiftEnd($jadwalPiket, $now),
                    'waktu_dimulai_menit' => $this->jadwalPiketService->getMinutesUntilShiftStart($jadwalPiket, $now),
                    'checklists' => $checklists
                ];

                if ($isActive) {
                    $response['current_shift'] = $jadwalInfo;
                } else {
                    $response['upcoming_shifts'][] = $jadwalInfo;
                }
            }

            // Get completed checklist
            $completedLogs = PerawatanLog::where([
                'user_id' => $user->id,
                'tanggal_eksekusi' => $date,
                'status' => 'completed'
            ])
            ->with('masterPerawatan', 'jadwalPiket')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'master_id' => $log->master_perawatan_id,
                    'nama' => $log->masterPerawatan->nama_kegiatan,
                    'jadwal_piket' => $log->jadwalPiket->nama_piket ?? 'N/A',
                    'jam_ceklis' => $log->jam_ceklis ? (string)$log->jam_ceklis : null,
                    'completed_at' => $log->updated_at,
                    'points' => $log->points_earned
                ];
            });

            $response['completed_today'] = $completedLogs;

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('getChecklistBySchedule error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete checklist dengan validasi jadwal piket
     * UPDATE METHOD: Tambahi validasi jadwal piket
     */
    public function completeChecklist(Request $request)
    {
        try {
            $request->validate([
                'checklist_id' => 'required|exists:perawatan_log,id'
            ]);

            $user = Auth::user();
            $now = now();

            // Get checklist log
            $log = PerawatanLog::find($request->checklist_id);

            // Validasi ownership
            if ($log->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak authorized untuk ceklis ini'
                ], 403);
            }

            // Validasi jadwal piket (IMPORTANT)
            if ($log->jadwal_piket_id) {
                $jadwalPiket = JadwalPiket::find($log->jadwal_piket_id);

                // Check apakah current time masih dalam jadwal piket
                if (!$this->jadwalPiketService->isInSchedule($jadwalPiket, $now)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Checklist ini hanya bisa diselesaikan pada jam piket Anda (' . 
                                     $jadwalPiket->jam_mulai->format('H:i') . ' - ' . 
                                     $jadwalPiket->jam_selesai->format('H:i') . ')'
                    ], 403);
                }
            }

            // Update checklist
            $points = $log->masterPerawatan->points ?? 0;
            $log->update([
                'status' => 'completed',
                'jam_ceklis' => $now->format('H:i:s'),
                'status_validity' => 'valid',
                'points_earned' => $points,
                'updated_at' => $now
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checklist berhasil diselesaikan',
                'points_earned' => $points,
                'completed_at' => $log->updated_at
            ]);
        } catch (\Exception $e) {
            Log::error('completeChecklist error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get riwayat checklist dengan info karyawan dan jam
     * NEW METHOD: Untuk display history dengan detail
     */
    public function getRiwayatChecklist(Request $request)
    {
        try {
            $user = Auth::user();
            $date = $request->input('date', now()->format('Y-m-d'));
            $limit = $request->input('limit', 50);

            // Get NIK dari user
            $userkaryawan = $user->userkaryawan;
            if (!$userkaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan karyawan'
                ], 403);
            }

            // Get riwayat checklist
            $riwayat = PerawatanLog::where([
                'user_id' => $user->id,
                'tanggal_eksekusi' => $date
            ])
            ->where('status', 'completed')
            ->with('masterPerawatan', 'jadwalPiket')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($log) use ($userkaryawan) {
                return [
                    'id' => $log->id,
                    'nama_kegiatan' => $log->masterPerawatan->nama_kegiatan,
                    'kategori' => $log->masterPerawatan->kategori,
                    'nama_karyawan' => $log->nama_karyawan ?? $userkaryawan->nama_lengkap,
                    'nik' => $userkaryawan->nik,
                    'jadwal_piket' => $log->jadwalPiket->nama_piket ?? 'N/A',
                    'jam_ceklis' => $log->jam_ceklis ? (string)$log->jam_ceklis : null,
                    'jam_ceklis_formatted' => $log->jam_ceklis ? \Carbon\Carbon::createFromFormat('H:i:s', $log->jam_ceklis)->format('H:i') : null,
                    'tanggal' => $log->tanggal_eksekusi,
                    'waktu_eksekusi' => $log->waktu_eksekusi,
                    'points_earned' => $log->points_earned,
                    'catatan' => $log->catatan,
                    'completed_at' => $log->updated_at,
                    'completed_at_formatted' => $log->updated_at->format('d/m/Y H:i')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $riwayat,
                'total' => $riwayat->count(),
                'tanggal' => $date
            ]);
        } catch (\Exception $e) {
            Log::error('getRiwayatChecklist error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get jadwal piket karyawan untuk hari ini
     * NEW METHOD: Untuk init data jadwal piket
     */
    public function getJadwalPiketKaryawan(Request $request)
    {
        try {
            $user = Auth::user();
            $date = $request->input('date', now()->format('Y-m-d'));

            // Get NIK dari user
            $userkaryawan = $user->userkaryawan;
            if (!$userkaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan karyawan'
                ], 403);
            }

            $nik = $userkaryawan->nik;

            // Get jadwal piket active untuk hari ini
            $jadwalPikets = JadwalPiketKaryawan::activeOnDate($nik, $date)
                ->with('jadwalPiket')
                ->get()
                ->pluck('jadwalPiket')
                ->unique('id')
                ->values()
                ->map(function ($jp) {
                    return [
                        'id' => $jp->id,
                        'nama' => $jp->nama_piket,
                        'jam_mulai' => $jp->jam_mulai->format('H:i'),
                        'jam_selesai' => $jp->jam_selesai->format('H:i'),
                        'hari' => $jp->hari,
                        'deskripsi' => $jp->deskripsi,
                        'is_active' => (new JadwalPiketService())->isInSchedule($jp)
                    ];
                });

            return response()->json([
                'success' => true,
                'jadwal_pikets' => $jadwalPikets,
                'total' => $jadwalPikets->count(),
                'date' => $date
            ]);
        } catch (\Exception $e) {
            Log::error('getJadwalPiketKaryawan error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get checklist grouped by jam kerja (shift) dan ruangan untuk karyawan hari ini
     * Checklist hanya tampil jika sesuai jam kerja karyawan atau tidak ada jam kerja yang ditentukan
     */
    public function getChecklistByJamKerja(Request $request)
    {
        try {
            $user = Auth::user();
            $date = $request->input('date', now()->format('Y-m-d'));

            // Get NIK dari user
            $userkaryawan = $user->userkaryawan;
            if (!$userkaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan karyawan'
                ], 403);
            }

            $nik = $userkaryawan->nik;

            // Get jam kerja karyawan hari ini
            $presensiToday = Presensi::where('nik', $nik)
                ->where('tanggal', $date)
                ->first();

            if (!$presensiToday) {
                return response()->json([
                    'success' => true,
                    'kode_jam_kerja' => null,
                    'jam_kerja' => null,
                    'grouped_checklists' => [],
                    'message' => 'Belum ada presensi hari ini'
                ]);
            }

            $kodeJamKerja = $presensiToday->kode_jam_kerja;

            // Get jam kerja details
            $jamKerja = \App\Models\Jamkerja::find($kodeJamKerja);

            // Get active checklists for harian periode
            $periodeKey = 'harian_' . $date;
            
            // Query: Get checklist yang sesuai dengan jam kerja karyawan
            // Checklist tampil jika:
            // 1. kode_jam_kerja NULL (untuk semua), ATAU
            // 2. kode_jam_kerja sesuai dengan jam kerja karyawan
            $masterChecklists = MasterPerawatan::active()
                ->byTipe('harian')
                ->where(function ($query) use ($kodeJamKerja) {
                    $query->whereNull('kode_jam_kerja')
                        ->orWhere('kode_jam_kerja', $kodeJamKerja);
                })
                ->ordered()
                ->with('ruangan')
                ->get();

            // Get completion status untuk setiap checklist
            $checklists = [];
            foreach ($masterChecklists as $master) {
                $log = PerawatanLog::where([
                    'user_id' => $user->id,
                    'master_perawatan_id' => $master->id,
                    'tanggal_eksekusi' => $date,
                    'periode_key' => $periodeKey
                ])->first();

                // Jika belum ada log, buat satu (status 'pending')
                if (!$log) {
                    $log = PerawatanLog::create([
                        'user_id' => $user->id,
                        'master_perawatan_id' => $master->id,
                        'tanggal_eksekusi' => $date,
                        'periode_key' => $periodeKey,
                        'status' => 'pending',
                        'status_validity' => 'valid',
                        'kode_jam_kerja' => $kodeJamKerja
                    ]);
                }

                $checklists[] = [
                    'id' => $log->id,
                    'master_id' => $master->id,
                    'nama' => $master->nama_kegiatan,
                    'deskripsi' => $master->deskripsi,
                    'kategori' => $master->kategori,
                    'status' => $log->status,
                    'status_validity' => $log->status_validity,
                    'is_valid' => $log->status_validity === 'valid',
                    'ruangan' => $master->ruangan?->nama_ruangan ?? 'Umum',
                    'points' => $master->points ?? 0,
                    'jam_kerja_required' => $master->kode_jam_kerja,
                    'jam_kerja_required_label' => $master->jamKerja?->nama_jam_kerja ?? null,
                    'completed_at' => $log->status === 'completed' ? $log->updated_at : null
                ];
            }

            // Group by ruangan dan jam kerja
            $groupedByRuangan = collect($checklists)->groupBy('ruangan')->toArray();
            $groupedByJamKerja = collect($checklists)->groupBy('jam_kerja_required_label')->toArray();

            return response()->json([
                'success' => true,
                'kode_jam_kerja' => $kodeJamKerja,
                'jam_kerja' => $jamKerja ? [
                    'kode' => $jamKerja->kode_jam_kerja,
                    'nama' => $jamKerja->nama_jam_kerja,
                    'jam_masuk' => $jamKerja->jam_masuk,
                    'jam_pulang' => $jamKerja->jam_pulang
                ] : null,
                'total_checklist' => count($checklists),
                'completed_count' => collect($checklists)->where('status', 'completed')->count(),
                'grouped_by_ruangan' => $groupedByRuangan,
                'grouped_by_jam_kerja' => $groupedByJamKerja,
                'all_checklists' => $checklists,
                'date' => $date
            ]);
        } catch (\Exception $e) {
            Log::error('getChecklistByJamKerja error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
