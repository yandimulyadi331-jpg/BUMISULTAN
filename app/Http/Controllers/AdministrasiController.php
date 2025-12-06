<?php

namespace App\Http\Controllers;

use App\Models\Administrasi;
use App\Models\TindakLanjutAdministrasi;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AdministrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Administrasi::with(['creator', 'tindakLanjut']);

        if ($request->filled('jenis_administrasi')) {
            $query->where('jenis_administrasi', $request->jenis_administrasi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_surat', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_administrasi', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('penerima', 'like', "%{$search}%");
            });
        }

        $administrasi = $query->latest()->paginate(15);
        $cabangs = Cabang::all();

        $jenisAdministrasi = [
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'undangan_masuk' => 'Undangan Masuk',
            'undangan_keluar' => 'Undangan Keluar',
            'proposal_masuk' => 'Proposal Masuk',
            'proposal_keluar' => 'Proposal Keluar',
            'paket_masuk' => 'Paket Masuk',
            'paket_keluar' => 'Paket Keluar',
            'memo_internal' => 'Memo Internal',
            'sk_internal' => 'SK Internal',
            'surat_tugas' => 'Surat Tugas',
            'surat_keputusan' => 'Surat Keputusan',
            'nota_dinas' => 'Nota Dinas',
            'berita_acara' => 'Berita Acara',
            'kontrak' => 'Kontrak',
            'mou' => 'MoU',
            'dokumen_lainnya' => 'Dokumen Lainnya',
        ];

        return view('administrasi.index', compact('administrasi', 'cabangs', 'jenisAdministrasi'));
    }

    public function create()
    {
        $cabangs = Cabang::all();
        
        $jenisAdministrasi = [
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'undangan_masuk' => 'Undangan Masuk',
            'undangan_keluar' => 'Undangan Keluar',
            'proposal_masuk' => 'Proposal Masuk',
            'proposal_keluar' => 'Proposal Keluar',
            'paket_masuk' => 'Paket Masuk',
            'paket_keluar' => 'Paket Keluar',
            'memo_internal' => 'Memo Internal',
            'sk_internal' => 'SK Internal',
            'surat_tugas' => 'Surat Tugas',
            'surat_keputusan' => 'Surat Keputusan',
            'nota_dinas' => 'Nota Dinas',
            'berita_acara' => 'Berita Acara',
            'kontrak' => 'Kontrak',
            'mou' => 'MoU',
            'dokumen_lainnya' => 'Dokumen Lainnya',
        ];

        return view('administrasi.create', compact('cabangs', 'jenisAdministrasi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_administrasi' => 'required|in:surat_masuk,surat_keluar,undangan_masuk,undangan_keluar,proposal_masuk,proposal_keluar,paket_masuk,paket_keluar,memo_internal,sk_internal,surat_tugas,surat_keputusan,nota_dinas,berita_acara,kontrak,mou,dokumen_lainnya',
            'nomor_surat' => 'nullable|string|max:255',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'required|string|max:500',
            // Field undangan
            'nama_acara' => 'nullable|string|max:255',
            'tanggal_acara_mulai' => 'nullable|date',
            'tanggal_acara_selesai' => 'nullable|date|after_or_equal:tanggal_acara_mulai',
            'waktu_acara_mulai' => 'nullable|date_format:H:i',
            'waktu_acara_selesai' => 'nullable|date_format:H:i',
            'lokasi_acara' => 'nullable|string|max:255',
            'alamat_acara' => 'nullable|string',
            'dress_code' => 'nullable|string|max:255',
            'catatan_acara' => 'nullable|string',
            // Field lainnya
            'ringkasan' => 'nullable|string',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'status' => 'required|in:pending,proses,selesai,ditolak,expired',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'disposisi_ke' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Validasi khusus undangan
        if (in_array($request->jenis_administrasi, ['undangan_masuk', 'undangan_keluar'])) {
            $request->validate([
                'nama_acara' => 'required|string|max:255',
                'tanggal_acara_mulai' => 'required|date',
                'lokasi_acara' => 'required|string|max:255',
            ], [
                'nama_acara.required' => 'Nama acara wajib diisi untuk undangan',
                'tanggal_acara_mulai.required' => 'Tanggal mulai acara wajib diisi untuk undangan',
                'lokasi_acara.required' => 'Lokasi acara wajib diisi untuk undangan',
            ]);
        }

        // Generate kode administrasi
        $validated['kode_administrasi'] = Administrasi::generateKodeAdministrasi();
        $validated['created_by'] = Auth::id();

        // Upload file dokumen
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_dokumen'] = $file->storeAs('administrasi/dokumen', $fileName, 'public');
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_foto_' . $foto->getClientOriginalName();
            $validated['foto'] = $foto->storeAs('administrasi/foto', $fotoName, 'public');
        }

        // Handle multiple lampiran
        if ($request->hasFile('lampiran')) {
            $lampiranFiles = [];
            foreach ($request->file('lampiran') as $lampiran) {
                $lampiranName = time() . '_' . uniqid() . '_' . $lampiran->getClientOriginalName();
                $path = $lampiran->storeAs('administrasi/lampiran', $lampiranName, 'public');
                $lampiranFiles[] = $path;
            }
            $validated['lampiran'] = $lampiranFiles;
        }

        $administrasi = Administrasi::create($validated);

        return redirect()->route('administrasi.index')
            ->with('success', 'Data administrasi berhasil ditambahkan dengan kode: ' . $administrasi->kode_administrasi);
    }

    public function show(Administrasi $administrasi)
    {
        $administrasi->load(['tindakLanjut', 'creator', 'updater']);
        return view('administrasi.show', compact('administrasi'));
    }

    public function edit(Administrasi $administrasi)
    {
        $cabangs = Cabang::all();
        
        $jenisAdministrasi = [
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'undangan_masuk' => 'Undangan Masuk',
            'undangan_keluar' => 'Undangan Keluar',
            'proposal_masuk' => 'Proposal Masuk',
            'proposal_keluar' => 'Proposal Keluar',
            'paket_masuk' => 'Paket Masuk',
            'paket_keluar' => 'Paket Keluar',
            'memo_internal' => 'Memo Internal',
            'sk_internal' => 'SK Internal',
            'surat_tugas' => 'Surat Tugas',
            'surat_keputusan' => 'Surat Keputusan',
            'nota_dinas' => 'Nota Dinas',
            'berita_acara' => 'Berita Acara',
            'kontrak' => 'Kontrak',
            'mou' => 'MoU',
            'dokumen_lainnya' => 'Dokumen Lainnya',
        ];

        return view('administrasi.edit', compact('administrasi', 'cabangs', 'jenisAdministrasi'));
    }

    public function update(Request $request, Administrasi $administrasi)
    {
        $validated = $request->validate([
            'jenis_administrasi' => 'required|in:surat_masuk,surat_keluar,undangan_masuk,undangan_keluar,proposal_masuk,proposal_keluar,paket_masuk,paket_keluar,memo_internal,sk_internal,surat_tugas,surat_keputusan,nota_dinas,berita_acara,kontrak,mou,dokumen_lainnya',
            'nomor_surat' => 'nullable|string|max:255',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'required|string|max:500',
            'ringkasan' => 'nullable|string',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'status' => 'required|in:pending,proses,selesai,ditolak,expired',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'disposisi_ke' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $validated['updated_by'] = Auth::id();

        // Upload file dokumen baru
        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama
            if ($administrasi->file_dokumen && Storage::disk('public')->exists($administrasi->file_dokumen)) {
                Storage::disk('public')->delete($administrasi->file_dokumen);
            }
            
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_dokumen'] = $file->storeAs('administrasi/dokumen', $fileName, 'public');
        }

        // Upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($administrasi->foto && Storage::disk('public')->exists($administrasi->foto)) {
                Storage::disk('public')->delete($administrasi->foto);
            }
            
            $foto = $request->file('foto');
            $fotoName = time() . '_foto_' . $foto->getClientOriginalName();
            $validated['foto'] = $foto->storeAs('administrasi/foto', $fotoName, 'public');
        }

        // Handle multiple lampiran
        if ($request->hasFile('lampiran')) {
            $lampiranFiles = $administrasi->lampiran ?? [];
            foreach ($request->file('lampiran') as $lampiran) {
                $lampiranName = time() . '_' . uniqid() . '_' . $lampiran->getClientOriginalName();
                $path = $lampiran->storeAs('administrasi/lampiran', $lampiranName, 'public');
                $lampiranFiles[] = $path;
            }
            $validated['lampiran'] = $lampiranFiles;
        }

        $administrasi->update($validated);

        return redirect()->route('administrasi.show', $administrasi)
            ->with('success', 'Data administrasi berhasil diperbarui.');
    }

    public function destroy(Administrasi $administrasi)
    {
        // Hapus file dokumen
        if ($administrasi->file_dokumen && Storage::disk('public')->exists($administrasi->file_dokumen)) {
            Storage::disk('public')->delete($administrasi->file_dokumen);
        }

        // Hapus foto
        if ($administrasi->foto && Storage::disk('public')->exists($administrasi->foto)) {
            Storage::disk('public')->delete($administrasi->foto);
        }

        // Hapus lampiran
        if ($administrasi->lampiran) {
            foreach ($administrasi->lampiran as $lampiran) {
                if (Storage::disk('public')->exists($lampiran)) {
                    Storage::disk('public')->delete($lampiran);
                }
            }
        }

        $administrasi->delete();

        return redirect()->route('administrasi.index')
            ->with('success', 'Data administrasi berhasil dihapus.');
    }

    public function downloadDokumen(Administrasi $administrasi)
    {
        if (!$administrasi->file_dokumen || !Storage::disk('public')->exists($administrasi->file_dokumen)) {
            return back()->with('error', 'File dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($administrasi->file_dokumen);
    }

    public function exportPdf(Administrasi $administrasi)
    {
        $administrasi->load(['tindakLanjut', 'creator']);
        
        $pdf = Pdf::loadView('administrasi.pdf', compact('administrasi'));
        return $pdf->download('Administrasi_' . $administrasi->kode_administrasi . '.pdf');
    }

    public function exportAllPdf(Request $request)
    {
        $query = Administrasi::with(['creator', 'tindakLanjut.pic']);

        // Apply same filters as index
        if ($request->filled('jenis_administrasi')) {
            $query->where('jenis_administrasi', $request->jenis_administrasi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_surat', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_administrasi', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('penerima', 'like', "%{$search}%");
            });
        }

        $administrasi = $query->latest()->get();
        
        $data = [
            'administrasi' => $administrasi,
            'tanggal_cetak' => now()->format('d F Y H:i:s'),
        ];
        
        $pdf = Pdf::loadView('administrasi.export-all', $data)
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan_Administrasi_' . now()->format('Y-m-d_His') . '.pdf');
    }

    // Tindak Lanjut Methods
    public function showTindakLanjut(Administrasi $administrasi)
    {
        return view('administrasi.tindak-lanjut.create', compact('administrasi'));
    }

    public function storeTindakLanjut(Request $request, Administrasi $administrasi)
    {
        $validated = $request->validate([
            'jenis_tindak_lanjut' => 'required|in:pencairan_dana,disposisi,konfirmasi_terima,konfirmasi_kirim,rapat_pembahasan,penerbitan_sk,tandatangan,verifikasi,approval,revisi,arsip,lainnya',
            'judul_tindak_lanjut' => 'required|string|max:255',
            'deskripsi_tindak_lanjut' => 'nullable|string',
            'status_tindak_lanjut' => 'required|in:pending,proses,selesai,ditolak',
            'nominal_pencairan' => 'nullable|numeric',
            'metode_pencairan' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
            'nama_penerima_dana' => 'nullable|string',
            'tanggal_pencairan' => 'nullable|date',
            'bukti_pencairan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tandatangan_pencairan' => 'nullable|string', // base64 signature
            'signature_ttd' => 'nullable|string', // base64 signature for tandatangan
            'disposisi_dari' => 'nullable|string',
            'disposisi_kepada' => 'nullable|string',
            'instruksi_disposisi' => 'nullable|string',
            'deadline_disposisi' => 'nullable|date',
            'nama_penerima_paket' => 'nullable|string',
            'foto_paket' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'waktu_terima_paket' => 'nullable|date',
            'kondisi_paket' => 'nullable|string',
            'resi_pengiriman' => 'nullable|string',
            'waktu_rapat' => 'nullable|date',
            'tempat_rapat' => 'nullable|string',
            'peserta_rapat' => 'nullable|array',
            'hasil_rapat' => 'nullable|string',
            'notulen_rapat' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'nama_penandatangan' => 'nullable|string',
            'jabatan_penandatangan' => 'nullable|string',
            'tanggal_tandatangan' => 'nullable|date',
            'file_dokumen_ttd' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'verifikator' => 'nullable|string',
            'tanggal_verifikasi' => 'nullable|date',
            'hasil_verifikasi' => 'nullable|in:disetujui,ditolak,revisi',
            'catatan_verifikasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'pic_id' => 'nullable|exists:users,id',
        ]);

        $validated['administrasi_id'] = $administrasi->id;
        $validated['kode_tindak_lanjut'] = TindakLanjutAdministrasi::generateKodeTindakLanjut();
        $validated['created_by'] = Auth::id();

        // Upload bukti pencairan
        if ($request->hasFile('bukti_pencairan')) {
            $file = $request->file('bukti_pencairan');
            $fileName = time() . '_bukti_' . $file->getClientOriginalName();
            $validated['bukti_pencairan'] = $file->storeAs('administrasi/tindak-lanjut/bukti', $fileName, 'public');
        }

        // Handle digital signature for pencairan dana
        if ($request->filled('tandatangan_pencairan')) {
            $signatureData = $request->tandatangan_pencairan;
            
            // Check if it's base64 signature
            if (strpos($signatureData, 'data:image') === 0) {
                // Decode base64 image
                $image = str_replace('data:image/png;base64,', '', $signatureData);
                $image = str_replace(' ', '+', $image);
                $imageName = 'ttd_pencairan_' . time() . '.png';
                
                // Save to storage
                $path = 'administrasi/tindak-lanjut/ttd/' . $imageName;
                Storage::disk('public')->put($path, base64_decode($image));
                
                $validated['tandatangan_pencairan'] = $path;
            }
        }

        // Handle digital signature for tandatangan
        if ($request->filled('signature_ttd')) {
            $signatureData = $request->signature_ttd;
            
            // Check if it's base64 signature
            if (strpos($signatureData, 'data:image') === 0) {
                // Decode base64 image
                $image = str_replace('data:image/png;base64,', '', $signatureData);
                $image = str_replace(' ', '+', $image);
                $imageName = 'ttd_dokumen_' . time() . '.png';
                
                // Save to storage
                $path = 'administrasi/tindak-lanjut/signature/' . $imageName;
                Storage::disk('public')->put($path, base64_decode($image));
                
                $validated['signature_ttd'] = $path;
            }
        }

        // Upload foto paket
        if ($request->hasFile('foto_paket')) {
            $file = $request->file('foto_paket');
            $fileName = time() . '_paket_' . $file->getClientOriginalName();
            $validated['foto_paket'] = $file->storeAs('administrasi/tindak-lanjut/paket', $fileName, 'public');
        }

        // Upload notulen rapat
        if ($request->hasFile('notulen_rapat')) {
            $file = $request->file('notulen_rapat');
            $fileName = time() . '_notulen_' . $file->getClientOriginalName();
            $validated['notulen_rapat'] = $file->storeAs('administrasi/tindak-lanjut/notulen', $fileName, 'public');
        }

        // Upload dokumen TTD
        if ($request->hasFile('file_dokumen_ttd')) {
            $file = $request->file('file_dokumen_ttd');
            $fileName = time() . '_dokumen_ttd_' . $file->getClientOriginalName();
            $validated['file_dokumen_ttd'] = $file->storeAs('administrasi/tindak-lanjut/dokumen', $fileName, 'public');
        }

        $tindakLanjut = TindakLanjutAdministrasi::create($validated);

        // Update status administrasi
        if ($tindakLanjut->status_tindak_lanjut == 'selesai') {
            $administrasi->update(['status' => 'selesai']);
        } elseif ($administrasi->status == 'pending') {
            $administrasi->update(['status' => 'proses']);
        }

        return redirect()->route('administrasi.show', $administrasi)
            ->with('success', 'Tindak lanjut berhasil ditambahkan dengan kode: ' . $tindakLanjut->kode_tindak_lanjut);
    }

    public function editTindakLanjut(Administrasi $administrasi, TindakLanjutAdministrasi $tindakLanjut)
    {
        return view('administrasi.tindak-lanjut.edit', compact('administrasi', 'tindakLanjut'));
    }

    public function updateTindakLanjut(Request $request, Administrasi $administrasi, TindakLanjutAdministrasi $tindakLanjut)
    {
        $validated = $request->validate([
            'jenis_tindak_lanjut' => 'required|in:pencairan_dana,disposisi,konfirmasi_terima,konfirmasi_kirim,rapat_pembahasan,penerbitan_sk,tandatangan,verifikasi,approval,revisi,arsip,lainnya',
            'judul_tindak_lanjut' => 'required|string|max:255',
            'deskripsi_tindak_lanjut' => 'nullable|string',
            'status_tindak_lanjut' => 'required|in:pending,proses,selesai,ditolak',
            'nominal_pencairan' => 'nullable|numeric',
            'metode_pencairan' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
            'nama_penerima_dana' => 'nullable|string',
            'tanggal_pencairan' => 'nullable|date',
            'bukti_pencairan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'tandatangan_pencairan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $validated['updated_by'] = Auth::id();

        // Upload file jika ada
        if ($request->hasFile('bukti_pencairan')) {
            if ($tindakLanjut->bukti_pencairan && Storage::disk('public')->exists($tindakLanjut->bukti_pencairan)) {
                Storage::disk('public')->delete($tindakLanjut->bukti_pencairan);
            }
            $file = $request->file('bukti_pencairan');
            $fileName = time() . '_bukti_' . $file->getClientOriginalName();
            $validated['bukti_pencairan'] = $file->storeAs('administrasi/tindak-lanjut/bukti', $fileName, 'public');
        }

        if ($request->hasFile('tandatangan_pencairan')) {
            if ($tindakLanjut->tandatangan_pencairan && Storage::disk('public')->exists($tindakLanjut->tandatangan_pencairan)) {
                Storage::disk('public')->delete($tindakLanjut->tandatangan_pencairan);
            }
            $file = $request->file('tandatangan_pencairan');
            $fileName = time() . '_ttd_' . $file->getClientOriginalName();
            $validated['tandatangan_pencairan'] = $file->storeAs('administrasi/tindak-lanjut/ttd', $fileName, 'public');
        }

        $tindakLanjut->update($validated);

        // Update status administrasi
        if ($tindakLanjut->status_tindak_lanjut == 'selesai') {
            $administrasi->update(['status' => 'selesai']);
        }

        return redirect()->route('administrasi.show', $administrasi)
            ->with('success', 'Tindak lanjut berhasil diperbarui.');
    }

    public function destroyTindakLanjut(Administrasi $administrasi, TindakLanjutAdministrasi $tindakLanjut)
    {
        // Hapus file terkait
        $files = [
            $tindakLanjut->bukti_pencairan,
            $tindakLanjut->tandatangan_pencairan,
            $tindakLanjut->foto_paket,
            $tindakLanjut->notulen_rapat,
            $tindakLanjut->file_dokumen_ttd,
        ];

        foreach ($files as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        $tindakLanjut->delete();

        return redirect()->route('administrasi.show', $administrasi)
            ->with('success', 'Tindak lanjut berhasil dihapus.');
    }

    // ============= KARYAWAN METHODS (READ-ONLY) =============
    
    public function indexKaryawan(Request $request)
    {
        $query = Administrasi::with(['creator', 'tindakLanjut']);

        if ($request->filled('jenis_administrasi')) {
            $query->where('jenis_administrasi', $request->jenis_administrasi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_surat', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_administrasi', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('penerima', 'like', "%{$search}%");
            });
        }

        $administrasi = $query->latest()->paginate(15);
        $cabangs = Cabang::all();

        $jenisAdministrasi = [
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'undangan_masuk' => 'Undangan Masuk',
            'undangan_keluar' => 'Undangan Keluar',
            'proposal_masuk' => 'Proposal Masuk',
            'proposal_keluar' => 'Proposal Keluar',
            'paket_masuk' => 'Paket Masuk',
            'paket_keluar' => 'Paket Keluar',
            'memo_internal' => 'Memo Internal',
            'sk_internal' => 'SK Internal',
            'surat_tugas' => 'Surat Tugas',
            'surat_keputusan' => 'Surat Keputusan',
            'nota_dinas' => 'Nota Dinas',
            'berita_acara' => 'Berita Acara',
            'kontrak' => 'Kontrak',
            'mou' => 'MoU',
            'dokumen_lainnya' => 'Dokumen Lainnya',
        ];

        return view('administrasi.index-karyawan', compact('administrasi', 'cabangs', 'jenisAdministrasi'));
    }

    public function showKaryawan($id)
    {
        $administrasi = Administrasi::with(['creator', 'tindakLanjut.user', 'cabang'])->findOrFail($id);
        
        return view('administrasi.show-karyawan', compact('administrasi'));
    }

    public function createKaryawan()
    {
        $cabangs = Cabang::all();
        
        $jenisAdministrasi = [
            'surat_masuk' => 'Surat Masuk',
            'surat_keluar' => 'Surat Keluar',
            'undangan_masuk' => 'Undangan Masuk',
            'undangan_keluar' => 'Undangan Keluar',
            'proposal_masuk' => 'Proposal Masuk',
            'proposal_keluar' => 'Proposal Keluar',
            'paket_masuk' => 'Paket Masuk',
            'paket_keluar' => 'Paket Keluar',
            'memo_internal' => 'Memo Internal',
            'sk_internal' => 'SK Internal',
            'surat_tugas' => 'Surat Tugas',
            'surat_keputusan' => 'Surat Keputusan',
            'nota_dinas' => 'Nota Dinas',
            'berita_acara' => 'Berita Acara',
            'kontrak' => 'Kontrak',
            'mou' => 'MoU',
            'dokumen_lainnya' => 'Dokumen Lainnya',
        ];

        return view('administrasi.create-karyawan', compact('cabangs', 'jenisAdministrasi'));
    }

    public function storeKaryawan(Request $request)
    {
        $validated = $request->validate([
            'jenis_administrasi' => 'required|in:surat_masuk,surat_keluar,undangan_masuk,undangan_keluar,proposal_masuk,proposal_keluar,paket_masuk,paket_keluar,memo_internal,sk_internal,surat_tugas,surat_keputusan,nota_dinas,berita_acara,kontrak,mou,dokumen_lainnya',
            'nomor_surat' => 'nullable|string|max:255',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'required|string|max:500',
            // Field undangan
            'nama_acara' => 'nullable|string|max:255',
            'tanggal_acara_mulai' => 'nullable|date',
            'tanggal_acara_selesai' => 'nullable|date|after_or_equal:tanggal_acara_mulai',
            'waktu_acara_mulai' => 'nullable|date_format:H:i',
            'waktu_acara_selesai' => 'nullable|date_format:H:i',
            'lokasi_acara' => 'nullable|string|max:255',
            'alamat_acara' => 'nullable|string',
            'dress_code' => 'nullable|string|max:255',
            'catatan_acara' => 'nullable|string',
            // Field lainnya
            'ringkasan' => 'nullable|string',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'status' => 'required|in:pending,proses,selesai,ditolak,expired',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'disposisi_ke' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Validasi khusus undangan
        if (in_array($request->jenis_administrasi, ['undangan_masuk', 'undangan_keluar'])) {
            $request->validate([
                'nama_acara' => 'required|string|max:255',
                'tanggal_acara_mulai' => 'required|date',
                'lokasi_acara' => 'required|string|max:255',
            ], [
                'nama_acara.required' => 'Nama acara wajib diisi untuk undangan',
                'tanggal_acara_mulai.required' => 'Tanggal mulai acara wajib diisi untuk undangan',
                'lokasi_acara.required' => 'Lokasi acara wajib diisi untuk undangan',
            ]);
        }

        // Generate kode administrasi
        $validated['kode_administrasi'] = Administrasi::generateKodeAdministrasi();
        $validated['created_by'] = Auth::id();

        // Upload file dokumen
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_dokumen'] = $file->storeAs('administrasi/dokumen', $fileName, 'public');
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_foto_' . $foto->getClientOriginalName();
            $validated['foto'] = $foto->storeAs('administrasi/foto', $fotoName, 'public');
        }

        $administrasi = Administrasi::create($validated);

        return redirect()->route('administrasi.karyawan.index')
            ->with('success', 'Data administrasi berhasil ditambahkan dengan kode: ' . $administrasi->kode_administrasi);
    }

    public function createTindakLanjutKaryawan($id)
    {
        $administrasi = Administrasi::with(['creator', 'tindakLanjut'])->findOrFail($id);
        
        return view('administrasi.tindak-lanjut.create-karyawan', compact('administrasi'));
    }

    public function storeTindakLanjutKaryawan(Request $request, $id)
    {
        $administrasi = Administrasi::findOrFail($id);

        $validated = $request->validate([
            'jenis_tindak_lanjut' => 'required|in:pencairan_dana,disposisi,konfirmasi_terima,konfirmasi_kirim,rapat_pembahasan,penerbitan_sk,tandatangan,verifikasi,approval,revisi,arsip,lainnya',
            'judul_tindak_lanjut' => 'required|string|max:255',
            'deskripsi_tindak_lanjut' => 'nullable|string',
            'status_tindak_lanjut' => 'required|in:pending,proses,selesai,ditolak',
            'nominal_pencairan' => 'nullable|numeric',
            'metode_pencairan' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
            'nama_penerima_dana' => 'nullable|string',
            'tanggal_pencairan' => 'nullable|date',
            'bukti_pencairan' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'disposisi_dari' => 'nullable|string',
            'disposisi_kepada' => 'nullable|string',
            'instruksi_disposisi' => 'nullable|string',
            'deadline_disposisi' => 'nullable|date',
            'nama_penerima_paket' => 'nullable|string',
            'foto_paket' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kondisi_paket' => 'nullable|string',
            'resi_pengiriman' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $validated['administrasi_id'] = $administrasi->id;
        $validated['kode_tindak_lanjut'] = TindakLanjutAdministrasi::generateKodeTindakLanjut();
        $validated['created_by'] = Auth::id();

        // Upload bukti pencairan
        if ($request->hasFile('bukti_pencairan')) {
            $file = $request->file('bukti_pencairan');
            $fileName = time() . '_bukti_' . $file->getClientOriginalName();
            $validated['bukti_pencairan'] = $file->storeAs('administrasi/tindak-lanjut/bukti', $fileName, 'public');
        }

        // Upload foto paket
        if ($request->hasFile('foto_paket')) {
            $file = $request->file('foto_paket');
            $fileName = time() . '_paket_' . $file->getClientOriginalName();
            $validated['foto_paket'] = $file->storeAs('administrasi/tindak-lanjut/paket', $fileName, 'public');
        }

        TindakLanjutAdministrasi::create($validated);

        return redirect()->route('administrasi.karyawan.show', $administrasi->id)
            ->with('success', 'Tindak lanjut berhasil ditambahkan!');
    }
}
