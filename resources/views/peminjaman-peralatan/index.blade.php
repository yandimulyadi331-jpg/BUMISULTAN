@extends('layouts.app')
@section('titlepage', 'Peminjaman Peralatan BS')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peralatan.index') }}">PERALATAN BS</a> / Peminjaman</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-clipboard-list me-2"></i> Peminjaman Peralatan</h4>
                    <div>
                        <a href="{{ route('peralatan.index') }}" class="btn btn-light btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali ke Peralatan
                        </a>
                        <a href="{{ route('peminjaman-peralatan.export-pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                        </a>
                        <button type="button" class="btn btn-primary btn-sm btnTambahPeminjaman">
                            <i class="ti ti-plus me-1"></i> Tambah Peminjaman
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('peminjaman-peralatan.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <input type="text" name="search" class="form-control" 
                                        placeholder="🔍 Cari nomor peminjaman, peralatan, atau karyawan..." 
                                        value="{{ Request('search') }}">
                                </div>
                                <div class="col-lg-3">
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="dipinjam" {{ Request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="dikembalikan" {{ Request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                        <option value="terlambat" {{ Request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Nomor Peminjaman</th>
                                <th>Peralatan</th>
                                <th>Peminjam</th>
                                <th width="8%">Jumlah</th>
                                <th width="10%">Tgl Pinjam</th>
                                <th width="10%">Tgl Kembali</th>
                                <th width="8%">Status</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $index => $item)
                            <tr>
                                <td class="text-center">{{ $peminjaman->firstItem() + $index }}</td>
                                <td><strong>{{ $item->nomor_peminjaman }}</strong></td>
                                <td>
                                    <strong>{{ $item->peralatan->nama_peralatan }}</strong><br>
                                    <small class="text-muted">{{ $item->peralatan->kode_peralatan }}</small>
                                </td>
                                <td>
                                    {{ $item->nama_peminjam }}
                                </td>
                                <td class="text-center">
                                    <strong>{{ $item->jumlah_dipinjam }} {{ $item->peralatan->satuan }}</strong>
                                </td>
                                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>
                                    @if($item->tanggal_kembali_aktual)
                                        <span class="badge bg-success">{{ $item->tanggal_kembali_aktual->format('d/m/Y') }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $item->tanggal_kembali_rencana->format('d/m/Y') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'dipinjam')
                                        @if($item->isTerlambat())
                                            <span class="badge bg-danger">
                                                <i class="ti ti-alert-triangle me-1"></i> Terlambat
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="ti ti-clock me-1"></i> Dipinjam
                                            </span>
                                        @endif
                                    @elseif($item->status == 'terlambat')
                                        <span class="badge bg-danger">Terlambat</span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="ti ti-check me-1"></i> Dikembalikan
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                            class="btn btn-info btn-sm btnDetailPeminjaman" 
                                            data-id="{{ $item->id }}"
                                            title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        @if($item->status == 'dipinjam')
                                            <button type="button" 
                                                class="btn btn-success btn-sm btnKembalikan" 
                                                data-id="{{ $item->id }}"
                                                title="Kembalikan">
                                                <i class="ti ti-arrow-back-up"></i>
                                            </button>
                                            <button type="button" 
                                                class="btn btn-warning btn-sm btnEditPeminjaman" 
                                                data-id="{{ $item->id }}"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                        @endif
                                        <button type="button" 
                                            class="btn btn-danger btn-sm btnHapus" 
                                            data-id="{{ $item->id }}"
                                            data-nomor="{{ $item->nomor_peminjaman }}"
                                            title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="ti ti-clipboard-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Belum ada data peminjaman</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $peminjaman->firstItem() ?? 0 }} - {{ $peminjaman->lastItem() ?? 0 }} 
                        dari {{ $peminjaman->total() }} data
                    </div>
                    <div>
                        {{ $peminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="ti ti-trash me-2"></i> Hapus Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus peminjaman <strong id="nomorPeminjaman"></strong>?</p>
                    <p class="text-danger"><i class="ti ti-alert-triangle me-1"></i> Stok akan dikembalikan ke peralatan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Peminjaman -->
<div class="modal fade" id="modalTambahPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-plus me-2"></i> Tambah Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="tambahPeminjamanContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peminjaman -->
<div class="modal fade" id="modalDetailPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="ti ti-info-circle me-2"></i> Detail Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailPeminjamanContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Peminjaman -->
<div class="modal fade" id="modalEditPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="ti ti-edit me-2"></i> Edit Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editPeminjamanContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengembalian -->
<div class="modal fade" id="modalPengembalian" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="ti ti-arrow-back-up me-2"></i> Pengembalian Peralatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="pengembalianContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(document).ready(function() {
        // Tambah Peminjaman
        $('.btnTambahPeminjaman').click(function() {
            $('#tambahPeminjamanContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalTambah = new bootstrap.Modal(document.getElementById('modalTambahPeminjaman'));
            modalTambah.show();
            
            $.ajax({
                url: "{{ route('peminjaman-peralatan.create') }}",
                type: 'GET',
                success: function(response) {
                    $('#tambahPeminjamanContent').html(response);
                },
                error: function() {
                    $('#tambahPeminjamanContent').html('<div class="alert alert-danger">Gagal memuat form</div>');
                }
            });
        });
        
        // Detail Peminjaman
        $('.btnDetailPeminjaman').click(function() {
            var id = $(this).data('id');
            $('#detailPeminjamanContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalDetail = new bootstrap.Modal(document.getElementById('modalDetailPeminjaman'));
            modalDetail.show();
            
            $.ajax({
                url: "{{ url('peminjaman-peralatan') }}/" + id,
                type: 'GET',
                success: function(response) {
                    $('#detailPeminjamanContent').html(response);
                },
                error: function() {
                    $('#detailPeminjamanContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            });
        });
        
        // Edit Peminjaman
        $('.btnEditPeminjaman').click(function() {
            var id = $(this).data('id');
            $('#editPeminjamanContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalEdit = new bootstrap.Modal(document.getElementById('modalEditPeminjaman'));
            modalEdit.show();
            
            $.ajax({
                url: "{{ url('peminjaman-peralatan') }}/" + id + "/edit",
                type: 'GET',
                success: function(response) {
                    $('#editPeminjamanContent').html(response);
                },
                error: function() {
                    $('#editPeminjamanContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            });
        });
        
        // Pengembalian
        $('.btnKembalikan').click(function() {
            var id = $(this).data('id');
            $('#pengembalianContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalPengembalian = new bootstrap.Modal(document.getElementById('modalPengembalian'));
            modalPengembalian.show();
            
            $.ajax({
                url: "{{ url('peminjaman-peralatan') }}/" + id + "/form-pengembalian",
                type: 'GET',
                success: function(response) {
                    $('#pengembalianContent').html(response);
                },
                error: function() {
                    $('#pengembalianContent').html('<div class="alert alert-danger">Gagal memuat form</div>');
                }
            });
        });
        
        // Hapus Peminjaman
        $('.btnHapus').click(function() {
            var id = $(this).data('id');
            var nomor = $(this).data('nomor');
            
            $('#nomorPeminjaman').text(nomor);
            $('#formHapus').attr('action', "{{ url('peminjaman-peralatan') }}/" + id);
            
            var modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));
            modalHapus.show();
        });
    });
</script>
@endpush

@endsection
