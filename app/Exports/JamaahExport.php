<?php

namespace App\Exports;

use App\Models\JamaahMajlisTaklim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JamaahExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return JamaahMajlisTaklim::with(['kehadiran', 'distribusiHadiah'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nomor Jamaah',
            'Nama Jamaah',
            'NIK',
            'Alamat',
            'Tanggal Lahir',
            'Tahun Masuk',
            'No Telepon',
            'Email',
            'Jenis Kelamin',
            'PIN Fingerprint',
            'Jumlah Kehadiran',
            'Status Umroh',
            'Tanggal Umroh',
            'Status Aktif',
            'Keterangan'
        ];
    }

    /**
     * @param mixed $jamaah
     * @return array
     */
    public function map($jamaah): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $jamaah->nomor_jamaah,
            $jamaah->nama_jamaah,
            "'" . $jamaah->nik, // Prefix dengan ' agar tidak dianggap angka
            $jamaah->alamat,
            $jamaah->tanggal_lahir ? $jamaah->tanggal_lahir->format('d-m-Y') : '',
            $jamaah->tahun_masuk,
            $jamaah->no_telepon,
            $jamaah->email,
            $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            $jamaah->pin_fingerprint,
            $jamaah->jumlah_kehadiran,
            $jamaah->status_umroh ? 'Sudah' : 'Belum',
            $jamaah->tanggal_umroh ? $jamaah->tanggal_umroh->format('d-m-Y') : '',
            ucfirst($jamaah->status_aktif),
            $jamaah->keterangan
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
