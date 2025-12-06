@extends('layouts.app')

@section('title', 'Tambah Distribusi Hadiah - MASAR')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('masar.index') }}">MASAR</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('masar.distribusi.index') }}">Distribusi Hadiah</a>
            </li>
            <li class="breadcrumb-item active">Tambah Distribusi</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="fw-bold">Tambah Distribusi Hadiah Baru</h4>
            <p class="text-muted">Catat distribusi hadiah kepada jamaah</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="form-distribusi" method="POST" action="{{ route('masar.distribusi.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hadiah_id" class="form-label">Hadiah <span class="text-danger">*</span></label>
                                    <select class="form-select @error('hadiah_id') is-invalid @enderror" 
                                            id="hadiah_id" name="hadiah_id" required>
                                        <option value="">-- Pilih Hadiah --</option>
                                        @foreach($hadiah_list as $hadiah)
                                            <option value="{{ $hadiah->id }}" 
                                                    data-stok="{{ $hadiah->stok_tersedia }}"
                                                    data-nama="{{ $hadiah->nama_hadiah }}"
                                                    @if($hadiah_id == $hadiah->id) selected @endif>
                                                {{ $hadiah->nama_hadiah }} (Stok: {{ $hadiah->stok_tersedia }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hadiah_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_distribusi" class="form-label">Tanggal Distribusi <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_distribusi') is-invalid @enderror" 
                                           id="tanggal_distribusi" name="tanggal_distribusi" required value="{{ date('Y-m-d') }}">
                                    @error('tanggal_distribusi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                           id="jumlah" name="jumlah" min="1" required>
                                    <small class="text-muted d-block mt-1">Stok Tersedia: <span id="stok-tersedia">-</span></small>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="ukuran" class="form-label">Ukuran</label>
                                    <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                                           id="ukuran" name="ukuran" placeholder="S, M, L, XL">
                                    @error('ukuran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="metode_distribusi" class="form-label">Metode <span class="text-danger">*</span></label>
                                    <select class="form-control @error('metode_distribusi') is-invalid @enderror" 
                                            id="metode_distribusi" name="metode_distribusi" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="langsung">Langsung</option>
                                        <option value="undian">Undian</option>
                                        <option value="prestasi">Prestasi</option>
                                        <option value="kehadiran">Kehadiran</option>
                                    </select>
                                    @error('metode_distribusi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status_distribusi" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status_distribusi') is-invalid @enderror" 
                                            id="status_distribusi" name="status_distribusi" required>
                                        <option value="diterima" selected>Diterima</option>
                                        <option value="pending">Pending</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                    @error('status_distribusi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="penerima" class="form-label">Penerima <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('penerima') is-invalid @enderror" 
                                           id="penerima" name="penerima" placeholder="Nama penerima hadiah" required>
                                    @error('penerima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jamaah_id" class="form-label">Jamaah Yayasan MASAR (Opsional)</label>
                                    <select class="form-select @error('jamaah_id') is-invalid @enderror" 
                                            id="jamaah_id" name="jamaah_id">
                                        <option value="">-- Pilih Jamaah --</option>
                                        @foreach($jamaah_list as $jamaah)
                                            <option value="{{ $jamaah->id }}">{{ $jamaah->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('jamaah_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="petugas_distribusi" class="form-label">Petugas Distribusi</label>
                                    <input type="text" class="form-control @error('petugas_distribusi') is-invalid @enderror" 
                                           id="petugas_distribusi" name="petugas_distribusi" 
                                           placeholder="Nama petugas" value="{{ auth()->user()->name }}">
                                    @error('petugas_distribusi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" rows="3" 
                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-2"></i> Simpan Distribusi
                                </button>
                                <a href="{{ route('masar.distribusi.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Hadiah</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" id="info-hadiah" style="display: none;">
                        <small>
                            <strong>Hadiah Dipilih:</strong> <span id="hadiah-nama" class="d-block mt-2">-</span>
                            <strong class="d-block mt-2">Stok Tersedia:</strong> <span id="hadiah-stok">-</span>
                        </small>
                    </div>
                    <div class="text-muted" id="no-hadiah" style="display: block;">
                        <small>Pilih hadiah untuk melihat informasi stok</small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Petunjuk</h5>
                </div>
                <div class="card-body">
                    <ul class="small text-muted">
                        <li>Pilih hadiah yang akan didistribusikan</li>
                        <li>Tentukan jumlah hadiah yang akan dibagikan</li>
                        <li>Pilih metode distribusi (langsung, undian, prestasi, atau kehadiran)</li>
                        <li>Tentukan tanggal distribusi</li>
                        <li>Masukkan nama penerima hadiah</li>
                        <li>Opsional: pilih jamaah yang menerima hadiah</li>
                        <li>Klik Simpan Distribusi untuk mencatat data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Handle hadiah selection change
        $('#hadiah_id').on('change', function () {
            const stok = $(this).find('option:selected').data('stok');
            const nama = $(this).find('option:selected').data('nama');

            if ($(this).val()) {
                $('#hadiah-nama').text(nama);
                $('#hadiah-stok').text(stok || 0);
                $('#stok-tersedia').text(stok || 0);
                $('#info-hadiah').show();
                $('#no-hadiah').hide();
            } else {
                $('#info-hadiah').hide();
                $('#no-hadiah').show();
                $('#stok-tersedia').text('-');
            }
        });

        // Form submission
        $('#form-distribusi').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                hadiah_id: $('#hadiah_id').val(),
                tanggal_distribusi: $('#tanggal_distribusi').val(),
                jumlah: $('#jumlah').val(),
                ukuran: $('#ukuran').val(),
                metode_distribusi: $('#metode_distribusi').val(),
                status_distribusi: $('#status_distribusi').val(),
                penerima: $('#penerima').val(),
                jamaah_id: $('#jamaah_id').val(),
                petugas_distribusi: $('#petugas_distribusi').val(),
                keterangan: $('#keterangan').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: '{{ route("masar.distribusi.store") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route("masar.distribusi.index") }}';
                        });
                    }
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON?.errors || {};
                    let errorMessage = '';

                    $.each(errors, function (key, value) {
                        errorMessage += '• ' + value[0] + '\n';
                    });

                    Swal.fire({
                        title: 'Validasi Gagal!',
                        text: errorMessage || 'Terjadi kesalahan saat menyimpan data',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endsection
