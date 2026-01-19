<?php

namespace App\Http\Controllers;

use App\Models\MasterPerawatan;
use App\Models\PerawatanLog;
use App\Models\PerawatanLaporan;
use App\Models\PerawatanStatusPeriode;
use App\Models\ChecklistPeriodeConfig;
use App\Models\KpiCrew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ManajemenPerawatanController extends Controller
{
    // ==================== MASTER CHECKLIST (CRUD) ====================
    
    public function index()
    {
        return view('perawatan.index');
    }

    public function masterIndex()
    {
        $masters = MasterPerawatan::with('ruangan')->withCount(['logs' => function($q) {
            $q->whereDate('tanggal_eksekusi', '>=', now()->subDays(30));
        }])->ordered()->get();
        
        return view('perawatan.master.index', compact('masters'));
    }

    public function masterCreate()
    {
        $ruangans = \App\Models\Ruangan::orderBy('nama_ruangan')->get();
        return view('perawatan.master.create', compact('ruangans'));
    }

    public function masterStore(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
            'kategori' => 'required|in:kebersihan,perawatan_rutin,pengecekan,lainnya',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'urutan' => 'nullable|integer|min:0',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'hari_minggu' => 'nullable|integer|min:1|max:7',
            'tanggal_bulan' => 'nullable|integer|min:1|max:31',
            'tanggal_target' => 'nullable|date',
            'points' => 'required|integer|min:1|max:100',
            'point_description' => 'nullable|string|max:500'
        ]);

        $validated['urutan'] = $validated['urutan'] ?? 0;
        $validated['is_active'] = true;

        MasterPerawatan::create($validated);

        return redirect()->route('perawatan.master.index')
            ->with('success', 'Master checklist berhasil ditambahkan!');
    }

    public function masterEdit($id)
    {
        $master = MasterPerawatan::findOrFail($id);
        $ruangans = \App\Models\Ruangan::orderBy('nama_ruangan')->get();
        return view('perawatan.master.edit', compact('master', 'ruangans'));
    }

    public function masterUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
            'kategori' => 'required|in:kebersihan,perawatan_rutin,pengecekan,lainnya',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
            'hari_minggu' => 'nullable|integer|min:1|max:7',
            'tanggal_bulan' => 'nullable|integer|min:1|max:31',
            'tanggal_target' => 'nullable|date',
            'points' => 'required|integer|min:1|max:100',
            'point_description' => 'nullable|string|max:500'
        ]);

        $master = MasterPerawatan::findOrFail($id);
        $master->update($validated);

        return redirect()->route('perawatan.master.index')
            ->with('success', 'Master checklist berhasil diupdate!');
    }

    public function masterDestroy($id)
    {
        $master = MasterPerawatan::findOrFail($id);
        $master->delete(); // Soft delete
        
        return redirect()->route('perawatan.master.index')
            ->with('success', 'Master checklist berhasil dihapus!');
    }

    // ==================== EKSEKUSI CHECKLIST ====================

    public function checklistHarian()
    {
        $tipe = 'harian';
        $periodeKey = $this->generatePeriodeKey($tipe);
        $statusPeriode = $this->getOrCreateStatusPeriode($tipe, $periodeKey);
        
        // Get config untuk status banner
        $config = ChecklistPeriodeConfig::byTipe($tipe)->first();
        
        // ✅ PERBAIKAN: Ambil dengan relasi ruangan dan group by ruangan
        $masters = MasterPerawatan::active()
            ->byTipe($tipe)
            ->with('ruangan')
            ->ordered()
            ->get();

        // Get today's logs with user info
        $logs = PerawatanLog::byPeriode($periodeKey)
            ->with('user:id,name')
            ->get()
            ->keyBy('master_perawatan_id');

        // ✅ PERBAIKAN: Group masters by ruangan_id untuk tampilan per ruangan
        $mastersByRuangan = $masters->groupBy(function($item) {
            return $item->ruangan_id ?? 'tanpa-ruangan';
        })->map(function($items, $ruanganId) {
            return [
                'ruangan_id' => $ruanganId,
                'ruangan_nama' => $ruanganId === 'tanpa-ruangan' 
                    ? 'Umum (Tanpa Ruangan)' 
                    : ($items->first()->ruangan->nama_ruangan ?? 'Unknown'),
                'items' => $items
            ];
        });

        return view('perawatan.checklist', compact('masters', 'mastersByRuangan', 'logs', 'tipe', 'periodeKey', 'statusPeriode', 'config'));
    }

    public function checklistMingguan()
    {
        $tipe = 'mingguan';
        $periodeKey = $this->generatePeriodeKey($tipe);
        $statusPeriode = $this->getOrCreateStatusPeriode($tipe, $periodeKey);
        
        // Get config untuk status banner
        $config = ChecklistPeriodeConfig::byTipe($tipe)->first();
        
        $masters = MasterPerawatan::active()
            ->byTipe($tipe)
            ->with('ruangan')
            ->ordered()
            ->get();

        $logs = PerawatanLog::byPeriode($periodeKey)
            ->with('user:id,name')
            ->get()
            ->keyBy('master_perawatan_id');

        // Group by ruangan
        $mastersByRuangan = $masters->groupBy(function($item) {
            return $item->ruangan_id ?? 'tanpa-ruangan';
        })->map(function($items, $ruanganId) {
            return [
                'ruangan_id' => $ruanganId,
                'ruangan_nama' => $ruanganId === 'tanpa-ruangan' 
                    ? 'Umum (Tanpa Ruangan)' 
                    : ($items->first()->ruangan->nama_ruangan ?? 'Unknown'),
                'items' => $items
            ];
        });

        return view('perawatan.checklist', compact('masters', 'mastersByRuangan', 'logs', 'tipe', 'periodeKey', 'statusPeriode', 'config'));
    }

    public function checklistBulanan()
    {
        $tipe = 'bulanan';
        $periodeKey = $this->generatePeriodeKey($tipe);
        $statusPeriode = $this->getOrCreateStatusPeriode($tipe, $periodeKey);
        
        // Get config untuk status banner
        $config = ChecklistPeriodeConfig::byTipe($tipe)->first();
        
        $masters = MasterPerawatan::active()
            ->byTipe($tipe)
            ->with('ruangan')
            ->ordered()
            ->get();

        $logs = PerawatanLog::byPeriode($periodeKey)
            ->with('user:id,name')
            ->get()
            ->keyBy('master_perawatan_id');

        // Group by ruangan
        $mastersByRuangan = $masters->groupBy(function($item) {
            return $item->ruangan_id ?? 'tanpa-ruangan';
        })->map(function($items, $ruanganId) {
            return [
                'ruangan_id' => $ruanganId,
                'ruangan_nama' => $ruanganId === 'tanpa-ruangan' 
                    ? 'Umum (Tanpa Ruangan)' 
                    : ($items->first()->ruangan->nama_ruangan ?? 'Unknown'),
                'items' => $items
            ];
        });

        return view('perawatan.checklist', compact('masters', 'mastersByRuangan', 'logs', 'tipe', 'periodeKey', 'statusPeriode', 'config'));
    }

    public function checklistTahunan()
    {
        $tipe = 'tahunan';
        $periodeKey = $this->generatePeriodeKey($tipe);
        $statusPeriode = $this->getOrCreateStatusPeriode($tipe, $periodeKey);
        
        // Get config untuk status banner
        $config = ChecklistPeriodeConfig::byTipe($tipe)->first();
        
        $masters = MasterPerawatan::active()
            ->byTipe($tipe)
            ->with('ruangan')
            ->ordered()
            ->get();

        $logs = PerawatanLog::byPeriode($periodeKey)
            ->with('user:id,name')
            ->get()
            ->keyBy('master_perawatan_id');

        // Group by ruangan
        $mastersByRuangan = $masters->groupBy(function($item) {
            return $item->ruangan_id ?? 'tanpa-ruangan';
        })->map(function($items, $ruanganId) {
            return [
                'ruangan_id' => $ruanganId,
                'ruangan_nama' => $ruanganId === 'tanpa-ruangan' 
                    ? 'Umum (Tanpa Ruangan)' 
                    : ($items->first()->ruangan->nama_ruangan ?? 'Unknown'),
                'items' => $items
            ];
        });

        return view('perawatan.checklist', compact('masters', 'mastersByRuangan', 'logs', 'tipe', 'periodeKey', 'statusPeriode', 'config'));
    }

    public function executeChecklist(Request $request)
    {
        $validated = $request->validate([
            'master_perawatan_id' => 'required|exists:master_perawatan,id',
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
            'catatan' => 'nullable|string',
            'foto_bukti' => 'nullable|image|max:10240'
        ]);

        $periodeKey = $this->generatePeriodeKey($validated['tipe_periode']);
        
        // Check if already executed (GLOBAL per periode, tidak per tanggal)
        $existingLog = PerawatanLog::where('master_perawatan_id', $validated['master_perawatan_id'])
            ->where('periode_key', $periodeKey)
            ->with('user:id,name')
            ->first();

        if ($existingLog) {
            return response()->json([
                'success' => false,
                'message' => 'Checklist ini sudah dikerjakan oleh ' . $existingLog->user->name . '!'
            ], 422);
        }

        // Get master perawatan untuk mendapatkan points
        $master = MasterPerawatan::findOrFail($validated['master_perawatan_id']);
        $pointsEarned = $master->points ?? 0;

        $fotoBukti = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBukti = $request->file('foto_bukti')->store('perawatan/bukti', 'public');
        }

        // Create log with points earned
        PerawatanLog::create([
            'master_perawatan_id' => $validated['master_perawatan_id'],
            'user_id' => Auth::id(),
            'tanggal_eksekusi' => today(),
            'waktu_eksekusi' => now()->format('H:i:s'),
            'status' => 'completed',
            'catatan' => $validated['catatan'] ?? null,
            'foto_bukti' => $fotoBukti,
            'periode_key' => $periodeKey,
            'points_earned' => $pointsEarned
        ]);

        // ✅ TAMBAHAN: Update KPI crew dengan points perawatan
        $this->updateKpiCrewPoints(Auth::user(), $pointsEarned);

        // Update status periode
        $this->updateStatusPeriode($validated['tipe_periode'], $periodeKey);

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil dicentang! (+' . $pointsEarned . ' points)',
            'points_earned' => $pointsEarned
        ]);
    }

    public function uncheckChecklist(Request $request)
    {
        $validated = $request->validate([
            'master_perawatan_id' => 'required|exists:master_perawatan,id',
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan'
        ]);

        $periodeKey = $this->generatePeriodeKey($validated['tipe_periode']);
        
        $log = PerawatanLog::where('master_perawatan_id', $validated['master_perawatan_id'])
            ->where('periode_key', $periodeKey)
            ->first();

        if ($log) {
            // ✅ TAMBAHAN: Kurangi points dari KPI crew sebelum delete
            $user = User::find($log->user_id);
            if ($user && $log->points_earned > 0) {
                $this->updateKpiCrewPoints($user, -$log->points_earned);
            }

            // Delete foto if exists
            if ($log->foto_bukti && Storage::disk('public')->exists($log->foto_bukti)) {
                Storage::disk('public')->delete($log->foto_bukti);
            }
            
            $log->delete();
            
            // Update status periode
            $this->updateStatusPeriode($validated['tipe_periode'], $periodeKey);
        }

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil dibatalkan!'
        ]);
    }

    // ==================== LAPORAN ====================

    public function generateLaporan(Request $request)
    {
        $validated = $request->validate([
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan'
        ]);

        $tipe = $validated['tipe_periode'];
        $periodeKey = $this->generatePeriodeKey($tipe);
        
        $statusPeriode = PerawatanStatusPeriode::where('tipe_periode', $tipe)
            ->where('periode_key', $periodeKey)
            ->first();

        if (!$statusPeriode) {
            return response()->json([
                'success' => false,
                'message' => 'Status periode tidak ditemukan!'
            ], 404);
        }

        if (!$statusPeriode->is_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Semua checklist harus diselesaikan sebelum generate laporan!',
                'completed' => $statusPeriode->total_completed,
                'total' => $statusPeriode->total_checklist
            ], 422);
        }

        // Check if laporan already exists
        $existingLaporan = PerawatanLaporan::where('tipe_laporan', $tipe)
            ->where('periode_key', $periodeKey)
            ->first();

        if ($existingLaporan) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan sudah pernah dibuat!',
                'laporan_id' => $existingLaporan->id,
                'download_url' => route('perawatan.laporan.download', $existingLaporan->id)
            ]);
        }

        // Generate laporan
        try {
            DB::beginTransaction();

            // Get all logs for this period
            $logs = PerawatanLog::with(['masterPerawatan', 'user'])
                ->byPeriode($periodeKey)
                ->orderBy('tanggal_eksekusi')
                ->orderBy('waktu_eksekusi')
                ->get();

            $laporan = PerawatanLaporan::create([
                'tipe_laporan' => $tipe,
                'periode_key' => $periodeKey,
                'tanggal_laporan' => today(),
                'dibuat_oleh' => Auth::id(),
                'total_checklist' => $statusPeriode->total_checklist,
                'total_completed' => $statusPeriode->total_completed,
                'ringkasan' => $this->generateRingkasan($tipe, $periodeKey, $logs)
            ]);

            // Generate PDF
            $pdfFileName = $this->generatePDF($laporan, $logs, $statusPeriode);
            $laporan->update(['file_pdf' => $pdfFileName]);

            // Mark status periode as completed
            $statusPeriode->update([
                'completed_by' => Auth::id(),
                'completed_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dibuat!',
                'laporan_id' => $laporan->id,
                'download_url' => route('perawatan.laporan.download', $laporan->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function laporanIndex()
    {
        $laporans = PerawatanLaporan::with('pembuatLaporan')
            ->orderBy('tanggal_laporan', 'desc')
            ->paginate(20);
        
        return view('perawatan.laporan.index', compact('laporans'));
    }

    public function downloadLaporan($id)
    {
        $laporan = PerawatanLaporan::findOrFail($id);
        
        if (!$laporan->file_pdf || !Storage::disk('public')->exists($laporan->file_pdf)) {
            return redirect()->back()->with('error', 'File PDF tidak ditemukan!');
        }

        return Storage::disk('public')->download($laporan->file_pdf);
    }

    // ==================== HELPER METHODS ====================

    private function generatePeriodeKey($tipe)
    {
        $now = now();
        
        switch ($tipe) {
            case 'harian':
                return 'harian_' . $now->format('Y-m-d');
            case 'mingguan':
                return 'mingguan_' . $now->format('Y') . '-W' . $now->format('W');
            case 'bulanan':
                return 'bulanan_' . $now->format('Y-m');
            case 'tahunan':
                return 'tahunan_' . $now->format('Y');
            default:
                return null;
        }
    }

    private function getPeriodeDates($tipe)
    {
        $now = now();
        
        switch ($tipe) {
            case 'harian':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'mingguan':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'bulanan':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'tahunan':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            default:
                return ['start' => $now, 'end' => $now];
        }
    }

    private function getOrCreateStatusPeriode($tipe, $periodeKey)
    {
        $dates = $this->getPeriodeDates($tipe);
        $totalChecklist = MasterPerawatan::active()->byTipe($tipe)->count();

        $statusPeriode = PerawatanStatusPeriode::firstOrCreate(
            [
                'tipe_periode' => $tipe,
                'periode_key' => $periodeKey
            ],
            [
                'periode_start' => $dates['start'],
                'periode_end' => $dates['end'],
                'total_checklist' => $totalChecklist,
                'total_completed' => 0,
                'is_completed' => false
            ]
        );

        // Update total_checklist if changed
        if ($statusPeriode->total_checklist != $totalChecklist) {
            $statusPeriode->update(['total_checklist' => $totalChecklist]);
        }

        return $statusPeriode;
    }

    private function updateStatusPeriode($tipe, $periodeKey)
    {
        $statusPeriode = PerawatanStatusPeriode::where('tipe_periode', $tipe)
            ->where('periode_key', $periodeKey)
            ->first();

        if (!$statusPeriode) {
            return;
        }

        $totalCompleted = PerawatanLog::byPeriode($periodeKey)
            ->where('status', 'completed')
            ->distinct('master_perawatan_id')
            ->count('master_perawatan_id');

        $isCompleted = $totalCompleted >= $statusPeriode->total_checklist;

        $statusPeriode->update([
            'total_completed' => $totalCompleted,
            'is_completed' => $isCompleted
        ]);
    }

    private function generateRingkasan($tipe, $periodeKey, $logs)
    {
        $kategoriCounts = $logs->groupBy('masterPerawatan.kategori')->map->count();
        
        $ringkasan = "Laporan Perawatan {$tipe} untuk periode {$periodeKey}\n\n";
        $ringkasan .= "Total kegiatan: {$logs->count()}\n";
        $ringkasan .= "Rincian per kategori:\n";
        
        foreach ($kategoriCounts as $kategori => $count) {
            $ringkasan .= "- " . ucfirst($kategori) . ": {$count} kegiatan\n";
        }
        
        return $ringkasan;
    }

    private function generatePDF($laporan, $logs, $statusPeriode)
    {
        $data = [
            'laporan' => $laporan,
            'logs' => $logs,
            'statusPeriode' => $statusPeriode,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = PDF::loadView('perawatan.laporan.pdf', $data);
        $fileName = 'perawatan/laporan/' . $laporan->periode_key . '_' . time() . '.pdf';
        
        Storage::disk('public')->put($fileName, $pdf->output());
        
        return $fileName;
    }

    // ==================== CHECKLIST PERIODE CONFIG ====================

    /**
     * Show config page untuk toggle checklist periode
     */
    public function showConfig()
    {
        $configs = ChecklistPeriodeConfig::orderByRaw("
            CASE tipe_periode
                WHEN 'harian' THEN 1
                WHEN 'mingguan' THEN 2
                WHEN 'bulanan' THEN 3
                WHEN 'tahunan' THEN 4
            END
        ")->get();

        return view('perawatan.config', compact('configs'));
    }

    /**
     * Update config toggle (AJAX)
     */
    public function updateConfig(Request $request)
    {
        $validated = $request->validate([
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
            'is_enabled' => 'required|boolean',
            'is_mandatory' => 'required|boolean',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $config = ChecklistPeriodeConfig::where('tipe_periode', $validated['tipe_periode'])->firstOrFail();
        
        $config->update([
            'is_enabled' => $validated['is_enabled'],
            'is_mandatory' => $validated['is_mandatory'],
            'keterangan' => $validated['keterangan'],
            'diubah_oleh' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi checklist ' . strtoupper($validated['tipe_periode']) . ' berhasil diupdate!',
            'data' => [
                'status_text' => $config->status_text,
                'badge_class' => $config->badge_class
            ]
        ]);
    }

    /**
     * Get status checklist by tipe (API untuk checking)
     */
    public function getStatusChecklist($tipe)
    {
        $config = ChecklistPeriodeConfig::where('tipe_periode', $tipe)->first();

        if (!$config) {
            return response()->json([
                'enabled' => false,
                'mandatory' => false,
                'keterangan' => 'Konfigurasi tidak ditemukan'
            ]);
        }

        return response()->json([
            'enabled' => $config->is_enabled,
            'mandatory' => $config->is_mandatory,
            'keterangan' => $config->keterangan,
            'status_text' => $config->status_text,
            'badge_class' => $config->badge_class
        ]);
    }

    /**
     * Validate checkout - check if mandatory checklist completed
     */
    public function validateCheckout(Request $request)
    {
        $validated = $request->validate([
            'tipe_periode' => 'required|in:harian,mingguan,bulanan,tahunan',
            'periode_key' => 'required|string'
        ]);

        $config = ChecklistPeriodeConfig::byTipe($validated['tipe_periode'])->first();

        // Jika checklist nonaktif, boleh checkout langsung
        if (!$config || !$config->is_enabled) {
            return response()->json([
                'can_checkout' => true,
                'reason' => 'skipped',
                'message' => 'Checklist ' . $validated['tipe_periode'] . ' nonaktif, checkout diizinkan.'
            ]);
        }

        // Jika checklist aktif tapi tidak mandatory, boleh checkout
        if (!$config->is_mandatory) {
            return response()->json([
                'can_checkout' => true,
                'reason' => 'optional',
                'message' => 'Checklist ' . $validated['tipe_periode'] . ' opsional, checkout diizinkan.'
            ]);
        }

        // Checklist mandatory, cek apakah semua item sudah completed
        $statusPeriode = PerawatanStatusPeriode::where('periode_key', $validated['periode_key'])->first();

        if (!$statusPeriode) {
            return response()->json([
                'can_checkout' => false,
                'reason' => 'not_started',
                'message' => 'Anda belum memulai checklist ' . $validated['tipe_periode'] . '. Silakan lengkapi terlebih dahulu.'
            ], 403);
        }

        // Cek apakah ada item yang belum completed
        $totalItems = MasterPerawatan::where('tipe_periode', $validated['tipe_periode'])
            ->where('is_active', true)
            ->count();

        $completedItems = PerawatanLog::where('periode_key', $validated['periode_key'])
            ->where('status', 'selesai')
            ->count();

        if ($completedItems < $totalItems) {
            return response()->json([
                'can_checkout' => false,
                'reason' => 'incomplete',
                'message' => "Checklist {$validated['tipe_periode']} belum lengkap! ({$completedItems}/{$totalItems} item selesai). Harap lengkapi sebelum checkout.",
                'progress' => [
                    'completed' => $completedItems,
                    'total' => $totalItems,
                    'percentage' => $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0
                ]
            ], 403);
        }

        // Semua item completed
        return response()->json([
            'can_checkout' => true,
            'reason' => 'completed',
            'message' => 'Semua checklist ' . $validated['tipe_periode'] . ' telah lengkap. Checkout diizinkan.',
            'progress' => [
                'completed' => $completedItems,
                'total' => $totalItems,
                'percentage' => 100
            ]
        ]);
    }

    /**
     * Helper method untuk update KPI crew dengan points perawatan
     * @param User $user - User yang menyelesaikan checklist
     * @param int $points - Points yang ditambahkan (bisa negatif untuk pengurangan)
     */
    private function updateKpiCrewPoints($user, $points)
    {
        try {
            // Ambil data karyawan dari user
            $karyawan = $user->karyawan;
            if (!$karyawan || !$karyawan->nik) {
                return; // Skip jika user tidak memiliki karyawan atau nik
            }

            // Dapatkan bulan dan tahun sekarang
            $bulan = now()->month;
            $tahun = now()->year;

            // Cari atau buat KPI crew record
            $kpiCrew = KpiCrew::firstOrCreate(
                [
                    'nik' => $karyawan->nik,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ],
                [
                    'kehadiran_count' => 0,
                    'aktivitas_count' => 0,
                    'perawatan_count' => 0,
                    'kehadiran_point' => 0,
                    'aktivitas_point' => 0,
                    'perawatan_point' => 0,
                    'total_point' => 0
                ]
            );

            // Update perawatan_point dan total_point
            $newPerawatanPoint = max(0, $kpiCrew->perawatan_point + $points); // Jangan biarkan negatif
            $newTotalPoint = max(0, $kpiCrew->total_point + $points);

            $kpiCrew->update([
                'perawatan_count' => $points > 0 ? $kpiCrew->perawatan_count + 1 : max(0, $kpiCrew->perawatan_count - 1),
                'perawatan_point' => $newPerawatanPoint,
                'total_point' => $newTotalPoint
            ]);
        } catch (\Exception $e) {
            // Log error tapi jangan crash aplikasi
            \Log::error('Error updating KPI crew: ' . $e->getMessage());
        }
    }
}

