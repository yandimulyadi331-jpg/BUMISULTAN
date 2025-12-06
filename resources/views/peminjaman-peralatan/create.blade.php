@extends('layouts.app')
@section('titlepage', 'Tambah Peminjaman Peralatan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peminjaman-peralatan.index') }}">Peminjaman Peralatan</a> / Tambah</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="ti ti-plus me-2"></i> Form Peminjaman Peralatan</h4>
            </div>
            <form action="{{ route('peminjaman-peralatan.store') }}" method="POST" id="formPeminjaman">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Peralatan <span class="text-danger">*</span></label>
                            <select name="peralatan_id" id="peralatan_id" class="form-select @error('peralatan_id') is-invalid @enderror" required>
                                <option value="">Pilih Peralatan</option>
                                @foreach($peralatan as $item)
                                    <option value="{{ $item->id }}" data-stok="{{ $item->stok_tersedia }}" data-satuan="{{ $item->satuan }}" {{ old('peralatan_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_peralatan }} ({{ $item->kode_peralatan }}) - Stok: {{ $item->stok_tersedia }} {{ $item->satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peralatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="infoStok"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                            <input type="text" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror" 
                                value="{{ old('nama_peminjam') }}" placeholder="Masukkan nama peminjam" required>
                            @error('nama_peminjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Dipinjam <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_dipinjam" id="jumlah_dipinjam" class="form-control @error('jumlah_dipinjam') is-invalid @enderror" 
                                value="{{ old('jumlah_dipinjam', 1) }}" min="1" required>
                            @error('jumlah_dipinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                            <input type="text" name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                value="{{ old('keperluan') }}" placeholder="Contoh: Membersihkan ruangan" required>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" 
                                value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Kondisi Saat Dipinjam</label>
                            <textarea name="kondisi_saat_dipinjam" class="form-control @error('kondisi_saat_dipinjam') is-invalid @enderror" 
                                rows="2" placeholder="Contoh: Baik, tidak ada kerusakan">{{ old('kondisi_saat_dipinjam', 'Baik') }}</textarea>
                            @error('kondisi_saat_dipinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                rows="2" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('peminjaman-peralatan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Peminjaman
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('myscript')
<script>
    $(document).ready(function() {
        // Update info stok saat peralatan dipilih
        $('#peralatan_id').change(function() {
            var stok = $(this).find(':selected').data('stok');
            var satuan = $(this).find(':selected').data('satuan');
            
            if (stok !== undefined) {
                $('#infoStok').text('Stok tersedia: ' + stok + ' ' + satuan);
                $('#jumlah_dipinjam').attr('max', stok);
            } else {
                $('#infoStok').text('');
                $('#jumlah_dipinjam').removeAttr('max');
            }
        });

        // Validasi jumlah
        $('#formPeminjaman').submit(function(e) {
            var stok = $('#peralatan_id').find(':selected').data('stok');
            var jumlah = parseInt($('#jumlah_dipinjam').val());
            
            if (jumlah > stok) {
                e.preventDefault();
                alert('Jumlah peminjaman melebihi stok tersedia!');
                return false;
            }
        });
    });
</script>
@endpush

@endsection
