<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuanganTukang;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('transaksi-keuangan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Export transaksi keuangan ke PDF dengan filter tanggal
     */
    public function exportPdf(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'tanggal_dari' => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari'
        ]);

        $tanggalDari = Carbon::parse($request->tanggal_dari);
        $tanggalSampai = Carbon::parse($request->tanggal_sampai);

        // Ambil data transaksi keuangan berdasarkan filter tanggal
        $transaksi = KeuanganTukang::with(['tukang', 'user'])
            ->whereBetween('tanggal', [$tanggalDari->startOfDay(), $tanggalSampai->endOfDay()])
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung total pemasukan dan pengeluaran
        $totalPemasukan = $transaksi->where('tipe_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transaksi->where('tipe_transaksi', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Data untuk PDF
        $data = [
            'transaksi' => $transaksi,
            'tanggal_dari' => $tanggalDari->format('d F Y'),
            'tanggal_sampai' => $tanggalSampai->format('d F Y'),
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'tanggal_cetak' => Carbon::now()->format('d F Y H:i'),
        ];

        // Generate PDF
        $pdf = PDF::loadView('transaksi-keuangan.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Nama file dengan tanggal
        $filename = 'Laporan_Keuangan_' . $tanggalDari->format('Ymd') . '_' . $tanggalSampai->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }
}
