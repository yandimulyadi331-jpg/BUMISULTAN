@extends('layouts.app')

@section('title', 'Edit Distribusi Hadiah - MASAR')

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
            <li class="breadcrumb-item active">Edit Distribusi</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="fw-bold">Edit Distribusi Hadiah</h4>
            <p class="text-muted">Ubah data distribusi hadiah</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="form-distribusi" method="PUT" action="{{ route('masar.distribusi.update', encrypt($distribusi->id)) }}">
                        @csrf
                        @method('PUT')

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
                                                    @if($distribusi->hadiah_id == $hadiah->id) selected @endif>
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
                                           id="tanggal_distribusi" name="tanggal_distribusi" required 
                                           value="{{ $distribusi->tanggal_distribusi->format('Y-m-d') }}">
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
                                           id="jumlah" name="jumlah" min="1" required value="{{ $distribusi->jumlah }}">
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
                                           id="ukuran" name="ukuran" placeholder="S, M, L, XL" value="{{ $distribusi->ukuran }}">
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
                                        <option value="langsung" @if($distribusi->metode_distribusi == 'langsung') selected @endif>Langsung</option>
                                        <option value="undian" @if($distribusi->metode_distribusi == 'undian') selected @endif>Undian</option>
                                        <option value="prestasi" @if($distribusi->metode_distribusi == 'prestasi') selected @endif>Prestasi</option>
                                        <option value="kehadiran" @if($distribusi->metode_distribusi == 'kehadiran') selected @endif>Kehadiran</option>
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
                                        <option value="diterima" @if($distribusi->status_distribusi == 'diterima') selected @endif>Diterima</option>
                                        <option value="pending" @if($distribusi->status_distribusi == 'pending') selected @endif>Pending</option>
                                        <option value="ditolak" @if($distribusi->status_distribusi == 'ditolak') selected @endif>Ditolak</option>
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
                                           id="penerima" name="penerima" placeholder="Nama penerima hadiah" required 
                                           value="{{ $distribusi->penerima }}">
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
                                            <option value="{{ $jamaah->id }}" @if($distribusi->jamaah_id == $jamaah->id) selected @endif>
                                                {{ $jamaah->nama }}
                                            </option>
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
                                           placeholder="Nama petugas" value="{{ $distribusi->petugas_distribusi }}">
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
                                      placeholder="Keterangan tambahan (opsional)">{{ $distribusi->keterangan }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-2"></i> Simpan Perubahan
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
                    <h5 class="mb-0">Informasi Distribusi</h5>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <p class="mb-2">
                            <strong>Nomor Distribusi:</strong><br>
                            {{ $distribusi->nomor_distribusi }}
                        </p>
                        <p class="mb-2">
                            <strong>Dibuat pada:</strong><br>
                            {{ $distribusi->created_at->format('d/m/Y H:i') }}
                        </p>
                        @if($distribusi->updated_at != $distribusi->created_at)
                        <p class="mb-2">
                            <strong>Diubah pada:</strong><br>
                            {{ $distribusi->updated_at->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-3">
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Initialize hadiah info on page load
        $('#hadiah_id').trigger('change');

        // Handle hadiah selection change
        $('#hadiah_id').on('change', function () {
            const stok = $(this).find('option:selected').data('stok');
            const nama = $(this).find('option:selected').data('nama');

            if ($(this).val()) {
                $('#hadiah-nama').text(nama);
                $('#hadiah-stok').text(stok || 0);
                $('#stok-tersedia').text(stok || 0);
                $('#info-hadiah').show();
            } else {
                $('#info-hadiah').hide();
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
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };

            $.ajax({
                url: '{{ route("masar.distribusi.update", encrypt($distribusi->id)) }}',
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
                        text: errorMessage || 'Terjadi kesalahan saat memperbarui data',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endsection
