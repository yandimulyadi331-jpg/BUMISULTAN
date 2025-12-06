<?php

namespace App\Exports;

use App\Models\SaldoHarianOperasional;
use App\Models\RealisasiDanaOperasional;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransaksiOperasionalExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $rowNumber = 0;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ?? Carbon::now()->startOfMonth();
        $this->endDate = $endDate ?? Carbon::now()->endOfMonth();
    }

    public function collection()
    {
        return RealisasiDanaOperasional::with(['pengajuan.user'])
            ->whereBetween('tanggal_realisasi', [$this->startDate, $this->endDate])
            ->orderBy('tanggal_realisasi', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal (Date)',
            'Jam (Time)',
            'Keterangan (Remarks)',
            'Kategori (Category)',
            'Dana Masuk (IDR)',
            'Dana Keluar (IDR)',
            'Saldo (IDR)',
        ];
    }

    public function map($realisasi): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $realisasi->tanggal_realisasi->format('d M Y'),
            $realisasi->created_at->format('H:i:s') . ' WIB',
            $realisasi->uraian . ($realisasi->keterangan ? "\n" . $realisasi->keterangan : ''),
            $realisasi->kategori ?? 'Operasional',
            '', // Dana Masuk (kosong untuk realisasi)
            number_format($realisasi->nominal, 2, ',', '.'),
            '', // Saldo akan dihitung di dashboard
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
