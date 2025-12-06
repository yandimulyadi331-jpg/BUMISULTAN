<form action="{{ route('yayasan_masar.store') }}" id="formcreateYayasanMasar" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="alert alert-info" role="alert">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Info:</strong> 
        <ul class="mb-0 mt-2">
            <li>Kode Yayasan Masar akan digenerate otomatis dengan format YYMM + nomor urut</li>
            <li>No. Identitas akan digenerate otomatis dengan format YYMMDD + nomor urut (4 digit)</li>
        </ul>
    </div>
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Error!</strong> Terdapat kesalahan pada form:
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- Hidden field for auto-generated no_identitas --}}
    <input type="hidden" name="no_identitas" id="no_identitas" />
    <x-input-with-icon-label icon="ti ti-user" label="Nama Yayasan Masar" name="nama" required="true" />
    <div class="row">
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-map-pin" label="Tempat Lahir" name="tempat_lahir" required="true" />
        </div>
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal Lahir" datepicker="flatpickr-date" name="tanggal_lahir" required="true" />
        </div>
    </div>
    
    <div class="form-group mb-3">
        <label for="alamat" style="font-weight: 600" class="form-label">
            Alamat <span class="text-danger">*</span>
        </label>
        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Alamat" required>{{ old('alamat') }}</textarea>
        @error('alamat')
            <small class="text-danger d-block mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
        @enderror
    </div>
    
    <div class="form-group mb-3">
        <label for="jenis_kelamin" style="font-weight: 600" class="form-label">
            Jenis Kelamin <span class="text-danger">*</span>
        </label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <small class="text-danger d-block mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
        @enderror
    </div>
    
    <x-input-with-icon-label icon="ti ti-phone" label="No. HP" name="no_hp" required="true" />
    <x-input-with-icon-label icon="ti ti-mail" label="Email" name="email" type="email" />
    
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="kode_status_kawin" style="font-weight: 600" class="form-label">
                    Status Perkawinan <span class="text-danger">*</span>
                </label>
                <select name="kode_status_kawin" id="kode_status_kawin" class="form-select @error('kode_status_kawin') is-invalid @enderror" required>
                    <option value="">-- Pilih Status Perkawinan --</option>
                    @foreach($status_kawin as $item)
                        <option value="{{ $item->kode_status_kawin }}" {{ old('kode_status_kawin') == $item->kode_status_kawin ? 'selected' : '' }}>
                            {{ $item->status_kawin }}
                        </option>
                    @endforeach
                </select>
                @error('kode_status_kawin')
                    <small class="text-danger d-block mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="pendidikan_terakhir" style="font-weight: 600" class="form-label">
                    Pendidikan Terakhir <span class="text-danger">*</span>
                </label>
                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') is-invalid @enderror" required>
                    <option value="">-- Pilih Pendidikan Terakhir --</option>
                    <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="SMK" {{ old('pendidikan_terakhir') == 'SMK' ? 'selected' : '' }}>SMK</option>
                    <option value="D1" {{ old('pendidikan_terakhir') == 'D1' ? 'selected' : '' }}>D1</option>
                    <option value="D2" {{ old('pendidikan_terakhir') == 'D2' ? 'selected' : '' }}>D2</option>
                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="D4" {{ old('pendidikan_terakhir') == 'D4' ? 'selected' : '' }}>D4</option>
                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
                @error('pendidikan_terakhir')
                    <small class="text-danger d-block mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    
    <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Masuk" name="tanggal_masuk" required="true" />
    
    <div class="form-group mb-3">
        <label for="status_umroh" style="font-weight: 600" class="form-label">
            Status Umroh <span class="text-danger">*</span>
        </label>
        <select name="status_umroh" id="status_umroh" class="form-select @error('status_umroh') is-invalid @enderror" required>
            <option value="">-- Pilih Status Umroh --</option>
            <option value="1" {{ old('status_umroh') == '1' ? 'selected' : '' }}>Umroh</option>
            <option value="0" {{ old('status_umroh') == '0' ? 'selected' : '' }}>Tidak</option>
        </select>
        @error('status_umroh')
            <small class="text-danger d-block mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
        @enderror
    </div>
    
    <x-input-file name="foto" label="Foto" />
    
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/js/pages/karyawan.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

<script>
    $(function() {

        $(".flatpickr-date").flatpickr();
        // mask opsional untuk nik_show jika diperlukan; nonaktifkan jika format bebas
        // $('#nik_show').mask('00.00.000');
    });
</script>
