<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaDevice;
use App\Models\WaContact;
use App\Models\WaGroup;
use App\Models\WaMessage;
use App\Models\WaBroadcast;
use App\Models\WaTemplate;
use App\Models\Karyawan;
use App\Models\Departemen;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\FonteService;

class WhatsAppController extends Controller
{
    protected $fonnte;

    public function __construct(FonteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }
    /**
     * Dashboard WhatsApp - Halaman Utama
     */
    public function index()
    {
        $data['devices'] = WaDevice::orderBy('created_at', 'desc')->get();
        $data['totalDevices'] = WaDevice::where('is_active', true)->count();
        $data['connectedDevices'] = WaDevice::where('status', 'connected')->count();
        $data['totalMessages'] = WaMessage::whereDate('created_at', today())->count();
        $data['totalBroadcasts'] = WaBroadcast::where('status', 'scheduled')->count();
        $data['totalContacts'] = WaContact::count();
        $data['totalGroups'] = WaGroup::count();
        $data['recentMessages'] = WaMessage::with(['device', 'group'])
            ->where('direction', 'incoming')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('whatsapp.index', $data);
    }

    /**
     * Device Management - Halaman Kelola Device
     */
    public function devices()
    {
        $data['devices'] = WaDevice::withCount(['groups', 'messages'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('whatsapp.devices', $data);
    }

    /**
     * Add Device - Tambah Device Baru
     */
    public function addDevice(Request $request)
    {
        \Log::info('🔵 ADD DEVICE REQUEST', [
            'device_name' => $request->device_name,
            'api_key' => substr($request->api_key ?? 'NULL', 0, 15) . '...',
            'all_request' => $request->all()
        ]);

        $request->validate([
            'device_name' => 'required|string|max:100',
            'api_key' => 'required|string|max:100'
        ]);

        try {
            // Mode testing: Skip validasi jika API key dimulai dengan "test-"
            $isTestMode = str_starts_with($request->api_key, 'test-');
            
            \Log::info('🔵 Test Mode: ' . ($isTestMode ? 'YES' : 'NO'));
            
            if (!$isTestMode) {
                // Validasi API key dulu ke Fonnte
                \Log::info('🔵 Validating API key to Fonnte...');
                $validation = $this->fonnte->validateApiKey($request->api_key);
                \Log::info('🔵 Fonnte Validation Result', $validation);
                
                if (!$validation['success']) {
                    \Log::error('❌ API Key validation failed', $validation);
                    return response()->json([
                        'success' => false,
                        'message' => 'API Key tidak valid: ' . ($validation['message'] ?? 'Unknown error')
                    ], 400);
                }

                // Ambil nomor dari Fonnte
                $phoneNumber = $validation['data']['device'] ?? 'Unknown';
            } else {
                // Test mode: gunakan nomor dummy
                $phoneNumber = '628123456789';
                \Log::info('⚠️ TEST MODE: Device ditambahkan dengan API key test');
            }

            \Log::info('🔵 Creating device...', [
                'device_name' => $request->device_name,
                'phone_number' => $phoneNumber,
                'status' => $isTestMode ? 'testing' : 'connected'
            ]);

            $device = WaDevice::create([
                'device_name' => $request->device_name,
                'phone_number' => $phoneNumber,
                'api_key' => $request->api_key,
                'status' => $isTestMode ? 'testing' : 'connected',
                'is_active' => true
            ]);

            \Log::info('✅ Device created successfully', ['device_id' => $device->id]);

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil ditambahkan' . ($isTestMode ? ' (TEST MODE)' : ''),
                'device' => $device
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ ADD DEVICE ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan device: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Device
     */
    public function deleteDevice($id)
    {
        try {
            $device = WaDevice::findOrFail($id);
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus device: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Broadcast Center - Halaman Broadcast
     */
    public function broadcasts()
    {
        $data['broadcasts'] = WaBroadcast::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $data['departemen'] = Departemen::all();
        $data['jabatan'] = Jabatan::all();
        $data['groups'] = WaGroup::where('total_members', '>', 0)->get();
        
        return view('whatsapp.broadcasts', $data);
    }

    /**
     * Create Broadcast
     */
    public function createBroadcast(Request $request)
    {
        \Log::info('🔵 CREATE BROADCAST Request', [
            'title' => $request->title,
            'target_type' => $request->target_type,
            'target_filter' => $request->target_filter
        ]);
        
        $request->validate([
            'title' => 'required|string|max:100',
            'message_text' => 'required|string',
            'target_type' => 'required|in:all,departemen,jabatan,grup,custom',
            'target_filter' => 'nullable|array',
            'schedule_at' => 'nullable|date'
        ]);

        try {
            DB::beginTransaction();

            // Hitung total recipients berdasarkan filter
            $recipients = $this->getRecipients($request->target_type, $request->target_filter);
            
            \Log::info('🔵 Recipients found', ['count' => count($recipients)]);

            if (empty($recipients)) {
                throw new \Exception('Tidak ada penerima yang ditemukan. Pastikan sudah sync contacts terlebih dahulu.');
            }

            $broadcast = WaBroadcast::create([
                'title' => $request->title,
                'message_text' => $request->message_text,
                'media_url' => $request->media_url,
                'target_type' => $request->target_type,
                'target_filter' => $request->target_filter,
                'total_recipients' => count($recipients),
                'schedule_at' => $request->schedule_at,
                'status' => $request->schedule_at ? 'scheduled' : 'sending',
                'created_by' => Auth::id()
            ]);

            // Simpan recipients
            foreach ($recipients as $recipient) {
                $broadcast->recipients()->create([
                    'contact_id' => $recipient['contact_id'] ?? null,
                    'phone_number' => $recipient['phone_number'],
                    'status' => 'pending'
                ]);
            }

            DB::commit();

            // Jika tidak di-schedule, langsung kirim via Baileys
            if (!$request->schedule_at) {
                $this->sendBroadcast($broadcast);
            }

            return response()->json([
                'success' => true,
                'message' => 'Broadcast berhasil dibuat',
                'broadcast' => $broadcast
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat broadcast: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Recipients berdasarkan filter
     */
    private function getRecipients($targetType, $targetFilter)
    {
        $recipients = [];

        switch ($targetType) {
            case 'all':
                $contacts = WaContact::where('type', 'karyawan')->get();
                foreach ($contacts as $contact) {
                    $recipients[] = [
                        'contact_id' => $contact->id,
                        'phone_number' => $contact->phone_number
                    ];
                }
                break;

            case 'departemen':
                $karyawans = Karyawan::where('kode_dept', $targetFilter['departemen_id'])->get();
                foreach ($karyawans as $karyawan) {
                    $contact = WaContact::where('karyawan_nik', $karyawan->nik)->first();
                    if ($contact) {
                        $recipients[] = [
                            'contact_id' => $contact->id,
                            'phone_number' => $contact->phone_number
                        ];
                    }
                }
                break;

            case 'jabatan':
                $karyawans = Karyawan::where('kode_jabatan', $targetFilter['jabatan_id'])->get();
                foreach ($karyawans as $karyawan) {
                    $contact = WaContact::where('karyawan_nik', $karyawan->nik)->first();
                    if ($contact) {
                        $recipients[] = [
                            'contact_id' => $contact->id,
                            'phone_number' => $contact->phone_number
                        ];
                    }
                }
                break;

            case 'grup':
                // Untuk grup, ambil dari target_filter yang berisi array group_ids
                if (isset($targetFilter['group_ids'])) {
                    foreach ($targetFilter['group_ids'] as $groupId) {
                        $group = WaGroup::find($groupId);
                        if ($group) {
                            $recipients[] = [
                                'contact_id' => null,
                                'phone_number' => $group->group_jid // Group JID
                            ];
                        }
                    }
                }
                break;

            case 'custom':
                if (isset($targetFilter['phone_numbers'])) {
                    foreach ($targetFilter['phone_numbers'] as $phone) {
                        $recipients[] = [
                            'contact_id' => null,
                            'phone_number' => $phone
                        ];
                    }
                }
                break;
        }

        return $recipients;
    }

    /**
     * Sync Groups dari WhatsApp Device
     */
    public function syncGroups(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:wa_devices,id'
        ]);

        try {
            $device = WaDevice::findOrFail($request->device_id);
            
            // Check device status
            if ($device->status !== 'connected' || !$device->api_key) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device belum terhubung atau API Key tidak valid.'
                ], 400);
            }

            // Fetch groups dari Fonnte API
            $result = $this->fonnte->getGroups($device->api_key);

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Gagal mengambil data group dari Fonnte');
            }

            $groups = $result['data'] ?? [];
            $syncedCount = 0;

            foreach ($groups as $group) {
                WaGroup::updateOrCreate(
                    ['group_jid' => $group['id']],
                    [
                        'group_name' => $group['name'],
                        'description' => $group['description'] ?? '',
                        'total_members' => $group['total_members'] ?? 0,
                        'device_id' => $request->device_id
                    ]
                );
                $syncedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => $syncedCount . ' grup berhasil di-sync dari WhatsApp Anda'
            ]);
        } catch (\Exception $e) {
            \Log::error('Sync Groups Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync grup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Templates - Halaman Template Library
     */
    public function templates()
    {
        $data['templates'] = WaTemplate::orderBy('category')->orderBy('template_name')->get();
        
        return view('whatsapp.templates', $data);
    }

    /**
     * Contacts - Halaman Contact Management
     */
    public function contacts()
    {
        $data['contacts'] = WaContact::with('karyawan')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('whatsapp.contacts', $data);
    }

    /**
     * Sync Contacts dari Database Karyawan
     */
    public function syncContacts()
    {
        try {
            \Log::info('🔵 SYNC CONTACTS Started');
            
            $karyawans = Karyawan::whereNotNull('no_hp')
                ->where('no_hp', '!=', '')
                ->where('status_aktif_karyawan', '1')
                ->get();
            
            \Log::info('🔵 Found karyawan', ['count' => $karyawans->count()]);
            
            $synced = 0;

            foreach ($karyawans as $karyawan) {
                $phone = $this->formatPhoneNumber($karyawan->no_hp);
                
                if ($phone) {
                    WaContact::updateOrCreate(
                        ['phone_number' => $phone],
                        [
                            'name' => $karyawan->nama_karyawan,
                            'type' => 'karyawan',
                            'karyawan_nik' => $karyawan->nik
                        ]
                    );
                    $synced++;
                }
            }

            \Log::info('✅ SYNC CONTACTS Completed', ['synced' => $synced]);

            return response()->json([
                'success' => true,
                'message' => $synced . ' kontak karyawan berhasil di-sync'
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ SYNC CONTACTS Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync kontak: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send Broadcast via Fonnte API
     */
    private function sendBroadcast(WaBroadcast $broadcast)
    {
        try {
            // Ambil device pertama yang connected
            $device = WaDevice::where('status', 'connected')
                ->whereNotNull('api_key')
                ->first();

            if (!$device) {
                throw new \Exception('Tidak ada device yang terhubung. Silakan tambahkan device terlebih dahulu.');
            }

            $recipients = $broadcast->recipients()->where('status', 'pending')->get();
            
            // Prepare targets untuk Fonnte broadcast
            $targets = [];
            foreach ($recipients as $recipient) {
                $targets[] = [
                    'target' => $recipient->phone_number,
                    'recipient_id' => $recipient->id
                ];
            }

            // Kirim via Fonnte broadcast dengan delay 5 detik
            $result = $this->fonnte->broadcast(
                $device->api_key,
                $targets,
                $broadcast->message_text,
                5 // delay 5 detik
            );

            // Update status recipients berdasarkan hasil
            foreach ($result['results'] as $sendResult) {
                $recipient = $broadcast->recipients()->find($sendResult['recipient_id']);
                if ($recipient) {
                    $recipient->update([
                        'status' => $sendResult['success'] ? 'sent' : 'failed',
                        'sent_at' => $sendResult['success'] ? now() : null,
                        'error_message' => $sendResult['success'] ? null : $sendResult['message']
                    ]);
                }
            }

            // Update broadcast status
            $totalSent = $broadcast->recipients()->where('status', 'sent')->count();
            $totalFailed = $broadcast->recipients()->where('status', 'failed')->count();
            
            $broadcast->update([
                'total_sent' => $totalSent,
                'total_failed' => $totalFailed,
                'status' => ($totalFailed == 0) ? 'completed' : 'partial',
                'completed_at' => now()
            ]);

            \Log::info("Broadcast #{$broadcast->id} completed: {$totalSent} sent, {$totalFailed} failed");
        } catch (\Exception $e) {
            \Log::error("Broadcast #{$broadcast->id} error: " . $e->getMessage());
            $broadcast->update([
                'status' => 'failed',
                'completed_at' => now()
            ]);
        }
    }

    /**
     * Format nomor telepon ke format WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika sudah dimulai dengan 62, biarkan
        if (substr($phone, 0, 2) === '62') {
            return $phone;
        }
        
        // Jika tidak ada format yang cocok, tambahkan 62 di depan
        return '62' . $phone;
    }

    /**
     * Scan QR Code Page
     */
}


