@extends('layouts.app')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Pelanggaran Santri
                </div>
                <h2 class="page-title">
                    Detail Riwayat Pelanggaran - {{ $santri->nama_lengkap }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('pelanggaran-santri.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Santri</h3>
                    </div>
                    <div class="card-body text-center">
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
                                class="avatar avatar-xl mb-3" 
                                style="cursor: pointer;"
                                onclick="showImageModal('{{ $fotoUrl }}')"
                                onerror="this.onerror=null; this.outerHTML='<span class=\'avatar avatar-xl mb-3 bg-azure text-white\'>{{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}</span>';">
                        @else
                        <span class="avatar avatar-xl mb-3 bg-azure text-white">{{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}</span>
                        @endif
                        <h3>{{ $santri->nama_lengkap }}</h3>
                        <p class="text-muted">NIK: {{ $santri->nik ?? '-' }}</p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Status Pelanggaran</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="display-6 fw-bold">{{ $totalPelanggaran }}</div>
                            <div class="text-muted">Total Pelanggaran</div>
                        </div>
                        <div class="mb-3">
                            <div class="display-6 fw-bold">{{ $totalPoint }}</div>
                            <div class="text-muted">Total Point</div>
                        </div>
                        <div class="mb-3">
                            <span class="badge {{ $statusInfo['badge'] }} fs-4 py-2 px-3">
                                {{ $statusInfo['status'] }}
                            </span>
                        </div>

                        @if($totalPelanggaran >= 75)
                        <div class="alert alert-danger mt-3">
                            <h4 class="alert-title">Peringatan!</h4>
                            <div class="text-muted">Santri memerlukan surat peringatan</div>
                            <div class="mt-3">
                                <a href="{{ route('pelanggaran-santri.surat-peringatan', $santri->id) }}"
                                    class="btn btn-danger w-100" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                        <path d="M12 17v-6"></path>
                                        <path d="M9.5 14.5l2.5 2.5l2.5 -2.5"></path>
                                    </svg>
                                    Download Surat Peringatan
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Pelanggaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Foto</th>
                                        <th>Point</th>
                                        <th>Dicatat Oleh</th>
                                        <th class="w-1">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatPelanggaran as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->tanggal_pelanggaran->format('d/m/Y') }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto"
                                                class="avatar avatar-sm" style="cursor: pointer;"
                                                onclick="showImageModal('{{ asset('storage/' . $item->foto) }}')">
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $item->point }}</span></td>
                                        <td>{{ $item->pencatat->name ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @can('pelanggaran-santri.edit')
                                                <a href="{{ route('pelanggaran-santri.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                        <path d="M16 5l3 3"></path>
                                                    </svg>
                                                </a>
                                                @endcan
                                                @can('pelanggaran-santri.delete')
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="confirmDelete({{ $item->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <line x1="4" y1="7" x2="20" y2="7"></line>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                    </svg>
                                                </button>
                                                <form id="delete-form-{{ $item->id }}" 
                                                    action="{{ route('pelanggaran-santri.destroy', $item->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan foto -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Pelanggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus data pelanggaran ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    let deleteItemId = null;

    function confirmDelete(id) {
        deleteItemId = id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteItemId) {
            document.getElementById('delete-form-' + deleteItemId).submit();
        }
    });
</script>
@endpush
