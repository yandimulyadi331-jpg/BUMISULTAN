@if($inventaris)
<div class="alert alert-info mb-3">
    <div class="d-flex align-items-center">
        <i class="ti ti-info-circle fs-4 me-2"></i>
        <div>
            <strong>{{ $inventaris->nama_barang }}</strong><br>
            <small>Kode: {{ $inventaris->kode_inventaris }} | Status: <span class="badge bg-{{ $inventaris->status == 'tersedia' ? 'success' : 'warning' }}">{{ ucfirst($inventaris->status) }}</span></small>
        </div>
    </div>
</div>
@endif

@if($histories->count() > 0)
<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="12%">Jenis Aktivitas</th>
                <th width="20%">Barang</th>
                <th width="28%">Deskripsi</th>
                <th width="10%">User</th>
                <th width="15%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $index => $history)
            <tr>
                <td>{{ $histories->firstItem() + $index }}</td>
                <td>
                    <small>
                        {{ $history->created_at->format('d/m/Y') }}<br>
                        <span class="text-muted">{{ $history->created_at->format('H:i') }}</span>
                    </small>
                </td>
                <td>
                    <span class="badge bg-{{ $history->getJenisAktivitasColor() }}" style="font-size:10px;">
                        {{ $history->getJenisAktivitasLabel() }}
                    </span>
                </td>
                <td>
                    <strong>{{ $history->inventaris->nama_barang }}</strong><br>
                    <small class="text-muted">{{ $history->inventaris->kode_inventaris }}</small>
                </td>
                <td>
                    <small>{{ \Illuminate\Support\Str::limit($history->deskripsi, 60) }}</small>
                    @if($history->status_sebelum && $history->status_sesudah)
                    <br>
                    <small class="text-muted">
                        <i class="ti ti-arrow-right"></i> {{ $history->status_sebelum }} → {{ $history->status_sesudah }}
                    </small>
                    @endif
                </td>
                <td>
                    <small>{{ $history->user->name ?? 'System' }}</small>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="#" class="btnHistoryDetail" data-id="{{ $history->id }}" title="Detail">
                            <i class="ti ti-eye text-info"></i>
                        </a>
                        <a href="#" class="btnEditHistory" data-id="{{ $history->id }}" title="Edit">
                            <i class="ti ti-edit text-success"></i>
                        </a>
                        <form action="{{ route('history-inventaris.destroy', $history->id) }}" method="POST" class="deleteHistoryForm d-inline" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 m-0" title="Hapus">
                                <i class="ti ti-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <small class="text-muted">
            Menampilkan {{ $histories->firstItem() }} - {{ $histories->lastItem() }} dari {{ $histories->total() }} history
        </small>
    </div>
    <div>
        {{ $histories->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
$(document).ready(function() {
    // Detail History
    $('.btnHistoryDetail').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Close current modal first
        $('#modal').modal('hide');
        
        // Open new modal after a short delay
        setTimeout(function() {
            $('#modalDetail').modal('show');
            $('.modal-title-detail').text('Detail History');
            $('#loadmodalDetail').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>');
            $('#loadmodalDetail').load('/inventaris/history/' + id);
        }, 300);
    });

    // Edit History
    $('.btnEditHistory').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        $('#modal').modal('hide');
        
        setTimeout(function() {
            $('#modalDetail').modal('show');
            $('.modal-title-detail').text('Edit History');
            $('#loadmodalDetail').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>');
            $('#loadmodalDetail').load('/history-inventaris/' + id + '/edit');
        }, 300);
    });

    // Delete History
    $('.deleteHistoryForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        
        Swal.fire({
            title: 'Hapus History?',
            text: 'Data history ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'History berhasil dihapus',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus history'
                        });
                    }
                });
            }
        });
    });
});
</script>
@else
<div class="alert alert-warning">
    <i class="ti ti-alert-circle me-2"></i>
    Belum ada history untuk barang ini.
</div>
@endif

<div class="d-flex justify-content-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
