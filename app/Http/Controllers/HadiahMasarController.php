<?php

namespace App\Http\Controllers;

use App\Models\HadiahMasar;
use App\Models\DistribusiHadiahMasar;
use App\Models\YayasanMasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HadiahMasarController extends Controller
{
    /**
     * Display a listing of hadiah.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $hadiah = HadiahMasar::select('hadiah_masar.*');

            return DataTables::of($hadiah)
                ->addIndexColumn()
                ->addColumn('stok_info', function ($row) {
                    $percentage = $row->stok_awal > 0 ? ($row->stok_tersedia / $row->stok_awal) * 100 : 0;
                    $color = $percentage > 50 ? 'success' : ($percentage > 20 ? 'warning' : 'danger');
                    
                    return '<div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-' . $color . '" role="progressbar" style="width: ' . $percentage . '%" aria-valuenow="' . $row->stok_tersedia . '" aria-valuemin="0" aria-valuemax="' . $row->stok_awal . '">
                                    ' . $row->stok_tersedia . '/' . $row->stok_awal . '
                                </div>
                            </div>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badge = $row->status == 'tersedia' ? 'success' : ($row->status == 'habis' ? 'danger' : 'secondary');
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<div class="btn-group" role="group">';
                    
                    // Tombol Distribusi - selalu tampil
                    $btn .= '<a href="' . route('masar.distribusi.index', ['hadiah_id' => $encryptedId]) . '" class="btn btn-sm btn-primary" title="Distribusi Hadiah"><i class="ti ti-gift"></i></a>';
                    
                    $btn .= '<a href="' . route('masar.hadiah.edit', $encryptedId) . '" class="btn btn-sm btn-warning" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $encryptedId . '" title="Hapus"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['stok_info', 'status_badge', 'action'])
                ->make(true);
        }

        return view('masar.hadiah.index');
    }

    /**
     * Show the form for creating a new hadiah.
     */
    public function create()
    {
        return view('masar.hadiah.create');
    }

    /**
     * Store a newly created hadiah.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_hadiah' => 'required|string|max:100',
            'jenis_hadiah' => 'required|in:sarung,peci,gamis,mukena,tasbih,sajadah,al_quran,buku,lainnya',
            'ukuran' => 'nullable|string|max:20',
            'warna' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'stok_awal' => 'required|integer|min:0',
            'nilai_hadiah' => 'nullable|numeric|min:0',
            'tanggal_pengadaan' => 'nullable|date',
            'supplier' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok_ukuran' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['foto', '_token', 'stok_ukuran', 'stok_ukuran_custom_name', 'stok_ukuran_custom_value']);
            
            // Generate kode hadiah
            $data['kode_hadiah'] = HadiahMasar::generateKodeHadiah($request->jenis_hadiah);
            $data['stok_tersedia'] = $request->stok_awal;
            $data['stok_terbagikan'] = 0;

            // Handle stok ukuran
            if ($request->has('stok_ukuran') && is_array($request->stok_ukuran)) {
                // Filter out zero values
                $stokUkuran = array_filter($request->stok_ukuran, function($value) {
                    return $value > 0;
                });
                
                // Handle custom ukuran
                if ($request->has('stok_ukuran_custom_name') && $request->has('stok_ukuran_custom_value')) {
                    $customNames = $request->stok_ukuran_custom_name;
                    $customValues = $request->stok_ukuran_custom_value;
                    
                    foreach ($customNames as $index => $name) {
                        if (!empty($name) && isset($customValues[$index]) && $customValues[$index] > 0) {
                            $stokUkuran[$name] = (int)$customValues[$index];
                        }
                    }
                }
                
                $data['stok_ukuran'] = !empty($stokUkuran) ? $stokUkuran : null;
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/hadiah', $fotoName);
                $data['foto'] = $fotoName;
            }

            HadiahMasar::create($data);

            DB::commit();

            return redirect()->route('masar.hadiah.index')
                ->with('success', 'Data hadiah berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing hadiah.
     */
    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $hadiah = HadiahMasar::findOrFail($id);
            return view('masar.hadiah.edit', compact('hadiah'));
        } catch (\Exception $e) {
            return redirect()->route('masar.hadiah.index')
                ->with('error', 'Data hadiah tidak ditemukan');
        }
    }

    /**
     * Update hadiah.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $validator = Validator::make($request->all(), [
            'nama_hadiah' => 'required|string|max:100',
            'jenis_hadiah' => 'required|in:sarung,peci,gamis,mukena,tasbih,sajadah,al_quran,buku,lainnya',
            'ukuran' => 'nullable|string|max:20',
            'warna' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'stok_awal' => 'required|integer|min:0',
            'nilai_hadiah' => 'nullable|numeric|min:0',
            'tanggal_pengadaan' => 'nullable|date',
            'supplier' => 'nullable|string|max:100',
            'status' => 'required|in:tersedia,habis,tidak_aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok_ukuran' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $hadiah = HadiahMasar::findOrFail($id);
            $data = $request->except(['foto', '_token', '_method', 'stok_ukuran', 'stok_ukuran_custom_name', 'stok_ukuran_custom_value']);

            // Update stok tersedia jika stok awal berubah
            if ($request->stok_awal != $hadiah->stok_awal) {
                $selisih = $request->stok_awal - $hadiah->stok_awal;
                $data['stok_tersedia'] = $hadiah->stok_tersedia + $selisih;
            }

            // Handle stok ukuran
            if ($request->has('stok_ukuran') && is_array($request->stok_ukuran)) {
                // Filter out zero values
                $stokUkuran = array_filter($request->stok_ukuran, function($value) {
                    return $value > 0;
                });
                
                // Handle custom ukuran
                if ($request->has('stok_ukuran_custom_name') && $request->has('stok_ukuran_custom_value')) {
                    $customNames = $request->stok_ukuran_custom_name;
                    $customValues = $request->stok_ukuran_custom_value;
                    
                    foreach ($customNames as $index => $name) {
                        if (!empty($name) && isset($customValues[$index]) && $customValues[$index] > 0) {
                            $stokUkuran[$name] = (int)$customValues[$index];
                        }
                    }
                }
                
                $data['stok_ukuran'] = !empty($stokUkuran) ? $stokUkuran : null;
            } else {
                // If stok_ukuran not provided in request, set to null (disable tracking)
                $data['stok_ukuran'] = null;
            }

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($hadiah->foto) {
                    Storage::delete('public/hadiah/' . $hadiah->foto);
                }

                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/hadiah', $fotoName);
                $data['foto'] = $fotoName;
            }

            $hadiah->update($data);

            DB::commit();

            return redirect()->route('masar.hadiah.index')
                ->with('success', 'Data hadiah berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove hadiah.
     */
    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $hadiah = HadiahMasar::findOrFail($id);

            // Cek apakah sudah ada distribusi
            if ($hadiah->distribusi()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hadiah tidak dapat dihapus karena sudah pernah didistribusikan'
                ], 400);
            }

            // Delete foto
            if ($hadiah->foto) {
                Storage::delete('public/hadiah/' . $hadiah->foto);
            }

            $hadiah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data hadiah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display distribusi hadiah page
     */
    public function distribusi(Request $request)
    {
        if ($request->ajax()) {
            $distribusi = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])
                ->select('distribusi_hadiah_masar.*');

            return DataTables::of($distribusi)
                ->addIndexColumn()
                ->addColumn('jamaah_nama', function ($row) {
                    if ($row->jamaah_id) {
                        return $row->jamaah->nama_jamaah . '<br><small class="text-muted">' . $row->jamaah->nomor_jamaah . '</small>';
                    } else {
                        return '<span class="badge bg-info">Non-Jamaah</span><br><small class="text-muted">' . $row->penerima . '</small>';
                    }
                })
                ->addColumn('hadiah_nama', function ($row) {
                    return $row->hadiah->nama_hadiah . '<br><small class="text-muted">' . $row->hadiah->kode_hadiah . '</small>';
                })
                ->editColumn('ukuran', function ($row) {
                    return $row->ukuran ? '<span class="badge bg-primary">' . $row->ukuran . '</span>' : '<span class="badge bg-secondary">-</span>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badge = $row->status_distribusi == 'diterima' ? 'success' : ($row->status_distribusi == 'pending' ? 'warning' : 'danger');
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status_distribusi) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info detail-btn" data-id="' . $encryptedId . '" data-bs-toggle="tooltip" title="Detail"><i class="ti ti-eye"></i></button>';
                    $btn .= '<a href="' . route('masar.distribusi.edit', $encryptedId) . '" class="btn btn-sm btn-warning edit-btn" data-bs-toggle="tooltip" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-distribusi-btn" data-id="' . $encryptedId . '" data-nomor="' . $row->nomor_distribusi . '" data-bs-toggle="tooltip" title="Hapus"><i class="ti ti-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['jamaah_nama', 'hadiah_nama', 'ukuran', 'status_badge', 'action'])
                ->make(true);
        }

        $hadiahList = HadiahMasar::where('status', 'tersedia')
            ->orderBy('nama_hadiah')
            ->get(['id', 'kode_hadiah', 'nama_hadiah', 'stok_tersedia', 'stok_ukuran']);
        // Ambil data Jamaah dari YayasanMasar
        $jamaahList = YayasanMasar::where('status_aktif', 1)
            ->orderBy('nama_jamaah')
            ->get(['kode_yayasan', 'nama_jamaah', 'no_telepon']);

        // Get selected hadiah_id from query parameter (jika ada)
        $selectedHadiahId = null;
        if ($request->has('hadiah_id')) {
            try {
                $selectedHadiahId = Crypt::decrypt($request->hadiah_id);
            } catch (\Exception $e) {
                // Ignore invalid encrypted ID
            }
        }

        return view('masar.hadiah.distribusi', compact('hadiahList', 'jamaahList', 'selectedHadiahId'));
    }

    /**
     * Build keterangan penerima untuk penyimpanan
     */
    private function buildKeterangan(Request $request)
    {
        $keterangan = "Penerima: {$request->penerima_nama_umum}";
        if ($request->penerima_hp_umum) {
            $keterangan .= " | HP: {$request->penerima_hp_umum}";
        }
        if ($request->penerima_alamat_umum) {
            $keterangan .= " | Alamat: {$request->penerima_alamat_umum}";
        }
        return $keterangan;
    }

    /**
     * Store distribusi hadiah (MULTIPLE ITEMS SUPPORT + NON-JAMAAH + BREAKDOWN UKURAN)
     */
    public function storeDistribusi(Request $request)
    {
        // Validasi untuk penerima (Non-Jamaah) dengan optional jamaah_id
        $rules = [
            'tipe_penerima' => 'required|in:umum',
            'jamaah_id' => 'nullable|exists:yayasan_masar,kode_yayasan',
            'penerima_nama_umum' => 'required|string|max:200',
            'penerima_hp_umum' => 'nullable|string|max:20',
            'penerima_alamat_umum' => 'nullable|string|max:200',
            'hadiah_id' => 'required|array|min:1',
            'hadiah_id.*' => 'required|exists:hadiah_masar,id',
            'tanggal_distribusi' => 'required|date',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'ukuran' => 'nullable|array',
            'ukuran.*' => 'nullable|string|max:20',
            'ukuran_breakdown' => 'nullable|array',
            'ukuran_breakdown.*' => 'nullable|json',
            'penerima' => 'required|string|max:100',
            'petugas_distribusi' => 'nullable|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $totalDistribusi = 0;
            $hadiahDistribusi = [];

            // Generate base nomor distribusi sekali untuk batch ini
            $baseNomorDistribusi = DistribusiHadiahMasar::generateNomorDistribusi();
            
            // Tentukan keterangan penerima (Non-Jamaah atau dari Jamaah)
            $jamaahId = null;
            $keteranganPenerima = '';
            
            if ($request->filled('jamaah_id')) {
                // Jika jamaah dipilih, gunakan nama jamaah
                $jamaahId = $request->jamaah_id;
                $jamaah = YayasanMasar::where('kode_yayasan', $jamaahId)->firstOrFail();
                $keteranganPenerima = "Jamaah Yayasan MASAR: {$jamaah->nama_jamaah}";
            } else {
                // Penerima umum/non-jamaah
                $keteranganPenerima = $this->buildKeterangan($request);
            }

            // Loop setiap hadiah yang dipilih
            foreach ($request->hadiah_id as $index => $hadiahId) {
                $hadiah = HadiahMasar::findOrFail($hadiahId);
                $jumlah = $request->jumlah[$index];
                $ukuran = $request->ukuran[$index] ?? null;
                $breakdownUkuran = $request->ukuran_breakdown[$index] ?? null;

                // Cek apakah menggunakan breakdown ukuran (multiple sizes)
                if (!empty($breakdownUkuran)) {
                    $breakdown = json_decode($breakdownUkuran, true);
                    
                    // Validasi dan kurangi stok per ukuran dari breakdown
                    $stokUkuran = $hadiah->stok_ukuran;
                    foreach ($breakdown as $size => $qty) {
                        if (!isset($stokUkuran[$size])) {
                            throw new \Exception("Ukuran {$size} tidak tersedia untuk {$hadiah->nama_hadiah}");
                        }
                        if ($stokUkuran[$size] < $qty) {
                            throw new \Exception("Stok ukuran {$size} tidak mencukupi (tersedia: {$stokUkuran[$size]})");
                        }
                        $stokUkuran[$size] -= $qty;
                        if ($stokUkuran[$size] <= 0) {
                            unset($stokUkuran[$size]);
                        }
                    }
                    $hadiah->update(['stok_ukuran' => empty($stokUkuran) ? null : $stokUkuran]);
                    
                    // Simpan sebagai JSON di kolom keterangan atau ukuran
                    $ukuranInfo = implode(', ', array_map(fn($s, $q) => "{$s}={$q}", array_keys($breakdown), $breakdown));
                    
                } elseif (!empty($hadiah->stok_ukuran) && !empty($ukuran)) {
                    // Single ukuran (cara lama)
                    if (!isset($hadiah->stok_ukuran[$ukuran])) {
                        throw new \Exception("Ukuran {$ukuran} untuk hadiah {$hadiah->nama_hadiah} tidak tersedia");
                    }
                    
                    if ($hadiah->stok_ukuran[$ukuran] < $jumlah) {
                        throw new \Exception("Stok ukuran {$ukuran} untuk {$hadiah->nama_hadiah} tidak mencukupi (tersedia: {$hadiah->stok_ukuran[$ukuran]})");
                    }
                    
                    // Reduce stock by size
                    $stokUkuran = $hadiah->stok_ukuran;
                    $stokUkuran[$ukuran] -= $jumlah;
                    
                    if ($stokUkuran[$ukuran] <= 0) {
                        unset($stokUkuran[$ukuran]);
                    }
                    
                    $hadiah->update(['stok_ukuran' => empty($stokUkuran) ? null : $stokUkuran]);
                    $ukuranInfo = $ukuran;
                    
                } else {
                    // Normal stock check (no size tracking)
                    if ($hadiah->stok_tersedia < $jumlah) {
                        throw new \Exception("Stok hadiah {$hadiah->nama_hadiah} tidak mencukupi");
                    }
                    $ukuranInfo = null;
                }

                // Create distribusi record - tambahkan suffix item number untuk unique nomor
                $itemNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                $nomorDistribusi = $baseNomorDistribusi . '-' . $itemNumber;
                
                $dataDistribusi = [
                    'nomor_distribusi' => $nomorDistribusi,
                    'jamaah_id' => $jamaahId, // NULL jika non-jamaah
                    'hadiah_id' => $hadiahId,
                    'tanggal_distribusi' => $request->tanggal_distribusi,
                    'jumlah' => $jumlah,
                    'ukuran' => $ukuranInfo ?? $ukuran, // Simpan breakdown atau single ukuran
                    'metode_distribusi' => $request->metode_distribusi ?? 'langsung',
                    'penerima' => $request->penerima,
                    'petugas_distribusi' => $request->petugas_distribusi,
                    'keterangan' => $keteranganPenerima . ($request->keterangan ? " | {$request->keterangan}" : ''),
                    'status_distribusi' => 'diterima',
                ];

                DistribusiHadiahMasar::create($dataDistribusi);

                // Update overall stock
                $hadiah->updateStokSetelahDistribusi($jumlah);

                $totalDistribusi++;
                $hadiahDistribusi[] = $hadiah->nama_hadiah . ($ukuranInfo ? " ({$ukuranInfo})" : '') . " x{$jumlah}";
            }

            DB::commit();

            $pesanSuccess = "Berhasil mendistribusikan {$totalDistribusi} jenis hadiah: " . implode(', ', $hadiahDistribusi);
            if ($request->tipe_penerima === 'umum') {
                $pesanSuccess .= " kepada {$request->penerima_nama_umum} (Non-Jamaah)";
            }

            return redirect()->route('masar.distribusi.index')
                ->with('success', $pesanSuccess);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show detail distribusi
     */
    public function showDistribusi(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $distribusi = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])->findOrFail($id);
            
            // Jika AJAX request, return view modal
            if ($request->ajax()) {
                return view('masar.hadiah.show_distribusi_modal', compact('distribusi'));
            }
            
            // Jika bukan AJAX, return full page
            return view('masar.hadiah.show_distribusi', compact('distribusi'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
            return redirect()->route('masar.distribusi.index')
                ->with('error', 'Data distribusi tidak ditemukan');
        }
    }

    /**
     * Edit distribusi
     */
    public function editDistribusi($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $distribusi = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])->findOrFail($id);
            
            $hadiahList = HadiahMasar::where('status', 'tersedia')
                ->orderBy('nama_hadiah')
                ->get(['id', 'kode_hadiah', 'nama_hadiah', 'stok_tersedia', 'stok_ukuran']);
            
            $jamaahList = YayasanMasar::where('status_aktif', 1)
                ->orderBy('nama_jamaah')
                ->get(['kode_yayasan', 'nama_jamaah', 'no_telepon']);
            
            return view('masar.hadiah.edit_distribusi', compact('distribusi', 'hadiahList', 'jamaahList'));
        } catch (\Exception $e) {
            return redirect()->route('masar.distribusi.index')
                ->with('error', 'Data distribusi tidak ditemukan');
        }
    }

    /**
     * Update distribusi
     */
    public function updateDistribusi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipe_penerima' => 'required|in:umum',
            'jamaah_id' => 'nullable|exists:yayasan_masar,kode_yayasan',
            'penerima_nama_umum' => 'required|string|max:200',
            'penerima_hp_umum' => 'nullable|string|max:20',
            'penerima_alamat_umum' => 'nullable|string|max:200',
            'hadiah_id' => 'required|exists:hadiah_masar,id',
            'tanggal_distribusi' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'ukuran' => 'nullable|string|max:20',
            'penerima' => 'required|string|max:100',
            'petugas_distribusi' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($id);
            $distribusi = DistribusiHadiahMasar::findOrFail($id);
            $oldJumlah = $distribusi->jumlah;
            $oldUkuran = $distribusi->ukuran;
            $oldHadiahId = $distribusi->hadiah_id;

            // Kembalikan stok hadiah lama
            $oldHadiah = HadiahMasar::findOrFail($oldHadiahId);
            $oldHadiah->stok_tersedia += $oldJumlah;
            $oldHadiah->stok_terbagikan -= $oldJumlah;
            
            // Return stok ukuran lama jika ada
            if (!empty($oldHadiah->stok_ukuran) && !empty($oldUkuran)) {
                $stokUkuran = $oldHadiah->stok_ukuran;
                $stokUkuran[$oldUkuran] = ($stokUkuran[$oldUkuran] ?? 0) + $oldJumlah;
                $oldHadiah->stok_ukuran = $stokUkuran;
            }
            
            if ($oldHadiah->stok_tersedia > 0 && $oldHadiah->status == 'habis') {
                $oldHadiah->status = 'tersedia';
            }
            $oldHadiah->save();

            // Proses hadiah baru
            $newHadiah = HadiahMasar::findOrFail($request->hadiah_id);
            
            // Check stok baru
            if (!empty($newHadiah->stok_ukuran) && !empty($request->ukuran)) {
                if (!isset($newHadiah->stok_ukuran[$request->ukuran])) {
                    return redirect()->back()
                        ->with('error', 'Ukuran yang dipilih tidak tersedia')
                        ->withInput();
                }
                
                if ($newHadiah->stok_ukuran[$request->ukuran] < $request->jumlah) {
                    return redirect()->back()
                        ->with('error', "Stok ukuran {$request->ukuran} tidak mencukupi")
                        ->withInput();
                }
                
                // Kurangi stok ukuran baru
                $stokUkuran = $newHadiah->stok_ukuran;
                $stokUkuran[$request->ukuran] -= $request->jumlah;
                if ($stokUkuran[$request->ukuran] <= 0) {
                    unset($stokUkuran[$request->ukuran]);
                }
                $newHadiah->stok_ukuran = empty($stokUkuran) ? null : $stokUkuran;
            } else {
                if ($newHadiah->stok_tersedia < $request->jumlah) {
                    return redirect()->back()
                        ->with('error', 'Stok hadiah tidak mencukupi')
                        ->withInput();
                }
            }

            // Update distribusi dengan penerima dari form atau jamaah
            $jamaahId = null;
            $keteranganPenerima = '';
            $penerimaNama = $request->penerima_nama_umum;
            
            if ($request->filled('jamaah_id')) {
                $jamaahId = $request->jamaah_id;
                $jamaah = YayasanMasar::where('kode_yayasan', $jamaahId)->firstOrFail();
                $penerimaNama = $jamaah->nama_jamaah;
                $keteranganPenerima = "Jamaah Yayasan MASAR: {$jamaah->nama_jamaah}";
            } else {
                $keteranganPenerima = $this->buildKeterangan($request);
            }
            
            $distribusi->update([
                'jamaah_id' => $jamaahId,
                'hadiah_id' => $request->hadiah_id,
                'tanggal_distribusi' => $request->tanggal_distribusi,
                'jumlah' => $request->jumlah,
                'ukuran' => $request->ukuran,
                'penerima' => $penerimaNama,
                'petugas_distribusi' => $request->petugas_distribusi,
                'keterangan' => $keteranganPenerima,
            ]);

            // Update stok hadiah baru
            $newHadiah->updateStokSetelahDistribusi($request->jumlah);

            DB::commit();

            return redirect()->route('masar.distribusi.index')
                ->with('success', 'Distribusi hadiah berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete distribusi
     */
    public function destroyDistribusi($id)
    {
        try {
            DB::beginTransaction();
            
            $id = Crypt::decrypt($id);
            $distribusi = DistribusiHadiahMasar::findOrFail($id);
            
            // Kembalikan stok hadiah
            $hadiah = $distribusi->hadiah;
            $hadiah->stok_tersedia += $distribusi->jumlah;
            $hadiah->stok_terbagikan -= $distribusi->jumlah;
            
            // Return stok per ukuran if exists
            if (!empty($hadiah->stok_ukuran) && !empty($distribusi->ukuran)) {
                $stokUkuran = $hadiah->stok_ukuran;
                $stokUkuran[$distribusi->ukuran] = ($stokUkuran[$distribusi->ukuran] ?? 0) + $distribusi->jumlah;
                $hadiah->stok_ukuran = $stokUkuran;
            }
            
            if ($hadiah->stok_tersedia > 0 && $hadiah->status == 'habis') {
                $hadiah->status = 'tersedia';
            }
            
            $hadiah->save();
            
            // Hapus distribusi
            $distribusi->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data distribusi berhasil dihapus dan stok dikembalikan'
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
     * Laporan stok per ukuran
     */
    public function laporanStokUkuran(Request $request)
    {
        $jenisFilter = $request->get('jenis_hadiah', 'all');
        
        $query = HadiahMasar::whereNotNull('stok_ukuran')
            ->where(function($q) {
                $q->where('stok_ukuran', '!=', '[]')
                  ->where('stok_ukuran', '!=', '{}');
            });
        
        if ($jenisFilter != 'all') {
            $query->where('jenis_hadiah', $jenisFilter);
        }
        
        $hadiahList = $query->get();
        
        return view('masar.laporan.stok_ukuran', compact('hadiahList', 'jenisFilter'));
    }

    /**
     * Laporan rekapitulasi distribusi hadiah
     */
    public function laporanRekapDistribusi(Request $request)
    {
        $query = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])
            ->orderBy('tanggal_distribusi', 'desc');
        
        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_distribusi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_distribusi', '<=', $request->tanggal_sampai);
        }
        
        // Filter by hadiah
        if ($request->filled('hadiah_id')) {
            $query->where('hadiah_id', $request->hadiah_id);
        }
        
        // Filter by ukuran
        if ($request->filled('ukuran')) {
            $query->where('ukuran', $request->ukuran);
        }
        
        $distribusiList = $query->get();
        
        // Get all hadiah for filter dropdown
        $hadiahList = HadiahMasar::orderBy('nama_hadiah')->get();
        
        return view('masar.laporan.rekap_distribusi', compact('distribusiList', 'hadiahList'));
    }

    // ========================================
    // METHODS UNTUK KARYAWAN
    // ========================================

    /**
     * Display a listing of hadiah for karyawan (CAN INPUT)
     */
    public function indexKaryawan(Request $request)
    {
        if ($request->ajax()) {
            $hadiah = HadiahMasar::select('hadiah_masar.*');

            return DataTables::of($hadiah)
                ->addIndexColumn()
                ->addColumn('stok_info', function ($row) {
                    $percentage = $row->stok_awal > 0 ? ($row->stok_tersedia / $row->stok_awal) * 100 : 0;
                    $color = $percentage > 50 ? 'success' : ($percentage > 20 ? 'warning' : 'danger');
                    
                    return '<div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-' . $color . '" role="progressbar" style="width: ' . $percentage . '%" aria-valuenow="' . $row->stok_tersedia . '" aria-valuemin="0" aria-valuemax="' . $row->stok_awal . '">
                                    ' . $row->stok_tersedia . '/' . $row->stok_awal . '
                                </div>
                            </div>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badge = $row->status == 'tersedia' ? 'success' : ($row->status == 'habis' ? 'danger' : 'secondary');
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $encryptedId . '" title="Lihat Detail"><i class="ti ti-eye"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['stok_info', 'status_badge', 'action'])
                ->make(true);
        }

        return view('masar.karyawan.hadiah.index');
    }

    /**
     * Show the form for creating hadiah for karyawan (CAN INPUT)
     */
    public function createKaryawan()
    {
        return view('masar.karyawan.hadiah.create');
    }

    /**
     * Store hadiah for karyawan (CAN INPUT)
     */
    public function storeKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_hadiah' => 'required|string|max:255',
            'jenis_hadiah' => 'required|in:pakaian,aksesoris,elektronik,voucher,lainnya',
            'deskripsi' => 'nullable|string',
            'stok_awal' => 'required|integer|min:1',
            'ukuran.*' => 'nullable|string|max:50',
            'jumlah.*' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
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

            $data = $request->except('foto', 'ukuran', 'jumlah');
            $data['stok_tersedia'] = $request->stok_awal;
            $data['status'] = 'tersedia';

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/hadiah_masar', $fotoName);
                $data['foto'] = $fotoName;
            }

            // Handle ukuran data
            if ($request->has('ukuran') && is_array($request->ukuran)) {
                $ukuranData = [];
                foreach ($request->ukuran as $index => $ukuran) {
                    if (!empty($ukuran)) {
                        $ukuranData[] = [
                            'ukuran' => $ukuran,
                            'jumlah' => $request->jumlah[$index] ?? 0
                        ];
                    }
                }
                $data['detail_ukuran'] = json_encode($ukuranData);
            }

            $hadiah = HadiahMasar::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hadiah berhasil ditambahkan',
                'data' => $hadiah
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan hadiah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display distribusi for karyawan (VIEW ONLY)
     */
    public function distribusiKaryawan(Request $request)
    {
        if ($request->ajax()) {
            $query = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])
                ->orderBy('tanggal_distribusi', 'desc');

            if ($request->filled('hadiah_id')) {
                $hadiahId = Crypt::decrypt($request->hadiah_id);
                $query->where('hadiah_id', $hadiahId);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('jamaah_nama', function ($row) {
                    return $row->jamaah->nama_lengkap ?? '-';
                })
                ->addColumn('hadiah_nama', function ($row) {
                    return $row->hadiah->nama_hadiah ?? '-';
                })
                ->addColumn('tanggal', function ($row) {
                    return date('d/m/Y', strtotime($row->tanggal_distribusi));
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = Crypt::encrypt($row->id);
                    $btn = '<button type="button" class="btn btn-sm btn-info view-distribusi-btn" data-id="' . $encryptedId . '" title="Lihat Detail"><i class="ti ti-eye"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $hadiahList = HadiahMasar::orderBy('nama_hadiah')->get();
        return view('masar.karyawan.hadiah.distribusi', compact('hadiahList'));
    }

    /**
     * Store new distribusi for karyawan
     */
    public function storeDistribusiKaryawan(Request $request)
    {
        // Validation rules - Support multiple hadiah (array)
        $rules = [
            'tipe_penerima' => 'required|in:jamaah,umum',
            'hadiah_id' => 'required|array|min:1',
            'hadiah_id.*' => 'required|exists:hadiah_masar,id',
            'tanggal_distribusi' => 'required|date',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'ukuran' => 'nullable|array',
            'ukuran.*' => 'nullable|string|max:20',
            'ukuran_breakdown' => 'nullable|array',
            'ukuran_breakdown.*' => 'nullable|json',
            'penerima' => 'required|string|max:100',
            'petugas_distribusi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
        ];

        // Conditional validation based on tipe_penerima
        if ($request->tipe_penerima === 'jamaah') {
            $rules['jamaah_id'] = 'required|exists:jamaah_masar,id';
        } else {
            $rules['penerima_nama_umum'] = 'required|string|max:200';
            $rules['penerima_hp_umum'] = 'nullable|string|max:20';
            $rules['penerima_alamat_umum'] = 'nullable|string|max:200';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate nomor distribusi base
            $tanggal = date('Ymd', strtotime($request->tanggal_distribusi));
            $lastDistribusi = DistribusiHadiahMasar::whereDate('tanggal_distribusi', $request->tanggal_distribusi)
                ->orderBy('id', 'desc')
                ->first();
            
            $sequence = $lastDistribusi ? ((int)substr($lastDistribusi->nomor_distribusi, -4) + 1) : 1;
            $baseNomorDistribusi = 'DSM-' . $tanggal . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Prepare keterangan penerima
            $keteranganPenerima = '';
            if ($request->tipe_penerima === 'jamaah') {
                $jamaah = JamaahMasar::findOrFail($request->jamaah_id);
                $keteranganPenerima = "Jamaah Terdaftar: {$jamaah->nama_jamaah}";
            } else {
                $keteranganPenerima = "Penerima Umum: {$request->penerima_nama_umum}";
                if ($request->penerima_hp_umum) {
                    $keteranganPenerima .= " | HP: {$request->penerima_hp_umum}";
                }
            }

            $totalDistribusi = 0;
            $hadiahDistribusi = [];

            // Loop through each hadiah and create distribusi record
            foreach ($request->hadiah_id as $index => $hadiahId) {
                $hadiah = HadiahMasar::findOrFail($hadiahId);
                $jumlah = $request->jumlah[$index];
                $ukuran = $request->ukuran[$index] ?? null;
                $breakdownUkuran = $request->ukuran_breakdown[$index] ?? null;

                // Check stock and update accordingly
                if (!empty($breakdownUkuran)) {
                    $breakdown = json_decode($breakdownUkuran, true);
                    
                    $stokUkuran = $hadiah->stok_ukuran;
                    foreach ($breakdown as $size => $qty) {
                        if (!isset($stokUkuran[$size])) {
                            throw new \Exception("Ukuran {$size} tidak tersedia untuk {$hadiah->nama_hadiah}");
                        }
                        if ($stokUkuran[$size] < $qty) {
                            throw new \Exception("Stok ukuran {$size} tidak mencukupi (tersedia: {$stokUkuran[$size]})");
                        }
                        $stokUkuran[$size] -= $qty;
                        if ($stokUkuran[$size] <= 0) {
                            unset($stokUkuran[$size]);
                        }
                    }
                    $hadiah->update(['stok_ukuran' => empty($stokUkuran) ? null : $stokUkuran]);
                    
                    $ukuranInfo = implode(', ', array_map(fn($s, $q) => "{$s}={$q}", array_keys($breakdown), $breakdown));
                    
                } elseif (!empty($hadiah->stok_ukuran) && !empty($ukuran)) {
                    if (!isset($hadiah->stok_ukuran[$ukuran])) {
                        throw new \Exception("Ukuran {$ukuran} untuk hadiah {$hadiah->nama_hadiah} tidak tersedia");
                    }
                    
                    if ($hadiah->stok_ukuran[$ukuran] < $jumlah) {
                        throw new \Exception("Stok ukuran {$ukuran} untuk {$hadiah->nama_hadiah} tidak mencukupi (tersedia: {$hadiah->stok_ukuran[$ukuran]})");
                    }
                    
                    $stokUkuran = $hadiah->stok_ukuran;
                    $stokUkuran[$ukuran] -= $jumlah;
                    
                    if ($stokUkuran[$ukuran] <= 0) {
                        unset($stokUkuran[$ukuran]);
                    }
                    
                    $hadiah->update(['stok_ukuran' => empty($stokUkuran) ? null : $stokUkuran]);
                    $ukuranInfo = $ukuran;
                    
                } else {
                    if ($hadiah->stok_tersedia < $jumlah) {
                        throw new \Exception("Stok hadiah {$hadiah->nama_hadiah} tidak mencukupi");
                    }
                    $ukuranInfo = null;
                }

                // Create distribusi record with unique nomor
                $itemNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                $nomorDistribusi = $baseNomorDistribusi . '-' . $itemNumber;
                
                $dataDistribusi = [
                    'nomor_distribusi' => $nomorDistribusi,
                    'hadiah_id' => $hadiahId,
                    'tanggal_distribusi' => $request->tanggal_distribusi,
                    'jumlah' => $jumlah,
                    'ukuran' => $ukuranInfo ?? $ukuran,
                    'penerima' => $request->penerima,
                    'petugas_distribusi' => $request->petugas_distribusi ?? auth()->user()->name,
                    'keterangan' => $keteranganPenerima . ($request->keterangan ? " | {$request->keterangan}" : ''),
                    'status_distribusi' => 'diterima',
                ];

                // Set jamaah_id if applicable
                if ($request->tipe_penerima === 'jamaah') {
                    $dataDistribusi['jamaah_id'] = $request->jamaah_id;
                } else {
                    $dataDistribusi['jamaah_id'] = null;
                }

                DistribusiHadiahMasar::create($dataDistribusi);

                // Update overall stock
                $hadiah->updateStokSetelahDistribusi($jumlah);

                $totalDistribusi++;
                $hadiahDistribusi[] = $hadiah->nama_hadiah . ($ukuranInfo ? " ({$ukuranInfo})" : '') . " x{$jumlah}";
            }

            DB::commit();

            $pesanSuccess = "Berhasil mendistribusikan {$totalDistribusi} jenis hadiah: " . implode(', ', $hadiahDistribusi);
            if ($request->tipe_penerima === 'umum') {
                $pesanSuccess .= " kepada {$request->penerima_nama_umum} (Non-Jamaah)";
            }

            return redirect()->route('masar.karyawan.index')
                ->with('success', $pesanSuccess);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show distribusi detail for karyawan (VIEW ONLY)
     */
    public function showDistribusiKaryawan(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $distribusi = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])->findOrFail($id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $distribusi
                ]);
            }

            return view('masar.karyawan.hadiah.show_distribusi', compact('distribusi'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data distribusi tidak ditemukan'
                ], 404);
            }
            return redirect()->route('masar.karyawan.distribusi.index')
                ->with('error', 'Data distribusi tidak ditemukan');
        }
    }

    /**
     * Display laporan index for karyawan (VIEW ONLY)
     */
    public function laporanIndexKaryawan()
    {
        return view('masar.karyawan.laporan.index');
    }

    /**
     * Display stok ukuran report for karyawan (VIEW ONLY)
     */
    public function laporanStokUkuranKaryawan(Request $request)
    {
        $hadiahList = HadiahMasar::where('status', 'tersedia')
            ->where('stok_ukuran', '!=', null)
            ->orderBy('nama_hadiah')
            ->get();

        return view('masar.karyawan.laporan.stok_ukuran', compact('hadiahList'));
    }

    /**
     * Display rekap distribusi report for karyawan (VIEW ONLY)
     */
    public function laporanRekapDistribusiKaryawan(Request $request)
    {
        $query = DistribusiHadiahMasar::with(['jamaah', 'hadiah'])
            ->orderBy('tanggal_distribusi', 'desc');
        
        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_distribusi', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_distribusi', '<=', $request->tanggal_sampai);
        }
        
        // Filter by hadiah
        if ($request->filled('hadiah_id')) {
            $query->where('hadiah_id', $request->hadiah_id);
        }
        
        // Filter by ukuran
        if ($request->filled('ukuran')) {
            $query->where('ukuran', $request->ukuran);
        }
        
        $distribusiList = $query->get();
        
        // Get all hadiah for filter dropdown
        $hadiahList = HadiahMasar::orderBy('nama_hadiah')->get();
        
        return view('masar.karyawan.laporan.rekap_distribusi', compact('distribusiList', 'hadiahList'));
    }
}
