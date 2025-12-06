@extends('layouts.app')
@section('titlepage', 'Riwayat Aktivitas Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Riwayat Aktivitas</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        
        @if($kendaraan->aktivitasAktif)
            <a href="{{ route('kendaraan.aktivitas.kembali', Crypt::encrypt($kendaraan->aktivitasAktif->id)) }}" class="btn btn-success">
                <i class="ti ti-check me-2"></i>Tandai Kembali
            </a>
        @else
            <a href="{{ route('kendaraan.aktivitas.keluar', Crypt::encrypt($kendaraan->id)) }}" class="btn btn-primary">
                <i class="ti ti-car me-2"></i>Tambah Aktivitas Keluar
            </a>
        @endif
    </div>
</div>

<div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                @if($kendaraan->foto)
                                    <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-fluid rounded" alt="Foto">
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h3>{{ $kendaraan->nama_kendaraan }} <span class="badge bg-primary">{{ $kendaraan->no_polisi }}</span></h3>
                                <p class="text-muted mb-1">{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->merk }} {{ $kendaraan->model }}</p>
                                <p class="mb-0"><strong>Status:</strong> 
                                    @if($kendaraan->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($kendaraan->status == 'keluar')
                                        <span class="badge bg-info">Sedang Keluar</span>
                                    @elseif($kendaraan->status == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @else
                                        <span class="badge bg-danger">Service</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Aktivitas Keluar/Masuk</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Pengemudi</th>
                                        <th>Tujuan</th>
                                        <th>Waktu Keluar</th>
                                        <th>Waktu Kembali</th>
                                        <th>Durasi</th>
                                        <th>KM</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($aktivitas as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $aktivitas->firstItem() - 1 }}</td>
                                            <td><strong>{{ $d->kode_aktivitas }}</strong></td>
                                            <td>{{ $d->driver }}</td>
                                            <td>{{ Str::limit($d->tujuan, 30) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($d->waktu_keluar)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($d->waktu_kembali)
                                                    {{ \Carbon\Carbon::parse($d->waktu_kembali)->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="badge bg-warning">Belum Kembali</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->waktu_kembali)
                                                    {{ \Carbon\Carbon::parse($d->waktu_keluar)->diffInHours(\Carbon\Carbon::parse($d->waktu_kembali)) }} jam
                                                @else
                                                    {{ \Carbon\Carbon::parse($d->waktu_keluar)->diffForHumans() }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->km_awal && $d->km_akhir)
                                                    {{ $d->km_akhir - $d->km_awal }} km
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->status == 'keluar')
                                                    <span class="badge bg-info">Keluar</span>
                                                @else
                                                    <span class="badge bg-success">Kembali</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('kendaraan.aktivitas.tracking', Crypt::encrypt($d->id)) }}" 
                                                    class="btn btn-sm btn-primary" target="_blank" title="GPS Tracking">
                                                    <i class="ti ti-map"></i>
                                                </a>
                                                @if($d->status == 'keluar')
                                                    <a href="{{ route('admin.livetracking', Crypt::encrypt($d->id)) }}" 
                                                        class="btn btn-sm btn-success" target="_blank" title="Live Tracking">
                                                        <i class="ti ti-live-view"></i>
                                                    </a>
                                                @endif
                                                <button class="btn btn-sm btn-info detailAktivitas" data-id="{{ $d->id }}" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Belum ada riwayat aktivitas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $aktivitas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Detail Aktivitas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="loadDetailAktivitas"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).on('click', '.detailAktivitas', function() {
        var id = $(this).data('id');
        $('#modalDetail').modal('show');
        $('#loadDetailAktivitas').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
        
        $.ajax({
            url: '/aktivitas/' + id + '/detail',
            method: 'GET',
            success: function(response) {
                $('#loadDetailAktivitas').html(response);
            },
            error: function() {
                $('#loadDetailAktivitas').html('<div class="alert alert-danger">Gagal memuat detail</div>');
            }
        });
    });
</script>
@endpush
