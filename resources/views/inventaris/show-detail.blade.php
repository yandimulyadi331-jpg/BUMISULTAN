@extends('layouts.app')
@section('titlepage', 'Detail Inventaris: ' . $inventaris->nama_barang)

@section('content')
@section('navigasi')
    <span><a href="{{ route('inventaris.index') }}">Master Data Inventaris</a> / Detail</span>
@endsection

<div class="row">
    <div class="col-12">
        <!-- Header & Info Card -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            @if($inventaris->foto)
                                <img src="{{ Storage::url($inventaris->foto) }}" class="me-3" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div class="me-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: #f0f0f0; border-radius: 8px;">
                                    <i class="ti ti-package" style="font-size: 48px; color: #999;"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="mb-1">{{ $inventaris->nama_barang }}</h3>
                                <h5 class="text-muted mb-2">{{ $inventaris->kode_inventaris }}</h5>
                                <div>
                                    <span class="badge bg-info">{{ ucfirst($inventaris->kategori) }}</span>
                                    @if($inventaris->merk)
                                        <span class="badge bg-secondary">{{ $inventaris->merk }}</span>
                                    @endif
                                    @if($inventaris->tracking_per_unit)
                                        <span class="badge bg-primary"><i class="ti ti-qrcode me-1"></i>Multi-Unit Tracking</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" style="width: 150px;">Deskripsi</td>
                                        <td>: {{ $inventaris->deskripsi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Lokasi</td>
                                        <td>: {{ $inventaris->lokasi_penyimpanan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Cabang</td>
                                        <td>: {{ $inventaris->cabang->nama_cabang ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" style="width: 150px;">Tanggal Perolehan</td>
                                        <td>: {{ $inventaris->tanggal_perolehan ? $inventaris->tanggal_perolehan->format('d M Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Harga Perolehan</td>
                                        <td>: Rp {{ number_format($inventaris->harga_perolehan ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Dibuat Oleh</td>
                                        <td>: {{ $inventaris->createdBy->name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="text-end mb-3">
                            <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="#" class="btn btn-success btnEdit" data-id="{{ $inventaris->id }}">
                                <i class="ti ti-edit me-1"></i> Edit
                            </a>
                        </div>
                        
                        <!-- Stats Cards -->
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body p-3">
                                        <h2 class="mb-0">{{ $stats['total_units'] }}</h2>
                                        <small>Total Unit</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body p-3">
                                        <h2 class="mb-0">{{ $stats['tersedia'] }}</h2>
                                        <small>Tersedia</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body p-3">
                                        <h2 class="mb-0">{{ $stats['dipinjam'] }}</h2>
                                        <small>Dipinjam</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-danger text-white">
                                    <div class="card-body p-3">
                                        <h2 class="mb-0">{{ $stats['rusak'] }}</h2>
                                        <small>Rusak</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="inventarisTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="units-tab" data-bs-toggle="tab" data-bs-target="#units" type="button" role="tab">
                            <i class="ti ti-list-check me-1"></i> Detail Units
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content" id="inventarisTabContent">
                    <!-- Tab: Detail Units -->
                    <div class="tab-pane fade show active" id="units" role="tabpanel">
                        @include('inventaris.partials.tab-units')
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
<x-modal-form id="modalUnit" size="modal-lg" show="loadmodalUnit" title="" />

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Edit Inventaris
        $('.btnEdit').click(function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#modal').modal('show');
            $('.modal-title').text('Edit Inventaris');
            $('#loadmodal').load('/inventaris/' + id + '/edit');
        });

        // Tambah Unit (jika tracking per unit)
        $(document).on('click', '.btnTambahUnit', function(e) {
            e.preventDefault();
            let inventarisId = '{{ $inventaris->id }}';
            $('#modalUnit').modal('show');
            $('.modal-title').text('Tambah Unit Baru');
            $('#loadmodalUnit').load('/inventaris/' + inventarisId + '/units/create');
        });

        // Edit Unit
        $(document).on('click', '.btnEditUnit', function(e) {
            e.preventDefault();
            let inventarisId = '{{ $inventaris->id }}';
            let unitId = $(this).data('id');
            $('#modalUnit').modal('show');
            $('.modal-title').text('Edit Unit');
            $('#loadmodalUnit').load('/inventaris/' + inventarisId + '/units/' + unitId + '/edit');
        });

        // History Unit - Buka di halaman baru
        $(document).on('click', '.btnHistoryUnit', function(e) {
            e.preventDefault();
            let inventarisId = '{{ $inventaris->id }}';
            let unitId = $(this).data('id');
            
            // Redirect ke halaman history langsung
            window.location.href = '/inventaris/' + inventarisId + '/units/' + unitId + '/history';
        });

        // Delete Unit
        $(document).on('click', '.btnDeleteUnit', function(e) {
            e.preventDefault();
            let inventarisId = '{{ $inventaris->id }}';
            let unitId = $(this).data('id');
            let kodeUnit = $(this).data('kode');
            
            Swal.fire({
                title: 'Hapus Unit?',
                text: `Yakin ingin menghapus unit ${kodeUnit}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/inventaris/' + inventarisId + '/units/' + unitId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        // Peminjaman Unit
        $(document).on('click', '.btnPinjamUnit', function(e) {
            e.preventDefault();
            let inventarisId = '{{ $inventaris->id }}';
            let unitId = $(this).data('id');
            let kodeUnit = $(this).data('kode');
            
            console.log('Loading peminjaman form for unit:', unitId);
            const url = '/inventaris/' + inventarisId + '/units/' + unitId + '/peminjaman';
            console.log('URL:', url);
            
            // Load modal form
            $('#modalPeminjamanUnit').modal('show');
            $('#loadmodalPeminjamanUnit').load(url, function(response, status, xhr) {
                if (status == "error") {
                    console.error('Load error:', xhr.status, xhr.statusText);
                    $('#loadmodalPeminjamanUnit').html('<div class="alert alert-danger">Error: ' + xhr.statusText + '</div>');
                } else {
                    console.log('Form loaded successfully');
                    // Set default date
                    const today = new Date().toISOString().split('T')[0];
                    $('input[name="tanggal_pinjam"]').val(today);
                }
            });
        });

        // Pengembalian Unit
        $(document).on('click', '.btnKembalikanUnit', function(e) {
            e.preventDefault();
            let inventarisId = '{{ $inventaris->id }}';
            let unitId = $(this).data('id');
            let kodeUnit = $(this).data('kode');
            let peminjam = $(this).data('peminjam');
            let tglPinjam = $(this).data('tglpinjam');
            
            // Fallback untuk data yang tidak tersedia
            if (!peminjam || peminjam === '' || peminjam === 'null') {
                peminjam = 'Unknown';
            }
            if (!tglPinjam || tglPinjam === '' || tglPinjam === 'null') {
                tglPinjam = '-';
            }
            
            // Show form pengembalian di modal
            $('#modalUnit').modal('show');
            $('.modal-title').text('Form Pengembalian Unit: ' + kodeUnit);
            $('#loadmodalUnit').html(`
                <form id="formPengembalianUnit">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="unit_id" value="${unitId}">
                    
                    <div class="alert alert-info mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small><strong>Kode Unit:</strong> ${kodeUnit}</small>
                            </div>
                            <div class="col-md-6">
                                <small><strong>Peminjam:</strong> ${peminjam}</small>
                            </div>
                            <div class="col-md-12 mt-1">
                                <small><strong>Tgl Pinjam:</strong> ${tglPinjam}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kondisi Unit <span class="text-danger">*</span></label>
                        <select name="kondisi" class="form-select" required>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                        <small class="text-muted">Pilih kondisi unit saat dikembalikan</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Pengembalian</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan kondisi, kerusakan, atau hal lainnya..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Denda Keterlambatan</label>
                        <input type="number" name="denda" class="form-control" placeholder="0" min="0">
                        <small class="text-muted">Kosongkan jika tidak ada denda</small>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Proses Pengembalian
                        </button>
                    </div>
                </form>
            `);
            
            // Set tanggal hari ini
            const today = new Date().toISOString().split('T')[0];
            $('input[name="tanggal_kembali"]').val(today);
            
            // Handle form submit
            $(document).off('submit', '#formPengembalianUnit').on('submit', '#formPengembalianUnit', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Memproses...');
                
                $.ajax({
                    url: '/inventaris/' + inventarisId + '/units/' + unitId + '/kembalikan',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#modalUnit').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Proses Pengembalian');
                        let errorMsg = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMsg
                        });
                    }
                });
            });
        });

        // Delete confirmations
        $('.delete-confirm').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
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
