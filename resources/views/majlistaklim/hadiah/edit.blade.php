@extends('layouts.app')
@section('titlepage', 'Edit Hadiah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Hadiah /</span> Edit Hadiah
@endsection

<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <form action="{{ route('majlistaklim.hadiah.update', $hadiah->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-gift me-2"></i>Edit Hadiah: {{ $hadiah->nama_hadiah }}</h5>
                    <span class="badge bg-label-primary">{{ $hadiah->kode_hadiah }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statistik Singkat -->
                        <div class="col-12 mb-4">
                            <div class="alert alert-info">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="mb-1"><i class="ti ti-box fs-4"></i></div>
                                        <div class="fw-bold">{{ $hadiah->stok_tersedia }}</div>
                                        <small>Stok Tersedia</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-1"><i class="ti ti-users fs-4"></i></div>
                                        <div class="fw-bold">{{ $hadiah->distribusiHadiah ? $hadiah->distribusiHadiah->count() : 0 }}</div>
                                        <small>Terdistribusi</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-1"><i class="ti ti-calendar fs-4"></i></div>
                                        <div class="fw-bold">{{ $hadiah->tanggal_pengadaan ? \Carbon\Carbon::parse($hadiah->tanggal_pengadaan)->format('d M Y') : '-' }}</div>
                                        <small>Tgl. Pengadaan</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-1"><i class="ti ti-currency-dollar fs-4"></i></div>
                                        <div class="fw-bold">Rp {{ number_format($hadiah->nilai_hadiah, 0, ',', '.') }}</div>
                                        <small>Nilai Hadiah</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Hadiah -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="ti ti-info-circle me-2"></i>Informasi Hadiah</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama_hadiah" class="form-label">Nama Hadiah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-gift"></i></span>
                                <input type="text" class="form-control @error('nama_hadiah') is-invalid @enderror" 
                                    id="nama_hadiah" name="nama_hadiah" value="{{ old('nama_hadiah', $hadiah->nama_hadiah) }}" required>
                            </div>
                            @error('nama_hadiah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis_hadiah" class="form-label">Jenis Hadiah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-category"></i></span>
                                <select class="form-select @error('jenis_hadiah') is-invalid @enderror" id="jenis_hadiah" name="jenis_hadiah" required disabled>
                                    <option value="">Pilih Jenis Hadiah</option>
                                    <option value="sarung" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'sarung' ? 'selected' : '' }}>Sarung</option>
                                    <option value="peci" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'peci' ? 'selected' : '' }}>Peci</option>
                                    <option value="gamis" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'gamis' ? 'selected' : '' }}>Gamis</option>
                                    <option value="mukena" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'mukena' ? 'selected' : '' }}>Mukena</option>
                                    <option value="tasbih" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'tasbih' ? 'selected' : '' }}>Tasbih</option>
                                    <option value="sajadah" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'sajadah' ? 'selected' : '' }}>Sajadah</option>
                                    <option value="al_quran" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'al_quran' ? 'selected' : '' }}>Al-Qur'an</option>
                                    <option value="buku" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'buku' ? 'selected' : '' }}>Buku</option>
                                    <option value="lainnya" {{ old('jenis_hadiah', $hadiah->jenis_hadiah) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <input type="hidden" name="jenis_hadiah" value="{{ $hadiah->jenis_hadiah }}">
                            </div>
                            @error('jenis_hadiah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jenis hadiah tidak dapat diubah (Kode: {{ $hadiah->kode_hadiah }})</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="ukuran" class="form-label">Ukuran</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-ruler"></i></span>
                                <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                                    id="ukuran" name="ukuran" value="{{ old('ukuran', $hadiah->ukuran) }}" placeholder="S, M, L, XL, atau nomor">
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
                                    id="warna" name="warna" value="{{ old('warna', $hadiah->warna) }}" placeholder="Merah, Biru, dll">
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
                                    id="nilai_hadiah" name="nilai_hadiah" value="{{ old('nilai_hadiah', $hadiah->nilai_hadiah) }}" min="0">
                            </div>
                            @error('nilai_hadiah')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $hadiah->deskripsi) }}</textarea>
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
                                    id="stok_awal" name="stok_awal" value="{{ old('stok_awal', $hadiah->stok_awal) }}" min="{{ $hadiah->distribusiHadiah ? $hadiah->distribusiHadiah->count() : 0 }}" required>
                            </div>
                            @error('stok_awal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Min: {{ $hadiah->distribusiHadiah ? $hadiah->distribusiHadiah->count() : 0 }} (sudah terdistribusi)</small>
                        </div>

                        <!-- Stok Per Ukuran -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Stok Per Ukuran (Opsional)</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="enable_ukuran" {{ old('enable_ukuran', !empty($hadiah->stok_ukuran)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enable_ukuran">
                                            Aktifkan tracking stok per ukuran
                                        </label>
                                    </div>
                                    
                                    <div id="ukuran_section" style="display: {{ old('enable_ukuran', !empty($hadiah->stok_ukuran)) ? 'block' : 'none' }};">
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
                                        
                                        @php
                                            $existingStok = old('stok_ukuran', $hadiah->stok_ukuran ?? []);
                                            $ukuranHuruf = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                                            $ukuranAngka = array_merge(range(1, 15), [38, 39, 40, 41, 42, 43, 44]);
                                        @endphp
                                        
                                        <!-- Template Huruf -->
                                        <div id="template_huruf" class="ukuran-template">
                                            <div class="row g-2">
                                                @foreach($ukuranHuruf as $size)
                                                <div class="col-md-2">
                                                    <label class="form-label small">{{ $size }}</label>
                                                    <input type="number" class="form-control form-control-sm" 
                                                        name="stok_ukuran[{{ $size }}]" min="0" 
                                                        value="{{ old('stok_ukuran.'.$size, $existingStok[$size] ?? 0) }}">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Template Angka -->
                                        <div id="template_angka" class="ukuran-template" style="display:none;">
                                            <div class="mb-2"><small class="text-muted">Ukuran 1-15 (untuk anak-anak):</small></div>
                                            <div class="row g-2 mb-3">
                                                @for($i = 1; $i <= 15; $i++)
                                                <div class="col-md-1">
                                                    <label class="form-label small">{{ $i }}</label>
                                                    <input type="number" class="form-control form-control-sm" 
                                                        name="stok_ukuran[{{ $i }}]" min="0" 
                                                        value="{{ old('stok_ukuran.'.$i, $existingStok[$i] ?? 0) }}">
                                                </div>
                                                @endfor
                                            </div>
                                            
                                            <div class="mb-2"><small class="text-muted">Ukuran 38-44 (untuk dewasa):</small></div>
                                            <div class="row g-2">
                                                @foreach([38, 39, 40, 41, 42, 43, 44] as $size)
                                                <div class="col-md-2">
                                                    <label class="form-label small">{{ $size }}</label>
                                                    <input type="number" class="form-control form-control-sm" 
                                                        name="stok_ukuran[{{ $size }}]" min="0" 
                                                        value="{{ old('stok_ukuran.'.$size, $existingStok[$size] ?? 0) }}">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Template Custom -->
                                        <div id="template_custom" class="ukuran-template" style="display:none;">
                                            <div id="custom_ukuran_container">
                                                @if(!empty($existingStok))
                                                    @foreach($existingStok as $ukuran => $jumlah)
                                                        @if(!in_array($ukuran, $ukuranHuruf) && !in_array($ukuran, $ukuranAngka))
                                                        <div class="row g-2 mb-2 custom-ukuran-row">
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control form-control-sm" 
                                                                    name="stok_ukuran_custom_name[]" placeholder="Nama ukuran" value="{{ $ukuran }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" class="form-control form-control-sm" 
                                                                    name="stok_ukuran_custom_value[]" placeholder="Jumlah" min="0" value="{{ $jumlah }}">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-sm btn-danger remove-custom-ukuran">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
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
                                    id="tanggal_pengadaan" name="tanggal_pengadaan" value="{{ old('tanggal_pengadaan', $hadiah->tanggal_pengadaan) }}">
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
                                    id="supplier" name="supplier" value="{{ old('supplier', $hadiah->supplier) }}">
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
                            <label for="foto" class="form-label">Upload Foto Baru (Opsional)</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                                id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                            @error('foto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG (Max: 2MB)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preview Foto</label>
                            <div>
                                <img id="preview" src="{{ $hadiah->foto ? asset('storage/' . $hadiah->foto) : asset('assets/img/icons/unicons/cube.png') }}" 
                                    alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-status-change"></i></span>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="tersedia" {{ old('status', $hadiah->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="habis" {{ old('status', $hadiah->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                                    <option value="tidak_aktif" {{ old('status', $hadiah->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $hadiah->keterangan) }}</textarea>
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
                            <i class="ti ti-device-floppy me-1"></i> Update Hadiah
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

        // Warning jika stok awal dikurangi
        const stokAwalAwal = {{ $hadiah->stok_awal }};
        $('#stok_awal').change(function() {
            const stokBaru = parseInt($(this).val());
            if (stokBaru < stokAwalAwal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Anda mengurangi stok awal. Stok tersedia akan disesuaikan otomatis.',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Toggle Ukuran Section
        $('#enable_ukuran').change(function() {
            if ($(this).is(':checked')) {
                $('#ukuran_section').slideDown();
            } else {
                $('#ukuran_section').slideUp();
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

        // Auto-detect ukuran type based on existing data
        @if(!empty($hadiah->stok_ukuran))
            const existingKeys = @json(array_keys($hadiah->stok_ukuran));
            const hurufSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
            const angkaSizes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '38', '39', '40', '41', '42', '43', '44'];
            
            const hasHuruf = existingKeys.some(key => hurufSizes.includes(key));
            const hasAngka = existingKeys.some(key => angkaSizes.includes(key));
            
            if (hasHuruf) {
                $('#ukuran_type').val('huruf').trigger('change');
            } else if (hasAngka) {
                $('#ukuran_type').val('angka').trigger('change');
            } else {
                $('#ukuran_type').val('custom').trigger('change');
            }
        @else
            // Initialize: disable angka & custom inputs by default
            $('#template_angka input, #template_custom input').prop('disabled', true);
        @endif

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
    });
</script>
@endpush
