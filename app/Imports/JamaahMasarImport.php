<?php

namespace App\Imports;

use App\Models\JamaahMasar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;
use Illuminate\Support\Str;

class JamaahMasarImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsFailures;
    
    /**
     * Prepare data sebelum validasi - convert semua ke string
     */
    public function prepareForValidation($data, $index)
    {
        // Normalisasi keys - hapus spasi, tanda bintang, kurung, ubah ke lowercase
        $normalizedData = [];
        foreach ($data as $key => $value) {
            $cleanKey = Str::slug(str_replace(['*', '(', ')', '/'], '', $key), '_');
            // Convert ke string untuk field yang perlu string
            if (in_array($cleanKey, ['nik_16_digit', 'nik', 'no_telepon', 'pin_fingerprint', 'tahun_masuk_yyyy', 'tahun_masuk'])) {
                $normalizedData[$cleanKey] = (string) $value;
            } else {
                $normalizedData[$cleanKey] = $value;
            }
        }
        
        // Tambahkan fallback mapping untuk jenis kelamin
        if (!isset($normalizedData['jenis_kelamin_l_p']) && isset($normalizedData['jenis_kelamin'])) {
            $normalizedData['jenis_kelamin_l_p'] = $normalizedData['jenis_kelamin'];
        }
        
        return $normalizedData;
    }
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Data sudah dinormalisasi oleh prepareForValidation
        // Mapping header sesuai template
        $namaJamaah = $row['nama_jamaah'] ?? null;
        $nik = $row['nik_16_digit'] ?? $row['nik'] ?? null;
        $alamat = $row['alamat'] ?? null;
        $tanggalLahir = $row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? null;
        $tahunMasuk = $row['tahun_masuk_yyyy'] ?? $row['tahun_masuk'] ?? null;
        $noTelepon = $row['no_telepon'] ?? null;
        $email = $row['email'] ?? null;
        $jenisKelamin = $row['jenis_kelamin_l_p'] ?? $row['jenis_kelamin'] ?? 'L';
        $pinFingerprint = $row['pin_fingerprint'] ?? null;
        
        // Skip jika NIK kosong
        if (empty($nik)) {
            return null;
        }
        
        // Skip jika NIK sudah ada (cek dengan refresh dari DB)
        if (JamaahMasar::where('nik', $nik)->exists()) {
            \Log::info("Skip NIK duplikat: {$nik}");
            return null;
        }

        try {
            // Buat temporary nomor jamaah dulu
            $tempNomor = 'TEMP-' . uniqid();
            
            $jamaah = JamaahMasar::create([
                'nomor_jamaah' => $tempNomor,
                'nama_jamaah' => $namaJamaah,
                'nik' => $nik,
                'alamat' => $alamat,
                'tanggal_lahir' => $this->transformDate($tanggalLahir),
                'tahun_masuk' => $tahunMasuk,
                'no_telepon' => $noTelepon,
                'email' => $email,
                'jenis_kelamin' => strtoupper(substr($jenisKelamin, 0, 1)),
                'pin_fingerprint' => $pinFingerprint,
                'jumlah_kehadiran' => 0,
                'status_umroh' => false,
                'status_aktif' => 'aktif',
            ]);

            // Generate nomor jamaah final dengan ID asli
            $jamaah->nomor_jamaah = JamaahMasar::generateNomorJamaah(
                $jamaah->nik,
                $jamaah->tahun_masuk,
                $jamaah->id
            );
            $jamaah->save();

            return $jamaah;
        } catch (\Exception $e) {
            // Skip jika ada error (duplicate, dll)
            \Log::warning("Skip import row - {$namaJamaah}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Transform date from various formats
     */
    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                // Excel date format
                return Carbon::createFromFormat('Y-m-d', '1899-12-30')->addDays($value);
            }
            
            // Try common date formats
            foreach (['d-m-Y', 'd/m/Y', 'Y-m-d', 'd-M-Y'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $value);
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
