<?php

namespace App\Exports;

use App\Models\KeuanganSantriTransaction;
use App\Services\KeuanganSantriService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KeuanganSantriExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filters;
    protected $keuanganService;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->keuanganService = new KeuanganSantriService();
    }

    public function collection()
    {
        return $this->keuanganService->getTransactions($this->filters)->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Nama Santri',
            'Jenis',
            'Kategori',
            'Deskripsi',
            'Metode Pembayaran',
            'Jumlah',
            'Saldo Sebelum',
            'Saldo Sesudah',
            'Status',
            'Catatan',
            'Dibuat Oleh',
            'Tanggal Dibuat',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->kode_transaksi,
            $transaction->tanggal_transaksi->format('d/m/Y'),
            $transaction->santri->nama_lengkap ?? '-',
            ucfirst($transaction->jenis),
            $transaction->category->nama_kategori ?? '-',
            $transaction->deskripsi,
            $transaction->metode_pembayaran ?? '-',
            number_format($transaction->jumlah, 0, ',', '.'),
            number_format($transaction->saldo_sebelum, 0, ',', '.'),
            number_format($transaction->saldo_sesudah, 0, ',', '.'),
            $transaction->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi',
            $transaction->catatan ?? '-',
            $transaction->creator->name ?? '-',
            $transaction->created_at->format('d/m/Y H:i'),
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
        return 'Laporan Keuangan';
    }
}
