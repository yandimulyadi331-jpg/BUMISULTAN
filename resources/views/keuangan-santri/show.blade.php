@extends('layouts.app')
@section('titlepage', 'Detail Dompet Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('keuangan-santri.index') }}">Dompet Santri</a> / Detail {{ $santri->nama_lengkap }}</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <!-- Info Santri & Saldo -->
        <div class="card mb-3">
            <div class="card-header bg-gradient-info">
                <h5 class="text-white mb-0">
                    <i class="ti ti-user me-2"></i>Informasi Santri
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>NIS</strong></td>
                                <td>: {{ $santri->nis }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: {{ $santri->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    @if($santri->status_santri == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($santri->status_santri) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        @if($santri->keuanganSaldo)
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">Saldo Saat Ini</p>
                                <h2 class="text-primary mb-2">
                                    Rp {{ number_format($santri->keuanganSaldo->saldo_akhir, 0, ',', '.') }}
                                </h2>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Total Setoran</small><br>
                                        <strong class="text-success">
                                            Rp {{ number_format($santri->keuanganSaldo->total_pemasukan, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Total Penarikan</small><br>
                                        <strong class="text-danger">
                                            Rp {{ number_format($santri->keuanganSaldo->total_pengeluaran, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="ti ti-info-circle me-2"></i>Belum ada transaksi untuk santri ini
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('keuangan-santri.create', ['santri_id' => $santri->id]) }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i>Tambah Transaksi Baru
                    </a>
                    <a href="{{ route('keuangan-santri.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header bg-gradient-info d-flex justify-content-between align-items-center">
                <h6 class="text-white mb-0">
                    <i class="ti ti-filter me-2"></i>Filter Pencarian
                </h6>
                <button type="button" class="btn btn-sm btn-light" id="toggleFilter">
                    <i class="ti ti-chevron-down"></i>
                </button>
            </div>
            <div class="card-body" id="filterForm" style="display: none;">
                <form method="GET" action="{{ route('keuangan-santri.show', $santri->id) }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label">Periode</label>
                            <select name="periode" id="periodeSelect" class="form-select">
                                <option value="hari_ini" {{ request('periode', 'hari_ini') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                                <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Pilih Bulan</option>
                                <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Pilih Tahun</option>
                                <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                                <option value="semua" {{ request('periode') == 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3" id="bulanInput" style="display: none;">
                            <label class="form-label">Bulan</label>
                            <input type="month" name="bulan" value="{{ request('bulan') }}" class="form-control">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3" id="tahunInput" style="display: none;">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" 
                                   min="2020" max="{{ date('Y') + 1 }}" class="form-control">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3" id="tanggalMulaiInput" style="display: none;">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3" id="tanggalSelesaiInput" style="display: none;">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select name="jenis" class="form-select">
                                <option value="">Semua</option>
                                <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search me-2"></i>Cari
                        </button>
                        <a href="{{ route('keuangan-santri.show', $santri->id) }}" class="btn btn-secondary">
                            <i class="ti ti-refresh me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Transaksi -->
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h5 class="text-white mb-0">
                    <i class="ti ti-history me-2"></i>Riwayat Transaksi - 
                    @if(request('periode') == 'semua')
                        Semua Waktu
                    @elseif(request('periode') == 'minggu_ini')
                        Minggu Ini
                    @elseif(request('periode') == 'bulan_ini')
                        Bulan Ini
                    @elseif(request('periode') == 'tahun_ini')
                        Tahun Ini
                    @elseif(request('periode') == 'bulan')
                        Bulan {{ request('bulan') }}
                    @elseif(request('periode') == 'tahun')
                        Tahun {{ request('tahun') }}
                    @elseif(request('periode') == 'rentang')
                        {{ request('tanggal_mulai') }} s/d {{ request('tanggal_selesai') }}
                    @else
                        Hari Ini
                    @endif
                    ({{ $transactions->total() }} transaksi)
                </h5>
            </div>
            <div class="card-body">
                <!-- Filter Pencarian -->
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <form method="GET" action="{{ route('keuangan-santri.show', $santri->id) }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Periode</label>
                                <select name="periode" class="form-select" id="periodeSelect">
                                    <option value="hari_ini" {{ request('periode', 'hari_ini') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                                    <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                                    <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Pilih Bulan</option>
                                    <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Pilih Tahun</option>
                                    <option value="semua" {{ request('periode') == 'semua' ? 'selected' : '' }}>Semua</option>
                                </select>
                            </div>

                            <!-- Rentang Tanggal -->
                            <div class="col-md-3" id="tanggalMulai" style="display: {{ request('periode') == 'rentang' ? 'block' : 'none' }};">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
                            </div>
                            <div class="col-md-3" id="tanggalSelesai" style="display: {{ request('periode') == 'rentang' ? 'block' : 'none' }};">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control">
                            </div>

                            <!-- Pilih Bulan -->
                            <div class="col-md-3" id="pilihBulan" style="display: {{ request('periode') == 'bulan' ? 'block' : 'none' }};">
                                <label class="form-label">Bulan & Tahun</label>
                                <input type="month" name="bulan" value="{{ request('bulan', date('Y-m')) }}" class="form-control">
                            </div>

                            <!-- Pilih Tahun -->
                            <div class="col-md-3" id="pilihTahun" style="display: {{ request('periode') == 'tahun' ? 'block' : 'none' }};">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" class="form-select">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i>Cari
                                </button>
                                <a href="{{ route('keuangan-santri.show', $santri->id) }}" class="btn btn-secondary">
                                    <i class="ti ti-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 100px;">Tanggal</th>
                                <th style="width: 130px;">Kode</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th style="width: 100px;">Jenis</th>
                                <th style="width: 130px;" class="text-end">Nominal</th>
                                <th style="width: 130px;" class="text-end">Saldo Setelah</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                                <td>
                                    <small>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $transaction->kode_transaksi }}</strong>
                                </td>
                                <td>
                                    {{ $transaction->deskripsi }}
                                    @if($transaction->catatan)
                                        <br><small class="text-muted">{{ Str::limit($transaction->catatan, 40) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->category)
                                        <span class="badge bg-{{ $transaction->category->warna }}" style="color: #fff;">
                                            <i class="{{ $transaction->category->icon }} me-1"></i>
                                            {{ $transaction->category->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Tanpa Kategori</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->jenis === 'pemasukan')
                                        <span class="badge bg-success">
                                            <i class="ti ti-arrow-up me-1"></i> Setor
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="ti ti-arrow-down me-1"></i> Tarik
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong class="{{ $transaction->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->jenis === 'pemasukan' ? '+' : '-' }} 
                                        Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-primary">
                                        Rp {{ number_format($transaction->saldo_sesudah, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-warning btn-edit" 
                                                data-id="{{ $transaction->id }}"
                                                data-santri="{{ $transaction->santri_id }}"
                                                data-tanggal="{{ $transaction->tanggal_transaksi->format('Y-m-d') }}"
                                                data-jenis="{{ $transaction->jenis }}"
                                                data-jumlah="{{ $transaction->jumlah }}"
                                                data-deskripsi="{{ $transaction->deskripsi }}"
                                                data-kategori="{{ $transaction->category_id }}"
                                                data-metode="{{ $transaction->metode_pembayaran }}"
                                                data-catatan="{{ $transaction->catatan }}"
                                                data-bukti="{{ $transaction->bukti_file }}"
                                                title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                                data-id="{{ $transaction->id }}" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="ti ti-info-circle me-2"></i>Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning">
                <h5 class="modal-title text-white" id="editModalLabel">
                    <i class="ti ti-edit me-2"></i>Edit Transaksi Keuangan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditTransaksi" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Santri -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Santri <span class="text-danger">*</span></label>
                                <select name="santri_id" id="edit_santri_id" required class="form-select">
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($santriList ?? [] as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nis }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_transaksi" id="edit_tanggal" required class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Jenis Transaksi -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis" id="edit_pemasukan" value="pemasukan">
                                        <label class="form-check-label" for="edit_pemasukan">
                                            <i class="ti ti-arrow-up text-success"></i> Pemasukan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis" id="edit_pengeluaran" value="pengeluaran">
                                        <label class="form-check-label" for="edit_pengeluaran">
                                            <i class="ti ti-arrow-down text-danger"></i> Pengeluaran
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" id="edit_jumlah" required min="0" step="0.01" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" name="deskripsi" id="edit_deskripsi" required class="form-control">
                    </div>

                    <div class="row">
                        <!-- Kategori -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" id="edit_category_id" class="form-select" style="color: #000;">
                                    <option value="" style="color: #666;">-- Auto Detect --</option>
                                    @foreach($categories as $jenis => $cats)
                                        <optgroup label="{{ ucfirst($jenis) }}" style="color: #000; font-weight: bold;">
                                            @foreach($cats as $cat)
                                                <option value="{{ $cat->id }}" data-jenis="{{ $cat->jenis }}" style="color: #000;">
                                                    {{ $cat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="edit_metode" class="form-select">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet (OVO, GoPay, Dana)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="form-group mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="catatan" id="edit_catatan" rows="2" class="form-control"></textarea>
                    </div>

                    <!-- Bukti File -->
                    <div class="form-group mb-3">
                        <label class="form-label">Upload Bukti Baru (Opsional)</label>
                        <div id="edit_bukti_preview" class="mb-2"></div>
                        <input type="file" name="bukti_file" accept="image/*,application/pdf" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah bukti</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-2"></i>Batal
                    </button>
                    <button type="button" id="btnSubmitEdit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i>Update Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('myscript')
<script>
// Open Edit Modal
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const santriId = this.dataset.santri;
        const tanggal = this.dataset.tanggal;
        const jenis = this.dataset.jenis;
        const jumlah = this.dataset.jumlah;
        const deskripsi = this.dataset.deskripsi;
        const kategori = this.dataset.kategori;
        const metode = this.dataset.metode;
        const catatan = this.dataset.catatan;
        const bukti = this.dataset.bukti;

        // Set form action
        document.getElementById('formEditTransaksi').action = `/keuangan-santri/${id}`;

        // Fill form fields
        document.getElementById('edit_santri_id').value = santriId;
        document.getElementById('edit_tanggal').value = tanggal;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_category_id').value = kategori || '';
        document.getElementById('edit_metode').value = metode || 'Tunai';
        document.getElementById('edit_catatan').value = catatan || '';

        // Set radio button
        if (jenis === 'pemasukan') {
            document.getElementById('edit_pemasukan').checked = true;
        } else {
            document.getElementById('edit_pengeluaran').checked = true;
        }

        // Show existing file
        const previewDiv = document.getElementById('edit_bukti_preview');
        if (bukti) {
            previewDiv.innerHTML = `<small class="text-muted">File saat ini: </small>
                <a href="/storage/${bukti}" target="_blank" class="text-primary">
                    <i class="ti ti-file me-1"></i>Lihat File
                </a>`;
        } else {
            previewDiv.innerHTML = '';
        }

        // Show modal
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});

// Submit Edit with Confirmation
document.getElementById('btnSubmitEdit').addEventListener('click', function() {
    Swal.fire({
        title: 'Konfirmasi Update',
        text: 'Apakah Anda yakin ingin mengupdate transaksi ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditTransaksi').submit();
        }
    });
});

// Delete with Confirmation
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus transaksi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/keuangan-santri/${id}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    });
});
</script>

<script>
// Toggle filter form
document.getElementById('toggleFilter').addEventListener('click', function() {
    const filterForm = document.getElementById('filterForm');
    const icon = this.querySelector('i');
    
    if (filterForm.style.display === 'none') {
        filterForm.style.display = 'block';
        icon.classList.remove('ti-chevron-down');
        icon.classList.add('ti-chevron-up');
    } else {
        filterForm.style.display = 'none';
        icon.classList.remove('ti-chevron-up');
        icon.classList.add('ti-chevron-down');
    }
});

// Toggle form pencarian berdasarkan periode
function togglePeriodeInputs() {
    const periode = document.getElementById('periodeSelect').value;
    
    // Hide all
    document.getElementById('bulanInput').style.display = 'none';
    document.getElementById('tahunInput').style.display = 'none';
    document.getElementById('tanggalMulaiInput').style.display = 'none';
    document.getElementById('tanggalSelesaiInput').style.display = 'none';
    
    // Show based on selection
    if (periode === 'rentang') {
        document.getElementById('tanggalMulaiInput').style.display = 'block';
        document.getElementById('tanggalSelesaiInput').style.display = 'block';
    } else if (periode === 'bulan') {
        document.getElementById('bulanInput').style.display = 'block';
    } else if (periode === 'tahun') {
        document.getElementById('tahunInput').style.display = 'block';
    }
}

document.getElementById('periodeSelect').addEventListener('change', togglePeriodeInputs);

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    togglePeriodeInputs();
    
    // Show filter if any filter is active
    @if(request('periode') != 'hari_ini' || request('jenis'))
        document.getElementById('filterForm').style.display = 'block';
        document.getElementById('toggleFilter').querySelector('i').classList.remove('ti-chevron-down');
        document.getElementById('toggleFilter').querySelector('i').classList.add('ti-chevron-up');
    @endif
});
</script>
@endpush
@endsection
