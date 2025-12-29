@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="ti ti-package-export me-2"></i>Detail Barang Keluar</h4>
                <div>
                    <a href="{{ route('barang-keluar.edit', $barangKeluar->id) }}" class="btn btn-warning">
                        <i class="ti ti-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            
            {{-- Status & Kode --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-2">{{ $barangKeluar->kode_transaksi }}</h3>
                            <div class="mb-2">
                                {!! $barangKeluar->status_badge !!}
                                {!! $barangKeluar->prioritas_badge !!}
                                @if($barangKeluar->is_terlambat)
                                    <span class="badge bg-danger">
                                        <i class="ti ti-alert-triangle"></i> Terlambat {{ $barangKeluar->hari_terlambat }} hari
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted mb-0">
                                <small>Dibuat: {{ $barangKeluar->created_at->format('d/m/Y H:i') }} oleh {{ $barangKeluar->creator->name ?? '-' }}</small>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="ti ti-refresh me-1"></i> Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Barang --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-box me-2"></i>Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Jenis Barang</strong></td>
                            <td>: {{ ucfirst(str_replace('_', ' ', $barangKeluar->jenis_barang)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Barang</strong></td>
                            <td>: {{ $barangKeluar->nama_barang }}</td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi</strong></td>
                            <td>: {{ $barangKeluar->deskripsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah</strong></td>
                            <td>: {{ $barangKeluar->jumlah }} {{ $barangKeluar->satuan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kondisi Keluar</strong></td>
                            <td>: <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $barangKeluar->kondisi_keluar)) }}</span></td>
                        </tr>
                        @if($barangKeluar->kondisi_kembali)
                        <tr>
                            <td><strong>Kondisi Kembali</strong></td>
                            <td>: <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $barangKeluar->kondisi_kembali)) }}</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Informasi Pemilik --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-user me-2"></i>Informasi Pemilik</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Nama Pemilik</strong></td>
                            <td>: {{ $barangKeluar->pemilik_barang }}</td>
                        </tr>
                        <tr>
                            <td><strong>Departemen</strong></td>
                            <td>: {{ $barangKeluar->departemen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Telp</strong></td>
                            <td>: {{ $barangKeluar->no_telp_pemilik ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Informasi Vendor --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-building-store me-2"></i>Informasi Vendor</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Nama Vendor</strong></td>
                            <td>: {{ $barangKeluar->nama_vendor }}</td>
                        </tr>
                        <tr>
                            <td><strong>PIC</strong></td>
                            <td>: {{ $barangKeluar->pic_vendor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>: {{ $barangKeluar->alamat_vendor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. Telp</strong></td>
                            <td>: {{ $barangKeluar->no_telp_vendor ?? '-' }}</td>
                        </tr>
                        @if($barangKeluar->rating_vendor)
                        <tr>
                            <td><strong>Rating</strong></td>
                            <td>: 
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star{{ $i <= $barangKeluar->rating_vendor ? '-filled text-warning' : '' }}"></i>
                                @endfor
                            </td>
                        </tr>
                        @endif
                        @if($barangKeluar->review_vendor)
                        <tr>
                            <td><strong>Review</strong></td>
                            <td>: {{ $barangKeluar->review_vendor }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Foto Dokumentasi --}}
            @if($barangKeluar->foto_sebelum || $barangKeluar->foto_sesudah)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-camera me-2"></i>Foto Dokumentasi</h5>
                </div>
                <div class="card-body">
                    @if($barangKeluar->foto_sebelum && count($barangKeluar->foto_sebelum) > 0)
                        <h6 class="mb-3">Foto Sebelum</h6>
                        <div class="row g-2 mb-4">
                            @foreach($barangKeluar->foto_sebelum as $foto)
                            <div class="col-md-3">
                                <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto) }}" class="img-fluid rounded" 
                                         style="width: 100%; height: 150px; object-fit: cover;"
                                         onerror="this.src='{{ asset('assets/template/img/no-image.png') }}'; this.onerror=null;">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    @if($barangKeluar->foto_sesudah && count($barangKeluar->foto_sesudah) > 0)
                        <h6 class="mb-3">Foto Sesudah</h6>
                        <div class="row g-2">
                            @foreach($barangKeluar->foto_sesudah as $foto)
                            <div class="col-md-3">
                                <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto) }}" class="img-fluid rounded" 
                                         style="width: 100%; height: 150px; object-fit: cover;"
                                         onerror="this.src='{{ asset('assets/template/img/no-image.png') }}'; this.onerror=null;">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- History Status --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-history me-2"></i>Riwayat Perubahan Status</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($barangKeluar->histories as $history)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="avatar avatar-sm bg-primary">
                                        <i class="ti ti-circle-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>
                                                @if($history->status_dari)
                                                    {{ ucfirst($history->status_dari) }} → 
                                                @endif
                                                {{ ucfirst($history->status_ke) }}
                                            </strong>
                                        </div>
                                        <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    @if($history->catatan)
                                        <p class="mb-1">{{ $history->catatan }}</p>
                                    @endif
                                    @if($history->foto && is_array($history->foto))
                                        <div class="mt-2">
                                            @foreach($history->foto as $foto)
                                                <a href="{{ asset('storage/' . $foto) }}" target="_blank" class="me-2">
                                                    <img src="{{ asset('storage/' . $foto) }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded"
                                                         onerror="this.style.display='none'">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                    <small class="text-muted">oleh: {{ $history->user->name ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            
            {{-- Timeline --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-calendar-time me-2"></i>Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Keluar</small>
                        <p class="mb-0"><strong>{{ $barangKeluar->tanggal_keluar->format('d M Y, H:i') }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Estimasi Kembali</small>
                        <p class="mb-0">
                            <strong>{{ $barangKeluar->estimasi_kembali ? $barangKeluar->estimasi_kembali->format('d M Y') : '-' }}</strong>
                        </p>
                    </div>
                    @if($barangKeluar->tanggal_kembali)
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Kembali</small>
                        <p class="mb-0"><strong>{{ $barangKeluar->tanggal_kembali->format('d M Y, H:i') }}</strong></p>
                    </div>
                    @endif
                    <div>
                        <small class="text-muted">Durasi</small>
                        <p class="mb-0"><strong>{{ $barangKeluar->durasi_hari }} hari</strong></p>
                    </div>
                </div>
            </div>

            {{-- Biaya --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-cash me-2"></i>Informasi Biaya</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Estimasi Biaya</small>
                        <p class="mb-0"><strong>Rp {{ number_format($barangKeluar->estimasi_biaya, 0, ',', '.') }}</strong></p>
                    </div>
                    @if($barangKeluar->biaya_aktual > 0)
                    <div>
                        <small class="text-muted">Biaya Aktual</small>
                        <p class="mb-0"><strong class="text-primary">Rp {{ number_format($barangKeluar->biaya_aktual, 0, ',', '.') }}</strong></p>
                    </div>
                    @if($barangKeluar->foto_nota)
                    <div class="mt-3">
                        <a href="{{ asset('storage/' . $barangKeluar->foto_nota) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                            <i class="ti ti-file-invoice me-1"></i> Lihat Nota
                        </a>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- Catatan --}}
            @if($barangKeluar->catatan_keluar || $barangKeluar->catatan_kembali || $barangKeluar->catatan_vendor)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-note me-2"></i>Catatan</h5>
                </div>
                <div class="card-body">
                    @if($barangKeluar->catatan_keluar)
                    <div class="mb-3">
                        <small class="text-muted">Catatan Keluar</small>
                        <p class="mb-0">{{ $barangKeluar->catatan_keluar }}</p>
                    </div>
                    @endif
                    @if($barangKeluar->catatan_vendor)
                    <div class="mb-3">
                        <small class="text-muted">Catatan Vendor</small>
                        <p class="mb-0">{{ $barangKeluar->catatan_vendor }}</p>
                    </div>
                    @endif
                    @if($barangKeluar->catatan_kembali)
                    <div>
                        <small class="text-muted">Catatan Kembali</small>
                        <p class="mb-0">{{ $barangKeluar->catatan_kembali }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

{{-- Modal Update Status --}}
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('barang-keluar.update-status', $barangKeluar->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status Baru</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $barangKeluar->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dikirim" {{ $barangKeluar->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="proses" {{ $barangKeluar->status == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai_vendor" {{ $barangKeluar->status == 'selesai_vendor' ? 'selected' : '' }}>Selesai Vendor</option>
                            <option value="diambil" {{ $barangKeluar->status == 'diambil' ? 'selected' : '' }}>Diambil</option>
                            <option value="batal" {{ $barangKeluar->status == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan perubahan status..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Pendukung (Opsional)</label>
                        <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Maksimal 3 foto</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
