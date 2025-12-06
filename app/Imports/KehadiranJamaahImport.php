<?php

namespace App\Imports;

use App\Models\JamaahMajlisTaklim;
use App\Models\JamaahMasar;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class KehadiranJamaahImport implements ToCollection, WithHeadingRow
{
    protected $updatedCount = 0;
    protected $errorCount = 0;
    protected $errors = [];
    protected $type = 'masar'; // Default type

    /**
     * Constructor to set import type
     */
    public function __construct($type = 'masar')
    {
        $this->type = $type;
    }

    /**
     * Import kehadiran by urutan baris
     * Format Excel: 1 kolom saja (jumlah_kehadiran)
     * Baris 1 → Jamaah ID 1, Baris 2 → Jamaah ID 2, dst
     */
    public function collection(Collection $rows)
    {
        // Get all jamaah ordered by ID based on type
        if ($this->type === 'majlis_taklim') {
            $allJamaah = JamaahMajlisTaklim::orderBy('id')->get();
        } else {
            $allJamaah = JamaahMasar::orderBy('id')->get();
        }
        
        foreach ($rows as $index => $row) {
            try {
                // $index dimulai dari 0 (setelah header)
                // Ambil jamaah sesuai urutan
                if (isset($allJamaah[$index])) {
                    $jamaah = $allJamaah[$index];
                    
                    // Ambil nilai kehadiran dari kolom pertama (header: jumlah_kehadiran)
                    $jumlahKehadiran = isset($row['jumlah_kehadiran']) ? (int)$row['jumlah_kehadiran'] : 0;
                    
                    // Update jumlah_kehadiran
                    $jamaah->jumlah_kehadiran = $jumlahKehadiran;
                    $jamaah->save();
                    
                    $this->updatedCount++;
                } else {
                    $this->errorCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Jamaah tidak ditemukan";
                }
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
