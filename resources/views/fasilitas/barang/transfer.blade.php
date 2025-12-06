@extends('layouts.app')
@section('titlepage', 'Transfer Barang')

@section('content')
@section('navigasi')
    <span>
        <a href="{{ route('gedung.index') }}">Manajemen Gedung</a> / 
        <a href="{{ route('ruangan.index', Crypt::encrypt($gedung->id)) }}">{{ $gedung->nama_gedung }}</a> / 
        <a href="{{ route('barang.index', [
            'gedung_id' => Crypt::encrypt($gedung->id),
            'ruangan_id' => Crypt::encrypt($ruangan->id)
        ]) }}">{{ $ruangan->nama_ruangan }}</a> / 
        Transfer Barang
    </span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transfer Barang</h5>
                    <a href="{{ route('barang.index', [
                        'gedung_id' => Crypt::encrypt($gedung->id),
                        'ruangan_id' => Crypt::encrypt($ruangan->id)
                    ]) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Barang -->
                <div class="alert alert-info">
                    <h6 class="mb-2"><strong>Informasi Barang</strong></h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td width="30%">Kode Barang</td>
                            <td>: {{ $barang->kode_barang }}</td>
                        </tr>
                        <tr>
                            <td>Nama Barang</td>
                            <td>: {{ $barang->nama_barang }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi Saat Ini</td>
                            <td>: {{ $gedung->nama_gedung }} - {{ $ruangan->nama_ruangan }}</td>
                        </tr>
                        <tr>
                            <td>Stok Tersedia</td>
                            <td>: <strong>{{ $barang->jumlah }} {{ $barang->satuan }}</strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Form Transfer -->
                <form action="{{ route('barang.prosesTransfer', [
                    'gedung_id' => Crypt::encrypt($gedung->id),
                    'ruangan_id' => Crypt::encrypt($ruangan->id),
                    'id' => Crypt::encrypt($barang->id)
                ]) }}" id="formTransferBarang" method="POST">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-door me-1"></i> Ruangan Tujuan <span class="text-danger">*</span></label>
                        <select name="ruangan_tujuan_id" id="ruangan_tujuan_id" class="form-select">
                            <option value="">-- Pilih Ruangan Tujuan --</option>
                            @foreach ($all_ruangan as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->gedung->nama_gedung }} - {{ $r->nama_ruangan }} 
                                    @if($r->lantai) (Lantai {{ $r->lantai }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <x-input-with-icon icon="ti ti-number" label="Jumlah Transfer" name="jumlah_transfer" type="number" 
                        min="1" max="{{ $barang->jumlah }}" required />
                    
                    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Transfer" name="tanggal_transfer" type="date" 
                        value="{{ date('Y-m-d') }}" required />
                    
                    <x-input-with-icon icon="ti ti-user" label="Petugas" name="petugas" />
                    
                    <x-textarea label="Keterangan Transfer" name="keterangan" />
                    
                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="ti ti-transfer me-1"></i>
                            Proses Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
    <script>
        $("#formTransferBarang").submit(function(e) {
            var ruangan_tujuan_id = $("#ruangan_tujuan_id").val();
            var jumlah_transfer = $("#jumlah_transfer").val();
            var tanggal_transfer = $("#tanggal_transfer").val();
            var max_stok = {{ $barang->jumlah }};

            if (ruangan_tujuan_id == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Ruangan Tujuan Harus Dipilih !',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#ruangan_tujuan_id").focus();
                });
                return false;
            } else if (jumlah_transfer == "" || jumlah_transfer <= 0) {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Jumlah Transfer Harus Diisi dan Lebih dari 0 !',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#jumlah_transfer").focus();
                });
                return false;
            } else if (parseInt(jumlah_transfer) > max_stok) {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Jumlah Transfer Melebihi Stok Tersedia (' + max_stok + ' {{ $barang->satuan }}) !',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#jumlah_transfer").focus();
                });
                return false;
            } else if (tanggal_transfer == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal Transfer Harus Diisi !',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    $("#tanggal_transfer").focus();
                });
                return false;
            }

            // Konfirmasi transfer
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Transfer',
                text: 'Apakah Anda yakin akan mentransfer ' + jumlah_transfer + ' {{ $barang->satuan }} barang ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Transfer',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).unbind('submit').submit();
                }
            });
        });
    </script>
@endpush
