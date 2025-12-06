<?php

namespace App\Http\Controllers;

use App\Models\JamaahMajlisTaklim;
use App\Models\KehadiranJamaah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JamaahExport;
use App\Imports\JamaahImport;
use Barryvdh\DomPDF\Facade\Pdf;

class JamaahMajlisTaklimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jamaah = JamaahMajlisTaklim::with(['kehadiran', 'distribusiHadiah'])
                        ->select('jamaah_majlis_taklim.*');

            // Apply filters
            if ($request->filled('tahun_masuk')) {
                $jamaah->where('tahun_masuk', $request->tahun_masuk);
            }

            if ($request->filled('status_aktif')) {
                $jamaah->where('status_aktif', $request->status_aktif);
            }

            if ($request->filled('status_umroh')) {
                $jamaah->where('status_umroh', $request->status_umroh);
            }

            return DataTables::of($jamaah)
                ->addIndexColumn()
                ->addColumn('badge_kehadiran', function ($row) {
                    $badge = $row->badge_color;
                    return '<span class="badge bg-' . $badge . '">' . $row->jumlah_kehadiran . ' kali</span>';
                })
                ->addColumn('status_umroh_badge', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $checked = $row->status_umroh ? 'checked' : '';
                    $bgColor = $row->status_umroh ? 'bg-success' : 'bg-secondary';
                    
                    return '<div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input toggle-umroh" type="checkbox" 
                                       data-id="' . $encryptedId . '" ' . $checked . ' 
                                       style="cursor: pointer; width: 50px; height: 25px;">
                                <label class="form-check-label ms-2">
                                    <span class="badge ' . $bgColor . ' badge-umroh-' . $row->id . '">
                                        ' . ($row->status_umroh ? '<i class="ti ti-plane"></i> Sudah' : 'Belum') . '
                                    </span>
                                </label>
                            </div>';
                })
                ->addColumn('status_aktif_badge', function ($row) {
                    if ($row->status_aktif == 'aktif') {
                        return '<span class="badge bg-success">Aktif</span>';
                    }
                    return '<span class="badge bg-danger">Non Aktif</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<div class="btn-group" role="group">';
                    
                    // Button Get Data Mesin (tampil jika ada PIN)
                    if (!empty($row->pin_fingerprint)) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success btngetDatamesin" 
                                 data-id="' . $encryptedId . '" 
                                 data-pin="' . $row->pin_fingerprint . '" 
                                 title="Get Data Mesin Fingerspot">
                                 <i class="ti ti-device-desktop"></i>
                                 </button>';
                    }
                    
                    $btn .= '<a href="' . route('majlistaklim.jamaah.show', $encryptedId) . '" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>';
                    $btn .= '<a href="' . route('majlistaklim.jamaah.edit', $encryptedId) . '" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn .= '<a href="' . route('majlistaklim.jamaah.downloadIdCard', $encryptedId) . '" class="btn btn-sm btn-primary" title="Download ID Card" target="_blank"><i class="ti ti-id"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $encryptedId . '" title="Hapus"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['badge_kehadiran', 'status_umroh_badge', 'status_aktif_badge', 'action'])
                ->make(true);
        }

        return view('majlistaklim.jamaah.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('majlistaklim.jamaah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jamaah' => 'required|string|max:100',
            'nik' => 'required|string|size:16|unique:jamaah_majlis_taklim,nik',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|integer|digits:4',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'pin_fingerprint' => 'nullable|string|max:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['foto', '_token']);

            // Set default values
            $data['status_umroh'] = false;
            $data['status_aktif'] = 'aktif';
            $data['jumlah_kehadiran'] = 0;

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/jamaah', $fotoName);
                $data['foto'] = 'jamaah/' . $fotoName;
            }

            // Generate temporary nomor jamaah (akan di-update setelah dapat ID)
            $data['nomor_jamaah'] = 'TEMP-' . uniqid();

            // Create jamaah
            $jamaah = JamaahMajlisTaklim::create($data);

            // Generate nomor jamaah final dengan ID asli
            $jamaah->nomor_jamaah = JamaahMajlisTaklim::generateNomorJamaah(
                $jamaah->nik,
                $jamaah->tahun_masuk,
                $jamaah->id
            );
            $jamaah->save();

            DB::commit();

            return redirect()->route('majlistaklim.jamaah.index')
                ->with('success', 'Data jamaah berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMajlisTaklim::with(['kehadiran', 'distribusiHadiah.hadiah', 'pemenangUndian.undian'])
                        ->findOrFail($id);

            // Statistik kehadiran per bulan
            $kehadiranPerBulan = KehadiranJamaah::where('jamaah_id', $id)
                ->selectRaw('MONTH(tanggal_kehadiran) as bulan, COUNT(*) as jumlah')
                ->whereYear('tanggal_kehadiran', date('Y'))
                ->groupBy('bulan')
                ->pluck('jumlah', 'bulan')
                ->toArray();

            return view('majlistaklim.jamaah.show', compact('jamaah', 'kehadiranPerBulan'));
        } catch (\Exception $e) {
            return redirect()->route('majlistaklim.jamaah.index')
                ->with('error', 'Data jamaah tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMajlisTaklim::findOrFail($id);
            return view('majlistaklim.jamaah.edit', compact('jamaah'));
        } catch (\Exception $e) {
            return redirect()->route('majlistaklim.jamaah.index')
                ->with('error', 'Data jamaah tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama_jamaah' => 'required|string|max:100',
            'nik' => 'required|string|size:16|unique:jamaah_majlis_taklim,nik,' . $id,
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|integer|digits:4',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'pin_fingerprint' => 'nullable|string|max:10',
            'tanggal_umroh' => 'nullable|date',
            'status_aktif' => 'required|in:aktif,non_aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $jamaah = JamaahMajlisTaklim::findOrFail($id);
            $data = $request->except(['foto', '_token', '_method']);

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($jamaah->foto) {
                    Storage::delete('public/jamaah/' . $jamaah->foto);
                }

                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/jamaah', $fotoName);
                $data['foto'] = $fotoName;
            }

            // Update status umroh
            $data['status_umroh'] = $request->has('status_umroh') ? true : false;

            $jamaah->update($data);

            DB::commit();

            return redirect()->route('majlistaklim.jamaah.index')
                ->with('success', 'Data jamaah berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMajlisTaklim::findOrFail($id);

            // Soft delete
            $jamaah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data jamaah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download ID Card jamaah dalam format PDF
     */
    public function downloadIdCard($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMajlisTaklim::findOrFail($id);
            $data['jamaah'] = $jamaah;
            return view('majlistaklim.jamaah.id_card', $data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Import jamaah dari Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'File harus berupa Excel (xlsx, xls, atau csv) dengan maksimal 5MB');
        }

        try {
            $import = new JamaahImport;
            Excel::import($import, $request->file('file'));
            
            // Cek apakah ada error validasi
            $failures = $import->failures();
            if ($failures->count() > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                }
                
                return redirect()->back()
                    ->with('error', 'Import gagal pada beberapa baris:<br>' . implode('<br>', array_slice($errorMessages, 0, 5)) . 
                           ($failures->count() > 5 ? '<br>...dan ' . ($failures->count() - 5) . ' error lainnya' : ''));
            }

            return redirect()->route('majlistaklim.jamaah.index')
                ->with('success', 'Data jamaah berhasil diimport');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->back()
                ->with('error', 'Validasi gagal:<br>' . implode('<br>', array_slice($errorMessages, 0, 5)));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    /**
     * Export jamaah ke Excel
     */
    public function export()
    {
        return Excel::download(new JamaahExport, 'data_jamaah_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Download template import Excel
     */
    public function downloadTemplate()
    {
        try {
            return Excel::download(new \App\Exports\JamaahTemplateExport(), 'Template_Import_Jamaah_' . date('Ymd') . '.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status umroh jamaah
     */
    public function toggleUmroh($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMajlisTaklim::findOrFail($id);
            
            // Toggle status
            $jamaah->status_umroh = !$jamaah->status_umroh;
            $jamaah->save();

            return response()->json([
                'success' => true,
                'message' => 'Status umroh berhasil diupdate',
                'status_umroh' => $jamaah->status_umroh
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel untuk import kehadiran
     */
    public function downloadTemplateKehadiran()
    {
        $jamaah = JamaahMajlisTaklim::orderBy('id')->get(['id', 'nomor_jamaah', 'nama_jamaah', 'jumlah_kehadiran']);
        
        return Excel::download(new class($jamaah) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $jamaah;
            
            public function __construct($jamaah)
            {
                $this->jamaah = $jamaah;
            }
            
            public function collection()
            {
                return $this->jamaah->map(function($j) {
                    return [
                        $j->jumlah_kehadiran // Hanya kolom jumlah_kehadiran
                    ];
                });
            }
            
            public function headings(): array
            {
                return ['jumlah_kehadiran'];
            }
        }, 'template_kehadiran_jamaah.xlsx');
    }

    /**
     * Import kehadiran dari Excel
     */
    public function importKehadiran(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new \App\Imports\KehadiranJamaahImport('majlis_taklim'); // Specify type
            Excel::import($import, $request->file('file'));

            $message = 'Import berhasil! ' . $import->getUpdatedCount() . ' data diupdate';
            
            if ($import->getErrorCount() > 0) {
                $message .= ', ' . $import->getErrorCount() . ' error';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated' => $import->getUpdatedCount(),
                'errors' => $import->getErrors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // FINGERSPOT CLOUD API INTEGRATION
    // ========================================

    /**
     * Get data absensi dari mesin Fingerspot Cloud API
     * Mirip dengan PresensiController@getdatamesin dan JamaahMasarController@getdatamesin
     */
    public function getdatamesin(Request $request)
    {
        $tanggal = $request->tanggal;
        $pin = $request->pin_fingerprint;
        $general_setting = \App\Models\Pengaturanumum::where('id', 1)->first();

        // Validasi setting
        if (empty($general_setting->cloud_id) || empty($general_setting->api_key)) {
            return view('majlistaklim.jamaah.getdatamesin_error', [
                'error' => 'Cloud ID atau API Key belum diatur. Silakan hubungi administrator.'
            ]);
        }

        // Setup API Request ke Fingerspot Cloud
        $url = 'https://developer.fingerspot.io/api/get_attlog';
        $data = json_encode([
            'trans_id' => '1',
            'cloud_id' => $general_setting->cloud_id,
            'start_date' => $tanggal,
            'end_date' => $tanggal
        ]);
        
        $authorization = "Authorization: Bearer " . $general_setting->api_key;

        // CURL Request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            $authorization
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Handle CURL Error
        if ($curlError) {
            return view('majlistaklim.jamaah.getdatamesin_error', [
                'error' => 'Gagal koneksi ke server Fingerspot: ' . $curlError
            ]);
        }

        // Parse Response
        $res = json_decode($result);
        
        if (!$res || !isset($res->data)) {
            return view('majlistaklim.jamaah.getdatamesin_error', [
                'error' => 'Gagal mengambil data dari mesin. HTTP Code: ' . $httpCode,
                'response' => $result
            ]);
        }

        $datamesin = $res->data;

        // Filter by PIN
        $filtered_array = array_filter($datamesin, function ($obj) use ($pin) {
            return $obj->pin == $pin;
        });

        // Return view dengan data
        return view('majlistaklim.jamaah.getdatamesin', compact('filtered_array'));
    }

    /**
     * Update presensi jamaah dari data mesin Fingerspot
     * Mirip dengan PresensiController@updatefrommachine dan JamaahMasarController@updatefrommachine
     */
    public function updatefrommachine(Request $request, $pin, $status_scan)
    {
        try {
            // Decrypt PIN
            $pin = Crypt::decrypt($pin);
            $scan_date = $request->scan_date;

            // Cari Jamaah by PIN
            $jamaah = JamaahMajlisTaklim::where('pin_fingerprint', $pin)->first();

            if ($jamaah == null) {
                return redirect()->back()->with('error', 'Jamaah dengan PIN ' . $pin . ' tidak ditemukan di database');
            }

            // Parse tanggal & jam dari scan_date
            $tanggal_scan = date("Y-m-d", strtotime($scan_date));
            $jam_scan = date("H:i:s", strtotime($scan_date));

            // Cek apakah sudah ada kehadiran di tanggal ini
            $kehadiran = KehadiranJamaah::where('jamaah_id', $jamaah->id)
                ->whereDate('tanggal_kehadiran', $tanggal_scan)
                ->first();

            // Status scan: 0,2,4,6,8 = MASUK | 1,3,5,7,9 = PULANG
            $is_masuk = in_array($status_scan, [0, 2, 4, 6, 8]);

            if ($kehadiran) {
                // Update existing record
                if ($is_masuk) {
                    // Update jam masuk jika belum ada
                    if (empty($kehadiran->jam_masuk)) {
                        $kehadiran->jam_masuk = $jam_scan;
                        $kehadiran->save();
                        $message = 'Berhasil update JAM MASUK untuk ' . $jamaah->nama_jamaah;
                    } else {
                        return redirect()->back()->with('warning', 'Jamaah ' . $jamaah->nama_jamaah . ' sudah absen MASUK pada ' . $tanggal_scan);
                    }
                } else {
                    // Update jam pulang jika belum ada
                    if (empty($kehadiran->jam_pulang)) {
                        $kehadiran->jam_pulang = $jam_scan;
                        $kehadiran->save();
                        $message = 'Berhasil update JAM PULANG untuk ' . $jamaah->nama_jamaah;
                    } else {
                        return redirect()->back()->with('warning', 'Jamaah ' . $jamaah->nama_jamaah . ' sudah absen PULANG pada ' . $tanggal_scan);
                    }
                }
            } else {
                // Create new kehadiran record
                $kehadiran = new KehadiranJamaah();
                $kehadiran->jamaah_id = $jamaah->id;
                $kehadiran->tanggal_kehadiran = $tanggal_scan;
                $kehadiran->status = 'hadir';
                $kehadiran->keterangan = 'Absensi dari mesin fingerprint (PIN: ' . $pin . ')';
                
                if ($is_masuk) {
                    $kehadiran->jam_masuk = $jam_scan;
                    $message = 'Berhasil simpan JAM MASUK untuk ' . $jamaah->nama_jamaah;
                } else {
                    $kehadiran->jam_pulang = $jam_scan;
                    $message = 'Berhasil simpan JAM PULANG untuk ' . $jamaah->nama_jamaah;
                }
                
                $kehadiran->save();

                // Update jumlah kehadiran di tabel jamaah_majlis_taklim
                $jamaah->increment('jumlah_kehadiran');
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Test koneksi ke mesin fingerprint (untuk diagnostic)
     */
    public function testConnection(Request $request)

    {
        try {
            $ip = config('app.zkteco_ip', '192.168.1.201');
            $port = config('app.zkteco_port', 4370);
            $timeout = config('app.zkteco_timeout', 10);
            
            $zkService = new \App\Services\ZKTecoService($ip, $port, $timeout);

            // Test koneksi
            if (!$zkService->connect()) {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal koneksi ke mesin di {$ip}:{$port}",
                    'details' => [
                        'ip' => $ip,
                        'port' => $port,
                        'status' => 'offline'
                    ]
                ], 500);
            }

            // Ambil device info
            $deviceInfo = $zkService->getDeviceInfo();
            
            // Disconnect
            $zkService->disconnect();

            return response()->json([
                'success' => true,
                'message' => 'Koneksi berhasil!',
                'details' => [
                    'ip' => $ip,
                    'port' => $port,
                    'status' => 'online',
                    'device_info' => $deviceInfo
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error test connection: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance data dari mesin fingerprint dan tampilkan untuk preview
     */
    public function fetchDataFromMachine(Request $request)
    {
        try {
            $zkService = new \App\Services\ZKTecoService(
                config('app.zkteco_ip', '192.168.1.201'),
                config('app.zkteco_port', 4370)
            );

            // Koneksi ke mesin
            if (!$zkService->connect()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal koneksi ke mesin fingerprint di ' . config('app.zkteco_ip', '192.168.1.201') . ':' . config('app.zkteco_port', 4370)
                ], 500);
            }

            // Ambil attendance data
            $attendance = $zkService->getAttendance();
            
            if ($attendance === false) {
                $zkService->disconnect();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data attendance dari mesin'
                ], 500);
            }

            // Format attendance data
            $formattedData = $zkService->formatAttendance($attendance);
            
            // Disconnect dari mesin
            $zkService->disconnect();

            // Cek apakah ada data
            if (empty($formattedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data attendance di mesin'
                ]);
            }

            // Enrich data dengan info jamaah
            $enrichedData = [];
            foreach ($formattedData as $record) {
                $pin = $record['pin'];
                
                // Cari jamaah berdasarkan PIN
                $jamaah = JamaahMajlisTaklim::where('pin_fingerprint', $pin)->first();
                
                $enrichedData[] = [
                    'pin' => $pin,
                    'tanggal' => $record['tanggal'],
                    'jam' => $record['jam'],
                    'timestamp' => $record['timestamp'],
                    'type' => $record['type'],
                    'nama_jamaah' => $jamaah ? $jamaah->nama_jamaah : 'PIN Tidak Terdaftar',
                    'nomor_jamaah' => $jamaah ? $jamaah->nomor_jamaah : '-',
                    'jamaah_id' => $jamaah ? $jamaah->id : null,
                    'status' => $jamaah ? 'Terdaftar' : 'Tidak Terdaftar'
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil ambil ' . count($enrichedData) . ' data dari mesin',
                'data' => $enrichedData,
                'total' => count($enrichedData)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetch data from machine: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // METHODS UNTUK KARYAWAN (VIEW ONLY)
    // ========================================

    /**
     * Display a listing of jamaah for karyawan (VIEW ONLY)
     * Menampilkan data dari YayasanMasar saja (Majlis Taklim data tidak dipakai lagi)
     */
    public function indexKaryawan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Data dari YayasanMasar dengan kehadiran terintegrasi
            $yayasanMasar = \App\Models\YayasanMasar::where('status_aktif', 1)->get();
            $allData = $yayasanMasar->map(function($item) {
                try {
                    // Get presensi yayasan (if exists)
                    $presensi_terbaru = $item->presensi()->latest('tanggal')->first();
                    $status_kehadiran_hari_ini = \App\Models\PresensiYayasan::where('kode_yayasan', $item->kode_yayasan)
                        ->whereDate('tanggal', today())
                        ->exists() ? 'Hadir' : 'Belum';
                    
                    // Format tanggal dengan aman
                    $kehadiran_terbaru_text = '-';
                    if ($presensi_terbaru) {
                        $tanggal = is_string($presensi_terbaru->tanggal) 
                            ? \Carbon\Carbon::parse($presensi_terbaru->tanggal) 
                            : $presensi_terbaru->tanggal;
                        $kehadiran_terbaru_text = $tanggal->format('d M Y');
                    }
                    
                    // Get jam_in dengan aman
                    $jam_in_value = null;
                    if ($presensi_terbaru) {
                        $jam_in_value = is_string($presensi_terbaru->jam_in) 
                            ? $presensi_terbaru->jam_in 
                            : $presensi_terbaru->jam_in;
                    }
                    
                    return [
                        'id' => $item->kode_yayasan,
                        'encrypted_id' => Crypt::encrypt($item->kode_yayasan),
                        'nomor_jamaah' => $item->kode_yayasan,
                        'nama_jamaah' => $item->nama,
                        'nik' => $item->no_identitas,
                        'no_hp' => $item->no_hp,
                        'alamat' => $item->alamat,
                        'tahun_masuk' => date('Y', strtotime($item->tanggal_masuk)),
                        'jumlah_kehadiran' => \App\Models\PresensiYayasan::where('kode_yayasan', $item->kode_yayasan)->count(),
                        'status_aktif' => 'aktif',
                        'status_umroh' => '-',
                        'foto_jamaah' => null,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'type' => 'yayasan',
                        'kehadiran_terbaru' => $kehadiran_terbaru_text,
                        'status_kehadiran_hari_ini' => $status_kehadiran_hari_ini,
                        'jam_masuk' => $jam_in_value,
                    ];
                } catch (\Exception $e) {
                    // Return default data jika ada error
                    return [
                        'id' => $item->kode_yayasan,
                        'encrypted_id' => Crypt::encrypt($item->kode_yayasan),
                        'nomor_jamaah' => $item->kode_yayasan,
                        'nama_jamaah' => $item->nama,
                        'nik' => $item->no_identitas,
                        'no_hp' => $item->no_hp,
                        'alamat' => $item->alamat,
                        'tahun_masuk' => date('Y', strtotime($item->tanggal_masuk)),
                        'jumlah_kehadiran' => 0,
                        'status_aktif' => 'aktif',
                        'status_umroh' => '-',
                        'foto_jamaah' => null,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'type' => 'yayasan',
                        'kehadiran_terbaru' => '-',
                        'status_kehadiran_hari_ini' => 'Belum',
                        'jam_masuk' => null,
                    ];
                }
            });

            // Return only YayasanMasar data (Majlis Taklim data no longer used)
            return response()->json([
                'success' => true,
                'data' => $allData->values()
            ]);
        }

        return view('majlistaklim.karyawan.jamaah.index');
    }

    /**
     * Display the specified jamaah for karyawan (VIEW ONLY)
     */
    public function showKaryawan($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMajlisTaklim::with(['kehadiran.jadwal', 'distribusiHadiah.hadiah'])->findOrFail($id);
            
            return view('majlistaklim.karyawan.jamaah.show', compact('jamaah'));
        } catch (\Exception $e) {
            return redirect()->route('majlistaklim.karyawan.jamaah.index')
                ->with('error', 'Data jamaah tidak ditemukan');
        }
    }

    // ========================================
    // FINGERSPOT INTEGRATION - GET DATA MESIN
    // ========================================
}

