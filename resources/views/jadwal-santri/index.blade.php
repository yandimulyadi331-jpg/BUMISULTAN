@extends('layouts.app')
@section('titlepage', 'Jadwal Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span>Manajemen Saung Santri / Absensi Santri / Jadwal Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-calendar-event me-2"></i> Jadwal Kegiatan Santri</h4>
                    <div>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">
                            <i class="ti ti-plus me-1"></i> Tambah Jadwal Santri
                        </button>
                        <a href="{{ route('absensi-santri.laporan') }}" class="btn btn-info btn-sm">
                            <i class="ti ti-file-analytics me-1"></i> Laporan Absensi
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Alert -->
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: '{{ session('success') }}',
                                timer: 3000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                timerProgressBar: true
                            });
                        });
                    </script>
                @endif
                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: '{{ session('error') }}',
                                timer: 3000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                timerProgressBar: true
                            });
                        });
                    </script>
                @endif

                <!-- Informasi -->
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Info:</strong> Tombol <span class="badge bg-success">Absensi</span> akan muncul pada jadwal yang sedang berlangsung sesuai hari dan waktu yang ditentukan.
                </div>

                <!-- Tabel Jadwal -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Jadwal</th>
                                <th>Tipe</th>
                                <th>Hari/Tanggal</th>
                                <th>Waktu</th>
                                <th>Tempat</th>
                                <th>Pembimbing</th>
                                <th width="10%">Status</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalList as $index => $jadwal)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $jadwal->nama_jadwal }}</strong>
                                        @if($jadwal->deskripsi)
                                            <br><small class="text-muted">{{ Str::limit($jadwal->deskripsi, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($jadwal->tipe_jadwal) }}</span>
                                    </td>
                                    <td>
                                        @if($jadwal->tipe_jadwal == 'harian')
                                            <span class="text-muted">Setiap Hari</span>
                                        @elseif($jadwal->tipe_jadwal == 'mingguan')
                                            <i class="ti ti-calendar me-1"></i> {{ $jadwal->hari }}
                                        @else
                                            <i class="ti ti-calendar me-1"></i> {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        <i class="ti ti-clock me-1"></i> 
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td>{{ $jadwal->tempat ?? '-' }}</td>
                                    <td>{{ $jadwal->pembimbing ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($jadwal->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('jadwal-santri.show', $jadwal->id) }}" 
                                                class="btn btn-info btn-sm" 
                                                title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-warning btn-sm" 
                                                title="Edit"
                                                onclick="openEditModal({{ $jadwal->id }}, '{{ $jadwal->nama_jadwal }}', '{{ $jadwal->deskripsi }}', '{{ $jadwal->tipe_jadwal }}', '{{ $jadwal->hari }}', '{{ $jadwal->tanggal }}', '{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}', '{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}', '{{ $jadwal->tempat }}', '{{ $jadwal->pembimbing }}', '{{ $jadwal->status }}', '{{ $jadwal->keterangan }}')">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            @if($jadwal->is_berlangsung && $jadwal->status == 'aktif')
                                                <a href="{{ route('absensi-santri.create', $jadwal->id) }}" 
                                                    class="btn btn-success btn-sm" 
                                                    title="Absensi Santri">
                                                    <i class="ti ti-checkbox"></i> Absensi
                                                </a>
                                            @endif
                                            <form action="{{ route('jadwal-santri.destroy', $jadwal->id) }}" 
                                                method="POST" 
                                                style="display: inline-block;"
                                                class="delete-form"
                                                id="delete-form-{{ $jadwal->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus" data-id="{{ $jadwal->id }}" data-nama="{{ $jadwal->nama_jadwal }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ti ti-calendar-off" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Belum ada jadwal santri. Silakan tambahkan jadwal baru.</p>
                                        <a href="{{ route('jadwal-santri.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ti ti-plus me-1"></i> Tambah Jadwal Santri
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('jadwal-santri.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title" id="tambahJadwalModalLabel">
                        <i class="ti ti-calendar-plus me-2"></i> Tambah Jadwal Santri
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_jadwal" class="form-label">Nama Jadwal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_jadwal" name="nama_jadwal" placeholder="Contoh: Ngaji, Tahfidz, Kajian" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipe_jadwal" class="form-label">Tipe Jadwal <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipe_jadwal" name="tipe_jadwal" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan/Sekali Waktu</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3" id="field_hari" style="display: none;">
                            <label for="hari" class="form-label">Hari</label>
                            <select class="form-select" id="hari" name="hari">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="field_tanggal" style="display: none;">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tempat" class="form-label">Tempat</label>
                            <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Contoh: Masjid, Aula, Kelas A">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pembimbing" class="form-label">Pembimbing/Ustadz</label>
                            <input type="text" class="form-control" id="pembimbing" name="pembimbing" placeholder="Nama Ustadz/Ustadzah">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2" placeholder="Deskripsi kegiatan..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan tambahan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editJadwalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title" id="editJadwalModalLabel">
                        <i class="ti ti-calendar-edit me-2"></i> Edit Jadwal Santri
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_jadwal" class="form-label">Nama Jadwal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_jadwal" name="nama_jadwal" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_tipe_jadwal" class="form-label">Tipe Jadwal <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_tipe_jadwal" name="tipe_jadwal" required>
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan/Sekali Waktu</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3" id="edit_field_hari" style="display: none;">
                            <label for="edit_hari" class="form-label">Hari</label>
                            <select class="form-select" id="edit_hari" name="hari">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="edit_field_tanggal" style="display: none;">
                            <label for="edit_tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="edit_jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="edit_jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="edit_jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_tempat" class="form-label">Tempat</label>
                            <input type="text" class="form-control" id="edit_tempat" name="tempat">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_pembimbing" class="form-label">Pembimbing/Ustadz</label>
                            <input type="text" class="form-control" id="edit_pembimbing" name="pembimbing">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="edit_keterangan" name="keterangan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Update Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, nama, deskripsi, tipe, hari, tanggal, jamMulai, jamSelesai, tempat, pembimbing, status, keterangan) {
    // Set form action
    document.getElementById('editJadwalForm').action = '/jadwal-santri/' + id;
    
    // Fill form fields
    document.getElementById('edit_nama_jadwal').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi || '';
    document.getElementById('edit_tipe_jadwal').value = tipe;
    document.getElementById('edit_hari').value = hari || '';
    document.getElementById('edit_tanggal').value = tanggal || '';
    document.getElementById('edit_jam_mulai').value = jamMulai;
    document.getElementById('edit_jam_selesai').value = jamSelesai;
    document.getElementById('edit_tempat').value = tempat || '';
    document.getElementById('edit_pembimbing').value = pembimbing || '';
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_keterangan').value = keterangan || '';
    
    // Toggle fields based on tipe
    toggleEditFields();
    
    // Show modal
    var myModal = new bootstrap.Modal(document.getElementById('editJadwalModal'));
    myModal.show();
}

function toggleEditFields() {
    const tipe = document.getElementById('edit_tipe_jadwal').value;
    const fieldHari = document.getElementById('edit_field_hari');
    const fieldTanggal = document.getElementById('edit_field_tanggal');
    
    fieldHari.style.display = 'none';
    fieldTanggal.style.display = 'none';
    
    if (tipe === 'mingguan') {
        fieldHari.style.display = 'block';
    } else if (tipe === 'bulanan') {
        fieldTanggal.style.display = 'block';
    }
}

function toggleTambahFields() {
    const tipe = document.getElementById('tipe_jadwal').value;
    const fieldHari = document.getElementById('field_hari');
    const fieldTanggal = document.getElementById('field_tanggal');
    
    fieldHari.style.display = 'none';
    fieldTanggal.style.display = 'none';
    
    if (tipe === 'mingguan') {
        fieldHari.style.display = 'block';
    } else if (tipe === 'bulanan') {
        fieldTanggal.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // For Edit Modal
    const editTipeJadwal = document.getElementById('edit_tipe_jadwal');
    if (editTipeJadwal) {
        editTipeJadwal.addEventListener('change', toggleEditFields);
    }
    
    // For Tambah Modal
    const tipeJadwal = document.getElementById('tipe_jadwal');
    if (tipeJadwal) {
        tipeJadwal.addEventListener('change', toggleTambahFields);
    }

    // SweetAlert2 untuk konfirmasi hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const jadwalId = this.getAttribute('data-id');
            const jadwalNama = this.getAttribute('data-nama');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus jadwal <strong>"${jadwalNama}"</strong>?<br><small class="text-danger">Data absensi terkait jadwal ini juga akan terhapus!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="ti ti-x me-1"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    document.getElementById('delete-form-' + jadwalId).submit();
                }
            });
        });
    });
});
</script>
@endsection
