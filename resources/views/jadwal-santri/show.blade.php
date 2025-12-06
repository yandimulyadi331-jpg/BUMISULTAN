@extends('layouts.app')
@section('titlepage', 'Detail Jadwal Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('jadwal-santri.index') }}">Manajemen Saung Santri</a> / Detail Jadwal Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-calendar-event me-2"></i> Detail Jadwal: {{ $jadwalSantri->nama_jadwal }}</h4>
                    <div>
                        <a href="{{ route('jadwal-santri.edit', $jadwalSantri->id) }}" class="btn btn-light btn-sm">
                            <i class="ti ti-edit me-1"></i> Edit Jadwal
                        </a>
                        <a href="{{ route('jadwal-santri.index') }}" class="btn btn-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Nama Jadwal</th>
                                <td>: {{ $jadwalSantri->nama_jadwal }}</td>
                            </tr>
                            <tr>
                                <th>Tipe Jadwal</th>
                                <td>: <span class="badge bg-primary">{{ ucfirst($jadwalSantri->tipe_jadwal) }}</span></td>
                            </tr>
                            <tr>
                                <th>Hari/Tanggal</th>
                                <td>: 
                                    @if($jadwalSantri->tipe_jadwal == 'harian')
                                        Setiap Hari
                                    @elseif($jadwalSantri->tipe_jadwal == 'mingguan')
                                        {{ $jadwalSantri->hari }}
                                    @else
                                        {{ \Carbon\Carbon::parse($jadwalSantri->tanggal)->format('d F Y') }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jam</th>
                                <td>: {{ \Carbon\Carbon::parse($jadwalSantri->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalSantri->jam_selesai)->format('H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">Tempat</th>
                                <td>: {{ $jadwalSantri->tempat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pembimbing</th>
                                <td>: {{ $jadwalSantri->pembimbing ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: 
                                    @if($jadwalSantri->status == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>: {{ $jadwalSantri->keterangan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($jadwalSantri->deskripsi)
                <div class="row mt-2">
                    <div class="col-12">
                        <h6><strong>Deskripsi:</strong></h6>
                        <p>{{ $jadwalSantri->deskripsi }}</p>
                    </div>
                </div>
                @endif

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="ti ti-checkbox me-2"></i> Riwayat Absensi</h5>
                    <a href="{{ route('absensi-santri.create', $jadwalSantri->id) }}" class="btn btn-success btn-sm">
                        <i class="ti ti-plus me-1"></i> Input Absensi Baru
                    </a>
                </div>

                <!-- Filter Bulan & Tahun -->
                <div class="card mb-3" style="background-color: #f8f9fa;">
                    <div class="card-body">
                        <form action="{{ route('jadwal-santri.show', $jadwalSantri->id) }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="bulan" class="form-label"><i class="ti ti-calendar me-1"></i> Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tahun" class="form-label"><i class="ti ti-calendar-event me-1"></i> Tahun</label>
                                <select name="tahun" id="tahun" class="form-select">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-filter me-1"></i> Tampilkan
                                </button>
                                <a href="{{ route('jadwal-santri.show', $jadwalSantri->id) }}" class="btn btn-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                                <span class="ms-3 text-muted">
                                    <i class="ti ti-info-circle me-1"></i> 
                                    Menampilkan: <strong>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</strong>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                @if($absensiPerTanggal->count() > 0)
                    @foreach($absensiPerTanggal as $tanggal => $absensiList)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong>
                                    <span class="ms-2 text-muted">({{ $absensiList->count() }} santri)</span>
                                </div>
                                <div>
                                    <button type="button" 
                                        class="btn btn-danger btn-sm btn-delete-date" 
                                        data-jadwal-id="{{ $jadwalSantri->id }}"
                                        data-tanggal="{{ $tanggal }}"
                                        data-tanggal-format="{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}"
                                        title="Hapus Semua">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="12%">NIS</th>
                                            <th width="25%">Nama Santri</th>
                                            <th width="15%">Status Kehadiran</th>
                                            <th width="28%">Keterangan</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensiList as $index => $absensi)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $absensi->santri->nis }}</td>
                                            <td>{{ $absensi->santri->nama_lengkap }}</td>
                                            <td class="text-center">
                                                @if($absensi->status_kehadiran == 'hadir')
                                                    <span class="badge bg-success">Hadir</span>
                                                @elseif($absensi->status_kehadiran == 'ijin')
                                                    <span class="badge bg-warning">Ijin</span>
                                                @elseif($absensi->status_kehadiran == 'sakit')
                                                    <span class="badge bg-info">Sakit</span>
                                                @elseif($absensi->status_kehadiran == 'khidmat')
                                                    <span class="badge bg-primary">Khidmat</span>
                                                @else
                                                    <span class="badge bg-danger">Absen</span>
                                                @endif
                                            </td>
                                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                                            <td class="text-center">
                                                <button type="button" 
                                                    class="btn btn-warning btn-sm btn-edit-absensi" 
                                                    data-id="{{ $absensi->id }}"
                                                    data-santri="{{ $absensi->santri->nama_lengkap }}"
                                                    data-nis="{{ $absensi->santri->nis }}"
                                                    data-status="{{ $absensi->status_kehadiran }}"
                                                    data-keterangan="{{ $absensi->keterangan }}"
                                                    title="Edit Absensi">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button type="button" 
                                                    class="btn btn-danger btn-sm btn-delete-absensi" 
                                                    data-id="{{ $absensi->id }}"
                                                    data-santri="{{ $absensi->santri->nama_lengkap }}"
                                                    title="Hapus Absensi">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-warning">
                        <i class="ti ti-info-circle me-2"></i> 
                        Tidak ada data absensi untuk periode <strong>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</strong>. 
                        Silakan pilih bulan lain atau <a href="{{ route('absensi-santri.create', $jadwalSantri->id) }}">input absensi baru</a>.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Absensi -->
<div class="modal fade" id="editAbsensiModal" tabindex="-1" aria-labelledby="editAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title" id="editAbsensiModalLabel">
                    <i class="ti ti-edit me-2"></i> Edit Absensi Santri
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAbsensiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>NIS:</strong> <span id="edit-nis"></span><br>
                        <strong>Nama:</strong> <span id="edit-nama"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kehadiran <span class="text-danger">*</span></label>
                        <div class="d-grid gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_kehadiran" id="edit-hadir" value="hadir" required>
                                <label class="form-check-label" for="edit-hadir">
                                    Hadir
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_kehadiran" id="edit-ijin" value="ijin">
                                <label class="form-check-label" for="edit-ijin">
                                    Ijin
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_kehadiran" id="edit-sakit" value="sakit">
                                <label class="form-check-label" for="edit-sakit">
                                    Sakit
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_kehadiran" id="edit-khidmat" value="khidmat">
                                <label class="form-check-label" for="edit-khidmat">
                                    Khidmat
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_kehadiran" id="edit-absen" value="absen">
                                <label class="form-check-label" for="edit-absen">
                                    Absen
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit-keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit-keterangan" name="keterangan" rows="3" placeholder="Tambahkan keterangan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = new bootstrap.Modal(document.getElementById('editAbsensiModal'));
    
    // Handle Edit Button Click
    document.querySelectorAll('.btn-edit-absensi').forEach(button => {
        button.addEventListener('click', function() {
            const absensiId = this.getAttribute('data-id');
            const santriNama = this.getAttribute('data-santri');
            const santriNis = this.getAttribute('data-nis');
            const status = this.getAttribute('data-status');
            const keterangan = this.getAttribute('data-keterangan');
            
            // Set form action
            document.getElementById('editAbsensiForm').action = `/absensi-santri/${absensiId}/update-single`;
            
            // Set santri info
            document.getElementById('edit-nis').textContent = santriNis;
            document.getElementById('edit-nama').textContent = santriNama;
            
            // Set status radio button
            document.getElementById('edit-' + status).checked = true;
            
            // Set keterangan
            document.getElementById('edit-keterangan').value = keterangan || '';
            
            // Show modal
            editModal.show();
        });
    });

    // Handle Delete Button Click (Per Santri)
    document.querySelectorAll('.btn-delete-absensi').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const absensiId = this.getAttribute('data-id');
            const santriNama = this.getAttribute('data-santri');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus data absensi santri <strong>${santriNama}</strong>?<br><small class="text-danger">Data yang dihapus tidak dapat dikembalikan!</small>`,
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
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/absensi-santri/${absensiId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // Handle Delete Button Click (Per Tanggal/Semua)
    document.querySelectorAll('.btn-delete-date').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const jadwalId = this.getAttribute('data-jadwal-id');
            const tanggal = this.getAttribute('data-tanggal');
            const tanggalFormat = this.getAttribute('data-tanggal-format');
            
            Swal.fire({
                title: 'Konfirmasi Hapus Semua',
                html: `Apakah Anda yakin ingin menghapus <strong>SEMUA</strong> data absensi tanggal <strong>${tanggalFormat}</strong>?<br><small class="text-danger">Semua data santri di tanggal ini akan terhapus!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus Semua!',
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
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/absensi-santri/${jadwalId}/delete/${tanggal}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
