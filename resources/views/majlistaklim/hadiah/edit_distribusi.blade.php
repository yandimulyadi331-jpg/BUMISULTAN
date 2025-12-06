@extends('layouts.app')
@section('titlepage', 'Edit Distribusi Hadiah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan /</span> Edit Distribusi Hadiah
@endsection

<!-- Navigation Tabs -->
@include('majlistaklim.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-edit me-2"></i>Edit Distribusi Hadiah</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('majlistaklim.distribusi.update', Crypt::encrypt($distribusi->id)) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Pilih Hadiah -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pilih Hadiah <span class="text-danger">*</span></label>
                            <select name="hadiah_id" id="hadiah_id" class="form-select @error('hadiah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Hadiah --</option>
                                @foreach($hadiahList as $hadiah)
                                    <option value="{{ $hadiah->id }}" 
                                            data-stok="{{ $hadiah->stok_tersedia }}"
                                            {{ old('hadiah_id', $distribusi->hadiah_id) == $hadiah->id ? 'selected' : '' }}>
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

                        <!-- Ukuran -->
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

                        <!-- Pilih Jamaah -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Jamaah Yayasan MASAR <span class="text-danger">*</span></label>
                            <select name="jamaah_id" id="jamaah_id" class="form-select select2-jamaah @error('jamaah_id') is-invalid @enderror" required>
                                <option value="">-- Cari Jamaah --</option>
                                @foreach($jamaahList as $jamaah)
                                    <option value="{{ $jamaah->kode_yayasan }}" {{ old('jamaah_id', $distribusi->jamaah_id) == $jamaah->kode_yayasan ? 'selected' : '' }}>
                                        {{ $jamaah->nama }}
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
                                   value="{{ old('jumlah', $distribusi->jumlah) }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="jumlahInfo">Maksimal: -</small>
                        </div>

                        <!-- Tanggal Distribusi -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Distribusi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_distribusi" class="form-control @error('tanggal_distribusi') is-invalid @enderror" 
                                   value="{{ old('tanggal_distribusi', $distribusi->tanggal_distribusi) }}" required>
                            @error('tanggal_distribusi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Metode Distribusi -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Metode Distribusi</label>
                            <select name="metode_distribusi" class="form-select">
                                <option value="langsung" {{ old('metode_distribusi', $distribusi->metode_distribusi) == 'langsung' ? 'selected' : '' }}>Langsung</option>
                                <option value="kurir" {{ old('metode_distribusi', $distribusi->metode_distribusi) == 'kurir' ? 'selected' : '' }}>Kurir</option>
                                <option value="diambil" {{ old('metode_distribusi', $distribusi->metode_distribusi) == 'diambil' ? 'selected' : '' }}>Diambil</option>
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan">{{ old('keterangan', $distribusi->keterangan) }}</textarea>
                        </div>

                        <!-- Penerima -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="penerima" class="form-control @error('penerima') is-invalid @enderror" 
                                   value="{{ old('penerima', $distribusi->penerima) }}" placeholder="Nama penerima" required>
                            @error('penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Petugas Distribusi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Petugas Distribusi</label>
                            <input type="text" name="petugas_distribusi" class="form-control" 
                                   value="{{ old('petugas_distribusi', $distribusi->petugas_distribusi) }}" placeholder="Nama petugas">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('majlistaklim.distribusi.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Distribusi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('mystyle')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-jamaah').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        const hadiahData = {!! json_encode($hadiahList->map(function($h) {
            return [
                'id' => $h->id,
                'nama_hadiah' => $h->nama_hadiah,
                'stok' => $h->stok_tersedia,
                'stok_ukuran' => $h->stok_ukuran
            ];
        })->values()) !!};

        const oldUkuran = "{{ old('ukuran', $distribusi->ukuran) }}";
        
        // Trigger change on load
        $('#hadiah_id').trigger('change');

        $('#hadiah_id').change(function() {
            var hadiahId = $(this).val();
            const ukuranSelect = $('#ukuran_select');
            const stokUkuranInfo = $('#stokUkuranInfo');
            const ukuranRequired = $('#ukuran_required');
            
            ukuranSelect.html('<option value="">-- Ukuran --</option>');
            ukuranSelect.removeAttr('required');
            ukuranRequired.hide();
            stokUkuranInfo.text('-');
            $('#stokInfo').text('Pilih hadiah terlebih dahulu');
            $('#jumlah').removeAttr('max');
            
            if (hadiahId) {
                const hadiah = hadiahData.find(h => h.id == hadiahId);
                
                if (hadiah && hadiah.stok_ukuran && Object.keys(hadiah.stok_ukuran).length > 0) {
                    ukuranSelect.attr('required', true);
                    ukuranRequired.show();
                    
                    Object.entries(hadiah.stok_ukuran).forEach(([ukuran, stok]) => {
                        const selected = ukuran == oldUkuran ? 'selected' : '';
                        ukuranSelect.append(`<option value="${ukuran}" data-stok="${stok}" ${selected}>${ukuran} (Stok: ${stok})</option>`);
                    });
                    
                    stokUkuranInfo.text('Pilih ukuran');
                    $('#stokInfo').text('Total Stok: ' + hadiah.stok);
                } else {
                    ukuranSelect.append('<option value="">Tidak ada ukuran</option>');
                    ukuranSelect.prop('disabled', true);
                    stokUkuranInfo.text('N/A');
                    $('#stokInfo').text('Stok: ' + hadiah.stok);
                }
            } else {
                ukuranSelect.prop('disabled', true);
            }
        });
        
        $('#ukuran_select').change(function() {
            var stokUkuran = $(this).find(':selected').data('stok');
            
            if (stokUkuran !== undefined) {
                $('#stokUkuranInfo').text('Stok tersedia: ' + stokUkuran);
                $('#jumlah').attr('max', stokUkuran);
            } else {
                $('#stokUkuranInfo').text('-');
                $('#jumlah').removeAttr('max');
            }
        });
    });
</script>
@endpush
