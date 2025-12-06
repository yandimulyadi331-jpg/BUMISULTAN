@extends('layouts.app')
@section('titlepage', 'Detail Pengunjung')

@section('content')
@section('navigasi')
    <span><a href="{{ route('pengunjung.index') }}">Manajemen Pengunjung</a> / Detail</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Pengunjung</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($pengunjung->foto)
                            <img src="{{ Storage::url($pengunjung->foto) }}" 
                                alt="Foto Pengunjung" 
                                class="img-fluid rounded shadow-sm mb-3"
                                style="max-height: 400px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                style="height: 300px;">
                                <i class="fa fa-user" style="font-size: 100px;"></i>
                            </div>
                        @endif
                        
                        <div class="mt-3">
                            <span class="badge bg-{{ $pengunjung->status == 'checkin' ? 'success' : 'secondary' }} p-3" 
                                style="font-size: 16px;">
                                {{ strtoupper($pengunjung->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $pengunjung->nama_lengkap }}</h3>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Kode Pengunjung</strong></td>
                                <td>: {{ $pengunjung->kode_pengunjung }}</td>
                            </tr>
                            <tr>
                                <td><strong>Instansi/Perusahaan</strong></td>
                                <td>: {{ $pengunjung->instansi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Identitas</strong></td>
                                <td>: {{ $pengunjung->no_identitas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Telepon</strong></td>
                                <td>: {{ $pengunjung->no_telepon }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $pengunjung->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: {{ $pengunjung->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cabang</strong></td>
                                <td>: {{ $pengunjung->cabang->nama_cabang ?? '-' }}</td>
                            </tr>
                        </table>

                        <hr>

                        <h5 class="mb-3">Informasi Kunjungan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Keperluan</strong></td>
                                <td>: {{ $pengunjung->keperluan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Bertemu Dengan</strong></td>
                                <td>: {{ $pengunjung->bertemu_dengan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu Check-In</strong></td>
                                <td>: {{ $pengunjung->waktu_checkin->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu Check-Out</strong></td>
                                <td>: 
                                    @if($pengunjung->waktu_checkout)
                                        {{ $pengunjung->waktu_checkout->format('d/m/Y H:i:s') }}
                                    @else
                                        <span class="badge bg-warning">Belum Check-Out</span>
                                    @endif
                                </td>
                            </tr>
                            @if($pengunjung->waktu_checkout)
                            <tr>
                                <td><strong>Durasi Kunjungan</strong></td>
                                <td>: 
                                    @php
                                        $durasi = $pengunjung->waktu_checkin->diff($pengunjung->waktu_checkout);
                                        $jam = $durasi->h;
                                        $menit = $durasi->i;
                                    @endphp
                                    {{ $jam }} jam {{ $menit }} menit
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Dari Jadwal</strong></td>
                                <td>: 
                                    @if($pengunjung->jadwalPengunjung)
                                        {{ $pengunjung->jadwalPengunjung->kode_jadwal }}
                                    @else
                                        <span class="badge bg-secondary">Walk-In</span>
                                    @endif
                                </td>
                            </tr>
                            @if($pengunjung->catatan)
                            <tr>
                                <td><strong>Catatan</strong></td>
                                <td>: {{ $pengunjung->catatan }}</td>
                            </tr>
                            @endif
                        </table>

                        <div class="mt-4">
                            <a href="{{ route('pengunjung.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-2"></i> Kembali
                            </a>
                            @if($pengunjung->status == 'checkin')
                                <button type="button" class="btn btn-warning btn-checkout-detail" 
                                    data-id="{{ $pengunjung->id }}" 
                                    data-nama="{{ $pengunjung->nama_lengkap }}">
                                    <i class="fa fa-sign-out me-2"></i> Check-Out
                                </button>
                            @endif
                            <a href="{{ route('pengunjung.edit', $pengunjung->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit me-2"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Checkout confirmation
        $('.btn-checkout-detail').click(function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            
            Swal.fire({
                title: 'Konfirmasi Check-Out',
                html: `Apakah Anda yakin ingin check-out pengunjung:<br><strong>${nama}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-sign-out me-1"></i> Ya, Check-Out',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': '/pengunjung/' + id + '/checkout'
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
