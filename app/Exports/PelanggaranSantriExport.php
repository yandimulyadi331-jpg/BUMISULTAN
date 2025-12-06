<?php

namespace App\Exports;

use App\Models\PelanggaranSantri;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelanggaranSantriExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = DB::table('pelanggaran_santri')
            ->join('santri', 'pelanggaran_santri.user_id', '=', 'santri.id')
            ->select(
                'santri.id',
                'santri.nama_lengkap as name',
                'santri.nik',
                DB::raw('COUNT(pelanggaran_santri.id) as total_pelanggaran'),
                DB::raw('SUM(pelanggaran_santri.point) as total_point'),
                DB::raw('MAX(pelanggaran_santri.tanggal_pelanggaran) as pelanggaran_terakhir')
            )
            ->groupBy('santri.id', 'santri.nama_lengkap', 'santri.nik')
            ->orderBy('total_pelanggaran', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('pelanggaran_santri.tanggal_pelanggaran', [
                $this->startDate,
                $this->endDate
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Santri',
            'Total Pelanggaran',
            'Total Point',
            'Status',
            'Pelanggaran Terakhir',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $statusInfo = PelanggaranSantri::getStatusPelanggaran($row->total_pelanggaran);

        return [
            $no,
            $row->nik ?? '-',
            $row->name,
            $row->total_pelanggaran,
            $row->total_point,
            $statusInfo['status'],
            $row->pelanggaran_terakhir ? date('d-m-Y', strtotime($row->pelanggaran_terakhir)) : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Pelanggaran Santri';
    }
}
