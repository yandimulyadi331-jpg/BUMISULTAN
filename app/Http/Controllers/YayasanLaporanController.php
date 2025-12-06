<?php

namespace App\Http\Controllers;

use App\Models\PresensiYayasan;
use App\Models\YayasanMasar;
use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;

class YayasanLaporanController extends Controller
{
    public function laporanPresensi()
    {
        $cabang = Cabang::all();
        return view('laporan.presensi-yayasan', compact('cabang'));
    }

    public function cetakPresensiyayasan(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $cabang = $request->input('cabang');
        $format = $request->input('format', 'pdf');

        $query = PresensiYayasan::whereBetween('tanggal', [$from, $to]);

        if ($cabang != 'all') {
            $query->whereHas('yayasan', function ($q) use ($cabang) {
                $q->where('kode_cabang', $cabang);
            });
        }

        $data = $query->with('yayasan', 'jamKerja')
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_in', 'desc')
            ->get();

        $from_format = DateToIndo($from);
        $to_format = DateToIndo($to);

        if ($format == 'pdf') {
            return $this->generatePDF($data, $from_format, $to_format, $cabang);
        } else {
            return $this->generateExcel($data, $from_format, $to_format, $cabang);
        }
    }

    private function generatePDF($data, $from_format, $to_format, $cabang)
    {
        $cabang_name = $cabang == 'all' ? 'Semua Cabang' : Cabang::where('kode_cabang', $cabang)->first()->nama_cabang ?? 'N/A';

        $pdf = Pdf::loadView('laporan.presensi-yayasan-cetak', [
            'data' => $data,
            'from' => $from_format,
            'to' => $to_format,
            'cabang' => $cabang_name
        ]);

        return $pdf->download('Laporan-Presensi-Yayasan-' . date('Y-m-d') . '.pdf');
    }

    private function generateExcel($data, $from_format, $to_format, $cabang)
    {
        $cabang_name = $cabang == 'all' ? 'Semua Cabang' : Cabang::where('kode_cabang', $cabang)->first()->nama_cabang ?? 'N/A';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'LAPORAN PRESENSI YAYASAN');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', 'Periode: ' . $from_format . ' - ' . $to_format);
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A3', 'Cabang: ' . $cabang_name);
        $sheet->mergeCells('A3:H3');
        $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal('center');

        // Set column headers
        $headers = ['No', 'Kode Yayasan', 'Nama', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Jam Kerja', 'Status'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $sheet->getStyle($col . '5')->getFont()->setBold(true)->setColor(new Color('FFFFFFFF'));
            $sheet->getStyle($col . '5')->getFill()->setFillType('solid')->getStartColor()->setARGB('FF000000');
            $col++;
        }

        // Set data
        $row = 6;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->kode_yayasan);
            $sheet->setCellValue('C' . $row, $item->yayasan->nama ?? '-');
            $sheet->setCellValue('D' . $row, DateToIndo($item->tanggal));
            $sheet->setCellValue('E' . $row, $item->jam_in ?? '-');
            $sheet->setCellValue('F' . $row, $item->jam_out ?? '-');
            $sheet->setCellValue('G' . $row, $item->jamKerja->nama_jam_kerja ?? '-');
            $sheet->setCellValue('H' . $row, $this->getStatusLabel($item->status));
            $row++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);

        // Output
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan-Presensi-Yayasan-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    private function getStatusLabel($status)
    {
        $statusMap = [
            'H' => 'Hadir',
            'I' => 'Izin',
            'S' => 'Sakit',
            'A' => 'Alfa',
            'C' => 'Cuti'
        ];
        return $statusMap[$status] ?? $status;
    }
}
