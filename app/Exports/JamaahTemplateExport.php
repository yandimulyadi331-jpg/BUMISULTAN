<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class JamaahTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function collection()
    {
        // Return contoh data (1 row untuk panduan)
        // Tambahkan prefix ' agar Excel treat sebagai text
        return collect([
            [
                'YANDI MULYADI',
                '3201062404000007',
                'KP LEMBUR SAWAH RT 002 RW002',
                '2006-02-09',
                '2019',
                '085715375490',
                'yandimulyadi@gmail.com',
                'L',
                '001',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'NAMA JAMAAH *',
            'NIK (16 Digit) *',
            'ALAMAT *',
            'TANGGAL LAHIR (YYYY-MM-DD) *',
            'TAHUN MASUK (YYYY) *',
            'NO TELEPON',
            'EMAIL',
            'JENIS KELAMIN (L/P) *',
            'PIN FINGERPRINT',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style untuk contoh data
        $sheet->getStyle('A2:I2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(20);

        return [];
    }

    /**
     * Format kolom agar NIK, NO TELEPON, PIN tetap sebagai TEXT
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // NIK (16 Digit)
            'F' => NumberFormat::FORMAT_TEXT, // NO TELEPON
            'I' => NumberFormat::FORMAT_TEXT, // PIN FINGERPRINT
        ];
    }
}
