@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Pelanggaran Santri
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Santri & Pelanggaran</h3>
                <div class="ms-auto">
                    <a href="{{ route('pelanggaran-santri.laporan') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                            <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                            <line x1="9" y1="12" x2="9.01" y2="12"></line>
                            <line x1="13" y1="12" x2="15" y2="12"></line>
                            <line x1="9" y1="16" x2="9.01" y2="16"></line>
                            <line x1="13" y1="16" x2="15" y2="16"></line>
                        </svg>
                        Laporan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cari Santri</label>
                            <select name="santri_id" class="form-select">
                                <option value="">Semua Santri</option>
                                @foreach($allSantri as $santri)
                                <option value="{{ $santri->id }}" {{ request('santri_id')==$santri->id ? 'selected' : '' }}>
                                    {{ $santri->nama_lengkap }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Santri</label>
                            <select name="status_santri" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status_santri')=='aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status_santri')=='nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-vcenter card-table table-striped">
                    <thead style="position: sticky; top: 0; background-color: #fff; z-index: 10;">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Santri</th>
                            <th>NIK</th>
                            <th>Total Pelanggaran</th>
                            <th>Total Point</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santriList as $santri)
                        <tr id="santri-row-{{ $santri->id }}">
                            <td>{{ ($santriList->currentPage() - 1) * $santriList->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($santri->foto)
                                    @php
                                    // Cek apakah path sudah lengkap atau hanya nama file
                                    if(Str::contains($santri->foto, '/')) {
                                        $fotoUrl = Storage::url($santri->foto);
                                    } else {
                                        // Jika hanya nama file, tambahkan folder santri/
                                        $fotoUrl = asset('storage/santri/' . $santri->foto);
                                    }
                                    @endphp
                                <img src="{{ $fotoUrl }}" alt="Foto Santri"
                                    class="avatar avatar-sm" style="cursor: pointer;"
                                    onclick="showImageModal('{{ $fotoUrl }}')"
                                    onerror="this.onerror=null; this.outerHTML='<span class=\'avatar avatar-sm bg-azure text-white\'>{{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}</span>';">
                                @else
                                <span class="avatar avatar-sm bg-azure text-white">
                                    {{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $santri->nama_lengkap }}</strong>
                                <div class="text-muted small">{{ $santri->nama_panggilan ?? '-' }}</div>
                            </td>
                            <td>{{ $santri->nik ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info text-white fs-5" id="total-{{ $santri->id }}">
                                    {{ $santri->total_pelanggaran }}x
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary" id="point-{{ $santri->id }}">
                                    {{ $santri->total_point }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $santri->status_info['badge'] }} text-white" id="status-{{ $santri->id }}">
                                    {{ $santri->status_info['status'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="openTambahModal({{ $santri->id }}, '{{ $santri->nama_lengkap }}', '{{ $santri->nik }}')"
                                            title="Tambah Pelanggaran">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
                                    <a href="{{ route('pelanggaran-santri.show', $santri->id) }}"
                                        class="btn btn-sm btn-info" title="Detail Riwayat">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                        </svg>
                                    </a>

                                    @if($santri->total_point >= 8)
                                    <a href="{{ route('pelanggaran-santri.surat-peringatan', $santri->id) }}"
                                        class="btn btn-sm btn-danger" target="_blank" title="Surat Peringatan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                            <line x1="9" y1="7" x2="10" y2="7"></line>
                                            <line x1="9" y1="13" x2="15" y2="13"></line>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data santri</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $santriList->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelanggaran -->
<div class="modal fade" id="tambahPelanggaranModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambahPelanggaran" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="modal_user_id">
                    <input type="hidden" name="nama_santri" id="modal_nama_santri">
                    <input type="hidden" name="nik_santri" id="modal_nik_santri">

                    <div class="mb-3">
                        <label class="form-label">Santri</label>
                        <input type="text" class="form-control" id="display_nama_santri" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Tanggal Pelanggaran</label>
                                <input type="date" name="tanggal_pelanggaran" class="form-control" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label required">Point Pelanggaran</label>
                                <input type="number" name="point" class="form-control" 
                                       value="1" min="1" max="10" required>
                                <small class="text-muted">Range 1-10 (1-4: Ringan, 5-7: Sedang, 8-10: Berat)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Foto Pelanggaran</label>
                                <input type="file" name="foto" class="form-control" accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="text-muted">Max 5MB (JPG, JPEG, PNG)</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Keterangan Pelanggaran</label>
                        <textarea name="keterangan" class="form-control" rows="4" required
                                  placeholder="Jelaskan detail pelanggaran yang dilakukan santri..."></textarea>
                    </div>

                    <div class="mb-3" id="preview-container" style="display: none;">
                        <label class="form-label">Preview Foto</label>
                        <div>
                            <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Informasi:</strong> Masukkan point pelanggaran 1-10 berdasarkan tingkat pelanggaran:<br>
                        • 1-4: Ringan | • 5-7: Sedang | • 8-10: Berat
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Simpan Pelanggaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    // Show image modal
    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    // Open tambah modal
    function openTambahModal(userId, namaSantri, nikSantri) {
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('modal_nama_santri').value = namaSantri;
        document.getElementById('modal_nik_santri').value = nikSantri;
        document.getElementById('display_nama_santri').value = namaSantri + ' (' + (nikSantri || 'No NIK') + ')';
        
        // Reset form
        document.getElementById('formTambahPelanggaran').reset();
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('modal_nama_santri').value = namaSantri;
        document.getElementById('modal_nik_santri').value = nikSantri;
        document.getElementById('display_nama_santri').value = namaSantri + ' (' + (nikSantri || 'No NIK') + ')';
        document.getElementById('preview-container').style.display = 'none';
        
        var myModal = new bootstrap.Modal(document.getElementById('tambahPelanggaranModal'));
        myModal.show();
    }

    // Preview image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById('preview-container').style.display = 'none';
        }
    }

    // Submit form via AJAX
    document.getElementById('formTambahPelanggaran').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var submitBtn = this.querySelector('button[type="submit"]');
        var originalBtnText = submitBtn.innerHTML;
        
        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        
        fetch('{{ route("pelanggaran-santri.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tutup modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('tambahPelanggaranModal'));
                modal.hide();
                
                // Reload halaman untuk update data
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
</script>
@endpush
