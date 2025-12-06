<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonteService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.fonnte.url', 'https://api.fonnte.com');
    }

    /**
     * Validate Fonnte API Key
     */
    public function validateApiKey(string $apiKey): array
    {
        try {
            // Fonnte endpoint untuk cek device status
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post($this->baseUrl . '/device');

            Log::info('🔵 Fonnte Validation Raw Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Fonnte response format: {valid: 1, device: "xxx", status: "xxx"}
                // atau bisa juga {status: true, device: "xxx"}
                $isValid = (isset($data['valid']) && $data['valid'] == 1) 
                        || (isset($data['status']) && $data['status'] === true);
                
                return [
                    'success' => $isValid,
                    'valid' => $isValid,
                    'data' => [
                        'device' => $data['device'] ?? $data['number'] ?? 'Unknown',
                        'status' => $data['status'] ?? 'active'
                    ],
                    'message' => $isValid ? 'API Key valid' : 'API Key tidak valid',
                    'raw' => $data // untuk debug
                ];
            }

            return [
                'success' => false,
                'valid' => false,
                'message' => 'Invalid API key or connection failed: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte validation error: ' . $e->getMessage());
            return [
                'success' => false,
                'valid' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send message to WhatsApp number
     */
    public function sendMessage(string $apiKey, string $number, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post($this->baseUrl . '/send', [
                'target' => $number,
                'message' => $message,
                'countryCode' => '62'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'status' => false,
                'reason' => $response->json('reason') ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte send message error: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Send message to WhatsApp group
     */
    public function sendToGroup(string $apiKey, string $groupId, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post($this->baseUrl . '/send', [
                'target' => $groupId,
                'message' => $message
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'status' => false,
                'reason' => $response->json('reason') ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte send group message error: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Get all WhatsApp groups
     */
    public function getGroups(string $apiKey): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post($this->baseUrl . '/get-groups');

            if ($response->successful()) {
                $data = $response->json();
                
                $groups = [];
                foreach ($data as $group) {
                    $groups[] = [
                        'groupJid' => $group['id'] ?? '',
                        'groupName' => $group['subject'] ?? 'Unknown',
                        'description' => $group['desc'] ?? '',
                        'totalMembers' => $group['size'] ?? 0
                    ];
                }

                return ['success' => true, 'groups' => $groups];
            }

            return [
                'success' => false,
                'error' => $response->json('reason') ?? 'Failed to fetch groups',
                'groups' => []
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte get groups error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'groups' => []
            ];
        }
    }

    /**
     * Broadcast to multiple targets (numbers or groups)
     */
    public function broadcast(string $apiKey, array $targets, string $message, int $delay = 5): array
    {
        $results = [];

        foreach ($targets as $index => $target) {
            try {
                // Delay between messages to avoid spam
                if ($index > 0) {
                    sleep($delay);
                }

                $result = $this->sendMessage($apiKey, $target, $message);
                
                $results[] = [
                    'target' => $target,
                    'status' => $result['status'] ?? false,
                    'reason' => $result['reason'] ?? 'Success',
                    'sentAt' => now()
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'target' => $target,
                    'status' => false,
                    'reason' => $e->getMessage(),
                    'sentAt' => now()
                ];
            }
        }

        return $results;
    }

    /**
     * Get device status
     */
    public function getDeviceStatus(string $apiKey): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post($this->baseUrl . '/device');

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'status' => 'disconnected',
                'device' => null
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte device status error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'device' => null,
                'error' => $e->getMessage()
            ];
        }
    }
}
