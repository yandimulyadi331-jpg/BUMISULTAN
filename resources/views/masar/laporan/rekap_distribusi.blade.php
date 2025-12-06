@extends('layouts.app')
@section('titlepage', 'Laporan Rekapitulasi Distribusi')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Majlis Ta'lim /</span> Laporan Rekap Distribusi
@endsection

<!-- Navigation Tabs -->
@include('masar.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-file-report me-2"></i>Rekapitulasi Distribusi Hadiah</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success" onclick="exportToExcel()">
                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                    </button>
                    <button class="btn btn-sm btn-info" onclick="window.print()">
                        <i class="ti ti-printer me-1"></i> Cetak
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" class="form-control form-control-sm" 
                                   value="{{ request('tanggal_dari') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" class="form-control form-control-sm" 
                                   value="{{ request('tanggal_sampai') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Hadiah</label>
                            <select name="hadiah_id" class="form-select form-select-sm">
                                <option value="">Semua Hadiah</option>
                                @foreach($hadiahList as $hadiah)
                                    <option value="{{ $hadiah->id }}" {{ request('hadiah_id') == $hadiah->id ? 'selected' : '' }}>
                                        {{ $hadiah->nama_hadiah }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Ukuran</label>
                            <input type="text" name="ukuran" class="form-control form-control-sm" 
                                   value="{{ request('ukuran') }}" placeholder="S, M, L, dll">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="ti ti-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Laporan Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="tableRekap">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nomor Distribusi</th>
                                <th>Jamaah</th>
                                <th>Hadiah</th>
                                <th>Ukuran</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Penerima</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                                $totalJumlah = 0;
                            @endphp
                            @forelse($distribusiList as $dist)
                                @php $totalJumlah += $dist->jumlah; @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dist->tanggal_distribusi)->format('d/m/Y') }}</td>
                                    <td><small>{{ $dist->nomor_distribusi }}</small></td>
                                    <td>
                                        @if($dist->jamaah_id)
                                            <strong>{{ $dist->jamaah->nama_jamaah }}</strong><br>
                                            <small class="text-muted">{{ $dist->jamaah->nomor_jamaah }}</small>
                                        @else
                                            <span class="badge bg-info">Non-Jamaah</span><br>
                                            <small class="text-muted">{{ $dist->penerima }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $dist->hadiah->nama_hadiah ?? '-' }}<br>
                                        <small class="text-muted">{{ $dist->hadiah->kode_hadiah ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($dist->ukuran)
                                            <span class="badge bg-primary">{{ $dist->ukuran }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $dist->jumlah }}</span>
                                    </td>
                                    <td>
                                        @if($dist->metode_distribusi == 'langsung')
                                            <span class="badge bg-info">Langsung</span>
                                        @elseif($dist->metode_distribusi == 'undian')
                                            <span class="badge bg-warning">Undian</span>
                                        @elseif($dist->metode_distribusi == 'prestasi')
                                            <span class="badge bg-success">Prestasi</span>
                                        @elseif($dist->metode_distribusi == 'kehadiran')
                                            <span class="badge bg-primary">Kehadiran</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($dist->metode_distribusi ?? '-') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $dist->penerima ?? '-' }}</td>
                                    <td><small>{{ $dist->petugas_distribusi ?? '-' }}</small></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info detail-btn" 
                                                    data-id="{{ encrypt($dist->id) }}" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <a href="{{ route('masar.distribusi.edit', encrypt($dist->id)) }}" 
                                               class="btn btn-sm btn-warning" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-distribusi-btn" 
                                                    data-id="{{ encrypt($dist->id) }}" 
                                                    data-nomor="{{ $dist->nomor_distribusi }}" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Tidak ada data distribusi</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($distribusiList->count() > 0)
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="6" class="text-end">TOTAL:</th>
                                <th class="text-center">
                                    <span class="badge bg-dark">{{ $totalJumlah }}</span>
                                </th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>

                <!-- Summary -->
                @if($distribusiList->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="alert alert-primary">
                            <strong>Total Transaksi:</strong><br>
                            {{ $distribusiList->count() }} distribusi
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-success">
                            <strong>Total Hadiah Terdistribusi:</strong><br>
                            {{ $totalJumlah }} pcs
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-info">
                            <strong>Jenis Hadiah Berbeda:</strong><br>
                            {{ $distribusiList->unique('hadiah_id')->count() }} jenis
                        </div>
                    </div>
                </div>

                <!-- Rekap per Ukuran -->
                @php 
                    $rekapUkuran = $distribusiList->whereNotNull('ukuran')->groupBy('ukuran')->map(function($items) {
                        return [
                            'count' => $items->count(),
                            'total' => $items->sum('jumlah')
                        ];
                    });
                @endphp
                @if($rekapUkuran->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ti ti-ruler me-2"></i>Rekapitulasi Per Ukuran</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($rekapUkuran as $ukuran => $data)
                            <div class="col-md-2 mb-2">
                                <div class="border rounded p-2 text-center">
                                    <div class="fs-4 fw-bold text-primary">{{ $ukuran }}</div>
                                    <small class="text-muted">{{ $data['count'] }} transaksi</small><br>
                                    <span class="badge bg-success">{{ $data['total'] }} pcs</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Distribusi -->
<div class="modal fade" id="modalDetailDistribusi" tabindex="-1" aria-labelledby="modalDetailDistribusiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailDistribusiLabel">
                    <i class="ti ti-eye me-2"></i>Detail Distribusi Hadiah
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function exportToExcel() {
    const table = document.getElementById('tableRekap');
    const wb = XLSX.utils.table_to_book(table, {sheet: 'Rekap Distribusi'});
    const filename = 'Rekap_Distribusi_' + new Date().toISOString().slice(0,10) + '.xlsx';
    XLSX.writeFile(wb, filename);
}

// Initialize Bootstrap Tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Detail Button Handler
$(document).on('click', '.detail-btn', function() {
    const id = $(this).data('id');
    const modal = $('#modalDetailDistribusi');
    const modalContent = $('#modalDetailContent');
    
    modalContent.html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    modal.modal('show');
    
    $.ajax({
        url: '/masar/distribusi/' + id,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            modalContent.html(response);
        },
        error: function(xhr) {
            modalContent.html(`
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    Gagal memuat detail: ${xhr.responseJSON?.message || 'Terjadi kesalahan'}
                </div>
            `);
        }
    });
});

// Delete Button Handler
$(document).on('click', '.delete-distribusi-btn', function() {
    const id = $(this).data('id');
    const nomor = $(this).data('nomor');
    
    Swal.fire({
        title: 'Hapus Distribusi?',
        html: `Anda yakin ingin menghapus distribusi <strong>${nomor}</strong>?<br><small class="text-muted">Stok hadiah akan dikembalikan</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/masar/distribusi/' + id,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Distribusi berhasil dihapus',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus'
                    });
                }
            });
        }
    });
});
</script>

<style>
@media print {
    .card-header button, .card-header .d-flex, form { display: none !important; }
    .badge { border: 1px solid #000 !important; }
}
</style>
@endpush
