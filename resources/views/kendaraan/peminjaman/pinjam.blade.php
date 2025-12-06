@extends('layouts.app')
@section('titlepage', 'Form Peminjaman Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Peminjaman</span>
@endsection

@section('content')
<style>
    .signature-pad {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        background-color: #ffffff;
        cursor: crosshair;
        display: block;
        width: 100%;
        height: 200px;
        touch-action: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
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
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-white mb-0">
                    <i class="ti ti-hand-grab me-2"></i>Form Peminjaman Kendaraan
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('kendaraan.peminjaman.prosesPinjam', $kendaraan_id) }}" 
                      method="POST" 
                      id="formPeminjaman" 
                      enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Data Peminjam -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="ti ti-user me-2"></i>Data Peminjam
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="nama_peminjam" 
                                       class="form-control" 
                                       placeholder="Masukkan nama peminjam"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP Peminjam</label>
                                <input type="tel" 
                                       name="no_hp_peminjam" 
                                       class="form-control" 
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Email Peminjam</label>
                                <input type="email" 
                                       name="email_peminjam" 
                                       class="form-control" 
                                       placeholder="email@example.com">
                                <small class="text-muted">Email akan digunakan untuk notifikasi dan tracking GPS</small>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Identitas -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="ti ti-id me-2"></i>Dokumen Identitas
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Foto KTP/Identitas <span class="text-danger">*</span></label>
                                <input type="file" 
                                       name="foto_identitas" 
                                       class="form-control" 
                                       accept="image/jpeg,image/jpg,image/png" 
                                       id="inputFotoIdentitas"
                                       required>
                                <small class="text-muted">
                                    Upload foto KTP, SIM, atau identitas lainnya (Max: 2MB, Format: JPG/PNG)
                                </small>
                                <div class="mt-3" id="previewContainer" style="display: none;">
                                    <img id="previewIdentitas" 
                                         src="" 
                                         alt="Preview" 
                                         class="img-thumbnail" 
                                         style="max-width: 300px; max-height: 200px; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Peminjaman -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="ti ti-clipboard me-2"></i>Detail Peminjaman
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                                <textarea name="keperluan" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Jelaskan keperluan peminjaman kendaraan"
                                          required></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="ti ti-calendar-time me-1"></i>Waktu Mulai Pinjam <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       name="waktu_pinjam" 
                                       class="form-control" 
                                       id="waktuPinjam"
                                       value="{{ date('Y-m-d\TH:i') }}"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="ti ti-calendar-event me-1"></i>Estimasi Waktu Kembali <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       name="estimasi_kembali" 
                                       class="form-control" 
                                       id="estimasiKembali"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Kendaraan -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="ti ti-car me-2"></i>Kondisi Kendaraan Saat Pinjam
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kilometer Awal</label>
                                <input type="number" 
                                       name="km_awal" 
                                       class="form-control" 
                                       placeholder="Contoh: 15000"
                                       step="0.01"
                                       min="0">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status BBM Saat Pinjam</label>
                                <select name="status_bbm_pinjam" class="form-select">
                                    <option value="Penuh" selected>Penuh (Full Tank)</option>
                                    <option value="3/4">3/4 Tank</option>
                                    <option value="1/2">1/2 Tank</option>
                                    <option value="1/4">1/4 Tank</option>
                                    <option value="Kosong">Hampir Kosong</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan Tambahan</label>
                                <textarea name="keterangan" 
                                          class="form-control" 
                                          rows="2" 
                                          placeholder="Catatan kondisi kendaraan atau informasi tambahan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi GPS -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="ti ti-map-pin me-2"></i>Lokasi Peminjaman
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Koordinat GPS</label>
                                <input type="text" 
                                       id="lokasiDisplay" 
                                       class="form-control" 
                                       readonly 
                                       placeholder="Mencari lokasi GPS...">
                                <input type="hidden" name="latitude_pinjam" id="latitudePinjam">
                                <input type="hidden" name="longitude_pinjam" id="longitudePinjam">
                                <small class="text-muted">
                                    <i class="ti ti-info-circle me-1"></i>GPS akan terdeteksi otomatis untuk tracking
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Tanda Tangan Digital -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="ti ti-writing me-2"></i>Tanda Tangan Digital Peminjam
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Tanda Tangan di Area Bawah <span class="text-danger">*</span>
                                </label>
                                <canvas id="signaturePad" class="signature-pad"></canvas>
                                <input type="hidden" name="ttd_peminjam" id="ttdPeminjam">
                                <div class="mt-2">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            id="btnClearSignature">
                                        <i class="ti ti-eraser me-1"></i>Hapus Tanda Tangan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="ti ti-send me-2"></i>Submit Peminjaman Kendaraan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Informasi Kendaraan -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title text-white mb-0">
                    <i class="ti ti-car me-2"></i>Informasi Kendaraan
                </h5>
            </div>
            <div class="card-body">
                @if($kendaraan->foto && file_exists(public_path('storage/kendaraan/' . $kendaraan->foto)))
                    <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" 
                         class="img-fluid rounded mb-3" 
                         alt="Foto Kendaraan"
                         style="width: 100%; height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="ti ti-car" style="font-size: 64px; color: #ccc;"></i>
                    </div>
                @endif
                
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="40%"><strong>Kode</strong></td>
                        <td>{{ $kendaraan->kode_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $kendaraan->nama_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Polisi</strong></td>
                        <td><span class="badge bg-primary">{{ $kendaraan->no_polisi }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis</strong></td>
                        <td>{{ $kendaraan->jenis_kendaraan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Merk</strong></td>
                        <td>{{ $kendaraan->merk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Model</strong></td>
                        <td>{{ $kendaraan->model ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tahun</strong></td>
                        <td>{{ $kendaraan->tahun ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td><span class="badge bg-success">{{ ucfirst($kendaraan->status) }}</span></td>
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
    const canvas = document.getElementById('signaturePad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });

    // Resize canvas to match display size
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Clear signature button
    $('#btnClearSignature').click(function() {
        signaturePad.clear();
    });

    // Preview foto identitas
    $('#inputFotoIdentitas').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Ukuran file maksimal 2MB',
                    confirmButtonText: 'OK'
                });
                $(this).val('');
                $('#previewContainer').hide();
                return;
            }

            // Validasi tipe
            if (!file.type.match('image.*')) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Valid!',
                    text: 'Hanya file gambar yang diperbolehkan',
                    confirmButtonText: 'OK'
                });
                $(this).val('');
                $('#previewContainer').hide();
                return;
            }

            // Preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewIdentitas').attr('src', e.target.result);
                $('#previewContainer').fadeIn();
            }
            reader.readAsDataURL(file);
        }
    });

    // Set minimum datetime untuk estimasi kembali
    $('#waktuPinjam').on('change', function() {
        const waktuPinjam = $(this).val();
        if (waktuPinjam) {
            $('#estimasiKembali').attr('min', waktuPinjam);
            
            // Auto set estimasi kembali +1 hour if empty
            if (!$('#estimasiKembali').val()) {
                const date = new Date(waktuPinjam);
                date.setHours(date.getHours() + 1);
                const newDate = date.toISOString().slice(0, 16);
                $('#estimasiKembali').val(newDate);
            }
        }
    });

    // Trigger on page load
    $('#waktuPinjam').trigger('change');

    // Get GPS Location
    if (navigator.geolocation) {
        $('#lokasiDisplay').val('Mengambil lokasi GPS...');
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                $('#latitudePinjam').val(latitude);
                $('#longitudePinjam').val(longitude);
                $('#lokasiDisplay').val(`${latitude.toFixed(6)}, ${longitude.toFixed(6)}`);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Terdeteksi!',
                    text: 'GPS berhasil mendapatkan koordinat lokasi Anda',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            },
            function(error) {
                console.error('GPS Error:', error);
                $('#lokasiDisplay').val('GPS tidak dapat diakses (opsional)');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'GPS Tidak Tersedia',
                    text: 'Lokasi GPS tidak dapat diambil, namun peminjaman tetap bisa dilanjutkan',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        $('#lokasiDisplay').val('Browser tidak mendukung GPS');
    }

    // Form validation and submit
    $('#formPeminjaman').submit(function(e) {
        e.preventDefault();

        // Validate signature
        if (signaturePad.isEmpty()) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanda Tangan Belum Diisi!',
                text: 'Silakan tanda tangan terlebih dahulu di area yang disediakan',
                confirmButtonText: 'OK'
            });
            $('html, body').animate({
                scrollTop: $('#signaturePad').offset().top - 100
            }, 500);
            return false;
        }

        // Validate datetime
        const waktuPinjam = new Date($('#waktuPinjam').val());
        const estimasiKembali = new Date($('#estimasiKembali').val());
        
        if (estimasiKembali <= waktuPinjam) {
            Swal.fire({
                icon: 'error',
                title: 'Waktu Tidak Valid!',
                text: 'Estimasi waktu kembali harus lebih dari waktu pinjam',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Save signature as base64
        const dataURL = signaturePad.toDataURL('image/png');
        $('#ttdPeminjam').val(dataURL);

        // Show loading
        Swal.fire({
            title: 'Memproses Peminjaman...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit form
        this.submit();
    });
});
</script>
@endpush
