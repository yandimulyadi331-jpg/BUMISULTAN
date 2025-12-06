<?php

namespace App\Http\Controllers;

use App\Models\RekapKehadiranJamaah;
use App\Models\JamaahMajlisTaklim;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RekapKehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RekapKehadiranJamaah::with('creator')
                ->orderBy('tanggal', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal_formatted', function ($row) {
                    return $row->tanggal->format('d/m/Y');
                })
                ->addColumn('persentase_badge', function ($row) {
                    $color = $row->persentase >= 80 ? 'success' : ($row->persentase >= 60 ? 'warning' : 'danger');
                    return '<span class="badge bg-' . $color . '">' . $row->persentase . '%</span>';
                })
                ->addColumn('info_kehadiran', function ($row) {
                    return $row->jumlah_hadir . ' dari ' . ($row->total_jamaah ?? 'N/A');
                })
                ->addColumn('aksi', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></button>';
                    $deleteBtn = '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '" title="Hapus"><i class="ti ti-trash"></i></button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['persentase_badge', 'aksi'])
                ->make(true);
        }

        return view('majlistaklim.kehadiran.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $totalJamaah = JamaahMajlisTaklim::count();
        return view('majlistaklim.kehadiran.create', compact('totalJamaah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_hadir' => 'required|integer|min:0',
            'total_jamaah' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        // Auto-calculate persentase (handled by model boot method)
        RekapKehadiranJamaah::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekap kehadiran berhasil disimpan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rekap = RekapKehadiranJamaah::findOrFail($id);
        return response()->json($rekap);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rekap = RekapKehadiranJamaah::findOrFail($id);
        $totalJamaah = JamaahMajlisTaklim::count();
        return view('majlistaklim.kehadiran.edit', compact('rekap', 'totalJamaah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah_hadir' => 'required|integer|min:0',
            'total_jamaah' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $rekap = RekapKehadiranJamaah::findOrFail($id);
        $rekap->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekap kehadiran berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rekap = RekapKehadiranJamaah::findOrFail($id);
        $rekap->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rekap kehadiran berhasil dihapus'
        ]);
    }

    /**
     * Display dashboard statistics.
     */
    public function dashboard()
    {
        // Get latest 10 records
        $rekapTerbaru = RekapKehadiranJamaah::orderBy('tanggal', 'desc')->take(10)->get();

        // Calculate statistics
        $totalPertemuan = RekapKehadiranJamaah::count();
        $rataRataKehadiran = RekapKehadiranJamaah::avg('jumlah_hadir');
        $rataRataPersentase = RekapKehadiranJamaah::avg('persentase');

        // This week
        $mingguIni = RekapKehadiranJamaah::whereBetween('tanggal', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->first();

        // Last week
        $mingguLalu = RekapKehadiranJamaah::whereBetween('tanggal', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->first();

        // Monthly trend (last 6 months)
        $trenBulanan = RekapKehadiranJamaah::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, AVG(jumlah_hadir) as rata_hadir, AVG(persentase) as rata_persentase')
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return view('majlistaklim.kehadiran.dashboard', compact(
            'rekapTerbaru',
            'totalPertemuan',
            'rataRataKehadiran',
            'rataRataPersentase',
            'mingguIni',
            'mingguLalu',
            'trenBulanan'
        ));
    }
}
