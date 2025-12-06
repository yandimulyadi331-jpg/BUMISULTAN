<?php

namespace App\Http\Controllers;

use App\Models\DistribusiHadiahYayasanMasar;
use App\Models\HadiahMasar;
use App\Models\YayasanMasar;
use App\Models\JamaahMasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class DistribusiHadiahMasarController extends Controller
{
    /**
     * Display a listing of distribusi hadiah (Admin)
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $distribusi = DistribusiHadiahYayasanMasar::with(['hadiah', 'jamaah'])
                ->select('distribusi_hadiah_yayasan_masar.*');

            // Filter by metode
            if ($request->filled('metode')) {
                $distribusi->where('metode_distribusi', $request->metode);
            }

            // Filter by status
            if ($request->filled('status')) {
                $distribusi->where('status_distribusi', $request->status);
            }

            // Filter by date range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $distribusi->whereBetween('tanggal_distribusi', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $distribusi->where(function ($q) use ($search) {
                    $q->where('nomor_distribusi', 'LIKE', "%{$search}%")
                        ->orWhere('penerima', 'LIKE', "%{$search}%")
                        ->orWhereHas('jamaah', function ($q2) use ($search) {
                            $q2->where('nama_jamaah', 'LIKE', "%{$search}%");
                        });
                });
            }

            return DataTables::of($distribusi)
                ->addIndexColumn()
                ->addColumn('hadiah_name', function ($row) {
                    return $row->hadiah ? $row->hadiah->nama_hadiah : '-';
                })
                ->addColumn('jamaah_name', function ($row) {
                    return $row->jamaah ? $row->jamaah->nama_jamaah : '-';
                })
                ->addColumn('metode_badge', function ($row) {
                    $badges = [
                        'langsung' => 'primary',
                        'undian' => 'info',
                        'prestasi' => 'success',
                        'kehadiran' => 'warning'
                    ];
                    $color = $badges[$row->metode_distribusi] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->metode_distribusi) . '</span>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger'
                    ];
                    $color = $badges[$row->status_distribusi] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->status_distribusi) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<div class="btn-group btn-group-sm" role="group">';
                    $btn .= '<a href="' . route('masar.distribusi.show', $encryptedId) . '" class="btn btn-info" title="Lihat"><i class="ti ti-eye"></i></a>';
                    $btn .= '<a href="' . route('masar.distribusi.edit', $encryptedId) . '" class="btn btn-warning" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger delete-btn" data-id="' . $encryptedId . '" title="Hapus"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['metode_badge', 'status_badge', 'action'])
                ->make(true);
        }

        $hadiah_id = null;
        if ($request->filled('hadiah_id')) {
            try {
                $hadiah_id = Crypt::decrypt($request->hadiah_id);
            } catch (\Exception $e) {
                $hadiah_id = null;
            }
        }

        return view('masar.distribusi.index', [
            'hadiah_id' => $hadiah_id
        ]);
    }

    /**
     * Show the form for creating a new distribusi hadiah
     */
    public function create(Request $request)
    {
        $hadiah_id = null;
        if ($request->filled('hadiah_id')) {
            try {
                $hadiah_id = Crypt::decrypt($request->hadiah_id);
            } catch (\Exception $e) {
                $hadiah_id = null;
            }
        }

        $hadiah_list = HadiahMasar::where('status', '!=', 'habis')->get();
        // Load YayasanMasar (jamaah data dari yayasan masar)
        $jamaah_list = YayasanMasar::where('status_aktif', 1)
            ->orderBy('nama')
            ->get();

        return view('masar.distribusi.create', [
            'hadiah_list' => $hadiah_list,
            'jamaah_list' => $jamaah_list,
            'hadiah_id' => $hadiah_id
        ]);
    }

    /**
     * Store a newly created distribusi hadiah
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hadiah_id' => 'required|exists:hadiah_masar,id',
            'jamaah_id' => 'nullable|exists:yayasan_masar,id',
            'tanggal_distribusi' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'metode_distribusi' => 'required|in:langsung,undian,prestasi,kehadiran',
            'penerima' => 'required|string|max:100',
            'petugas_distribusi' => 'nullable|string|max:100',
            'status_distribusi' => 'required|in:pending,diterima,ditolak',
            'ukuran' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get hadiah
            $hadiah = HadiahMasar::findOrFail($request->hadiah_id);

            // Check stok
            if ($request->status_distribusi == 'diterima' && $hadiah->stok_tersedia < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok hadiah tidak cukup. Tersedia: ' . $hadiah->stok_tersedia
                ], 422);
            }

            // Create distribusi
            $distribusi = DistribusiHadiahYayasanMasar::create([
                'nomor_distribusi' => DistribusiHadiahYayasanMasar::generateNomorDistribusi(),
                'hadiah_id' => $request->hadiah_id,
                'jamaah_id' => $request->jamaah_id,
                'tanggal_distribusi' => $request->tanggal_distribusi,
                'jumlah' => $request->jumlah,
                'ukuran' => $request->ukuran,
                'metode_distribusi' => $request->metode_distribusi,
                'penerima' => $request->penerima,
                'petugas_distribusi' => $request->petugas_distribusi ?? auth()->user()->name,
                'status_distribusi' => $request->status_distribusi,
                'keterangan' => $request->keterangan
            ]);

            // Update stok jika diterima
            if ($request->status_distribusi == 'diterima') {
                $hadiah->stok_tersedia -= $request->jumlah;
                $hadiah->stok_terbagikan = ($hadiah->stok_awal - $hadiah->stok_tersedia);
                
                if ($hadiah->stok_tersedia <= 0) {
                    $hadiah->status = 'habis';
                }
                
                $hadiah->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Distribusi hadiah berhasil ditambahkan',
                'data' => $distribusi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified distribusi hadiah
     */
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $distribusi = DistribusiHadiahYayasanMasar::with(['hadiah', 'jamaah'])->findOrFail($id);
        return view('masar.distribusi.show', ['distribusi' => $distribusi]);
    }

    /**
     * Show the form for editing the specified distribusi hadiah
     */
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $distribusi = DistribusiHadiahYayasanMasar::findOrFail($id);
        $hadiah_list = HadiahMasar::all();
        // Load YayasanMasar (jamaah data dari yayasan masar)
        $jamaah_list = YayasanMasar::where('status_aktif', 1)
            ->orderBy('nama')
            ->get();

        return view('masar.distribusi.edit', [
            'distribusi' => $distribusi,
            'hadiah_list' => $hadiah_list,
            'jamaah_list' => $jamaah_list
        ]);
    }

    /**
     * Update the specified distribusi hadiah
     */
    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'hadiah_id' => 'required|exists:hadiah_masar,id',
            'jamaah_id' => 'nullable|exists:yayasan_masar,id',
            'tanggal_distribusi' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'metode_distribusi' => 'required|in:langsung,undian,prestasi,kehadiran',
            'penerima' => 'required|string|max:100',
            'petugas_distribusi' => 'nullable|string|max:100',
            'status_distribusi' => 'required|in:pending,diterima,ditolak',
            'ukuran' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $distribusi = DistribusiHadiahYayasanMasar::findOrFail($id);
            $old_status = $distribusi->status_distribusi;
            $old_jumlah = $distribusi->jumlah;
            $old_hadiah_id = $distribusi->hadiah_id;

            // Update distribusi
            $distribusi->update([
                'hadiah_id' => $request->hadiah_id,
                'jamaah_id' => $request->jamaah_id,
                'tanggal_distribusi' => $request->tanggal_distribusi,
                'jumlah' => $request->jumlah,
                'ukuran' => $request->ukuran,
                'metode_distribusi' => $request->metode_distribusi,
                'penerima' => $request->penerima,
                'petugas_distribusi' => $request->petugas_distribusi,
                'status_distribusi' => $request->status_distribusi,
                'keterangan' => $request->keterangan
            ]);

            // Handle stok changes
            if ($old_hadiah_id == $request->hadiah_id) {
                // Same hadiah
                if ($old_status == 'diterima' && $request->status_distribusi != 'diterima') {
                    // Return stok
                    $hadiah = HadiahMasar::findOrFail($request->hadiah_id);
                    $hadiah->stok_tersedia += $old_jumlah;
                    if ($hadiah->stok_tersedia > 0) {
                        $hadiah->status = 'tersedia';
                    }
                    $hadiah->stok_terbagikan = $hadiah->stok_awal - $hadiah->stok_tersedia;
                    $hadiah->save();
                } elseif ($old_status != 'diterima' && $request->status_distribusi == 'diterima') {
                    // Deduct stok
                    $hadiah = HadiahMasar::findOrFail($request->hadiah_id);
                    if ($hadiah->stok_tersedia < $request->jumlah) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok hadiah tidak cukup. Tersedia: ' . $hadiah->stok_tersedia
                        ], 422);
                    }
                    $hadiah->stok_tersedia -= $request->jumlah;
                    if ($hadiah->stok_tersedia <= 0) {
                        $hadiah->status = 'habis';
                    }
                    $hadiah->stok_terbagikan = $hadiah->stok_awal - $hadiah->stok_tersedia;
                    $hadiah->save();
                } elseif ($old_status == 'diterima' && $request->status_distribusi == 'diterima' && $old_jumlah != $request->jumlah) {
                    // Change jumlah
                    $hadiah = HadiahMasar::findOrFail($request->hadiah_id);
                    $diff = $request->jumlah - $old_jumlah;
                    if ($hadiah->stok_tersedia < $diff) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok hadiah tidak cukup. Tersedia: ' . $hadiah->stok_tersedia
                        ], 422);
                    }
                    $hadiah->stok_tersedia -= $diff;
                    if ($hadiah->stok_tersedia <= 0) {
                        $hadiah->status = 'habis';
                    }
                    $hadiah->stok_terbagikan = $hadiah->stok_awal - $hadiah->stok_tersedia;
                    $hadiah->save();
                }
            } else {
                // Different hadiah
                if ($old_status == 'diterima') {
                    // Return old hadiah stok
                    $old_hadiah = HadiahMasar::findOrFail($old_hadiah_id);
                    $old_hadiah->stok_tersedia += $old_jumlah;
                    if ($old_hadiah->stok_tersedia > 0) {
                        $old_hadiah->status = 'tersedia';
                    }
                    $old_hadiah->stok_terbagikan = $old_hadiah->stok_awal - $old_hadiah->stok_tersedia;
                    $old_hadiah->save();
                }

                if ($request->status_distribusi == 'diterima') {
                    // Deduct new hadiah stok
                    $new_hadiah = HadiahMasar::findOrFail($request->hadiah_id);
                    if ($new_hadiah->stok_tersedia < $request->jumlah) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok hadiah tidak cukup. Tersedia: ' . $new_hadiah->stok_tersedia
                        ], 422);
                    }
                    $new_hadiah->stok_tersedia -= $request->jumlah;
                    if ($new_hadiah->stok_tersedia <= 0) {
                        $new_hadiah->status = 'habis';
                    }
                    $new_hadiah->stok_terbagikan = $new_hadiah->stok_awal - $new_hadiah->stok_tersedia;
                    $new_hadiah->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Distribusi hadiah berhasil diperbarui',
                'data' => $distribusi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified distribusi hadiah
     */
    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ID'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $distribusi = DistribusiHadiahYayasanMasar::findOrFail($id);
            $hadiah_id = $distribusi->hadiah_id;
            $jumlah = $distribusi->jumlah;
            $status = $distribusi->status_distribusi;

            // Return stok if diterima
            if ($status == 'diterima') {
                $hadiah = HadiahMasar::findOrFail($hadiah_id);
                $hadiah->stok_tersedia += $jumlah;
                if ($hadiah->stok_tersedia > 0) {
                    $hadiah->status = 'tersedia';
                }
                $hadiah->stok_terbagikan = $hadiah->stok_awal - $hadiah->stok_tersedia;
                $hadiah->save();
            }

            $distribusi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Distribusi hadiah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export distribusi hadiah to PDF
     */
    public function exportPDF(Request $request)
    {
        $distribusi = DistribusiHadiahYayasanMasar::with(['hadiah', 'jamaah'])
            ->orderBy('tanggal_distribusi', 'desc');

        // Apply filters
        if ($request->filled('metode')) {
            $distribusi->where('metode_distribusi', $request->metode);
        }

        if ($request->filled('status')) {
            $distribusi->where('status_distribusi', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $distribusi->whereBetween('tanggal_distribusi', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $distribusi = $distribusi->get();

        $pdf = Pdf::loadView('masar.distribusi.pdf', ['data' => $distribusi]);
        return $pdf->download('distribusi_hadiah_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Get statistik distribusi hadiah (API)
     */
    public function getStatistik(Request $request)
    {
        $query = DistribusiHadiahYayasanMasar::query();

        // Apply filters if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_distribusi', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $total_distribusi = $query->count();
        $total_diterima = (clone $query)->where('status_distribusi', 'diterima')->count();
        $total_pending = (clone $query)->where('status_distribusi', 'pending')->count();
        $total_ditolak = (clone $query)->where('status_distribusi', 'ditolak')->count();

        // Per metode
        $per_metode = (clone $query)
            ->selectRaw('metode_distribusi, COUNT(*) as jumlah')
            ->groupBy('metode_distribusi')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_distribusi' => $total_distribusi,
                'total_diterima' => $total_diterima,
                'total_pending' => $total_pending,
                'total_ditolak' => $total_ditolak,
                'per_metode' => $per_metode
            ]
        ]);
    }

    /**
     * Display distribusi hadiah for karyawan (READ-ONLY)
     */
    public function distribusiKaryawan(Request $request)
    {
        if ($request->ajax()) {
            $distribusi = DistribusiHadiahYayasanMasar::with(['hadiah', 'jamaah'])
                ->where('status_distribusi', 'diterima')
                ->select('distribusi_hadiah_yayasan_masar.*');

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $distribusi->where(function ($q) use ($search) {
                    $q->where('nomor_distribusi', 'LIKE', "%{$search}%")
                        ->orWhere('penerima', 'LIKE', "%{$search}%");
                });
            }

            return DataTables::of($distribusi)
                ->addIndexColumn()
                ->addColumn('hadiah_name', function ($row) {
                    return $row->hadiah ? $row->hadiah->nama_hadiah : '-';
                })
                ->addColumn('metode_badge', function ($row) {
                    $badges = [
                        'langsung' => 'primary',
                        'undian' => 'info',
                        'prestasi' => 'success',
                        'kehadiran' => 'warning'
                    ];
                    $color = $badges[$row->metode_distribusi] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->metode_distribusi) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    return '<a href="' . route('masar.karyawan.distribusi.show', $encryptedId) . '" class="btn btn-sm btn-info"><i class="ti ti-eye"></i></a>';
                })
                ->rawColumns(['metode_badge', 'action'])
                ->make(true);
        }

        return view('masar.distribusi.karyawan-index');
    }

    /**
     * Show distribusi hadiah for karyawan (READ-ONLY)
     */
    public function showDistribusiKaryawan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $distribusi = DistribusiHadiahYayasanMasar::with(['hadiah', 'jamaah'])->findOrFail($id);
        return view('masar.distribusi.karyawan-show', ['distribusi' => $distribusi]);
    }

    /**
     * Store distribusi for karyawan (only pending & diterima)
     */
    public function storeDistribusiKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hadiah_id' => 'required|exists:hadiah_masar,id',
            'jamaah_id' => 'nullable|exists:yayasan_masar,id',
            'tanggal_distribusi' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'metode_distribusi' => 'required|in:langsung,undian,prestasi,kehadiran',
            'penerima' => 'required|string|max:100',
            'status_distribusi' => 'required|in:pending,diterima',
            'ukuran' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $hadiah = HadiahMasar::findOrFail($request->hadiah_id);

            if ($request->status_distribusi == 'diterima' && $hadiah->stok_tersedia < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok hadiah tidak cukup. Tersedia: ' . $hadiah->stok_tersedia
                ], 422);
            }

            $distribusi = DistribusiHadiahYayasanMasar::create([
                'nomor_distribusi' => DistribusiHadiahYayasanMasar::generateNomorDistribusi(),
                'hadiah_id' => $request->hadiah_id,
                'jamaah_id' => $request->jamaah_id,
                'tanggal_distribusi' => $request->tanggal_distribusi,
                'jumlah' => $request->jumlah,
                'ukuran' => $request->ukuran,
                'metode_distribusi' => $request->metode_distribusi,
                'penerima' => $request->penerima,
                'petugas_distribusi' => auth()->user()->name,
                'status_distribusi' => $request->status_distribusi,
                'keterangan' => $request->keterangan
            ]);

            if ($request->status_distribusi == 'diterima') {
                $hadiah->stok_tersedia -= $request->jumlah;
                $hadiah->stok_terbagikan = $hadiah->stok_awal - $hadiah->stok_tersedia;
                if ($hadiah->stok_tersedia <= 0) {
                    $hadiah->status = 'habis';
                }
                $hadiah->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Distribusi hadiah berhasil ditambahkan',
                'data' => $distribusi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
