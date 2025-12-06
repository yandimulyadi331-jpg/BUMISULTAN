<?php

namespace App\Http\Controllers;

use App\Models\MasterPerawatan;
use App\Models\PerawatanLog;
use App\Models\PerawatanStatusPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PerawatanKaryawanController extends Controller
{
    /**
     * Dashboard Perawatan untuk Karyawan
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistik checklist hari ini
        $today = now()->format('Y-m-d');
        $todayKey = 'harian_' . $today;
        
        $checklistHariIni = MasterPerawatan::active()
            ->byTipe('harian')
            ->ordered()
            ->get();
            
        $completedHariIni = PerawatanLog::where('user_id', $user->id)
            ->where('periode_key', $todayKey)
            ->where('status', 'completed')
            ->count();
            
        // Statistik minggu ini
        $thisWeek = now()->format('Y-\\WW');
        $weekKey = 'mingguan_' . $thisWeek;
        
        $checklistMingguIni = MasterPerawatan::active()
            ->byTipe('mingguan')
            ->count();
            
        $completedMingguIni = PerawatanLog::where('user_id', $user->id)
            ->where('periode_key', $weekKey)
            ->where('status', 'completed')
            ->count();
            
        // History aktivitas terakhir
        $recentActivities = PerawatanLog::where('user_id', $user->id)
            ->with('masterPerawatan')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('perawatan.karyawan.index', compact(
            'checklistHariIni',
            'completedHariIni',
            'checklistMingguIni',
            'completedMingguIni',
            'recentActivities'
        ));
    }

    /**
     * Tampilkan checklist berdasarkan tipe (harian, mingguan, bulanan, tahunan)
     */
    public function checklist($tipe)
    {
        if (!in_array($tipe, ['harian', 'mingguan', 'bulanan', 'tahunan'])) {
            abort(404);
        }

        $user = Auth::user();
        $periodeKey = $this->generatePeriodeKey($tipe);
        
        // Ambil presensi hari ini untuk filter checklist harian berdasarkan shift
        $presensiToday = null;
        $jamKerja = null;
        if ($tipe === 'harian') {
            $today = now()->format('Y-m-d');
            $presensiToday = \App\Models\Presensi::where('nik', $user->nik)
                ->where('tanggal', $today)
                ->first();
            
            if ($presensiToday) {
                $jamKerja = \App\Models\Jamkerja::where('kode_jam_kerja', $presensiToday->kode_jam_kerja)->first();
            }
        }
        
        // Ambil semua master checklist dengan filter berdasarkan tipe periode
        $query = MasterPerawatan::active()->byTipe($tipe);
        
        // Filter berdasarkan jadwal
        if ($tipe === 'harian' && $jamKerja) {
            // Filter checklist harian berdasarkan jam kerja shift karyawan
            $query->where(function($q) use ($jamKerja) {
                if ($jamKerja->lintashari == '0') {
                    // Shift normal (tidak lintas hari): 08:00 - 17:00
                    $q->where(function($subQ) use ($jamKerja) {
                        // Checklist yang jam_mulai dan jam_selesai berada dalam range shift
                        $subQ->whereTime('jam_mulai', '>=', $jamKerja->jam_masuk)
                             ->whereTime('jam_mulai', '<', $jamKerja->jam_pulang);
                    })->orWhereNull('jam_mulai'); // Backward compatibility
                } else {
                    // Shift lintas hari: 20:00 - 08:00 (besok pagi)
                    $q->where(function($subQ) use ($jamKerja) {
                        // Checklist malam hari (20:00 - 23:59)
                        $subQ->whereTime('jam_mulai', '>=', $jamKerja->jam_masuk)
                             // Checklist pagi hari (00:00 - 08:00)
                             ->orWhereTime('jam_mulai', '<', $jamKerja->jam_pulang);
                    })->orWhereNull('jam_mulai');
                }
            });
        } elseif ($tipe === 'mingguan') {
            // Filter mingguan berdasarkan hari dalam minggu ini
            $currentDayOfWeek = now()->dayOfWeekIso; // 1=Senin, 7=Minggu
            $query->where(function($q) use ($currentDayOfWeek) {
                $q->where('hari_minggu', $currentDayOfWeek)
                  ->orWhereNull('hari_minggu');
            });
        } elseif ($tipe === 'bulanan') {
            // Filter bulanan berdasarkan bulan saat ini
            $currentMonth = now()->format('Y-m');
            $query->where(function($q) use ($currentMonth) {
                $q->where('bulan_target', $currentMonth)
                  ->orWhereNull('bulan_target');
            });
        } elseif ($tipe === 'tahunan') {
            // Filter tahunan berdasarkan tahun saat ini
            $currentYear = now()->year;
            $query->where(function($q) use ($currentYear) {
                $q->whereYear('tanggal_target', $currentYear)
                  ->orWhereNull('tanggal_target');
            });
        }
        
        $checklists = $query->ordered()
            ->with(['logs' => function($query) use ($periodeKey) {
                $query->where('periode_key', $periodeKey)
                      ->with('user:id,name');
            }])
            ->get();
        
        // Hitung progress GLOBAL
        $totalChecklist = $checklists->count();
        $completedChecklist = $checklists->filter(function($item) {
            return $item->logs->where('status', 'completed')->count() > 0;
        })->count();
        
        $progress = $totalChecklist > 0 ? round(($completedChecklist / $totalChecklist) * 100) : 0;
        
        return view('perawatan.karyawan.checklist', compact(
            'tipe',
            'checklists',
            'periodeKey',
            'totalChecklist',
            'completedChecklist',
            'progress',
            'jamKerja'
        ));
    }

    /**
     * Simpan/Update checklist yang sudah dikerjakan
     */
    public function executeChecklist(Request $request)
    {
        // Validasi
        $request->validate([
            'master_perawatan_id' => 'required|exists:master_perawatan,id',
            'periode_key' => 'required|string',
            'catatan' => 'nullable|string|max:500',
            'foto_bukti' => 'nullable|image|max:2048'
        ]);

        $user = Auth::user();
        
        try {
            // Cek duplikasi GLOBAL (tidak per user, tapi per periode)
            // Jika sudah ada yang checklist periode ini, maka SEMUA karyawan lihat sudah selesai
            $existingLog = PerawatanLog::where([
                ['master_perawatan_id', '=', $request->master_perawatan_id],
                ['periode_key', '=', $request->periode_key]
            ])->with('user:id,name')->first();
            
            if ($existingLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checklist ini sudah dikerjakan oleh ' . $existingLog->user->name . '!'
                ], 422);
            }
            
            // Upload foto bukti (client sudah kompresi)
            $fotoBukti = null;
            if ($request->hasFile('foto_bukti')) {
                $file = $request->file('foto_bukti');
                $filename = 'perawatan_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/perawatan', $filename);
                $fotoBukti = $filename;
            }
            
            // Simpan log
            PerawatanLog::create([
                'master_perawatan_id' => $request->master_perawatan_id,
                'user_id' => $user->id,
                'tanggal_eksekusi' => now()->format('Y-m-d'),
                'waktu_eksekusi' => now()->format('H:i:s'),
                'status' => 'completed',
                'catatan' => $request->catatan,
                'foto_bukti' => $fotoBukti,
                'periode_key' => $request->periode_key
            ]);
            
            return response()->json(['success' => true, 'message' => 'Berhasil!']);
            
        } catch (\Exception $e) {
            // Hapus file jika ada error
            if (isset($fotoBukti) && $fotoBukti) {
                Storage::delete('public/perawatan/' . $fotoBukti);
            }
            
            \Log::error('Perawatan Execute Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan!'
            ], 500);
        }
    }

    /**
     * Hapus/Uncheck checklist (jika salah centang)
     */
    public function uncheckChecklist(Request $request)
    {
        $validated = $request->validate([
            'master_perawatan_id' => 'required|exists:master_perawatan,id',
            'periode_key' => 'required|string'
        ]);

        $user = Auth::user();
        
        // Cari log berdasarkan periode saja (GLOBAL)
        $log = PerawatanLog::where('master_perawatan_id', $validated['master_perawatan_id'])
            ->where('periode_key', $validated['periode_key'])
            ->first();
        
        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log tidak ditemukan!'
            ], 404);
        }
        
        // Validasi: hanya yang checklist yang bisa uncheck
        if ($log->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa membatalkan checklist orang lain!'
            ], 403);
        }
        
        // Hapus foto jika ada
        if ($log->foto_bukti) {
            Storage::delete('public/perawatan/' . $log->foto_bukti);
        }
        
        $log->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil dibatalkan!'
        ]);
    }

    /**
     * History checklist yang sudah dikerjakan user
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $tipe = $request->get('tipe', 'all');
        
        $query = PerawatanLog::where('user_id', $user->id)
            ->with('masterPerawatan')
            ->orderBy('tanggal_eksekusi', 'desc')
            ->orderBy('waktu_eksekusi', 'desc');
        
        if ($tipe !== 'all') {
            $query->whereHas('masterPerawatan', function($q) use ($tipe) {
                $q->where('tipe_periode', $tipe);
            });
        }
        
        $histories = $query->paginate(20);
        
        return view('perawatan.karyawan.history', compact('histories', 'tipe'));
    }

    /**
     * Generate periode key berdasarkan tipe
     */
    private function generatePeriodeKey($tipe)
    {
        $now = now();
        
        switch ($tipe) {
            case 'harian':
                return 'harian_' . $now->format('Y-m-d');
            
            case 'mingguan':
                return 'mingguan_' . $now->format('Y-\\WW');
            
            case 'bulanan':
                return 'bulanan_' . $now->format('Y-m');
            
            case 'tahunan':
                return 'tahunan_' . $now->format('Y');
            
            default:
                return 'harian_' . $now->format('Y-m-d');
        }
    }
}
