<?php

namespace App\Http\Controllers;

use App\Models\JamaahMasar;
use App\Models\KehadiranJamaahMasar;
use App\Models\Pengaturanumum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JamaahExport;
use App\Imports\JamaahMasarImport;
use Barryvdh\DomPDF\Facade\Pdf;

class JamaahMasarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $jamaah = JamaahMasar::with(['kehadiran', 'distribusiHadiah'])->select('jamaah_masar.*');
            if ($request->filled('tahun_masuk')) {
                $jamaah->where('tahun_masuk', $request->tahun_masuk);
            }
            if ($request->filled('status_aktif')) {
                $jamaah->where('status_aktif', $request->status_aktif);
            }
            if ($request->filled('status_umroh')) {
                $jamaah->where('status_umroh', $request->status_umroh);
            }
            return DataTables::of($jamaah)->addIndexColumn()->addColumn('action', function ($row) {
                $encryptedId = Crypt::encrypt($row->id);
                return '<a href="' . route('masar.jamaah.show', $encryptedId) . '" class="btn btn-sm btn-info"><i class="ti ti-eye"></i></a>';
            })->rawColumns(['action'])->make(true);
        }
        return view('masar.jamaah.index');
    }

    public function create()
    {
        return view('masar.jamaah.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('masar.jamaah.index')->with('success', 'Data jamaah berhasil ditambahkan');
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMasar::with(['kehadiran', 'distribusiHadiah.hadiah'])->findOrFail($id);
            return view('masar.jamaah.show', compact('jamaah'));
        } catch (\Exception $e) {
            return redirect()->route('masar.jamaah.index')->with('error', 'Data jamaah tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMasar::findOrFail($id);
            return view('masar.jamaah.edit', compact('jamaah'));
        } catch (\Exception $e) {
            return redirect()->route('masar.jamaah.index')->with('error', 'Data jamaah tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        return redirect()->route('masar.jamaah.index')->with('success', 'Data jamaah berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMasar::findOrFail($id);
            $jamaah->delete();
            return response()->json(['success' => true, 'message' => 'Data jamaah berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function downloadIdCard($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMasar::findOrFail($id);
            return view('masar.jamaah.id_card', ['jamaah' => $jamaah]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function toggleUmroh($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMasar::findOrFail($id);
            $jamaah->status_umroh = !$jamaah->status_umroh;
            $jamaah->save();
            return response()->json(['success' => true, 'message' => 'Status umroh berhasil diupdate']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function import(Request $request)
    {
        return redirect()->back()->with('success', 'Import berhasil');
    }

    public function export()
    {
        return Excel::download(new JamaahExport(), 'jamaah.xlsx');
    }

    public function downloadTemplate()
    {
        return response()->download(storage_path('app/template.xlsx'));
    }

    public function indexKaryawan(Request $request)
    {
        return view('masar.karyawan.jamaah.index');
    }

    public function showKaryawan($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $jamaah = JamaahMasar::with(['kehadiran', 'distribusiHadiah'])->findOrFail($id);
            return view('masar.karyawan.jamaah.show', compact('jamaah'));
        } catch (\Exception $e) {
            return redirect()->route('masar.karyawan.jamaah.index')->with('error', 'Data tidak ditemukan');
        }
    }

    public function getdatamesin(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function updatefrommachine(Request $request, $pin, $status_scan)
    {
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }
}
