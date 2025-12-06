@extends('layouts.app')
@section('titlepage', 'Riwayat Service Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Riwayat Service</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        
        @if($kendaraan->serviceAktif)
            <a href="{{ route('service.selesai', Crypt::encrypt($kendaraan->serviceAktif->id)) }}" class="btn btn-success">
                <i class="ti ti-check me-2"></i>Tandai Selesai
            </a>
        @else
            <a href="{{ route('service.form', Crypt::encrypt($kendaraan->id)) }}" class="btn btn-warning text-white">
                <i class="ti ti-tool me-2"></i>Service Kendaraan
            </a>
        @endif
        
        <a href="{{ route('service.jadwal', Crypt::encrypt($kendaraan->id)) }}" class="btn btn-info">
            <i class="ti ti-calendar me-2"></i>Jadwal Service
        </a>
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
            <div class="card-header bg-warning text-white">
                <h3 class="card-title text-white"><i class="ti ti-tool me-2"></i>Riwayat Service</h3>
            </div>
            <div class="card-body">
                @if($services->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Service</th>
                                    <th>Jenis Service</th>
                                    <th>Bengkel</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Selesai</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $index => $s)
                                <tr>
                                    <td>{{ $services->firstItem() + $index }}</td>
                                    <td><strong>{{ $s->kode_service }}</strong></td>
                                    <td>{{ $s->jenis_service }}</td>
                                    <td>{{ $s->bengkel }}</td>
                                    <td>{{ \Carbon\Carbon::parse($s->waktu_service)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($s->waktu_selesai)
                                            {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="badge bg-warning">Belum Selesai</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($s->biaya_akhir ?? $s->estimasi_biaya, 0, ',', '.') }}</td>
                                    <td>
                                        @if($s->status == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-warning">Proses</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($s->foto_before)
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalBefore{{ $s->id }}">
                                                    <i class="ti ti-photo"></i> Before
                                                </button>
                                            @endif
                                            @if($s->foto_after)
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalAfter{{ $s->id }}">
                                                    <i class="ti ti-photo"></i> After
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $s->id }}" title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <form action="{{ route('service.delete', Crypt::encrypt($s->id)) }}" method="POST" class="d-inline form-delete-service">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Foto Before -->
                                @if($s->foto_before)
                                <div class="modal fade" id="modalBefore{{ $s->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Foto Sebelum Service - {{ $s->kode_service }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/service/' . $s->foto_before) }}" class="img-fluid rounded" alt="Foto Before">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Modal Foto After -->
                                @if($s->foto_after)
                                <div class="modal fade" id="modalAfter{{ $s->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Foto Setelah Service - {{ $s->kode_service }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/service/' . $s->foto_after) }}" class="img-fluid rounded" alt="Foto After">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Modal Detail -->
                                <div class="modal fade" id="modalDetail{{ $s->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title text-white">Detail Service - {{ $s->kode_service }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Jenis Service:</strong><br>
                                                        {{ $s->jenis_service }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Bengkel:</strong><br>
                                                        {{ $s->bengkel }}
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Waktu Masuk:</strong><br>
                                                        {{ \Carbon\Carbon::parse($s->waktu_service)->format('d/m/Y H:i') }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Waktu Selesai:</strong><br>
                                                        @if($s->waktu_selesai)
                                                            {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="badge bg-warning">Belum Selesai</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>KM Service:</strong><br>
                                                        {{ number_format($s->km_service, 0, ',', '.') }} km
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>KM Selesai:</strong><br>
                                                        {{ $s->km_selesai ? number_format($s->km_selesai, 0, ',', '.') . ' km' : '-' }}
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Estimasi Biaya:</strong><br>
                                                        Rp {{ number_format($s->estimasi_biaya, 0, ',', '.') }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Biaya Akhir:</strong><br>
                                                        {{ $s->biaya_akhir ? 'Rp ' . number_format($s->biaya_akhir, 0, ',', '.') : '-' }}
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <strong>Deskripsi Kerusakan:</strong><br>
                                                        {{ $s->deskripsi_kerusakan }}
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <strong>Pekerjaan:</strong><br>
                                                        {{ $s->pekerjaan_selesai ?? $s->pekerjaan ?? '-' }}
                                                    </div>
                                                </div>
                                                @if($s->catatan_mekanik)
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <strong>Catatan Mekanik:</strong><br>
                                                        <div class="alert alert-info mb-0">{{ $s->catatan_mekanik }}</div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>PIC / Mekanik:</strong><br>
                                                        {{ $s->pic_selesai ?? $s->pic ?? '-' }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Kondisi Kendaraan:</strong><br>
                                                        {{ $s->kondisi_kendaraan ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $services->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>Belum ada riwayat service untuk kendaraan ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Handle delete service with SweetAlert2
        $('.form-delete-service').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Hapus Service?',
                text: "Data service akan dihapus permanen!",
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
