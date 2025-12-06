<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informasi = Informasi::orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.informasi.index', compact('informasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.informasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'nullable|string',
            'tipe' => 'required|in:banner,link,text',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'priority' => 'nullable|integer',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle file upload
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('informasi/banners', $filename, 'public');
            $data['banner_path'] = $path;
            
            // Sync to public/storage for Windows compatibility
            $this->syncStorageFile('informasi/banners', $filename);
        }

        Informasi::create($data);

        return redirect()->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $informasi = Informasi::findOrFail($id);
        return view('admin.informasi.show', compact('informasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $informasi = Informasi::findOrFail($id);
        return view('admin.informasi.edit', compact('informasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $informasi = Informasi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'nullable|string',
            'tipe' => 'required|in:banner,link,text',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'priority' => 'nullable|integer',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle file upload
        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($informasi->banner_path) {
                Storage::disk('public')->delete($informasi->banner_path);
            }

            $file = $request->file('banner');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('informasi/banners', $filename, 'public');
            $data['banner_path'] = $path;
            
            // Sync to public/storage for Windows compatibility
            $this->syncStorageFile('informasi/banners', $filename);
        }

        $informasi->update($data);

        return redirect()->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(string $id)
    {
        $informasi = Informasi::findOrFail($id);
        $informasi->delete(); // Soft delete

        return redirect()->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil dihapus');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(string $id)
    {
        $informasi = Informasi::findOrFail($id);
        $informasi->is_active = !$informasi->is_active;
        $informasi->save();

        return back()->with('success', 'Status informasi berhasil diubah');
    }

    /**
     * Sync file from storage/app/public to public/storage (Windows fix)
     */
    private function syncStorageFile($folder, $filename)
    {
        try {
            $source = storage_path('app/public/' . $folder . '/' . $filename);
            $destination = public_path('storage/' . $folder . '/' . $filename);

            // Create destination folder if not exists
            if (!is_dir(dirname($destination))) {
                mkdir(dirname($destination), 0755, true);
            }

            // Copy file
            if (file_exists($source)) {
                copy($source, $destination);
            }
        } catch (\Exception $e) {
            // Silent fail - file is still in storage
            \Log::warning('Storage sync failed: ' . $e->getMessage());
        }
    }
}
