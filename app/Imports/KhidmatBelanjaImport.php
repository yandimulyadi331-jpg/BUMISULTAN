<?php

namespace App\Imports;

use App\Models\BelanjaKhidmat;
use App\Models\JadwalKhidmat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class KhidmatBelanjaImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $jadwalId;

    public function __construct($jadwalId)
    {
        $this->jadwalId = $jadwalId;
    }

    public function model(array $row)
    {
        // Skip jika baris kosong
        if (empty($row['nama_barang'])) {
            return null;
        }

        $jumlah = (int) $row['jumlah'];
        $hargaSatuan = (float) $row['harga_satuan'];
        $totalHarga = $jumlah * $hargaSatuan;

        return new BelanjaKhidmat([
            'jadwal_khidmat_id' => $this->jadwalId,
            'nama_barang' => $row['nama_barang'],
            'jumlah' => $jumlah,
            'satuan' => $row['satuan'],
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'keterangan' => $row['keterangan'] ?? null
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama barang harus diisi',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah minimal 1',
            'satuan.required' => 'Satuan harus diisi',
            'harga_satuan.required' => 'Harga satuan harus diisi',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka',
        ];
    }

    /**
     * Run after all rows imported
     */
    public function afterImport()
    {
        // Update total belanja dan saldo akhir
        $jadwal = JadwalKhidmat::find($this->jadwalId);
        if ($jadwal) {
            $totalBelanja = BelanjaKhidmat::where('jadwal_khidmat_id', $this->jadwalId)->sum('total_harga');
            $saldoAkhir = ($jadwal->saldo_awal + $jadwal->saldo_masuk) - $totalBelanja;
            
            $jadwal->update([
                'total_belanja' => $totalBelanja,
                'saldo_akhir' => $saldoAkhir
            ]);

            // Update next jadwal saldo
            $this->updateNextJadwalSaldo($jadwal);
        }
    }

    private function updateNextJadwalSaldo($jadwal)
    {
        $nextJadwal = JadwalKhidmat::where('tanggal_jadwal', '>', $jadwal->tanggal_jadwal)
            ->orderBy('tanggal_jadwal', 'asc')
            ->first();

        if ($nextJadwal) {
            $nextJadwal->update([
                'saldo_awal' => $jadwal->saldo_akhir
            ]);

            $nextJadwal->update([
                'saldo_akhir' => ($nextJadwal->saldo_awal + $nextJadwal->saldo_masuk) - $nextJadwal->total_belanja
            ]);

            $this->updateNextJadwalSaldo($nextJadwal);
        }
    }
}
