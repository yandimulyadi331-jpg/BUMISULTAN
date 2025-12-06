@extends('layouts.app')
@section('titlepage', 'Form Pengembalian Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Pengembalian / Form</span>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa fa-undo me-2"></i>Form Pengembalian Inventaris</h4>
                    <a href="{{ route('pengembalian-inventaris.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Peminjaman -->
                <div class="alert alert-info">
                    <h5><i class="fa fa-info-circle me-2"></i>Informasi Peminjaman</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kode Peminjaman:</strong> {{ $peminjaman->kode_peminjaman }}</p>
                            <p class="mb-1"><strong>Inventaris:</strong> {{ $peminjaman->inventaris->nama }}</p>
                            <p class="mb-1"><strong>Jumlah Pinjam:</strong> {{ $peminjaman->jumlah_pinjam }} {{ $peminjaman->inventaris->satuan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Peminjam:</strong> {{ $peminjaman->karyawan ? $peminjaman->karyawan->nama_lengkap : $peminjaman->nama_peminjam }}</p>
                            <p class="mb-1"><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Rencana Kembali:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($peminjaman->isTerlambat())
                    <div class="alert alert-danger mt-2 mb-0">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>PERINGATAN: Peminjaman ini sudah terlambat!</strong> Akan dikenakan denda keterlambatan.
                    </div>
                    @endif
                </div>

                <form action="{{ route('pengembalian-inventaris.store') }}" method="POST" enctype="multipart/form-data" id="formPengembalian">
                    @csrf
                    <input type="hidden" name="peminjaman_inventaris_id" value="{{ $peminjaman->id }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-calendar me-1"></i> Tanggal Kembali <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_kembali" 
                                    class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                                    value="{{ old('tanggal_kembali', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('tanggal_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-check me-1"></i> Kondisi Barang <span class="text-danger">*</span></label>
                                <select name="kondisi_barang_kembali" class="form-select @error('kondisi_barang_kembali') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="baik" {{ old('kondisi_barang_kembali', 'baik') == 'baik' ? 'selected' : '' }}>Baik (Tidak Ada Kerusakan)</option>
                                    <option value="rusak_ringan" {{ old('kondisi_barang_kembali') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi_barang_kembali') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    <option value="hilang" {{ old('kondisi_barang_kembali') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('kondisi_barang_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-money me-1"></i> Denda Keterlambatan</label>
                                <input type="number" name="denda" id="denda" 
                                    class="form-control @error('denda') is-invalid @enderror" 
                                    value="{{ old('denda', 0) }}" min="0" readonly>
                                <small class="text-muted">Dihitung otomatis berdasarkan keterlambatan</small>
                                @error('denda')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-file-text me-1"></i> Keterangan Pengembalian <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                            rows="3" placeholder="Jelaskan kondisi barang saat dikembalikan" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-photo me-1"></i> Foto Barang Saat Dikembalikan <span class="text-danger">*</span></label>
                                <input type="file" name="foto_barang_kembali" class="form-control @error('foto_barang_kembali') is-invalid @enderror" 
                                    accept="image/*" id="fotoBarangKembaliInput" required>
                                <small class="text-muted">Upload foto kondisi barang saat dikembalikan</small>
                                @error('foto_barang_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="previewFotoKembali" class="mt-2" style="display: none;">
                                <img id="previewImageKembali" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-writing me-1"></i> TTD Digital Peminjam <span class="text-danger">*</span></label>
                                <div class="border rounded p-2">
                                    <canvas id="signature-pad-peminjam" width="300" height="150" style="border: 1px solid #ddd; width: 100%; height: 150px;"></canvas>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-warning" id="clear-signature-peminjam">
                                        <i class="fa fa-eraser me-1"></i>Hapus TTD
                                    </button>
                                </div>
                                <input type="hidden" name="ttd_penerima" id="ttd_penerima">
                                <small class="text-muted">TTD peminjam yang menerima barang kembali</small>
                                @error('ttd_penerima')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label"><i class="ti ti-writing-sign me-1"></i> TTD Digital Petugas <span class="text-danger">*</span></label>
                                <div class="border rounded p-2">
                                    <canvas id="signature-pad-petugas" width="300" height="150" style="border: 1px solid #ddd; width: 100%; height: 150px;"></canvas>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-warning" id="clear-signature-petugas">
                                        <i class="fa fa-eraser me-1"></i>Hapus TTD
                                    </button>
                                </div>
                                <input type="hidden" name="ttd_petugas_pengembalian" id="ttd_petugas_pengembalian">
                                <small class="text-muted">TTD petugas yang menerima pengembalian</small>
                                @error('ttd_petugas_pengembalian')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Foto Peminjaman (Reference) -->
                    @if($peminjaman->foto_barang_pinjam)
                    <div class="form-group mb-3">
                        <label class="form-label"><i class="ti ti-photo me-1"></i> Referensi: Foto Saat Dipinjam</label>
                        <div>
                            <img src="{{ Storage::url($peminjaman->foto_barang_pinjam) }}" alt="Foto Pinjam" 
                                class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Penting:</strong>
                        <ul class="mb-0">
                            <li>Pastikan foto barang yang dikembalikan jelas dan menunjukkan kondisi barang</li>
                            <li>TTD digital diperlukan sebagai bukti pengembalian</li>
                            <li>Denda keterlambatan akan dihitung otomatis jika ada</li>
                            <li>Status inventaris akan otomatis diupdate setelah pengembalian</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('pengembalian-inventaris.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check me-2"></i>Proses Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    // Preview foto barang kembali
    document.getElementById('fotoBarangKembaliInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImageKembali').src = e.target.result;
                document.getElementById('previewFotoKembali').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Signature Pad untuk Peminjam (Penerima)
    const canvasPeminjam = document.getElementById('signature-pad-peminjam');
    const signaturePadPeminjam = new SignaturePad(canvasPeminjam, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });

    document.getElementById('clear-signature-peminjam').addEventListener('click', function() {
        signaturePadPeminjam.clear();
    });

    // Signature Pad untuk Petugas
    const canvasPetugas = document.getElementById('signature-pad-petugas');
    const signaturePadPetugas = new SignaturePad(canvasPetugas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)'
    });

    document.getElementById('clear-signature-petugas').addEventListener('click', function() {
        signaturePadPetugas.clear();
    });

    // Auto-calculate denda berdasarkan tanggal
    const tanggalKembaliRencana = new Date('{{ $peminjaman->tanggal_kembali_rencana }}');
    const dendaPerHari = 10000; // Rp 10,000 per hari

    document.querySelector('[name="tanggal_kembali"]').addEventListener('change', function() {
        const tanggalKembaliAktual = new Date(this.value);
        
        if (tanggalKembaliAktual > tanggalKembaliRencana) {
            const diffTime = Math.abs(tanggalKembaliAktual - tanggalKembaliRencana);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const denda = diffDays * dendaPerHari;
            
            document.getElementById('denda').value = denda;
            
            Swal.fire({
                icon: 'warning',
                title: 'Terlambat!',
                html: `<p>Pengembalian terlambat <strong>${diffDays} hari</strong></p>
                       <p>Denda: <strong class="text-danger">Rp ${denda.toLocaleString('id-ID')}</strong></p>`,
                confirmButtonText: 'OK'
            });
        } else {
            document.getElementById('denda').value = 0;
        }
    });

    // Trigger perhitungan denda saat page load
    document.querySelector('[name="tanggal_kembali"]').dispatchEvent(new Event('change'));

    // Form validation
    document.getElementById('formPengembalian').addEventListener('submit', function(e) {
        // Validasi signature peminjam
        if (signaturePadPeminjam.isEmpty()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'TTD Peminjam Diperlukan',
                text: 'Silakan buat tanda tangan digital peminjam terlebih dahulu!'
            });
            return false;
        }

        // Validasi signature petugas
        if (signaturePadPetugas.isEmpty()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'TTD Petugas Diperlukan',
                text: 'Silakan buat tanda tangan digital petugas terlebih dahulu!'
            });
            return false;
        }

        // Save signatures to hidden inputs
        document.getElementById('ttd_penerima').value = signaturePadPeminjam.toDataURL();
        document.getElementById('ttd_petugas_pengembalian').value = signaturePadPetugas.toDataURL();
    });
</script>
@endpush
@endsection
