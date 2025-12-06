@extends('layouts.app')
@section('titlepage', 'Distribusi Hadiah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Distribusi Hadiah
@endsection

<!-- Navigation Tabs -->
@include('majlistaklim.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-gift me-2"></i>Distribusi Hadiah kepada Jamaah</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('majlistaklim.distribusi.store') }}" method="POST" id="formDistribusi">
                    @csrf
                    
                    <!-- Pilih Tipe Penerima -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Tipe Penerima <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipe_penerima" id="tipeJamaah" value="jamaah" checked autocomplete="off">
                                <label class="btn btn-outline-primary" for="tipeJamaah">
                                    <i class="ti ti-users me-2"></i>Jamaah Terdaftar
                                </label>

                                <input type="radio" class="btn-check" name="tipe_penerima" id="tipeUmum" value="umum" autocomplete="off">
                                <label class="btn btn-outline-success" for="tipeUmum">
                                    <i class="ti ti-user me-2"></i>Penerima Lain (Non-Jamaah)
                                </label>
                            </div>
                        </div>

                        <!-- Section Jamaah Terdaftar -->
                        <div class="col-md-12" id="sectionJamaah">
                            <label class="form-label fw-bold">Pilih Jamaah Yayasan MASAR <span class="text-danger">*</span></label>
                            <select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror">
                                <option value="">-- Cari Jamaah (ketik nama) --</option>
                                @foreach($jamaahList as $jamaah)
                                    <option value="{{ $jamaah->kode_yayasan }}" {{ old('jamaah_id') == $jamaah->kode_yayasan ? 'selected' : '' }}>
                                        {{ $jamaah->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jamaah_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section Penerima Lain (Non-Jamaah) -->
                        <div class="col-md-12" id="sectionUmum" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                    <input type="text" name="penerima_nama_umum" id="penerima_nama_umum" class="form-control @error('penerima_nama_umum') is-invalid @enderror" 
                                           value="{{ old('penerima_nama_umum') }}" placeholder="Nama lengkap penerima">
                                    @error('penerima_nama_umum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">No HP/WA</label>
                                    <input type="text" name="penerima_hp_umum" id="penerima_hp_umum" class="form-control" 
                                           value="{{ old('penerima_hp_umum') }}" placeholder="08xxxx">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Alamat/Keterangan</label>
                                    <input type="text" name="penerima_alamat_umum" id="penerima_alamat_umum" class="form-control" 
                                           value="{{ old('penerima_alamat_umum') }}" placeholder="Alamat singkat">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DAFTAR HADIAH (Multiple Rows) -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0">Daftar Hadiah yang Didistribusikan</label>
                                <button type="button" class="btn btn-sm btn-primary" id="btnTambahHadiah">
                                    <i class="ti ti-plus me-1"></i> Tambah Hadiah
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableHadiah">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="30%">Hadiah</th>
                                            <th width="20%">Ukuran/Detail</th>
                                            <th width="12%">Jumlah</th>
                                            <th width="18%">Stok Info</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hadiahRows">
                                        <!-- Row pertama (default) -->
                                        <tr class="hadiah-row">
                                            <td class="text-center row-number">1</td>
                                            <td>
                                                <select name="hadiah_id[]" class="form-select form-select-sm hadiah-select" required>
                                                    <option value="">-- Pilih Hadiah --</option>
                                                    @foreach($hadiahList as $hadiah)
                                                        <option value="{{ $hadiah->id }}" 
                                                                data-stok="{{ $hadiah->stok_tersedia }}"
                                                                data-stok-ukuran="{{ json_encode($hadiah->stok_ukuran ?? []) }}"
                                                                data-nama="{{ $hadiah->nama_hadiah }}">
                                                            {{ $hadiah->kode_hadiah }} - {{ $hadiah->nama_hadiah }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <!-- Single Ukuran (default) -->
                                                <select name="ukuran[]" class="form-select form-select-sm ukuran-select mb-1">
                                                    <option value="">-</option>
                                                </select>
                                                <!-- Button untuk breakdown ukuran multiple -->
                                                <button type="button" class="btn btn-xs btn-outline-info btn-breakdown-ukuran" style="display:none;">
                                                    <i class="ti ti-list-details"></i> Detail Ukuran
                                                </button>
                                                <!-- Hidden input untuk menyimpan breakdown -->
                                                <input type="hidden" name="ukuran_breakdown[]" class="ukuran-breakdown-data" value="">
                                            </td>
                                            <td>
                                                <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" 
                                                       value="1" min="1" required readonly>
                                                <small class="text-muted jumlah-note">Manual/Auto</small>
                                            </td>
                                            <td>
                                                <small class="text-muted stok-info">Pilih hadiah</small>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-row" disabled>
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="row">
                        <!-- Tanggal Distribusi -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Distribusi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_distribusi" class="form-control @error('tanggal_distribusi') is-invalid @enderror" 
                                   value="{{ old('tanggal_distribusi', date('Y-m-d')) }}" required>
                            @error('tanggal_distribusi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Metode Distribusi -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Metode Distribusi</label>
                            <select name="metode_distribusi" class="form-select">
                                <option value="langsung" {{ old('metode_distribusi') == 'langsung' ? 'selected' : '' }}>Langsung</option>
                                <option value="undian" {{ old('metode_distribusi') == 'undian' ? 'selected' : '' }}>Undian</option>
                                <option value="prestasi" {{ old('metode_distribusi') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                                <option value="kehadiran" {{ old('metode_distribusi') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                            </select>
                        </div>

                        <!-- Penerima -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="penerima" class="form-control @error('penerima') is-invalid @enderror" 
                                   value="{{ old('penerima') }}" placeholder="Nama penerima" required>
                            @error('penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Petugas Distribusi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Petugas Distribusi</label>
                            <input type="text" name="petugas_distribusi" class="form-control" 
                                   value="{{ old('petugas_distribusi', auth()->user()->name ?? '') }}" placeholder="Nama petugas">
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('majlistaklim.hadiah.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Distribusi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Distribusi -->
<div class="modal fade" id="modalDetailDistribusi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-eye me-2"></i>Detail Distribusi Hadiah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Breakdown Ukuran -->
<div class="modal fade" id="modalBreakdownUkuran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-ruler me-2"></i>Detail Ukuran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Isi jumlah untuk setiap ukuran yang diambil:</p>
                <div id="breakdownUkuranContainer">
                    <!-- Will be populated dynamically -->
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Total:</strong> <span id="totalBreakdown">0</span> pcs
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanBreakdown">
                    <i class="ti ti-check me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('mystyle')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .table-bordered th, .table-bordered td {
        vertical-align: middle;
    }
</style>
@endpush

@push('myscript')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Hadiah options untuk clone
        let hadiahOptions = `
            <option value="">-- Pilih Hadiah --</option>
            @foreach($hadiahList as $hadiah)
                <option value="{{ $hadiah->id }}" 
                        data-stok="{{ $hadiah->stok_tersedia }}"
                        data-stok-ukuran="{{ json_encode($hadiah->stok_ukuran ?? []) }}"
                        data-nama="{{ $hadiah->nama_hadiah }}">
                    {{ $hadiah->kode_hadiah }} - {{ $hadiah->nama_hadiah }}
                </option>
            @endforeach
        `;

        // Initialize Select2 for Jamaah
        $('.select2-jamaah').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari Jamaah --'
        });

        // Toggle antara Jamaah dan Umum
        $('input[name="tipe_penerima"]').change(function() {
            let tipe = $(this).val();
            
            if (tipe === 'jamaah') {
                $('#sectionJamaah').show();
                $('#sectionUmum').hide();
                $('#jamaah_id').prop('required', true);
                $('#penerima_nama_umum').prop('required', false);
            } else {
                $('#sectionJamaah').hide();
                $('#sectionUmum').show();
                $('#jamaah_id').prop('required', false).val('').trigger('change');
                $('#penerima_nama_umum').prop('required', true);
            }
        });

        // Tambah Baris Hadiah
        $('#btnTambahHadiah').click(function() {
            let rowCount = $('#hadiahRows tr').length + 1;
            let newRow = `
                <tr class="hadiah-row">
                    <td class="text-center row-number">${rowCount}</td>
                    <td>
                        <select name="hadiah_id[]" class="form-select form-select-sm hadiah-select" required>
                            ${hadiahOptions}
                        </select>
                    </td>
                    <td>
                        <select name="ukuran[]" class="form-select form-select-sm ukuran-select mb-1">
                            <option value="">-</option>
                        </select>
                        <button type="button" class="btn btn-xs btn-outline-info btn-breakdown-ukuran" style="display:none;">
                            <i class="ti ti-list-details"></i> Detail Ukuran
                        </button>
                        <input type="hidden" name="ukuran_breakdown[]" class="ukuran-breakdown-data" value="">
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" 
                               value="1" min="1" required readonly>
                        <small class="text-muted jumlah-note">Manual/Auto</small>
                    </td>
                    <td>
                        <small class="text-muted stok-info">Pilih hadiah</small>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#hadiahRows').append(newRow);
            updateRowNumbers();
            updateRemoveButtons();
        });

        // Hapus Baris
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
            updateRemoveButtons();
        });

        // Update nomor urut
        function updateRowNumbers() {
            $('#hadiahRows tr').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

        // Update tombol hapus (disable jika hanya 1 baris)
        function updateRemoveButtons() {
            let rowCount = $('#hadiahRows tr').length;
            if (rowCount === 1) {
                $('.btn-remove-row').prop('disabled', true);
            } else {
                $('.btn-remove-row').prop('disabled', false);
            }
        }

        // Ketika hadiah dipilih, populate ukuran
        $(document).on('change', '.hadiah-select', function() {
            let $row = $(this).closest('tr');
            let $ukuranSelect = $row.find('.ukuran-select');
            let $btnBreakdown = $row.find('.btn-breakdown-ukuran');
            let $stokInfo = $row.find('.stok-info');
            let $jumlahInput = $row.find('.jumlah-input');
            let $breakdownData = $row.find('.ukuran-breakdown-data');
            
            let selectedOption = $(this).find('option:selected');
            let stok = selectedOption.data('stok');
            let stokUkuran = selectedOption.data('stok-ukuran');
            
            // Reset
            $ukuranSelect.html('<option value="">-</option>');
            $breakdownData.val('');
            $row.data('stok-ukuran', stokUkuran);
            
            if (stokUkuran && Object.keys(stokUkuran).length > 0) {
                // Ada stok per ukuran - tampilkan opsi breakdown
                $.each(stokUkuran, function(ukuran, jumlah) {
                    $ukuranSelect.append(`<option value="${ukuran}">${ukuran} (Stok: ${jumlah})</option>`);
                });
                
                // Show breakdown button
                $btnBreakdown.show();
                $stokInfo.html('<span class="text-info">Pilih ukuran atau klik Detail Ukuran</span>');
                $ukuranSelect.prop('required', false); // Tidak required jika pakai breakdown
                $jumlahInput.prop('readonly', false).val(1);
            } else {
                // Stok biasa (tanpa ukuran)
                $btnBreakdown.hide();
                $stokInfo.html(`<span class="text-success">Stok: ${stok}</span>`);
                $jumlahInput.attr('max', stok).prop('readonly', false).val(1);
                $ukuranSelect.prop('required', false);
            }
        });

        // Handler button "Detail Ukuran"
        let currentBreakdownRow = null;
        
        $(document).on('click', '.btn-breakdown-ukuran', function() {
            currentBreakdownRow = $(this).closest('tr');
            let stokUkuran = currentBreakdownRow.data('stok-ukuran');
            let existingBreakdown = currentBreakdownRow.find('.ukuran-breakdown-data').val();
            
            // Build form untuk setiap ukuran
            let html = '';
            $.each(stokUkuran, function(ukuran, stokMax) {
                let currentValue = 0;
                if (existingBreakdown) {
                    let breakdown = JSON.parse(existingBreakdown);
                    currentValue = breakdown[ukuran] || 0;
                }
                
                html += `
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Ukuran <strong>${ukuran}</strong></label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control breakdown-input" 
                                   data-ukuran="${ukuran}" 
                                   value="${currentValue}" 
                                   min="0" 
                                   max="${stokMax}" 
                                   placeholder="Max: ${stokMax}">
                            <small class="text-muted">Stok tersedia: ${stokMax}</small>
                        </div>
                    </div>
                `;
            });
            
            $('#breakdownUkuranContainer').html(html);
            calculateTotalBreakdown();
            $('#modalBreakdownUkuran').modal('show');
        });

        // Calculate total saat input breakdown berubah
        $(document).on('input', '.breakdown-input', function() {
            calculateTotalBreakdown();
        });

        function calculateTotalBreakdown() {
            let total = 0;
            $('.breakdown-input').each(function() {
                total += parseInt($(this).val()) || 0;
            });
            $('#totalBreakdown').text(total);
        }

        // Simpan breakdown
        $('#btnSimpanBreakdown').click(function() {
            let breakdown = {};
            let total = 0;
            let valid = true;
            
            $('.breakdown-input').each(function() {
                let ukuran = $(this).data('ukuran');
                let jumlah = parseInt($(this).val()) || 0;
                let maxStok = parseInt($(this).attr('max'));
                
                if (jumlah > maxStok) {
                    Swal.fire('Error!', `Jumlah ukuran ${ukuran} melebihi stok (max: ${maxStok})`, 'error');
                    valid = false;
                    return false;
                }
                
                if (jumlah > 0) {
                    breakdown[ukuran] = jumlah;
                    total += jumlah;
                }
            });
            
            if (!valid) return;
            
            if (total === 0) {
                Swal.fire('Perhatian!', 'Minimal isi 1 ukuran', 'warning');
                return;
            }
            
            // Simpan breakdown ke hidden input
            currentBreakdownRow.find('.ukuran-breakdown-data').val(JSON.stringify(breakdown));
            
            // Update jumlah input dan readonly
            currentBreakdownRow.find('.jumlah-input').val(total).prop('readonly', true);
            
            // Update ukuran select menjadi summary
            let summary = Object.keys(breakdown).map(u => `${u}(${breakdown[u]})`).join(', ');
            currentBreakdownRow.find('.ukuran-select').html(`<option value="">✓ ${summary}</option>`).prop('disabled', true);
            
            // Update stok info
            currentBreakdownRow.find('.stok-info').html(`<span class="text-success">Total: ${total} pcs</span>`);
            
            $('#modalBreakdownUkuran').modal('hide');
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `Detail ukuran disimpan: ${total} pcs`,
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Ketika ukuran dipilih manual (single), update stok info
        $(document).on('change', '.ukuran-select', function() {
            let $row = $(this).closest('tr');
            let $hadiahSelect = $row.find('.hadiah-select');
            let $stokInfo = $row.find('.stok-info');
            let $jumlahInput = $row.find('.jumlah-input');
            
            let selectedOption = $hadiahSelect.find('option:selected');
            let stokUkuran = selectedOption.data('stok-ukuran');
            let ukuranDipilih = $(this).val();
            
            if (ukuranDipilih && stokUkuran[ukuranDipilih]) {
                let stokTersedia = stokUkuran[ukuranDipilih];
                $stokInfo.html(`<span class="text-success">Stok ukuran ${ukuranDipilih}: ${stokTersedia}</span>`);
                $jumlahInput.attr('max', stokTersedia).prop('readonly', false);
            } else {
                $stokInfo.html('<span class="text-muted">-</span>');
                $jumlahInput.removeAttr('max');
            }
        });

        // Validasi sebelum submit
        $('#formDistribusi').submit(function(e) {
            let tipePenerima = $('input[name="tipe_penerima"]:checked').val();
            
            // Validasi berdasarkan tipe penerima
            if (tipePenerima === 'jamaah') {
                let jamaahId = $('#jamaah_id').val();
                if (!jamaahId) {
                    e.preventDefault();
                    Swal.fire('Perhatian!', 'Silakan pilih jamaah terlebih dahulu', 'warning');
                    return false;
                }
            } else {
                let namaPenerima = $('#penerima_nama_umum').val();
                if (!namaPenerima || namaPenerima.trim() === '') {
                    e.preventDefault();
                    Swal.fire('Perhatian!', 'Silakan isi nama penerima', 'warning');
                    $('#penerima_nama_umum').focus();
                    return false;
                }
            }

            // Validasi hadiah
            let isValid = true;
            $('.hadiah-select').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                Swal.fire('Perhatian!', 'Pastikan semua hadiah sudah dipilih', 'warning');
                return false;
            }
        });

        // PRE-SELECT HADIAH dari URL parameter (jika ada) - HARUS DI AKHIR SETELAH SEMUA HANDLER
        @if(isset($selectedHadiahId) && $selectedHadiahId)
        setTimeout(function() {
            let selectedHadiahId = {{ $selectedHadiahId }};
            console.log('Selected Hadiah ID from URL:', selectedHadiahId);
            
            let firstHadiahSelect = $('.hadiah-select').first();
            console.log('First hadiah select found:', firstHadiahSelect.length);
            console.log('Option exists:', firstHadiahSelect.find('option[value="' + selectedHadiahId + '"]').length);
            
            if (firstHadiahSelect.length && firstHadiahSelect.find('option[value="' + selectedHadiahId + '"]').length) {
                firstHadiahSelect.val(selectedHadiahId).trigger('change');
                console.log('Hadiah auto-selected and change triggered!');
            } else {
                console.log('Option not found in dropdown');
            }
        }, 100);
        @endif
    });
</script>
@endpush
