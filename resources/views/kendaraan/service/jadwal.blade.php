@extends('layouts.app')
@section('titlepage', 'Jadwal Service Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Jadwal Service</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
            <i class="ti ti-plus me-2"></i>Tambah Jadwal
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title text-white"><i class="ti ti-car me-2"></i>Informasi Kendaraan</h3>
            </div>
            <div class="card-body">
                @if($kendaraan->foto)
                    <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-fluid rounded mb-3" alt="Foto Kendaraan">
                @endif
                <table class="table table-sm">
                    <tr>
                        <td><strong>Kode</strong></td>
                        <td>{{ $kendaraan->kode_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $kendaraan->nama_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Polisi</strong></td>
                        <td><span class="badge bg-primary">{{ $kendaraan->no_polisi }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis</strong></td>
                        <td>{{ $kendaraan->jenis_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($kendaraan->status == 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($kendaraan->status == 'keluar')
                                <span class="badge bg-info">Sedang Keluar</span>
                            @elseif($kendaraan->status == 'dipinjam')
                                <span class="badge bg-warning">Dipinjam</span>
                            @else
                                <span class="badge bg-danger">Service</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-white"><i class="ti ti-calendar me-2"></i>Jadwal Service</h3>
            </div>
            <div class="card-body">
                @if($jadwals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Service</th>
                                    <th>Interval</th>
                                    <th>Terakhir Service</th>
                                    <th>Jadwal Berikutnya</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwals as $index => $j)
                                @php
                                    $isOverdue = false;
                                    $isDue = false;
                                    
                                    if($j->tipe_interval == 'kilometer') {
                                        // Cek berdasarkan KM (perlu data KM terkini dari kendaraan)
                                        // Asumsi: jika terakhir service + interval < KM sekarang
                                        $kmSekarang = $kendaraan->km_terakhir ?? 0; // Perlu field ini di tabel kendaraan
                                        $targetKm = $j->km_terakhir + $j->interval_km;
                                        if($kmSekarang >= $targetKm) {
                                            $isOverdue = true;
                                        } elseif($kmSekarang >= ($targetKm - 500)) {
                                            $isDue = true;
                                        }
                                    } else {
                                        // Cek berdasarkan tanggal
                                        $targetDate = \Carbon\Carbon::parse($j->jadwal_berikutnya);
                                        $sekarang = \Carbon\Carbon::now();
                                        if($sekarang->gt($targetDate)) {
                                            $isOverdue = true;
                                        } elseif($sekarang->diffInDays($targetDate) <= 7) {
                                            $isDue = true;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $jadwals->firstItem() + $index }}</td>
                                    <td><strong>{{ $j->jenis_service }}</strong></td>
                                    <td>
                                        @if($j->tipe_interval == 'kilometer')
                                            {{ number_format($j->interval_km, 0, ',', '.') }} km
                                        @else
                                            {{ $j->interval_hari }} hari
                                        @endif
                                    </td>
                                    <td>
                                        @if($j->tipe_interval == 'kilometer')
                                            {{ number_format($j->km_terakhir, 0, ',', '.') }} km
                                        @else
                                            {{ $j->tanggal_terakhir ? \Carbon\Carbon::parse($j->tanggal_terakhir)->format('d/m/Y') : '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($j->tipe_interval == 'kilometer')
                                            {{ number_format($j->km_terakhir + $j->interval_km, 0, ',', '.') }} km
                                        @else
                                            {{ $j->jadwal_berikutnya ? \Carbon\Carbon::parse($j->jadwal_berikutnya)->format('d/m/Y') : '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($isOverdue)
                                            <span class="badge bg-danger">Terlambat</span>
                                        @elseif($isDue)
                                            <span class="badge bg-warning">Segera</span>
                                        @else
                                            <span class="badge bg-success">Terjadwal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalEditJadwal{{ $j->id }}" title="Edit Jadwal">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('service.deleteJadwal', Crypt::encrypt($j->id)) }}" method="POST" class="d-inline form-delete-jadwal">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                            @if($isOverdue)
                                            <a href="{{ route('service.form', Crypt::encrypt($kendaraan->id)) }}" class="btn btn-sm btn-warning" title="Service Sekarang">
                                                <i class="ti ti-tool"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit Jadwal -->
                                <div class="modal fade" id="modalEditJadwal{{ $j->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('service.updateJadwal', Crypt::encrypt($j->id)) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Jadwal Service</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Jenis Service</label>
                                                        <input type="text" name="jenis_service" class="form-control" value="{{ $j->jenis_service }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Tipe Interval</label>
                                                        <select name="tipe_interval" class="form-select interval-type-{{ $j->id }}">
                                                            <option value="kilometer" {{ $j->tipe_interval == 'kilometer' ? 'selected' : '' }}>Berdasarkan KM</option>
                                                            <option value="waktu" {{ $j->tipe_interval == 'waktu' ? 'selected' : '' }}>Berdasarkan Waktu</option>
                                                        </select>
                                                    </div>
                                                    <div class="interval-km-{{ $j->id }}" style="display: {{ $j->tipe_interval == 'kilometer' ? 'block' : 'none' }}">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Interval KM</label>
                                                            <input type="number" name="interval_km" class="form-control" value="{{ $j->interval_km }}">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">KM Terakhir Service</label>
                                                            <input type="number" name="km_terakhir" class="form-control" value="{{ $j->km_terakhir }}">
                                                        </div>
                                                    </div>
                                                    <div class="interval-waktu-{{ $j->id }}" style="display: {{ $j->tipe_interval == 'waktu' ? 'block' : 'none' }}">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Interval Hari</label>
                                                            <input type="number" name="interval_hari" class="form-control" value="{{ $j->interval_hari }}">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Tanggal Terakhir Service</label>
                                                            <input type="date" name="tanggal_terakhir" class="form-control" value="{{ $j->tanggal_terakhir }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Keterangan</label>
                                                        <textarea name="keterangan" class="form-control" rows="2">{{ $j->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        $('.interval-type-{{ $j->id }}').change(function() {
                                            if($(this).val() == 'kilometer') {
                                                $('.interval-km-{{ $j->id }}').show();
                                                $('.interval-waktu-{{ $j->id }}').hide();
                                            } else {
                                                $('.interval-km-{{ $j->id }}').hide();
                                                $('.interval-waktu-{{ $j->id }}').show();
                                            }
                                        });
                                    });
                                </script>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $jadwals->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>Belum ada jadwal service untuk kendaraan ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('service.storeJadwal', Crypt::encrypt($kendaraan->id)) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Jenis Service <span class="text-danger">*</span></label>
                        <select name="jenis_service" class="form-select">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Service Rutin">Service Rutin</option>
                            <option value="Ganti Oli">Ganti Oli</option>
                            <option value="Ganti Ban">Ganti Ban</option>
                            <option value="Tune Up">Tune Up</option>
                            <option value="Cek Berkala">Cek Berkala</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Tipe Interval <span class="text-danger">*</span></label>
                        <select name="tipe_interval" class="form-select" id="tipeInterval">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="kilometer">Berdasarkan Kilometer</option>
                            <option value="waktu">Berdasarkan Waktu</option>
                        </select>
                    </div>
                    <div id="intervalKm" style="display: none;">
                        <div class="form-group mb-3">
                            <label class="form-label">Interval KM</label>
                            <input type="number" name="interval_km" class="form-control" placeholder="Contoh: 5000">
                            <small class="text-muted">Service setiap berapa KM</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">KM Terakhir Service</label>
                            <input type="number" name="km_terakhir" class="form-control" placeholder="KM saat ini">
                        </div>
                    </div>
                    <div id="intervalWaktu" style="display: none;">
                        <div class="form-group mb-3">
                            <label class="form-label">Interval Hari</label>
                            <input type="number" name="interval_hari" class="form-control" placeholder="Contoh: 90">
                            <small class="text-muted">Service setiap berapa hari</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Tanggal Terakhir Service</label>
                            <input type="date" name="tanggal_terakhir" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        $('#tipeInterval').change(function() {
            if($(this).val() == 'kilometer') {
                $('#intervalKm').show();
                $('#intervalWaktu').hide();
            } else if($(this).val() == 'waktu') {
                $('#intervalKm').hide();
                $('#intervalWaktu').show();
            } else {
                $('#intervalKm').hide();
                $('#intervalWaktu').hide();
            }
        });
        
        // Handle delete jadwal with SweetAlert2
        $('.form-delete-jadwal').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Jadwal service akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
