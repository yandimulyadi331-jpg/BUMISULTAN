<form action="{{ route('peminjaman-peralatan.update', $peminjamanPeralatan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Nomor Peminjaman</label>
            <input type="text" class="form-control" value="{{ $peminjamanPeralatan->nomor_peminjaman }}" readonly>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Peralatan</label>
            <input type="text" class="form-control" value="{{ $peminjamanPeralatan->peralatan->nama_peralatan }}" readonly>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
            <input type="text" name="nama_peminjam" class="form-control" value="{{ $peminjamanPeralatan->nama_peminjam }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jumlah Dipinjam <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_dipinjam" class="form-control" value="{{ $peminjamanPeralatan->jumlah_dipinjam }}" min="1" required>
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Keperluan <span class="text-danger">*</span></label>
            <input type="text" name="keperluan" class="form-control" value="{{ $peminjamanPeralatan->keperluan }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ $peminjamanPeralatan->tanggal_pinjam->format('Y-m-d') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ $peminjamanPeralatan->tanggal_kembali_rencana->format('Y-m-d') }}" required>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Kondisi Saat Dipinjam</label>
            <textarea name="kondisi_saat_dipinjam" class="form-control" rows="2">{{ $peminjamanPeralatan->kondisi_saat_dipinjam }}</textarea>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="2">{{ $peminjamanPeralatan->catatan }}</textarea>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-warning">
            <i class="ti ti-device-floppy me-1"></i> Update
        </button>
    </div>
</form>
