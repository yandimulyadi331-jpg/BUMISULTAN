<form action="{{ route('peminjaman-inventaris.store') }}" method="POST" enctype="multipart/form-data" id="formPeminjamanCreate">
    @csrf
    
    @php
        $selectedInventaris = null;
        if(request('inventaris_id')) {
            $selectedInventaris = $inventaris->firstWhere('id', request('inventaris_id'));
        }
    @endphp
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Inventaris <span class="text-danger">*</span></label>
                <input type="hidden" name="inventaris_id" value="{{ request('inventaris_id') }}" id="hiddenInventarisId">
                <input type="text" class="form-control" readonly 
                    value="{{ $selectedInventaris ? $selectedInventaris->nama_barang . ' (' . $selectedInventaris->kode_inventaris . ')' : 'Pilih inventaris dari tabel' }}"
                    id="displayInventaris">
                <small class="text-muted" id="infoTersedia">
                    @if($selectedInventaris)
                        Tersedia: {{ $selectedInventaris->jumlahTersedia() }} {{ $selectedInventaris->satuan }}
                    @endif
                </small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                <input type="text" name="nama_peminjam" class="form-control" required placeholder="Masukkan nama peminjam" value="{{ old('nama_peminjam') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Jumlah Pinjam <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_pinjam" class="form-control" value="{{ old('jumlah_pinjam', 1) }}" 
                    required min="1" id="inputJumlah" 
                    @if($selectedInventaris) max="{{ $selectedInventaris->jumlahTersedia() }}" @endif>
                @if($selectedInventaris)
                    <small class="text-muted">
                        Stok Total: <strong>{{ $selectedInventaris->jumlah }}</strong> | 
                        Tersedia: <strong class="text-success">{{ $selectedInventaris->jumlahTersedia() }}</strong> {{ $selectedInventaris->satuan }}
                    </small>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label">Rencana Kembali <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
        <textarea name="keperluan" class="form-control" rows="2" required placeholder="Jelaskan keperluan peminjaman">{{ old('keperluan') }}</textarea>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Event (Opsional)</label>
        <select name="inventaris_event_id" class="form-select">
            <option value="">-- Tidak Ada Event --</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->nama_event }} ({{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d/m/Y') }})</option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Foto Barang (Opsional)</label>
        <input type="file" name="foto_barang" class="form-control" accept="image/*">
        <small class="text-muted">Foto kondisi barang saat dipinjam</small>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="catatan_peminjaman" class="form-control" rows="2" placeholder="Catatan tambahan">{{ old('catatan_peminjaman') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanda Tangan Peminjam <span class="text-danger">*</span></label>
                <div style="border: 2px solid #dee2e6; border-radius: 8px; background: white;">
                    <canvas id="signaturePadPeminjam" style="width:100%; height:150px; cursor:crosshair; display:block;"></canvas>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" id="clearSignaturePeminjam">
                    <i class="ti ti-eraser"></i> Hapus TTD
                </button>
                <input type="hidden" name="ttd_peminjam" id="ttdPeminjam">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label">Tanda Tangan Petugas</label>
                <div style="border: 2px solid #dee2e6; border-radius: 8px; background: white;">
                    <canvas id="signaturePadPetugas" style="width:100%; height:150px; cursor:crosshair; display:block;"></canvas>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" id="clearSignaturePetugas">
                    <i class="ti ti-eraser"></i> Hapus TTD
                </button>
                <input type="hidden" name="ttd_petugas" id="ttdPetugas">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i>Ajukan Peminjaman
        </button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Peminjaman Modal: Initializing...');
    
    // Wait for modal to be fully shown
    setTimeout(function() {
        const canvasPeminjam = document.getElementById('signaturePadPeminjam');
        const canvasPetugas = document.getElementById('signaturePadPetugas');
        
        console.log('Canvas Peminjam:', canvasPeminjam);
        console.log('Canvas Petugas:', canvasPetugas);
        
        if (canvasPeminjam && canvasPetugas) {
            function resizeCanvas(canvas) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
            }
            
            // Resize canvas first
            resizeCanvas(canvasPeminjam);
            resizeCanvas(canvasPetugas);
            
            // Then initialize SignaturePad - SIMPAN KE WINDOW
            window.signaturePadPeminjamanPeminjam = new SignaturePad(canvasPeminjam, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 0.5,
                maxWidth: 2.5
            });
            
            window.signaturePadPeminjamanPetugas = new SignaturePad(canvasPetugas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 0.5,
                maxWidth: 2.5
            });
            
            console.log('Signature pads initialized successfully');

            // Clear buttons
            $('#clearSignaturePeminjam').click(function() {
                console.log('Clearing signature peminjam');
                window.signaturePadPeminjamanPeminjam.clear();
                $('#ttdPeminjam').val('');
            });

            $('#clearSignaturePetugas').click(function() {
                console.log('Clearing signature petugas');
                window.signaturePadPeminjamanPetugas.clear();
                $('#ttdPetugas').val('');
            });
            
            // Re-resize on window resize
            $(window).on('resize', function() {
                resizeCanvas(canvasPeminjam);
                resizeCanvas(canvasPetugas);
            });
        } else {
            console.error('Canvas elements not found!');
        }
    }, 300);
});
</script>
