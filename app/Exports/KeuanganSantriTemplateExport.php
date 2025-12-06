<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KeuanganSantriTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [date('d/m/Y'), 'pemasukan', 500000, 'Kiriman dari orang tua', 'Transfer Bank', 'Uang saku bulan ini'],
            [date('d/m/Y'), 'pengeluaran', 15000, 'Beli sabun mandi', 'Tunai', '-'],
            [date('d/m/Y'), 'pengeluaran', 20000, 'Jajan bakso dan es teh', 'Tunai', '-'],
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis',
            'Jumlah',
            'Deskripsi',
            'Metode Pembayaran',
            'Catatan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
