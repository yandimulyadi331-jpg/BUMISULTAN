@extends('layouts.app')
@section('titlepage', 'Riwayat Peminjaman Kendaraan')

@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan / Riwayat Peminjaman</span>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        
        @if($kendaraan->peminjamanAktif)
            <a href="{{ route('kendaraan.peminjaman.kembali', Crypt::encrypt($kendaraan->peminjamanAktif->id)) }}" class="btn btn-success">
                <i class="ti ti-check me-2"></i>Tandai Kembali
            </a>
        @else
            <button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#modalPinjamKendaraan">
                <i class="ti ti-user-check me-2"></i>Pinjam Kendaraan
            </button>
        @endif
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($kendaraan->foto)
                            <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-fluid rounded" alt="Foto">
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h3>{{ $kendaraan->nama_kendaraan }} <span class="badge bg-primary">{{ $kendaraan->no_polisi }}</span></h3>
                        <p class="text-muted mb-1">{{ $kendaraan->jenis_kendaraan }} - {{ $kendaraan->merk }} {{ $kendaraan->model }}</p>
                        <p class="mb-0"><strong>Status:</strong> 
                            @if($kendaraan->status == 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($kendaraan->status == 'keluar')
                                <span class="badge bg-info">Sedang Keluar</span>
                            @elseif($kendaraan->status == 'dipinjam')
                                <span class="badge bg-primary">Dipinjam</span>
                            @else
                                <span class="badge bg-danger">Service</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Peminjaman</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Peminjam</th>
                                <th>Keperluan</th>
                                <th>Foto Identitas</th>
                                <th>Waktu Pinjam</th>
                                <th>Waktu Kembali</th>
                                <th>Foto Kembali</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $d)
                                <tr>
                                    <td>{{ $loop->iteration + $peminjaman->firstItem() - 1 }}</td>
                                    <td><strong>{{ $d->kode_peminjaman }}</strong></td>
                                    <td>
                                        {{ $d->nama_peminjam }}<br>
                                        <small class="text-muted">{{ $d->no_hp_peminjam }}</small>
                                    </td>
                                    <td>{{ Str::limit($d->keperluan, 30) }}</td>
                                    <td class="text-center">
                                        @if($d->foto_identitas)
                                            <img src="{{ asset('storage/peminjaman/identitas/' . $d->foto_identitas) }}" 
                                                alt="Foto Identitas" 
                                                class="img-thumbnail foto-popup" 
                                                style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                data-foto="{{ asset('storage/peminjaman/identitas/' . $d->foto_identitas) }}"
                                                data-title="Foto Identitas - {{ $d->nama_peminjam }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($d->waktu_pinjam)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if(isset($d->waktu_kembali) && $d->waktu_kembali)
                                            {{ \Carbon\Carbon::parse($d->waktu_kembali)->format('d/m/Y H:i') }}
                                            @php
                                                $isLate = \Carbon\Carbon::parse($d->waktu_kembali)->gt(\Carbon\Carbon::parse($d->estimasi_kembali));
                                            @endphp
                                            @if($isLate)
                                                <br><span class="badge bg-danger">Terlambat</span>
                                            @endif
                                        @else
                                            <span class="badge bg-warning">Belum Kembali</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($d->foto_kembali)
                                            <img src="{{ asset('storage/peminjaman/' . $d->foto_kembali) }}" 
                                                alt="Foto Kembali" 
                                                class="img-thumbnail foto-popup" 
                                                style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                data-foto="{{ asset('storage/peminjaman/' . $d->foto_kembali) }}"
                                                data-title="Foto Kembali - {{ $d->nama_peminjam }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($d->waktu_kembali) && $d->waktu_kembali)
                                            {{ \Carbon\Carbon::parse($d->waktu_pinjam)->diffInHours(\Carbon\Carbon::parse($d->waktu_kembali)) }} jam
                                        @else
                                            {{ \Carbon\Carbon::parse($d->waktu_pinjam)->diffForHumans() }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($d->status == 'dipinjam')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('kendaraan.peminjaman.tracking', Crypt::encrypt($d->id)) }}" 
                                            class="btn btn-sm btn-primary" target="_blank" title="GPS Tracking">
                                            <i class="ti ti-map"></i>
                                        </a>
                                        <button class="btn btn-sm btn-info detailPeminjaman" data-id="{{ $d->id }}" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning editPeminjaman" data-id="{{ Crypt::encrypt($d->id) }}" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <form action="{{ route('kendaraan.peminjaman.delete', Crypt::encrypt($d->id)) }}" method="POST" style="display: inline;" class="form-delete-peminjaman">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada riwayat peminjaman</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $peminjaman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Detail Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="loadDetailPeminjaman"></div>
        </div>
    </div>
</div>

<!-- Modal Popup Foto -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoTitle">Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFotoImage" src="" alt="Foto" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Peminjaman -->
<div class="modal fade" id="modalEditPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-edit me-2"></i>Edit Peminjaman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPeminjaman" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Peminjam</label>
                                <input type="text" class="form-control" id="edit_nama_peminjam" name="nama_peminjam" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="edit_no_hp_peminjam" name="no_hp_peminjam">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email_peminjam" name="email_peminjam">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Keperluan</label>
                                <textarea class="form-control" id="edit_keperluan" name="keperluan" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Pinjam</label>
                                <input type="datetime-local" class="form-control" id="edit_waktu_pinjam" name="waktu_pinjam" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estimasi Kembali</label>
                                <input type="datetime-local" class="form-control" id="edit_estimasi_kembali" name="estimasi_kembali" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Waktu Kembali</label>
                                <input type="datetime-local" class="form-control" id="edit_waktu_kembali" name="waktu_kembali">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KM Awal</label>
                                <input type="number" class="form-control" id="edit_km_awal" name="km_awal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KM Akhir</label>
                                <input type="number" class="form-control" id="edit_km_akhir" name="km_akhir">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Popup Foto
        $('.foto-popup').click(function() {
            var fotoUrl = $(this).data('foto');
            var title = $(this).data('title');
            
            $('#modalFotoTitle').text(title);
            $('#modalFotoImage').attr('src', fotoUrl);
            $('#modalFoto').modal('show');
        });

        // Edit Peminjaman
        $('.editPeminjaman').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            
            $.ajax({
                url: '/peminjaman/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#edit_nama_peminjam').val(response.nama_peminjam);
                    $('#edit_no_hp_peminjam').val(response.no_hp_peminjam);
                    $('#edit_email_peminjam').val(response.email_peminjam);
                    $('#edit_keperluan').val(response.keperluan);
                    $('#edit_waktu_pinjam').val(response.waktu_pinjam);
                    $('#edit_estimasi_kembali').val(response.estimasi_kembali);
                    $('#edit_waktu_kembali').val(response.waktu_kembali);
                    $('#edit_km_awal').val(response.km_awal);
                    $('#edit_km_akhir').val(response.km_akhir);
                    
                    $('#formEditPeminjaman').attr('action', '/peminjaman/' + id + '/update');
                    $('#modalEditPeminjaman').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Gagal mengambil data peminjaman', 'error');
                }
            });
        });

        // Detail Peminjaman
        $('.detailPeminjaman').click(function() {
            var id = $(this).data('id');
            $('#modalDetail').modal('show');
            $('#loadDetailPeminjaman').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
            
            // Untuk saat ini tampilkan info sederhana
            $('#loadDetailPeminjaman').html('<p class="text-center">Detail akan ditampilkan di sini</p>');
        });
        
        // Handle delete peminjaman with SweetAlert2
        $('.form-delete-peminjaman').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Hapus Peminjaman?',
                text: "Data peminjaman akan dihapus permanen!",
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
    });
</script>

<!-- Modal Form Peminjaman Kendaraan -->
<div class="modal fade" id="modalPinjamKendaraan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ti ti-hand-grab me-2"></i>Form Peminjaman Kendaraan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('kendaraan.peminjaman.prosesPinjam', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formPeminjamanModal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Form Column -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><i class="ti ti-user me-2"></i>Nama Peminjam <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_peminjam" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><i class="ti ti-phone me-2"></i>No. HP Peminjam</label>
                                        <input type="text" name="no_hp_peminjam" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><i class="ti ti-mail me-2"></i>Email Peminjam</label>
                                        <input type="email" name="email_peminjam" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><i class="ti ti-id me-2"></i>Foto KTP/Identitas <span class="text-danger">*</span></label>
                                        <input type="file" name="foto_identitas" class="form-control" accept="image/*" required onchange="previewIdentitas(event)">
                                        <small class="text-muted">Upload foto KTP, SIM, atau identitas lainnya (Max: 2MB, Format: JPG/PNG)</small>
                                        <div class="mt-2">
                                            <img id="preview_identitas_modal" src="" alt="Preview" style="max-width: 200px; display: none;" class="img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                                        <textarea name="keperluan" class="form-control" rows="3" placeholder="Masukkan keperluan peminjaman" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Jam Pinjam <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_pinjam" class="form-control" value="{{ date('H:i') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_rencana_kembali" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Jam Rencana Kembali <span class="text-danger">*</span></label>
                                        <input type="time" name="jam_rencana_kembali" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Kilometer Awal</label>
                                        <input type="number" name="km_awal" class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status BBM Saat Pinjam</label>
                                        <select name="status_bbm_pinjam" class="form-select">
                                            <option value="">Pilih Status BBM</option>
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
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tanda Tangan Peminjam <span class="text-danger">*</span></label>
                                        <div class="border rounded p-2" style="background: #f8f9fa;">
                                            <canvas id="signaturePadModal" width="600" height="200" style="border: 2px dashed #ddd; border-radius: 8px; background: white; cursor: crosshair; width: 100%;"></canvas>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignatureModal()">
                                                <i class="ti ti-eraser me-1"></i>Hapus Tanda Tangan
                                            </button>
                                        </div>
                                        <input type="hidden" name="ttd_peminjam" id="ttd_peminjam_data_modal">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Column -->
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-header">
                                    <h5 class="card-title text-white mb-0"><i class="ti ti-car me-2"></i>Informasi Kendaraan</h5>
                                </div>
                                <div class="card-body">
                                    @if($kendaraan->foto)
                                        <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" class="img-fluid rounded mb-3" alt="Foto">
                                    @endif
                                    <h4>{{ $kendaraan->nama_kendaraan }}</h4>
                                    <p class="mb-2"><strong>No. Polisi:</strong> {{ $kendaraan->no_polisi }}</p>
                                    <p class="mb-2"><strong>Jenis:</strong> {{ $kendaraan->jenis_kendaraan }}</p>
                                    <p class="mb-2"><strong>Merk:</strong> {{ $kendaraan->merk }} {{ $kendaraan->model }}</p>
                                    <p class="mb-2"><strong>Warna:</strong> {{ $kendaraan->warna ?? '-' }}</p>
                                    <p class="mb-2"><strong>Kapasitas:</strong> {{ $kendaraan->kapasitas ?? '-' }} orang</p>
                                    <p class="mb-0"><strong>Status:</strong> 
                                        @if($kendaraan->status == 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($kendaraan->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitPinjamModal">
                        <i class="ti ti-check me-2"></i>Submit Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePadModal;
    
    $('#modalPinjamKendaraan').on('shown.bs.modal', function() {
        const canvas = document.getElementById('signaturePadModal');
        if (canvas && !signaturePadModal) {
            signaturePadModal = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });
        }
    });
    
    function clearSignatureModal() {
        if (signaturePadModal) {
            signaturePadModal.clear();
        }
    }
    
    function previewIdentitas(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview_identitas_modal');
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
    
    $('#formPeminjamanModal').on('submit', function(e) {
        if (signaturePadModal && !signaturePadModal.isEmpty()) {
            $('#ttd_peminjam_data_modal').val(signaturePadModal.toDataURL());
        } else {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Tanda Tangan Diperlukan',
                text: 'Silakan tanda tangan terlebih dahulu!',
            });
            return false;
        }
    });
</script>
@endpush
