<?php

namespace App\Http\Controllers;

use App\Models\QRAttendanceCode;
use App\Models\QRAttendanceEvent;
use App\Models\QRAttendanceLog;
use App\Models\JamaahDevice;
use App\Models\YayasanMasar;
use App\Models\PresensiYayasan;
use App\Models\Jamkerja;
use App\Services\GeolocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QRAttendanceController extends Controller
{
    /**
     * STEP 1: Scan QR Code (Initial Entry Point)
     */
    public function scan($token)
    {
        try {
            // LAPIS 1: Validasi QR Code
            $qrCode = QRAttendanceCode::with('event')
                ->where('qr_token', $token)
                ->where('is_active', true)
                ->first();

            if (!$qrCode) {
                return view('qr-attendance.error', [
                    'title' => 'QR Code Tidak Valid',
                    'message' => 'QR Code yang Anda scan tidak valid atau sudah tidak aktif.',
                    'icon' => 'ti-alert-circle'
                ]);
            }

            $event = $qrCode->event;

            // LAPIS 2: Validasi Event aktif
            if (!$event->is_active) {
                return view('qr-attendance.error', [
                    'title' => 'Event Tidak Aktif',
                    'message' => 'Event ini sedang tidak aktif.',
                    'icon' => 'ti-calendar-off',
                    'event' => $event
                ]);
            }

            // LAPIS 3: Validasi tanggal event (harus hari ini)
            if (!$event->event_date->isToday()) {
                return view('qr-attendance.error', [
                    'title' => 'Bukan Hari Event',
                    'message' => 'Event ini dijadwalkan pada ' . $event->event_date->format('d F Y') . '. Silakan scan pada hari event.',
                    'icon' => 'ti-calendar-x',
                    'event' => $event
                ]);
            }

            // LAPIS 4: Validasi Waktu Event (dalam jam operasional)
            if (!$event->isOngoing()) {
                QRAttendanceLog::logAttempt([
                    'event_id' => $event->id,
                    'qr_code_id' => $qrCode->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'status' => 'failed_time',
                    'failure_reason' => 'Event belum dimulai atau sudah selesai'
                ]);

                return view('qr-attendance.error', [
                    'title' => 'Di Luar Jam Event',
                    'message' => 'Absensi hanya bisa dilakukan pada pukul ' . date('H:i', strtotime($event->event_start_time)) . ' - ' . date('H:i', strtotime($event->event_end_time)) . '.',
                    'icon' => 'ti-clock-x',
                    'event' => $event
                ]);
            }

            // Langsung ke halaman daftar jamaah (tanpa login - untuk umum)
            return redirect()->route('qr-attendance.jamaah-list', ['token' => $token]);

        } catch (\Exception $e) {
            \Log::error('QR Attendance Scan Error: ' . $e->getMessage(), [
                'token' => $token,
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('qr-attendance.error', [
                'title' => 'Terjadi Kesalahan',
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'icon' => 'ti-alert-triangle'
            ]);
        }
    }

    /**
     * STEP 2: Show Login Form
     */
    public function showLogin($token)
    {
        $qrCode = QRAttendanceCode::with('event')
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->where('expired_at', '>', now())
            ->firstOrFail();

        return view('qr-attendance.login', [
            'token' => $token,
            'event' => $qrCode->event
        ]);
    }

    /**
     * STEP 3: Process Login & Device Binding
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'no_hp' => 'required|string',
            'pin' => 'required|numeric',
        ]);

        try {
            // Validasi QR masih valid
            $qrCode = QRAttendanceCode::with('event')
                ->where('qr_token', $request->token)
                ->where('is_active', true)
                ->first();

            if (!$qrCode) {
                return back()->with(messageError('QR Code tidak valid'));
            }

            // Validasi event masih berlangsung
            if (!$qrCode->event->isOngoing()) {
                return back()->with(messageError('Event sudah berakhir atau belum dimulai'));
            }

            // Cari jamaah berdasarkan no_hp dan pin
            $jamaah = YayasanMasar::where('no_hp', $request->no_hp)
                ->where('pin', $request->pin)
                ->where('status_aktif', '1')
                ->first();

            if (!$jamaah) {
                return back()->with(messageError('Nomor HP atau PIN tidak valid atau akun tidak aktif'));
            }

            // Generate device fingerprint
            $deviceId = JamaahDevice::generateFingerprint($request);
            $userAgent = $request->userAgent();

            // LAPIS 3: Device Binding Check
            $existingDevice = JamaahDevice::where('kode_yayasan', $jamaah->kode_yayasan)
                ->where('is_active', true)
                ->first();

            if ($existingDevice && $existingDevice->device_id != $deviceId) {
                // Jamaah sudah terdaftar di HP lain
                QRAttendanceLog::logAttempt([
                    'event_id' => $qrCode->event_id,
                    'qr_code_id' => $qrCode->id,
                    'kode_yayasan' => $jamaah->kode_yayasan,
                    'device_id' => $deviceId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'status' => 'failed_device',
                    'failure_reason' => 'Akun sudah terdaftar di perangkat lain'
                ]);

                return back()->with(messageError(
                    'Akun Anda sudah terdaftar di perangkat lain. ' .
                    'Hubungi admin untuk reset device jika Anda berganti HP.'
                ));
            }

            // Detect device info
            $os = JamaahDevice::detectOS($userAgent);
            $browser = JamaahDevice::detectBrowser($userAgent);
            $deviceModel = JamaahDevice::detectDeviceModel($userAgent);

            // Simpan atau update device
            JamaahDevice::updateOrCreate(
                ['kode_yayasan' => $jamaah->kode_yayasan],
                [
                    'device_id' => $deviceId,
                    'device_name' => $userAgent,
                    'device_model' => $deviceModel,
                    'os_name' => $os['name'],
                    'os_version' => $os['version'],
                    'browser' => $browser,
                    'last_login_at' => now(),
                    'is_active' => true
                ]
            );

            // Login jamaah
            Auth::login($jamaah, true);

            return redirect()->route('qr-attendance.form', ['token' => $request->token])
                ->with(messageSuccess("Selamat datang, {$jamaah->nama}!"));

        } catch (\Exception $e) {
            return back()->with(messageError('Terjadi kesalahan: ' . $e->getMessage()));
        }
    }

    /**
     * STEP 4: Show Attendance Form (with GPS request)
     */
    public function showForm($token)
    {
        $qrCode = QRAttendanceCode::with('event')
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $event = $qrCode->event;

        // Validasi event masih berlangsung
        if (!$event->event_date->isToday() || !$event->isOngoing()) {
            return view('qr-attendance.error', [
                'title' => 'Event Tidak Tersedia',
                'message' => 'Absensi hanya bisa dilakukan saat event berlangsung.',
                'icon' => 'ti-calendar-off',
                'event' => $event
            ]);
        }

        return view('qr-attendance.form', [
            'token' => $token,
            'event' => $event
        ]);
    }

    /**
     * STEP 5: Submit Attendance (Final Processing)
     */
    public function submit(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'no_hp' => 'required|string',
            'pin' => 'required|numeric',
            'nama' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo_selfie' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Cari jamaah berdasarkan no_hp dan pin
            $jamaah = YayasanMasar::where('no_hp', $request->no_hp)
                ->where('pin', $request->pin)
                ->where('status_aktif', '1')
                ->first();

            if (!$jamaah) {
                return back()->with(messageError('Nomor HP atau PIN tidak valid atau akun tidak aktif'));
            }

            // Validasi QR Code
            $qrCode = QRAttendanceCode::with('event')
                ->where('qr_token', $request->token)
                ->where('is_active', true)
                ->firstOrFail();

            $event = $qrCode->event;

            // Generate device ID
            $deviceId = JamaahDevice::generateFingerprint($request);

            // LAPIS 4: Geofencing Validation
            $geofence = GeolocationService::isWithinGeofence(
                $request->latitude,
                $request->longitude,
                $event->venue_latitude,
                $event->venue_longitude,
                $event->venue_radius_meter
            );

            if (!$geofence['is_within']) {
                // Log gagal
                QRAttendanceLog::logAttempt([
                    'event_id' => $event->id,
                    'qr_code_id' => $qrCode->id,
                    'kode_yayasan' => $jamaah->kode_yayasan,
                    'device_id' => $deviceId,
                    'scan_latitude' => $request->latitude,
                    'scan_longitude' => $request->longitude,
                    'distance_from_venue' => $geofence['distance'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed_geofence',
                    'failure_reason' => "Jarak {$geofence['distance']}m, melebihi radius {$event->venue_radius_meter}m"
                ]);

                DB::rollBack();

                return back()->with(messageError(
                    'Anda berada di luar area venue. Jarak Anda: ' . 
                    GeolocationService::formatDistance($geofence['distance']) . 
                    '. Maksimal radius: ' . 
                    GeolocationService::formatDistance($event->venue_radius_meter)
                ));
            }

            // Cek duplikasi absensi
            $existingAttendance = PresensiYayasan::where('kode_yayasan', $jamaah->kode_yayasan)
                ->where('tanggal', $event->event_date)
                ->whereNotNull('jam_in')
                ->first();

            if ($existingAttendance) {
                QRAttendanceLog::logAttempt([
                    'event_id' => $event->id,
                    'qr_code_id' => $qrCode->id,
                    'kode_yayasan' => $jamaah->kode_yayasan,
                    'device_id' => $deviceId,
                    'scan_latitude' => $request->latitude,
                    'scan_longitude' => $request->longitude,
                    'distance_from_venue' => $geofence['distance'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed_duplicate',
                    'failure_reason' => 'Sudah melakukan absensi pada ' . $existingAttendance->jam_in
                ]);

                DB::rollBack();

                return back()->with(messageError('Anda sudah melakukan absensi hari ini'));
            }

            // Handle foto selfie (LAPIS 5 - OPSIONAL)
            $photoPath = null;
            if ($request->hasFile('photo_selfie')) {
                $photo = $request->file('photo_selfie');
                $filename = 'attendance_' . $jamaah->kode_yayasan . '_' . time() . '.' . $photo->extension();
                $photoPath = $photo->storeAs('attendance-selfies', $filename, 'public');
            }

            // Get jam kerja default (bisa disesuaikan)
            $jamKerja = Jamkerja::where('kode_jam_kerja', 'JK01')->first();

            // SIMPAN PRESENSI ✅
            $attendance = PresensiYayasan::create([
                'kode_yayasan' => $jamaah->kode_yayasan,
                'tanggal' => $event->event_date,
                'jam_in' => now(),
                'lokasi_in' => $request->latitude . ',' . $request->longitude,
                'foto_in' => $photoPath,
                'kode_jam_kerja' => $jamKerja->kode_jam_kerja ?? 'JK01',
                'status' => 'h',
                'attendance_method' => 'qr_code',
                'qr_event_id' => $event->id,
                'device_id' => $deviceId
            ]);

            // Log sukses ✅
            QRAttendanceLog::logAttempt([
                'event_id' => $event->id,
                'qr_code_id' => $qrCode->id,
                'kode_yayasan' => $jamaah->kode_yayasan,
                'device_id' => $deviceId,
                'scan_latitude' => $request->latitude,
                'scan_longitude' => $request->longitude,
                'distance_from_venue' => $geofence['distance'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'photo_selfie' => $photoPath
            ]);

            // Increment scan count
            $qrCode->incrementScanCount();

            DB::commit();

            return redirect()->route('qr-attendance.success')
                ->with('attendance', $attendance)
                ->with('event', $event)
                ->with(messageSuccess('Absensi berhasil dicatat! Terima kasih.'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(messageError('Terjadi kesalahan: ' . $e->getMessage()));
        }
    }

    /**
     * Success page after attendance
     */
    public function success(Request $request)
    {
        // Support both old and new flow
        $attendance = session('attendance');
        $event = session('event');

        // New flow: get from query parameters
        if ($request->has('kode_yayasan') && $request->has('event_id')) {
            $jamaah = YayasanMasar::where('kode_yayasan', $request->kode_yayasan)->first();
            $event = QRAttendanceEvent::find($request->event_id);
            
            if ($jamaah && $event) {
                $jumlahKehadiran = PresensiYayasan::where('kode_yayasan', $jamaah->kode_yayasan)
                    ->where('qr_event_id', $event->id)
                    ->count();

                return view('qr-attendance.success', [
                    'jamaah' => $jamaah,
                    'event' => $event,
                    'jumlahKehadiran' => $jumlahKehadiran
                ]);
            }
        }

        // Old flow fallback
        if (!$attendance || !$event) {
            return redirect('/')->with(messageError('Session expired'));
        }

        return view('qr-attendance.success', compact('attendance', 'event'));
    }

    /**
     * Logout jamaah
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with(messageSuccess('Anda telah logout'));
    }

    /**
     * Request device reset (untuk ganti HP)
     */
    public function requestDeviceReset(Request $request)
    {
        $request->validate([
            'kode_yayasan' => 'required|exists:yayasan_masar,kode_yayasan',
            'no_hp' => 'required',
        ]);

        // Logika: Kirim notifikasi ke admin atau buat request
        // Untuk sementara langsung reset (dalam production harus approval admin)

        try {
            $jamaah = YayasanMasar::where('kode_yayasan', $request->kode_yayasan)
                ->where('no_hp', $request->no_hp)
                ->firstOrFail();

            // Nonaktifkan device lama
            JamaahDevice::where('kode_yayasan', $jamaah->kode_yayasan)
                ->update(['is_active' => false]);

            return back()->with(messageSuccess(
                'Request reset device berhasil. Silakan login kembali dengan HP baru Anda.'
            ));

        } catch (\Exception $e) {
            return back()->with(messageError('Gagal melakukan reset device'));
        }
    }

    /**
     * STEP 2 (NEW): Tampilkan Daftar Jamaah untuk Absensi
     */
    public function showJamaahList($token)
    {
        try {
            // Validasi QR Code
            $qrCode = QRAttendanceCode::with('event')
                ->where('qr_token', $token)
                ->where('is_active', true)
                ->first();

            if (!$qrCode) {
                return view('qr-attendance.error', [
                    'title' => 'QR Code Tidak Valid',
                    'message' => 'QR Code yang Anda scan tidak valid atau sudah tidak aktif.',
                    'icon' => 'ti-alert-circle'
                ]);
            }

            $event = $qrCode->event;

            // Validasi event masih berlangsung
            if (!$event->event_date->isToday() || !$event->isOngoing()) {
                return view('qr-attendance.error', [
                    'title' => 'Event Tidak Tersedia',
                    'message' => 'Absensi hanya bisa dilakukan saat event berlangsung.',
                    'icon' => 'ti-calendar-off',
                    'event' => $event
                ]);
            }

            // Ambil semua jamaah aktif dari YayasanMasar
            $jamaahList = YayasanMasar::where('status_aktif', '1')
                ->with(['departemen', 'cabang'])
                ->select('kode_yayasan', 'no_identitas', 'nama', 'no_hp', 'pin', 'foto', 'status', 'tanggal_masuk', 'kode_dept', 'kode_cabang')
                ->orderBy('nama', 'asc')
                ->get()
                ->map(function ($jamaah) use ($event) {
                    // Hitung jumlah kehadiran jamaah di event ini
                    $jumlahKehadiran = PresensiYayasan::where('kode_yayasan', $jamaah->kode_yayasan)
                        ->where('qr_event_id', $event->id)
                        ->count();
                    
                    $jamaah->jumlah_kehadiran = $jumlahKehadiran;
                    return $jamaah;
                });

            return view('qr-attendance.jamaah-list', [
                'token' => $token,
                'event' => $event,
                'jamaahList' => $jamaahList
            ]);

        } catch (\Exception $e) {
            \Log::error('QR Attendance Jamaah List Error: ' . $e->getMessage(), [
                'token' => $token,
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('qr-attendance.error', [
                'title' => 'Terjadi Kesalahan',
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'icon' => 'ti-alert-triangle'
            ]);
        }
    }

    /**
     * STEP 3 (NEW): Tampilkan Konfirmasi Absensi Jamaah
     */
    public function showConfirmAttendance($token, $kode_yayasan)
    {
        try {
            // Validasi QR Code
            $qrCode = QRAttendanceCode::with('event')
                ->where('qr_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $event = $qrCode->event;

            // Ambil data jamaah
            $jamaah = YayasanMasar::where('kode_yayasan', $kode_yayasan)
                ->where('status_aktif', '1')
                ->with(['departemen', 'cabang'])
                ->firstOrFail();

            // Hitung jumlah kehadiran
            $jumlahKehadiran = PresensiYayasan::where('kode_yayasan', $jamaah->kode_yayasan)
                ->where('qr_event_id', $event->id)
                ->count();

            return view('qr-attendance.confirm', [
                'token' => $token,
                'event' => $event,
                'jamaah' => $jamaah,
                'jumlahKehadiran' => $jumlahKehadiran
            ]);

        } catch (\Exception $e) {
            return back()->with(messageError('Jamaah tidak ditemukan atau tidak aktif'));
        }
    }

    /**
     * STEP 4 (NEW): Proses Absensi Jamaah (Simplified)
     */
    public function submitSimpleAttendance(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'kode_yayasan' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Validasi QR Code
            $qrCode = QRAttendanceCode::with('event')
                ->where('qr_token', $request->token)
                ->where('is_active', true)
                ->firstOrFail();

            $event = $qrCode->event;

            // Validasi jamaah
            $jamaah = YayasanMasar::where('kode_yayasan', $request->kode_yayasan)
                ->where('status_aktif', '1')
                ->firstOrFail();

            // Cek apakah sudah absen hari ini untuk event ini
            $sudahAbsen = PresensiYayasan::where('kode_yayasan', $jamaah->kode_yayasan)
                ->where('qr_event_id', $event->id)
                ->whereDate('tanggal_presensi', now()->toDateString())
                ->exists();

            if ($sudahAbsen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi untuk event ini hari ini'
                ], 400);
            }

            // Geofencing Validation
            $geofence = GeolocationService::isWithinGeofence(
                $request->latitude,
                $request->longitude,
                $event->venue_latitude,
                $event->venue_longitude,
                $event->venue_radius_meter
            );

            if (!$geofence['is_within']) {
                QRAttendanceLog::logAttempt([
                    'event_id' => $event->id,
                    'qr_code_id' => $qrCode->id,
                    'kode_yayasan' => $jamaah->kode_yayasan,
                    'scan_latitude' => $request->latitude,
                    'scan_longitude' => $request->longitude,
                    'distance_from_venue' => $geofence['distance'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed_geofence',
                    'failure_reason' => 'Lokasi terlalu jauh dari venue (' . round($geofence['distance']) . ' meter)'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi Anda terlalu jauh dari venue event (' . round($geofence['distance']) . ' meter). Jarak maksimal: ' . $event->venue_radius_meter . ' meter.'
                ], 400);
            }

            // Tentukan jam kerja berdasarkan waktu absen
            $jamKerja = Jamkerja::where('kode_cabang', $event->kode_cabang)
                ->where('kode_jam_kerja', 'JK01') // Default jam kerja
                ->first();

            // Simpan presensi
            $presensi = PresensiYayasan::create([
                'kode_yayasan' => $jamaah->kode_yayasan,
                'tanggal_presensi' => now()->toDateString(),
                'kode_jam_kerja' => $jamKerja ? $jamKerja->kode_jam_kerja : 'JK01',
                'jam_in' => now()->toTimeString(),
                'foto_in' => null,
                'keterangan' => 'Hadir via QR Code - ' . $event->event_name,
                'status' => 'h',
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'attendance_method' => 'qr_code',
                'qr_event_id' => $event->id,
            ]);

            // Log sukses
            QRAttendanceLog::logAttempt([
                'event_id' => $event->id,
                'qr_code_id' => $qrCode->id,
                'kode_yayasan' => $jamaah->kode_yayasan,
                'scan_latitude' => $request->latitude,
                'scan_longitude' => $request->longitude,
                'distance_from_venue' => $geofence['distance'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'presensi_id' => $presensi->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil! Selamat mengikuti ' . $event->event_name,
                'redirect_url' => route('qr-attendance.success', ['kode_yayasan' => $jamaah->kode_yayasan, 'event_id' => $event->id])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('QR Attendance Submit Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

