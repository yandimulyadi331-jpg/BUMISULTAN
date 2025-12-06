@extends('layouts.app')
@section('titlepage', 'Tindak Lanjut Administrasi')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Administrasi / Tindak Lanjut</span>
@endsection

<div class="row">
    <!-- Info Administrasi -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-{{ $administrasi->getJenisAdministrasiColor() }} text-white">
                <h6 class="mb-0">
                    <i class="{{ $administrasi->getJenisAdministrasiIcon() }} me-2"></i>
                    Info Administrasi
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Kode</small>
                    <h5 class="text-primary">{{ $administrasi->kode_administrasi }}</h5>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Jenis</small>
                    <p class="mb-0">
                        <span class="badge bg-{{ $administrasi->getJenisAdministrasiColor() }}">
                            {{ $administrasi->getJenisAdministrasiLabel() }}
                        </span>
                    </p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Perihal</small>
                    <p class="mb-0 fw-bold">{{ $administrasi->perihal }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Status</small>
                    <div>{!! $administrasi->getStatusBadge() !!}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Tindak Lanjut -->
    <div class="col-lg-8">
        <form action="{{ route('administrasi.tindak-lanjut.store', $administrasi->id) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="formTindakLanjut">
            @csrf
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="ti ti-arrow-forward me-2"></i>Form Tindak Lanjut</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Jenis Tindak Lanjut -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Tindak Lanjut <span class="text-danger">*</span></label>
                                <select name="jenis_tindak_lanjut" 
                                        id="jenis_tindak_lanjut" 
                                        class="form-select @error('jenis_tindak_lanjut') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Pilih Jenis Tindak Lanjut --</option>
                                    <option value="pencairan_dana">Pencairan Dana</option>
                                    <option value="disposisi">Disposisi</option>
                                    <option value="konfirmasi_terima">Konfirmasi Terima</option>
                                    <option value="konfirmasi_kirim">Konfirmasi Kirim</option>
                                    <option value="rapat_pembahasan">Rapat Pembahasan</option>
                                    <option value="penerbitan_sk">Penerbitan SK</option>
                                    <option value="tandatangan">Penandatanganan</option>
                                    <option value="verifikasi">Verifikasi</option>
                                    <option value="approval">Approval</option>
                                    <option value="revisi">Revisi</option>
                                    <option value="arsip">Pengarsipan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                @error('jenis_tindak_lanjut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Tindak Lanjut -->
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status_tindak_lanjut" 
                                        class="form-select @error('status_tindak_lanjut') is-invalid @enderror" 
                                        required>
                                    <option value="pending">Pending</option>
                                    <option value="proses" selected>Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                                @error('status_tindak_lanjut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Judul Tindak Lanjut -->
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Judul Tindak Lanjut <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="judul_tindak_lanjut" 
                                       class="form-control @error('judul_tindak_lanjut') is-invalid @enderror" 
                                       value="{{ old('judul_tindak_lanjut') }}" 
                                       placeholder="Contoh: Pencairan Dana Proposal Kegiatan"
                                       required>
                                @error('judul_tindak_lanjut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi_tindak_lanjut" 
                                          class="form-control @error('deskripsi_tindak_lanjut') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Deskripsi detail tindak lanjut">{{ old('deskripsi_tindak_lanjut') }}</textarea>
                                @error('deskripsi_tindak_lanjut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Dynamic Fields Container -->
                    <div id="dynamic-fields"></div>

                    <!-- Generic Catatan -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" 
                                          class="form-control" 
                                          rows="2" 
                                          placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('administrasi.show', $administrasi->id) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-device-floppy me-1"></i>Simpan Tindak Lanjut
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
// Dynamic form fields based on jenis_tindak_lanjut
document.getElementById('jenis_tindak_lanjut').addEventListener('change', function() {
    const jenis = this.value;
    const container = document.getElementById('dynamic-fields');
    container.innerHTML = ''; // Clear previous fields
    
    if (!jenis) return;

    let html = '<h5 class="mb-3 text-primary">Form Detail ' + this.options[this.selectedIndex].text + '</h5><div class="row">';

    // Pencairan Dana
    if (jenis === 'pencairan_dana') {
        html += `
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nominal Pencairan <span class="text-danger">*</span></label>
                    <input type="number" name="nominal_pencairan" class="form-control" placeholder="Contoh: 5000000" step="0.01">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Metode Pencairan</label>
                    <select name="metode_pencairan" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Cek">Cek</option>
                        <option value="Giro">Giro</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Penerima Dana</label>
                    <input type="text" name="nama_penerima_dana" class="form-control" placeholder="Nama penerima">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" class="form-control" placeholder="Nomor rekening">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Tanggal Pencairan</label>
                    <input type="date" name="tanggal_pencairan" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Upload Bukti Pencairan (PDF/JPG)</label>
                    <input type="file" name="bukti_pencairan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Max: 2MB</small>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Tanda Tangan Digital <span class="text-danger">*</span></label>
                    <div class="border rounded p-2 bg-light">
                        <canvas id="signature-pad-pencairan" class="signature-pad" width="600" height="200" style="border: 2px dashed #ccc; background: white; cursor: crosshair; width: 100%;"></canvas>
                        <input type="hidden" name="tandatangan_pencairan" id="signature-data-pencairan">
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-warning" onclick="clearSignature('pencairan')">
                            <i class="ti ti-eraser"></i> Hapus TTD
                        </button>
                        <small class="text-muted ms-2">Tanda tangan di area putih menggunakan mouse/touchpad</small>
                    </div>
                </div>
            </div>
        `;
    }

    // Disposisi
    else if (jenis === 'disposisi') {
        html += `
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Dari</label>
                    <input type="text" name="disposisi_dari" class="form-control" placeholder="Nama pengirim disposisi">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Kepada <span class="text-danger">*</span></label>
                    <input type="text" name="disposisi_kepada" class="form-control" placeholder="Nama penerima disposisi">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline_disposisi" class="form-control">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Instruksi Disposisi</label>
                    <textarea name="instruksi_disposisi" class="form-control" rows="3" placeholder="Instruksi atau perintah disposisi"></textarea>
                </div>
            </div>
        `;
    }

    // Konfirmasi Terima/Kirim Paket
    else if (jenis === 'konfirmasi_terima' || jenis === 'konfirmasi_kirim') {
        html += `
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Penerima Paket</label>
                    <input type="text" name="nama_penerima_paket" class="form-control" placeholder="Nama penerima">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Waktu Terima/Kirim</label>
                    <input type="datetime-local" name="waktu_terima_paket" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Kondisi Paket</label>
                    <select name="kondisi_paket" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Tidak Lengkap">Tidak Lengkap</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nomor Resi</label>
                    <input type="text" name="resi_pengiriman" class="form-control" placeholder="Nomor resi pengiriman">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Foto Paket (JPG/PNG)</label>
                    <input type="file" name="foto_paket" class="form-control" accept=".jpg,.jpeg,.png">
                    <small class="text-muted">Max: 2MB</small>
                </div>
            </div>
        `;
    }

    // Rapat Pembahasan
    else if (jenis === 'rapat_pembahasan') {
        html += `
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Waktu Rapat</label>
                    <input type="datetime-local" name="waktu_rapat" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Tempat Rapat</label>
                    <input type="text" name="tempat_rapat" class="form-control" placeholder="Lokasi rapat">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Hasil Rapat</label>
                    <textarea name="hasil_rapat" class="form-control" rows="4" placeholder="Ringkasan hasil rapat"></textarea>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Upload Notulen (PDF/DOC)</label>
                    <input type="file" name="notulen_rapat" class="form-control" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Max: 5MB</small>
                </div>
            </div>
        `;
    }

    // Penandatanganan
    else if (jenis === 'tandatangan' || jenis === 'penerbitan_sk') {
        html += `
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Penandatangan</label>
                    <input type="text" name="nama_penandatangan" class="form-control" placeholder="Nama yang menandatangani">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan_penandatangan" class="form-control" placeholder="Jabatan penandatangan">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Tanggal Tandatangan</label>
                    <input type="date" name="tanggal_tandatangan" class="form-control">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Tanda Tangan Digital <span class="text-danger">*</span></label>
                    <div class="border rounded p-2 bg-light">
                        <canvas id="signature-pad-ttd" class="signature-pad" width="600" height="200" style="border: 2px dashed #ccc; background: white; cursor: crosshair; width: 100%;"></canvas>
                        <input type="hidden" name="signature_ttd" id="signature-data-ttd">
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-warning" onclick="clearSignature('ttd')">
                            <i class="ti ti-eraser"></i> Hapus TTD
                        </button>
                        <small class="text-muted ms-2">Tanda tangan di area putih menggunakan mouse/touchpad</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Upload Dokumen TTD (PDF)</label>
                    <input type="file" name="file_dokumen_ttd" class="form-control" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Max: 5MB - Upload dokumen yang sudah ditandatangani</small>
                </div>
            </div>
        `;
    }

    // Verifikasi/Approval
    else if (jenis === 'verifikasi' || jenis === 'approval') {
        html += `
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Verifikator</label>
                    <input type="text" name="verifikator" class="form-control" placeholder="Nama verifikator">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Tanggal Verifikasi</label>
                    <input type="date" name="tanggal_verifikasi" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label class="form-label">Hasil Verifikasi</label>
                    <select name="hasil_verifikasi" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="revisi">Perlu Revisi</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="form-label">Catatan Verifikasi</label>
                    <textarea name="catatan_verifikasi" class="form-control" rows="3" placeholder="Catatan hasil verifikasi"></textarea>
                </div>
            </div>
        `;
    }

    html += '</div><hr>';
    container.innerHTML = html;
    
    // Initialize signature pads after DOM update
    setTimeout(() => {
        if (jenis === 'pencairan_dana') {
            initSignaturePad('pencairan');
        } else if (jenis === 'tandatangan') {
            initSignaturePad('ttd');
        }
    }, 100);
});

// Signature Pad functionality
let signaturePads = {};

function initSignaturePad(type) {
    const canvasId = 'signature-pad-' + type;
    const canvas = document.getElementById(canvasId);
    
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    
    // Set canvas size
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = 200;
    
    // Drawing functions
    function startDrawing(e) {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        [lastX, lastY] = [e.clientX - rect.left, e.clientY - rect.top];
    }
    
    function draw(e) {
        if (!isDrawing) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.stroke();
        
        [lastX, lastY] = [x, y];
        
        // Save signature data
        saveSignatureData(type);
    }
    
    function stopDrawing() {
        isDrawing = false;
    }
    
    // Event listeners
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Touch support
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });
    
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    });
    
    canvas.addEventListener('touchend', (e) => {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup', {});
        canvas.dispatchEvent(mouseEvent);
    });
    
    signaturePads[type] = { canvas, ctx };
}

function saveSignatureData(type) {
    const canvas = document.getElementById('signature-pad-' + type);
    const hiddenInput = document.getElementById('signature-data-' + type);
    
    if (canvas && hiddenInput) {
        // Convert canvas to base64
        const dataURL = canvas.toDataURL('image/png');
        hiddenInput.value = dataURL;
    }
}

function clearSignature(type) {
    const canvas = document.getElementById('signature-pad-' + type);
    const hiddenInput = document.getElementById('signature-data-' + type);
    
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
    
    if (hiddenInput) {
        hiddenInput.value = '';
    }
}

// Form validation
document.getElementById('formTindakLanjut').addEventListener('submit', function(e) {
    const jenisTindakLanjut = document.getElementById('jenis_tindak_lanjut').value;
    
    if (!jenisTindakLanjut) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Silakan pilih jenis tindak lanjut terlebih dahulu',
        });
        return false;
    }
    
    // Validate signature for pencairan_dana
    if (jenisTindakLanjut === 'pencairan_dana') {
        const signatureData = document.getElementById('signature-data-pencairan');
        if (signatureData && !signatureData.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Tanda Tangan Diperlukan!',
                text: 'Silakan buat tanda tangan digital untuk pencairan dana',
            });
            return false;
        }
    }
    
    // Validate signature for tandatangan
    if (jenisTindakLanjut === 'tandatangan') {
        const signatureData = document.getElementById('signature-data-ttd');
        if (signatureData && !signatureData.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Tanda Tangan Diperlukan!',
                text: 'Silakan buat tanda tangan digital terlebih dahulu',
            });
            return false;
        }
    }
});

</script>
@endpush
