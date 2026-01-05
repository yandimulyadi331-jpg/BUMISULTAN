@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-eye"></i> Detail Agenda</h2>
        <div>
            <a href="{{ route('agenda.edit', $agenda) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
            <form action="{{ route('agenda.destroy', $agenda) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus agenda {{ $agenda->nomor_agenda }}? Data tidak dapat dikembalikan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
            </form>
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Info Utama -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ $agenda->judul }}</h5>
                    <div class="d-flex gap-2 mt-2">
                        <span class="badge bg-{{ $agenda->status_badge }}">{{ ucfirst($agenda->status) }}</span>
                        <span class="badge bg-{{ $agenda->prioritas_badge }}">{{ ucfirst($agenda->prioritas) }}</span>
                        @if($agenda->is_wajib_hadir)
                            <span class="badge bg-danger">Wajib Hadir</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p><strong>Nomor Agenda:</strong> {{ $agenda->nomor_agenda }}</p>
                    <p><strong>Tipe:</strong> {{ ucfirst($agenda->tipe_agenda) }}</p>
                    <p><strong>Kategori:</strong> {{ ucfirst($agenda->kategori_agenda) }}</p>
                    @if($agenda->deskripsi)
                        <p><strong>Deskripsi:</strong><br>{{ $agenda->deskripsi }}</p>
                    @endif

                    <hr>

                    <h6><i class="bi bi-calendar"></i> Waktu</h6>
                    <p>
                        <strong>Tanggal:</strong> {{ $agenda->tanggal_mulai->format('d F Y') }}<br>
                        <strong>Waktu:</strong> {{ substr($agenda->waktu_mulai, 0, 5) }} WIB
                        @if($agenda->waktu_selesai)
                            - {{ substr($agenda->waktu_selesai, 0, 5) }} WIB
                        @endif
                    </p>

                    <hr>

                    <h6><i class="bi bi-geo-alt"></i> Lokasi</h6>
                    @if($agenda->is_online)
                        <p>
                            <span class="badge bg-info">Online</span><br>
                            @if($agenda->link_meeting)
                                <a href="{{ $agenda->link_meeting }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-camera-video"></i> Join Meeting
                                </a>
                            @endif
                        </p>
                    @else
                        <p>{{ $agenda->lokasi }}</p>
                        @if($agenda->lokasi_detail)
                            <small class="text-muted">{{ $agenda->lokasi_detail }}</small>
                        @endif
                    @endif

                    <hr>

                    <h6>{{ $agenda->dress_code_icon }} Dress Code</h6>
                    <p>
                        <strong>{{ ucfirst(str_replace('_', ' ', $agenda->dress_code)) }}</strong>
                        @if($agenda->dress_code_keterangan)
                            <br><small>{{ $agenda->dress_code_keterangan }}</small>
                        @endif
                    </p>

                    @if($agenda->perlengkapan_dibawa)
                        <hr>
                        <h6><i class="bi bi-bag"></i> Perlengkapan yang Dibawa</h6>
                        <p>{{ $agenda->perlengkapan_dibawa }}</p>
                    @endif

                    @if($agenda->penyelenggara)
                        <hr>
                        <h6><i class="bi bi-building"></i> Penyelenggara</h6>
                        <p>{{ $agenda->penyelenggara }}</p>
                        @if($agenda->contact_person)
                            <p><strong>Contact Person:</strong> {{ $agenda->contact_person }}
                            @if($agenda->no_telp_cp)
                                ({{ $agenda->no_telp_cp }})
                            @endif
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Hasil Agenda -->
            @if($agenda->status == 'selesai')
                <div class="card mb-3">
                    <div class="card-header"><h5>Hasil Agenda</h5></div>
                    <div class="card-body">
                        <p><strong>Kehadiran:</strong> <span class="badge bg-success">{{ ucfirst($agenda->kehadiran_konfirmasi) }}</span></p>
                        @if($agenda->hasil_agenda)
                            <p><strong>Hasil:</strong><br>{{ $agenda->hasil_agenda }}</p>
                        @endif
                        @if($agenda->tindak_lanjut)
                            <p><strong>Tindak Lanjut:</strong><br>{{ $agenda->tindak_lanjut }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- History -->
            <div class="card mb-3">
                <div class="card-header"><h5>Riwayat</h5></div>
                <div class="card-body">
                    @foreach($agenda->history as $h)
                        <div class="mb-2">
                            <small class="text-muted">{{ $h->created_at->format('d M Y H:i') }}</small>
                            <p class="mb-0"><strong>{{ $h->user_name }}</strong> - {{ $h->aksi }}</p>
                            @if($h->catatan)
                                <small>{{ $h->catatan }}</small>
                            @endif
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Reminder Status -->
            <div class="card mb-3">
                <div class="card-header"><h6>Status Reminder</h6></div>
                <div class="card-body">
                    @if($agenda->reminder_aktif)
                        <p class="text-success"><i class="bi bi-check-circle"></i> Reminder Aktif</p>
                        @if($agenda->reminder_1_hari)
                            <small>✓ 1 Hari Sebelum</small><br>
                        @endif
                        @if($agenda->reminder_3_jam)
                            <small>✓ 3 Jam Sebelum</small><br>
                        @endif
                        @if($agenda->reminder_30_menit)
                            <small>✓ 30 Menit Sebelum</small><br>
                        @endif
                    @else
                        <p class="text-muted">Reminder Tidak Aktif</p>
                    @endif

                    @if($agenda->reminderLogs->count() > 0)
                        <hr>
                        <p><strong>Log Pengiriman:</strong></p>
                        @foreach($agenda->reminderLogs->take(5) as $log)
                            <small>{{ $log->created_at->format('d M H:i') }} - {{ $log->status }}</small><br>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Dokumen -->
            @if($agenda->dokumen_undangan || $agenda->dokumen_rundown || $agenda->dokumen_materi)
                <div class="card mb-3">
                    <div class="card-header"><h6>Dokumen</h6></div>
                    <div class="card-body">
                        @if($agenda->dokumen_undangan)
                            <a href="{{ Storage::url($agenda->dokumen_undangan) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                                <i class="bi bi-file-pdf"></i> Undangan
                            </a>
                        @endif
                        @if($agenda->dokumen_rundown)
                            <a href="{{ Storage::url($agenda->dokumen_rundown) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                                <i class="bi bi-file-pdf"></i> Rundown
                            </a>
                        @endif
                        @if($agenda->dokumen_materi)
                            <a href="{{ Storage::url($agenda->dokumen_materi) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                                <i class="bi bi-file-pdf"></i> Materi
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if($agenda->status != 'dibatalkan' && $agenda->status != 'selesai')
                <div class="card mb-3">
                    <div class="card-header"><h6>Aksi</h6></div>
                    <div class="card-body">
                        @if($agenda->kehadiran_konfirmasi == 'belum')
                            <form action="{{ route('agenda.konfirmasi-kehadiran', $agenda) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <select name="kehadiran_konfirmasi" class="form-select form-select-sm" required>
                                        <option value="hadir">Hadir</option>
                                        <option value="tidak_hadir">Tidak Hadir</option>
                                        <option value="diwakilkan">Diwakilkan</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-success w-100">Konfirmasi Kehadiran</button>
                            </form>
                        @else
                            <p class="text-success"><i class="bi bi-check-circle"></i> Kehadiran: {{ ucfirst($agenda->kehadiran_konfirmasi) }}</p>
                        @endif

                        @if($agenda->status == 'terjadwal')
                            <button class="btn btn-sm btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#batalkanModal">
                                <i class="bi bi-x-circle"></i> Batalkan Agenda
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Batalkan -->
<div class="modal fade" id="batalkanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('agenda.batalkan', $agenda) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Pembatalan *</label>
                        <textarea name="alasan_dibatalkan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Batalkan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
