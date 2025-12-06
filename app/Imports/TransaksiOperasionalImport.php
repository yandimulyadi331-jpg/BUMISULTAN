<?php

namespace App\Imports;

use App\Models\RealisasiDanaOperasional;
use App\Models\PengajuanDanaOperasional;
use App\Models\SaldoHarianOperasional;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiOperasionalImport implements ToCollection, WithHeadingRow, WithStartRow
{
    protected $pengajuanId;
    protected $errors = [];
    protected $rowNumber = 0;

    public function __construct($pengajuanId = null)
    {
        $this->pengajuanId = $pengajuanId;
    }
    
    /**
     * Header ada di baris ke-13 (setelah panduan dan contoh)
     */
    public function startRow(): int
    {
        return 13;
    }
    
    /**
     * Heading row adalah baris 13
     */
    public function headingRow(): int
    {
        return 13;
    }

    /**
     * LOGIKA BARU: Import seluruh collection sekaligus untuk hitung saldo running
     * Seperti Excel: setiap baris menghitung saldo dari baris sebelumnya
     */
    public function collection(Collection $rows)
    {
        // Ambil saldo awal (dari kemarin atau 0)
        $saldoRunning = SaldoHarianOperasional::getSaldoKemarin();
        
        $userId = Auth::id() ?? 1;
        $urutanBaris = 1; // Tracking urutan baris Excel (mulai dari 1)
        
        // Array untuk menyimpan data transaksi yang akan di-insert batch
        $dataToInsert = [];
        
        foreach ($rows as $row) {
            // Parse data dari row Excel
            $tanggalInput = $row['tanggal'] ?? $row['date'] ?? $row['tgl'] ?? null;
            $keterangan = $row['keterangan_remarks'] ?? $row['keterangan'] ?? $row['remarks'] ?? null;
            $danaKeluar = $row['dana_keluar_idr'] ?? $row['dana_keluar'] ?? $row['nominal'] ?? 0;
            $danaMasuk = $row['dana_masuk_idr'] ?? $row['dana_masuk'] ?? 0;
            
            // Skip jika baris kosong
            if (empty($keterangan)) {
                $urutanBaris++;
                continue;
            }
            
            // Skip baris SUBTOTAL atau TOTAL (hanya informasi, bukan transaksi riil)
            if (preg_match('/^(sub)?total/i', trim($keterangan))) {
                \Log::info('Baris subtotal di-skip', ['baris' => $urutanBaris, 'keterangan' => $keterangan]);
                $urutanBaris++;
                continue;
            }
            
            // Parse nominal
            $danaKeluar = $this->parseNominal($danaKeluar);
            $danaMasuk = $this->parseNominal($danaMasuk);
            
            // Skip jika keduanya 0
            if ($danaKeluar == 0 && $danaMasuk == 0) {
                $urutanBaris++;
                continue;
            }
            
            // Tentukan tipe transaksi dan nominal
            $tipeTransaksi = 'keluar';
            $nominal = $danaKeluar;
            
            if ($danaMasuk > 0) {
                $tipeTransaksi = 'masuk';
                $nominal = $danaMasuk;
            }
            
            // HITUNG SALDO RUNNING (seperti Excel)
            // Rumus Excel: Saldo = Saldo Sebelumnya + Dana Masuk - Dana Keluar
            if ($tipeTransaksi == 'masuk') {
                $saldoRunning += $nominal; // Saldo bertambah
            } else {
                $saldoRunning -= $nominal; // Saldo berkurang
            }
            
            // Parse tanggal
            $tanggal = $this->parseTanggal($tanggalInput);
            
            // AI Auto-detect kategori
            $kategori = $this->detectKategori($keterangan);
            
            // Generate nomor realisasi unik
            $mikrodetik = str_replace('.', '', microtime(true));
            $uniqueId = substr($mikrodetik, -6) . str_pad($urutanBaris, 3, '0', STR_PAD_LEFT);
            $nomorRealisasi = sprintf(
                'RLS/%s/%s/%s/%s',
                $tanggal->format('Y'),
                $tanggal->format('m'),
                $tanggal->format('d'),
                $uniqueId
            );
            
            // Siapkan data untuk insert
            $dataToInsert[] = [
                'pengajuan_id' => $this->pengajuanId,
                'nomor_realisasi' => $nomorRealisasi,
                'tanggal_realisasi' => $tanggal->format('Y-m-d'),
                'urutan_baris' => $urutanBaris, // SIMPAN URUTAN BARIS EXCEL
                'uraian' => $keterangan,
                'nominal' => $nominal,
                'saldo_running' => $saldoRunning, // SIMPAN SALDO RUNNING (seperti Excel)
                'tipe_transaksi' => $tipeTransaksi,
                'kategori' => $kategori,
                'keterangan' => null,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $urutanBaris++;
        }
        
        // Insert batch ke database (lebih cepat daripada satu-satu)
        if (!empty($dataToInsert)) {
            DB::table('realisasi_dana_operasional')->insert($dataToInsert);
            
            \Log::info('Import Excel selesai', [
                'total_rows' => count($dataToInsert),
                'saldo_akhir' => $saldoRunning,
            ]);
        }
    }
    
    /**
     * Parse nominal dari string (hapus titik, koma, Rp, dll)
     */
    private function parseNominal($value)
    {
        if (is_string($value)) {
            $value = str_replace(['.', ',', ' ', 'Rp'], ['', '.', '', ''], $value);
        }
        return (float) $value;
    }
    
    /**
     * AI Auto-detect kategori berdasarkan keterangan transaksi
     */
    private function detectKategori($keterangan)
    {
        $keterangan = strtolower($keterangan);
        
        // Kategori: Transport & Kendaraan
        if (preg_match('/bensin|solar|bbm|oli|motor|mobil|parkir|tol|grab|gojek|taxi|transport|ongkos|ongkir|kirim|jne|jnt|sicepat/i', $keterangan)) {
            return 'Transport & Kendaraan';
        }
        
        // Kategori: ATK & Perlengkapan Kantor
        if (preg_match('/atk|kertas|pulpen|spidol|pensil|buku|amplop|map|staples|penggaris|tinta|printer|fotocopy|fotokopi|scan|print|alat tulis/i', $keterangan)) {
            return 'ATK & Perlengkapan';
        }
        
        // Kategori: Konsumsi & Makan
        if (preg_match('/makan|minum|snack|kopi|teh|air mineral|katering|catering|nasi|ayam|soto|bakso|gado|sayur|lauk|sarapan|siang|malam/i', $keterangan)) {
            return 'Konsumsi';
        }
        
        // Kategori: Utilitas (Listrik, Air, Internet)
        if (preg_match('/listrik|air|pdam|token|wifi|internet|pulsa|paket data|telkom|indihome|speedy/i', $keterangan)) {
            return 'Utilitas';
        }
        
        // Kategori: Maintenance & Perbaikan
        if (preg_match('/servis|service|perbaikan|maintenance|ganti|repair|rusak|bocor|memperbaiki/i', $keterangan)) {
            return 'Maintenance';
        }
        
        // Kategori: Kebersihan & Sanitasi
        if (preg_match('/sabun|detergen|pel|sapu|kain|pembersih|cleaning|sanitasi|kebersihan|sampah/i', $keterangan)) {
            return 'Kebersihan';
        }
        
        // Kategori: Kesehatan & Obat
        if (preg_match('/obat|vitamin|p3k|plester|betadine|medis|dokter|rumah sakit|rs|klinik|apotek/i', $keterangan)) {
            return 'Kesehatan';
        }
        
        // Kategori: Komunikasi
        if (preg_match('/telepon|telpon|sms|whatsapp|telegram|komunikasi|koordinasi/i', $keterangan)) {
            return 'Komunikasi';
        }
        
        // Kategori: Administrasi & Legal
        if (preg_match('/administrasi|legal|notaris|materai|surat|dokumen|perizinan|pajak|retribusi/i', $keterangan)) {
            return 'Administrasi';
        }
        
        // Default: Operasional
        return 'Operasional';
    }

    /**
     * Parse berbagai format tanggal
     */
    private function parseTanggal($value)
    {
        // Jika tidak ada input tanggal, gunakan hari ini
        if (empty($value)) {
            return Carbon::now();
        }

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
            if (is_numeric($value) && $value > 40000) { // Excel dates start from 1900
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
            
            // Try general parse
            return Carbon::parse($value);
            
        } catch (\Exception $e) {
            // Default ke hari ini jika gagal parse
            return Carbon::now();
        }
    }

    public function rules(): array
    {
        return [
            // Tidak ada validasi ketat, karena sudah di-handle di method model()
        ];
    }
}
