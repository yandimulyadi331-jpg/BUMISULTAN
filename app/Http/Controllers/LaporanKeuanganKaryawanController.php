<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganKaryawanController extends Controller
{
    /**
     * Display a listing of published financial reports for karyawan
     */
    public function index(Request $request)
    {
        $jenis = $request->get('jenis'); // mingguan, bulanan, tahunan, triwulan, semester
        
        // Query laporan yang sudah published
        $query = DB::table('laporan_keuangan')
            ->leftJoin('users as creator', 'laporan_keuangan.user_id', '=', 'creator.id')
            ->leftJoin('users as publisher', 'laporan_keuangan.published_by', '=', 'publisher.id')
            ->select(
                'laporan_keuangan.*',
                'creator.name as creator_name',
                'publisher.name as publisher_name'
            )
            ->where('laporan_keuangan.is_published', true)
            ->orderByDesc('laporan_keuangan.published_at');
        
        // Filter berdasarkan jenis periode
        if ($jenis) {
            $query->where('laporan_keuangan.periode', $jenis);
        }
        
        $laporans = $query->paginate(10);
        
        return view('laporan-keuangan-karyawan.index', compact('laporans', 'jenis'));
    }
    
    /**
     * Display the specified published financial report
     */
    public function show($id)
    {
        $laporan = DB::table('laporan_keuangan')
            ->leftJoin('users as creator', 'laporan_keuangan.user_id', '=', 'creator.id')
            ->leftJoin('users as publisher', 'laporan_keuangan.published_by', '=', 'publisher.id')
            ->select(
                'laporan_keuangan.*',
                'creator.name as creator_name',
                'publisher.name as publisher_name'
            )
            ->where('laporan_keuangan.id', $id)
            ->where('laporan_keuangan.is_published', true)
            ->first();
        
        if (!$laporan) {
            return redirect()->route('laporan-keuangan-karyawan.index')
                ->with('error', 'Laporan tidak ditemukan atau belum dipublish.');
        }
        
        return view('laporan-keuangan-karyawan.show', compact('laporan'));
    }
    
    /**
     * Download PDF laporan
     */
    public function downloadPDF($id)
    {
        $laporan = DB::table('laporan_keuangan')
            ->where('id', $id)
            ->where('is_published', true)
            ->first();
        
        if (!$laporan) {
            return back()->with('error', 'Laporan tidak ditemukan');
        }
        
        if (!$laporan->file_pdf) {
            return back()->with('error', 'File PDF tidak tersedia');
        }
        
        // Return download response (file di storage atau generate ulang)
        return response()->download(storage_path('app/public/' . $laporan->file_pdf));
    }
    
    /**
     * Download Excel laporan
     */
    public function downloadExcel($id)
    {
        $laporan = DB::table('laporan_keuangan')
            ->where('id', $id)
            ->where('is_published', true)
            ->first();
        
        if (!$laporan) {
            return back()->with('error', 'Laporan tidak ditemukan');
        }
        
        if (!$laporan->file_excel) {
            return back()->with('error', 'File Excel tidak tersedia');
        }
        
        // Return download response
        return response()->download(storage_path('app/public/' . $laporan->file_excel));
    }
}
