@extends('layouts.app')
@section('titlepage', 'Master Data Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Master Data</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-primary btnTambah">
                            <i class="ti ti-plus me-2"></i> Tambah Inventaris
                        </button>
                        <a href="{{ route('inventaris.export-pdf') }}" class="btn btn-danger" target="_blank">
                            <i class="ti ti-file-pdf me-2"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('inventaris.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <x-input-with-icon label="Cari Nama / Kode" value="{{ Request('search') }}" 
                                        name="search" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kategori" class="form-select">
                                            <option value="">Semua Kategori</option>
                                            <option value="elektronik" {{ Request('kategori') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                                            <option value="furnitur" {{ Request('kategori') == 'furnitur' ? 'selected' : '' }}>Furnitur</option>
                                            <option value="kendaraan" {{ Request('kategori') == 'kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                            <option value="alat_kantor" {{ Request('kategori') == 'alat_kantor' ? 'selected' : '' }}>Alat Kantor</option>
                                            <option value="lainnya" {{ Request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="tersedia" {{ Request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="dipinjam" {{ Request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                            <option value="rusak" {{ Request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                            <option value="maintenance" {{ Request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            <option value="hilang" {{ Request('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="kondisi" class="form-select">
                                            <option value="">Semua Kondisi</option>
                                            <option value="baik" {{ Request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak_ringan" {{ Request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                            <option value="rusak_berat" {{ Request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa fa-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Tersedia</th>
                                <th>Status</th>
                                <th>Kondisi</th>
                                <th>Lokasi</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventaris as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($inventaris->currentPage() - 1) * $inventaris->perPage() }}</td>
                                <td><strong>{{ $item->kode_inventaris }}</strong></td>
                                <td>
                                    <strong>{{ $item->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($item->deskripsi, 30) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($item->kategori) }}</span>
                                </td>
                                <td>
                                    <strong>{{ $item->jumlah }}</strong> {{ $item->satuan }}
                                </td>
                                <td>
                                    <strong class="text-success">{{ $item->jumlahTersedia() }}</strong> {{ $item->satuan }}
                                    @php
                                        $tersedia = $item->jumlahTersedia();
                                        $dipinjam = $item->jumlah - $tersedia;
                                    @endphp
                                    @if($dipinjam > 0)
                                        <br><small class="text-muted">
                                            <span class="text-warning">Dipinjam: {{ $dipinjam }}</span>
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($item->status == 'dipinjam')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @elseif($item->status == 'rusak')
                                        <span class="badge bg-danger">Rusak</span>
                                    @elseif($item->status == 'maintenance')
                                        <span class="badge bg-info">Maintenance</span>
                                    @else
                                        <span class="badge bg-dark">Hilang</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->kondisi == 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif($item->kondisi == 'rusak_ringan')
                                        <span class="badge bg-warning">Rusak Ringan</span>
                                    @else
                                        <span class="badge bg-danger">Rusak Berat</span>
                                    @endif
                                </td>
                                <td>{{ $item->lokasi }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('inventaris.detail', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-success btnEdit" data-id="{{ $item->id }}" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST" class="deleteform" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-confirm" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data inventaris</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $inventaris->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="Inventaris" />

<!-- Modal Detail untuk nested modal -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-title-detail" id="modalDetailLabel">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadmodalDetail">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(document).ready(function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error") }}',
                timer: 3000
            });
        @endif

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
            </div>`);
        }

        // Tambah Baru
        $('.btnTambah').click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Inventaris Baru");
            $("#loadmodal").load('/inventaris/create');
        });

        // Detail
        $('.btnDetail').click(function(e) {
            e.preventDefault();
            loading();
            var id = $(this).data('id');
            $("#modal").modal("show");
            $(".modal-title").text("Detail Inventaris");
            $("#loadmodal").load('/inventaris/' + id);
        });

        // Edit
        $('.btnEdit').click(function(e) {
            e.preventDefault();
            loading();
            var id = $(this).data('id');
            $("#modal").modal("show");
            $(".modal-title").text("Edit Inventaris");
            $("#loadmodal").load('/inventaris/' + id + '/edit');
        });

        // Pinjam
        $('.btnPinjam').click(function(e) {
            e.preventDefault();
            loading();
            var id = $(this).data('id');
            $("#modal").modal("show");
            $(".modal-title").text("Form Peminjaman Inventaris");
            $("#loadmodal").load('/peminjaman-inventaris/create?inventaris_id=' + id);
        });

        // Kembali
        $('.btnKembali').click(function(e) {
            e.preventDefault();
            loading();
            var id = $(this).data('id');
            $("#modal").modal("show");
            $(".modal-title").text("Form Pengembalian Inventaris");
            $("#loadmodal").load('/pengembalian-inventaris/create?inventaris_id=' + id);
        });

        // History - redirect ke halaman penuh
        $('.btnHistory').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            window.location.href = '/history-inventaris?inventaris_id=' + id;
        });

        // Delete confirmation
        $('.delete-confirm').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus inventaris ini?",
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

        // Handle form submit di modal (untuk form Edit)
        $(document).on('submit', '#formInventarisEdit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var formData = new FormData(this);
            var url = form.attr('action');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#modal").modal("hide");
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data inventaris berhasil diupdate',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON?.errors;
                    if (errors) {
                        var errorMsg = Object.values(errors).flat().join('\n');
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengupdate data'
                        });
                    }
                }
            });
        });

        // Handle form submit di modal (untuk form Create)
        $(document).on('submit', '#formInventarisCreate', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var formData = new FormData(this);
            var url = form.attr('action');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#modal").modal("hide");
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data inventaris berhasil ditambahkan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON?.errors;
                    if (errors) {
                        var errorMsg = Object.values(errors).flat().join('\n');
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menambahkan data'
                        });
                    }
                }
            });
        });

        // Handle form submit Peminjaman
        $(document).on('submit', '#formPeminjamanCreate', function(e) {
            e.preventDefault();
            console.log('Form peminjaman submitted');
            console.log('SignaturePad Peminjaman Peminjam:', window.signaturePadPeminjamanPeminjam);
            console.log('SignaturePad Peminjaman Petugas:', window.signaturePadPeminjamanPetugas);
            
            // Validasi dan capture tanda tangan peminjam
            if (window.signaturePadPeminjamanPeminjam) {
                console.log('Is Peminjam empty?', window.signaturePadPeminjamanPeminjam.isEmpty());
                
                if (window.signaturePadPeminjamanPeminjam.isEmpty()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Tanda tangan peminjam harus diisi!'
                    });
                    return false;
                }
                
                const ttdData = window.signaturePadPeminjamanPeminjam.toDataURL();
                console.log('TTD Peminjam length:', ttdData.length);
                $('#ttdPeminjam').val(ttdData);
            } else {
                console.error('SignaturePad Peminjaman Peminjam not initialized!');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Signature pad belum diinisialisasi. Silakan tutup dan buka kembali form.'
                });
                return false;
            }
            
            // Capture tanda tangan petugas jika ada
            if (window.signaturePadPeminjamanPetugas && !window.signaturePadPeminjamanPetugas.isEmpty()) {
                const ttdPetugasData = window.signaturePadPeminjamanPetugas.toDataURL();
                console.log('TTD Petugas length:', ttdPetugasData.length);
                $('#ttdPetugas').val(ttdPetugasData);
            }
            
            var form = $(this);
            var formData = new FormData(this);
            var url = form.attr('action');
            
            // Log form data for debugging
            console.log('Form URL:', url);
            for (let [key, value] of formData.entries()) {
                if (key === 'ttd_peminjam' || key === 'ttd_petugas') {
                    console.log(key + ': [base64 data length: ' + (value ? value.length : 0) + ']');
                } else {
                    console.log(key + ':', value);
                }
            }
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Success response:', response);
                    $("#modal").modal("hide");
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Pengajuan peminjaman berhasil dibuat',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    console.error('Error response:', xhr);
                    console.error('Status:', xhr.status);
                    console.error('Response:', xhr.responseJSON);
                    
                    var errors = xhr.responseJSON?.errors;
                    var errorMsg = xhr.responseJSON?.message;
                    
                    if (errors) {
                        errorMsg = Object.values(errors).flat().join('\n');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg || 'Terjadi kesalahan saat mengajukan peminjaman'
                    });
                }
            });
        });

        // Handle form submit Pengembalian
        $(document).on('submit', '#formPengembalianCreate', function(e) {
            e.preventDefault();
            console.log('Form pengembalian submitted');
            console.log('SignaturePad Peminjam:', window.signaturePadPeminjam);
            console.log('SignaturePad Petugas:', window.signaturePadPetugas);
            
            // Validasi dan capture tanda tangan peminjam
            if (window.signaturePadPeminjam) {
                console.log('Is Peminjam empty?', window.signaturePadPeminjam.isEmpty());
                
                if (window.signaturePadPeminjam.isEmpty()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Tanda tangan peminjam harus diisi!'
                    });
                    return false;
                }
                
                const ttdData = window.signaturePadPeminjam.toDataURL();
                console.log('TTD Peminjam length:', ttdData.length);
                $('#ttdPeminjam').val(ttdData);
            } else {
                console.error('SignaturePad Peminjam not initialized!');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Signature pad belum diinisialisasi. Silakan tutup dan buka kembali form.'
                });
                return false;
            }
            
            // Capture tanda tangan petugas jika ada
            if (window.signaturePadPetugas && !window.signaturePadPetugas.isEmpty()) {
                const ttdPetugasData = window.signaturePadPetugas.toDataURL();
                console.log('TTD Petugas length:', ttdPetugasData.length);
                $('#ttdPetugas').val(ttdPetugasData);
            }
            
            var form = $(this);
            var formData = new FormData(this);
            var url = form.attr('action');
            
            // Log form data for debugging
            console.log('Form URL:', url);
            for (let [key, value] of formData.entries()) {
                if (key === 'ttd_peminjam' || key === 'ttd_petugas') {
                    console.log(key + ': [base64 data length: ' + (value ? value.length : 0) + ']');
                } else {
                    console.log(key + ':', value);
                }
            }
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Success response:', response);
                    $("#modal").modal("hide");
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Pengembalian barang berhasil diproses',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    console.error('Error response:', xhr);
                    console.error('Status:', xhr.status);
                    console.error('Response:', xhr.responseJSON);
                    
                    var errors = xhr.responseJSON?.errors;
                    var errorMsg = xhr.responseJSON?.message;
                    
                    if (errors) {
                        errorMsg = Object.values(errors).flat().join('\n');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg || 'Terjadi kesalahan saat memproses pengembalian'
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection
