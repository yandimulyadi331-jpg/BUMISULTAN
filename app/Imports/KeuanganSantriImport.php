<?php

namespace App\Imports;

use App\Models\KeuanganSantriTransaction;
use App\Models\KeuanganSantriCategory;
use App\Services\KeuanganSantriService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class KeuanganSantriImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $santriId;
    protected $keuanganService;
    protected $imported = 0;
    protected $skipped = 0;

    public function __construct($santriId)
    {
        $this->santriId = $santriId;
        $this->keuanganService = new KeuanganSantriService();
    }

    public function model(array $row)
    {
        // Skip jika data tidak lengkap
        if (empty($row['tanggal']) || empty($row['deskripsi']) || empty($row['jumlah'])) {
            $this->skipped++;
            return null;
        }

        try {
            // Parse tanggal
            $tanggal = $this->parseDate($row['tanggal']);

            // Determine jenis (default pengeluaran jika minus)
            $jumlah = $this->parseAmount($row['jumlah']);
            $jenis = 'pengeluaran';
            
            if (isset($row['jenis'])) {
                $jenis = strtolower($row['jenis']) === 'pemasukan' ? 'pemasukan' : 'pengeluaran';
            } elseif ($jumlah > 0 && isset($row['kredit']) && $row['kredit']) {
                $jenis = 'pemasukan';
            }

            // Auto-detect kategori
            $deskripsi = $row['deskripsi'];
            $category = $this->keuanganService->detectCategory($deskripsi, $jenis);

            // Override kategori jika ada di Excel
            if (isset($row['kategori']) && !empty($row['kategori'])) {
                $categoryFromExcel = KeuanganSantriCategory::where('nama_kategori', 'LIKE', '%' . $row['kategori'] . '%')
                    ->where('jenis', $jenis)
                    ->first();
                if ($categoryFromExcel) {
                    $category = $categoryFromExcel;
                }
            }

            // Prepare data
            $data = [
                'santri_id' => $this->santriId,
                'category_id' => $category ? $category->id : null,
                'jenis' => $jenis,
                'jumlah' => abs($jumlah),
                'tanggal_transaksi' => $tanggal,
                'deskripsi' => $deskripsi,
                'catatan' => $row['catatan'] ?? null,
                'metode_pembayaran' => $row['metode_pembayaran'] ?? 'Tunai',
                'created_by' => Auth::id(),
            ];

            // Create transaction menggunakan service
            $this->keuanganService->createTransaction($data);
            $this->imported++;

            return null; // Service sudah handle pembuatan

        } catch (\Exception $e) {
            $this->skipped++;
            \Log::error('Import error: ' . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required',
            'deskripsi' => 'required',
            'jumlah' => 'required',
        ];
    }

    protected function parseDate($date)
    {
        if ($date instanceof \DateTime) {
            return Carbon::instance($date);
        }

        // Try various date formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
        
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date);
            } catch (\Exception $e) {
                continue;
            }
        }

        // Default to today if parsing fails
        return now();
    }

    protected function parseAmount($amount)
    {
        // Remove currency symbols and separators
        $amount = str_replace(['Rp', '.', ',', ' '], '', $amount);
        return floatval($amount);
    }

    public function getImportedCount()
    {
        return $this->imported;
    }

    public function getSkippedCount()
    {
        return $this->skipped;
    }
}
