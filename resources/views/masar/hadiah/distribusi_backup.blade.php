@extends('layouts.app')
@section('titlepage', 'Distribusi Hadiah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Distribusi Hadiah
@endsection

<!-- Navigation Tabs -->
@include('masar.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-gift me-2"></i>Distribusi Hadiah kepada Jamaah</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('masar.distribusi.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Pilih Hadiah -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pilih Hadiah <span class="text-danger">*</span></label>
                            <select name="hadiah_id" id="hadiah_id" class="form-select @error('hadiah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Hadiah --</option>
                                @foreach($hadiahList as $hadiah)
                                    <option value="{{ $hadiah->id }}" 
                                            data-stok="{{ $hadiah->stok_tersedia }}"
                                            data-nama="{{ $hadiah->nama_hadiah }}"
                                            {{ old('hadiah_id') == $hadiah->id ? 'selected' : '' }}>
                                        {{ $hadiah->kode_hadiah }} - {{ $hadiah->nama_hadiah }} 
                                        (Stok: {{ $hadiah->stok_tersedia }})
                                    </option>
                                @endforeach
                            </select>
                            @error('hadiah_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="stokInfo">Pilih hadiah terlebih dahulu</small>
                        </div>

                        <!-- Ukuran - SELALU TAMPIL -->
                        <div class="col-md-2 mb-3" id="ukuran_container">
                            <label class="form-label">Ukuran <span class="text-danger" id="ukuran_required">*</span></label>
                            <select name="ukuran" id="ukuran_select" class="form-select @error('ukuran') is-invalid @enderror">
                                <option value="">-- Ukuran --</option>
                            </select>
                            @error('ukuran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="stokUkuranInfo">-</small>
                        </div>

                        <!-- Pilih Jamaah dengan Search -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Jamaah <span class="text-danger">*</span></label>
                            <select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror" required>
                                <option value="">-- Cari Jamaah (ketik nama/nomor) --</option>
                                @foreach($jamaahList as $jamaah)
                                    <option value="{{ $jamaah->id }}" {{ old('jamaah_id') == $jamaah->id ? 'selected' : '' }}>
                                        {{ $jamaah->nomor_jamaah }} - {{ $jamaah->nama_jamaah }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jamaah_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                                   value="{{ old('jumlah', 1) }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="stokInfo">Maksimal: -</small>
                        </div>

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

                        <!-- Keterangan -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                        </div>

                        <!-- Penerima -->
                        <div class="col-md-6 mb-3">
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
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('masar.hadiah.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
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

@endsection

@push('mystyle')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</style>
@endpush

@push('myscript')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        console.log('Document ready!');
        console.log('jQuery version:', $.fn.jquery);
        
        // Initialize Select2 for Jamaah dropdown
        $('.select2-jamaah').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Cari Jamaah (ketik nama/nomor) --',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Tidak ada jamaah ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });
        
        // Data hadiah dengan stok_ukuran
        const hadiahData = {!! json_encode($hadiahList->map(function($h) {
            return [
                'id' => $h->id,
                'nama_hadiah' => $h->nama_hadiah,
                'stok' => $h->stok_tersedia,
                'stok_ukuran' => $h->stok_ukuran
            ];
        })->values()) !!};

        // Update stok info dan populate ukuran saat hadiah dipilih
        $('#hadiah_id').change(function() {
            var hadiahId = $(this).val();
            var stok = $(this).find(':selected').data('stok');
            
            const ukuranSelect = $('#ukuran_select');
            const stokUkuranInfo = $('#stokUkuranInfo');
            const ukuranRequired = $('#ukuran_required');
            
            // Reset ukuran
            ukuranSelect.html('<option value="">-- Ukuran --</option>');
            ukuranSelect.removeAttr('required');
            ukuranRequired.hide();
            stokUkuranInfo.text('-');
            $('#stokInfo').text('Pilih hadiah terlebih dahulu');
            $('#jumlah').removeAttr('max');
            
            if (hadiahId) {
                // Cari data hadiah
                const hadiah = hadiahData.find(h => h.id == hadiahId);
                
                if (hadiah && hadiah.stok_ukuran && Object.keys(hadiah.stok_ukuran).length > 0) {
                    // Ada stok ukuran - WAJIB pilih ukuran
                    ukuranSelect.attr('required', true);
                    ukuranRequired.show();
                    
                    // Populate ukuran dari stok_ukuran
                    Object.entries(hadiah.stok_ukuran).forEach(([ukuran, stok]) => {
                        ukuranSelect.append(`<option value="${ukuran}" data-stok="${stok}">${ukuran} (Stok: ${stok})</option>`);
                    });
                    
                    stokUkuranInfo.text('Pilih ukuran');
                    $('#stokInfo').text('Total Stok: ' + hadiah.stok);
                } else {
                    // Tidak ada stok ukuran - kolom ukuran disabled
                    ukuranSelect.append('<option value="">Tidak ada ukuran</option>');
                    ukuranSelect.prop('disabled', true);
                    stokUkuranInfo.text('N/A');
                    
                    if (stok !== undefined) {
                        $('#stokInfo').text('Stok: ' + stok);
                        $('#jumlah').attr('max', stok);
                    } else {
                        $('#stokInfo').text('Stok habis');
                    }
                }
            } else {
                ukuranSelect.prop('disabled', true);
            }
        });
        
        // Update stok info saat ukuran dipilih
        $('#ukuran_select').change(function() {
            var stokUkuran = $(this).find(':selected').data('stok');
            
            if (stokUkuran !== undefined) {
                $('#stokUkuranInfo').text('Stok tersedia: ' + stokUkuran);
                $('#jumlah').attr('max', stokUkuran);
            } else {
                $('#stokUkuranInfo').text('Stok: -');
                $('#jumlah').attr('max', '');
            }
        });
    });
</script>
@endpush
