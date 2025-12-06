@extends('layouts.app')
@section('titlepage', 'Input Absensi Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('jadwal-santri.index') }}">Manajemen Saung Santri</a> / Input Absensi Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h4 class="mb-0"><i class="ti ti-checkbox me-2"></i> Input Absensi: {{ $jadwal->nama_jadwal }}</h4>
            </div>
            <div class="card-body">
                <!-- Info Jadwal -->
                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Jadwal:</strong> {{ $jadwal->nama_jadwal }}<br>
                            <strong>Hari/Tanggal:</strong> 
                            @if($jadwal->tipe_jadwal == 'harian')
                                Harian
                            @elseif($jadwal->tipe_jadwal == 'mingguan')
                                {{ $jadwal->hari }}
                            @else
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}<br>
                            <strong>Pembimbing:</strong> {{ $jadwal->pembimbing ?? '-' }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('absensi-santri.store', $jadwal->id) }}" method="POST" id="formAbsensi">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="tanggal_absensi" class="form-label">Tanggal Absensi <span class="text-danger">*</span></label>
                            <input type="date" 
                                class="form-control @error('tanggal_absensi') is-invalid @enderror" 
                                id="tanggal_absensi" 
                                name="tanggal_absensi" 
                                value="{{ old('tanggal_absensi', date('Y-m-d')) }}" 
                                onchange="checkExistingAbsensi()"
                                required>
                            @error('tanggal_absensi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-info w-100" id="btnSalinMingguLalu" onclick="salinMingguLalu()">
                                <i class="ti ti-copy me-1"></i> Salin Minggu Lalu
                            </button>
                        </div>
                    </div>

                    <!-- Alert Info -->
                    <div id="alertInfo" class="alert alert-warning d-none mb-3">
                        <i class="ti ti-info-circle me-2"></i> <span id="alertText"></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tableAbsensi">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">NIS</th>
                                    <th>Nama Santri</th>
                                    <th width="35%">Status Kehadiran</th>
                                    <th width="25%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($santriList as $index => $santri)
                                <tr data-santri-id="{{ $santri->id }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $santri->nis }}</td>
                                    <td>
                                        <strong>{{ $santri->nama_lengkap }}</strong>
                                        @if($santri->jenis_kelamin)
                                            <span class="badge bg-{{ $santri->jenis_kelamin == 'L' ? 'primary' : 'danger' }} ms-2">
                                                {{ $santri->jenis_kelamin }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <input type="radio" 
                                                class="btn-check" 
                                                name="absensi[{{ $santri->id }}]" 
                                                id="hadir_{{ $santri->id }}" 
                                                value="hadir" 
                                                {{ isset($absensiData[$santri->id]) && $absensiData[$santri->id]->status_kehadiran == 'hadir' ? 'checked' : '' }}
                                                required>
                                            <label class="btn btn-outline-success btn-sm" for="hadir_{{ $santri->id }}">
                                                <i class="ti ti-check"></i> Hadir
                                            </label>

                                            <input type="radio" 
                                                class="btn-check" 
                                                name="absensi[{{ $santri->id }}]" 
                                                id="ijin_{{ $santri->id }}" 
                                                value="ijin"
                                                {{ isset($absensiData[$santri->id]) && $absensiData[$santri->id]->status_kehadiran == 'ijin' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning btn-sm" for="ijin_{{ $santri->id }}">
                                                <i class="ti ti-calendar-minus"></i> Ijin
                                            </label>

                                            <input type="radio" 
                                                class="btn-check" 
                                                name="absensi[{{ $santri->id }}]" 
                                                id="sakit_{{ $santri->id }}" 
                                                value="sakit"
                                                {{ isset($absensiData[$santri->id]) && $absensiData[$santri->id]->status_kehadiran == 'sakit' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-info btn-sm" for="sakit_{{ $santri->id }}">
                                                <i class="ti ti-ambulance"></i> Sakit
                                            </label>

                                            <input type="radio" 
                                                class="btn-check" 
                                                name="absensi[{{ $santri->id }}]" 
                                                id="khidmat_{{ $santri->id }}" 
                                                value="khidmat"
                                                {{ isset($absensiData[$santri->id]) && $absensiData[$santri->id]->status_kehadiran == 'khidmat' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary btn-sm" for="khidmat_{{ $santri->id }}">
                                                <i class="ti ti-heart-handshake"></i> Khidmat
                                            </label>

                                            <input type="radio" 
                                                class="btn-check" 
                                                name="absensi[{{ $santri->id }}]" 
                                                id="absen_{{ $santri->id }}" 
                                                value="absen"
                                                {{ isset($absensiData[$santri->id]) && $absensiData[$santri->id]->status_kehadiran == 'absen' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger btn-sm" for="absen_{{ $santri->id }}">
                                                <i class="ti ti-x"></i> Absen
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" 
                                            class="form-control form-control-sm" 
                                            name="keterangan[{{ $santri->id }}]" 
                                            value="{{ isset($absensiData[$santri->id]) ? $absensiData[$santri->id]->keterangan : '' }}"
                                            placeholder="Keterangan...">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="ti ti-users-off" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Tidak ada data santri aktif.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('jadwal-santri.show', $jadwal->id) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        @if($santriList->count() > 0)
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Absensi
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const jadwalId = {{ $jadwal->id }};

// Auto select "Hadir" untuk semua santri jika belum ada pilihan
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const radios = row.querySelectorAll('input[type="radio"]');
        const hasChecked = Array.from(radios).some(radio => radio.checked);
        
        // Jika belum ada yang dipilih, pilih "Hadir" sebagai default
        if (!hasChecked && radios.length > 0) {
            radios[0].checked = true; // Hadir adalah radio pertama
        }
    });
    
    // Check existing absensi on page load
    checkExistingAbsensi();
});

// Fungsi untuk cek apakah tanggal sudah ada absensi
function checkExistingAbsensi() {
    const tanggal = document.getElementById('tanggal_absensi').value;
    if (!tanggal) return;
    
    // Show loading
    Swal.fire({
        title: 'Memeriksa data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/absensi-santri/${jadwalId}/check-date?tanggal=${tanggal}`)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.exists) {
                // Ada data absensi
                document.getElementById('alertInfo').classList.remove('d-none');
                document.getElementById('alertText').innerHTML = 
                    `<strong>Data absensi tanggal ${data.tanggal_format} sudah ada!</strong> Anda dapat mengubah data yang sudah ada atau menggantinya.`;
                
                // Load data absensi yang ada
                loadExistingAbsensi(data.absensi);
            } else {
                // Tidak ada data
                document.getElementById('alertInfo').classList.add('d-none');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
        });
}

// Fungsi untuk load data absensi yang sudah ada
function loadExistingAbsensi(absensiData) {
    absensiData.forEach(absensi => {
        const row = document.querySelector(`tr[data-santri-id="${absensi.santri_id}"]`);
        if (row) {
            // Set status kehadiran
            const radio = row.querySelector(`input[value="${absensi.status_kehadiran}"]`);
            if (radio) {
                radio.checked = true;
            }
            
            // Set keterangan
            const keteranganInput = row.querySelector(`input[name="keterangan[${absensi.santri_id}]"]`);
            if (keteranganInput) {
                keteranganInput.value = absensi.keterangan || '';
            }
        }
    });
    
    Swal.fire({
        icon: 'info',
        title: 'Data Ter-load!',
        text: 'Data absensi yang sudah ada berhasil dimuat. Silakan edit jika perlu.',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

// Fungsi untuk salin data minggu lalu
function salinMingguLalu() {
    const tanggal = document.getElementById('tanggal_absensi').value;
    if (!tanggal) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Tanggal Dulu',
            text: 'Silakan pilih tanggal absensi terlebih dahulu!'
        });
        return;
    }
    
    Swal.fire({
        title: 'Salin Data Minggu Lalu?',
        text: 'Data absensi 7 hari sebelumnya akan disalin ke form ini',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="ti ti-copy me-1"></i> Ya, Salin!',
        cancelButtonText: '<i class="ti ti-x me-1"></i> Batal',
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-info me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Calculate tanggal minggu lalu
            const selectedDate = new Date(tanggal);
            const lastWeekDate = new Date(selectedDate);
            lastWeekDate.setDate(lastWeekDate.getDate() - 7);
            const lastWeekDateStr = lastWeekDate.toISOString().split('T')[0];
            
            // Show loading
            Swal.fire({
                title: 'Menyalin data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/absensi-santri/${jadwalId}/check-date?tanggal=${lastWeekDateStr}`)
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.exists && data.absensi.length > 0) {
                        // Load data minggu lalu
                        loadExistingAbsensi(data.absensi);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `Data absensi tanggal ${data.tanggal_format} berhasil disalin!`,
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Ada Data',
                            text: `Tidak ada data absensi pada tanggal ${lastWeekDateStr} (7 hari lalu)`,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyalin data'
                    });
                    console.error('Error:', error);
                });
        }
    });
}
</script>
@endsection
