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
                            <i class="ti ti-chef-hat me-2"></i>Jadwal Khidmat - Belanja Masak Santri (7 Hari Terbaru)
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

            <div class="card-body">
                <!-- Notifikasi menggunakan Toastr -->
                
                <!-- Filter Pencarian -->
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">
                            <i class="ti ti-search me-1"></i>Cari Jadwal Lama:
                        </label>
                        <input type="text" id="searchJadwal" class="form-control" placeholder="Ketik kelompok/tanggal untuk cari jadwal lama...">
                        <small class="text-muted">Kosongkan untuk lihat 7 hari terbaru</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter Status:</label>
                        <select id="filterStatus" class="form-select">
                            <option value="">Semua</option>
                            <option value="belum">Belum Selesai</option>
                            <option value="selesai">Sudah Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-end">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="button" class="btn btn-info" id="btnResetFilter">
                            <i class="ti ti-refresh me-1"></i> Reset (Tampilkan 7 Hari Terbaru)
                        </button>
                    </div>
                </div>
                
                <!-- Table -->
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
                                <td colspan="9" class="text-center">Belum ada jadwal khidmat</td>
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
        pageLength: 10
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
                $('#jadwalKhidmatTable tbody').html('<tr><td colspan="10" class="text-center"><i class="ti ti-loader ti-spin me-2"></i>Mencari jadwal...</td></tr>');
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
            $('#jadwalKhidmatTable tbody').html('<tr><td colspan="10" class="text-center text-muted">Tidak ada data ditemukan</td></tr>');
            return;
        }
        
        data.forEach(function(item, index) {
            const petugasNames = item.petugas.map(p => p.santri.nama_lengkap).join(', ') || '-';
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
