@extends('layouts.app')
@section('titlepage', 'Khidmat - Belanja Masak Santri')

@section('content')
@section('navigasi')
    <span>Saung Santri / Khidmat</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white mb-0">
                            <i class="ti ti-chef-hat me-2"></i>Jadwal Khidmat - Belanja Masak Santri
                        </h5>
                        <small class="text-white-50">Sistem otomatis membuat jadwal baru ketika 7 hari selesai semua</small>
                    </div>
                    <div>
                        <a href="{{ route('khidmat.download-pdf') }}" class="btn btn-light">
                            <i class="ti ti-file-download me-2"></i> Download PDF Keseluruhan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Date Navigation Section -->
            <div class="card-header bg-light border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <a href="{{ route('khidmat.index', ['tanggal' => $tanggalKemarin->toDateString()]) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="ti ti-arrow-left me-1"></i> Kemarin ({{ $tanggalKemarin->format('d/m') }})
                        </a>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="p-3 bg-white rounded border">
                            <h6 class="mb-1 text-muted">Tanggal Khidmat</h6>
                            <h4 class="mb-0 text-primary fw-bold">
                                <i class="ti ti-calendar me-2"></i>{{ $namaHariSelected }}, {{ $tanggalDisplay }}
                            </h4>
                            @if($tanggalSelected->isToday())
                                <span class="badge bg-success mt-2"><i class="ti ti-check me-1"></i>Hari Ini</span>
                            @elseif($tanggalSelected->isPast())
                                <span class="badge bg-secondary mt-2"><i class="ti ti-calendar-off me-1"></i>Tanggal Lalu</span>
                            @else
                                <span class="badge bg-info mt-2"><i class="ti ti-calendar-future me-1"></i>Tanggal Mendatang</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('khidmat.index', ['tanggal' => $tanggalBesok->toDateString()]) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            Besok ({{ $tanggalBesok->format('d/m') }}) <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="col-12 mt-3 text-center">
                        <a href="{{ route('khidmat.index') }}" class="btn btn-warning btn-sm">
                            <i class="ti ti-calendar-today me-1"></i> Kembali ke Hari Ini
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Notifikasi menggunakan Toastr -->
                
                <!-- Status Tanggal Terpilih -->
                @if($jadwal->isEmpty())
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Tidak ada data khidmat</strong> untuk {{ $namaHariSelected }}, {{ $tanggalDisplay }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filter Pencarian (Monitoring/Arsip Lama) -->
                <div class="card mt-3 mb-3 bg-light">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="ti ti-search me-1"></i>Monitor / Cari Jadwal Lama
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label form-label-sm fw-bold">Cari Kelompok / Tanggal:</label>
                                <input type="text" id="searchJadwal" class="form-control form-control-sm" placeholder="Ketik untuk cari jadwal...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm fw-bold">Filter Status:</label>
                                <select id="filterStatus" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="belum">Belum Selesai</option>
                                    <option value="selesai">Sudah Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm d-block">&nbsp;</label>
                                <button type="button" class="btn btn-sm btn-info w-100" id="btnResetFilter">
                                    <i class="ti ti-refresh me-1"></i> Lihat 7 Hari Terakhir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Table Jadwal Hari Ini/Terpilih -->
                @if($jadwal->isNotEmpty())
                <h6 class="mb-3 text-primary fw-bold">
                    <i class="ti ti-calendar-check me-1"></i>Data Khidmat - {{ $namaHariSelected }}, {{ $tanggalDisplay }}
                </h6>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="jadwalKhidmatTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kelompok</th>
                                <th>Tanggal</th>
                                <th>Petugas</th>
                                <th>Saldo Awal</th>
                                <th>Saldo Masuk</th>
                                <th>Total Belanja</th>
                                <th>Saldo Akhir</th>
                                <th>Kebersihan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->nama_kelompok }}</strong></td>
                                <td>{{ $item->tanggal_jadwal->format('d/m/Y') }}</td>
                                <td>
                                    @foreach($item->petugas as $petugas)
                                        <span class="badge bg-info me-1">{{ $petugas->santri->nama_lengkap }}</span>
                                    @endforeach
                                </td>
                                <td class="text-success">Rp {{ number_format($item->saldo_awal, 0, ',', '.') }}</td>
                                <td class="text-info">Rp {{ number_format($item->saldo_masuk, 0, ',', '.') }}</td>
                                <td class="text-danger">Rp {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                                <td class="text-primary"><strong>Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</strong></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input kebersihan-toggle" type="checkbox" 
                                               data-id="{{ $item->id }}" 
                                               {{ $item->status_kebersihan == 'bersih' ? 'checked' : '' }}
                                               {{ $item->status_selesai ? 'disabled' : '' }}>
                                        <label class="form-check-label status-label-{{ $item->id }}">
                                            {{ $item->status_kebersihan == 'bersih' ? 'Bersih' : 'Kotor' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm {{ $item->status_selesai ? 'btn-success' : 'btn-outline-secondary' }} btn-toggle-selesai" 
                                            data-id="{{ $item->id }}" 
                                            data-status="{{ $item->status_selesai ? '1' : '0' }}"
                                            title="{{ $item->status_selesai ? 'Sudah Selesai' : 'Belum Selesai' }}">
                                        <i class="ti {{ $item->status_selesai ? 'ti-circle-check' : 'ti-circle' }} fs-5"></i>
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('khidmat.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('khidmat.laporan', $item->id) }}" class="btn btn-sm btn-success" title="Laporan Keuangan">
                                            <i class="ti ti-report-money"></i>
                                        </a>
                                        <a href="{{ route('khidmat.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('khidmat.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus" 
                                                    data-kelompok="{{ $item->nama_kelompok }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">Belum ada jadwal khidmat</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush

@push('myscript')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
// Toastr Notifications dari Session
@if(session('success'))
    toastr.success('{{ session('success') }}', 'Berhasil!', {
        closeButton: true,
        progressBar: true,
        timeOut: 5000
    });
@endif

@if(session('error'))
    toastr.error('{{ session('error') }}', 'Gagal!', {
        closeButton: true,
        progressBar: true,
        timeOut: 8000
    });
@endif

$(document).ready(function() {
    console.log('Khidmat JS loaded');
    
    var table = $('#jadwalKhidmatTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        order: [[2, 'desc']],
        pageLength: 10,
        columnDefs: [
            { targets: 0, orderable: false, searchable: false }, // No
            { targets: 1, orderable: true, searchable: true },   // Kelompok
            { targets: 2, orderable: true, searchable: true },   // Tanggal
            { targets: 3, orderable: false, searchable: true },  // Petugas
            { targets: 4, orderable: true, searchable: false },  // Saldo Awal
            { targets: 5, orderable: true, searchable: false },  // Saldo Masuk
            { targets: 6, orderable: true, searchable: false },  // Total Belanja
            { targets: 7, orderable: true, searchable: false },  // Saldo Akhir
            { targets: 8, orderable: false, searchable: false }, // Kebersihan
            { targets: 9, orderable: false, searchable: false }, // Status
            { targets: 10, orderable: false, searchable: false } // Aksi
        ]
    });

    // AJAX Search untuk jadwal lama
    let searchTimeout;
    $('#searchJadwal').on('keyup', function() {
        clearTimeout(searchTimeout);
        const search = $(this).val();
        const status = $('#filterStatus').val();
        
        // Jika kosong, reload halaman untuk tampilkan 7 hari terbaru
        if (search === '' && status === '') {
            searchTimeout = setTimeout(function() {
                window.location.reload();
            }, 500);
            return;
        }
        
        // Delay 500ms untuk AJAX search
        searchTimeout = setTimeout(function() {
            loadJadwalFromSearch(search, status);
        }, 500);
    });
    
    $('#filterStatus').on('change', function() {
        const search = $('#searchJadwal').val();
        const status = $(this).val();
        
        if (search === '' && status === '') {
            window.location.reload();
            return;
        }
        
        loadJadwalFromSearch(search, status);
    });

    $('#btnResetFilter').on('click', function() {
        $('#searchJadwal').val('');
        $('#filterStatus').val('');
        window.location.reload(); // Reload untuk tampilkan 7 hari terbaru
    });
    
    // Function untuk load jadwal via AJAX
    function loadJadwalFromSearch(search, status) {
        $.ajax({
            url: '{{ route("khidmat.search") }}',
            method: 'GET',
            data: {
                search: search,
                status: status
            },
            beforeSend: function() {
                $('#jadwalKhidmatTable tbody').html('<tr><td colspan="11" class="text-center"><i class="ti ti-loader ti-spin me-2"></i>Mencari jadwal...</td></tr>');
            },
            success: function(response) {
                if (response.success) {
                    renderTableData(response.data);
                }
            },
            error: function() {
                toastr.error('Gagal melakukan pencarian');
            }
        });
    }
    
    // Function untuk render data ke table
    function renderTableData(data) {
        table.clear();
        
        if (data.length === 0) {
            $('#jadwalKhidmatTable tbody').html('<tr><td colspan="11" class="text-center text-muted">Tidak ada data ditemukan</td></tr>');
            return;
        }
        
        data.forEach(function(item, index) {
            const petugasNames = item.petugas.map(p => p.santri.nama_lengkap).join(', ') || '-';
            const petugasBadges = item.petugas.map(p => `<span class="badge bg-info me-1">${p.santri.nama_lengkap}</span>`).join('') || '-';
            const kebersihanIcon = item.status_kebersihan === 'bersih' 
                ? '<i class="ti ti-check text-success"></i>' 
                : '<i class="ti ti-x text-danger"></i>';
            const statusIcon = item.status_selesai 
                ? '<i class="ti ti-circle-check text-success fs-4"></i>' 
                : '<i class="ti ti-circle text-muted fs-4"></i>';
                
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_kelompok}</td>
                    <td>${new Date(item.tanggal_jadwal).toLocaleDateString('id-ID')}</td>
                    <td>${petugasBadges}</td>
                    <td>Rp ${parseFloat(item.saldo_awal).toLocaleString('id-ID')}</td>
                    <td>Rp ${parseFloat(item.saldo_masuk).toLocaleString('id-ID')}</td>
                    <td>Rp ${parseFloat(item.total_belanja).toLocaleString('id-ID')}</td>
                    <td>Rp ${parseFloat(item.saldo_akhir).toLocaleString('id-ID')}</td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input kebersihan-toggle" type="checkbox" 
                                   data-id="${item.id}" ${item.status_kebersihan === 'bersih' ? 'checked' : ''}>
                            <label class="form-check-label status-label-${item.id}">
                                ${item.status_kebersihan === 'bersih' ? 'Bersih' : 'Kotor'}
                            </label>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-toggle-selesai" 
                                data-id="${item.id}" data-status="${item.status_selesai ? '1' : '0'}">
                            ${statusIcon}
                        </button>
                    </td>
                    <td>
                        <a href="/khidmat/${item.id}/laporan" class="btn btn-sm btn-warning" title="Input Belanja">
                            <i class="ti ti-file-invoice"></i>
                        </a>
                        <a href="/khidmat/${item.id}" class="btn btn-sm btn-info" title="Detail">
                            <i class="ti ti-eye"></i>
                        </a>
                        <form action="/khidmat/${item.id}" method="POST" class="d-inline delete-form">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-kelompok="${item.nama_kelompok}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            `;
            
            table.row.add($(row)).draw(false);
        });
    }

    // Toggle kebersihan (dengan event delegation)
    $(document).on('change', '.kebersihan-toggle', function() {
        const id = $(this).data('id');
        const isChecked = $(this).is(':checked');
        const status = isChecked ? 'bersih' : 'kotor';

        $.ajax({
            url: `/khidmat/${id}/kebersihan`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status_kebersihan: status
            },
            success: function(response) {
                if(response.success) {
                    $(`.status-label-${id}`).text(status == 'bersih' ? 'Bersih' : 'Kotor');
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Gagal mengupdate status kebersihan');
            }
        });
    });

    // Toggle Status Selesai
    $(document).on('click', '.btn-toggle-selesai', function(e) {
        e.preventDefault();
        console.log('Toggle selesai clicked');
        
        const btn = $(this);
        const id = btn.data('id');
        const currentStatus = btn.data('status');
        const newStatus = currentStatus == 1 ? 'belum selesai' : 'selesai';

        console.log('ID:', id, 'Current:', currentStatus, 'New:', newStatus);

        // Check if Swal is defined
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 not loaded!');
            toastr.error('SweetAlert2 library not loaded');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            html: `Tandai jadwal ini sebagai <strong>${newStatus}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tandai!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/khidmat/${id}/toggle-selesai`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            // Update button appearance
                            if(response.status_selesai) {
                                btn.removeClass('btn-outline-secondary').addClass('btn-success');
                                btn.find('i').removeClass('ti-circle').addClass('ti-circle-check');
                                btn.attr('title', 'Sudah Selesai');
                                btn.data('status', '1');
                                // Disable kebersihan toggle
                                btn.closest('tr').find('.kebersihan-toggle').prop('disabled', true);
                            } else {
                                btn.removeClass('btn-success').addClass('btn-outline-secondary');
                                btn.find('i').removeClass('ti-circle-check').addClass('ti-circle');
                                btn.attr('title', 'Belum Selesai');
                                btn.data('status', '0');
                                // Enable kebersihan toggle
                                btn.closest('tr').find('.kebersihan-toggle').prop('disabled', false);
                            }
                            
                            toastr.success(response.message);

                            // Jika semua sudah selesai, reload halaman untuk generate jadwal baru
                            if(response.all_completed) {
                                Swal.fire({
                                    title: 'Semua Jadwal Selesai!',
                                    html: 'Sistem akan membuat jadwal baru untuk minggu berikutnya.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    timer: 3000
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Gagal mengupdate status');
                    }
                });
            }
        });
    });

    // Konfirmasi delete dengan SweetAlert (dengan event delegation untuk DataTables)
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        console.log('Delete button clicked');
        
        const form = $(this).closest('form');
        const kelompok = $(this).data('kelompok');
        
        console.log('Form:', form, 'Kelompok:', kelompok);

        // Check if Swal is defined
        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 not loaded!');
            toastr.error('SweetAlert2 library not loaded');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus jadwal khidmat <strong>${kelompok}</strong>?<br><br><span class="text-danger">Data yang dihapus tidak dapat dikembalikan!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush

@endsection
