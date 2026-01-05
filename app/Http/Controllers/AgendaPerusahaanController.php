<?php

namespace App\Http\Controllers;

use App\Models\AgendaPerusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgendaPerusahaanController extends Controller
{
    public function index(Request $request)
    {
        $query = AgendaPerusahaan::with(['pembuat', 'reminderLogs'])->orderBy('tanggal_mulai', 'desc')->orderBy('waktu_mulai', 'desc');

        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe_agenda', $request->tipe);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('prioritas') && $request->prioritas != '') {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $agenda = $query->paginate(20);

        $stats = [
            'hari_ini' => AgendaPerusahaan::hariIni()->count(),
            'minggu_ini' => AgendaPerusahaan::mingguIni()->count(),
            'total_terjadwal' => AgendaPerusahaan::where('status', 'terjadwal')->count(),
            'total_urgent' => AgendaPerusahaan::where('prioritas', 'urgent')->whereNotIn('status', ['selesai', 'dibatalkan'])->count(),
        ];

        return view('agenda.index', compact('agenda', 'stats'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        
        return view('agenda.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe_agenda' => 'required|in:undangan,rapat,kunjungan,event,deadline,lainnya',
            'kategori_agenda' => 'required|in:internal,eksternal,pemerintah,vendor,client,umum',
            'tanggal_mulai' => 'required|date',
            'waktu_mulai' => 'required',
            'lokasi' => 'nullable|string|max:255',
            'dress_code' => 'required|in:formal,semi_formal,casual,bebas_rapi,batik,khusus',
            'status' => 'required|in:draft,terjadwal',
            'prioritas' => 'required|in:rendah,sedang,tinggi,urgent',
        ]);

        try {
            DB::beginTransaction();

            $validated['nomor_agenda'] = AgendaPerusahaan::generateNomorAgenda();
            $validated['dibuat_oleh'] = auth()->id();
            $validated['is_online'] = $request->has('is_online');
            $validated['ada_anggaran'] = $request->has('ada_anggaran');
            $validated['is_wajib_hadir'] = $request->has('is_wajib_hadir');
            $validated['reminder_aktif'] = $request->has('reminder_aktif') ? true : false;
            $validated['reminder_1_hari'] = $request->has('reminder_1_hari');
            $validated['reminder_3_jam'] = $request->has('reminder_3_jam');
            $validated['reminder_30_menit'] = $request->has('reminder_30_menit');
            $validated['deskripsi'] = $request->deskripsi;
            $validated['tanggal_selesai'] = $request->tanggal_selesai;
            $validated['waktu_selesai'] = $request->waktu_selesai;
            $validated['durasi_menit'] = $request->durasi_menit;
            $validated['lokasi_detail'] = $request->lokasi_detail;
            $validated['link_meeting'] = $request->link_meeting;
            $validated['penyelenggara'] = $request->penyelenggara;
            $validated['contact_person'] = $request->contact_person;
            $validated['no_telp_cp'] = $request->no_telp_cp;
            $validated['email_cp'] = $request->email_cp;
            $validated['dress_code_keterangan'] = $request->dress_code_keterangan;
            $validated['perlengkapan_dibawa'] = $request->perlengkapan_dibawa;
            $validated['peserta_internal'] = $request->peserta_internal;
            $validated['peserta_eksternal'] = $request->peserta_eksternal;
            $validated['jumlah_peserta_estimasi'] = $request->jumlah_peserta_estimasi;
            $validated['nominal_anggaran'] = $request->nominal_anggaran;
            $validated['sumber_anggaran'] = $request->sumber_anggaran;
            $validated['reminder_custom_menit'] = $request->reminder_custom_menit;

            if ($request->hasFile('dokumen_undangan')) {
                $validated['dokumen_undangan'] = $request->file('dokumen_undangan')->store('agenda/undangan', 'public');
            }

            if ($request->hasFile('dokumen_rundown')) {
                $validated['dokumen_rundown'] = $request->file('dokumen_rundown')->store('agenda/rundown', 'public');
            }

            if ($request->hasFile('dokumen_materi')) {
                $validated['dokumen_materi'] = $request->file('dokumen_materi')->store('agenda/materi', 'public');
            }

            $agenda = AgendaPerusahaan::create($validated);
            $agenda->logHistory('created', null, 'Agenda baru dibuat');

            DB::commit();

            return redirect()->route('agenda.show', $agenda->id)
                           ->with('success', 'Agenda berhasil dibuat dengan nomor: ' . $agenda->nomor_agenda);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat agenda: ' . $e->getMessage());
        }
    }

    public function show(AgendaPerusahaan $agenda)
    {
        $agenda->load(['pembuat', 'pengupdate', 'reminderLogs', 'history.user']);
        
        $pesertaInternal = [];
        if (!empty($agenda->peserta_internal)) {
            $pesertaInternal = User::whereIn('id', $agenda->peserta_internal)->get();
        }

        return view('agenda.show', compact('agenda', 'pesertaInternal'));
    }

    public function edit(AgendaPerusahaan $agenda)
    {
        $users = User::orderBy('name')->get();
        
        $pesertaInternal = [];
        if (!empty($agenda->peserta_internal)) {
            $pesertaInternal = User::whereIn('id', $agenda->peserta_internal)->get();
        }
        
        return view('agenda.edit', compact('agenda', 'users', 'pesertaInternal'));
    }

    public function update(Request $request, AgendaPerusahaan $agenda)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe_agenda' => 'required',
            'kategori_agenda' => 'required',
            'tanggal_mulai' => 'required|date',
            'waktu_mulai' => 'required',
            'dress_code' => 'required',
            'status' => 'required',
            'prioritas' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $oldData = $agenda->toArray();
            
            $validated['diupdate_oleh'] = auth()->id();
            $validated['is_online'] = $request->has('is_online');
            $validated['ada_anggaran'] = $request->has('ada_anggaran');
            $validated['is_wajib_hadir'] = $request->has('is_wajib_hadir');
            $validated['reminder_aktif'] = $request->has('reminder_aktif');
            $validated['reminder_1_hari'] = $request->has('reminder_1_hari');
            $validated['reminder_3_jam'] = $request->has('reminder_3_jam');
            $validated['reminder_30_menit'] = $request->has('reminder_30_menit');
            
            // Add all other fields
            $validated['deskripsi'] = $request->deskripsi;
            $validated['tanggal_selesai'] = $request->tanggal_selesai;
            $validated['waktu_selesai'] = $request->waktu_selesai;
            $validated['durasi_menit'] = $request->durasi_menit;
            $validated['lokasi'] = $request->lokasi;
            $validated['lokasi_detail'] = $request->lokasi_detail;
            $validated['link_meeting'] = $request->link_meeting;
            $validated['penyelenggara'] = $request->penyelenggara;
            $validated['contact_person'] = $request->contact_person;
            $validated['no_telp_cp'] = $request->no_telp_cp;
            $validated['email_cp'] = $request->email_cp;
            $validated['dress_code_keterangan'] = $request->dress_code_keterangan;
            $validated['perlengkapan_dibawa'] = $request->perlengkapan_dibawa;
            $validated['peserta_internal'] = $request->peserta_internal;
            $validated['peserta_eksternal'] = $request->peserta_eksternal;
            $validated['jumlah_peserta_estimasi'] = $request->jumlah_peserta_estimasi;
            $validated['nominal_anggaran'] = $request->nominal_anggaran;
            $validated['sumber_anggaran'] = $request->sumber_anggaran;
            $validated['reminder_custom_menit'] = $request->reminder_custom_menit;

            if ($request->hasFile('dokumen_undangan')) {
                if ($agenda->dokumen_undangan) {
                    Storage::disk('public')->delete($agenda->dokumen_undangan);
                }
                $validated['dokumen_undangan'] = $request->file('dokumen_undangan')->store('agenda/undangan', 'public');
            }

            $agenda->update($validated);
            $agenda->logHistory('updated', null, 'Agenda diupdate');

            DB::commit();

            return redirect()->route('agenda.show', $agenda->id)
                           ->with('success', 'Agenda berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengupdate agenda: ' . $e->getMessage());
        }
    }

    public function destroy(AgendaPerusahaan $agenda)
    {
        try {
            $agenda->logHistory('deleted', null, 'Agenda dihapus');
            
            if ($agenda->dokumen_undangan) {
                Storage::disk('public')->delete($agenda->dokumen_undangan);
            }
            
            $agenda->delete();

            return redirect()->route('agenda.index')
                           ->with('success', 'Agenda berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus agenda: ' . $e->getMessage());
        }
    }

    public function konfirmasiKehadiran(Request $request, AgendaPerusahaan $agenda)
    {
        $validated = $request->validate([
            'kehadiran_konfirmasi' => 'required|in:hadir,tidak_hadir,diwakilkan',
            'nama_perwakilan' => 'required_if:kehadiran_konfirmasi,diwakilkan',
            'catatan_kehadiran' => 'nullable|string',
        ]);

        $agenda->update($validated);
        $agenda->logHistory('konfirmasi_kehadiran', ['status' => $validated['kehadiran_konfirmasi']], 'Konfirmasi kehadiran');

        return back()->with('success', 'Konfirmasi kehadiran berhasil disimpan');
    }

    public function inputHasil(Request $request, AgendaPerusahaan $agenda)
    {
        $validated = $request->validate([
            'hasil_agenda' => 'required|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        if ($request->hasFile('foto_dokumentasi')) {
            $fotoDokumentasi = [];
            foreach ($request->file('foto_dokumentasi') as $file) {
                $fotoDokumentasi[] = $file->store('agenda/dokumentasi', 'public');
            }
            $validated['foto_dokumentasi'] = $fotoDokumentasi;
        }

        $validated['status'] = 'selesai';
        $agenda->update($validated);
        $agenda->logHistory('input_hasil', null, 'Hasil agenda diinput');

        return back()->with('success', 'Hasil agenda berhasil disimpan');
    }

    public function batalkan(Request $request, AgendaPerusahaan $agenda)
    {
        $validated = $request->validate([
            'alasan_dibatalkan' => 'required|string',
        ]);

        $agenda->update([
            'status' => 'dibatalkan',
            'dibatalkan_oleh' => auth()->id(),
            'tanggal_dibatalkan' => now(),
            'alasan_dibatalkan' => $validated['alasan_dibatalkan'],
        ]);

        $agenda->logHistory('dibatalkan', null, 'Agenda dibatalkan: ' . $validated['alasan_dibatalkan']);

        return redirect()->route('agenda.show', $agenda->id)
                       ->with('success', 'Agenda berhasil dibatalkan');
    }

    public function kalender(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('m'));

        $agendaBulanIni = AgendaPerusahaan::whereYear('tanggal_mulai', $tahun)
                                         ->whereMonth('tanggal_mulai', $bulan)
                                         ->whereNotIn('status', ['dibatalkan'])
                                         ->orderBy('tanggal_mulai')
                                         ->orderBy('waktu_mulai')
                                         ->get();

        return view('agenda.kalender', compact('agendaBulanIni', 'tahun', 'bulan'));
    }
}
