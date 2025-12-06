@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan Belanja Khidmat')

@section('content')
@section('navigasi')
    <span><a href="{{ route('khidmat.index') }}">Saung Santri / Khidmat</a> / Laporan Keuangan</span>
@endsection

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header bg-gradient-success">
                <h5 class="text-white mb-0">
                    <i class="ti ti-report-money me-2"></i>Laporan Keuangan Belanja - {{ $jadwal->nama_kelompok }}
                </h5>
            </div>

            <div class="card-body">
                <!-- Notifikasi dengan Toastr (tidak menggunakan alert bawaan browser) -->
                
                <!-- Info Jadwal -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Tanggal:</strong> {{ $jadwal->tanggal_jadwal->format('d F Y') }}<br>
                                <strong>Petugas:</strong> 
                                @foreach($jadwal->petugas as $petugas)
                                    <span class="badge bg-info">{{ $petugas->santri->nama_lengkap }}</span>
                                @endforeach
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Saldo Awal:</strong> <span class="text-success">Rp {{ number_format($jadwal->saldo_awal, 0, ',', '.') }}</span><br>
                                <strong>Saldo Masuk:</strong> <span class="text-info">Rp {{ number_format($jadwal->saldo_masuk, 0, ',', '.') }}</span><br>
                                <strong>Total Belanja:</strong> <span class="text-danger">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</span><br>
                                <strong>Saldo Akhir:</strong> <span class="text-primary fs-5">Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Template & Import Excel -->
                <div class="card mb-4">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="mb-0"><i class="ti ti-file-spreadsheet me-2"></i>Import Data Belanja dari Excel</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Langkah-langkah:</strong></p>
                                <ol class="mb-0">
                                    <li>Download template Excel</li>
                                    <li>Isi data belanja di file Excel</li>
                                    <li>Upload file yang sudah diisi</li>
                                </ol>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('khidmat.download-template', $jadwal->id) }}" 
                                   class="btn btn-success mb-2">
                                    <i class="ti ti-download me-2"></i>Download Template Excel
                                </a>
                                <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="ti ti-upload me-2"></i>Upload File Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Belanja -->
                <form action="{{ route('khidmat.store-belanja', $jadwal->id) }}" method="POST" id="formBelanja">
                    @csrf
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="ti ti-shopping-cart me-2"></i>Rincian Belanja</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addItem">
                                <i class="ti ti-plus me-1"></i> Tambah Item
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="belanjaContainer">
                                @if($jadwal->belanja->count() > 0)
                                    @foreach($jadwal->belanja as $index => $belanja)
                                    <div class="belanja-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Item #<span class="item-number">{{ $index + 1 }}</span></h6>
                                            <button type="button" class="btn btn-sm btn-danger remove-item">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Nama Barang</label>
                                                <input type="text" name="items[{{ $index }}][nama_barang]" 
                                                       class="form-control" value="{{ $belanja->nama_barang }}" required>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" name="items[{{ $index }}][jumlah]" 
                                                       class="form-control jumlah" value="{{ $belanja->jumlah }}" min="1" required>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="form-label">Satuan</label>
                                                <input type="text" name="items[{{ $index }}][satuan]" 
                                                       class="form-control" value="{{ $belanja->satuan }}" placeholder="kg, buah, dll" required>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Harga Satuan</label>
                                                <input type="number" name="items[{{ $index }}][harga_satuan]" 
                                                       class="form-control harga-satuan" value="{{ $belanja->harga_satuan }}" min="0" required>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label class="form-label">Keterangan</label>
                                                <input type="text" name="items[{{ $index }}][keterangan]" 
                                                       class="form-control" value="{{ $belanja->keterangan }}" placeholder="Opsional">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Total Harga</label>
                                                <input type="text" class="form-control total-harga" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <!-- Default empty item -->
                                    <div class="belanja-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Item #<span class="item-number">1</span></h6>
                                            <button type="button" class="btn btn-sm btn-danger remove-item">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Nama Barang</label>
                                                <input type="text" name="items[0][nama_barang]" class="form-control" required>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" name="items[0][jumlah]" class="form-control jumlah" min="1" required>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="form-label">Satuan</label>
                                                <input type="text" name="items[0][satuan]" class="form-control" placeholder="kg, buah, dll" required>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Harga Satuan</label>
                                                <input type="number" name="items[0][harga_satuan]" class="form-control harga-satuan" min="0" required>
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label class="form-label">Keterangan</label>
                                                <input type="text" name="items[0][keterangan]" class="form-control" placeholder="Opsional">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Total Harga</label>
                                                <input type="text" class="form-control total-harga" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="alert alert-info mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Grand Total Belanja:</strong>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <h5 class="mb-0 text-danger" id="grandTotal">Rp 0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('khidmat.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-device-floppy me-2"></i>Simpan Laporan
                        </button>
                    </div>
                </form>

                <!-- Upload Foto Belanja -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-camera me-2"></i>Upload Foto Rincian Belanja</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('khidmat.upload-foto', $jadwal->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8 mb-2">
                                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-upload me-2"></i>Upload
                                    </button>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="keterangan" class="form-control" placeholder="Keterangan foto (opsional)">
                                </div>
                            </div>
                        </form>

                        @if($jadwal->foto->count() > 0)
                        <hr>
                        <div class="row mt-3">
                            @foreach($jadwal->foto as $foto)
                            <div class="col-md-3 mb-3">
                                <div class="position-relative">
                                    <img src="{{ Storage::url($foto->path_file) }}" class="img-fluid rounded" alt="Foto Belanja">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-foto" 
                                            data-id="{{ $foto->id }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    @if($foto->keterangan)
                                    <p class="small mt-1 mb-0">{{ $foto->keterangan }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = {{ $jadwal->belanja->count() > 0 ? $jadwal->belanja->count() : 1 }};

$(document).ready(function() {
    // Calculate totals on load
    calculateAllTotals();

    // Add new item
    $('#addItem').click(function() {
        const newItem = `
            <div class="belanja-item mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Item #<span class="item-number">${itemIndex + 1}</span></h6>
                    <button type="button" class="btn btn-sm btn-danger remove-item">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="items[${itemIndex}][nama_barang]" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control jumlah" min="1" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="items[${itemIndex}][satuan]" class="form-control" placeholder="kg, buah, dll" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Harga Satuan</label>
                        <input type="number" name="items[${itemIndex}][harga_satuan]" class="form-control harga-satuan" min="0" required>
                    </div>
                    <div class="col-md-8 mb-2">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="items[${itemIndex}][keterangan]" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Total Harga</label>
                        <input type="text" class="form-control total-harga" readonly>
                    </div>
                </div>
            </div>
        `;
        $('#belanjaContainer').append(newItem);
        itemIndex++;
        updateItemNumbers();
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        if($('.belanja-item').length > 1) {
            $(this).closest('.belanja-item').remove();
            updateItemNumbers();
            calculateAllTotals();
        } else {
            alert('Minimal harus ada 1 item belanja');
        }
    });

    // Calculate item total on input change
    $(document).on('input', '.jumlah, .harga-satuan', function() {
        const item = $(this).closest('.belanja-item');
        calculateItemTotal(item);
        calculateAllTotals();
    });

    // Delete foto
    $('.delete-foto').click(function() {
        if(confirm('Hapus foto ini?')) {
            const id = $(this).data('id');
            const btn = $(this);
            
            $.ajax({
                url: `/khidmat/foto/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        btn.closest('.col-md-3').remove();
                        toastr.success(response.message);
                    }
                },
                error: function() {
                    toastr.error('Gagal menghapus foto');
                }
            });
        }
    });
});

function calculateItemTotal(item) {
    const jumlah = parseFloat(item.find('.jumlah').val()) || 0;
    const hargaSatuan = parseFloat(item.find('.harga-satuan').val()) || 0;
    const total = jumlah * hargaSatuan;
    
    item.find('.total-harga').val('Rp ' + formatNumber(total));
}

function calculateAllTotals() {
    let grandTotal = 0;
    
    $('.belanja-item').each(function() {
        calculateItemTotal($(this));
        
        const jumlah = parseFloat($(this).find('.jumlah').val()) || 0;
        const hargaSatuan = parseFloat($(this).find('.harga-satuan').val()) || 0;
        grandTotal += jumlah * hargaSatuan;
    });
    
    $('#grandTotal').text('Rp ' + formatNumber(grandTotal));
}

function updateItemNumbers() {
    $('.belanja-item').each(function(index) {
        $(this).find('.item-number').text(index + 1);
    });
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('khidmat.import-belanja', $jadwal->id) }}" method="POST" enctype="multipart/form-data" id="formImportExcel">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="ti ti-upload me-2"></i>Upload File Excel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Perhatian:</strong> Upload file akan menghapus semua data belanja yang sudah ada dan menggantinya dengan data dari file Excel.
                    </div>
                    
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="fileExcel" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Format: .xlsx atau .xls (Max: 2MB)</small>
                    </div>

                    <div class="alert alert-info mb-0">
                        <strong>Format Excel harus sesuai template:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Kolom 1: Nama Barang</li>
                            <li>Kolom 2: Jumlah (angka)</li>
                            <li>Kolom 3: Satuan (kg, liter, pcs, dll)</li>
                            <li>Kolom 4: Harga Satuan (angka)</li>
                            <li>Kolom 5: Keterangan (opsional)</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnImport">
                        <i class="ti ti-upload me-2"></i>Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('myscript')
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

// Konfirmasi sebelum import dengan SweetAlert
$('#formImportExcel').on('submit', function(e) {
    e.preventDefault();
    
    const fileInput = $('#fileExcel')[0];
    if (!fileInput.files || fileInput.files.length === 0) {
        toastr.error('Silakan pilih file Excel terlebih dahulu');
        return false;
    }

    const fileName = fileInput.files[0].name;
    const fileSize = (fileInput.files[0].size / 1024 / 1024).toFixed(2); // MB

    Swal.fire({
        title: 'Konfirmasi Import',
        html: `
            <div class="text-start">
                <p><strong>File:</strong> ${fileName}</p>
                <p><strong>Ukuran:</strong> ${fileSize} MB</p>
                <hr>
                <p class="text-danger"><i class="ti ti-alert-triangle"></i> Data belanja yang sudah ada akan dihapus dan diganti dengan data dari file ini.</p>
                <p>Apakah Anda yakin ingin melanjutkan?</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Import Sekarang!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Sedang Memproses...',
                html: 'Mohon tunggu, sedang mengimport data dari Excel',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            e.target.submit();
        }
    });
});
</script>
@endpush

@endsection
