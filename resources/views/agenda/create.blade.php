@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-plus-circle"></i> Buat Agenda Baru</h2>

    <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card mb-3">
            <div class="card-header"><h5>Informasi Dasar</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Judul Agenda *</label>
                        <input type="text" name="judul" class="form-control" required value="{{ old('judul') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipe *</label>
                        <select name="tipe_agenda" class="form-select" required>
                            <option value="undangan">Undangan</option>
                            <option value="rapat">Rapat</option>
                            <option value="kunjungan">Kunjungan</option>
                            <option value="event">Event</option>
                            <option value="deadline">Deadline</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Waktu & Tempat</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required value="{{ old('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" class="form-control" required value="{{ old('waktu_mulai') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Link Meeting (Jika Online)</label>
                        <input type="url" name="link_meeting" class="form-control" value="{{ old('link_meeting') }}">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_online" id="is_online">
                            <label class="form-check-label" for="is_online">Agenda Online</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Dress Code & Detail</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Dress Code *</label>
                        <select name="dress_code" class="form-select" required>
                            <option value="formal">👔 Formal</option>
                            <option value="semi_formal">👕 Semi Formal</option>
                            <option value="batik">👘 Batik</option>
                            <option value="casual">👕 Casual</option>
                            <option value="bebas_rapi" selected>👔 Bebas Rapi</option>
                            <option value="khusus">🎭 Khusus</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kategori *</label>
                        <select name="kategori_agenda" class="form-select" required>
                            <option value="internal">Internal</option>
                            <option value="eksternal">Eksternal</option>
                            <option value="pemerintah">Pemerintah</option>
                            <option value="vendor">Vendor</option>
                            <option value="client">Client</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Penyelenggara</label>
                        <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Perlengkapan yang Dibawa</label>
                        <textarea name="perlengkapan_dibawa" class="form-control" rows="2">{{ old('perlengkapan_dibawa') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Status & Prioritas</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft">Draft</option>
                            <option value="terjadwal" selected>Terjadwal</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prioritas *</label>
                        <select name="prioritas" class="form-select" required>
                            <option value="rendah">Rendah</option>
                            <option value="sedang" selected>Sedang</option>
                            <option value="tinggi">Tinggi</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="is_wajib_hadir" id="is_wajib_hadir">
                            <label class="form-check-label" for="is_wajib_hadir">Wajib Dihadiri Pimpinan</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Pengaturan Reminder</h5></div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="reminder_aktif" id="reminder_aktif" checked>
                    <label class="form-check-label" for="reminder_aktif"><strong>Aktifkan Reminder</strong></label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="reminder_1_hari" checked>
                    <label class="form-check-label">Reminder 1 Hari Sebelum</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="reminder_3_jam" checked>
                    <label class="form-check-label">Reminder 3 Jam Sebelum</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="reminder_30_menit" checked>
                    <label class="form-check-label">Reminder 30 Menit Sebelum</label>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5>Dokumen</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Dokumen Undangan/Surat</label>
                        <input type="file" name="dokumen_undangan" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Rundown Acara</label>
                        <input type="file" name="dokumen_rundown" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Materi/Presentasi</label>
                        <input type="file" name="dokumen_materi" class="form-control" accept=".pdf,.ppt,.pptx">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Agenda</button>
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
