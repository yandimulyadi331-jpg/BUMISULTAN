@extends('layouts.app')
@section('titlepage', 'Ijin Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span>Manajemen Saung Santri / Ijin Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-file-text me-2"></i> Data Ijin Santri</h4>
                    <div>
                        <a href="{{ route('ijin-santri.create') }}" class="btn btn-light btn-sm">
                            <i class="ti ti-plus me-1"></i> Buat Ijin Santri
                        </a>
                        <a href="{{ route('ijin-santri.export-pdf', request()->all()) }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Informasi Alur -->
                <div class="alert alert-info">
                    <h6 class="mb-2"><i class="ti ti-info-circle me-1"></i> Alur Proses Ijin Santri:</h6>
                    <ol class="mb-0" style="font-size: 0.9em;">
                        <li><strong>Admin membuat ijin</strong> → Status: Menunggu TTD Ustadz</li>
                        <li><strong>Download PDF surat ijin</strong> → Serahkan ke Ustadz untuk TTD</li>
                        <li><strong>Verifikasi TTD Ustadz</strong> → Setelah surat di-TTD Ustadz</li>
                        <li><strong>Verifikasi Kepulangan</strong> → Santri boleh pulang dengan surat (untuk TTD Ortu)</li>
                        <li><strong>Verifikasi Kembali</strong> → Upload foto surat ber-TTD Ortu saat santri kembali</li>
                    </ol>
                </div>

                <!-- Filter & Pencarian -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form action="{{ route('ijin-santri.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-lg-3">
                                    <label class="form-label small">Pencarian</label>
                                    <input type="text" name="search" class="form-control" 
                                        placeholder="🔍 Cari No. Surat/Nama/NIS..." 
                                        value="{{ Request('search') }}">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label small">Tanggal Dari</label>
                                    <input type="date" name="tanggal_dari" class="form-control" 
                                        value="{{ Request('tanggal_dari') }}">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label small">Tanggal Sampai</label>
                                    <input type="date" name="tanggal_sampai" class="form-control" 
                                        value="{{ Request('tanggal_sampai') }}">
                                </div>
                                <div class="col-lg-2">
                                    <label class="form-label small">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ Request('status') == 'pending' ? 'selected' : '' }}>Menunggu TTD</option>
                                        <option value="ttd_ustadz" {{ Request('status') == 'ttd_ustadz' ? 'selected' : '' }}>Sudah TTD</option>
                                        <option value="dipulangkan" {{ Request('status') == 'dipulangkan' ? 'selected' : '' }}>Dipulangkan</option>
                                        <option value="kembali" {{ Request('status') == 'kembali' ? 'selected' : '' }}>Sudah Kembali</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label small">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="ti ti-search me-1"></i> Cari
                                        </button>
                                        <a href="{{ route('ijin-santri.index') }}" class="btn btn-secondary flex-fill">
                                            <i class="ti ti-refresh me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @if(Request('search') || Request('tanggal_dari') || Request('tanggal_sampai') || Request('status'))
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="ti ti-filter"></i> Filter aktif: 
                                        Menampilkan hasil pencarian/filter
                                    </small>
                                </div>
                            @else
                                <div class="mt-2">
                                    <small class="text-info">
                                        <i class="ti ti-info-circle"></i> Menampilkan ijin hari ini. 
                                        Gunakan filter tanggal untuk melihat riwayat.
                                    </small>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th>No. Surat</th>
                                <th>Nama Santri</th>
                                <th>Tanggal Ijin</th>
                                <th>Rencana Kembali</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ijinSantri as $index => $ijin)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td><strong>{{ $ijin->nomor_surat }}</strong></td>
                                    <td>
                                        <strong>{{ $ijin->santri->nama_lengkap }}</strong><br>
                                        <small class="text-muted">NIS: {{ $ijin->santri->nis }}</small>
                                    </td>
                                    <td>{{ $ijin->tanggal_ijin->format('d/m/Y') }}</td>
                                    <td>{{ $ijin->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($ijin->alasan_ijin, 50) }}</td>
                                    <td>{!! $ijin->status_label !!}</td>
                                    <td>
                                        <div class="btn-group-vertical w-100" role="group">
                                            <a href="{{ route('ijin-santri.show', $ijin->id) }}" 
                                               class="btn btn-sm btn-info mb-1">
                                                <i class="ti ti-eye"></i> Detail
                                            </a>

                                            @if($ijin->status == 'pending')
                                                <a href="{{ route('ijin-santri.download-pdf', $ijin->id) }}" 
                                                   class="btn btn-sm btn-danger mb-1">
                                                    <i class="ti ti-file-type-pdf"></i> Download PDF
                                                </a>
                                                <button type="button" class="btn btn-sm btn-warning mb-1"
                                                        onclick="verifikasiTtdUstadz({{ $ijin->id }})">
                                                    <i class="ti ti-check"></i> TTD Ustadz
                                                </button>
                                            @endif

                                            @if($ijin->status == 'ttd_ustadz')
                                                <button type="button" class="btn btn-sm btn-primary mb-1"
                                                        onclick="verifikasiKepulangan({{ $ijin->id }})">
                                                    <i class="ti ti-plane-departure"></i> Pulangkan
                                                </button>
                                            @endif

                                            @if($ijin->status == 'dipulangkan')
                                                <button type="button" class="btn btn-sm btn-success mb-1"
                                                        onclick="verifikasiKembali({{ $ijin->id }})">
                                                    <i class="ti ti-plane-arrival"></i> Sudah Kembali
                                                </button>
                                            @endif

                                            <!-- Tombol Hapus -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-delete-ijin" 
                                                    data-id="{{ $ijin->id }}"
                                                    data-nomor="{{ $ijin->nomor_surat }}"
                                                    data-nama="{{ $ijin->santri->nama_lengkap }}">
                                                <i class="ti ti-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="ti ti-file-off" style="font-size: 3em;"></i><br>
                                        Belum ada data ijin santri
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi TTD Ustadz -->
<div class="modal fade" id="modalTtdUstadz" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTtdUstadz" method="POST">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Verifikasi TTD Ustadz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Konfirmasi bahwa surat ijin sudah di-TTD oleh Ustadz?</p>
                    <div class="alert alert-info">
                        <small>Setelah verifikasi ini, santri siap untuk dipulangkan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ya, Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Kepulangan -->
<div class="modal fade" id="modalKepulangan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKepulangan" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Verifikasi Kepulangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Konfirmasi bahwa santri sudah dipulangkan?</p>
                    <div class="alert alert-warning">
                        <small>Status santri akan berubah menjadi <strong>PULANG</strong>. Santri membawa surat untuk di-TTD orang tua.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Pulangkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Kembali -->
<div class="modal fade" id="modalKembali" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKembali" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Verifikasi Kembali</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Santri sudah kembali ke pesantren. Upload foto surat ijin yang sudah di-TTD orang tua.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kembali Aktual <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_aktual" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Surat (Sudah TTD Ortu) <span class="text-danger">*</span></label>
                        <input type="file" name="foto_surat_ttd_ortu" class="form-control" 
                               accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG. Max 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Verifikasi Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    function verifikasiTtdUstadz(id) {
        const form = document.getElementById('formTtdUstadz');
        form.action = `/ijin-santri/${id}/verifikasi-ttd-ustadz`;
        const modal = new bootstrap.Modal(document.getElementById('modalTtdUstadz'));
        modal.show();
    }

    function verifikasiKepulangan(id) {
        const form = document.getElementById('formKepulangan');
        form.action = `/ijin-santri/${id}/verifikasi-kepulangan`;
        const modal = new bootstrap.Modal(document.getElementById('modalKepulangan'));
        modal.show();
    }

    function verifikasiKembali(id) {
        const form = document.getElementById('formKembali');
        form.action = `/ijin-santri/${id}/verifikasi-kembali`;
        const modal = new bootstrap.Modal(document.getElementById('modalKembali'));
        modal.show();
    }

    // SweetAlert2 untuk konfirmasi hapus
    $(document).on('click', '.btn-delete-ijin', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const nomor = $(this).data('nomor');
        const nama = $(this).data('nama');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus ijin ini?<br>
                   <strong>No. Surat:</strong> ${nomor}<br>
                   <strong>Santri:</strong> ${nama}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="ti ti-x me-1"></i> Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Buat form dan submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/ijin-santri/${id}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Auto hide alert setelah 3 detik
    @if(session('success') || session('error'))
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    @endif
</script>
@endpush
