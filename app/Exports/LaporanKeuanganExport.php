<?php

namespace App\Exports;

use App\Models\RealisasiDanaOperasional;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    /**
     * Get data collection
     */
    public function collection()
    {
        $tanggalAwal = $this->periode['tanggal_awal'];
        $tanggalAkhir = $this->periode['tanggal_akhir'];

        return RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal_realisasi', 'asc')
            ->get();
    }

    /**
     * Map data untuk setiap row
     */
    public function map($transaksi): array
    {
        return [
            Carbon::parse($transaksi->tanggal_realisasi)->format('d/m/Y'),
            $transaksi->nomor_transaksi ?? '-',
            $transaksi->tipe_transaksi,
            $transaksi->kategori,
            $transaksi->keterangan,
            $transaksi->tipe_transaksi == 'Dana Masuk' ? $transaksi->nominal : 0,
            $transaksi->tipe_transaksi == 'Dana Keluar' ? $transaksi->nominal : 0,
            $transaksi->saldo_running ?? 0,
        ];
    }

    /**
     * Headings untuk Excel
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Transaksi',
            'Tipe',
            'Kategori',
            'Keterangan',
            'Dana Masuk (Rp)',
            'Dana Keluar (Rp)',
            'Saldo (Rp)',
        ];
    }

    /**
     * Style untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e3c72']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'Laporan ' . $this->periode['nama_periode'];
    }
}
