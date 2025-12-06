<?php

namespace App\Http\Controllers;

use App\Models\UndianUmroh;
use App\Models\PemenangUndianUmroh;
use App\Models\JamaahMajlisTaklim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UndianUmrohController extends Controller
{
    /**
     * Display a listing of undian umroh.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $undian = UndianUmroh::withCount('pemenang')->select('undian_umroh.*');

            return DataTables::of($undian)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'draft' => 'secondary',
                        'aktif' => 'primary',
                        'selesai' => 'success',
                        'batal' => 'danger'
                    ];
                    $badge = $badges[$row->status_undian] ?? 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status_undian) . '</span>';
                })
                ->addColumn('pemenang_info', function ($row) {
                    return $row->pemenang_count . ' / ' . $row->jumlah_pemenang;
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="' . route('majlistaklim.undian.show', $encryptedId) . '" class="btn btn-sm btn-info" title="Detail"><i class="ti ti-eye"></i></a>';
                    $btn .= '<a href="' . route('majlistaklim.undian.edit', $encryptedId) . '" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-edit"></i></a>';
                    if ($row->status_undian == 'aktif' && $row->pemenang_count < $row->jumlah_pemenang) {
                        $btn .= '<a href="' . route('majlistaklim.undian.undi', $encryptedId) . '" class="btn btn-sm btn-success" title="Undi"><i class="ti ti-gift"></i> Undi</a>';
                    }
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $encryptedId . '" title="Hapus"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('majlistaklim.undian.index');
    }

    /**
     * Show the form for creating undian.
     */
    public function create()
    {
        return view('majlistaklim.undian.create');
    }

    /**
     * Store undian.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_program' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_undian' => 'required|date',
            'periode_keberangkatan_dari' => 'nullable|date',
            'periode_keberangkatan_sampai' => 'nullable|date|after_or_equal:periode_keberangkatan_dari',
            'jumlah_pemenang' => 'required|integer|min:1',
            'minimal_kehadiran' => 'required|integer|min:0',
            'syarat_ketentuan' => 'nullable|string',
            'biaya_program' => 'nullable|numeric|min:0',
            'sponsor' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['nomor_undian'] = UndianUmroh::generateNomorUndian();
            $data['status_undian'] = 'draft';

            UndianUmroh::create($data);

            DB::commit();

            return redirect()->route('majlistaklim.undian.index')
                ->with('success', 'Program undian umroh berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display undian detail and pemenang.
     */
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $undian = UndianUmroh::with(['pemenang.jamaah'])->findOrFail($id);

            // Jamaah yang memenuhi syarat
            $jamaahMemenuhi = $undian->jamaahMemenuhi();

            return view('majlistaklim.undian.show', compact('undian', 'jamaahMemenuhi'));
        } catch (\Exception $e) {
            return redirect()->route('majlistaklim.undian.index')
                ->with('error', 'Data undian tidak ditemukan');
        }
    }

    /**
     * Show the form for editing undian.
     */
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $undian = UndianUmroh::findOrFail($id);
            return view('majlistaklim.undian.edit', compact('undian'));
        } catch (\Exception $e) {
            return redirect()->route('majlistaklim.undian.index')
                ->with('error', 'Data undian tidak ditemukan');
        }
    }

    /**
     * Update undian.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama_program' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_undian' => 'required|date',
            'periode_keberangkatan_dari' => 'nullable|date',
            'periode_keberangkatan_sampai' => 'nullable|date|after_or_equal:periode_keberangkatan_dari',
            'jumlah_pemenang' => 'required|integer|min:1',
            'minimal_kehadiran' => 'required|integer|min:0',
            'status_undian' => 'required|in:draft,aktif,selesai,batal',
            'syarat_ketentuan' => 'nullable|string',
            'biaya_program' => 'nullable|numeric|min:0',
            'sponsor' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $undian = UndianUmroh::findOrFail($id);
            $undian->update($request->all());

            DB::commit();

            return redirect()->route('majlistaklim.undian.index')
                ->with('success', 'Data undian umroh berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Proses undian - random jamaah yang memenuhi syarat
     */
    public function undi($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $undian = UndianUmroh::findOrFail($id);

            // Validasi status undian
            if ($undian->status_undian != 'aktif') {
                return redirect()->back()
                    ->with('error', 'Undian harus berstatus aktif untuk melakukan pengundian');
            }

            // Validasi jumlah pemenang sudah terpenuhi
            $jumlahPemenangSekarang = $undian->pemenang()->count();
            if ($jumlahPemenangSekarang >= $undian->jumlah_pemenang) {
                return redirect()->back()
                    ->with('error', 'Jumlah pemenang sudah terpenuhi');
            }

            DB::beginTransaction();

            // Get jamaah yang memenuhi syarat dan belum pernah menang
            $idPemenangSebelumnya = $undian->pemenang()->pluck('jamaah_id')->toArray();
            
            $jamaahMemenuhi = JamaahMajlisTaklim::where('jumlah_kehadiran', '>=', $undian->minimal_kehadiran)
                                               ->where('status_aktif', 'aktif')
                                               ->whereNotIn('id', $idPemenangSebelumnya)
                                               ->inRandomOrder()
                                               ->first();

            if (!$jamaahMemenuhi) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Tidak ada jamaah yang memenuhi syarat untuk diundi');
            }

            // Simpan pemenang
            PemenangUndianUmroh::create([
                'undian_id' => $undian->id,
                'jamaah_id' => $jamaahMemenuhi->id,
                'urutan_pemenang' => $jumlahPemenangSekarang + 1,
                'tanggal_pengumuman' => now(),
                'status_keberangkatan' => 'belum_berangkat'
            ]);

            // Update status umroh jamaah
            $jamaahMemenuhi->update([
                'status_umroh' => true,
                'tanggal_umroh' => $undian->periode_keberangkatan_dari
            ]);

            // Jika semua pemenang sudah terpenuhi, ubah status undian
            if ($jumlahPemenangSekarang + 1 >= $undian->jumlah_pemenang) {
                $undian->update(['status_undian' => 'selesai']);
            }

            DB::commit();

            return redirect()->route('majlistaklim.undian.show', Crypt::encrypt($id))
                ->with('success', 'Selamat! Pemenang berhasil diundi: ' . $jamaahMemenuhi->nama_jamaah);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove undian.
     */
    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $undian = UndianUmroh::findOrFail($id);

            // Cek apakah sudah ada pemenang
            if ($undian->pemenang()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undian tidak dapat dihapus karena sudah memiliki pemenang'
                ], 400);
            }

            $undian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data undian berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
