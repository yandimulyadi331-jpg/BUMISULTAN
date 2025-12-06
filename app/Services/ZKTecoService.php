<?php

namespace App\Services;

use TCPDF;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Log;
use Exception;

class ZKTecoService
{
    protected $ip;
    protected $port;
    protected $zk;
    protected $isConnected = false;
    protected $timeout;

    /**
     * Konstruktor untuk inisialisasi koneksi ke mesin ZKTeco
     * 
     * @param string $ip IP Address mesin fingerprint
     * @param int $port Port mesin fingerprint (default: 4370)
     * @param int $timeout Connection timeout in seconds (default: 10)
     */
    public function __construct($ip = '192.168.1.201', $port = 4370, $timeout = 10)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->timeout = $timeout;
        
        // Set connection timeout
        ini_set('default_socket_timeout', $timeout);
        set_time_limit(120); // Max execution time 2 minutes
        
        $this->zk = new ZKTeco($ip, $port);
    }

    /**
     * Koneksi ke mesin fingerprint
     * 
     * @return bool
     */
    public function connect()
    {
        try {
            $this->isConnected = $this->zk->connect();
            
            if ($this->isConnected) {
                Log::info("Berhasil koneksi ke mesin ZKTeco di {$this->ip}:{$this->port}");
            } else {
                Log::error("Gagal koneksi ke mesin ZKTeco di {$this->ip}:{$this->port}");
            }
            
            return $this->isConnected;
        } catch (Exception $e) {
            Log::error("Error koneksi ke mesin ZKTeco: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Disconnect dari mesin fingerprint
     * 
     * @return bool
     */
    public function disconnect()
    {
        try {
            if ($this->isConnected) {
                $this->zk->disconnect();
                $this->isConnected = false;
                Log::info("Berhasil disconnect dari mesin ZKTeco");
            }
            return true;
        } catch (Exception $e) {
            Log::error("Error disconnect dari mesin ZKTeco: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ambil semua data attendance dari mesin
     * 
     * @return array|false
     */
    public function getAttendance()
    {
        try {
            if (!$this->isConnected) {
                if (!$this->connect()) {
                    return false;
                }
            }

            // Disable device untuk menghindari conflict saat ambil data
            $this->zk->disableDevice();
            
            // Ambil attendance log
            $attendance = $this->zk->getAttendance();
            
            // Enable device kembali
            $this->zk->enableDevice();
            
            Log::info("Berhasil ambil " . count($attendance) . " data attendance dari mesin");
            
            return $attendance;
        } catch (Exception $e) {
            Log::error("Error saat ambil attendance: " . $e->getMessage());
            $this->zk->enableDevice(); // Pastikan device di-enable kembali
            return false;
        }
    }

    /**
     * Ambil semua data user dari mesin
     * 
     * @return array|false
     */
    public function getUsers()
    {
        try {
            if (!$this->isConnected) {
                if (!$this->connect()) {
                    return false;
                }
            }

            // Disable device
            $this->zk->disableDevice();
            
            // Ambil user data
            $users = $this->zk->getUser();
            
            // Enable device kembali
            $this->zk->enableDevice();
            
            Log::info("Berhasil ambil " . count($users) . " data user dari mesin");
            
            return $users;
        } catch (Exception $e) {
            Log::error("Error saat ambil users: " . $e->getMessage());
            $this->zk->enableDevice();
            return false;
        }
    }

    /**
     * Set user (PIN) ke mesin fingerprint
     * Digunakan untuk registrasi PIN jamaah ke mesin
     * 
     * @param int $uid User ID / PIN
     * @param int $userid User ID
     * @param string $name Nama user
     * @param string $password Password (optional)
     * @param int $role Role (0=User, 14=Admin)
     * @return bool
     */
    public function setUser($uid, $userid, $name, $password = '', $role = 0)
    {
        try {
            if (!$this->isConnected) {
                if (!$this->connect()) {
                    return false;
                }
            }

            // Disable device
            $this->zk->disableDevice();
            
            // Set user ke mesin
            $result = $this->zk->setUser($uid, $userid, $name, $password, $role);
            
            // Enable device kembali
            $this->zk->enableDevice();
            
            if ($result) {
                Log::info("Berhasil set user PIN:{$uid} Name:{$name} ke mesin");
            } else {
                Log::error("Gagal set user PIN:{$uid} Name:{$name} ke mesin");
            }
            
            return $result;
        } catch (Exception $e) {
            Log::error("Error saat set user: " . $e->getMessage());
            $this->zk->enableDevice();
            return false;
        }
    }

    /**
     * Hapus user dari mesin
     * 
     * @param int $uid User ID / PIN
     * @return bool
     */
    public function deleteUser($uid)
    {
        try {
            if (!$this->isConnected) {
                if (!$this->connect()) {
                    return false;
                }
            }

            // Disable device
            $this->zk->disableDevice();
            
            // Delete user
            $result = $this->zk->deleteUser($uid);
            
            // Enable device kembali
            $this->zk->enableDevice();
            
            if ($result) {
                Log::info("Berhasil hapus user PIN:{$uid} dari mesin");
            } else {
                Log::error("Gagal hapus user PIN:{$uid} dari mesin");
            }
            
            return $result;
        } catch (Exception $e) {
            Log::error("Error saat hapus user: " . $e->getMessage());
            $this->zk->enableDevice();
            return false;
        }
    }

    /**
     * Clear attendance log dari mesin
     * HATI-HATI: Ini akan menghapus semua data attendance di mesin!
     * 
     * @return bool
     */
    public function clearAttendance()
    {
        try {
            if (!$this->isConnected) {
                if (!$this->connect()) {
                    return false;
                }
            }

            // Disable device
            $this->zk->disableDevice();
            
            // Clear attendance
            $result = $this->zk->clearAttendance();
            
            // Enable device kembali
            $this->zk->enableDevice();
            
            if ($result) {
                Log::info("Berhasil clear attendance log dari mesin");
            } else {
                Log::error("Gagal clear attendance log dari mesin");
            }
            
            return $result;
        } catch (Exception $e) {
            Log::error("Error saat clear attendance: " . $e->getMessage());
            $this->zk->enableDevice();
            return false;
        }
    }

    /**
     * Get device info (Serial Number, MAC, Firmware, dll)
     * 
     * @return array|false
     */
    public function getDeviceInfo()
    {
        try {
            if (!$this->isConnected) {
                if (!$this->connect()) {
                    return false;
                }
            }

            $info = [
                'serial_number' => $this->zk->serialNumber(),
                'platform' => $this->zk->platform(),
                'firmware_version' => $this->zk->fmVersion(),
                'work_code' => $this->zk->workCode(),
                'ssr' => $this->zk->ssr(),
                'pin_width' => $this->zk->pinWidth(),
                'face_function' => $this->zk->faceFunctionOn(),
                'device_name' => $this->zk->deviceName(),
            ];

            Log::info("Berhasil ambil device info", $info);
            
            return $info;
        } catch (Exception $e) {
            Log::error("Error saat ambil device info: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format attendance data untuk kemudahan processing
     * Convert array attendance dari mesin ke format standard
     * 
     * @param array $attendance Raw attendance data dari mesin
     * @return array Formatted attendance data
     */
    public function formatAttendance($attendance)
    {
        $formatted = [];
        
        foreach ($attendance as $record) {
            $formatted[] = [
                'pin' => $record['id'] ?? $record['uid'] ?? null,
                'timestamp' => $record['timestamp'] ?? null,
                'type' => $record['type'] ?? $record['state'] ?? 0, // 0=check in, 1=check out, dll
                'tanggal' => isset($record['timestamp']) ? date('Y-m-d', strtotime($record['timestamp'])) : null,
                'jam' => isset($record['timestamp']) ? date('H:i:s', strtotime($record['timestamp'])) : null,
            ];
        }
        
        return $formatted;
    }

    /**
     * Sync PIN jamaah ke mesin
     * Kirim data PIN dari database ke mesin fingerprint
     * 
     * @param array $jamaahData Array of jamaah [id, pin_fingerprint, nama_lengkap]
     * @return array [success_count, failed_count, errors]
     */
    public function syncJamaahToDevice($jamaahData)
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        if (!$this->connect()) {
            return [
                'success_count' => 0,
                'failed_count' => count($jamaahData),
                'errors' => ['Gagal koneksi ke mesin fingerprint']
            ];
        }

        foreach ($jamaahData as $jamaah) {
            $pin = $jamaah['pin_fingerprint'];
            $nama = $jamaah['nama_lengkap'];
            
            if (empty($pin)) {
                $failedCount++;
                $errors[] = "PIN tidak tersedia untuk {$nama}";
                continue;
            }

            $result = $this->setUser(
                $pin,           // UID
                $pin,           // User ID (sama dengan UID)
                $nama,          // Nama
                '',             // Password (kosong)
                0               // Role (0 = User biasa)
            );

            if ($result) {
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = "Gagal set PIN {$pin} untuk {$nama}";
            }
        }

        $this->disconnect();

        return [
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors
        ];
    }
}
