<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    /**
     * Constructor - Apply middleware to specific methods
     */
    public function __construct()
    {
        // Only super admin can create, edit, delete
        $this->middleware('role:super admin')->only([
            'create', 'store', 'edit', 'update', 'destroy'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika bukan super admin, redirect ke tampilan karyawan (mobile-friendly)
        if (!auth()->user()->hasRole('super admin')) {
            return redirect()->route('dokumen.karyawan.index', $request->all());
        }

        $query = Document::with(['category', 'uploader']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by access level
        if ($request->filled('access_level')) {
            $query->byAccessLevel($request->access_level);
        }

        // Filter by loker
        if ($request->filled('nomor_loker')) {
            $query->where('nomor_loker', 'like', "%{$request->nomor_loker}%");
        }

        $documents = $query->latest()->paginate(10);
        $categories = DocumentCategory::active()->get();

        return view('dokumen.index', compact('documents', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DocumentCategory::active()->get();
        return view('dokumen.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen' => 'required|string|max:255',
            'document_category_id' => 'required|exists:document_categories,id',
            'deskripsi' => 'nullable|string',
            'nomor_loker' => 'nullable|string|max:20',
            'lokasi_loker' => 'nullable|string|max:100',
            'rak' => 'nullable|string|max:20',
            'baris' => 'nullable|string|max:20',
            'jenis_dokumen' => 'required|in:file,link',
            'file_dokumen' => 'required_if:jenis_dokumen,file|file|max:10240', // Max 10MB
            'link_dokumen' => 'required_if:jenis_dokumen,link|nullable|url',
            'access_level' => 'required|in:public,view_only,restricted',
            'tanggal_dokumen' => 'nullable|date',
            'tanggal_berlaku' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_berlaku',
            'nomor_referensi' => 'nullable|string|max:100',
            'penerbit' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:aktif,arsip,kadaluarsa',
        ], [
            'nama_dokumen.required' => 'Nama dokumen harus diisi',
            'document_category_id.required' => 'Kategori dokumen harus dipilih',
            'jenis_dokumen.required' => 'Jenis dokumen harus dipilih',
            'file_dokumen.required_if' => 'File dokumen harus diupload',
            'file_dokumen.max' => 'Ukuran file maksimal 10MB',
            'link_dokumen.required_if' => 'Link dokumen harus diisi',
            'link_dokumen.url' => 'Link dokumen harus berupa URL yang valid',
            'access_level.required' => 'Level akses harus dipilih',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal berlaku',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate kode dokumen
            $kodeDokumen = Document::generateKodeDokumen(
                $request->document_category_id,
                $request->nomor_loker
            );

            $data = [
                'kode_dokumen' => $kodeDokumen,
                'nama_dokumen' => $request->nama_dokumen,
                'document_category_id' => $request->document_category_id,
                'deskripsi' => $request->deskripsi,
                'nomor_loker' => $request->nomor_loker,
                'lokasi_loker' => $request->lokasi_loker,
                'rak' => $request->rak,
                'baris' => $request->baris,
                'jenis_dokumen' => $request->jenis_dokumen,
                'access_level' => $request->access_level,
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'tanggal_berlaku' => $request->tanggal_berlaku,
                'tanggal_berakhir' => $request->tanggal_berakhir,
                'nomor_referensi' => $request->nomor_referensi,
                'penerbit' => $request->penerbit,
                'tags' => $request->tags,
                'status' => $request->status,
            ];

            // Handle file upload atau link
            if ($request->jenis_dokumen === 'file' && $request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents', $fileName, 'public');

                $data['file_path'] = $filePath;
                $data['file_size'] = $this->formatBytes($file->getSize());
                $data['file_extension'] = $file->getClientOriginalExtension();
                $data['jenis_file'] = $file->getClientMimeType();
            } elseif ($request->jenis_dokumen === 'link') {
                $data['file_path'] = $request->link_dokumen;
                $data['jenis_file'] = 'link';
            }

            Document::create($data);

            return redirect()->route('dokumen.index')
                ->with('success', 'Dokumen berhasil ditambahkan dengan kode: ' . $kodeDokumen);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $document = Document::with(['category', 'uploader', 'updater'])->findOrFail($id);

        // Check permission
        if (!$document->canView()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        // Log access
        DocumentAccessLog::logAccess($document->id, 'view');

        // Increment view count
        $document->incrementView();

        // Get access logs
        $accessLogs = $document->accessLogs()
            ->latest()
            ->take(10)
            ->get();

        return view('dokumen.show', compact('document', 'accessLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $categories = DocumentCategory::active()->get();

        return view('dokumen.edit', compact('document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_dokumen' => 'required|string|max:255',
            'document_category_id' => 'required|exists:document_categories,id',
            'deskripsi' => 'nullable|string',
            'nomor_loker' => 'nullable|string|max:20',
            'lokasi_loker' => 'nullable|string|max:100',
            'rak' => 'nullable|string|max:20',
            'baris' => 'nullable|string|max:20',
            'jenis_dokumen' => 'required|in:file,link',
            'file_dokumen' => 'nullable|file|max:10240',
            'link_dokumen' => 'required_if:jenis_dokumen,link|url',
            'access_level' => 'required|in:public,view_only,restricted',
            'tanggal_dokumen' => 'nullable|date',
            'tanggal_berlaku' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_berlaku',
            'nomor_referensi' => 'nullable|string|max:100',
            'penerbit' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:aktif,arsip,kadaluarsa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'nama_dokumen' => $request->nama_dokumen,
                'document_category_id' => $request->document_category_id,
                'deskripsi' => $request->deskripsi,
                'nomor_loker' => $request->nomor_loker,
                'lokasi_loker' => $request->lokasi_loker,
                'rak' => $request->rak,
                'baris' => $request->baris,
                'jenis_dokumen' => $request->jenis_dokumen,
                'access_level' => $request->access_level,
                'tanggal_dokumen' => $request->tanggal_dokumen,
                'tanggal_berlaku' => $request->tanggal_berlaku,
                'tanggal_berakhir' => $request->tanggal_berakhir,
                'nomor_referensi' => $request->nomor_referensi,
                'penerbit' => $request->penerbit,
                'tags' => $request->tags,
                'status' => $request->status,
            ];

            // Update kode dokumen jika kategori atau loker berubah
            if ($document->document_category_id != $request->document_category_id || 
                $document->nomor_loker != $request->nomor_loker) {
                $data['kode_dokumen'] = Document::generateKodeDokumen(
                    $request->document_category_id,
                    $request->nomor_loker
                );
            }

            // Handle file update
            if ($request->jenis_dokumen === 'file') {
                if ($request->hasFile('file_dokumen')) {
                    // Hapus file lama
                    if ($document->file_path && $document->jenis_dokumen === 'file') {
                        Storage::disk('public')->delete($document->file_path);
                    }

                    $file = $request->file('file_dokumen');
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('documents', $fileName, 'public');

                    $data['file_path'] = $filePath;
                    $data['file_size'] = $this->formatBytes($file->getSize());
                    $data['file_extension'] = $file->getClientOriginalExtension();
                    $data['jenis_file'] = $file->getClientMimeType();
                }
            } elseif ($request->jenis_dokumen === 'link') {
                // Hapus file lama jika ada
                if ($document->file_path && $document->jenis_dokumen === 'file') {
                    Storage::disk('public')->delete($document->file_path);
                }

                $data['file_path'] = $request->link_dokumen;
                $data['jenis_file'] = 'link';
                $data['file_size'] = null;
                $data['file_extension'] = null;
            }

            $document->update($data);

            return redirect()->route('dokumen.index')
                ->with('success', 'Dokumen berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $document = Document::findOrFail($id);
            $document->delete();

            return redirect()->route('dokumen.index')
                ->with('success', 'Dokumen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download document
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Check permission
        if (!$document->canDownload()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh dokumen ini.');
        }

        // Log access
        DocumentAccessLog::logAccess($document->id, 'download');

        // Increment download count
        $document->incrementDownload();

        // Download file
        if ($document->jenis_dokumen === 'file' && $document->file_path) {
            $filePath = storage_path('app/public/' . $document->file_path);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $document->nama_dokumen . '.' . $document->file_extension);
            } else {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }
        } elseif ($document->jenis_dokumen === 'link') {
            return redirect($document->file_path);
        }

        return redirect()->back()->with('error', 'Dokumen tidak dapat diunduh');
    }

    /**
     * Preview document in modal
     */
    public function preview($id)
    {
        try {
            $document = Document::findOrFail($id);

            // Check permission
            if (!$document->canView()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk melihat dokumen ini.'
                ], 403);
            }

            // Log access
            DocumentAccessLog::logAccess($document->id, 'preview');

            $document->load(['category', 'uploader', 'updater']);

            return response()->json([
                'success' => true,
                'document' => $document,
                'canDownload' => $document->canDownload(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search by kode dokumen atau nomor loker
     */
    public function searchByCode(Request $request)
    {
        $search = $request->input('q');

        $documents = Document::with('category')
            ->where(function ($query) use ($search) {
                $query->where('kode_dokumen', 'like', "%{$search}%")
                    ->orWhere('nomor_loker', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json($documents);
    }

    /**
     * Get document info by loker
     */
    public function getByLoker($nomorLoker)
    {
        $documents = Document::with('category')
            ->where('nomor_loker', $nomorLoker)
            ->get();

        if ($documents->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada dokumen di loker ' . $nomorLoker
            ], 404);
        }

        return response()->json([
            'success' => true,
            'documents' => $documents,
            'total' => $documents->count()
        ]);
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    // ============ METHODS FOR KARYAWAN (READ ONLY) ============

    /**
     * Display listing for karyawan (mobile view)
     */
    public function indexKaryawan(Request $request)
    {
        $query = Document::with(['category', 'uploader']);

        // Filter hanya dokumen public dan view_only untuk karyawan
        $query->whereIn('access_level', ['public', 'view_only']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Filter by status (hanya aktif dan arsip untuk karyawan)
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by loker
        if ($request->filled('nomor_loker')) {
            $query->where('nomor_loker', 'like', "%{$request->nomor_loker}%");
        }

        $documents = $query->latest()->paginate(10);
        $categories = DocumentCategory::active()->get();

        return view('dokumen.index-karyawan', compact('documents', 'categories'));
    }

    /**
     * Show document detail for karyawan (AJAX)
     */
    public function showKaryawan($id)
    {
        $document = Document::with(['category', 'uploader'])->findOrFail($id);

        // Check permission
        if (!$document->canView()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat dokumen ini.'
            ], 403);
        }

        // Log access
        DocumentAccessLog::logAccess($document->id, 'view');

        // Increment view count
        $document->incrementView();

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'kode_dokumen' => $document->kode_dokumen,
                'nama_dokumen' => $document->nama_dokumen,
                'deskripsi' => $document->deskripsi,
                'jenis_dokumen' => $document->jenis_dokumen,
                'link_url' => $document->jenis_dokumen === 'link' ? $document->file_path : null,
                'file_path' => $document->jenis_dokumen === 'file' ? $document->file_path : null,
                'file_name' => $document->file_name,
                'access_level' => $document->access_level,
                'nomor_loker' => $document->nomor_loker,
                'lokasi_loker' => $document->lokasi_loker,
                'rak' => $document->rak,
                'baris' => $document->baris,
                'nomor_referensi' => $document->nomor_referensi,
                'penerbit' => $document->penerbit,
                'tanggal_dokumen' => $document->tanggal_dokumen ? $document->tanggal_dokumen->format('Y-m-d') : null,
                'tanggal_dokumen_formatted' => $document->tanggal_dokumen ? $document->tanggal_dokumen->format('d/m/Y') : '-',
                'status' => ucfirst($document->status),
                'jumlah_view' => $document->jumlah_view,
                'jumlah_download' => $document->jumlah_download,
                'category' => [
                    'nama_kategori' => $document->category->nama_kategori,
                    'kode_kategori' => $document->category->kode_kategori,
                    'warna' => $document->category->warna,
                ]
            ]
        ]);
    }

    /**
     * Preview document for karyawan (Full page view)
     */
    public function previewKaryawan($id)
    {
        $document = Document::with(['category', 'uploader'])->findOrFail($id);

        // Check permission
        if (!$document->canView()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        // Log access
        DocumentAccessLog::logAccess($document->id, 'view');

        // Increment view count
        $document->incrementView();

        return view('dokumen.preview-karyawan', compact('document'));
    }

    /**
     * Download document for karyawan
     */
    public function downloadKaryawan($id)
    {
        $document = Document::findOrFail($id);

        // Check permission
        if (!$document->canDownload()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengunduh dokumen ini.');
        }

        // Log access
        DocumentAccessLog::logAccess($document->id, 'download');

        // Increment download count
        $document->incrementDownload();

        if ($document->jenis_dokumen === 'file') {
            $filePath = storage_path('app/public/' . $document->file_path);

            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }

            return response()->download($filePath, $document->nama_dokumen . '.' . $document->file_extension);
        } else {
            // Redirect ke link eksternal
            return redirect($document->file_path);
        }
    }

    /**
     * Export daftar dokumen ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Document::with(['category', 'uploader']);

        // Apply filters from request
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('access_level')) {
            $query->byAccessLevel($request->access_level);
        }

        if ($request->filled('nomor_loker')) {
            $query->where('nomor_loker', 'like', "%{$request->nomor_loker}%");
        }

        $documents = $query->latest()->get();

        $pdf = \PDF::loadView('dokumen.pdf', compact('documents'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Daftar_Dokumen_Perusahaan_' . date('YmdHis') . '.pdf');
    }
}
