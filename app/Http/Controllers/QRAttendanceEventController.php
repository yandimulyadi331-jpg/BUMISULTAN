<?php

namespace App\Http\Controllers;

use App\Models\QRAttendanceEvent;
use App\Models\QRAttendanceCode;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRAttendanceEventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        $query = QRAttendanceEvent::with(['cabang', 'creator']);

        // Filter by date
        if ($request->filled('tanggal')) {
            $query->whereDate('event_date', $request->tanggal);
        }

        // Filter by cabang
        if ($request->filled('kode_cabang')) {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')
            ->orderBy('event_start_time', 'desc')
            ->paginate(15);

        $events->appends($request->all());

        $cabang = Cabang::orderBy('kode_cabang')->get();

        return view('qr-attendance.events.index', compact('events', 'cabang'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $cabang = Cabang::orderBy('kode_cabang')->get();
        return view('qr-attendance.events.create', compact('cabang'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:200',
            'event_date' => 'required|date',
            'event_start_time' => 'required',
            'event_end_time' => 'required',
            'venue_name' => 'nullable|string|max:200',
            'venue_latitude' => 'required|numeric|between:-90,90',
            'venue_longitude' => 'required|numeric|between:-180,180',
            'venue_radius_meter' => 'required|integer|min:10|max:1000',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate event code
            $eventCode = 'EVT' . date('YmdHis') . strtoupper(substr(md5(uniqid()), 0, 4));

            $event = QRAttendanceEvent::create([
                'event_code' => $eventCode,
                'event_name' => $request->event_name,
                'event_date' => $request->event_date,
                'event_start_time' => $request->event_start_time,
                'event_end_time' => $request->event_end_time,
                'venue_name' => $request->venue_name,
                'venue_latitude' => $request->venue_latitude,
                'venue_longitude' => $request->venue_longitude,
                'venue_radius_meter' => $request->venue_radius_meter,
                'kode_cabang' => $request->kode_cabang,
                'description' => $request->description,
                'is_active' => true,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('qr-attendance.events.show', $event->id)
                ->with(messageSuccess('Event berhasil dibuat'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(messageError('Gagal membuat event: ' . $e->getMessage()));
        }
    }

    /**
     * Display the specified event
     */
    public function show($id)
    {
        $event = QRAttendanceEvent::with(['cabang', 'creator', 'attendances.jamaah'])
            ->findOrFail($id);

        $statistics = $event->getStatistics();
        $activeQR = $event->getActiveQRCode();

        // Get recent logs
        $recentLogs = $event->logs()
            ->with('jamaah')
            ->orderBy('scan_at', 'desc')
            ->limit(50)
            ->get();

        return view('qr-attendance.events.show', compact('event', 'statistics', 'activeQR', 'recentLogs'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit($id)
    {
        $event = QRAttendanceEvent::findOrFail($id);
        $cabang = Cabang::orderBy('kode_cabang')->get();
        
        return view('qr-attendance.events.edit', compact('event', 'cabang'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'event_name' => 'required|string|max:200',
            'event_date' => 'required|date',
            'event_start_time' => 'required',
            'event_end_time' => 'required',
            'venue_name' => 'nullable|string|max:200',
            'venue_latitude' => 'required|numeric|between:-90,90',
            'venue_longitude' => 'required|numeric|between:-180,180',
            'venue_radius_meter' => 'required|integer|min:10|max:1000',
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'description' => 'nullable|string',
        ]);

        try {
            $event = QRAttendanceEvent::findOrFail($id);

            $event->update([
                'event_name' => $request->event_name,
                'event_date' => $request->event_date,
                'event_start_time' => $request->event_start_time,
                'event_end_time' => $request->event_end_time,
                'venue_name' => $request->venue_name,
                'venue_latitude' => $request->venue_latitude,
                'venue_longitude' => $request->venue_longitude,
                'venue_radius_meter' => $request->venue_radius_meter,
                'kode_cabang' => $request->kode_cabang,
                'description' => $request->description,
            ]);

            return redirect()->route('qr-attendance.events.show', $event->id)
                ->with(messageSuccess('Event berhasil diupdate'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(messageError('Gagal mengupdate event: ' . $e->getMessage()));
        }
    }

    /**
     * Toggle event status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $event = QRAttendanceEvent::findOrFail($id);
            $event->update(['is_active' => !$event->is_active]);

            $status = $event->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->back()
                ->with(messageSuccess("Event berhasil {$status}"));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(messageError('Gagal mengubah status event: ' . $e->getMessage()));
        }
    }

    /**
     * Remove the specified event
     */
    public function destroy($id)
    {
        try {
            $event = QRAttendanceEvent::findOrFail($id);
            $event->delete();

            return redirect()->route('qr-attendance.events.index')
                ->with(messageSuccess('Event berhasil dihapus'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(messageError('Gagal menghapus event: ' . $e->getMessage()));
        }
    }

    /**
     * Generate QR Code for event
     */
    public function generateQR($id)
    {
        try {
            $event = QRAttendanceEvent::findOrFail($id);

            // Validasi: Event harus aktif
            if (!$event->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event tidak aktif'
                ], 400);
            }

            // Cek apakah sudah ada QR code aktif
            $existingQR = QRAttendanceCode::where('event_id', $event->id)
                ->where('is_active', true)
                ->first();

            if ($existingQR) {
                // Gunakan QR yang sudah ada (permanent)
                $qrCode = $existingQR;
                $token = $qrCode->qr_token;
            } else {
                // Generate token unik pertama kali
                $token = QRAttendanceCode::generateToken();

                // Buat QR code permanent (no expiry)
                $qrCode = QRAttendanceCode::create([
                    'event_id' => $event->id,
                    'qr_token' => $token,
                    'qr_hash' => bcrypt($token),
                    'generated_at' => now(),
                    'expired_at' => $event->event_date->copy()->addDays(1), // End of event day
                    'is_active' => true,
                ]);
            }

            // Generate URL untuk QR
            $url = route('qr-attendance.scan', ['token' => $token]);

            // Generate QR image as SVG (no imagick required)
            $qrImage = base64_encode(QrCode::format('svg')
                ->size(400)
                ->errorCorrection('H')
                ->generate($url));

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil digenerate',
                'data' => [
                    'qr_token' => $token,
                    'qr_image' => 'data:image/svg+xml;base64,' . $qrImage,
                    'url' => $url,
                    'is_permanent' => true,
                    'event_date' => $event->event_date->format('d F Y'),
                    'event_time' => date('H:i', strtotime($event->event_start_time)) . ' - ' . date('H:i', strtotime($event->event_end_time)),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display QR Code untuk ditampilkan di layar
     */
    public function displayQR($id)
    {
        $event = QRAttendanceEvent::findOrFail($id);
        $activeQR = $event->getActiveQRCode();

        return view('qr-attendance.events.display-qr', compact('event', 'activeQR'));
    }

    /**
     * Get event statistics (AJAX)
     */
    public function getStatistics($id)
    {
        try {
            $event = QRAttendanceEvent::findOrFail($id);
            $statistics = $event->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
