@extends('layouts.app')
@section('titlepage', 'PERALATAN BS - Bumi Sultan')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / PERALATAN BS / Master Data</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-tools me-2"></i> PERALATAN BS (Bumi Sultan)</h4>
                    <div>
                        <button type="button" class="btn btn-light btn-sm btnTambahPeralatan">
                            <i class="ti ti-plus me-1"></i> Tambah Peralatan
                        </button>
                        <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-warning btn-sm">
                            <i class="ti ti-clipboard-list me-1"></i> Peminjaman
                        </a>
                        <a href="{{ route('peralatan.export-pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                        </a>
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
                        <form action="{{ route('peralatan.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-lg-3">
                                    <input type="text" name="search" class="form-control" 
                                        placeholder="🔍 Cari nama/kode peralatan..." 
                                        value="{{ Request('search') }}">
                                </div>
                                <div class="col-lg-2">
                                    <select name="kategori" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        <option value="Alat Kebersihan" {{ Request('kategori') == 'Alat Kebersihan' ? 'selected' : '' }}>Alat Kebersihan</option>
                                        <option value="Alat Tulis Kantor" {{ Request('kategori') == 'Alat Tulis Kantor' ? 'selected' : '' }}>Alat Tulis Kantor</option>
                                        <option value="Elektronik" {{ Request('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                        <option value="Peralatan Dapur" {{ Request('kategori') == 'Peralatan Dapur' ? 'selected' : '' }}>Peralatan Dapur</option>
                                        <option value="Peralatan Olahraga" {{ Request('kategori') == 'Peralatan Olahraga' ? 'selected' : '' }}>Peralatan Olahraga</option>
                                        <option value="Peralatan Taman" {{ Request('kategori') == 'Peralatan Taman' ? 'selected' : '' }}>Peralatan Taman</option>
                                        <option value="Perkakas" {{ Request('kategori') == 'Perkakas' ? 'selected' : '' }}>Perkakas</option>
                                        <option value="Keamanan" {{ Request('kategori') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                                        <option value="Lainnya" {{ Request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <select name="kondisi" class="form-select">
                                        <option value="">Semua Kondisi</option>
                                        <option value="baik" {{ Request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak ringan" {{ Request('kondisi') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak berat" {{ Request('kondisi') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" name="lokasi" class="form-control" 
                                        placeholder="Lokasi penyimpanan..." 
                                        value="{{ Request('lokasi') }}">
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('peralatan.index') }}" class="btn btn-secondary">
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
                                <th width="10%">Kode</th>
                                <th>Nama Peralatan</th>
                                <th>Kategori</th>
                                <th width="8%">Stok Tersedia</th>
                                <th width="8%">Dipinjam</th>
                                <th width="8%">Rusak</th>
                                <th width="10%">Lokasi</th>
                                <th width="10%">Kondisi</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peralatan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $peralatan->firstItem() + $index }}</td>
                                <td><strong>{{ $item->kode_peralatan }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/peralatan/' . $item->foto) }}" 
                                                alt="{{ $item->nama_peralatan }}" 
                                                class="rounded me-2 foto-peralatan" 
                                                style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                                data-foto="{{ asset('storage/peralatan/' . $item->foto) }}"
                                                data-nama="{{ $item->nama_peralatan }}"
                                                title="Klik untuk memperbesar">
                                        @else
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                style="width: 40px; height: 40px;">
                                                <i class="ti ti-tools text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $item->nama_peralatan }}</strong>
                                            @if($item->isStokMenipis())
                                                <br><span class="badge bg-danger">Stok Menipis!</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item->kategori }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">{{ $item->stok_tersedia }} {{ $item->satuan }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning fs-6">{{ $item->stok_dipinjam }} {{ $item->satuan }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger fs-6">{{ $item->stok_rusak }} {{ $item->satuan }}</span>
                                </td>
                                <td>{{ $item->lokasi_penyimpanan ?? '-' }}</td>
                                <td>
                                    @if($item->kondisi == 'baik')
                                        <span class="badge bg-success">{{ ucfirst($item->kondisi) }}</span>
                                    @elseif($item->kondisi == 'rusak ringan')
                                        <span class="badge bg-warning">{{ ucfirst($item->kondisi) }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($item->kondisi) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                            class="btn btn-info btn-sm btnDetail" 
                                            data-id="{{ $item->id }}"
                                            title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button type="button" 
                                            class="btn btn-warning btn-sm btnEdit" 
                                            data-id="{{ $item->id }}"
                                            title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" 
                                            class="btn btn-danger btn-sm btnHapus" 
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_peralatan }}"
                                            title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="ti ti-database-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Belum ada data peralatan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $peralatan->firstItem() ?? 0 }} - {{ $peralatan->lastItem() ?? 0 }} 
                        dari {{ $peralatan->total() }} data
                    </div>
                    <div>
                        {{ $peralatan->links() }}
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
                <h5 class="modal-title"><i class="ti ti-trash me-2"></i> Hapus Peralatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus peralatan <strong id="namaPeralatan"></strong>?</p>
                    <p class="text-danger"><i class="ti ti-alert-triangle me-1"></i> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-plus me-2"></i> Tambah Peralatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="tambahContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="ti ti-info-circle me-2"></i> Detail Peralatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="ti ti-edit me-2"></i> Edit Peralatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoTitle">Foto Peralatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFotoImage" src="" alt="" class="img-fluid rounded" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(document).ready(function() {
        // Foto Peralatan - Klik untuk zoom
        $('.foto-peralatan').click(function() {
            var foto = $(this).data('foto');
            var nama = $(this).data('nama');
            
            $('#modalFotoImage').attr('src', foto);
            $('#modalFotoImage').attr('alt', nama);
            $('#modalFotoTitle').text('Foto: ' + nama);
            
            var modalFoto = new bootstrap.Modal(document.getElementById('modalFoto'));
            modalFoto.show();
        });
        
        // Tambah Peralatan
        $('.btnTambahPeralatan').click(function() {
            $('#tambahContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
            modalTambah.show();
            
            $.ajax({
                url: "{{ route('peralatan.create') }}",
                type: 'GET',
                success: function(response) {
                    $('#tambahContent').html(response);
                },
                error: function() {
                    $('#tambahContent').html('<div class="alert alert-danger">Gagal memuat form</div>');
                }
            });
        });
        
        // Detail Peralatan
        $('.btnDetail').click(function() {
            var id = $(this).data('id');
            $('#detailContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
            modalDetail.show();
            
            $.ajax({
                url: "{{ url('peralatan') }}/" + id,
                type: 'GET',
                success: function(response) {
                    $('#detailContent').html(response);
                    
                    // Bind event untuk foto di detail modal
                    $('.foto-peralatan-detail').click(function() {
                        var foto = $(this).data('foto');
                        var nama = $(this).data('nama');
                        
                        $('#modalFotoImage').attr('src', foto);
                        $('#modalFotoImage').attr('alt', nama);
                        $('#modalFotoTitle').text('Foto: ' + nama);
                        
                        // Tutup modal detail dulu
                        bootstrap.Modal.getInstance(document.getElementById('modalDetail')).hide();
                        
                        // Tampilkan modal foto
                        var modalFoto = new bootstrap.Modal(document.getElementById('modalFoto'));
                        modalFoto.show();
                    });
                },
                error: function() {
                    $('#detailContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            });
        });
        
        // Edit Peralatan
        $('.btnEdit').click(function() {
            var id = $(this).data('id');
            $('#editContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            
            var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
            modalEdit.show();
            
            $.ajax({
                url: "{{ url('peralatan') }}/" + id + "/edit",
                type: 'GET',
                success: function(response) {
                    $('#editContent').html(response);
                },
                error: function() {
                    $('#editContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                }
            });
        });
        
        // Hapus Peralatan
        $('.btnHapus').click(function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            $('#namaPeralatan').text(nama);
            $('#formHapus').attr('action', "{{ url('peralatan') }}/" + id);
            
            var modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));
            modalHapus.show();
        });
    });
</script>
@endpush

@endsection
