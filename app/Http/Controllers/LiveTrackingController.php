<?php

namespace App\Http\Controllers;

use App\Models\AktivitasKendaraan;
use App\Models\GpsTrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LiveTrackingController extends Controller
{
    /**
     * Halaman driver untuk kirim GPS
     */
    public function driverTracking($aktivitas_id)
    {
        $aktivitas_id = Crypt::decrypt($aktivitas_id);
        $aktivitas = AktivitasKendaraan::with('kendaraan')->findOrFail($aktivitas_id);
        
        if ($aktivitas->status != 'keluar') {
            return redirect()->back()->with(messageError('Aktivitas sudah selesai'));
        }
        
        return view('kendaraan.live-tracking.driver', compact('aktivitas'));
    }

    /**
     * API untuk terima GPS dari driver
     */
    public function storeGpsData(Request $request, $aktivitas_id)
    {
        $aktivitas_id = Crypt::decrypt($aktivitas_id);
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'accuracy' => 'nullable|numeric',
        ]);

        $aktivitas = AktivitasKendaraan::findOrFail($aktivitas_id);
        
        if ($aktivitas->status != 'keluar') {
            return response()->json(['success' => false, 'message' => 'Aktivitas sudah selesai'], 400);
        }

        // Cek apakah kendaraan sedang bergerak atau diam
        $lastLog = GpsTrackingLog::where('aktivitas_id', $aktivitas_id)
            ->latest()
            ->first();

        $status = 'moving';
        if ($lastLog && $request->speed < 5) { // Jika kecepatan < 5 km/h = diam
            $status = 'stopped';
        }

        GpsTrackingLog::create([
            'aktivitas_id' => $aktivitas_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed ?? 0,
            'accuracy' => $request->accuracy,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'GPS data saved',
            'status' => $status
        ]);
    }

    /**
     * Halaman admin untuk monitor real-time
     */
    public function adminLiveTracking($aktivitas_id)
    {
        $aktivitas_id = Crypt::decrypt($aktivitas_id);
        $aktivitas = AktivitasKendaraan::with('kendaraan')->findOrFail($aktivitas_id);
        
        return view('kendaraan.live-tracking.admin', compact('aktivitas'));
    }

    /**
     * API untuk ambil data GPS terbaru (untuk admin)
     */
    public function getLatestGpsData($aktivitas_id)
    {
        $aktivitas_id = Crypt::decrypt($aktivitas_id);
        
        $latestLog = GpsTrackingLog::where('aktivitas_id', $aktivitas_id)
            ->latest()
            ->first();

        $allLogs = GpsTrackingLog::where('aktivitas_id', $aktivitas_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'latest' => $latestLog,
            'trail' => $allLogs,
            'total_distance' => $this->calculateTotalDistance($allLogs),
        ]);
    }

    /**
     * Hitung total jarak tempuh
     */
    private function calculateTotalDistance($logs)
    {
        $distance = 0;
        
        for ($i = 1; $i < count($logs); $i++) {
            $distance += $this->haversineDistance(
                $logs[$i-1]->latitude,
                $logs[$i-1]->longitude,
                $logs[$i]->latitude,
                $logs[$i]->longitude
            );
        }
        
        return round($distance, 2); // dalam KM
    }

    /**
     * Haversine formula untuk hitung jarak antar 2 titik GPS
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // radius bumi dalam KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}
