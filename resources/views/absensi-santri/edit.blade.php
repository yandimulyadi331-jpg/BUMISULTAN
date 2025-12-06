@extends('layouts.app')
@section('titlepage', 'Edit Absensi Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('jadwal-santri.index') }}">Manajemen Saung Santri</a> / Edit Absensi Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h4 class="mb-0"><i class="ti ti-edit me-2"></i> Edit Absensi: {{ $jadwal->nama_jadwal }}</h4>
            </div>
            <div class="card-body">
                <!-- Info Jadwal -->
                <div class="alert alert-warning mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Jadwal:</strong> {{ $jadwal->nama_jadwal }}<br>
                            <strong>Tanggal Absensi:</strong> {{ \Carbon\Carbon::parse($tanggalAbsensi)->format('d F Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}<br>
                            <strong>Pembimbing:</strong> {{ $jadwal->pembimbing ?? '-' }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('absensi-santri.update', [$jadwal->id, $tanggalAbsensi]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="tanggal_absensi" class="form-label">Tanggal Absensi <span class="text-danger">*</span></label>
                        <input type="date" 
                            class="form-control @error('tanggal_absensi') is-invalid @enderror" 
                            id="tanggal_absensi" 
                            name="tanggal_absensi" 
                            value="{{ old('tanggal_absensi', $tanggalAbsensi) }}" 
                            required>
                        @error('tanggal_absensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                                <tr>
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
                            <i class="ti ti-device-floppy me-1"></i> Update Absensi
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto select "Hadir" untuk santri yang belum ada data absensinya
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
});
</script>
@endsection
