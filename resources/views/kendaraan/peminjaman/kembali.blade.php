@extends('layouts.app')
@section('titlepage', 'Form Pengembalian Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Pengembalian</span>
@endsection

@section('content')
<style>
    .signature-pad {
        border: 2px solid #ddd;
        border-radius: 10px;
        background-color: white;
        cursor: crosshair;
    }
</style>

<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title text-white"><i class="ti ti-arrow-back me-2"></i>Form Pengembalian</h3>
            </div>
            <div class="card-body">
                <!-- Info Peminjaman -->
                <div class="alert alert-info">
                    <h4 class="alert-heading"><i class="ti ti-info-circle me-2"></i>Informasi Peminjaman</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Peminjam:</strong> {{ $peminjaman->nama_peminjam }}</p>
                            <p class="mb-1"><strong>Keperluan:</strong> {{ $peminjaman->keperluan }}</p>
                            <p class="mb-1"><strong>Waktu Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Estimasi Kembali:</strong> {{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Durasi Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->diffForHumans(null, true) }}</p>
                            @php
                                $isLate = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($peminjaman->estimasi_kembali));
                            @endphp
                            @if($isLate)
                                <p class="mb-1"><strong class="text-danger">STATUS: TERLAMBAT!</strong></p>
                            @else
                                <p class="mb-1"><strong class="text-success">STATUS: ON TIME</strong></p>
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ route('kendaraan.peminjaman.prosesKembali', Crypt::encrypt($peminjaman->id)) }}" method="POST" id="formPengembalian" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="form-label">
                                    <i class="ti ti-calendar-check me-2"></i>Waktu Kembali <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" name="waktu_kembali" class="form-control" 
                                       value="{{ date('Y-m-d\TH:i') }}" 
                                       min="{{ \Carbon\Carbon::parse($peminjaman->waktu_pinjam)->format('Y-m-d\TH:i') }}"
                                       required>
                                <small class="text-muted">Tentukan tanggal dan jam pengembalian kendaraan</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input-with-icon icon="ti ti-gauge" label="KM Akhir" name="km_akhir" type="number" />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status BBM Kembali</label>
                                <select name="status_bbm_kembali" class="form-select">
                                    <option value="Penuh">Penuh</option>
                                    <option value="3/4">3/4</option>
                                    <option value="1/2">1/2</option>
                                    <option value="1/4">1/4</option>
                                    <option value="Kosong">Kosong</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Kondisi Kendaraan</label>
                                <select name="kondisi_kendaraan" class="form-select">
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup">Cukup</option>
                                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Foto Kondisi Kendaraan</label>
                                <input type="file" name="foto_kembali" class="form-control" id="foto_kembali" accept="image/*">
                                <small class="text-muted">Upload foto kondisi kendaraan saat dikembalikan</small>
                                <div class="mt-2">
                                    <img id="preview_foto" style="max-width: 200px; display: none;" class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan kondisi atau catatan lainnya"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Lokasi GPS Saat Ini</label>
                                <input type="text" id="lokasi_display" class="form-control" readonly placeholder="Mencari lokasi...">
                                <input type="hidden" name="latitude_kembali" id="latitude_kembali">
                                <input type="hidden" name="longitude_kembali" id="longitude_kembali">
                                <small class="text-muted">GPS akan otomatis terdeteksi</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanda Tangan Digital <span class="text-danger">*</span></label>
                                <div class="border rounded p-2 bg-light">
                                    <canvas id="signature-pad" class="signature-pad" width="700" height="200"></canvas>
                                </div>
                                <input type="hidden" name="ttd_kembali" id="ttd_kembali">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-warning" id="btnClearSignature">
                                        <i class="ti ti-eraser me-1"></i>Hapus Tanda Tangan
                                    </button>
                                    <small class="text-muted ms-3">Tanda tangan di kotak putih di atas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($isLate)
                        <div class="alert alert-danger">
                            <strong><i class="ti ti-alert-triangle me-2"></i>PERHATIAN!</strong><br>
                            Kendaraan dikembalikan terlambat. Durasi keterlambatan: 
                            {{ \Carbon\Carbon::parse($peminjaman->estimasi_kembali)->diffForHumans() }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ti ti-check me-2"></i>Kembalikan Kendaraan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title text-white"><i class="ti ti-car me-2"></i>Informasi Kendaraan</h3>
            </div>
            <div class="card-body">
                @if($peminjaman->kendaraan->foto)
                    <img src="{{ asset('storage/kendaraan/' . $peminjaman->kendaraan->foto) }}" class="img-fluid rounded mb-3" alt="Foto Kendaraan">
                @endif
                <table class="table table-sm">
                    <tr>
                        <td><strong>Kode</strong></td>
                        <td>{{ $peminjaman->kendaraan->kode_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $peminjaman->kendaraan->nama_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Polisi</strong></td>
                        <td><span class="badge bg-primary">{{ $peminjaman->kendaraan->no_polisi }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td><span class="badge bg-primary">Dipinjam</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Signature Pad
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Clear button
        $('#btnClearSignature').click(function() {
            signaturePad.clear();
        });

        // Preview foto
        $('#foto_kembali').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto').hide();
            }
        });

        // Get GPS Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                
                $('#latitude_kembali').val(latitude);
                $('#longitude_kembali').val(longitude);
                $('#lokasi_display').val(latitude + ', ' + longitude);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Terdeteksi!',
                    text: 'GPS berhasil mendapatkan koordinat',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, function(error) {
                $('#lokasi_display').val('GPS tidak dapat diakses');
            });
        }

        // Form Submit
        $('#formPengembalian').submit(function(e) {
            e.preventDefault();

            if (signaturePad.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanda Tangan Belum Diisi!',
                    text: 'Silakan tanda tangan terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Save signature as base64
            const dataURL = signaturePad.toDataURL();
            $('#ttd_kembali').val(dataURL);

            // Submit form
            this.submit();
        });
    });
</script>
@endpush
