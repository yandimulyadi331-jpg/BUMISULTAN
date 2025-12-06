@extends('layouts.app')
@section('titlepage', 'Tambah Hadiah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Hadiah /</span> Tambah Hadiah
@endsection

<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ route('majlistaklim.hadiah.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-gift me-2"></i>Form Tambah Hadiah</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informasi Hadiah -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="ti ti-info-circle me-2"></i>Informasi Hadiah</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama_hadiah" class="form-label">Nama Hadiah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-gift"></i></span>
                                <input type="text" class="form-control @error('nama_hadiah') is-invalid @enderror" 
                                    id="nama_hadiah" name="nama_hadiah" value="{{ old('nama_hadiah') }}" required>
                            </div>
                            @error('nama_hadiah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis_hadiah" class="form-label">Jenis Hadiah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-category"></i></span>
                                <select class="form-select @error('jenis_hadiah') is-invalid @enderror" id="jenis_hadiah" name="jenis_hadiah" required>
                                    <option value="">Pilih Jenis Hadiah</option>
                                    <option value="sarung" {{ old('jenis_hadiah') == 'sarung' ? 'selected' : '' }}>Sarung</option>
                                    <option value="peci" {{ old('jenis_hadiah') == 'peci' ? 'selected' : '' }}>Peci</option>
                                    <option value="gamis" {{ old('jenis_hadiah') == 'gamis' ? 'selected' : '' }}>Gamis</option>
                                    <option value="mukena" {{ old('jenis_hadiah') == 'mukena' ? 'selected' : '' }}>Mukena</option>
                                    <option value="tasbih" {{ old('jenis_hadiah') == 'tasbih' ? 'selected' : '' }}>Tasbih</option>
                                    <option value="sajadah" {{ old('jenis_hadiah') == 'sajadah' ? 'selected' : '' }}>Sajadah</option>
                                    <option value="al_quran" {{ old('jenis_hadiah') == 'al_quran' ? 'selected' : '' }}>Al-Qur'an</option>
                                    <option value="buku" {{ old('jenis_hadiah') == 'buku' ? 'selected' : '' }}>Buku</option>
                                    <option value="lainnya" {{ old('jenis_hadiah') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            @error('jenis_hadiah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kode hadiah akan di-generate otomatis</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="ukuran" class="form-label">Ukuran</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-ruler"></i></span>
                                <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                                    id="ukuran" name="ukuran" value="{{ old('ukuran') }}" placeholder="S, M, L, XL, atau nomor">
                            </div>
                            @error('ukuran')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="warna" class="form-label">Warna</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-palette"></i></span>
                                <input type="text" class="form-control @error('warna') is-invalid @enderror" 
                                    id="warna" name="warna" value="{{ old('warna') }}" placeholder="Merah, Biru, dll">
                            </div>
                            @error('warna')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nilai_hadiah" class="form-label">Nilai Hadiah (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('nilai_hadiah') is-invalid @enderror" 
                                    id="nilai_hadiah" name="nilai_hadiah" value="{{ old('nilai_hadiah', 0) }}" min="0">
                            </div>
                            @error('nilai_hadiah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok & Pengadaan -->
                        <div class="col-12 mt-3">
                            <h6 class="text-primary mb-3"><i class="ti ti-box me-2"></i>Stok & Pengadaan</h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="stok_awal" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-package"></i></span>
                                <input type="number" class="form-control @error('stok_awal') is-invalid @enderror" 
                                    id="stok_awal" name="stok_awal" value="{{ old('stok_awal', 0) }}" min="0" required>
                            </div>
                            @error('stok_awal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Total semua ukuran</small>
                        </div>

                        <!-- Stok Per Ukuran -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Stok Per Ukuran (Opsional)</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="enable_ukuran">
                                        <label class="form-check-label" for="enable_ukuran">
                                            Aktifkan tracking stok per ukuran
                                        </label>
                                    </div>
                                    
                                    <div id="ukuran_section" style="display: none;">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="form-label small"><strong>Tipe Ukuran:</strong></label>
                                                <select class="form-select form-select-sm" id="ukuran_type">
                                                    <option value="huruf">Huruf (S, M, L, XL, XXL)</option>
                                                    <option value="angka">Angka (38-44)</option>
                                                    <option value="custom">Custom</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Template Huruf -->
                                        <div id="template_huruf" class="ukuran-template">
                                            <div class="row g-2">
                                                <div class="col-md-2">
                                                    <label class="form-label small">S</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[S]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">M</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[M]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">L</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[L]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">XL</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[XL]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">XXL</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[XXL]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">XXXL</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[XXXL]" min="0" value="0">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Template Angka -->
                                        <div id="template_angka" class="ukuran-template" style="display:none;">
                                            <div class="mb-2"><small class="text-muted">Ukuran 1-15 (untuk anak-anak):</small></div>
                                            <div class="row g-2 mb-3">
                                                @for($i = 1; $i <= 15; $i++)
                                                <div class="col-md-1">
                                                    <label class="form-label small">{{ $i }}</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[{{ $i }}]" min="0" value="0">
                                                </div>
                                                @endfor
                                            </div>
                                            
                                            <div class="mb-2"><small class="text-muted">Ukuran 38-44 (untuk dewasa):</small></div>
                                            <div class="row g-2">
                                                <div class="col-md-2">
                                                    <label class="form-label small">38</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[38]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">39</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[39]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">40</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[40]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">41</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[41]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">42</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[42]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">43</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[43]" min="0" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">44</label>
                                                    <input type="number" class="form-control form-control-sm" name="stok_ukuran[44]" min="0" value="0">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Template Custom -->
                                        <div id="template_custom" class="ukuran-template" style="display:none;">
                                            <div id="custom_ukuran_container"></div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add_custom_ukuran">
                                                <i class="ti ti-plus me-1"></i> Tambah Ukuran
                                            </button>
                                        </div>
                                        
                                        <div class="alert alert-info mt-3 mb-0">
                                            <small><i class="ti ti-info-circle me-1"></i> Total stok ukuran harus sama dengan <strong>Stok Awal</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tanggal_pengadaan" class="form-label">Tanggal Pengadaan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="date" class="form-control @error('tanggal_pengadaan') is-invalid @enderror" 
                                    id="tanggal_pengadaan" name="tanggal_pengadaan" value="{{ old('tanggal_pengadaan', date('Y-m-d')) }}">
                            </div>
                            @error('tanggal_pengadaan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-building-store"></i></span>
                                <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                    id="supplier" name="supplier" value="{{ old('supplier') }}">
                            </div>
                            @error('supplier')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div class="col-12 mt-3">
                            <h6 class="text-primary mb-3"><i class="ti ti-camera me-2"></i>Foto Hadiah</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="foto" class="form-label">Upload Foto</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                            @error('foto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preview Foto</label>
                            <div>
                                <img id="preview" src="{{ asset('assets/img/icons/unicons/cube.png') }}" 
                                    alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-12 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('majlistaklim.hadiah.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Hadiah
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Preview foto
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Toggle Ukuran Section
        $('#enable_ukuran').change(function() {
            if ($(this).is(':checked')) {
                $('#ukuran_section').slideDown();
            } else {
                $('#ukuran_section').slideUp();
                // Reset all inputs
                $('.ukuran-template input').val(0);
            }
        });

        // Switch Ukuran Type
        $('#ukuran_type').change(function() {
            $('.ukuran-template').hide();
            $('.ukuran-template input').prop('disabled', true);
            
            const type = $(this).val();
            $('#template_' + type).show();
            $('#template_' + type + ' input').prop('disabled', false);
        });

        // Add Custom Ukuran
        $('#add_custom_ukuran').click(function() {
            const index = $('#custom_ukuran_container .custom-ukuran-row').length;
            const html = `
                <div class="row g-2 mb-2 custom-ukuran-row">
                    <div class="col-md-4">
                        <input type="text" class="form-control form-control-sm" 
                            name="stok_ukuran_custom_name[]" placeholder="Nama ukuran (cth: XS)">
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control form-control-sm" 
                            name="stok_ukuran_custom_value[]" placeholder="Jumlah" min="0" value="0">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-danger remove-custom-ukuran">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#custom_ukuran_container').append(html);
        });

        // Remove Custom Ukuran
        $(document).on('click', '.remove-custom-ukuran', function() {
            $(this).closest('.custom-ukuran-row').remove();
        });

        // Auto-suggest ukuran type berdasarkan jenis hadiah
        $('#jenis_hadiah').change(function() {
            const jenis = $(this).val();
            let suggestedType = 'huruf';
            
            if (['peci', 'sepatu'].includes(jenis)) {
                suggestedType = 'angka';
            } else if (['sarung', 'gamis', 'mukena'].includes(jenis)) {
                suggestedType = 'huruf';
            }
            
            $('#ukuran_type').val(suggestedType).trigger('change');
        });

        // Validate total stok matches stok_awal
        $('form').submit(function(e) {
            if ($('#enable_ukuran').is(':checked')) {
                let totalUkuran = 0;
                const activeTemplate = $('.ukuran-template:visible');
                
                activeTemplate.find('input[type="number"]:not(:disabled)').each(function() {
                    totalUkuran += parseInt($(this).val()) || 0;
                });

                const stokAwal = parseInt($('#stok_awal').val()) || 0;

                if (totalUkuran !== stokAwal) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: `Total stok per ukuran (${totalUkuran}) harus sama dengan Stok Awal (${stokAwal})`,
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }
            }
        });

        // Initialize: disable angka & custom inputs by default
        $('#template_angka input, #template_custom input').prop('disabled', true);
    });
</script>
@endpush
