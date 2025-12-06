<?php

namespace App\Imports;

use App\Models\RealisasiDanaOperasional;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DanaOperasionalImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsEmptyRows,
    SkipsOnError,
    SkipsOnFailure
{
    /**
     * Import data dari Excel ke database
     * 
     * Format Excel yang diharapkan:
     * | tanggal | tipe | kategori | uraian | nominal |
     * 
     * Contoh:
     * | 2025-01-05 | pemasukan | Operasional | Saldo awal | 50000000 |
     * | 2025-01-10 | pengeluaran | Konsumsi | Belanja | 500000 |
     */
    public function model(array $row)
    {
        // Skip baris kosong
        if (empty($row['tanggal']) && empty($row['tipe']) && empty($row['nominal'])) {
            return null;
        }
        
        // Skip baris jika kolom penting kosong
        if (empty($row['tanggal']) || empty($row['tipe']) || empty($row['nominal']) || empty($row['uraian'])) {
            return null;
        }
        
        // Parse tanggal dari berbagai format
        $tanggal = $this->parseTanggal($row['tanggal']);
        
        // Clean nominal (hapus Rp, titik, koma)
        $nominal = $this->cleanNominal($row['nominal']);
        
        // Skip jika nominal 0 atau negatif
        if ($nominal <= 0) {
            return null;
        }
        
        // Tentukan tipe transaksi
        $tipe = strtolower(trim($row['tipe']));
        if (in_array($tipe, ['masuk', 'pemasukan', 'credit', 'cr', 'income'])) {
            $tipe = 'pemasukan';
        } elseif (in_array($tipe, ['keluar', 'pengeluaran', 'debit', 'db', 'expense'])) {
            $tipe = 'pengeluaran';
        } else {
            // Default ke pengeluaran jika tidak dikenali
            $tipe = 'pengeluaran';
        }
        
        return new RealisasiDanaOperasional([
            'pengajuan_id' => null, // Untuk data historis tidak terkait pengajuan
            'tanggal_realisasi' => $tanggal,
            'tipe_transaksi' => $tipe,
            'kategori' => $row['kategori'] ?? 'Umum',
            'uraian' => $row['uraian'] ?? '-',
            'keterangan' => $row['keterangan'] ?? $row['uraian'] ?? '-',
            'nominal' => $nominal,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Validasi data Excel
     */
    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'tipe' => 'required|string',
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:1',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'tanggal.required' => 'Kolom tanggal tidak boleh kosong',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tipe.required' => 'Kolom tipe tidak boleh kosong (isi: pemasukan atau pengeluaran)',
            'tipe.string' => 'Kolom tipe harus berupa text',
            'nominal.required' => 'Kolom nominal tidak boleh kosong',
            'nominal.numeric' => 'Kolom nominal harus berupa angka',
            'nominal.min' => 'Kolom nominal harus lebih dari 0',
            'uraian.required' => 'Kolom uraian tidak boleh kosong',
            'uraian.string' => 'Kolom uraian harus berupa text',
        ];
    }

    /**
     * Parse berbagai format tanggal
     */
    private function parseTanggal($value)
    {
        try {
            // Format ISO: 2025-01-05
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return Carbon::parse($value);
            }
            
            // Format Indonesia: 05/01/2025 atau 05-01-2025
            if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/', $value, $matches)) {
                return Carbon::createFromFormat('d/m/Y', $matches[1] . '/' . $matches[2] . '/' . $matches[3]);
            }
            
            // Excel date number (serial date)
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
            
            // Try general parse
            return Carbon::parse($value);
            
        } catch (\Exception $e) {
            // Default ke hari ini jika gagal parse
            return Carbon::now();
        }
    }

    /**
     * Clean nominal dari format Rp, titik, koma
     */
    private function cleanNominal($value)
    {
        // Hapus Rp, spasi, titik pemisah ribuan
        $cleaned = preg_replace('/[Rp\s\.]/', '', $value);
        
        // Ganti koma desimal dengan titik (jika ada)
        $cleaned = str_replace(',', '.', $cleaned);
        
        // Convert ke float
        return floatval($cleaned);
    }

    /**
     * Handle error saat import
     */
    public function onError(\Throwable $e)
    {
        Log::error('Import Error: ' . $e->getMessage());
    }

    /**
     * Handle validation failure
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::warning('Import Validation Failed - Row ' . $failure->row() . ': ' . implode(', ', $failure->errors()));
        }
    }
}
