<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterPerawatan;
use App\Models\PerawatanLog;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
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

        // Get all master checklist harian yang aktif
        $masterChecklists = MasterPerawatan::active()
            ->byTipe('harian')
            ->ordered()
            ->get();

        if ($masterChecklists->isEmpty()) {
            // Jika tidak ada master checklist harian, tidak perlu modal
            return response()->json([
                'hasIncompleteChecklist' => false,
                'shouldShowModal' => false,
                'message' => 'Tidak ada checklist harian'
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
}
