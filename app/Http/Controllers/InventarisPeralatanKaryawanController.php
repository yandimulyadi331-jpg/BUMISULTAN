<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Peralatan;
use Illuminate\Http\Request;

class InventarisPeralatanKaryawanController extends Controller
{
    /**
     * Display inventaris dan peralatan untuk karyawan (READ ONLY)
     */
    public function index(Request $request)
    {
        // Query untuk Inventaris
        $queryInventaris = Inventaris::with(['cabang', 'barang', 'createdBy']);

        if ($request->filled('kategori_inventaris')) {
            $queryInventaris->where('kategori', $request->kategori_inventaris);
        }

        if ($request->filled('status_inventaris')) {
            $queryInventaris->where('status', $request->status_inventaris);
        }

        if ($request->filled('search_inventaris')) {
            $search = $request->search_inventaris;
            $queryInventaris->where(function ($q) use ($search) {
                $q->where('kode_inventaris', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        $inventaris = $queryInventaris->latest()->paginate(10, ['*'], 'inventaris_page');

        // Query untuk Peralatan
        $queryPeralatan = Peralatan::query();

        if ($request->filled('kategori_peralatan')) {
            $queryPeralatan->where('kategori', $request->kategori_peralatan);
        }

        if ($request->filled('kondisi_peralatan')) {
            $queryPeralatan->where('kondisi', $request->kondisi_peralatan);
        }

        if ($request->filled('search_peralatan')) {
            $search = $request->search_peralatan;
            $queryPeralatan->where(function($q) use ($search) {
                $q->where('nama_peralatan', 'like', '%' . $search . '%')
                  ->orWhere('kode_peralatan', 'like', '%' . $search . '%');
            });
        }

        $peralatan = $queryPeralatan->latest()->paginate(10, ['*'], 'peralatan_page');

        // Data untuk filter
        $kategoriInventaris = ['elektronik', 'furnitur', 'kendaraan', 'alat_kantor', 'lainnya'];
        $statusInventaris = ['tersedia', 'dipinjam', 'rusak', 'maintenance', 'hilang'];
        $kondisiInventaris = ['baik', 'rusak_ringan', 'rusak_berat'];
        
        $kategoriPeralatan = [
            'Alat Kebersihan',
            'Alat Tulis Kantor',
            'Elektronik',
            'Peralatan Dapur',
            'Peralatan Olahraga',
            'Peralatan Taman',
            'Perkakas',
            'Keamanan',
            'Lainnya'
        ];
        $kondisiPeralatan = ['baik', 'rusak ringan', 'rusak berat'];

        // Statistik
        $totalInventaris = Inventaris::count();
        $totalPeralatan = Peralatan::count();
        $inventarisTersedia = Inventaris::where('status', 'tersedia')->count();
        $peralatanBaik = Peralatan::where('kondisi', 'baik')->count();

        return view('fasilitas.inventaris-peralatan.index-karyawan', compact(
            'inventaris',
            'peralatan',
            'kategoriInventaris',
            'statusInventaris',
            'kondisiInventaris',
            'kategoriPeralatan',
            'kondisiPeralatan',
            'totalInventaris',
            'totalPeralatan',
            'inventarisTersedia',
            'peralatanBaik'
        ));
    }

    /**
     * Display detail inventaris
     */
    public function showInventaris($id)
    {
        $inventaris = Inventaris::with(['cabang', 'barang', 'createdBy'])->findOrFail($id);
        
        if (request()->ajax()) {
            return view('fasilitas.inventaris-peralatan.show-inventaris-modal', compact('inventaris'));
        }
        
        return view('fasilitas.inventaris-peralatan.show-inventaris', compact('inventaris'));
    }

    /**
     * Display detail peralatan
     */
    public function showPeralatan($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        
        if (request()->ajax()) {
            return view('fasilitas.inventaris-peralatan.show-peralatan-modal', compact('peralatan'));
        }
        
        return view('fasilitas.inventaris-peralatan.show-peralatan', compact('peralatan'));
    }
}
