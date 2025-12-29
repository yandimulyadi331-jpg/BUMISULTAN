<form action="{{ route('potongan_pinjaman_master.update', Crypt::encrypt($potongan->id)) }}" method="POST" id="formPotongan">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Kode:</strong> {{ $potongan->kode_potongan }}<br>
                <strong>Karyawan:</strong> {{ $potongan->karyawan->nama_karyawan ?? 'N/A' }} ({{ $potongan->karyawan->nik_show ?? $potongan->nik }})
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Total Pinjaman</label>
                <input type="text" class="form-control text-end" 
                    value="{{ formatAngka($potongan->jumlah_pinjaman) }}" disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Sisa Pinjaman</label>
                <input type="text" class="form-control text-end" 
                    value="{{ formatAngka($potongan->sisa_pinjaman) }}" disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon label="Cicilan per Bulan" name="cicilan_per_bulan" icon="fa fa-money-bill" 
                value="{{ $potongan->cicilan_per_bulan }}" required="true" align="right" money="true" />
            <small class="text-muted">Update cicilan per bulan akan menghitung ulang periode selesai</small>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Progress</label>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                        style="width: {{ $potongan->progress_percentage }}%"
                        aria-valuenow="{{ $potongan->progress_percentage }}" 
                        aria-valuemin="0" aria-valuemax="100">
                        {{ $potongan->progress_text }} ({{ number_format($potongan->progress_percentage, 1) }}%)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Periode Mulai</label>
                <input type="text" class="form-control" 
                    value="{{ config('global.nama_bulan')[$potongan->bulan_mulai] }} {{ $potongan->tahun_mulai }}" 
                    disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Periode Selesai (Estimasi)</label>
                <input type="text" class="form-control" 
                    value="{{ config('global.nama_bulan')[$potongan->bulan_selesai] }} {{ $potongan->tahun_selesai }}" 
                    disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif" {{ $potongan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ $potongan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditunda" {{ $potongan->status == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                    <option value="dibatalkan" {{ $potongan->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="text" class="form-control" 
                    value="{{ $potongan->tanggal_selesai ? $potongan->tanggal_selesai->format('d-m-Y') : '-' }}" 
                    disabled>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $potongan->keterangan }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-2"></i> Update
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Batal
            </button>
        </div>
    </div>
</form>
