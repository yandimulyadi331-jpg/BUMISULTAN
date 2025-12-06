<?php

namespace App\Http\Controllers;

use App\Models\InventarisEvent;
use App\Models\Inventaris;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisEventController extends Controller
{
    public function index(Request $request)
    {
        $query = InventarisEvent::with(['pic', 'eventItems.inventaris']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_event')) {
            $query->where('jenis_event', $request->jenis_event);
        }

        $events = $query->latest()->paginate(15);

        return view('inventaris-event.index', compact('events'));
    }

    public function create()
    {
        $users = User::all();
        $jenisEvents = ['Outing', 'Training', 'Gathering', 'Naik Gunung', 'Camping', 'Workshop', 'Seminar', 'Olahraga', 'Lainnya'];

        return view('inventaris-event.create', compact('users', 'jenisEvents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'deskripsi_event' => 'nullable|string',
            'jenis_event' => 'required|string',
            'tanggal_event' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_event',
            'lokasi_event' => 'nullable|string',
            'pic_id' => 'required|exists:users,id',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'persiapan';
        $validated['created_by'] = auth()->id();

        $event = InventarisEvent::create($validated);

        return redirect()->route('inventaris-event.show', $event)
            ->with('success', 'Event berhasil dibuat dengan kode: ' . $event->kode_event);
    }

    public function show(InventarisEvent $inventarisEvent)
    {
        $inventarisEvent->load(['pic', 'eventItems.inventaris', 'peminjaman.karyawan']);

        return view('inventaris-event.show', compact('inventarisEvent'));
    }

    public function edit(InventarisEvent $inventarisEvent)
    {
        if ($inventarisEvent->status === 'selesai') {
            return redirect()->back()->with('error', 'Event yang sudah selesai tidak dapat diedit');
        }

        $users = User::all();
        $jenisEvents = ['Outing', 'Training', 'Gathering', 'Naik Gunung', 'Camping', 'Workshop', 'Seminar', 'Olahraga', 'Lainnya'];

        return view('inventaris-event.edit', compact('inventarisEvent', 'users', 'jenisEvents'));
    }

    public function update(Request $request, InventarisEvent $inventarisEvent)
    {
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'deskripsi_event' => 'nullable|string',
            'jenis_event' => 'required|string',
            'tanggal_event' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_event',
            'lokasi_event' => 'nullable|string',
            'pic_id' => 'required|exists:users,id',
            'status' => 'required|in:persiapan,berlangsung,selesai,dibatalkan',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $inventarisEvent->update($validated);

        return redirect()->route('inventaris-event.show', $inventarisEvent)
            ->with('success', 'Event berhasil diupdate');
    }

    public function destroy(InventarisEvent $inventarisEvent)
    {
        if ($inventarisEvent->status === 'berlangsung') {
            return redirect()->back()->with('error', 'Event yang sedang berlangsung tidak dapat dihapus');
        }

        $kode = $inventarisEvent->kode_event;
        $inventarisEvent->delete();

        return redirect()->route('inventaris-event.index')
            ->with('success', 'Event ' . $kode . ' berhasil dihapus');
    }

    // Tambah inventaris ke event
    public function addInventaris(Request $request, InventarisEvent $inventarisEvent)
    {
        $validated = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah_dibutuhkan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        // Check if already added
        $exists = $inventarisEvent->eventItems()
            ->where('inventaris_id', $validated['inventaris_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Inventaris sudah ditambahkan ke event ini');
        }

        $inventarisEvent->addInventaris(
            $validated['inventaris_id'],
            $validated['jumlah_dibutuhkan'],
            $validated['keterangan'] ?? null
        );

        return redirect()->back()->with('success', 'Inventaris berhasil ditambahkan ke event');
    }

    // Remove inventaris dari event
    public function removeInventaris(InventarisEvent $inventarisEvent, $itemId)
    {
        $item = $inventarisEvent->eventItems()->findOrFail($itemId);
        
        if ($item->status === 'terdistribusi') {
            return redirect()->back()->with('error', 'Inventaris yang sudah terdistribusi tidak dapat dihapus');
        }

        $item->delete();

        return redirect()->back()->with('success', 'Inventaris berhasil dihapus dari event');
    }

    // Cek ketersediaan inventaris
    public function cekKetersediaan(InventarisEvent $inventarisEvent)
    {
        $results = $inventarisEvent->cekKetersediaanInventaris();

        return redirect()->back()
            ->with('success', 'Ketersediaan inventaris berhasil dicek')
            ->with('ketersediaan_results', $results);
    }

    // Distribusi inventaris ke karyawan
    public function distribusi(Request $request, InventarisEvent $inventarisEvent)
    {
        $validated = $request->validate([
            'karyawan_ids' => 'required|array',
            'karyawan_ids.*' => 'exists:karyawans,id',
        ]);

        // Check ketersediaan dulu
        $inventarisEvent->cekKetersediaanInventaris();

        // Check if all inventaris ready
        $notReady = $inventarisEvent->eventItems()->where('status', '!=', 'tersedia')->count();
        if ($notReady > 0) {
            return redirect()->back()->with('error', 'Masih ada inventaris yang belum tersedia');
        }

        $distributed = $inventarisEvent->distribusiKeKaryawan($validated['karyawan_ids']);

        return redirect()->back()
            ->with('success', count($distributed) . ' peminjaman berhasil dibuat untuk event ini');
    }

    // Get inventaris untuk ditambahkan
    public function getInventarisForEvent(InventarisEvent $inventarisEvent)
    {
        $inventaris = Inventaris::where('status', 'tersedia')
            ->whereNotIn('id', $inventarisEvent->eventItems()->pluck('inventaris_id'))
            ->get();

        return view('inventaris-event.add-inventaris', compact('inventarisEvent', 'inventaris'));
    }

    // Get karyawan untuk distribusi
    public function getKaryawanForDistribusi(InventarisEvent $inventarisEvent)
    {
        $karyawans = Karyawan::all();

        return view('inventaris-event.distribusi-karyawan', compact('inventarisEvent', 'karyawans'));
    }

    // Export PDF
    public function exportPdf(InventarisEvent $inventarisEvent)
    {
        $inventarisEvent->load(['pic', 'eventItems.inventaris', 'peminjaman.karyawan']);

        $pdf = Pdf::loadView('inventaris-event.pdf', compact('inventarisEvent'));
        return $pdf->download('event-' . $inventarisEvent->kode_event . '-' . date('Y-m-d') . '.pdf');
    }
}
